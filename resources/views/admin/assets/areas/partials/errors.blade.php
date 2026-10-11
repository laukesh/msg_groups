@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">

        <div class="fw-semibold mb-2">
            Please correct the following errors:
        </div>

        <ul class="mb-0 ps-3">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>

        <button type="button"
                class="btn-close"
                data-bs-dismiss="alert"
                aria-label="Close">
        </button>

    </div>
@endif
