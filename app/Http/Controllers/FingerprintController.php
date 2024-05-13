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
				->orWhere('terlambat', 'LIKE', "%$search%")
				->orWhere('scan_istirahat_1', 'LIKE', "%$search%")
				->orWhere('scan_istirahat_2', 'LIKE', "%$search%")
				->orWhere('istirahat', 'LIKE', "%$search%")
				->orWhere('durasi', 'LIKE', "%$search%")
				->orWhere('lembur_akhir', 'LIKE', "%$search%")
				->paginate(10);
		} else {
			$fingerprint = Fingerprint::paginate(10);
		}

		return view('pages.fingerprint.index', [
			'fingerprint' => $fingerprint,
			'search' => $search
		])->with('page_title', 'Data Fingerprint Lembur');
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
				$fileExtension = $request->file('file')->getClientOriginalExtension();

				if (!in_array($fileExtension, ['xlsx', 'xls'])) {
					return back()->with('error', 'Harap unggah file dengan ekstensi .xlsx atau .xls.');
				}

				// Hapus data
				Fingerprint::truncate();

				// Disable logging -> menonaktifkan log activity
				activity()->disableLogging();

				// Create data
				Excel::import(new FingerprintsImport, $request->file('file'));
			}

			// Enable logging -> mengaktifkan kembali log activity
			activity()->enableLogging();

			// Log activity
			$user = Auth::user();
			activity('Fingerprint')
				->event('imported')
				->performedOn(new Fingerprint())
				->withProperties(['attributes' => ['nama' => $user->fullname]])
				->log("imported fingerprint {$request->file('file')->getClientOriginalName()}");

			return redirect('/fingerprint')->with('success', 'Berhasil upload');
		} catch (\Exception $e) {
			return back()->with('error', 'Format isi file tidak sesuai');
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
		$validated = $request->validate([
			'jadwal' => 'required',
			'tgl' => 'required',
			'jam_kerja' => 'required',
			'terlambat' => 'required',
			'scan_istirahat_1' => 'nullable',
			'scan_istirahat_2' => 'nullable',
			'istirahat' => 'required',
			'durasi' => 'required',
			'lembur_akhir' => 'required'
		]);

		try {
			Fingerprint::where('id', $id)->update($validated);

			return redirect('/fingerprint')->with('success', 'Berhasil update');
		} catch (\Exception $e) {
			return back()->with('error', 'Gagal mengupdate : ' . $e->getMessage());
		}
	}
}
