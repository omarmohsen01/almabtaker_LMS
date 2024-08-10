@if (session()->has('success'))
    <div class="alert-big alert alert-success  alert-dismissible fade show " role="alert">
        <div class="alert-content">
            <h6 class='alert-heading'>Success </h6>
            <p>{{ session('success') }}</p>
            <button type="button" class="btn-close text-capitalize" data-bs-dismiss="alert" aria-label="Close">
                {{-- <img src="{{ asset('dashboard/images/svg/x.svg') }}" alt="x" class="svg" aria-hidden="true"> --}}
            </button>
        </div>
    </div>
@elseif (session()->has('fail'))
    <div class="alert-big alert alert-danger  alert-dismissible fade show " role="alert">
        <div class="alert-content">
            <h6 class='alert-heading'>Error </h6>
            <p>{{ session('fail') }}</p>
            <button type="button" class="btn-close text-capitalize" data-bs-dismiss="alert" aria-label="Close">
                {{-- <img src="{{ asset('dashboard/images/svg/x.svg') }}" alt="x" class="svg" aria-hidden="true"> --}}
            </button>
        </div>
    </div>
@endif
