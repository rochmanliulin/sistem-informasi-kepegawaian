<aside class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4 "
    id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
            aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand m-0" href="{{ route('home') }}"
            target="_blank">
            {{-- <img src="./img/logo-ct-dark.png" class="navbar-brand-img h-100" alt="main_logo">
            <span class="ms-1 font-weight-bold">SIM Kepegawaian PGS</span> --}}
            <span class="ms-4 ps-2 font-weight-bold">SIM Kepegawaian PGS</span>
        </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse h-auto w-auto " id="sidenav-collapse-main">
        <ul class="navbar-nav">
            @cannot('isEmployee')
            <li class="nav-item">
                <a class="nav-link {{ Route::currentRouteName() == 'home' ? 'active' : '' }}" href="{{ route('home') }}">
                    <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-tv-2 text-primary text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Dashboard</span>
                </a>
            </li>
            @endcannot

            @cannot('isAdmin')
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" data-bs-target="#subMenuReport">
                    <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fas fa-folder-open text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Laporan</span>
                </a>
                <ul class="navbar-nav collapse {{ request()->is('user*') ? 'show' : '' }}" id="subMenuReport">
                    <!-- Submenu items go here -->
                    <li class="nav-item">
                        <a class="nav-link {{ Route::currentRouteName() == 'user.index' ? 'active' : '' }}" href="{{ route('user.index') }}">
                            <span class="nav-link-text ms-2">Gaji</span>
                        </a>
                    </li>
                </ul>
            </li>
            @endcannot

            @can('isEditorOrAdmin')
                <li class="nav-item mt-3">
                    <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Data</h6>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('employee*') ? 'active' : '' }}" href="{{ route('employee.index') }}">
                        <div
                            class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="fas fa-users text-dark text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Pegawai</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('allowance*') ? 'active' : '' }}" href="{{ route('allowance.index') }}">
                        <div
                            class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="fas fa-dollar-sign text-dark text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Tunjangan</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" data-bs-target="#subMenuFingerprint">
                        <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="fas fa-fingerprint text-dark text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Fingerprint</span>
                    </a>
                    <ul class="navbar-nav collapse {{ request()->is('fingerprint*') ? 'show' : '' }}" id="subMenuFingerprint">
                        <!-- Submenu items go here -->
                        <li class="nav-item">
                            <a class="nav-link {{ Route::currentRouteName() == 'fingerprint.index' ? 'active' : '' }}" href="{{ route('fingerprint.index') }}">
                                <span class="nav-link-text ms-2">File Excel</span>
                            </a>
                        </li>
                        <!-- Add more submenu items as needed -->
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" data-bs-target="#subMenuSalary">
                        <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="fas fa-hand-holding-usd text-dark text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Laporan Gaji</span>
                    </a>
                    <ul class="navbar-nav collapse {{ request()->is('salary*') ? 'show' : '' }}" id="subMenuSalary">
                        <!-- Submenu items go here -->
                        <li class="nav-item">
                            <a class="nav-link {{ Route::currentRouteName() == 'overtime-salary.index' ? 'active' : '' }}" href="{{ route('overtime-salary.index') }}">
                                <span class="nav-link-text ms-2">Gaji Lembur</span>
                            </a>
                        </li>
                        {{-- <li class="nav-item">
                            <a class="nav-link {{ Route::currentRouteName() == 'overtime-salary' ? 'active' : '' }}" href="{{ route('overtime-salary') }}">
                                <span class="nav-link-text ms-2">Gaji Bulanan</span>
                            </a>
                        </li> --}}
                        <!-- Add more submenu items as needed -->
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Route::currentRouteName() == 'payroll.index' ? 'active' : '' }}" href="{{ route('payroll.index') }}">
                        <div
                            class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="fas fa-file-invoice-dollar text-dark text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Payroll</span>
                    </a>
                </li>
            @endcan

            @can('isAdmin')
            <li class="nav-item mt-5">
                <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">User</h6>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Route::currentRouteName() == 'users-management.index' ? 'active' : '' }}" href="{{ route('users-management.index') }}">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fas fa-user-cog text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Manajemen User</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Route::currentRouteName() == 'users-activity.index' ? 'active' : '' }}" href="{{ route('users-activity.index') }}">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fas fa-info-circle text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Log Aktivitas</span>
                </a>
            </li>
            @endcan

            @can('isEditor')
            <li class="nav-item mt-5">
                <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Tutorial</h6>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Route::currentRouteName() == 'tutorial-video.index' ? 'active' : '' }}" href="{{ route('tutorial-video.index') }}">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fas fa-video text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Video Tutorial</span>
                </a>
            </li>
            @endcan
        </ul>
    </div>
</aside>
