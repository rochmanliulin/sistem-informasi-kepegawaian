<?php

namespace App\Http\Controllers;

use App\Exports\OvertimeSalaryExport;
use Illuminate\Http\Request;
use App\Models\Allowance;
use App\Models\Fingerprint;
use App\Models\OvertimeSalary;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class OvertimeSalaryController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index(Request $request)
	{
		$search = $request->search;
		$info = OvertimeSalary::distinct()->pluck('keterangan')->toArray();

		if ($search) {
			$overtimeSalary = OvertimeSalary::whereHas('employee', function ($query) use ($search) {
																					$query->where('nama', 'LIKE', "%$search%");
																				})
																				->orWhere('keterangan', 'LIKE', "%$search%")
																				->orWhere('tgl_terbit', 'LIKE', "%$search%")
																				->paginate(10);
		} else {
			$overtimeSalary = OvertimeSalary::paginate(10);
		}

		return view('pages.salary.index_overtime', [
			'overtimeSalary' => $overtimeSalary,
			'search' => $search,
			'info' => $info
		])->with('page_title', 'Gaji Lembur');
	}

	public function export(Request $request)
	{
		$request->validate([
			'info' => 'required'
		]);

		$info = $request->info;

		try {
      // Log activity
      $user = Auth::user();
      activity('Overtime Salary')
        ->event('exported')
        ->performedOn(new OvertimeSalary())
        ->withProperties(['attributes' => ['nama' => $user->fullname]])
        ->log("exported overtime salary {$info}.xlsx");
      
      return Excel::download(new OvertimeSalaryExport($info), 'Gaji ' . $info . '.xlsx');
    } catch (\Exception $e) {
      return back()->with('error', 'Gagal export file : ' . $e->getMessage());
    }
	}

	/**
	 * Process hasil inputan berdasarkan tanggal
	 */
	public function process(Request $request)
	{
		$request->validate([
			'range_tgl' => 'required|string',
			'tgl_terbit' => 'required|string'
		]);

		// Parse range tanggal
		$dates = explode(' to ', $request->range_tgl);
		$startDate = $dates[0];
		$endDate = $dates[1];

		// Tanggal terbit
		$tglTerbit = $request->tgl_terbit;

		// Field Keterangan
		$keterangan = 'Slip Lembur ' . date('j', strtotime($startDate)) . '-' . date('j F Y', strtotime($endDate));

		// Array asosiatif untuk menyimpan data per NIP
		$totalDataPerNIP = [];

		// Logika 2 tapi lebih lambat dan jika file fingerprint tidak diproses akan tetapi lebih aman
		// Query untuk mendapatkan data fingerprint berdasarkan range tanggal
		$fingerprintQuery = Fingerprint::whereBetween('tgl', [$startDate, $endDate])->select('nip', 'jadwal', 'jam_kerja', 'terlambat', 'scan_istirahat_1', 'scan_istirahat_2', 'istirahat', 'durasi', 'lembur_akhir');
		$dataFingerprint = $fingerprintQuery->get();

		// Olah data  doa, uang makan, kopi, lembur, dan lembur hari minggu
		foreach ($dataFingerprint as $data) {
			$nip = $data->nip;
			$jamKerja = $data->jam_kerja;
			$jadwal = $data->jadwal;
			$durasi = $data->durasi;
			$terlambat = $data->terlambat;
			$scanIstirahatMasuk = $data->scan_istirahat_1;
			$scanIstirahatKembali = $data->scan_istirahat_2;
			$istirahat = $data->istirahat;
			$uangLembur = 0;
			$uangMakan = 0;
			$uangKopi = 0;
			$uangLemburMinggu = 0;
			
			// Inisialisasi data jika NIP belum ada di array totalDataPerNIP
			if (!isset($totalDataPerNIP[$nip])) {
				$totalDataPerNIP[$nip] = [
					'total_uang_lembur' => 0,
					'doa' => 0,
					'premi' => 0,
					'gaji' => 0,
					'total_uang_kopi' => 0,
					'total_uang_lembur_minggu' => 0,
					'total_uang_makan' => 0,
					'total' => 0,
					'hari_aktif' => 0,
					'total_jam_lembur' => 0,
					'hari_terlambat' => 0,
					'total_terlambat' => 0,
					'tidak_istirahat' => 0,
					'tidak_istirahat_masuk' => 0,
					'tidak_istirahat_kembali' => 0,
					'lebih_istirahat' => 0
				];
			}

			// Hitung uang lembur dan uang makan
			if ($jadwal !== 'Lembur') {
				$uangLembur = $data->calculateUangLembur();
				$uangMakan = $data->calculateUangMakan();
			}

			// Hitung uang kopi
			if ((stripos($jamKerja, 'packing') !== false) && (preg_match('/\b[23]\b/', $jamKerja))) {
				$uangKopi = 10000;
			}

			// Hitung uang lembur hari minggu
			if ($jadwal == 'Lembur') {
				$uangLemburMinggu = ($durasi / 60) * 20000;
			}

			// Terlambat
			if ($terlambat !== 0) {
				$totalDataPerNIP[$nip]['total_terlambat'] += $terlambat;
				$totalDataPerNIP[$nip]['hari_terlambat']++;
			}

			if ($jamKerja !== 'Libur Rutin' && $jamKerja !== 'NORMAL SIANG SABTU') {
				// Tidak check lock istirahat
				if ($scanIstirahatMasuk === null && $scanIstirahatKembali === null) {
					$totalDataPerNIP[$nip]['tidak_istirahat']++;
				} 

				// Tidak check lock masuk
				if ($scanIstirahatMasuk === null) {
					$totalDataPerNIP[$nip]['tidak_istirahat_masuk']++;
				}

				// Tidak check lock kembali
				if ($scanIstirahatKembali === null) {
					$totalDataPerNIP[$nip]['tidak_istirahat_kembali']++;
				}
			}

			// Waktu lebih istirahat
			if ($jamKerja === 'SENIN-KAMIS REG' || $jamKerja === 'NORMAL SIANG SENIN-KAMIS' || $jamKerja === 'NORMAL SIANG JUMAT' || $jamKerja === 'SENIN-SABTU ADMIN' || $jamKerja === 'SENIN-KAMIS HARIAN' || $jamKerja === 'SABTU HARIAN' || $jamKerja === 'JADWAL PACKING HARIAN SHIFT 2 SABTU' || $jamKerja === 'JADWAL PACKING SHIFT 1 SENIN-KAMIS' || $jamKerja === 'JADWAL PACKING SHIFT 1 SABTU' || $jamKerja === 'JADWAL PACKING HARIAN SHIFT 1 SENIN-KAMIS' || $jamKerja === 'JADWAL PACKING HARIAN SHIFT 1 SENIN-KAMIS' || $jamKerja === 'GUDANG SENIN-KAMIS SHIFT 2' || $jamKerja === 'JADWAL PACKING HARIAN SHIFT 1 SABTU' || $jamKerja === "JADWAL PACKING SHIFT 3 SENIN-JUM'AT" || $jamKerja === "JUM'AT HARIAN SHIFT 2" || $jamKerja === 'SENIN-KAMIS HARIAN SHIFT 2' || $jamKerja === 'JADWAL PACKING SHIFT 2 SABTU BARU 2' || $jamKerja === 'JADWAL PACKING SHIFT 3 SABTU BARU' || $jamKerja === 'PACKING SHIFT 2 SENIN-JUMAT BARU 2' || $jamKerja === 'PACKING SHIFT 2 SABTU BARU') {
				if ($istirahat >= 60) {
					$totalDataPerNIP[$nip]['lebih_istirahat'] += $istirahat - 60;
				} else {
					$totalDataPerNIP[$nip]['lebih_istirahat'] += 0;
				}
			} else if ($jamKerja === 'JUMAT REG' || $jamKerja === 'JUMAT ADMIN' || $jamKerja === 'JUMAT HARIAN' || $jamKerja === "JADWAL PACKING SHIFT 1 JUM'AT" ) {
				if ($istirahat >= 90) {
					$totalDataPerNIP[$nip]['lebih_istirahat'] += $istirahat - 90;
				} else {
					$totalDataPerNIP[$nip]['lebih_istirahat'] += 0;
				}
			} else if ($jamKerja === 'SABTU REG') {
				if ($istirahat >= 30) {
					$totalDataPerNIP[$nip]['lebih_istirahat'] += $istirahat - 30;
				} else {
					$totalDataPerNIP[$nip]['lebih_istirahat'] += 0;
				}
			}

			// Akumulasi
			$totalDataPerNIP[$nip]['total_uang_lembur'] += $uangLembur;
			$totalDataPerNIP[$nip]['total_uang_makan'] += $uangMakan;
			$totalDataPerNIP[$nip]['total_uang_kopi'] += $uangKopi;
			$totalDataPerNIP[$nip]['total_uang_lembur_minggu'] += $uangLemburMinggu;
		}
		
		// Query untuk mendapatkan jumlah hari aktif per NIP
		$hariAktifQuery = Fingerprint::whereBetween('tgl', [$startDate, $endDate])
			->whereNotIn('jam_kerja', ['Libur Rutin', 'Tidak Hadir', ''])
			// ->whereNotIn('durasi', [0])
			->selectRaw('nip, COUNT(*) as total_hari_aktif')
			->groupBy('nip');
		$hariAktif = $hariAktifQuery->get();

		// Hitung hari aktif per NIP
		foreach ($hariAktif as $data) {
			$nip = $data->nip;
			$totalHariAktif = $data->total_hari_aktif;

			if (isset($totalDataPerNIP[$nip])) {
				$totalDataPerNIP[$nip]['hari_aktif'] = $totalHariAktif;
			}
		}
		
		// Query untuk mendapatkan syarat premi lembur dan hadir per NIP
		$syaratPremiLemburQuery = Fingerprint::whereBetween('tgl', [$startDate, $endDate])->select('nip', 'jadwal', 'jam_kerja', 'lembur_akhir');
    $syaratPremiLembur = $syaratPremiLemburQuery->get();
		
		// Olah data premi hadir, lembur, dan gaji
		foreach ($syaratPremiLembur as $data) {
			$nip = $data->nip;
			$jadwal = $data->jadwal;
			$jamKerja = $data->jam_kerja;
			$durasiLembur = $data->lembur_akhir;

			if (isset($totalDataPerNIP[$nip])) {
				// Inisialisasi nilai default syarat premi lembur
				if (!isset($totalDataPerNIP[$nip]['syarat_premi_lembur'])) {
					$totalDataPerNIP[$nip]['syarat_premi_lembur'] = 0;
				}

				// Logika premi
				if ($totalDataPerNIP[$nip]['hari_aktif'] === 6 && $jadwal !== 'Lembur') {
					// Syarat mendapatkan premi lembur
					if (strpos($jamKerja, 'Sabtu') === false && $durasiLembur >= 120) {
						$totalDataPerNIP[$nip]['syarat_premi_lembur']++;
					} else if (strpos($jamKerja, 'Sabtu') !== false && $durasiLembur >= 180) {
						$totalDataPerNIP[$nip]['syarat_premi_lembur']++;
					}
				}

				if ($durasiLembur !== 0) {
					$totalDataPerNIP[$nip]['total_jam_lembur'] += $durasiLembur / 60;
				}
			}
		}
		
		$premi = Allowance::all();
		
		foreach ($premi as $data) {
			$nip = $data->nip;
			$doa = $data->doa;
			$premiHadir  = $data->premi_hadir;
			$premiLembur = $data->premi_lembur;
			$gaji = $data->gaji;
			
			if (isset($totalDataPerNIP[$nip])) {
				// Update data doa
				if (!is_null($doa)) {
					$totalDataPerNIP[$nip]['doa'] = $doa;
				}

				// Update data gaji
				if (!is_null($gaji)) {
					$totalDataPerNIP[$nip]['gaji'] = $gaji * $totalDataPerNIP[$nip]['hari_aktif'];
				}
				
				if ($totalDataPerNIP[$nip]['hari_aktif'] === 6) {
					// Premi hadir
					if (!is_null($premiHadir)) {
						$totalDataPerNIP[$nip]['premi'] = $premiHadir;
					}
					
					// Premi lembur
					if ($totalDataPerNIP[$nip]['syarat_premi_lembur'] == 6 && !is_null($premiLembur)) {
						$totalDataPerNIP[$nip]['premi'] = $premiLembur;
					}
				}
				
				// Hitung total
				$totalDataPerNIP[$nip]['total'] = $totalDataPerNIP[$nip]['total_uang_lembur'] + $totalDataPerNIP[$nip]['doa'] +
																					$totalDataPerNIP[$nip]['premi'] + $totalDataPerNIP[$nip]['gaji'] + $totalDataPerNIP[$nip]['total_uang_kopi'] + $totalDataPerNIP[$nip]['total_uang_lembur_minggu'] + $totalDataPerNIP[$nip]['total_uang_makan'];
			}
		}

		// Disable logging -> menonaktifkan log activity
		activity()->disableLogging();

		// Create Data
		foreach ($totalDataPerNIP as $nip => $data) {
			OvertimeSalary::updateOrCreate(
				['nip' => $nip, 'keterangan' => $keterangan, 'tgl_terbit' => $tglTerbit],
				[
					'nip' => $nip,
					'total_uang_lembur' => (int) $data['total_uang_lembur'],
					'doa' => $data['doa'],
					'premi' => $data['premi'],
					'gaji' => $data['gaji'],
					'total_uang_kopi' => $data['total_uang_kopi'],
					'total_uang_lembur_minggu' => $data['total_uang_lembur_minggu'],
					'total_uang_makan' => $data['total_uang_makan'],
					'total' => (int) $data['total'],
					'keterangan' => $keterangan,
					'hari_aktif' => $data['hari_aktif'],
					'total_jam_lembur' => $data['total_jam_lembur'],
					'tgl_terbit' => $tglTerbit,
					'hari_terlambat' => $data['hari_terlambat'],
					'total_terlambat' => $data['total_terlambat'],
					'tidak_istirahat' => $data['tidak_istirahat'],
					'tidak_istirahat_masuk' => $data['tidak_istirahat_masuk'],
					'tidak_istirahat_kembali' => $data['tidak_istirahat_kembali'],
					'lebih_istirahat' => $data['lebih_istirahat'],
				]
			);
		}

		// Enable logging -> mengaktifkan kembali log activity
		activity()->enableLogging();

		// Log activity
		$user = Auth::user();
		activity('Overtime Salary')
			->event('processed')
			->performedOn(new OvertimeSalary())
			->withProperties(['attributes' => ['nama' => $user->fullname]])
			->log("processed overtime salary {$keterangan}");

		return redirect('/salary/overtime')->with('success', 'Berhasil memproses ' . $keterangan);
	}
}