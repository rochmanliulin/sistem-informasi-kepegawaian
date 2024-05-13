<?php

namespace App\Exports;

use App\Models\Employee;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EmployeesExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Employee::select('nip', 'nama', 'jabatan', 'departemen', 'status', 'tgl_masuk_kerja')->get();
    }

    public function headings(): array
    {
        return [
            'NIP',
            'Nama',
            'Jabatan',
            'Departemen',
            'Tanggal Masuk',
            'Status'
        ];
    }
}
