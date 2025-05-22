<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Allowance extends Model
{
    use HasFactory;

    protected $table = 'allowances';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'nip',
        'gaji',
        'kos',
        'masuk_pagi',
        'prestasi',
        'komunikasi',
        'jabatan',
        'lain_lain',
        'uang_makan',
        'kasbon',
        'premi_hadir',
        'premi_lembur',
        'doa',
        'created_by',
        'updated_by',
    ];

    // Relasi
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'nip', 'nip');
    }

    public function overtimeSalary()
    {
        return $this->belongsTo(OvertimeSalary::class, 'nip', 'nip');
    }
}
