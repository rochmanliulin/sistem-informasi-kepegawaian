<?php

namespace App\Http\Controllers;

use App\Models\MonthlySalary;
use App\Models\Employee;
use App\Models\Allowance;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MonthlySalaryExport;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class MonthlySalaryController extends Controller
{
    public function index()
    {
        return view('pages.salary.monthly', [
            'page_title' => 'Gaji Bulanan'
        ]);
    }

    public function process(Request $request)
    {
        $request->validate([
            'bulan' => 'required|numeric|min:1|max:12',
            'tahun' => 'required|numeric|min:2000',
            'tanggal_terbit' => 'required|date',
            'jumlah_hari_kerja' => 'required|numeric|min:1',
        ]);

        DB::beginTransaction();
        try {
            $bulan = $request->bulan;
            $tahun = $request->tahun;
            $jumlahHariKerja = $request->jumlah_hari_kerja;
            $tanggalTerbit = $request->tanggal_terbit;

            $employees = Employee::all();

            foreach ($employees as $employee) {
                $allowance = Allowance::where('nip', $employee->nip)->first();

                if (!$allowance) {
                    \Log::warning('Allowance tidak ditemukan untuk NIP: ' . $employee->nip);
                    continue;
                }

                // Inisialisasi variabel tunjangan
                $masukPagi = 0;
                $uangMakan = 0;
                $premiHadir = 0;
                $hariKerjaAktif = 0;

                $fingerprints = $employee->fingerprints()
                    ->whereMonth('tgl', $bulan)
                    ->whereYear('tgl', $tahun)
                    ->get();

                foreach ($fingerprints as $fp) {
                    if ($fp->scan_masuk && $fp->scan_pulang) { 
                        $jamMasuk = Carbon::parse($fp->scan_masuk);
                        $jamPulang = Carbon::parse($fp->scan_pulang);
                        $durasiMenit = $jamMasuk->diffInMinutes($jamPulang);

                        if ($durasiMenit >= 360) {
                            $uangMakan += 20000;
                        }

                        $hariKerjaAktif++;
                    }
                }

                if ($hariKerjaAktif == $jumlahHariKerja) {
                    $masukPagi = 300000;
                    $premiHadir = 300000;
                } else {
                    $masukPagi = 0;
                    $premiHadir = 0;
                }

                $totalGaji = (
                    $allowance->gaji + $allowance->kos + $allowance->prestasi + $allowance->komunikasi +
                    $allowance->jabatan + $allowance->lain_lain + $masukPagi + $uangMakan + $allowance->kasbon +
                    $premiHadir + $allowance->doa
                );

                MonthlySalary::updateOrCreate(
                    [
                        'nip' => $employee->nip,
                        'month' => $bulan,
                        'year' => $tahun,
                    ],
                    [
                        'gaji' => $allowance->gaji,
                        'kos' => $allowance->kos,
                        'masuk_pagi' => $masukPagi,
                        'prestasi' => $allowance->prestasi,
                        'komunikasi' => $allowance->komunikasi,
                        'jabatan' => $allowance->jabatan,
                        'lain_lain' => $allowance->lain_lain,
                        'uang_makan' => $uangMakan,
                        'kasbon' => $allowance->kasbon,
                        'premi_hadir' => $premiHadir,
                        'doa' => $allowance->doa ?? 0,
                        'total_gaji' => $totalGaji,
                        'tanggal_terbit' => $tanggalTerbit,
                        'jumlah_hari_kerja' => $jumlahHariKerja,
                        'jumlah_hari_kerja_aktif' => $hariKerjaAktif,
                    ]
                );

                \Log::info("MonthlySalary updated for NIP: {$employee->nip}, Bulan: $bulan, Tahun: $tahun");
            }

            DB::commit();
            return redirect('/salary/monthly')->with('success', 'Proses gaji bulanan berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Gagal memproses gaji bulanan: ' . $e->getMessage());
            return back()->with('error', 'Gagal memproses gaji: ' . $e->getMessage());
        }
    }

    public function export(Request $request)
    {
        $request->validate([
            'bulan' => 'required|numeric|min:1|max:12',
            'tahun' => 'required|numeric|min:2000'
        ]);

        try {
            $bulan = $request->bulan;
            $tahun = $request->tahun;
            $namaBulan = Carbon::createFromFormat('!m', $bulan)->format('F');

            return Excel::download(
                new MonthlySalaryExport($bulan, $tahun),
                'Laporan_Gaji_Bulan_' . $namaBulan . '_' . $tahun . '.xlsx'
            );
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal export file: ' . $e->getMessage());
        }
    }
}
