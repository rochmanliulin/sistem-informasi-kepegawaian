<?php

namespace App\Imports;

use App\Models\Employee;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;


class EmployeesImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Ubah format tanggal
        $tglMasukKerja = null;
        if (!empty($row['tgl_masuk_kerja'])) {
            /**
             * Dikarenakan format kolom tanggal masuk kerja di file excel mempresentasikan serial number atau jumlah hari sejak 1 Januari 1900 (di Excel), maka harus di konversi ke tanggal yuang sesuai
             * - 2 dikarenakan ada perbedaan pengindeksan tanggal antara Excel dan Carbon (Excel dimulai dari tanggal 1 Januari 1900, sedangkan Carbon dimulai dari tanggal 1 Januari 1970).
             *  */ 
            $tglMasukKerja = Carbon::create(1900, 1, 1)->addDays($row['tgl_masuk_kerja'] - 2)->format('Y-m-d');
        }
        
        // Update or create data berdasarkan NIP
        $employee = Employee::updateOrCreate(
            ['nip' => $row['nip']],
            [
                'nama' => $row['nama'],
                'jabatan' => $row['jabatan'],
                'departemen' => $row['departemen'],
                'status' => $row['status'],
                'tgl_masuk_kerja' => $tglMasukKerja,
            ]
        );

        return $employee;
    }
}
