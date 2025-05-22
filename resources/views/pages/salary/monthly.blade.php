@extends('layouts.app', ['page_title' => $page_title])

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Gaji Bulanan</h6>
                </div>
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
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-block bg-gradient-success">
                                    <i class="fas fa-cogs"></i>&nbsp;&nbsp;Proses Gaji
                                </button>
                            </div>
                            <div class="col-md-6">
                                <button type="button" class="btn btn-block bg-gradient-secondary" onclick="exportExcel()">
                                    <i class="fas fa-download"></i>&nbsp;&nbsp;Export
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- FORM EXPORT (diletakkan di luar form proses) -->
                    <form id="exportForm" action="{{ route('monthly-salary.export') }}" method="GET">
                        @csrf
                        <input type="hidden" name="bulan" id="exportBulan">
                        <input type="hidden" name="tahun" id="exportTahun">
                    </form>

                    <!-- SCRIPT EXPORT -->
                    <script>
                        function exportExcel() {
                            const bulan = document.getElementById('bulan').value;
                            const tahun = document.getElementById('tahun').value;

                            if (!bulan || !tahun) {
                                alert('Silakan pilih bulan dan tahun terlebih dahulu');
                                return;
                            }

                            document.getElementById('exportBulan').value = bulan;
                            document.getElementById('exportTahun').value = tahun;
                            document.getElementById('exportForm').submit();
                        }
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
