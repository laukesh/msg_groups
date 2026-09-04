@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- =========================================================
        HEADER
    ========================================================== --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <div class="mb-1">

                <a href="{{ route(
                    'admin.procurement.tenders.show',
                    $procurementTender
                ) }}"
                   class="text-decoration-none">

                    Tender:
                    {{ $procurementTender->tender_number }}

                </a>

            </div>


            <h4 class="mb-1">
                {{ $prequalification->prequalification_no }}
            </h4>


            <div class="text-muted">

                {{
                    $prequalification
                        ->tenderBidder
                        ->bidder
                        ->company_name
                }}

            </div>

        </div>


        <div class="d-flex gap-2">

            <a href="{{ route(
                'admin.procurement.tenders.prequalifications.edit',
                [
                    'procurementTender' => $procurementTender,
                    'prequalification' => $prequalification,
                ]
            ) }}"
               class="btn btn-primary">

                Edit

            </a>


            <a href="{{ route(
                'admin.procurement.tenders.prequalifications.index',
                $procurementTender
            ) }}"
               class="btn btn-outline-secondary">

                Back

            </a>

        </div>

    </div>


    {{-- =========================================================
        SUCCESS MESSAGE
    ========================================================== --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- =========================================================
        ERROR MESSAGE
    ========================================================== --}}
    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- =========================================================
        VALIDATION ERRORS
    ========================================================== --}}
    @if($errors->any())

        <div class="alert alert-danger">

            <strong>
                Please correct the following:
            </strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- =========================================================
        PREQUALIFICATION INFORMATION
    ========================================================== --}}
    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Prequalification Assessment
            </strong>

        </div>


        <div class="card-body">

            <div class="row g-4">


                {{-- Prequalification No --}}
                <div class="col-md-3">

                    <div class="text-muted small">
                        Prequalification No.
                    </div>

                    <div class="fw-semibold">

                        {{
                            $prequalification
                                ->prequalification_no
                        }}

                    </div>

                </div>


                {{-- Bidder --}}
                <div class="col-md-5">

                    <div class="text-muted small">
                        Bidder
                    </div>

                    <div class="fw-semibold">

                        {{
                            $prequalification
                                ->tenderBidder
                                ->bidder
                                ->company_name
                        }}

                    </div>

                    <div class="small text-muted">

                        {{
                            $prequalification
                                ->tenderBidder
                                ->bidder
                                ->bidder_code
                        }}

                    </div>

                </div>


                {{-- Submission Date --}}
                <div class="col-md-2">

                    <div class="text-muted small">
                        Submission Date
                    </div>

                    <div>

                        {{
                            $prequalification->submission_date
                                ? $prequalification
                                    ->submission_date
                                    ->format('d-m-Y')
                                : '—'
                        }}

                    </div>

                </div>


                {{-- Status --}}
                <div class="col-md-2">

                    <div class="text-muted small">
                        Status
                    </div>


                    @php

                        $statusClass = match(
                            $prequalification->status
                        ) {

                            'Qualified'
                                => 'bg-success',

                            'Not Qualified',
                            'Rejected'
                                => 'bg-danger',

                            'Under Evaluation'
                                => 'bg-warning text-dark',

                            'Submitted'
                                => 'bg-info',

                            default
                                => 'bg-secondary',

                        };

                    @endphp


                    <span class="badge {{ $statusClass }}">

                        {{ $prequalification->status }}

                    </span>

                </div>


                {{-- Evaluation Date --}}
                <div class="col-md-4">

                    <div class="text-muted small">
                        Evaluation Date
                    </div>

                    <div>

                        {{
                            $prequalification->evaluation_date
                                ? $prequalification
                                    ->evaluation_date
                                    ->format('d-m-Y')
                                : '—'
                        }}

                    </div>

                </div>


                {{-- Evaluator --}}
                <div class="col-md-4">

                    <div class="text-muted small">
                        Evaluator
                    </div>

                    <div>

                        {{
                            $prequalification->evaluator_name
                            ?: '—'
                        }}

                    </div>

                </div>


                {{-- Tender --}}
                <div class="col-md-4">

                    <div class="text-muted small">
                        Tender
                    </div>

                    <a href="{{ route(
                        'admin.procurement.tenders.show',
                        $procurementTender
                    ) }}">

                        {{ $procurementTender->tender_number }}

                    </a>

                </div>


                {{-- Evaluation Summary --}}
                <div class="col-md-12">

                    <div class="text-muted small">
                        Evaluation Summary
                    </div>

                    <div>

                        {!! nl2br(
                            e(
                                $prequalification
                                    ->evaluation_summary
                                    ?: '—'
                            )
                        ) !!}

                    </div>

                </div>


                {{-- Remarks --}}
                <div class="col-md-12">

                    <div class="text-muted small">
                        Remarks
                    </div>

                    <div>

                        {!! nl2br(
                            e(
                                $prequalification
                                    ->remarks
                                    ?: '—'
                            )
                        ) !!}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        CRITERIA CARD
    ========================================================== --}}
    <div class="card mb-4">


        {{-- CARD HEADER --}}
        <div class="card-header d-flex justify-content-between align-items-center">

            <div>

                <strong>
                    Evaluation Criteria
                </strong>

                <span class="badge bg-primary ms-2">

                    {{
                        $prequalification
                            ->criteria
                            ->count()
                    }}

                </span>

            </div>


            <button
                type="button"
                class="btn btn-sm btn-primary"
                data-bs-toggle="collapse"
                data-bs-target="#addCriterionForm"
                aria-expanded="false"
                aria-controls="addCriterionForm"
            >

                + Add Criterion

            </button>

        </div>


        {{-- =====================================================
            ADD CRITERION FORM
        ====================================================== --}}
        <div class="collapse border-bottom"
             id="addCriterionForm">

            <div class="card-body">

                <form method="POST"
                      action="{{ route(
                          'admin.procurement.prequalifications.criteria.store',
                          $prequalification
                      ) }}">

                    @csrf


                    <div class="row g-3">


                        {{-- Criterion Name --}}
                        <div class="col-md-4">

                            <label class="form-label">
                                Criterion Name
                                <span class="text-danger">*</span>
                            </label>

                            <input
                                type="text"
                                name="criterion_name"
                                class="form-control"
                                value="{{ old('criterion_name') }}"
                                maxlength="255"
                                required
                            >

                        </div>


                        {{-- Criterion Type --}}
                        <div class="col-md-3">

                            <label class="form-label">
                                Criterion Type
                                <span class="text-danger">*</span>
                            </label>

                            <select
                                name="criterion_type"
                                class="form-select"
                                required
                            >

                                @foreach([
                                    'Technical',
                                    'Financial',
                                    'Legal',
                                    'Experience',
                                    'Manpower',
                                    'Equipment',
                                    'Other',
                                ] as $type)

                                    <option value="{{ $type }}"
                                        @selected(
                                            old('criterion_type', 'Technical')
                                            === $type
                                        )
                                    >

                                        {{ $type }}

                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- Requirement --}}
                        <div class="col-md-5">

                            <label class="form-label">
                                Requirement
                            </label>

                            <input
                                type="text"
                                name="requirement"
                                class="form-control"
                                value="{{ old('requirement') }}"
                                maxlength="500"
                            >

                        </div>


                        {{-- Description --}}
                        <div class="col-md-6">

                            <label class="form-label">
                                Criterion Description
                            </label>

                            <textarea
                                name="criterion_description"
                                rows="3"
                                class="form-control"
                            >{{ old('criterion_description') }}</textarea>

                        </div>


                        {{-- Bidder Response --}}
                        <div class="col-md-6">

                            <label class="form-label">
                                Bidder Response
                            </label>

                            <textarea
                                name="bidder_response"
                                rows="3"
                                class="form-control"
                            >{{ old('bidder_response') }}</textarea>

                        </div>


                        {{-- Evaluation Result --}}
                        <div class="col-md-4">

                            <label class="form-label">
                                Evaluation Result
                                <span class="text-danger">*</span>
                            </label>

                            <select
                                name="evaluation_result"
                                class="form-select"
                                required
                            >

                                @foreach([
                                    'Pending',
                                    'Compliant',
                                    'Non-Compliant',
                                    'Partially Compliant',
                                    'Not Applicable',
                                ] as $result)

                                    <option
                                        value="{{ $result }}"
                                        @selected(
                                            old(
                                                'evaluation_result',
                                                'Pending'
                                            ) === $result
                                        )
                                    >

                                        {{ $result }}

                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- Evaluator Remarks --}}
                        <div class="col-md-8">

                            <label class="form-label">
                                Evaluator Remarks
                            </label>

                            <textarea
                                name="evaluator_remarks"
                                rows="2"
                                class="form-control"
                            >{{ old('evaluator_remarks') }}</textarea>

                        </div>


                        {{-- Submit --}}
                        <div class="col-12 text-end">

                            <button
                                type="submit"
                                class="btn btn-primary"
                            >

                                Add Criterion

                            </button>

                        </div>

                    </div>

                </form>

            </div>

        </div>


        {{-- =====================================================
            CRITERIA TABLE
        ====================================================== --}}
        <div class="card-body p-0">

            @if(
                $prequalification
                    ->criteria
                    ->count()
            )

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                        <tr>

                            <th style="width: 60px;">
                                #
                            </th>

                            <th>
                                Criterion
                            </th>

                            <th>
                                Type
                            </th>

                            <th>
                                Requirement
                            </th>

                            <th>
                                Bidder Response
                            </th>

                            <th>
                                Result
                            </th>

                            <th>
                                Evaluator Remarks
                            </th>

                            <th class="text-end"
                                style="width: 150px;">

                                Action

                            </th>

                        </tr>

                        </thead>


                        <tbody>

                        @foreach(
                            $prequalification->criteria
                            as $criterion
                        )

                            <tr>


                                {{-- Number --}}
                                <td>

                                    {{ $criterion->criterion_no }}

                                </td>


                                {{-- Criterion --}}
                                <td>

                                    <div class="fw-semibold">

                                        {{ $criterion->criterion_name }}

                                    </div>


                                    @if(
                                        $criterion
                                            ->criterion_description
                                    )

                                        <div class="small text-muted">

                                            {{
                                                $criterion
                                                    ->criterion_description
                                            }}

                                        </div>

                                    @endif

                                </td>


                                {{-- Type --}}
                                <td>

                                    <span class="badge bg-secondary">

                                        {{ $criterion->criterion_type }}

                                    </span>

                                </td>


                                {{-- Requirement --}}
                                <td>

                                    {{
                                        $criterion->requirement
                                        ?: '—'
                                    }}

                                </td>


                                {{-- Bidder Response --}}
                                <td>

                                    {{
                                        $criterion->bidder_response
                                        ?: '—'
                                    }}

                                </td>


                                {{-- Result --}}
                                <td>

                                    @php

                                        $resultClass = match(
                                            $criterion->evaluation_result
                                        ) {

                                            'Compliant'
                                                => 'bg-success',

                                            'Non-Compliant'
                                                => 'bg-danger',

                                            'Partially Compliant'
                                                => 'bg-warning text-dark',

                                            'Not Applicable'
                                                => 'bg-secondary',

                                            default
                                                => 'bg-info',

                                        };

                                    @endphp


                                    <span class="badge {{ $resultClass }}">

                                        {{
                                            $criterion
                                                ->evaluation_result
                                        }}

                                    </span>

                                </td>


                                {{-- Evaluator Remarks --}}
                                <td>

                                    {{
                                        $criterion
                                            ->evaluator_remarks
                                        ?: '—'
                                    }}

                                </td>


                                {{-- Actions --}}
                                <td class="text-end">

                                    <button
                                        type="button"
                                        class="btn btn-sm btn-outline-primary"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editCriterion{{ $criterion->id }}"
                                    >

                                        Edit

                                    </button>


                                    <form
                                        method="POST"
                                        action="{{ route(
                                            'admin.procurement.prequalifications.criteria.destroy',
                                            [
                                                'prequalification' =>
                                                    $prequalification,

                                                'criterion' =>
                                                    $criterion,
                                            ]
                                        ) }}"
                                        class="d-inline"
                                    >

                                        @csrf

                                        @method('DELETE')


                                        <button
                                            type="submit"
                                            class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm(
                                                'Are you sure you want to delete this criterion?'
                                            )"
                                        >

                                            Delete

                                        </button>

                                    </form>

                                </td>

                            </tr>


                            {{-- =================================================
                                EDIT CRITERION MODAL
                            ================================================== --}}
                            <div
                                class="modal fade"
                                id="editCriterion{{ $criterion->id }}"
                                tabindex="-1"
                                aria-hidden="true"
                            >

                                <div class="modal-dialog modal-lg">

                                    <div class="modal-content">


                                        {{-- Modal Header --}}
                                        <div class="modal-header">

                                            <h5 class="modal-title">

                                                Edit Criterion
                                                #{{ $criterion->criterion_no }}

                                            </h5>


                                            <button
                                                type="button"
                                                class="btn-close"
                                                data-bs-dismiss="modal"
                                            ></button>

                                        </div>


                                        {{-- Modal Form --}}
                                        <form
                                            method="POST"
                                            action="{{ route(
                                                'admin.procurement.prequalifications.criteria.update',
                                                [
                                                    'prequalification' =>
                                                        $prequalification,

                                                    'criterion' =>
                                                        $criterion,
                                                ]
                                            ) }}"
                                        >

                                            @csrf

                                            @method('PUT')


                                            <div class="modal-body">

                                                <div class="row g-3">


                                                    {{-- Name --}}
                                                    <div class="col-md-7">

                                                        <label class="form-label">
                                                            Criterion Name
                                                            <span class="text-danger">*</span>
                                                        </label>

                                                        <input
                                                            type="text"
                                                            name="criterion_name"
                                                            class="form-control"
                                                            value="{{ $criterion->criterion_name }}"
                                                            maxlength="255"
                                                            required
                                                        >

                                                    </div>


                                                    {{-- Type --}}
                                                    <div class="col-md-5">

                                                        <label class="form-label">
                                                            Criterion Type
                                                            <span class="text-danger">*</span>
                                                        </label>

                                                        <select
                                                            name="criterion_type"
                                                            class="form-select"
                                                            required
                                                        >

                                                            @foreach([
                                                                'Technical',
                                                                'Financial',
                                                                'Legal',
                                                                'Experience',
                                                                'Manpower',
                                                                'Equipment',
                                                                'Other',
                                                            ] as $type)

                                                                <option
                                                                    value="{{ $type }}"
                                                                    @selected(
                                                                        $criterion->criterion_type
                                                                        === $type
                                                                    )
                                                                >

                                                                    {{ $type }}

                                                                </option>

                                                            @endforeach

                                                        </select>

                                                    </div>


                                                    {{-- Requirement --}}
                                                    <div class="col-12">

                                                        <label class="form-label">
                                                            Requirement
                                                        </label>

                                                        <input
                                                            type="text"
                                                            name="requirement"
                                                            class="form-control"
                                                            value="{{ $criterion->requirement }}"
                                                            maxlength="500"
                                                        >

                                                    </div>


                                                    {{-- Description --}}
                                                    <div class="col-12">

                                                        <label class="form-label">
                                                            Criterion Description
                                                        </label>

                                                        <textarea
                                                            name="criterion_description"
                                                            rows="3"
                                                            class="form-control"
                                                        >{{ $criterion->criterion_description }}</textarea>

                                                    </div>


                                                    {{-- Bidder Response --}}
                                                    <div class="col-12">

                                                        <label class="form-label">
                                                            Bidder Response
                                                        </label>

                                                        <textarea
                                                            name="bidder_response"
                                                            rows="3"
                                                            class="form-control"
                                                        >{{ $criterion->bidder_response }}</textarea>

                                                    </div>


                                                    {{-- Result --}}
                                                    <div class="col-md-5">

                                                        <label class="form-label">
                                                            Evaluation Result
                                                            <span class="text-danger">*</span>
                                                        </label>

                                                        <select
                                                            name="evaluation_result"
                                                            class="form-select"
                                                            required
                                                        >

                                                            @foreach([
                                                                'Pending',
                                                                'Compliant',
                                                                'Non-Compliant',
                                                                'Partially Compliant',
                                                                'Not Applicable',
                                                            ] as $result)

                                                                <option
                                                                    value="{{ $result }}"
                                                                    @selected(
                                                                        $criterion->evaluation_result
                                                                        === $result
                                                                    )
                                                                >

                                                                    {{ $result }}

                                                                </option>

                                                            @endforeach

                                                        </select>

                                                    </div>


                                                    {{-- Evaluator Remarks --}}
                                                    <div class="col-md-7">

                                                        <label class="form-label">
                                                            Evaluator Remarks
                                                        </label>

                                                        <textarea
                                                            name="evaluator_remarks"
                                                            rows="2"
                                                            class="form-control"
                                                        >{{ $criterion->evaluator_remarks }}</textarea>

                                                    </div>

                                                </div>

                                            </div>


                                            {{-- Modal Footer --}}
                                            <div class="modal-footer">

                                                <button
                                                    type="button"
                                                    class="btn btn-outline-secondary"
                                                    data-bs-dismiss="modal"
                                                >

                                                    Cancel

                                                </button>


                                                <button
                                                    type="submit"
                                                    class="btn btn-primary"
                                                >

                                                    Update Criterion

                                                </button>

                                            </div>

                                        </form>

                                    </div>

                                </div>

                            </div>

                        @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="text-center py-5">

                    <div class="text-muted mb-3">

                        No evaluation criteria have been
                        added yet.

                    </div>


                    <button
                        type="button"
                        class="btn btn-primary"
                        data-bs-toggle="collapse"
                        data-bs-target="#addCriterionForm"
                    >

                        + Add First Criterion

                    </button>

                </div>

            @endif

        </div>

    </div>


    {{-- =========================================================
        DELETE PREQUALIFICATION
    ========================================================== --}}
    <div class="card border-danger mb-4">

        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <strong class="text-danger">

                        Delete Prequalification

                    </strong>

                    <div class="small text-muted">

                        This will also delete all evaluation
                        criteria associated with this assessment.

                    </div>

                </div>


                <form
                    method="POST"
                    action="{{ route(
                        'admin.procurement.tenders.prequalifications.destroy',
                        [
                            'procurementTender' =>
                                $procurementTender,

                            'prequalification' =>
                                $prequalification,
                        ]
                    ) }}"
                >

                    @csrf

                    @method('DELETE')


                    <button
                        type="submit"
                        class="btn btn-danger"
                        onclick="return confirm(
                            'Delete this prequalification and all its criteria?'
                        )"
                    >

                        Delete Prequalification

                    </button>

                </form>

            </div>

        </div>

    </div>

</div>

@endsection