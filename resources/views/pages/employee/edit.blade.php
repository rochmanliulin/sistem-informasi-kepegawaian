@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
	@include('layouts.navbars.auth.topnav', ['title' => 'Edit Data Pegawai'])
	<div class="container py-4">
		<div class="row">
			<div class="col-xl-12 col-sm-6 mb-xl-0">
				<div class="card">
					<div class="container my-4">
						<h4 class="text-center mb-4">EDIT DATA</h4>
						{{-- route('nama route', id) --}}
						<form action="{{ route('employee.update', $employee->nip) }}" method="POST" onsubmit="return validateForm()">
							@csrf
							@method('PATCH')
							{{--
								Method patch/put -> method bawaan jika menggunakan route resource
								patch -> mengupdate semua input
								put -> mengupdate input tertentu
							--}}
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<label for="nip" class="form-control-label">NIP</label>
										<input type="text" class="form-control" id="nip" name="nip" disabled value="{{ $employee->nip }}"/>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label for="nama" class="form-control-label">Nama</label>
										<input type="text" class="form-control" id="nama" placeholder="Nama Pegawai" name="nama" required value="{{ $employee->nama }}"/>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label for="creditedAccount" class="form-control-label">Credited Account</label>
										<input type="text" class="form-control" id="creditedAccount" placeholder="Credited Account" name="credited_account" value="{{ $employee->credited_account }}"/>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label for="jabatan" class="form-control-label">Jabatan</label>
										<input type="text" class="form-control" id="jabatan" placeholder="Jabatan" name="jabatan" value="{{ $employee->jabatan }}"/>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="departemen" class="form-control-label">Departemen</label>
										<input type="text" class="form-control" id="departemen" placeholder="Departemen" name="departemen" value="{{ $employee->departemen }}"/>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label for="tgl_masuk_kerja" class="form-control-label">Tanggal Masuk Kerja</label>
										<input type="text" class="form-control bg-white date" id="tglMasukKerja" placeholder="Tanggal Masuk Kerja" name="tgl_masuk_kerja" onfocus="focused(this)" onfocusout="defocused(this)" value="{{ $employee->tgl_masuk_kerja }}"/>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="status" class="form-control-label">Status Pegawai</label>
										<select class="form-select" name="status" id="status" aria-label="Default select example" required>
											<option disabled selected>Pilih...</option>
											<option value="Pegawai Kontrak" {{ $employee->status === 'Pegawai Kontrak' ? 'selected' : '' }}>Pegawai Kontrak</option>
											<option value="Pegawai Harian" {{ $employee->status === 'Pegawai Harian' ? 'selected' : '' }}>Pegawai Harian</option>
										</select>
										<p id="error-status" class='text-danger text-xs pt-1'></p>
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
		// Format tanggal
		flatpickr('.date', {
      altInput: true,
      altFormat: "j F Y",
      dateFormat: "Y-m-d",
			maxDate: "today"
    });

		// Validasi form jika status yang dipilih user adalah Pilih... / sama dengan tidak memilih apapun
		function validateForm() {
			let status = document.getElementById('status').value;

			if (status === 'Pilih...') {
				document.getElementById('error-status').innerText = 'Pilih status pegawai!';
				
				return false;
			}
			
			return true;
		}
	</script>
@endpush