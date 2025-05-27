@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
  @include('layouts.navbars.auth.topnav', ['title' => 'Gaji Bulanan'])
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h4 class="text-dark">{{ $page_title }}</h4>
                </div>

                <!-- FORM INPUT GAJI -->
                <div class="card-body">
                    <form action="{{ route('monthly-salary.process') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="bulan" class="form-control-label">Bulan</label>
                                    <select class="form-control" id="bulan" name="bulan" required>
                                        <option value="">Pilih Bulan</option>
                                        @php
                                            $bulanIndo = [
                                                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                                                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                                                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                                            ];
                                        @endphp
                                        @for($i = 1; $i <= 12; $i++)
                                            <option value="{{ $i }}">{{ $bulanIndo[$i] }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tahun" class="form-control-label">Tahun</label>
                                    <input type="number" class="form-control" id="tahun" name="tahun" min="2000" max="{{ date('Y') }}" value="{{ date('Y') }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label">Jumlah Hari Kerja</label>
                                    <input type="number" class="form-control" name="jumlah_hari_kerja" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tanggal_terbit" class="form-control-label">Tanggal Terbit</label>
                                    <input type="date" class="form-control" id="tanggal_terbit" name="tanggal_terbit" required>

                                </div>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-block bg-gradient-success">
                                    <i class="fas fa-cogs"></i>&nbsp;&nbsp;Proses Gaji
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- FORM EXPORT -->
                    <hr>
                    <form action="{{ route('monthly-salary.export') }}" method="GET" id="exportForm">
                        @csrf
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="dataDate" class="form-control-label">Tanggal</label>
                                    <select class="form-select" id="dataDate" required>
                                        <option disabled selected></option>
                                        @foreach ($info as $item)
                                            <option value="{{ $item['value'] }}">{{ $item['label'] }}</option>
                                        @endforeach
                                    </select>

                                    <input type="hidden" name="bulan" id="bulanInput">
                                    <input type="hidden" name="tahun" id="tahunInput">
                                </div>
                                <div class="col-md-5 mt-2">
                                    <button type="button" onclick="exportExcel()" class="btn btn-block bg-gradient-secondary">
                                        <i class="fas fa-download"></i>&nbsp;&nbsp;Export
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- TABLE GAJI -->
                <div class="row">
                    <div class="col-xl-12 col-sm-6 mb-xl-0">
                        <div class="card">
                        <div class="d-flex justify-content-end m-3 mb-0">
                            <form action="{{ route('monthly-salary.index') }}" method="GET" class="col-lg-3">
                            {{-- request() -> Mengambil value request yang dikirim ke server --}}
                                <input type="text" id="searchInput" class="form-control" placeholder="Cari..." name="search" value="{{ request()->search }}">
                            </form>
                        </div>
                        <div class="table-responsive">

                            @if ($monthlySalary->isEmpty())
                                <p class="d-flex justify-content-center mt-3 mb-1 fw-bold">Tidak ada data yang ditemukan.</p>
                            @else
                            <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 text-center">No</th>
                                <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 text-center">NIK</th>
                                <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 ps-3">Nama</th>
                                <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 text-center">Gaji</th>
                                <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 text-center">Kos</th>
                                <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 text-center">Masuk Pagi</th>
                                <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 text-center">Prestasi</th>
                                <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 text-center">Komunikasi</th>
                                <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 text-center">Jabatan</th>
                                <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 text-center">Lain-lain</th>
                                <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 text-center">Uang Makan</th>
                                <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 text-center">Kasbon</th>
                                <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 text-center">Premi Hadir</th>
                                <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 text-center">Do'a</th>
                                <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 text-center">Total Gaji</th>
                                <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 text-center">Telat</th>
                                <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 text-center">Jumlah Waktu Telat &#40;Menit&#41;</th>
                                <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 text-center">Tidak Finger Istirahat</th>
                                <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 text-center">Tidak Finger Istirahat &#40;Masuk&#41;</th>
                                <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 text-center">Tidak Finger Istirahat &#40;Kembali&#41;</th>
                                <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 text-center">Waktu Lebih Istirahat</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($monthlySalary as $item)
                                <tr>
                                <td>
                                    <p class="text-xs my-auto text-center">{{ $monthlySalary->firstItem() + $loop->index }}</p>
                                </td>
                                <td>
                                    <p class="text-xs font-weight-bold mb-0 text-center">{{ $item->nip }}</p>
                                </td>
                                <td>
                                    <span class="badge badge-dot me-4 ps-2">
                                    <i class="bg-info"></i>
                                    <span class="text-dark text-xs">{{ $item->employee->nama }}</span>
                                    </span>
                                </td>
                                <td>
                                    <p class="text-xs font-weight-bold mb-0 text-center">{{ $item->total_uang_lembur }}</p>
                                </td>
                                <td>
                                    <p class="text-xs my-auto text-center">{{ $item->doa }}</p>
                                </td>
                                <td>
                                    <p class="text-xs my-auto text-center">{{ $item->premi }}</p>
                                </td>
                                <td>
                                    <p class="text-xs my-auto text-center">{{ $item->gaji }}</p>
                                </td>
                                <td>
                                    <p class="text-xs my-auto text-center">{{ $item->total_uang_kopi }}</p>
                                </td>
                                <td>
                                    <p class="text-xs my-auto text-center">{{ $item->total_uang_lembur_minggu }}</p>
                                </td>
                                <td>
                                    <p class="text-xs my-auto text-center">{{ $item->total_uang_makan }}</p>
                                </td>
                                <td>
                                    <p class="text-xs my-auto text-center">{{ $item->total }}</p>
                                </td>
                                <td>
                                    <p class="text-xs my-auto text-center">{{ $item->keterangan }}</p>
                                </td>
                                <td>
                                    <p class="text-xs my-auto text-center">{{ $item->hari_aktif }}</p>
                                </td>
                                <td>
                                    <p class="text-xs my-auto text-center">{{ $item->total_jam_lembur }}</p>
                                </td>
                                <td>
                                    <p class="text-xs my-auto text-center">{{ $item->tgl_terbit }}</p>
                                </td>
                                <td>
                                    <p class="text-xs my-auto text-center">{{ $item->hari_terlambat }}</p>
                                </td>
                                <td>
                                    <p class="text-xs my-auto text-center">{{ $item->total_terlambat }}</p>
                                </td>
                                <td>
                                    <p class="text-xs my-auto text-center">{{ $item->tidak_istirahat }}</p>
                                </td>
                                <td>
                                    <p class="text-xs my-auto text-center">{{ $item->tidak_istirahat_masuk }}</p>
                                </td>
                                <td>
                                    <p class="text-xs my-auto text-center">{{ $item->tidak_istirahat_kembali }}</p>
                                </td>
                                <td>
                                    <p class="text-xs my-auto text-center">{{ $item->lebih_istirahat }}</p>
                                </td>
                                </tr>
                                @endforeach
                            </tbody>
                            </table>
                            @endif

                        </div>
                        <div class=" px-5 mt-3">
                            {{ $monthlySalary->onEachSide(1)->appends(['search' => $search])->links() }}
                        </div>
                        </div>
                    </div>
                </div>

                    <!-- SCRIPT EXPORT -->
                    <script>
                        // Fungsi untuk mengekspor data ke Excel
                        function exportExcel() {
                            const info = document.getElementById('dataDate').value;

                            if (!info) {
                                alert('Silakan pilih bulan dan tahun terlebih dahulu');
                                return;
                            }

                            const [bulan, tahun] = info.split('-');

                            document.getElementById('bulanInput').value = bulan;
                            document.getElementById('tahunInput').value = tahun;
                            document.getElementById('exportForm').submit();
                        }

                        // Fungsi untuk menangani tombol Enter pada input pencarian
                        document.addEventListener("DOMContentLoaded", function() {
                            const searchInput = document.querySelector('#searchInput');

                            searchInput.addEventListener('keypress', function(e) {
                                if (e.key === 'Enter') {
                                    // Mencegah event bawaan browser
                                    e.preventDefault();

                            if (this.value.trim() === '') {
                            window.location.href = "{{ route('monthly-salary.index') }}";
                            } else {
                            // Ambil form dari input, kemudian submit
                            this.form.submit();
                            }
                                }
                            });
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
