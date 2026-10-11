@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                Technical Evaluation
            </h4>

            <div class="text-muted">
                Evaluation No.:
                <strong>{{ $evaluation->evaluation_number }}</strong>

                <span class="mx-1">|</span>

                Tender:
                <strong>
                    {{ $procurementTender->tender_number ?? $procurementTender->id }}
                </strong>
            </div>
        </div>

        <div class="d-flex gap-2">

            <a href="{{ route(
                'admin.projects.procurement.tenders.technical-evaluations.edit',
                [
                    'project' => $project,
                    'procurementTender' => $procurementTender,
                    'evaluation' => $evaluation,
                ]
            ) }}"
               class="btn btn-primary">

                <i class="bi bi-pencil me-1"></i>
                Edit

            </a>

            <a href="{{ route(
                'admin.projects.procurement.tenders.technical-evaluations.index',
                [
                    'project' => $project,
                    'procurementTender' => $procurementTender,
                ]
            ) }}"
               class="btn btn-outline-secondary">

                <i class="bi bi-arrow-left me-1"></i>
                Back

            </a>

        </div>

    </div>


    {{-- Flash Messages --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    @if(session('warning'))

        <div class="alert alert-warning alert-dismissible fade show">

            {{ session('warning') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- Evaluation Summary --}}
    <div class="row g-4 mb-4">

        {{-- Basic Information --}}
        <div class="col-lg-6">

            <div class="card shadow-sm h-100">

                <div class="card-header">
                    <h5 class="mb-0">
                        Evaluation Information
                    </h5>
                </div>

                <div class="card-body">

                    <div class="row mb-3">

                        <div class="col-sm-5 text-muted">
                            Evaluation Number
                        </div>

                        <div class="col-sm-7 fw-semibold">
                            {{ $evaluation->evaluation_number ?? '—' }}
                        </div>

                    </div>


                    <div class="row mb-3">

                        <div class="col-sm-5 text-muted">
                            Tender
                        </div>

                        <div class="col-sm-7">

                            <a href="{{ route(
                                'admin.projects.procurement.tenders.show',
                                [
                                    'project' => $project,
                                    'procurementTender' => $procurementTender,
                                ]
                            ) }}">

                                {{ $procurementTender->tender_number
                                    ?? ('Tender #' . $procurementTender->id) }}

                            </a>

                        </div>

                    </div>


                    <div class="row mb-3">

                        <div class="col-sm-5 text-muted">
                            Submission
                        </div>

                        <div class="col-sm-7">

                            @if($evaluation->submission)

                                {{ $evaluation->submission->submission_number
                                    ?? ('Submission #' . $evaluation->submission->id) }}

                            @else

                                —

                            @endif

                        </div>

                    </div>


                    <div class="row mb-3">

                        <div class="col-sm-5 text-muted">
                            Bidder
                        </div>

                        <div class="col-sm-7">

                            @php
                                $bidder = optional(
                                    optional($evaluation->submission?->tenderBidder)->bidder
                                );
                            @endphp

                            {{ $bidder->name
                                ?? $bidder->company_name
                                ?? '—' }}

                        </div>

                    </div>


                    <div class="row mb-3">

                        <div class="col-sm-5 text-muted">
                            Evaluation Date
                        </div>

                        <div class="col-sm-7">

                            {{ $evaluation->evaluation_date
                                ? $evaluation->evaluation_date->format('d-m-Y')
                                : '—' }}

                        </div>

                    </div>


                    <div class="row mb-3">

                        <div class="col-sm-5 text-muted">
                            Evaluator
                        </div>

                        <div class="col-sm-7">

                            {{ $evaluation->evaluator_name ?? '—' }}

                        </div>

                    </div>


                    <div class="row">

                        <div class="col-sm-5 text-muted">
                            Status
                        </div>

                        <div class="col-sm-7">

                            @php
                                $statusClass = match(strtolower($evaluation->status ?? '')) {
                                    'completed',
                                    'approved',
                                    'accepted' => 'success',

                                    'pending',
                                    'under review',
                                    'in review' => 'warning',

                                    'rejected',
                                    'cancelled' => 'danger',

                                    default => 'secondary',
                                };
                            @endphp

                            <span class="badge bg-{{ $statusClass }}">
                                {{ $evaluation->status ?? '—' }}
                            </span>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Score --}}
        <div class="col-lg-6">

            <div class="card shadow-sm h-100">

                <div class="card-header">
                    <h5 class="mb-0">
                        Technical Score
                    </h5>
                </div>

                <div class="card-body">

                    @php
                        $technicalScore = (float) $evaluation->technical_score;
                        $maximumScore = (float) $evaluation->maximum_score;
                        $passingScore = (float) $evaluation->passing_score;

                        $percentage = $maximumScore > 0
                            ? ($technicalScore / $maximumScore) * 100
                            : 0;
                    @endphp

                    <div class="text-center mb-4">

                        <div class="display-5 fw-bold">

                            {{ number_format($technicalScore, 2) }}

                            <span class="text-muted fs-5">
                                /
                                {{ number_format($maximumScore, 2) }}
                            </span>

                        </div>

                        <div class="text-muted">
                            Technical Score
                        </div>

                    </div>


                    <div class="progress mb-4"
                         style="height: 12px;">

                        <div class="progress-bar"
                             role="progressbar"
                             style="width: {{ min($percentage, 100) }}%;"
                             aria-valuenow="{{ $percentage }}"
                             aria-valuemin="0"
                             aria-valuemax="100">

                        </div>

                    </div>


                    <div class="row text-center">

                        <div class="col-4">

                            <div class="text-muted small">
                                Score
                            </div>

                            <strong>
                                {{ number_format($technicalScore, 2) }}
                            </strong>

                        </div>


                        <div class="col-4">

                            <div class="text-muted small">
                                Passing
                            </div>

                            <strong>
                                {{ number_format($passingScore, 2) }}
                            </strong>

                        </div>


                        <div class="col-4">

                            <div class="text-muted small">
                                Result
                            </div>

                            @if($evaluation->result === 'Pass')

                                <span class="badge bg-success">
                                    Pass
                                </span>

                            @elseif($evaluation->result === 'Fail')

                                <span class="badge bg-danger">
                                    Fail
                                </span>

                            @else

                                <span class="badge bg-secondary">
                                    {{ $evaluation->result ?? '—' }}
                                </span>

                            @endif

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Compliance --}}
    <div class="card shadow-sm mb-4">

        <div class="card-header">

            <h5 class="mb-0">
                Technical Compliance
            </h5>

        </div>

        <div class="card-body">

            @if($evaluation->technical_compliance)

                @php
                    $complianceClass = match(strtolower($evaluation->technical_compliance)) {
                        'compliant' => 'success',
                        'partially compliant' => 'warning',
                        'non-compliant' => 'danger',
                        default => 'secondary',
                    };
                @endphp

                <span class="badge bg-{{ $complianceClass }} fs-6">
                    {{ $evaluation->technical_compliance }}
                </span>

            @else

                <span class="text-muted">
                    No compliance assessment recorded.
                </span>

            @endif

        </div>

    </div>


    {{-- Strengths / Weaknesses --}}
    <div class="row g-4 mb-4">

        <div class="col-md-6">

            <div class="card shadow-sm h-100">

                <div class="card-header">
                    <h5 class="mb-0">
                        Strengths
                    </h5>
                </div>

                <div class="card-body">

                    @if($evaluation->strengths)

                        {!! nl2br(e($evaluation->strengths)) !!}

                    @else

                        <span class="text-muted">
                            No strengths recorded.
                        </span>

                    @endif

                </div>

            </div>

        </div>


        <div class="col-md-6">

            <div class="card shadow-sm h-100">

                <div class="card-header">
                    <h5 class="mb-0">
                        Weaknesses
                    </h5>
                </div>

                <div class="card-body">

                    @if($evaluation->weaknesses)

                        {!! nl2br(e($evaluation->weaknesses)) !!}

                    @else

                        <span class="text-muted">
                            No weaknesses recorded.
                        </span>

                    @endif

                </div>

            </div>

        </div>

    </div>


    {{-- Evaluation Summary --}}
    <div class="card shadow-sm mb-4">

        <div class="card-header">

            <h5 class="mb-0">
                Evaluation Summary
            </h5>

        </div>

        <div class="card-body">

            @if($evaluation->evaluation_summary)

                {!! nl2br(e($evaluation->evaluation_summary)) !!}

            @else

                <span class="text-muted">
                    No evaluation summary provided.
                </span>

            @endif

        </div>

    </div>


    {{-- Remarks --}}
    <div class="card shadow-sm mb-4">

        <div class="card-header">

            <h5 class="mb-0">
                Remarks
            </h5>

        </div>

        <div class="card-body">

            @if($evaluation->remarks)

                {!! nl2br(e($evaluation->remarks)) !!}

            @else

                <span class="text-muted">
                    No remarks.
                </span>

            @endif

        </div>

    </div>


    {{-- Commercial Evaluation --}}
    <div class="card shadow-sm mb-4">

        <div class="card-header d-flex justify-content-between align-items-center">

            <h5 class="mb-0">
                Commercial Evaluation
            </h5>

        </div>

        <div class="card-body">

            @if($evaluation->commercialEvaluation)

                <div class="alert alert-info mb-0">

                    Commercial evaluation has been created for this technical evaluation.

                </div>

            @else

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <div class="fw-semibold">
                            Commercial Evaluation Pending
                        </div>

                        <div class="text-muted small">
                            Create the commercial evaluation for this technically evaluated submission.
                        </div>

                    </div>


                    <a href="{{ route(
                        'admin.projects.procurement.tenders.commercial-evaluations.create',
                        [
                            'project' => $project,
                            'procurementTender' => $procurementTender,
                            'technicalEvaluation' => $evaluation,
                        ]
                    ) }}"
                       class="btn btn-outline-primary">

                        <i class="bi bi-plus-circle me-1"></i>
                        Create Commercial Evaluation

                    </a>

                </div>

            @endif

        </div>

    </div>


    {{-- Bottom Actions --}}
    <div class="d-flex justify-content-between align-items-center">

        <a href="{{ route(
            'admin.projects.procurement.tenders.technical-evaluations.index',
            [
                'project' => $project,
                'procurementTender' => $procurementTender,
            ]
        ) }}"
           class="btn btn-outline-secondary">

            <i class="bi bi-arrow-left me-1"></i>
            Back to Technical Evaluations

        </a>


        <div class="d-flex gap-2">

            <a href="{{ route(
                'admin.projects.procurement.tenders.technical-evaluations.edit',
                [
                    'project' => $project,
                    'procurementTender' => $procurementTender,
                    'evaluation' => $evaluation,
                ]
            ) }}"
               class="btn btn-primary">

                <i class="bi bi-pencil me-1"></i>
                Edit Evaluation

            </a>


            <form method="POST"
                  action="{{ route(
                      'admin.projects.procurement.tenders.technical-evaluations.destroy',
                      [
                          'project' => $project,
                          'procurementTender' => $procurementTender,
                          'evaluation' => $evaluation,
                      ]
                  ) }}"
                  onsubmit="return confirm('Are you sure you want to delete this technical evaluation?');">

                @csrf
                @method('DELETE')

                <button type="submit"
                        class="btn btn-outline-danger">

                    <i class="bi bi-trash me-1"></i>
                    Delete

                </button>

            </form>

        </div>

    </div>

</div>

@endsection