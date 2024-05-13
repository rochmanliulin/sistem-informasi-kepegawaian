<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OvertimeSalary extends Model
{
  use HasFactory;

  protected $guarded = [
    'id'
	];

  // Relasi
  public function allowance()
  {
    return $this->belongsTo(Allowance::class, 'nip', 'nip');
  }
  public function employee()
	{
		return $this->belongsTo(Employee::class, 'nip', 'nip');
	}
}
