<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlySalary extends Model
{
    use HasFactory;

    protected $table = 'monthly_salaries';
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
        'doa',
        'total_gaji',
        'keterangan',
        'tanggal_terbit',
        'jumlah_hari_kerja',
        'jumlah_hari_kerja_aktif',
        'month',
        'year',
    ];

    // Atur nilai default pada kolom total_gaji
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($salary) {
            // Cek jika total_gaji belum ada nilai, maka set default 0
            if (!$salary->total_gaji) {
                $salary->total_gaji = 0;
            }
        });
    }

    // Relasi ke tabel employees berdasarkan kolom 'nip'
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'nip', 'nip');
    }

    public function allowance()
    {
        return $this->belongsTo(Allowance::class, 'nip', 'nip');
    }

    public function payroll()
    {
        return $this->belongsTo(Payroll::class);
    }
}
