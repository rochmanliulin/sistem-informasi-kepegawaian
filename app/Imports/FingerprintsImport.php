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
        $scanMasuk = null;
        if (!empty($row['scan_masuk'])) {
            try {
                $scanMasuk = Carbon::createFromFormat('H:i:s', $row['scan_masuk']);
            } catch (\Exception $e) {
                return null; // atau log error
            }
        }

        $scanIstirahat1 = null;
        if (!empty($row['scan_istirahat_1'])) {
            try {
                $scanMasuk = Carbon::createFromFormat('H:i:s', $row['scan_masuk']);
            } catch (\Exception $e) {
                return null; // atau log error
            }
        }

        $scanIstirahat2 = null;
        if (!empty($row['scan_istirahat_2'])) {
            try {
                $scanIstirahat2 = Carbon::createFromFormat('H:i:s', $row['scan_istirahat_2']);
            } catch (\Exception $e) {
                return null; // atau log error
            }
        }

        $tgl = null;
        if (!empty($row['tanggal'])) {
            try {
                // Jika format berupa angka serial Excel
                if (is_numeric($row['tanggal'])) {
                    $tgl = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['tanggal'])->format('Y-m-d');
                } else {
                    $tgl = Carbon::createFromFormat('d-m-Y', $row['tanggal'])->format('Y-m-d');
                }
            } catch (\Exception $e) {
                \Log::error('Tanggal invalid: ' . $row['tanggal']);
                return null;
            }
        }

        $scanPulang = null;
        if (!empty($row['scan_pulang'])) {
            try {
                $scanPulang = Carbon::createFromFormat('H:i:s', $row['scan_pulang']);
            } catch (\Exception $e) {
                return null; // atau log error
            }
        }

        // Potong 30 menit dari lembur_akhir jika scan_pulang memenuhi kondisi
        $lemburAkhir = $this->potongLemburAkhir($row['scan_pulang'], $row['jam_kerja'], $row['lembur_akhir']);

        // Create data baru jika tidak ada data Fingerprint dengan NIP dan tanggal yang sama
        $fingerprints = Fingerprint::create([
            'jadwal' => $row['jadwal'],
            'tgl' => $tgl,
            'jam_kerja' => $row['jam_kerja'],
            'nip' => $row['nip'],
            'scan_masuk' => $scanMasuk,
            'terlambat' => $row['terlambat'],
            'scan_istirahat_1' => $scanIstirahat1,
            'scan_istirahat_2' => $scanIstirahat2,
            'istirahat' => $row['istirahat'],
            'scan_pulang' => $scanPulang,
            'durasi' => $row['durasi'],
            'lembur_akhir' => $lemburAkhir,
            'created_by' => $userId
        ]);

        return $fingerprints;
    }

    public function headingRow(): int
    {
        return 2;
    }

    protected function potongLemburAkhir($scanPulang, $jamKerja, $lemburAkhir)
    {
        $jamKerjaDikecualikan = [
            'SENIN-KAMIS HARIAN SHIFT 2',
            'JUM’AT HARIAN SHIFT 2',
            'NORMAL SIANG SENIN-KAMIS',
            'NORMAL SIANG SABTU',
            'NORMAL SIANG JUMAT'
        ];

        if (in_array($jamKerja, $jamKerjaDikecualikan)) {
            return $lemburAkhir;
        }

        if (empty($scanPulang) || !preg_match('/^\d{2}:\d{2}:\d{2}$/', $scanPulang)) {
            return $lemburAkhir;
        }

        try {
            $scanPulangCarbon = Carbon::createFromFormat('H:i:s', $scanPulang);
            $jamSelesaiKerja = Carbon::createFromTime(17, 0, 0); // 17:00:00
        } catch (\Exception $e) {
            return $lemburAkhir; // gagal parsing waktu, kembalikan nilai awal
        }

        $selisihMenit = 0;

        if ($scanPulangCarbon->gt($jamSelesaiKerja)) {
            $selisihMenit = $scanPulangCarbon->diffInMinutes($jamSelesaiKerja);
        }

        return $selisihMenit;
    }

}
