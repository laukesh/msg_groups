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
                <span class="mx-1">/</span>
                Edit
            </div>

            <h4 class="mb-1">
                Edit Bid Comparison
            </h4>

            <div class="text-muted">

                {{ $comparison->comparison_number }}

                <span class="mx-1">—</span>

                {{ $procurementTender->tender_number }}

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
                Tender
            </a>

            <a
                href="{{ route(
                    'admin.projects.procurement.tenders.bid-comparisons.show',
                    [
                        'project' => $project,
                        'procurementTender' => $procurementTender,
                        'comparison' => $comparison,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                Cancel
            </a>

        </div>

    </div>


    {{-- =========================================================
        ERRORS
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
        UPDATE FORM
    ========================================================== --}}

    <form
        method="POST"
        action="{{ route(
            'admin.projects.procurement.tenders.bid-comparisons.update',
            [
                'project' => $project,
                'procurementTender' => $procurementTender,
                'comparison' => $comparison,
            ]
        ) }}"
    >

        @csrf
        @method('PUT')


        {{-- =====================================================
            COMPARISON DETAILS
        ====================================================== --}}

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white py-3">

                <div class="fw-semibold">
                    <i class="bi bi-pencil-square me-2"></i>
                    Comparison Details
                </div>

            </div>

            <div class="card-body">

                @include(
                    'procurement.bid-comparisons._form',
                    [
                        'comparison' => $comparison,
                    ]
                )

            </div>

        </div>


        {{-- =====================================================
            QUALIFIED BIDS
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
                            Select the commercial evaluations included
                            in this comparison.
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
                                        Technical
                                    </th>

                                    <th>
                                        Price
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
                                                                $selectedEvaluationIds
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

                                            {{
                                                $evaluation
                                                    ->technicalEvaluation
                                                    ?->technical_score
                                                !== null
                                                    ? number_format(
                                                        (float)
                                                        $evaluation
                                                            ->technicalEvaluation
                                                            ->technical_score,
                                                        2
                                                    )
                                                    : '—'
                                            }}

                                        </td>


                                        <td>

                                            {{
                                                $evaluation->price_score
                                                !== null
                                                    ? number_format(
                                                        (float)
                                                        $evaluation->price_score,
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
                                                        $evaluation
                                                            ->commercial_compliance
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

                            No qualified commercial evaluations
                            are available for this Tender.

                        </div>

                    </div>

                @endif

            </div>


            <div class="card-footer bg-white">

                <div class="d-flex justify-content-end gap-2">

                    <a
                        href="{{ route(
                            'admin.projects.procurement.tenders.bid-comparisons.show',
                            [
                                'project' => $project,
                                'procurementTender' => $procurementTender,
                                'comparison' => $comparison,
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
                        <i class="bi bi-save me-1"></i>
                        Update Bid Comparison
                    </button>

                </div>

            </div>

        </div>

    </form>

</div>

@endsection