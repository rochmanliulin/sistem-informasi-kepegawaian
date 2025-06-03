<?php

namespace App\Http\Controllers;

use App\Models\Fingerprint;
use App\Models\History;
use App\Models\Employee;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\FingerprintsImport;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

// import yang baru ditambahkan
use PHPoffice\PhpSpreadsheet\IOFactory; // untuk membaca file excel
use Illuminate\Support\Facades\DB; // untuk query database
use Carbon\Carbon; // untuk manipulasi tanggal dan waktu

class FingerprintController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index(Request $request)
	{
		$search = $request->search;

		if ($search) {
			$fingerprint = Fingerprint::whereHas('employee', function ($query) use ($search) {
				$query->where('nama', 'LIKE', "%$search%");
			})->orWhere('jadwal', 'LIKE', "%$search%")
				->orWhere('tgl', 'LIKE', "%$search%")
				->orWhere('jam_kerja', 'LIKE', "%$search%")
                ->orWhere('scan_masuk', 'LIKE', "%$search%")
				->orWhere('terlambat', 'LIKE', "%$search%")
				->orWhere('scan_istirahat_1', 'LIKE', "%$search%")
				->orWhere('scan_istirahat_2', 'LIKE', "%$search%")
				->orWhere('istirahat', 'LIKE', "%$search%")
				->orWhere('scan_pulang', 'LIKE', "%$search%")
				->orWhere('durasi', 'LIKE', "%$search%")
				->orWhere('lembur_akhir', 'LIKE', "%$search%")
				->paginate(10);
		} else {
			$fingerprint = Fingerprint::paginate(10);
		}

		return view('pages.fingerprint.index', [
			'fingerprint' => $fingerprint,
			'search' => $search
		])->with('page_title', 'Data Fingerprint');
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
			if ($request->hasFile('file')) {
				// Cek extention
                $file = $request->file('file');
				$fileExtension = $request->file('file')->getClientOriginalExtension();

				if (!in_array($fileExtension, ['xlsx', 'xls'])) {
					return back()->with('error', 'Harap unggah file dengan ekstensi .xlsx atau .xls.');
				}

                // Pengecekan data tidak scan pulang
                $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);
                $sheet = $spreadsheet->getActiveSheet();
                $rows = $sheet->toArray();

                $errorList = [];

                foreach ($rows as $index => $row) {
                    if ($index <2) continue; // skip header

                    $nip = $row[5] ?? null; // Asumsi kolom ke-6 adalah NIP
                    $tgl = $row[0] ?? null; // Asumsi kolom ke-1 adalah tanggal
                    $jadwal = $row[1] ?? null; // Asumsi kolom ke-2 adalah jadwal
                    $scanPulang = $row[24] ?? null; // Asumsi kolom ke-25 adalah scan pulang

                    // Abaikan pengecekan jika jadwal adalah Cuti Pribadi atau Tidak Hadir
                    // Tambahkan kondisi lagi jika ada update jadwal libur dikemudian hari
                    if (in_array(strtolower($jadwal), ['cuti pribadi', 'tidak hadir'])) {
                        continue;
                    }

                    if (empty($scanPulang)) {
                        $pegawai = DB::table('employees')->where('nip', $nip)->first();
                        $errorList[] = [
                            'nip' => $nip,
                            'nama' => $pegawai->nama ?? 'Tidak ditemukan',
                            'tanggal' => $tgl,
                        ];
                    }
                }

                // Jika ada pegawai yang tidak scan pulang, tampilkan pesan error dan batalkan import
                if (!empty($errorList)) {
                    return back()
                        ->with('error_data', $errorList)
                        ->with('error', 'Import gagal karena ada pegawai yang tidak scan pulang.');
                }

				// Hapus data
				Fingerprint::truncate();

				// Create data
				Excel::import(new FingerprintsImport, $request->file('file'));
			}

			return redirect('/fingerprint')->with('success', 'Berhasil upload');
		} catch (\Exception $e) {
            // Jika terjadi kesalahan, tampilkan pesan error dan batalkan import
			return back()->with('error', 'Gagal import : ' . $e->getMessage());
		}
	}

	/**
	 * Show the form for editing the specified resource.
	 */
	public function edit(string $id)
	{
		$fingerprint = Fingerprint::where('id', $id)->first();
		$employee = Employee::where('nip', $fingerprint->nip)->first();

		return view('pages.fingerprint.edit', [
			'fingerprint' => $fingerprint,
			'employee' => $employee
		]);
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(Request $request, string $id)
	{
		$userId = auth()->user()->id;

		$validated = $request->validate([
			'jadwal' => 'required',
			'tgl' => 'required',
			'jam_kerja' => 'nullable',
            'scan_masuk' => 'nullable|date_format:H:i:s', // Boleh kosong, tetapi jika diisi harus dalam format jam:menit:detik (contoh: 14:30:00)
			'terlambat' => 'required',
			'scan_istirahat_1' => 'nullable|date_format:H:i:s', // Boleh kosong, tetapi jika diisi harus dalam format jam:menit:detik (contoh: 14:30:00)
			'scan_istirahat_2' => 'nullable|date_format:H:i:s', // Boleh kosong, tetapi jika diisi harus dalam format jam:menit:detik (contoh: 14:30:00)
			'istirahat' => 'required',
			'scan_pulang' => 'nullable|date_format:H:i:s', // Boleh kosong, tetapi jika diisi harus dalam format jam:menit:detik (contoh: 14:30:00)
			'durasi' => 'required',
			'lembur_akhir' => 'required'
		]);

		try {
			$validated['updated_by'] = $userId;
			$fingerprint = Fingerprint::where("id", $id)->first();

			// Cek apakah data yang diberikan sama dengan data yang ada dalam database
			if ($fingerprint->jadwal == $validated['jadwal'] &&
					$fingerprint->tgl == $validated['tgl'] &&
					$fingerprint->jam_kerja == $validated['jam_kerja'] &&
                    $fingerprint->scan_masuk == $validated['scan_masuk'] &&
					$fingerprint->terlambat == $validated['terlambat'] &&
					$fingerprint->scan_istirahat_1 == $validated['scan_istirahat_1'] &&
					$fingerprint->scan_istirahat_2 == $validated['scan_istirahat_2'] &&
					$fingerprint->istirahat == $validated['istirahat'] &&
					$fingerprint->scan_pulang == $validated['scan_pulang'] &&
					$fingerprint->durasi == $validated['durasi'] &&
					$fingerprint->lembur_akhir == $validated['lembur_akhir']) {

				return redirect('/fingerprint')->with('error', 'Tidak Ada Perubahan');
			}

			$fingerprint->update($validated);

			return redirect('/fingerprint')->with('success', 'Berhasil Update');
		} catch (\Exception $e) {
			return back()->with('error', 'Gagal mengupdate : ' . $e->getMessage());
		}
	}
}
