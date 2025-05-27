<?php

namespace App\Http\Controllers;

use App\Models\MonthlySalary;
use App\Models\Employee;
use App\Models\Allowance;
use App\Models\Fingerprint;
use App\Mail\MonthlySalaryMail;
use App\Exports\MonthlySalaryExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Notifications\MonthlySalarySlip;

class MonthlySalaryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        // Ambil distinct month dan year
        $info = MonthlySalary::select('month', 'year', DB::raw("DATE_FORMAT(tanggal_terbit, '%d %M %Y') as formatted_tgl_terbit"))
            ->distinct()
            ->get()
            ->map(function ($item) {
                return [
                    'label' => 'Slip Gaji Bulan ' . Carbon::create()->month($item->month)->translatedFormat('F') . ' Tahun ' . $item->year . ' (' . $item->formatted_tgl_terbit . ')',
                    'value' => $item->month . '-' . $item->year
                ];
            })
            ->toArray();

        if ($search) {
            $monthlySalary = MonthlySalary::whereHas('employee', function ($query) use ($search) {
                $query->where('nama', 'LIKE', "%$search%");
            })
                ->orWhere('keterangan', 'LIKE', "%$search%")
                ->orWhere('tgl_terbit', 'LIKE', "%$search%")
                ->paginate(10);
        } else {
            $monthlySalary = MonthlySalary::orderBy('created_at', 'desc')
                ->orderBy('nip', 'asc')
                ->paginate(10);
        }

        return view('pages.salary.monthly', [
            'page_title' => 'Gaji Bulanan',
            'search' => $search,
            'info' => $info,
            'monthlySalary' => $monthlySalary
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

                $keterangan = 'Slip Gaji Bulan ' . Carbon::create()->month($bulan)->translatedFormat('F') . ' Tahun ' . $tahun;
                \Log::info('Keterangan yang disimpan: ' . $keterangan); // Cek log

                // Inisialisasi variabel tunjangan
                $masukPagi = 0;
                $uangMakan = 0;
                $premiHadir = 0;
                $hariKerjaAktif = 0;

                $fingerprints = $employee->fingerprint()
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
                        'gaji' => $allowance->gaji ?? 0,
                        'kos' => $allowance->kos ?? 0,
                        'masuk_pagi' => $masukPagi ?? 0,
                        'prestasi' => $allowance->prestasi ?? 0,
                        'komunikasi' => $allowance->komunikasi ?? 0,
                        'jabatan' => $allowance->jabatan ?? 0,
                        'lain_lain' => $allowance->lain_lain ?? 0,
                        'uang_makan' => $uangMakan ?? 0,
                        'kasbon' => $allowance->kasbon ?? 0,
                        'premi_hadir' => $premiHadir ?? 0,
                        'doa' => $allowance->doa ?? 0,
                        'total_gaji' => $totalGaji ?? 0,
                        'keterangan' => $keterangan,
                        'tanggal_terbit' => $tanggalTerbit,
                        'jumlah_hari_kerja' => $jumlahHariKerja,
                        'jumlah_hari_kerja_aktif' => $hariKerjaAktif,
                    ]
                );

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

    // Kirim slip lembur ke karyawan
    public function downloadSlip($id)
    {
        $data = MonthlySalary::with('employee')->where('id', $id)->get();

        $pdf = Pdf::loadView('pages.user.pdf', compact('data'));

        return $pdf->download('Slip_Gaji_Bulanan_'.$data[0]->employee->nama.'.pdf');
    }

    // Kirim slip gaji bulanan ke karyawan
    public function sendMonthlySalarySlips()
    {
        $salaries = MonthlySalary::with('employee')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->get();

        foreach ($salaries as $salary) {
            if ($salary->employee && $salary->employee->email) {
                $salary->employee->notify(new MonthlySalarySlip($salary));
            }
        }

        return back()->with('success', 'Semua slip gaji bulanan berhasil dikirim!');
    }
}
