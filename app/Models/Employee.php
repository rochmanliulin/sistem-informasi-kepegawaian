<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Employee extends Model
{
    use HasFactory, SoftDeletes;
    use Notifiable;

    protected $primaryKey = 'nip';
    protected $guarded = [];

    public function fingerprints()
    {
        return $this->hasMany(Fingerprint::class, 'nip', 'nip');
    }

    public function allowance()
    {
        return $this->hasMany(\App\Models\Allowance::class, 'nip', 'nip');
    }

    public function monthlySalaries()
    {
        return $this->hasMany(MonthlySalary::class, 'nip', 'nip');
    }

    public function payroll()
    {
        return $this->hasMany(Payroll::class, 'nip', 'nip');
    }

    // Tambahkan scope untuk mengambil hanya karyawan aktif
    public function scopeActive($query)
    {
        return $query->whereNull('deleted_at');
    }

    public function routeNotificationForMail()
    {
        return $this->email;
    }
    
    // Jika kamu punya kolom status, contoh:
    // public function scopeActive($query)
    // {
    //     return $query->where('status', 'aktif');
    // }
}
