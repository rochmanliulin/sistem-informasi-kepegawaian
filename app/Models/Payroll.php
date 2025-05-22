<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $fillable = [
        'trx_id',
        'transfer_type',
        'amount',
        'nip',
        'remark',
        'keterangan',
        'bulan',
        'tahun',
        'tanggal_terbit',
        'catatan',
        'created_by',
        'updated_by'
    ];

    public function employee()
	{
		return $this->belongsTo(Employee::class, 'nip', 'nip');
	}
}
