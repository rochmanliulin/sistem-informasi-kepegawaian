@extends('layouts.app')

@section('content')
    <div class="container position-sticky z-index-sticky top-0">
        <div class="row">
            <div class="col-12">
                @include('layouts.navbars.guest.navbar')
            </div>
        </div>
    </div>
    <main class="main-content mt-0">
        <div class="glass-shape shape-large"></div>
        <div class="glass-shape shape-medium"></div>
        <div class="glass-shape shape-lower"></div>
        <section>
            <div class="page-header min-vh-100">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-4 col-lg-5 col-md-7 d-flex flex-column mx-lg-0 mx-auto">
                            <div class="card card-plain">
                                <div class="card-header pb-0 text-start">
                                    
                                    @include('components.alert')

                                    <h4 class="font-weight-bolder">Sign In</h4>
                                    <p class="mb-0">Masukan email dan password untuk sign in</p>
                                </div>
                                <div class="card-body">
                                    <form role="form" method="POST" action="{{ route('login.perform') }}">
                                        @csrf
                                        @method('post')
                                        <div class="flex flex-col mb-3">
                                            <input type="email" name="email" class="form-control form-control-lg" value="{{ old('email') }}" aria-label="Email" placeholder="Email">
                                            @error('email') <p class="text-danger text-xs pt-1"> {{$message}} </p>@enderror
                                        </div>
                                        <div class="flex flex-col mb-3">
                                            <input type="password" name="password" class="form-control form-control-lg" aria-label="Password" placeholder="Password">
                                            @error('password') <p class="text-danger text-xs pt-1"> {{$message}} </p>@enderror
                                        </div>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" name="remember" type="checkbox" id="rememberMe">
                                            <label class="form-check-label" for="rememberMe">Remember me</label>
                                        </div>
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-lg btn-dark btn-lg w-100 mt-4 mb-0">Sign in</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="card-footer text-center pt-0 px-lg-2 px-1">
                                    <p class="mb-1 text-sm mx-auto">
                                        Lupa kata sandi Anda? Reset password
                                        <a href="{{ route('reset-password') }}" class="text-dark text-gradient font-weight-bold">disini</a>
                                    </p>
                                </div>
                                {{-- <div class="card-footer text-center pt-0 px-lg-2 px-1">
                                    <p class="mb-4 text-sm mx-auto">
                                        Belum punya akun?
                                        <a href="{{ route('register') }}" class="text-dark text-gradient font-weight-bold">Sign up</a>
                                    </p>
                                </div> --}}
                                {{-- <div class="position-relative h-100 bg-gradient-primary d-lg-none">
                                    test
                                </div> --}}
                            </div>
                        </div>
                        <div class="col-6 d-lg-flex d-none h-100 my-auto pe-0 position-absolute top-0 end-0 text-center justify-content-center flex-column">
                            <div class="position-relative bg-gradient-primary h-100 m-3 px-7 border-radius-lg d-flex flex-column justify-content-center overflow-hidden"
                                style="background-image: url('https://raw.githubusercontent.com/creativetimofficial/public-assets/master/argon-dashboard-pro/assets/img/signin-ill.jpg'); background-size: cover;">
                                <span class="mask bg-gradient-primary opacity-6"></span>
                                <h4 class="mt-5 text-white font-weight-bolder position-relative">"Communication like a Book"</h4>
                                <p class="text-white position-relative">Semakin mudah tulisannya dibaca, semakin banyak usaha yang dilakukan penulis dalam prosesnya.</p>
                            </div>
                        </div>
                        <div class="container" >
                            <div class="row" style="position: relative; bottom: -23vh;">
                                <div class="col-12">
                                    @include('layouts.footers.auth.footer')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection

@push('css')
    <style>
        @media screen and (max-width: 520px) {
            .glass-shape {
                /* From https://css.glass */
                background: rgba(251, 99, 64, 0.32)!important;
                border-radius: 16px;
                box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1)!important;
                backdrop-filter: blur(5.1px);
                -webkit-backdrop-filter: blur(5.1px);
                position: absolute;
            }

            .shape-large {
                width: 80%;
                max-width: 600px;
                height: 20vh;
                transform: rotate(.015turn);
            }

            .shape-medium {
                width: 122px;
                height: 144px;
                top: 521px;
                left: 244px;
                transform: rotate(0.0793993turn);
                opacity: 0.2196;
            }

            .shape-lower {
                width: 195px;
                height: 83px;
                top: 650px;
                left: 190px;
                transform: rotate(0.1833306turn);
                opacity: 0.502313;
            }
        }
    </style>
@endpush