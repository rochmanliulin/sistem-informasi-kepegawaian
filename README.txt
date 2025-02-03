v1.0.0 - 26/04/2024
Deployment ke production (hosting Cloud Startup)

v1.0.1 - 27/04/2024
  -Tambahkan fitur laporan pada role admin keuangan
  -Ubah format no whatsapp pada create user agar menghilangkan semua tanda baca dan spasi kemudian mengganti dengan 62(bisa gunakan regex)
  -Hilangkan attribute required input no whatsapp
  -Tambah footer pada login page
  -Upgrade mobile desain login page

v1.0.2 - 13/05/2024
  -Release fitur baru: User Activity Log
  -Release sub-fitur baru: status online/offline
  -Fixing bug download pdf role:pegawai
  -Improve logic laporan gaji lembur role:pegawai hanya dapat dilihat jika tanggal terbit <= tanggal sekarang
  -Tambah footer pada menu Manajemen User dan Log Aktifitas
  -Clean Code dari code yang tidak diperlukan
  -Ubah status pegawai dari Pegawai Tetap, Pegawai Kontrak, Pegawai Harian -> Pegawai Kontrak dan Pegawai Harian
  -Tambah kolom status pada fitur import data fingerprint
  -Bug Fixing ketika update data tanpa adanya perubahan
  -Ubah alert: Swal -> Toast dan memindahkan code ke app.blade.php

v1.0.3 - 20/05/2024
  -Release fitur baru: Video Tutorial
  -Tambah credited account pada model employee
  -Improve code
  -Fixing Bug IP di Log Activity
  -Tambah log activity login/logout dan download report
  -Ubah input tipe text yang seharusnya tipe number

v1.0.4 - 19/06/2024
  -Bug fixing pegawai yang memperoleh premi lembur
  -Bug fixing pegawai yang telah dihapus tidak muncul pada menu payroll(soft delete)
  -Bug fixing edit tunjangan pada pegawai yang memiliki status kontrak
  -Bug fixing tanggal export gaji lembur
  -Bug fixing tanggal generate payroll
  -Tambah kolom created_by, updated_by, dan deleted_by pada tabel employee
  -Tambah kolom created_by dan updated_by pada tabel allowances, fingerprints, overtime_salaries, dan payrolls

v2.0.0 - 12/07/2024
  -Downgrade versi Laravel 10 -> 8
  -Ubah kolom hasil export pegawai Tanggal Masuk -> Tgl Masuk Kerja
  -Nonaktifkan fitur activity log
  -Ubah tampilan menu log aktivitas

v2.0.1 - 31/12/2024
  -Tambah auto cut kolom lembur_akhir berdasarkan scan_pulang
