@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
  @include('layouts.navbars.auth.topnav', ['title' => 'User Baru'])
  <div class="container-fluid py-4">
    <div class="d-flex justify-content-center mb-5">
      <div class="col-lg-9 mt-lg-0 mt-4">
        <div class="card mt-4">
          <div class="card-header">
            <h5>User Baru</h5>
          </div>
          <div class="card-body pt-0">
            <form action="{{ route('users-management.store') }}" method="POST" enctype="multipart/form-data">
              @csrf
              <div class="row">
                <div class="col-6">
                  <label class="form-label">Nama Lengkap</label>
                  <div class="input-group">
                    <select class="form-select" name="fullname" id="fullName">
                      <option disabled selected>Pilih...</option>
                      @foreach ($employee as $item)
                        <option value="{{ $item->nama }}" data-nip="{{ $item->nip }}">{{ $item->nama }}</option>
                      @endforeach
                    </select>
                  </div>
                  @error('fullname') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
                </div>
                <div class="col-6">
                  <label class="form-label">Nomor Induk Pegawai</label>
                  <div class="input-group">
                    <input id="nip" name="nip" class="form-control" type="text" placeholder="Nomor Induk Pegawai" readonly>
                  </div>
                  @error('nip') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
                </div>
              </div>
              <div class="row">
                <div class="col-6">
                  <label class="form-label">Password</label>
                  <div class="input-group">
                    <input type="password" id="password" name="password" class="form-control" type="text" placeholder="Password" onfocus="focused(this)" onfocusout="defocused(this)">
                  </div>
                  @error('password') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
                </div>
                <div class="col-6">
                  <label class="form-label">Konfirmasi Password</label>
                  <div class="input-group">
                    <input type="password" id="confirmPassword" name="confirm_password" class="form-control" type="text" placeholder="Masukan Ulang Password" onfocus="focused(this)" onfocusout="defocused(this)">
                  </div>
                  @error('confirm_password') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
                </div>
              </div>
              <div class="row">
                <div class="col-12">
                  <label class="form-label">Role</label>
                  <div class="input-group">
                    <select class="form-select" name="role" id="role">
                      <option disabled selected>Role</option>
                      <option value="admin">Administrator</option>
                      <option value="editor">Admin Keuangan</option>
                      <option value="employee">Pegawai</option>
                    </select>
                  </div>
                  @error('role') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
                </div>
              </div>
              <div class="row">
                <div class="col-6">
                  <label class="form-label">Email</label>
                  <div class="input-group">
                    <input id="email" name="email" class="form-control" type="text" placeholder="Email" onfocus="focused(this)" onfocusout="defocused(this)">
                  </div>
                  @error('email') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
                </div>
                <div class="col-6">
                  <label class="form-label">No. WhatsApp</label>
                  <div class="input-group">
                    <input id="whatsappNumber" name="whatsapp_number" class="form-control" type="text" placeholder="62823xxxxxxx2" onfocus="focused(this)" onfocusout="defocused(this)">
                  </div>
                  @error('whatsapp_number') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
                </div>
              </div>
              <div class="d-flex flex-column">
                <label class="mt-4 form-label" for="profileImage">Foto Profile</label>
                <input type="file" name="profile_image" accept="image/*" id="profileImage" class="form-control" onfocus="focused(this)" onfocusout="defocused(this)">
              </div>
              @error('profile_image') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
              <div class="d-flex justify-content-end mt-4">
                <a href="{{ route('users-management.index') }}" class="btn btn-light m-0">Kembali</a>
                <button type="submit" class="btn bg-gradient-info m-0 ms-2">Simpan</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    @include('layouts.footers.auth.footer')
  </div>
@endsection

@push('js')
  <script>
    // Untuk mengisi status berdasarkan nama pegawai
		document.getElementById('fullName').addEventListener('change', function () {
			let selectedOption = this.options[this.selectedIndex];
			let nip = document.getElementById('nip');

			if (selectedOption.value !== 'Pilih...') {
				nip.value = selectedOption.getAttribute('data-nip');
			} else {
				nip.value = '';
			}
		});
  </script>
@endpush