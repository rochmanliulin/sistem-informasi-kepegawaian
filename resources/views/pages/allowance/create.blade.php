@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
	@include('layouts.navbars.auth.topnav', ['title' => 'Tambah Data Tunjangan'])
	<div class="container py-4">
		<div class="row">
			<div class="col-xl-12 col-sm-6 mb-xl-0">
				<div class="card">
					<div class="container my-4">
						<h4 class="text-center mb-4">TAMBAH DATA</h4>
						<form action="{{ route('allowance.store') }}" method="POST">
							@csrf
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
                    <label for="namaPegawai" class="form-control-label">Nama Pegawai</label>
                    <select class="form-select" id="namaPegawai" aria-label="Default select example" name="nip">
                      <option disabled selected>Pilih...</option>
                      @foreach ($employee as $item)
											<option value="{{ $item->nip }}" data-status="{{ $item->status }}">{{ $item->nama }}</option>
                      @endforeach
                    </select>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="status" class="form-control-label">Status</label>
										<input type="text" class="form-control" id="status" name="status" readonly/>
									</div>
								</div>
							</div>
							
							<div id="pegawaiHarian" style="display: {{ $errors->has('premi_hadir_harian') || $errors->has('gaji_harian') ? 'block' : 'none' }}">
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label for="gajiPokok" class="form-control-label">Gaji Pokok</label>
											<input type="text" class="form-control" id="gajiPokok" placeholder="Nominal Gaji" name="gaji_harian"/>
											@error('gaji_harian') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="premiHadir" class="form-control-label">Premi Hadir</label>
											<input type="text" class="form-control" id="premiHadir" placeholder="Nominal Premi Hadir" name="premi_hadir_harian"/>
											@error('premi_hadir_harian') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
										</div>
									</div>
								</div>
								<div class="row justify-content-center mt-4">
									<button type="submit" class="btn bg-gradient-info col-1 mb-0">Tambah</button>
								</div>
							</div>

							<div id="pegawaiKontrak" style="display: {{ $errors->has('gaji_bulanan') || $errors->has('kos') || $errors->has('masuk_pagi') || $errors->has('prestasi') || $errors->has('komunikasi') || $errors->has('jabatan') || $errors->has('lain_lain') || $errors->has('uang_makan') || $errors->has('kasbon') || $errors->has('premi_hadir_bulanan') || $errors->has('premi_lembur') || $errors->has('doa') ? 'block' : 'none' }}">
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label for="gajiPokok" class="form-control-label">Gaji Pokok</label>
											<input type="text" class="form-control" id="gajiPokok" placeholder="Nominal Gaji Pokok" name="gaji_bulanan"/>
											@error('gaji_bulanan') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="kos" class="form-control-label">Tunjangan Kos</label>
											<input type="text" class="form-control" id="kos" placeholder="Nominal Tunjangan Kos" name="kos"/>
											@error('kos') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-2">
										<div class="form-group">
											<label for="masukPagi" class="form-control-label">Masuk Pagi</label>
											<input type="text" class="form-control" id="masukPagi" placeholder="Nominal Masuk Pagi" name="masuk_pagi"/>
											@error('masuk_pagi') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
										</div>
									</div>
									<div class="col-md-2">
										<div class="form-group">
											<label for="prestasi" class="form-control-label">Prestasi</label>
											<input type="text" class="form-control" id="prestasi" placeholder="Nominal Prestasi" name="prestasi"/>
											@error('prestasi') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
										</div>
									</div>
									<div class="col-md-2">
										<div class="form-group">
											<label for="komunikasi" class="form-control-label">Komunikasi</label>
											<input type="text" class="form-control" id="komunikasi" placeholder="Nominal Komunikasi" name="komunikasi"/>
											@error('komunikasi') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
										</div>
									</div>
									<div class="col-md-2">
										<div class="form-group">
											<label for="jabatan" class="form-control-label">Jabatan</label>
											<input type="text" class="form-control" id="jabatan" placeholder="Nominal Jabatan" name="jabatan"/>
											@error('jabatan') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
										</div>
									</div>
									<div class="col-md-2">
										<div class="form-group">
											<label for="lainLain" class="form-control-label">Lain-Lain</label>
											<input type="text" class="form-control" id="lainLain" placeholder="Nominal Lain-Lain" name="lain_lain"/>
											@error('lain_lain') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
										</div>
									</div>
									<div class="col-md-2">
										<div class="form-group">
											<label for="uangMakan" class="form-control-label">Uang Makan</label>
											<input type="text" class="form-control" id="uangMakan" placeholder="Nominal Uang Makan" name="uang_makan"/>
											@error('uang_makan') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-3">
										<div class="form-group">
											<label for="kasbon" class="form-control-label">Kasbon</label>
											<input type="text" class="form-control" id="kasbon" placeholder="Nominal Kasbon" name="kasbon"/>
											@error('kasbon') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label for="premiHadir" class="form-control-label">Premi Hadir</label>
											<input type="text" class="form-control" id="premiHadir" placeholder="Nominal Premi Hadir" name="premi_hadir_bulanan"/>
											@error('premi_hadir_bulanan') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label for="premiLembur" class="form-control-label">Premi Lembur</label>
											<input type="text" class="form-control" id="premiLembur" placeholder="Nominal Premi Lembur" name="premi_lembur"/>
											@error('premi_lembur') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label for="doa" class="form-control-label">Do'a</label>
											<input type="text" class="form-control" id="doa" placeholder="Nominal Hadir Do'a" name="doa"/>
											@error('doa') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
										</div>
									</div>
								</div>
								<div class="row justify-content-center mt-4">
										<button type="submit" class="btn bg-gradient-info col-1 mb-0">Tambah</button>
								</div>
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
		// Untuk mengisi status berdasarkan nama pegawai
		document.getElementById('namaPegawai').addEventListener('change', function () {
			let selectedOption = this.options[this.selectedIndex];
			let status = document.getElementById('status');
			let pegawaiHarian = document.getElementById('pegawaiHarian');
			let pegawaiKontrak = document.getElementById('pegawaiKontrak');

			if (selectedOption.value !== 'Pilih...') {
				status.value = selectedOption.getAttribute('data-status');
				if (status.value === 'Pegawai Harian') {
					pegawaiHarian.style.display = 'block';
					pegawaiKontrak.style.display = 'none';
				} else if (status.value === 'Pegawai Kontrak') {
					pegawaiHarian.style.display = 'none';
					pegawaiKontrak.style.display = 'block';
				} else {
					pegawaiHarian.style.display = 'none';
					pegawaiKontrak.style.display = 'none';
				}
			} else {
				status.value = '';
				pegawaiHarian.style.display = 'none';
				pegawaiKontrak.style.display = 'none';
			}
		});
	</script>
@endpush