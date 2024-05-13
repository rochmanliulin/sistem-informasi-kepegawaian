<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Employee extends Model
{
    use HasFactory, LogsActivity;

    protected $primaryKey = 'nip';
    protected $guarded = [];

    // User Activity Log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
                ->logAll()
                ->setDescriptionForEvent(fn(string $eventName) => "{$eventName} employee data")
                ->useLogName('Employee');
    }

    public function fingerprint()
    {
        return $this->hasMany(Fingerprint::class, 'nip', 'nip');
    }

    public function allowance()
    {
        return $this->hasOne(Allowance::class, 'nip', 'nip');
    }
    
    public function payroll()
    {
        return $this->hasMany(Payroll::class, 'nip', 'nip');
    }
}
