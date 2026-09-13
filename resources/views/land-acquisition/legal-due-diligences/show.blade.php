@extends('layouts.app')

@section('content')

<div class="container-fluid">


    {{-- =========================================================
        HEADER
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3 class="mb-1">
                Legal Due Diligence Details
            </h3>

            <p class="text-muted mb-0">

                {{ $land->land_code }}
                -
                {{ $land->land_name }}

            </p>

        </div>


        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.land.lands.legal-due-diligences.edit',
                    [
                        'land' => $land,
                        'legal_due_diligence' => $dueDiligence
                    ]
                ) }}"
                class="btn btn-primary"
            >
                Edit
            </a>


            <a
                href="{{ route(
                    'admin.land.lands.legal-due-diligences.index',
                    $land
                ) }}"
                class="btn btn-outline-secondary"
            >
                Back to Reviews
            </a>

        </div>

    </div>


    {{-- =========================================================
        SUCCESS
    ========================================================== --}}

    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


    {{-- =========================================================
        BASIC INFORMATION
    ========================================================== --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Basic Information
            </strong>

        </div>


        <div class="card-body">

            <div class="row g-4">


                <div class="col-md-3">

                    <small class="text-muted">
                        Due Diligence Type
                    </small>

                    <div class="fw-semibold">
                        {{ $dueDiligence->type }}
                    </div>

                </div>


                <div class="col-md-3">

                    <small class="text-muted">
                        Reference Number
                    </small>

                    <div class="fw-semibold">

                        {{ $dueDiligence->reference_no ?: '—' }}

                    </div>

                </div>


                <div class="col-md-3">

                    <small class="text-muted">
                        Assessment Date
                    </small>

                    <div>

                        @if($dueDiligence->assessment_date)

                            {{
                                $dueDiligence
                                    ->assessment_date
                                    ->format('d-m-Y')
                            }}

                        @else

                            —

                        @endif

                    </div>

                </div>


                <div class="col-md-3">

                    <small class="text-muted">
                        Conducted By
                    </small>

                    <div>

                        {{ $dueDiligence->conducted_by ?: '—' }}

                    </div>

                </div>


                <div class="col-md-3">

                    <small class="text-muted">
                        Status
                    </small>

                    <div>

                        @switch($dueDiligence->status)

                            @case('Approved')

                                <span class="badge bg-success">
                                    Approved
                                </span>

                                @break

                            @case('Rejected')

                                <span class="badge bg-danger">
                                    Rejected
                                </span>

                                @break

                            @case('Completed')

                                <span class="badge bg-primary">
                                    Completed
                                </span>

                                @break

                            @case('Under Review')

                                <span class="badge bg-warning text-dark">
                                    Under Review
                                </span>

                                @break

                            @case('Pending')

                                <span class="badge bg-secondary">
                                    Pending
                                </span>

                                @break

                            @default

                                <span class="badge bg-secondary">
                                    {{ $dueDiligence->status }}
                                </span>

                        @endswitch

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        SUMMARY
    ========================================================== --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Legal Assessment Summary</strong>
        </div>

        <div class="card-body">

            @if($dueDiligence->summary)

                {!! nl2br(
                    e($dueDiligence->summary)
                ) !!}

            @else

                <span class="text-muted">
                    No summary provided.
                </span>

            @endif

        </div>

    </div>


    {{-- =========================================================
        FINDINGS
    ========================================================== --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Legal Findings</strong>
        </div>

        <div class="card-body">

            @if($dueDiligence->findings)

                {!! nl2br(
                    e($dueDiligence->findings)
                ) !!}

            @else

                <span class="text-muted">
                    No findings provided.
                </span>

            @endif

        </div>

    </div>


    {{-- =========================================================
        RECOMMENDATIONS
    ========================================================== --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Recommendations</strong>
        </div>

        <div class="card-body">

            @if($dueDiligence->recommendations)

                {!! nl2br(
                    e($dueDiligence->recommendations)
                ) !!}

            @else

                <span class="text-muted">
                    No recommendations provided.
                </span>

            @endif

        </div>

    </div>


    {{-- =========================================================
        REMARKS
    ========================================================== --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Remarks</strong>
        </div>

        <div class="card-body">

            @if($dueDiligence->remarks)

                {!! nl2br(
                    e($dueDiligence->remarks)
                ) !!}

            @else

                <span class="text-muted">
                    No remarks provided.
                </span>

            @endif

        </div>

    </div>


    {{-- =========================================================
        DELETE
    ========================================================== --}}

    <div class="card border-danger">

        <div class="card-header text-danger">

            <strong>
                Danger Zone
            </strong>

        </div>


        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <strong class="text-danger">
                        Delete Legal Due Diligence
                    </strong>

                    <div class="text-muted">
                        This record will be moved to trash.
                    </div>

                </div>


                <form
                    method="POST"
                    action="{{ route(
                        'admin.land.lands.legal-due-diligences.destroy',
                        [
                            'land' => $land,
                            'legal_due_diligence' => $dueDiligence
                        ]
                    ) }}"
                    onsubmit="return confirm(
                        'Are you sure you want to delete this legal due diligence record?'
                    );"
                >

                    @csrf

                    @method('DELETE')

                    <button
                        type="submit"
                        class="btn btn-danger"
                    >
                        Delete
                    </button>

                </form>

            </div>

        </div>

    </div>

</div>

@endsection