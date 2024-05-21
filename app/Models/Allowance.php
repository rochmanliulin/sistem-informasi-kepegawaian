<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Request;

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

    public function tapActivity(Activity $activity, string $eventName)
    {
        $activity->properties = $activity->properties->merge(['ip' => Request::ip()]);
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
