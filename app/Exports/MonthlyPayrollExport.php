<?php

namespace App\Exports;

use App\Models\Allowance;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;

class MonthlyPayrollExport implements FromQuery, WithHeadings
{
    use Exportable;
    public $keterangan;
    public $createdDate;

    public function __construct($keterangan, $createdDate = null)
    {
        $this->keterangan = $keterangan;
        $this->createdDate = $createdDate;
    }

    public function query()
    {
        \Log::info('Starting payroll export', [
            'keterangan' => $this->keterangan,
            'created_date' => $this->createdDate
        ]);

        try {
            // First verify the data exists
            $baseQuery = Allowance::where('keterangan', 'like', '%'.$this->keterangan.'%');

            if ($this->createdDate) {
                $baseQuery->whereDate('created_at', $this->createdDate);
            }

            $dataExists = $baseQuery->exists();

            if (!$dataExists) {
                \Log::error('No matching allowance data found', [
                    'keterangan' => $this->keterangan,
                    'created_date' => $this->createdDate
                ]);
                return Allowance::whereNull('id'); // Return empty result
            }

            // Main export query
            $query = Allowance::with(['employee' => function($q) {
                $q->select('nip', 'nama', 'credited_account');
            }])
            ->select(
                'allowances.id',
                'allowances.nip',
                'employees.nama',
                'allowances.keterangan',
                DB::raw('(gaji + kos + masuk_pagi + prestasi + komunikasi + jabatan + lain_lain + uang_makan + kasbon + premi_hadir + premi_lembur + doa) as total_amount'),
                'allowances.created_at'
            )
            ->where('keterangan', 'like', '%'.$this->keterangan.'%');

            if ($this->createdDate) {
                $query->whereDate('created_at', $this->createdDate);
            }

            \Log::info('Export query executed successfully');
            return $query;

        } catch (\Exception $e) {
            \Log::error('Export failed: '.$e->getMessage());
            return Allowance::whereNull('id'); // Return empty result
        }
    }

    public function map($allowance): array
    {
        return [
            $allowance->nip,
            $allowance->nama,
            $allowance->keterangan,
            $allowance->total_amount,
            $allowance->credited_account,
            $allowance->tanggal
        ];
    }


    public function headings(): array
    {
        return [
            'NIP',
            'Nama',
            'Jenis Tunjangan',
            'Jumlah',
            'Nomor Rekening',
            'Tanggal'
        ];
    }

}
