<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Request;

class Allowance extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
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
