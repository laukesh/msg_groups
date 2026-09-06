@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- =========================================================
        HEADER
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3>
                Land Acquisition Review
            </h3>

            <p class="text-muted mb-0">

                {{ $land->land_code }}
                -
                {{ $land->land_name }}

            </p>

        </div>


        <div>

            <a
                href="{{ route(
                    'admin.land.lands.show',
                    $land
                ) }}"
                class="btn btn-secondary"
            >
                Back to Land
            </a>

        </div>

    </div>


    {{-- =========================================================
        READINESS
    ========================================================== --}}

    @if($readyForFeasibility)

        <div class="alert alert-success">

            <h5 class="mb-1">
                ✓ Ready for Feasibility
            </h5>

            <div>
                Required acquisition reviews have been completed.
            </div>

        </div>

    @else

        <div class="alert alert-warning">

            <h5 class="mb-1">
                ⚠ Not Ready for Feasibility
            </h5>

            <div>
                One or more acquisition requirements are still pending.
            </div>

        </div>

    @endif

        <div class="card mb-4">

        <div class="card-header">

            <strong>
                Land Information
            </strong>

        </div>


        <div class="card-body">

            <div class="row">

                <div class="col-md-3 mb-3">

                    <small class="text-muted">
                        Land Code
                    </small>

                    <div class="fw-semibold">
                        {{ $land->land_code }}
                    </div>

                </div>


                <div class="col-md-3 mb-3">

                    <small class="text-muted">
                        Land Name
                    </small>

                    <div>
                        {{ $land->land_name }}
                    </div>

                </div>


                <div class="col-md-3 mb-3">

                    <small class="text-muted">
                        Acquisition Status
                    </small>

                    <div>

                        <span class="badge bg-primary">

                            {{ $land->acquisition_status }}

                        </span>

                    </div>

                </div>


                <div class="col-md-3 mb-3">

                    <small class="text-muted">
                        Created
                    </small>

                    <div>

                        {{ $land->created_at
                            ? $land->created_at->format('d-m-Y')
                            : '-'
                        }}

                    </div>

                </div>

            </div>

        </div>

    </div>
        <div class="card mb-4">

        <div class="card-header">

            <strong>
                Due Diligence Review
            </strong>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover mb-0">

                    <thead>

                        <tr>

                            <th>
                                Assessment
                            </th>

                            <th>
                                Reference
                            </th>

                            <th>
                                Conducted By
                            </th>

                            <th>
                                Date
                            </th>

                            <th>
                                Status
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        {{-- Legal --}}

                        <tr>

                            <td>
                                <strong>
                                    Legal Due Diligence
                                </strong>
                            </td>

                            <td>
                                {{ $legalDueDiligence->reference_no ?? '-' }}
                            </td>

                            <td>
                                {{ $legalDueDiligence->conducted_by ?? '-' }}
                            </td>

                            <td>

                                {{ $legalDueDiligence?->assessment_date
                                    ? $legalDueDiligence
                                        ->assessment_date
                                        ->format('d-m-Y')
                                    : '-'
                                }}

                            </td>

                            <td>

                                @if($legalCompleted)

                                    <span class="badge bg-success">
                                        Completed
                                    </span>

                                @elseif($legalDueDiligence)

                                    <span class="badge bg-warning text-dark">
                                        {{ $legalDueDiligence->status }}
                                    </span>

                                @else

                                    <span class="badge bg-danger">
                                        Not Available
                                    </span>

                                @endif

                            </td>

                        </tr>


                        {{-- Technical --}}

                        <tr>

                            <td>
                                <strong>
                                    Technical Due Diligence
                                </strong>
                            </td>

                            <td>
                                {{ $technicalDueDiligence->reference_no ?? '-' }}
                            </td>

                            <td>
                                {{ $technicalDueDiligence->conducted_by ?? '-' }}
                            </td>

                            <td>

                                {{ $technicalDueDiligence?->assessment_date
                                    ? $technicalDueDiligence
                                        ->assessment_date
                                        ->format('d-m-Y')
                                    : '-'
                                }}

                            </td>

                            <td>

                                @if($technicalCompleted)

                                    <span class="badge bg-success">
                                        Completed
                                    </span>

                                @elseif($technicalDueDiligence)

                                    <span class="badge bg-warning text-dark">
                                        {{ $technicalDueDiligence->status }}
                                    </span>

                                @else

                                    <span class="badge bg-danger">
                                        Not Available
                                    </span>

                                @endif

                            </td>

                        </tr>


                        {{-- Environmental --}}

                        <tr>

                            <td>
                                <strong>
                                    Environmental Assessment
                                </strong>
                            </td>

                            <td>
                                {{ $environmentalAssessment->reference_no ?? '-' }}
                            </td>

                            <td>
                                {{ $environmentalAssessment->conducted_by ?? '-' }}
                            </td>

                            <td>

                                {{ $environmentalAssessment?->assessment_date
                                    ? $environmentalAssessment
                                        ->assessment_date
                                        ->format('d-m-Y')
                                    : '-'
                                }}

                            </td>

                            <td>

                                @if($environmentalCompleted)

                                    <span class="badge bg-success">
                                        Completed
                                    </span>

                                @elseif($environmentalAssessment)

                                    <span class="badge bg-warning text-dark">
                                        {{ $environmentalAssessment->status }}
                                    </span>

                                @else

                                    <span class="badge bg-danger">
                                        Not Available
                                    </span>

                                @endif

                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>

        </div>

    </div>
        <div class="card mb-4">

        <div class="card-header">

            <strong>
                Acquisition Cost Summary
            </strong>

        </div>


        <div class="card-body">

            <div class="row">

                <div class="col-md-4">

                    <small class="text-muted">
                        Total Acquisition Cost
                    </small>

                    <h4 class="mt-1">

                        $ {{ number_format(
                            $totalAcquisitionCost,
                            2
                        ) }}

                    </h4>

                </div>

            </div>

        </div>

    </div>

        <div class="card mb-4">

        <div class="card-header">

            <strong>
                Acquisition Status History
            </strong>

        </div>


        <div class="card-body">

            @forelse(
                $land->acquisitionStatusHistories
                as $history
            )

                <div class="d-flex mb-3">

                    <div class="me-3">

                        <span class="badge bg-primary">
                            {{ $history->to_status }}
                        </span>

                    </div>


                    <div>

                        <div>

                            @if($history->from_status)

                                {{ $history->from_status }}

                                →
                                
                            @endif

                            <strong>
                                {{ $history->to_status }}
                            </strong>

                        </div>


                        @if($history->remarks)

                            <small class="text-muted">
                                {{ $history->remarks }}
                            </small>

                        @endif


                        <div>

                            <small class="text-muted">

                                {{ $history->changed_at
                                    ? $history
                                        ->changed_at
                                        ->format('d-m-Y H:i')
                                    : '-'
                                }}

                            </small>

                        </div>

                    </div>

                </div>

            @empty

                <div class="text-muted">
                    No status history available.
                </div>

            @endforelse

        </div>

    </div>

        <div class="card mb-4">

        <div class="card-header d-flex justify-content-between align-items-center">

            <strong>
                Acquisition Documents
            </strong>


            <a
                href="{{ route(
                    'admin.land.lands.documents.index',
                    $land
                ) }}"
                class="btn btn-sm btn-outline-primary"
            >
                View All Documents
            </a>

        </div>


        <div class="card-body">

            @if($land->documents->count())

                <div class="row">

                    @foreach(
                        $land->documents->take(6)
                        as $document
                    )

                        <div class="col-md-4 mb-3">

                            <div class="border rounded p-3">

                                <strong>
                                    {{ $document->title }}
                                </strong>

                                <br>

                                <small class="text-muted">

                                    {{ $document->document_type }}

                                    |
                                    Version
                                    {{ $document->version }}

                                </small>

                            </div>

                        </div>

                    @endforeach

                </div>

            @else

                <div class="text-muted">

                    No documents uploaded.

                </div>

            @endif

        </div>

    </div>

        <div class="card mb-4">

        <div class="card-header">

            <strong>
                Acquisition Review Decision
            </strong>

        </div>


        <div class="card-body">

            @if($readyForFeasibility)

                <div class="alert alert-success">

                    <strong>
                        Land is ready for Feasibility & Investment.
                    </strong>

                    <div class="mt-2">

                        Legal, technical and environmental
                        assessments have been completed and the
                        acquisition status permits progression.

                    </div>

                </div>

                <button
                    type="button"
                    class="btn btn-success"
                    disabled
                >
                    Proceed to Feasibility
                </button>

            @else

                <div class="alert alert-warning">

                    <strong>
                        Land is not ready for Feasibility & Investment.
                    </strong>

                    <div class="mt-2">

                        Complete the pending acquisition requirements
                        before proceeding.

                    </div>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection