<?php

namespace App\Http\Controllers;

use App\Models\Allowance;
use App\Models\Employee;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\AllowancesImport;
use Illuminate\Support\Facades\Auth;

class AllowanceController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index(Request $request)
	{
		$search = $request->search;
		
		// Jika user menggunakan search filter
		if ($search) {
			$allowance = Allowance::whereHas('employee', function ($query) use ($search) {
																$query->where('nama', 'LIKE', "%$search%");
															})->paginate(10);
		} else {
			$allowance = Allowance::with('employee')->join('employees', 'allowances.nip', '=', 'employees.nip')
															->select('allowances.*')
															->orderBy('nama')
															->paginate(10);
		}

		return view('pages.allowance.index', [
			'allowance' => $allowance,
			'search' => $search
		])->with('page_title', 'Data Tunjangan');
	}

	/**
	 * Fitur Import Excel
	 */
	public function import(Request $request)
	{
		$request->validate([
			'file' => 'required|mimes:xlsx,xls',
		]);

    // Exception
		try {
			// Create data
			if ($request->hasFile('file')) {
				// Cek extention
				$fileExtension = $request->file('file')->getClientOriginalExtension();

				if (!in_array($fileExtension, ['xlsx', 'xls'])) {
					return back()->with('error', 'Harap unggah file dengan ekstensi .xlsx atau .xls.');
				}

				Excel::import(new AllowancesImport, $request->file('file'));
			}

			return redirect('/allowance')->with('success', 'Berhasil upload');
		} catch (\Exception $e) {
			return redirect('/allowance')->with('error', 'Format file tidak sesuai!');
		}
	}

	/**
	 * Show the form for creating a new resource.
	 */
	public function create()
	{
		$employee = Employee::doesntHave('allowance')->orderBy('nama')->get();

		return view('pages.allowance.create', [
			'employee' => $employee
		]);
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(Request $request)
	{
		$userId = auth()->user()->id;

		$request->validate([
			'nip' => 'required',
			'status' => 'required'
		]);

		if ($request->status === 'Pegawai Harian') {
			$validated = $request->validate([
				'nip' => 'required',
				'gaji_harian' => 'required',
				'premi_hadir_harian' => 'nullable'
			]);

			// Konversi sesuai format database
			$validated['gaji'] = $validated['gaji_harian'];
			$validated['premi_hadir'] = $validated['premi_hadir_harian'];

			// Hapus data yang telah dipindahkan
			unset($validated['gaji_harian']);
			unset($validated['premi_hadir_harian']);

		} else if ($request->status === 'Pegawai Kontrak') {
			$validated = $request->validate([
				'nip' => 'required',
				'gaji_bulanan' => 'nullable',
				'kos' => 'nullable',
				'masuk_pagi' => 'nullable',
				'prestasi' => 'nullable',
				'komunikasi' => 'nullable',
				'jabatan' => 'nullable',
				'lain_lain' => 'nullable',
				'uang_makan' => 'nullable',
				'kasbon' => 'nullable',
				'premi_hadir_bulanan' => 'nullable',
				'premi_lembur' => 'nullable',
				'doa' => 'nullable',
			]);

			// Konversi sesuai format database
			$validated['gaji'] = $validated['gaji_bulanan'];
			$validated['premi_hadir'] = $validated['premi_hadir_bulanan'];

			// Hapus data yang telah dipindahkan
			unset($validated['gaji_bulanan']);
			unset($validated['premi_hadir_bulanan']);
		} else {
			return redirect('/allowance')->with('error', Allowance::where('nip', $request->nip)->first()->employee->nama . ' tidak memiliki status');
		}

		try {
			$validated['created_by'] = $userId;
			// Create data
			Allowance::create($validated);
			
			return redirect('/allowance')->with('success', 'Berhasil Ditambahkan');
		} catch (\Exception $e) {
      return back()->with('error', 'Gagal menambahkan: ' . $e->getMessage());
		}
	}

	/**
	 * Show the form for editing the specified resource.
	 */
	public function edit(string $id)
	{
		$allowance = Allowance::where('nip', $id)->first();
		$employee = Employee::where('nip', $id)->first();
		
		if ($employee->status === 'Pegawai Harian') {
			// Konversi sesuai format database
			$allowance['gaji_harian'] = $allowance['gaji'];
			$allowance['premi_hadir_harian'] = $allowance['premi_hadir'];

			// Hapus data yang telah dipindahkan
			unset($allowance['gaji']);
			unset($allowance['premi_hadir']);

		} else if ($employee->status === 'Pegawai Kontrak') {
			// Konversi sesuai format database
			$allowance['gaji_bulanan'] = $allowance['gaji'];
			$allowance['premi_hadir_bulanan'] = $allowance['premi_hadir'];

			// Hapus data yang telah dipindahkan
			unset($allowance['gaji']);
			unset($allowance['premi_hadir']);
		}

		return view('pages.allowance.edit', [
			'allowance' => $allowance,
			'employee' => $employee,
		]);
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(Request $request, string $id)
	{
		$userId = auth()->user()->id;

		$request->validate([
			'nip' => 'required',
			'status' => 'required'
		]);

		if ($request->status === 'Pegawai Harian') {
			$validated = $request->validate([
				'nip' => 'required',
				'gaji_harian' => 'required',
				'premi_hadir_harian' => 'nullable'
			]);

		// Konversi sesuai format database
			$validated['gaji'] = $validated['gaji_harian'];
			$validated['premi_hadir'] = $validated['premi_hadir_harian'];

			// Hapus data yang telah dipindahkan
			unset($validated['gaji_harian']);
			unset($validated['premi_hadir_harian']);

		} else if ($request->status === 'Pegawai Kontrak') {
			$validated = $request->validate([
				'nip' => 'required',
				'gaji_bulanan' => 'nullable',
				'kos' => 'nullable',
				'masuk_pagi' => 'nullable',
				'prestasi' => 'nullable',
				'komunikasi' => 'nullable',
				'jabatan' => 'nullable',
				'lain_lain' => 'nullable',
				'uang_makan' => 'nullable',
				'kasbon' => 'nullable',
				'premi_hadir_bulanan' => 'nullable',
				'premi_lembur' => 'nullable',
				'doa' => 'nullable',
			]);

		// Konversi sesuai format database
			$validated['gaji'] = $validated['gaji_bulanan'];
			$validated['premi_hadir'] = $validated['premi_hadir_bulanan'];

			// Hapus data yang telah dipindahkan
			unset($validated['gaji_bulanan']);
			unset($validated['premi_hadir_bulanan']);
		} else {
			return redirect('/allowance')->with('error', 'Tunjangan NIP:' . $request->nip . ' terjadi error');
		}

		try {
			$validated['updated_by'] = $userId;
			$allowance = Allowance::where("nip", $id)->first();

			if ($request->status === 'Pegawai Harian') {
					// Cek apakah data yang diberikan sama dengan data yang ada dalam database
					if ($allowance->gaji == $validated['gaji'] &&
							$allowance->premi_hadir == $validated['premi_hadir']) {
							return redirect('/allowance')->with('error', 'Tidak Ada Perubahan');
					}
			} else if ($request->status === 'Pegawai Kontrak') {
					// Cek apakah data yang diberikan sama dengan data yang ada dalam database
					if ($allowance->gaji == $validated['gaji'] &&
							$allowance->premi_hadir == $validated['premi_hadir'] &&
							$allowance->kos == $validated['kos'] &&
							$allowance->masuk_pagi == $validated['masuk_pagi'] &&
							$allowance->prestasi == $validated['prestasi'] &&
							$allowance->komunikasi == $validated['komunikasi'] &&
							$allowance->jabatan == $validated['jabatan'] &&
							$allowance->lain_lain == $validated['lain_lain'] &&
							$allowance->uang_makan == $validated['uang_makan'] &&
							$allowance->kasbon == $validated['kasbon'] &&
							$allowance->premi_lembur == $validated['premi_lembur'] &&
							$allowance->doa == $validated['doa']) {

						return redirect('/allowance')->with('error', 'Tidak Ada Perubahan');
					}
			}

			$allowance->update($validated);

			return redirect('/allowance')->with('success', 'Berhasil Update');
		} catch (\Exception $e) {
			return back()->with('error', 'Gagal mengupdate : ' . $e->getMessage());
		}	
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(string $id)
	{
		try {
			$allowance = Allowance::where('nip', $id)->first();
			$allowance->delete();
	
			// strtolower() -> Convert ke lower case
			// ucwords() -> Convert capital case
			return back()->with('success', 'Tunjangan ' . ucwords(strtolower($allowance->employee->nama)) . ' telah dihapus!');
		} catch (\Exception $e) {
			return back()->with('error', 'Gagal menghapus : ' . $e->getMessage());
		}
	}
}
