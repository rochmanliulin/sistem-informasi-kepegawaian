@extends('layouts.app', ['class' => 'g-sidenav-show'])

@section('content')
    <!-- Navbar -->
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl
    {{ str_contains(Request::url(), 'virtual-reality') == true ? ' mt-3 mx-3 bg-primary' : '' }}" id="navbarBlur"
    data-scroll="false">
    <div class="container-fluid py-1 px-3">
    <nav aria-label="breadcrumb" class="nav-breadcrumb">
        <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Pages</a></li>
            <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Dashboard</li>
        </ol>
        <h6 class="font-weight-bolder text-dark mb-0">Dashboard</h6>
    </nav>
    <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
        <ul class="navbar-nav flex-grow-1 justify-content-end">
            <span class="me-4 text-dark fw-bold d-none d-lg-block">Welcome back,</span>
            <li class="nav-item dropdown me-3 d-flex">
                <a href="javascript:;" class="nav-link text-dark p-0 d-flex align-items-center fw-bold fs-6" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-user-circle pe-2" style="font-size: 24px"></i>
                    {{ auth()->user()->fullname }}
                </a>
                <ul class="dropdown-menu" style="top: 0!important;">
                    <li><a class="dropdown-item" href="#">Profile</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form role="form" method="post" action="{{ route('logout') }}" id="logout-form">
                            @csrf
                            <a href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                class="dropdown-item nav-link text-black font-weight-bold px-3">
                                <i class="fas fa-sign-out-alt"></i>
                                <span class="d-inline">Log out</span>
                            </a>
                        </form>
                    </li>
                </ul>
            </li>
            <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
                <a href="javascript:;" class="nav-link text-dark p-0" id="iconNavbarSidenav">
                    <div class="sidenav-toggler-inner">
                        <i class="sidenav-toggler-line bg-dark"></i>
                        <i class="sidenav-toggler-line bg-dark"></i>
                        <i class="sidenav-toggler-line bg-dark"></i>
                    </div>
                </a>
            </li>
        </ul>
    </div>
    </div>
    </nav>
    <!-- End Navbar -->
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-center align-items-center flex-column" style="height: 82vh;">
            <img src="{{ asset('img/maintenance.svg') }}" alt="Development" class="w-25">
            <h2 class="mt-4 text-center">Oops! page under maintenance</h2>
            <p class="text-muted mb-0 mt-2">halaman dashboard belum tersedia.</p>
            <p class="text-muted mb-0">gunakan halaman lain. <a href="{{ route('payroll.index') }}" class="text-primary custom-link">Menu Payroll</a></p>
        </div>
        @include('layouts.footers.auth.footer')
    </div>
@endsection

@push('css')
    <style>
        @media screen and (max-width: 576px) {
            .nav-breadcrumb  {
                display: none !important;
            }
        }
    </style>
@endpush
{{-- @push('css')
    <style>
        .custom-link {
            position: relative;
        }

        .custom-link::before {
            content: '';
            position: absolute;
            left: 0;
            bottom: -2px;
            width: 100%;
            height: 2px;
            background-color: #fb6340;
            transform: scaleX(1);
            transition: all 0.3s ease-in-out;
        }

        .custom-link:hover::before {
            transform: scaleX(0);
        }
    </style>
@endpush

@push('js')
    <script src="./assets/js/plugins/chartjs.min.js"></script>
    <script>
        var ctx1 = document.getElementById("chart-line").getContext("2d");

        var gradientStroke1 = ctx1.createLinearGradient(0, 230, 0, 50);

        gradientStroke1.addColorStop(1, 'rgba(251, 99, 64, 0.2)');
        gradientStroke1.addColorStop(0.2, 'rgba(251, 99, 64, 0.0)');
        gradientStroke1.addColorStop(0, 'rgba(251, 99, 64, 0)');
        new Chart(ctx1, {
            type: "line",
            data: {
                labels: ["Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                datasets: [{
                    label: "Mobile apps",
                    tension: 0.4,
                    borderWidth: 0,
                    pointRadius: 0,
                    borderColor: "#fb6340",
                    backgroundColor: gradientStroke1,
                    borderWidth: 3,
                    fill: true,
                    data: [50, 40, 300, 220, 500, 250, 400, 230, 500],
                    maxBarThickness: 6

                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false,
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index',
                },
                scales: {
                    y: {
                        grid: {
                            drawBorder: false,
                            display: true,
                            drawOnChartArea: true,
                            drawTicks: false,
                            borderDash: [5, 5]
                        },
                        ticks: {
                            display: true,
                            padding: 10,
                            color: '#fbfbfb',
                            font: {
                                size: 11,
                                family: "Open Sans",
                                style: 'normal',
                                lineHeight: 2
                            },
                        }
                    },
                    x: {
                        grid: {
                            drawBorder: false,
                            display: false,
                            drawOnChartArea: false,
                            drawTicks: false,
                            borderDash: [5, 5]
                        },
                        ticks: {
                            display: true,
                            color: '#ccc',
                            padding: 20,
                            font: {
                                size: 11,
                                family: "Open Sans",
                                style: 'normal',
                                lineHeight: 2
                            },
                        }
                    },
                },
            },
        });
    </script>
@endpush --}}
