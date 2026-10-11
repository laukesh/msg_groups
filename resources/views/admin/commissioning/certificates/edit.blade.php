@extends('layouts.app')

@section('title', 'Edit Commissioning Certificate')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                Edit Commissioning Certificate
            </h4>

            <div class="text-muted">
                {{ $certificate->certificate_no }}
            </div>

        </div>

        <a
            href="{{ route('admin.projects.commissioning.certificates.show', [$project, $certificate]) }}"
            class="btn btn-outline-secondary"
        >
            <i class="bi bi-arrow-left me-1"></i>
            Back
        </a>

    </div>


    @if($certificate->status === 'Rejected')

        <div class="alert alert-danger">

            <div class="fw-semibold">
                Certificate Rejected
            </div>

            <div class="small mt-1">
                {{ $certificate->rejection_reason }}
            </div>

            <div class="small mt-2">
                After updating, the certificate will return to Draft status.
            </div>

        </div>

    @endif


    @if($errors->any())

        <div class="alert alert-danger">

            <ul class="mb-0">

                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

    @endif


    <form
        method="POST"
        action="{{ route('admin.projects.commissioning.certificates.update', [$project, $certificate]) }}"
    >

        @csrf

        @method('PUT')

        @include(
            'admin.commissioning.certificates._form',
            ['certificate' => $certificate]
        )

        <div class="d-flex justify-content-end gap-2 mb-5">

            <a
                href="{{ route('admin.projects.commissioning.certificates.show', [$project, $certificate]) }}"
                class="btn btn-light"
            >
                Cancel
            </a>

            <button
                type="submit"
                class="btn btn-primary"
            >
                <i class="bi bi-save me-1"></i>
                Update Certificate
            </button>

        </div>

    </form>

</div>

@endsection