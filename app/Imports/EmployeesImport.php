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
        $userId = auth()->user()->id;
        $tglMasukKerjaFromExcel = $row['tgl_masuk_kerja'];

        if (!empty($tglMasukKerjaFromExcel)) {
            /**
             * Dikarenakan format kolom tanggal masuk kerja di file excel mempresentasikan serial number atau Excel menganggap 1 Januari 1900 sebagai hari pertama (nomor seri 1), jadi kita mulai dari 30 Desember 1899. Maka harus di konversi ke tanggal yuang sesuai
             * - 2 dikarenakan ada perbedaan pengindeksan tanggal antara Excel dan Carbon (Excel dimulai dari tanggal 1 Januari 1900, sedangkan Carbon dimulai dari tanggal 1 Januari 1970).
             *  */

            if (is_numeric($tglMasukKerjaFromExcel)) {
                $tglMasukKerja = Carbon::createFromDate(1899, 12, 30)->addDays($row['tgl_masuk_kerja'])->format('Y-m-d');
            } else {
                $tglMasukKerja = $tglMasukKerjaFromExcel;
            }
        } else {
            $tglMasukKerja = null;
        }

        // Update or create data berdasarkan NIP
        $employee = Employee::updateOrCreate(
            ['nip' => $row['nip']],
            [
                'nama' => $row['nama'],
                'credited_account' => $row['credited_account'],
                'jabatan' => $row['jabatan'],
                'departemen' => $row['departemen'],
                'status' => $row['status'],
                'tgl_masuk_kerja' => $tglMasukKerja,
                'email' => $row['email'],
                'updated_by' => $userId,
            ],
            [
                'created_by' => $userId,
            ]
        );

        return $employee;
    }
}
