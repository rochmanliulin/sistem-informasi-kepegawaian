@extends('layouts.app')

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Manajemen User'])
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-center align-items-center flex-column" style="height: 82vh;">
            <img src="{{ asset('img/maintenance-2.svg') }}" alt="Development" class="w-25">
            <h2 class="mt-4 text-center">Oops! page not yet available</h2>
            <p class="text-muted mb-0 mt-2">halaman log aktivitas belum tersedia.</p>
            <p class="text-muted mb-0">gunakan halaman lain.
							<a href="{{ route('users-management.index') }}" class="text-primary custom-link">Menu Manajemen User</a>
            </p>
        </div>
        @include('layouts.footers.auth.footer')
    </div>
@endsection
