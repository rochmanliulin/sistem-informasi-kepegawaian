<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Employee extends Model
{
    use HasFactory, SoftDeletes;
    use Notifiable;

    protected $primaryKey = 'nip';
    protected $guarded = [];

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

    public function routeNotificationForMail()
    {
        return $this->email;
    }
}
