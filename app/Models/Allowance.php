<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Allowance extends Model
{
    use HasFactory, LogsActivity;

    protected $guarded = [
        'id'
    ];

    // User Activity Log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
                ->logAll()
                ->setDescriptionForEvent(fn(string $eventName) => "{$eventName} allowance data")
                ->useLogName('Allowance');
    }

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
