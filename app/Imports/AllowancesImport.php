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
        $userId = auth()->user()->id;
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
                'updated_by' => $userId,
            ],
            [
                'created_by' => $userId,
            ]
        );

        return $allowance;
    }
}
