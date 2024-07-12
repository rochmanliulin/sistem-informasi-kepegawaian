{{-- <div class="px-4 pt-4"> --}}
    {{-- @if ($message = session()->has('succes'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <p class="text-white mb-0">{{ session()->get('succes') }}</p>
        </div>
    @endif
    @if ($message = session()->has('error'))
        <div class="alert alert-danger" role="alert">
            <p class="text-white mb-0">{{ session()->get('error') }}</p>
        </div>
    @endif --}}

    @if ($message = session()->has('success'))
        <div class="alert alert-success d-flex align-items-center text-white" role="alert">
            <i class="fas fa-check-circle fs-5 me-3"></i>
            <div class="flex-grow-1 fw-bold">{{ session('success') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($message = session()->has('error'))
        <div class="alert alert-danger d-flex align-items-center text-white" role="alert">
            <i class="fas fa-exclamation-circle fs-5 me-3"></i>
            <div class="flex-grow-1 fw-bold">{{ session('error') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
{{-- </div> --}}
