<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Fingerprint extends Model
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
							->setDescriptionForEvent(fn(string $eventName) => "{$eventName} fingerprint data")
							->useLogName('Fingerprint');
	}

	// Relasi
	public function employee()
	{
		return $this->belongsTo(Employee::class, 'nip', 'nip');
	}


	// Logika
	public function calculateUangLembur()
	{
		$durasiJamLembur = $this->lembur_akhir;
		$jabatan = $this->employee->jabatan;

		switch ($jabatan) {
			case 'Staff Khusus':
				if ($durasiJamLembur < 120) {
					return ($durasiJamLembur / 60) * 10000;
				} else if ($durasiJamLembur >= 120 && $durasiJamLembur < 180) {
					return (10000 + (($durasiJamLembur / 60) - 1) * 12000);
				} else if ($durasiJamLembur >= 180) {
					return ((($durasiJamLembur / 60) - 2) * 15000) + 22000;
				}
				break;
			
			case 'Kepala Operasional':
				if ($durasiJamLembur < 120) {
					return ($durasiJamLembur / 60) * 15000;
				} else if ($durasiJamLembur >= 120 && $durasiJamLembur < 180) {
					return ($durasiJamLembur / 60) * 15000;
				} else if ($durasiJamLembur >= 180) {
					return ($durasiJamLembur / 60) * 15000;
				}
				break;
			
			default:
				return ($durasiJamLembur / 60) * 10000;
				break;
		}
	}

	public function calculateUangMakan()
	{
		$durasiJamLembur = $this->lembur_akhir;
		$jabatan = $this->employee->jabatan;
		
		switch ($jabatan) {
			case 'Kepala Operasional':
				if ($durasiJamLembur >= 120 && $durasiJamLembur < 180) {
					return 10000;
				} else if ($durasiJamLembur >= 180) {
					return 20000;
				} else {
					return 0;
				}
				break;
				
			default:
				if ($durasiJamLembur >= 120 && $durasiJamLembur < 180) {
					return 10000;
				} else if ($durasiJamLembur >= 180) {
					return 15000;
				} else {
					return 0;
				}
				break;
		}
	}

	public function calculateUangKopi()
	{
		$jamKerja = $this->jam_kerja;

		if ((stripos($jamKerja, 'packing') !== false) && (preg_match('/\b[23]\b/', $jamKerja))) {
			return 10000;
		} else {
			return null;
		}
	}

	public function calculateUangLemburMinggu()
	{
		$durasiJamLembur = $this->lembur_akhir;

		if ($durasiJamLembur) {
			return ($durasiJamLembur / 60) * 20000;
		} else {
			return null;
		}
	}
}
