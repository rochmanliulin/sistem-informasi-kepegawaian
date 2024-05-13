@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
	@include('layouts.navbars.auth.topnav', ['title' => 'Edit Data Fingerprint'])
	<div class="container py-4">
		<div class="row">
			<div class="col-xl-12 col-sm-6 mb-xl-0">
				<div class="card">
					<div class="container my-4">
						<h4 class="text-center mb-4">EDIT DATA</h4>
						{{-- route('nama route', id) --}}
						<form action="{{ route('fingerprint.update', $fingerprint->id) }}" method="POST">
							@csrf
							@method('PATCH')
							{{--
								Method patch/put -> method bawaan jika menggunakan route resource
								patch -> mengupdate semua input
								put -> mengupdate input tertentu
							--}}
							<div class="row">
                <input type="hidden" name="id" value="{{ $fingerprint->id }}">
								<div class="col-md-4">
									<div class="form-group">
										<label for="nama" class="form-control-label">Nama</label>
										<input type="text" class="form-control" id="nama" readonly value="{{ $employee->nama }}"/>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label for="jadwal" class="form-control-label">Jadwal</label>
										<input type="text" class="form-control" id="jadwal" name="jadwal" value="{{ $fingerprint->jadwal }}"/>
                    @error('jadwal') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
									</div>
								</div>
								<div class="col-md-4">
                  <div class="form-group">
										<label for="tgl" class="form-control-label">Tanggal</label>
										<input type="text" class="form-control bg-white date" id="tgl" name="tgl" onfocus="focused(this)" onfocusout="defocused(this)" value="{{ $fingerprint->tgl }}"/>
                    @error('tgl') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
									</div>
								</div>
							</div>
							<div class="row">
                <div class="col-md-3">
                  <div class="form-group">
                    <label for="jamKerja" class="form-control-label">Jam Kerja</label>
                    <input type="text" class="form-control" id="jamKerja" name="jam_kerja" value="{{ $fingerprint->jam_kerja }}"/>
                    @error('jam_kerja') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
                  </div>
                </div>
								<div class="col-md-3">
                  <div class="form-group">
                    <label for="terlambat" class="form-control-label">Terlambat</label>
                    <input type="text" class="form-control" id="terlambat" name="terlambat" value="{{ $fingerprint->terlambat }}"/>
                    @error('terlambat') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
                  </div>
                </div>
								<div class="col-md-3">
                  <div class="form-group">
                    <label for="scanIstirahat1" class="form-control-label">Scan Istirahat 1</label>
                    <input type="time" class="form-control" id="scanIstirahat1" name="scan_istirahat_1" step="1" value="{{ $fingerprint->scan_istirahat_1 }}"/>
                    @error('scan_istirahat_1') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
                  </div>
                </div>
								<div class="col-md-3">
                  <div class="form-group">
                    <label for="scanIstirahat2" class="form-control-label">Scan Istirahat 2</label>
                    <input type="time" class="form-control" id="scanIstirahat2" name="scan_istirahat_2" step="1" value="{{ $fingerprint->scan_istirahat_2 }}"/>
                    @error('scan_istirahat_2') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
                  </div>
                </div>
							</div>
              <div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<label for="istirahat" class="form-control-label">Istirahat</label>
										<input type="text" class="form-control" id="istirahat" name="istirahat" value="{{ $fingerprint->istirahat }}"/>
                    @error('istirahat') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label for="durasi" class="form-control-label">Durasi</label>
										<input type="text" class="form-control" id="durasi" name="durasi" value="{{ $fingerprint->durasi }}"/>
                    @error('durasi') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label for="lemburAkhir" class="form-control-label">Lembur Akhir</label>
										<input type="text" class="form-control" id="lemburAkhir" name="lembur_akhir" value="{{ $fingerprint->lembur_akhir }}"/>
                    @error('lembur_akhir') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
									</div>
								</div>
							</div>
							<div class="row justify-content-center mt-4">
									<button type="submit" class="btn bg-gradient-success col-1 mb-0">Update</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
	@include('layouts.footers.auth.footer')
@endsection

@push('js')
	<script>
		// Format tgl
		flatpickr('.date', {
      altInput: true,
      altFormat: "j F Y",
      dateFormat: "Y-m-d",
			maxDate: "today"
    });

    // Fungsi untuk menghitung selisih waktu dalam menit
    function calculateTimeDifference(start, end) {
        var startTime = new Date('1970-01-01T' + start + 'Z');
        var endTime = new Date('1970-01-01T' + end + 'Z');

        var difference = endTime.getTime() - startTime.getTime(); // Selisih dalam milidetik
        var minutes = Math.floor(difference / 60000); // Konversi ke menit

        return minutes;
    }

    // Fungsi untuk mengupdate nilai input istirahat
    function updateIstirahat() {
        var scanIstirahat1 = document.getElementById('scanIstirahat1').value;
        var scanIstirahat2 = document.getElementById('scanIstirahat2').value;

        if (scanIstirahat1 && scanIstirahat2) {
            var istirahatInput = document.getElementById('istirahat');
            var durasi = calculateTimeDifference(scanIstirahat1, scanIstirahat2);
            istirahatInput.value = durasi;
        }
    }

    // Panggil fungsi update saat nilai input berubah
    document.getElementById('scanIstirahat1').addEventListener('change', updateIstirahat);
    document.getElementById('scanIstirahat2').addEventListener('change', updateIstirahat);
	</script>
@endpush