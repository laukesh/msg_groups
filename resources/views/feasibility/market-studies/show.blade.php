@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                Market Study
            </h4>

            <div class="text-muted">
                {{ $marketStudy->study_number }}
            </div>
        </div>

        <div class="d-flex gap-2">

            {{-- Back to Market Studies --}}
            <a
                href="{{ route(
                    'admin.land.lands.feasibility-assessments.market-studies.index',
                    [
                        'land' => $land->id,
                        'feasibilityAssessment' => $feasibilityAssessment->id,
                    ]
                ) }}"
                class="btn btn-secondary"
            >
                ← Back
            </a>

            {{-- Edit --}}
            <a
                href="{{ route(
                    'admin.land.lands.feasibility-assessments.market-studies.edit',
                    [
                        'land' => $land->id,
                        'feasibilityAssessment' => $feasibilityAssessment->id,
                        'marketStudy' => $marketStudy->id,
                    ]
                ) }}"
                class="btn btn-primary"
            >
                Edit
            </a>

        </div>

    </div>


    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif


    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="alert alert-danger">

            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

        </div>
    @endif


    {{-- Basic Information --}}
    <div class="card mb-4">

        <div class="card-header">
            <strong>Basic Information</strong>
        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-6 mb-3">

                    <label class="form-label text-muted">
                        Study Number
                    </label>

                    <div class="fw-semibold">
                        {{ $marketStudy->study_number ?? '-' }}
                    </div>

                </div>


                <div class="col-md-6 mb-3">

                    <label class="form-label text-muted">
                        Title
                    </label>

                    <div class="fw-semibold">
                        {{ $marketStudy->title ?? '-' }}
                    </div>

                </div>


                <div class="col-md-4 mb-3">

                    <label class="form-label text-muted">
                        Study Date
                    </label>

                    <div>
                        {{ $marketStudy->study_date
                            ? \Carbon\Carbon::parse($marketStudy->study_date)->format('d-m-Y')
                            : '-' }}
                    </div>

                </div>


                <div class="col-md-4 mb-3">

                    <label class="form-label text-muted">
                        Study Period
                    </label>

                    <div>
                        {{ $marketStudy->study_period ?? '-' }}
                    </div>

                </div>


                <div class="col-md-4 mb-3">

                    <label class="form-label text-muted">
                        Status
                    </label>

                    <div>

                        @php
                            $status = $marketStudy->status;
                        @endphp

                        @if($status === 'Draft')

                            <span class="badge bg-secondary">
                                Draft
                            </span>

                        @elseif($status === 'Submitted')

                            <span class="badge bg-warning text-dark">
                                Submitted
                            </span>

                        @elseif($status === 'Approved')

                            <span class="badge bg-success">
                                Approved
                            </span>

                        @elseif($status === 'Rejected')

                            <span class="badge bg-danger">
                                Rejected
                            </span>

                        @else

                            <span class="badge bg-secondary">
                                {{ $status ?? 'N/A' }}
                            </span>

                        @endif

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Market Information --}}
    <div class="card mb-4">

        <div class="card-header">
            <strong>Market Information</strong>
        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-6 mb-3">

                    <label class="form-label text-muted">
                        Market Location
                    </label>

                    <div>
                        {{ $marketStudy->market_location ?? '-' }}
                    </div>

                </div>


                <div class="col-md-6 mb-3">

                    <label class="form-label text-muted">
                        Market Segment
                    </label>

                    <div>
                        {{ $marketStudy->market_segment ?? '-' }}
                    </div>

                </div>


                <div class="col-md-12 mb-3">

                    <label class="form-label text-muted">
                        Market Overview
                    </label>

                    <div class="border rounded p-3 bg-light">
                        {!! nl2br(e($marketStudy->market_overview ?? '-')) !!}
                    </div>

                </div>


                <div class="col-md-12 mb-3">

                    <label class="form-label text-muted">
                        Market Trends
                    </label>

                    <div class="border rounded p-3 bg-light">
                        {!! nl2br(e($marketStudy->market_trends ?? '-')) !!}
                    </div>

                </div>


                <div class="col-md-12 mb-3">

                    <label class="form-label text-muted">
                        Target Market
                    </label>

                    <div class="border rounded p-3 bg-light">
                        {!! nl2br(e($marketStudy->target_market ?? '-')) !!}
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Market Size & Growth --}}
    <div class="card mb-4">

        <div class="card-header">
            <strong>Market Size & Growth</strong>
        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-4 mb-3">

                    <label class="form-label text-muted">
                        Market Size
                    </label>

                    <div class="fw-semibold">
                        {{ $marketStudy->market_size ?? '-' }}
                    </div>

                </div>


                <div class="col-md-4 mb-3">

                    <label class="form-label text-muted">
                        Growth Rate
                    </label>

                    <div class="fw-semibold">

                        @if($marketStudy->growth_rate !== null)
                            {{ $marketStudy->growth_rate }}%
                        @else
                            -
                        @endif

                    </div>

                </div>


                <div class="col-md-12 mb-3">

                    <label class="form-label text-muted">
                        Growth Outlook
                    </label>

                    <div class="border rounded p-3 bg-light">
                        {!! nl2br(e($marketStudy->growth_outlook ?? '-')) !!}
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Market Analysis --}}
    <div class="card mb-4">

        <div class="card-header">
            <strong>Market Analysis</strong>
        </div>

        <div class="card-body">

            <div class="mb-4">

                <label class="form-label text-muted">
                    Key Drivers
                </label>

                <div class="border rounded p-3 bg-light">
                    {!! nl2br(e($marketStudy->key_drivers ?? '-')) !!}
                </div>

            </div>


            <div class="mb-4">

                <label class="form-label text-muted">
                    Key Constraints
                </label>

                <div class="border rounded p-3 bg-light">
                    {!! nl2br(e($marketStudy->key_constraints ?? '-')) !!}
                </div>

            </div>


            <div>

                <label class="form-label text-muted">
                    Key Assumptions
                </label>

                <div class="border rounded p-3 bg-light">
                    {!! nl2br(e($marketStudy->key_assumptions ?? '-')) !!}
                </div>

            </div>

        </div>

    </div>


    {{-- Findings & Recommendation --}}
    <div class="card mb-4">

        <div class="card-header">
            <strong>Findings & Recommendation</strong>
        </div>

        <div class="card-body">

            <div class="mb-4">

                <label class="form-label text-muted">
                    Key Findings
                </label>

                <div class="border rounded p-3 bg-light">
                    {!! nl2br(e($marketStudy->key_findings ?? '-')) !!}
                </div>

            </div>


            <div>

                <label class="form-label text-muted">
                    Recommendation
                </label>

                <div class="border rounded p-3 bg-light">
                    {!! nl2br(e($marketStudy->recommendation ?? '-')) !!}
                </div>

            </div>

        </div>

    </div>


    {{-- Audit Information --}}
    <div class="card mb-4">

        <div class="card-header">
            <strong>Audit Information</strong>
        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-4">

                    <label class="form-label text-muted">
                        Created At
                    </label>

                    <div>
                        {{ $marketStudy->created_at
                            ? $marketStudy->created_at->format('d-m-Y H:i')
                            : '-' }}
                    </div>

                </div>


                <div class="col-md-4">

                    <label class="form-label text-muted">
                        Updated At
                    </label>

                    <div>
                        {{ $marketStudy->updated_at
                            ? $marketStudy->updated_at->format('d-m-Y H:i')
                            : '-' }}
                    </div>

                </div>


                <div class="col-md-4">

                    <label class="form-label text-muted">
                        Created By
                    </label>

                    <div>
                        {{ $marketStudy->created_by ?? '-' }}
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Bottom Actions --}}
    <div class="d-flex justify-content-between mb-4">

        <a
            href="{{ route(
                'admin.land.lands.feasibility-assessments.show',
                [
                    'land' => $land->id,
                    'feasibilityAssessment' => $feasibilityAssessment->id,
                ]
            ) }}"
            class="btn btn-outline-secondary"
        >
            ← Feasibility Assessment
        </a>


        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.land.lands.feasibility-assessments.market-studies.edit',
                    [
                        'land' => $land->id,
                        'feasibilityAssessment' => $feasibilityAssessment->id,
                        'marketStudy' => $marketStudy->id,
                    ]
                ) }}"
                class="btn btn-primary"
            >
                Edit Market Study
            </a>


            <form
                action="{{ route(
                    'admin.land.lands.feasibility-assessments.market-studies.destroy',
                    [
                        'land' => $land->id,
                        'feasibilityAssessment' => $feasibilityAssessment->id,
                        'marketStudy' => $marketStudy->id,
                    ]
                ) }}"
                method="POST"
                onsubmit="return confirm('Are you sure you want to delete this market study?');"
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

@endsection