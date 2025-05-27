<?php

namespace App\Exports;

use App\Models\MonthlySalary;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MonthlySalaryExport implements FromCollection, WithHeadings
{
    protected $bulan;
    protected $tahun;

    public function __construct($bulan, $tahun)
    {
        $this->bulan = $bulan;
        $this->tahun = $tahun;
    }

    public function collection()
    {
        return MonthlySalary::with('employee') // pastikan relasi employee disiapkan
            ->where('month', $this->bulan)
            ->where('year', $this->tahun)
            ->get()
            ->map(function ($item) {
                return [
                    'NIP' => $item->nip,
                    'Nama' => ($item->employee)->nama ?? '-',
                    'Gaji Pokok' => $item->gaji,
                    'Kos' => $item->kos,
                    'Masuk Pagi' => $item->masuk_pagi,
                    'Prestasi' => $item->prestasi,
                    'Komunikasi' => $item->komunikasi,
                    'Jabatan' => $item->jabatan,
                    'Lain-lain' => $item->lain_lain,
                    'Uang Makan' => $item->uang_makan,
                    'Kasbon' => $item->kasbon,
                    'Premi Hadir' => $item->premi_hadir,
                    'Doa' => $item->doa,
                    'Total Gaji' => $item->total_gaji,
                    'Jumlah Hari Kerja' => $item->jumlah_hari_kerja,
                    'Jumlah Hari Kerja Aktif' => $item->jumlah_hari_kerja_aktif,
                    'Tanggal Terbit' => $item->tanggal_terbit,
                    'Bulan' => $item->month,
                    'Tahun' => $item->year,
                ];
            });
    }

    public function headings(): array
    {
        return [
            'NIP',
            'Nama',
            'Gaji Pokok',
            'Kos',
            'Masuk Pagi',
            'Prestasi',
            'Komunikasi',
            'Jabatan',
            'Lain-lain',
            'Uang Makan',
            'Kasbon',
            'Premi Hadir',
            'Doa',
            'Total Gaji',
            'Jumlah Hari Kerja',
            'Jumlah Hari Kerja Aktif',
            'Tanggal Terbit',
            'Bulan',
            'Tahun',
            'Catatan',
        ];
    }
}
