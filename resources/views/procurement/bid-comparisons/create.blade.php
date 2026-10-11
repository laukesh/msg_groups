@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- =========================================================
        HEADER
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small mb-1">
                Procurement
                <span class="mx-1">/</span>
                Bid Comparison
            </div>

            <h4 class="mb-1">
                Create Bid Comparison
            </h4>

            <div class="text-muted">
                {{ $procurementTender->tender_number }}
                —
                {{ $procurementTender->tender_title }}
            </div>

            @if($project)
                <div class="mt-2">
                    <span class="badge bg-light text-dark border">
                        Project:
                        {{ $project->project_code ?? $project->id }}
                    </span>

                    @if($project->project_name)
                        <span class="text-muted small ms-2">
                            {{ $project->project_name }}
                        </span>
                    @endif
                </div>
            @endif

        </div>


        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.projects.procurement.tenders.show',
                    [
                        'project' => $project,
                        'procurementTender' => $procurementTender,
                    ]
                ) }}"
                class="btn btn-outline-primary"
            >
                <i class="bi bi-arrow-left"></i>
                Back to Tender
            </a>

            <a
                href="{{ route(
                    'admin.projects.procurement.tenders.bid-comparisons.index',
                    [
                        'project' => $project,
                        'procurementTender' => $procurementTender,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                Cancel
            </a>

        </div>

    </div>


    {{-- =========================================================
        VALIDATION ERRORS
    ========================================================== --}}

    @if($errors->any())

        <div class="alert alert-danger">

            <div class="fw-semibold mb-2">
                <i class="bi bi-exclamation-triangle me-1"></i>
                Please correct the following errors:
            </div>

            <ul class="mb-0">

                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

    @endif


    {{-- =========================================================
        FORM
    ========================================================== --}}

    <form
        method="POST"
        action="{{ route(
            'admin.projects.procurement.tenders.bid-comparisons.store',
            [
                'project' => $project,
                'procurementTender' => $procurementTender,
            ]
        ) }}"
    >

        @csrf


        {{-- =====================================================
            COMPARISON DETAILS
        ====================================================== --}}

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white py-3">

                <div class="fw-semibold">
                    <i class="bi bi-file-earmark-bar-graph me-2"></i>
                    Comparison Details
                </div>

                <div class="text-muted small mt-1">
                    Define the evaluation basis and comparison information.
                </div>

            </div>

            <div class="card-body">

                @include(
                    'procurement.bid-comparisons._form',
                    [
                        'comparison' => null,
                    ]
                )

            </div>

        </div>


        {{-- =====================================================
            QUALIFIED COMMERCIAL BIDS
        ====================================================== --}}

        <div class="card border-0 shadow-sm">

            <div class="card-header bg-white py-3">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <div class="fw-semibold">
                            <i class="bi bi-list-check me-2"></i>
                            Qualified Commercial Bids
                        </div>

                        <div class="text-muted small mt-1">
                            Select the qualified commercial evaluations
                            to include in this comparison.
                        </div>

                    </div>

                    <span class="badge bg-success">
                        {{ $eligibleCommercialEvaluations->count() }}
                        Eligible
                    </span>

                </div>

            </div>


            <div class="card-body p-0">

                @if($eligibleCommercialEvaluations->isNotEmpty())

                    <div class="table-responsive">

                        <table class="table table-hover align-middle mb-0">

                            <thead class="table-light">

                                <tr>

                                    <th width="50">
                                        #
                                    </th>

                                    <th width="50">
                                        Select
                                    </th>

                                    <th>
                                        Bidder
                                    </th>

                                    <th>
                                        Quoted Amount
                                    </th>

                                    <th>
                                        Evaluated Amount
                                    </th>

                                    <th>
                                        Final Evaluated Amount
                                    </th>

                                    <th>
                                        Technical Score
                                    </th>

                                    <th>
                                        Price Score
                                    </th>

                                    <th>
                                        Compliance
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @foreach(
                                    $eligibleCommercialEvaluations
                                    as $evaluation
                                )

                                    @php

                                        $bidderName =
                                            $evaluation
                                                ->submission
                                                ?->tenderBidder
                                                ?->bidder
                                                ?->company_name
                                            ?: 'Unknown Bidder';

                                        $technicalScore =
                                            $evaluation
                                                ->technicalEvaluation
                                                ?->technical_score;

                                        $priceScore =
                                            $evaluation->price_score;

                                    @endphp

                                    <tr>

                                        <td>
                                            {{ $loop->iteration }}
                                        </td>


                                        <td>

                                            <div class="form-check">

                                                <input
                                                    class="form-check-input"
                                                    type="checkbox"
                                                    name="selected_evaluations[]"
                                                    value="{{ $evaluation->id }}"
                                                    id="evaluation_{{ $evaluation->id }}"
                                                    @checked(
                                                        in_array(
                                                            $evaluation->id,
                                                            old(
                                                                'selected_evaluations',
                                                                []
                                                            )
                                                        )
                                                    )
                                                >

                                            </div>

                                        </td>


                                        <td>

                                            <label
                                                for="evaluation_{{ $evaluation->id }}"
                                                class="fw-semibold mb-0"
                                                style="cursor:pointer"
                                            >
                                                {{ $bidderName }}
                                            </label>

                                            <div class="small text-muted">
                                                Evaluation #{{ $evaluation->id }}
                                            </div>

                                        </td>


                                        <td>

                                            {{ $evaluation->currency ?? '' }}

                                            {{ number_format(
                                                (float) $evaluation->quoted_amount,
                                                2
                                            ) }}

                                        </td>


                                        <td>

                                            {{ $evaluation->currency ?? '' }}

                                            {{ number_format(
                                                (float) $evaluation->evaluated_amount,
                                                2
                                            ) }}

                                        </td>


                                        <td class="fw-semibold">

                                            {{ $evaluation->currency ?? '' }}

                                            {{ number_format(
                                                (float) $evaluation->final_evaluated_amount,
                                                2
                                            ) }}

                                        </td>


                                        <td>

                                            {{ $technicalScore !== null
                                                ? number_format(
                                                    (float) $technicalScore,
                                                    2
                                                )
                                                : '—'
                                            }}

                                        </td>


                                        <td>

                                            {{ $priceScore !== null
                                                ? number_format(
                                                    (float) $priceScore,
                                                    2
                                                )
                                                : '—'
                                            }}

                                        </td>


                                        <td>

                                            @if(
                                                $evaluation->commercial_compliance
                                            )

                                                <span class="badge bg-success">
                                                    {{
                                                        $evaluation->commercial_compliance
                                                    }}
                                                </span>

                                            @else

                                                <span class="text-muted">
                                                    —
                                                </span>

                                            @endif

                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                @else

                    <div class="p-4">

                        <div class="alert alert-warning mb-0">

                            <h6 class="alert-heading">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                No Qualified Commercial Bids
                            </h6>

                            <p class="mb-2">
                                There are currently no Commercial
                                Evaluations with result:
                                <strong>Qualified</strong>.
                            </p>

                            <hr>

                            <p class="mb-0 small">
                                Complete the Commercial Evaluation and
                                change its result from
                                <strong>Pending</strong>
                                to
                                <strong>Qualified</strong>
                                before creating the Bid Comparison.
                            </p>

                        </div>

                    </div>

                @endif

            </div>


            {{-- =================================================
                FOOTER
            ================================================== --}}

            <div class="card-footer bg-white">

                <div class="d-flex justify-content-between align-items-center">

                    <div class="text-muted small">

                        <i class="bi bi-info-circle me-1"></i>

                        At least one qualified bid must be selected.

                    </div>

                    <div class="d-flex gap-2">

                        <a
                            href="{{ route(
                                'admin.projects.procurement.tenders.bid-comparisons.index',
                                [
                                    'project' => $project,
                                    'procurementTender' => $procurementTender,
                                ]
                            ) }}"
                            class="btn btn-outline-secondary"
                        >
                            Cancel
                        </a>

                        <button
                            type="submit"
                            class="btn btn-primary"
                            @disabled(
                                $eligibleCommercialEvaluations->isEmpty()
                            )
                        >
                            <i class="bi bi-check2-circle me-1"></i>
                            Create Bid Comparison
                        </button>

                    </div>

                </div>

            </div>

        </div>

    </form>

</div>

@endsection