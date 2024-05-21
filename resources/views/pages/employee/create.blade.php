@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
	@include('layouts.navbars.auth.topnav', ['title' => 'Tambah Data Pegawai'])
	<div class="container py-4">
		<div class="row">
			<div class="col-xl-12 col-sm-6 mb-xl-0">
				<div class="card">
					<div class="container my-4">
						<h4 class="text-center mb-4">TAMBAH DATA</h4>
						<form action="{{ route('employee.store') }}" method="POST" onsubmit="return validateForm()">
							@csrf
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<label for="nip" class="form-control-label">NIP</label>
										<input type="text" class="form-control" id="nip" placeholder="166" name="nip" required/>
										@error('nip') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label for="nama" class="form-control-label">Nama</label>
										<input type="text" class="form-control" id="nama" placeholder="Nama Pegawai" name="nama" required/>
										@error('nama') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label for="creditedAccount" class="form-control-label">Credited Account</label>
										<input type="number" class="form-control" id="creditedAccount" placeholder="Credited Account" name="credited_account"/>
										@error('credited_account') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label for="jabatan" class="form-control-label">Jabatan</label>
										<input type="text" class="form-control" id="jabatan" placeholder="Jabatan" name="jabatan"/>
										@error('jabatan') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="departemen" class="form-control-label">Departemen</label>
										<input type="text" class="form-control" id="departemen" placeholder="Departemen" name="departemen"/>
										@error('departemen') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label for="tgl_masuk_kerja" class="form-control-label">Tanggal Masuk Kerja</label>
										<input type="text" class="form-control bg-white date" id="tglMasukKerja" placeholder="Tanggal Masuk Kerja" name="tgl_masuk_kerja" onfocus="focused(this)" onfocusout="defocused(this)" />
										@error('tgl_masuk_kerja') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="status" class="form-control-label">Status Pegawai</label>
										<select class="form-select" name="status" id="status" required aria-label="Default select example" >
											<option disabled selected>Pilih...</option>
											<option value="Pegawai Kontrak">Pegawai Kontrak</option>
											<option value="Pegawai Harian">Pegawai Harian</option>
										</select>
										@error('status') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
									</div>
								</div>
							</div>
							<div class="row justify-content-center mt-4">
									<button type="submit" class="btn bg-gradient-info col-1 mb-0">Tambah</button>
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
		// Format tanggal
		flatpickr('.date', {
      altInput: true,
      altFormat: "j F Y",
      dateFormat: "Y-m-d",
			maxDate: "today"
    });
	</script>
@endpush