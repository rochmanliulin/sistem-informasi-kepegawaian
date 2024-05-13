<?php

namespace App\Imports;

use App\Models\Employee;
use App\Models\Allowance;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AllowancesImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $employee = Employee::pluck('nip')->toArray();
        
        // Apakah nilai $row['nip'] tidak ada di dalam $employee
        if (!in_array($row['nip'], $employee)) {
            return null;
        }

        $allowance = Allowance::updateOrCreate(
            ['nip' => $row['nip']],
            
            [
                'nip' => $row['nip'],
                'gaji' => $row['gaji'],
                'premi_hadir' => $row['premi_hadir'],
                'premi_lembur' => $row['premi_lembur'],
            ]
        );

        return $allowance;

        // $employee = Employee::pluck('nip')->toArray();
        
        // // Apakah nilai $row['nip'] tidak ada di dalam $employeeNIP
        // if (!in_array($row['nip'], $employee)) {
        //     return null;
        // }

        // $allowance = Allowance::updateOrCreate(
        //     ['nip' => $row['nip']],
        //     [
        //         'gaji' => $row['gaji'],
		// 		'kos' => $row['kos'],
		// 		'masuk_pagi' => $row['masuk_pagi'],
		// 		'prestasi' => $row['prestasi'],
		// 		'komunikasi' => $row['komunikasi'],
		// 		'jabatan' => $row['jabatan'],
		// 		'lain_lain' => $row['lain_lain'],
		// 		'uang_makan' => $row['uang_makan'],
		// 		'kasbon' => $row['kasbon'],
		// 		'premi_hadir' => $row['premi_hadir'],
		// 		'premi_lembur' => $row['premi_lembur'],
		// 		'doa' => $row['doa'],
        //     ]
        // );

        // return $allowance;
    }
}
