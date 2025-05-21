<?php

namespace App\Exports;

use App\Models\OvertimeSalary;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;/*  */

class OvertimeSalaryExport implements FromQuery, WithHeadings
{
    use Exportable;
    public $info;

    public function __construct($info)
    {
        $this->info = $info;
    }

    public function query()
    {
        // Memisahkan keterangan dan tgl_terbit dari $this->info
        preg_match('/^(.*)\s\((.*)\)$/', $this->info, $matches);
        $keterangan = $matches[1];
        $tgl_terbit = Carbon::createFromFormat('d F Y', $matches[2])->format('Y-m-d');

        return OvertimeSalary::query()
            ->select(
                'overtime_salaries.nip',
                'employees.nama',
                'overtime_salaries.total_uang_lembur',
                'overtime_salaries.doa',
                'overtime_salaries.premi',
                'overtime_salaries.gaji',
                'overtime_salaries.total_uang_kopi',
                'overtime_salaries.total_uang_lembur_minggu',
                'overtime_salaries.total_uang_makan',
                'overtime_salaries.total',
                'overtime_salaries.hari_aktif',
                'overtime_salaries.keterangan',
                'overtime_salaries.total_jam_lembur',
                'overtime_salaries.tgl_terbit',
                'overtime_salaries.hari_terlambat',
                'overtime_salaries.total_terlambat',
                'overtime_salaries.tidak_istirahat',
                'overtime_salaries.tidak_istirahat_masuk',
                'overtime_salaries.tidak_istirahat_kembali',
                'overtime_salaries.lebih_istirahat',
            )
            ->leftJoin('employees', 'overtime_salaries.nip', '=', 'employees.nip')
            ->where('keterangan', $keterangan)
            ->whereDate('tgl_terbit', $tgl_terbit);
    }

    public function headings(): array
    {
        return [
            'No',
            'NIP',
            'Nama',
            'Lembur',
            'Doa',
            'Premi',
            'Gaji',
            'Kopi',
            'Lembur Minggu',
            'Uang makan',
            'Total',
            'Keterangan',
            'Hari',
            'Jam',
            'Tanggal Terbit',
            'Telat',
            'Jumlah Waktu Telat (Menit)',
            'Tidak Finger Istirahat',
            'Tidak Finger Istirahat (Masuk)',
            'Tidak Finger Istirahat (Kembali)',
            'Waktu lebih Istirahat'
        ];
    }
}
