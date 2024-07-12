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
            <form action="{{ route('users-management.update', $user->id) }}" method="POST" enctype="multipart/form-data">
              @csrf
              @method('PATCH')
              <div class="row">
                <input type="hidden" name="id" value="{{ $user->id }}">
                <div class="col-6">
                  <label class="form-label">Nama Lengkap</label>
                  <div class="input-group">
                    <input id="fullName" name="fullname" class="form-control" type="text" placeholder="Nama Lengkap" onfocus="focused(this)" onfocusout="defocused(this)" value="{{ $user->fullname }}">
                  </div>
                  @error('fullname') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
                </div>
                <div class="col-6">
                  <label class="form-label">Nomor Induk Pegawai</label>
                  <div class="input-group">
                    <input id="nip" name="nip" class="form-control" type="text" placeholder="Nomor Induk Pegawai" value="{{ $user->nip }}" readonly>
                  </div>
                  @error('nip') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
                </div>
              </div>
              <div class="row">
                <div class="col-12">
                  <label class="form-label">Role</label>
                  <div class="input-group">
                    <select class="form-select" name="role" id="" aria-expanded="false">
                      <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Administrator</option>
                      <option value="editor" {{ $user->role === 'editor' ? 'selected' : '' }}>Admin Keuangan</option>
                      <option value="employee" {{ $user->role === 'employee' ? 'selected' : '' }}>Pegawai</option>
                    </select>
                  </div>
                  @error('role') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
                </div>
              </div>
              <div class="row">
                <div class="col-6">
                  <label class="form-label">Email</label>
                  <div class="input-group">
                    <input id="email" name="email" class="form-control" type="text" placeholder="Email" onfocus="focused(this)" onfocusout="defocused(this)" value="{{ $user->email }}">
                  </div>
                  @error('email') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
                </div>
                <div class="col-6">
                  <label class="form-label">No. WhatsApp</label>
                  <div class="input-group">
                    <input id="whatsappNumber" name="whatsapp_number" class="form-control" type="text" placeholder="62823xxxxxxx2" onfocus="focused(this)" onfocusout="defocused(this)" value="{{ $user->whatsapp_number }}">
                  </div>
                  @error('whatsapp_number') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
                </div>
              </div>
              <div class="d-flex flex-column">
                <label class="mt-4 form-label">Foto Profile</label>
                @if($user->profile_image)
                  <div class="profile-img">
                    <img src="{{ asset('user_profile/' . $user->profile_image) }}" alt="Foto Profil" style="max-width: 200px; border-radius: 10px;" id="profileImagePreview" onclick="chooseProfileImage()">
                    <i class="fas fa-camera-retro" onclick="chooseProfileImage()"></i>
                    <input type="file" name="profile_image" accept="image/*" id="profileImage" class="form-control mt-2" style="display: none;" onchange="previewImage(event)">
                  </div>
                @else
                  <div class="profile-img">
                    <img src="{{ asset('user_profile/' . $user->profile_image) }}" alt="Foto Profil" style="max-width: 200px; border-radius: 10px;" id="profileImagePreview" onclick="chooseProfileImage()">
                    <i class="fas fa-camera-retro" onclick="chooseProfileImage()"></i>
                    <input type="file" name="profile_image" accept="image/*" id="profileImage" class="form-control mt-2" onchange="previewImage(event)">
                  </div>
                @endif
              </div>

              @error('profile_image') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
              <div class="d-flex justify-content-end mt-4">
                <a href="{{ route('users-management.index') }}" class="btn btn-light m-0">Kembali</a>
                <button type="submit" class="btn bg-gradient-success m-0 ms-2">Update</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    @include('layouts.footers.auth.footer')
  </div>
@endsection

@push('css')
  <style>
    #profileImagePreview {
      transition: filter 0.3s ease;
    }

    #profileImagePreview:hover {
      cursor: pointer;
      filter: brightness(75%);
    }

    .profile-img i {
      font-size: 2.5rem !important;
      position: absolute;
      top: 70%;
      left: 10.5%;
      transform: translate(-50%, -50%);
      z-index: 1000;
      color: rgba(0, 0, 0, 0.5);
      cursor: pointer;
    }
  </style>
@endpush

@push('js')
  <script>
    let error = "{{ session('error') }}";
    let image = "{{ $user->profile_image }}";
    console.log(image);

    // Alert error
		if (error !== '') {
			Swal.fire({
					icon: 'error',
					title: 'Waduh :(',
					text: error,
					showConfirmButton: false,
					timer: 3000,
					timerProgressBar: true,
			});
		}

    function chooseProfileImage() {
      document.getElementById('profileImage').click();
    }

    function previewImage(event) {
      const input = event.target;
      const profileImage = document.getElementById('profileImagePreview');
      const profileIcon = document.querySelector('.profile-img i');
      const fileInput = document.getElementById('profileImage');
      
      if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function (e) {
          document.getElementById('profileImagePreview').setAttribute('src', e.target.result);
        };
        if (image === '') {
          profileImage.style.display = 'inline-block';
          profileIcon.style.display = 'inline-block';
          fileInput.style.display = 'none';
        }
        reader.readAsDataURL(input.files[0]);
      }
    }

    function toggleProfileImageDisplay() {
      const profileImage = document.getElementById('profileImagePreview');
      const profileIcon = document.querySelector('.profile-img i');
      const fileInput = document.getElementById('profileImage');

      if (image !== '') {
        profileImage.style.display = 'inline-block';
        profileIcon.style.display = 'inline-block';
        fileInput.style.display = 'none';
      } else {
        profileImage.style.display = 'none';
        profileIcon.style.display = 'none';
        fileInput.style.display = 'inline-block';
      }
    }

    window.onload = function() {
      toggleProfileImageDisplay();
    };

    // document.getElementById('profileImage').addEventListener('change', function () {
    //   toggleProfileImageDisplay();
    //   previewImage(event);
    // });
  </script>
@endpush