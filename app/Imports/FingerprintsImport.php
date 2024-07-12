<?php

namespace App\Imports;

use App\Models\Employee;
use App\Models\Allowance;
use App\Models\Fingerprint;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;

class FingerprintsImport implements ToModel, WithHeadingRow
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
        $allowance = Allowance::pluck('nip')->toArray();
        
        // Apakah nilai $row['nip'] tidak ada di dalam $employee dan $allowance
        if (!in_array($row['nip'], $employee) || !in_array($row['nip'], $allowance)) {
            return null;
        }

        // Format ke database
        $scanIstirahat1 = null;
        if (!empty($row['scan_istirahat_1'])) {
            $scanIstirahat1 = Carbon::createFromFormat('H:i:s', $row['scan_istirahat_1']);
        }
        
        $scanIstirahat2 = null;
        if (!empty($row['scan_istirahat_2'])) {
            $scanIstirahat2 = Carbon::createFromFormat('H:i:s', $row['scan_istirahat_2']);
        }

        $tgl = null;
        if (!empty($row['tanggal'])) {
            $tgl = Carbon::createFromFormat('d-m-Y', $row['tanggal'])->format('Y-m-d');
        }

        // Create data baru jika tidak ada data Fingerprint dengan NIP dan tanggal yang sama
        $fingerprints = Fingerprint::create([
            'jadwal' => $row['jadwal'],
            'tgl' => $tgl,
            'jam_kerja' => $row['jam_kerja'],
            'nip' => $row['nip'],
            'terlambat' => $row['terlambat'],
            'scan_istirahat_1' => $scanIstirahat1,
            'scan_istirahat_2' => $scanIstirahat2,
            'istirahat' => $row['istirahat'],
            'durasi' => $row['durasi'],
            'lembur_akhir' => $row['lembur_akhir'],
            'created_by' => $userId
        ]);

        return $fingerprints;
    }

    public function headingRow(): int
    {
        return 2;
    }
}
