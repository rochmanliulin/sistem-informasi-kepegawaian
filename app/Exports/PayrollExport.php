<?php

namespace App\Exports;

use App\Models\Payroll;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;

class PayrollExport implements FromQuery, WithHeadings
{
    use Exportable;
    public $remark;

    public function __construct($remark)
    {
        $this->remark = $remark;
    }

    public function query()
    {
        return Payroll::query()
        ->select(
            'payrolls.trx_id',
            'payrolls.transfer_type',
            DB::raw("''"),
            'employees.credited_account',
            'employees.nama AS receiver_name',
            'payrolls.amount',
            'payrolls.nip',
            'payrolls.remark'
        )
        ->leftJoin('employees', 'payrolls.nip', '=', 'employees.nip')
        ->where('remark', $this->remark);
    }

    public function headings(): array
    {
        return [
            'Trx ID',
            'Transfer Type',
            'Beneficiary ID',
            'Credited Account',
            'Receiver Name',
            'Amount',
            'NIP',
            'Remark',
            'Beneficiary email',
            'Swift Code',
            'Cust Type',
            'Cust Residence'
        ];
    }
}
