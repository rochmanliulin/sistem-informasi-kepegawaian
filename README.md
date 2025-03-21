<p align="center"><a href="https://sik.pusatgrosirsidoarjo.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Personnel Information System (SIK)

Sistem Informasi Kepegawaian adalah sistem yang dapat membantu perusahaan untuk memanage penggajian pegawai.

## Version

### Release **v1.0.0** - 26 April 2024

- Release app

### Release **v1.0.1** - 27 April 2024

- New Feature: Report For Role:Editor
- Reformat Whatsapp Number Input
- Disable Required Whatsapp Number Input
- Add Footer Login Page
- Upgrade UI Mobile Login Page

### Release **v1.0.2** - 13 May 2024

- New Feature: User Activity Log
- New Feature: Status Online/Offline User
- Bug Fixing: Feature Download PDF Role:Employee -> View PDF Role:Employee
- Improve Logic Report Overtime Salary Role:Employee. So can view if date <= today's date
- Add Footer Manajemen User Menu and User Activity Log
- Clean Code From Unused Code
- Change Employee Status: Pegawai Tetap, Pegawai Kontrak, Pegawai Harian -> Pegawai Kontrak and Pegawai Harian
- Add Status Attribute on Import Data Fingerprint
- Improve Logic When Update Data Without Change's
- Change Alert: Swal -> Toast and Change Code to app.blade.php

### Release **v1.0.3** - 20 May 2024

- New Feature: Tutorial Video
- Add Credited Account
- Improve Code
- Fixing Bug IP on Log Activity
- Add Log Activity Login/Logout and Download Report
- Change Type Input Text Should Number

### Release **v1.0.4** - 19 June 2024

- Bug Fixing: Employee Get Premi Lembur
- Bug Fixing: Name and Credited Account Employee Can't Show on Payroll Menu
- Bug Fixing: Edit Allowance Employee Status: Pegawai Kontrak
- Bug Fixing: Input Export Date Overtime Salaries
- Bug Fixing: Input Date Generate Payroll
- Add Column on Database:
  - created_by, updated_by, and deleted_by on employees table
  - created and updated_by on allowances, fingerprints, overtime_salaries, and payrolls

### Release **v2.0.0** - 12 July 2024

- Downgrade Laravel v10 -> v8
- Change employee export results column to Tanggal Masuk -> Tgl Masuk Kerja
- Disable the activity log feature
- Change appearance of the activity log menu

### Release **v2.0.1** - 31 December 2024

- Add auto cut lembur_akhir column based on return scan_pulang

## License

Licensed under the [PT. Pusat Grosir Sidoarjo](https://pusatgrosirsidoarjo.com).
