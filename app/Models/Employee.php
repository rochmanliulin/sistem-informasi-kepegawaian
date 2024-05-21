<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    public function tapActivity(Activity $activity, string $eventName)
    {
        $activity->properties = $activity->properties->merge(['ip' => Request::ip()]);
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
