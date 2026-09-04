@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ================================================================ --}}
    {{-- HEADER --}}
    {{-- ================================================================ --}}

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="d-flex align-items-center gap-2 mb-1">

                <h4 class="mb-0">
                    {{ $ncr->ncr_number }}
                </h4>

                @php

                    $statusClass = match($ncr->status) {

                        'Open' =>
                            'bg-secondary',

                        'Submitted' =>
                            'bg-primary',

                        'Under Review' =>
                            'bg-info text-dark',

                        'Corrective Action Required' =>
                            'bg-warning text-dark',

                        'Corrective Action Submitted' =>
                            'bg-primary',

                        'Verification' =>
                            'bg-info text-dark',

                        'Closed' =>
                            'bg-success',

                        'Rejected' =>
                            'bg-danger',

                        default =>
                            'bg-secondary',

                    };

                    $severityClass = match($ncr->severity) {

                        'Critical' =>
                            'bg-danger',

                        'Major' =>
                            'bg-warning text-dark',

                        default =>
                            'bg-secondary',

                    };

                @endphp


                <span class="badge {{ $statusClass }}">
                    {{ $ncr->status }}
                </span>


                <span class="badge {{ $severityClass }}">
                    {{ $ncr->severity }}
                </span>

            </div>


            <div class="text-muted">

                Project:
                <strong>
                    {{ $project->project_name ?? $project->name }}
                </strong>

            </div>

        </div>


        <div class="d-flex gap-2">

            @if(
                in_array(
                    $ncr->status,
                    ['Open', 'Rejected'],
                    true
                )
            )

                <a
                    href="{{ route(
                        'admin.projects.construction.quality.ncrs.edit',
                        [
                            'project' => $project,
                            'ncr' => $ncr
                        ]
                    ) }}"
                    class="btn btn-outline-primary"
                >
                    Edit
                </a>

            @endif


            <a
                href="{{ route(
                    'admin.projects.construction.quality.ncrs.index',
                    $project
                ) }}"
                class="btn btn-outline-secondary"
            >
                ← Back
            </a>

        </div>

    </div>


    {{-- ================================================================ --}}
    {{-- SUCCESS / ERROR MESSAGES --}}
    {{-- ================================================================ --}}

    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


    @if($errors->any())

        <div class="alert alert-danger">

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- ================================================================ --}}
    {{-- WORKFLOW --}}
    {{-- ================================================================ --}}

    <div class="card shadow-sm mb-4">

        <div class="card-header">

            <h5 class="mb-0">
                NCR Workflow
            </h5>

        </div>


        <div class="card-body">

            <div class="d-flex flex-wrap gap-2">


                {{-- ---------------------------------------------------- --}}
                {{-- OPEN --}}
                {{-- ---------------------------------------------------- --}}

                @if($ncr->status === 'Open')

                    <form
                        method="POST"
                        action="{{ route(
                            'admin.projects.construction.quality.ncrs.submit',
                            [
                                'project' => $project,
                                'ncr' => $ncr
                            ]
                        ) }}"
                    >

                        @csrf

                        <button
                            type="submit"
                            class="btn btn-primary"
                            onclick="return confirm('Submit this NCR for review?')"
                        >
                            Submit NCR
                        </button>

                    </form>

                @endif


                {{-- ---------------------------------------------------- --}}
                {{-- SUBMITTED --}}
                {{-- ---------------------------------------------------- --}}

                @if($ncr->status === 'Submitted')

                    <form
                        method="POST"
                        action="{{ route(
                            'admin.projects.construction.quality.ncrs.start-review',
                            [
                                'project' => $project,
                                'ncr' => $ncr
                            ]
                        ) }}"
                    >

                        @csrf

                        <button
                            type="submit"
                            class="btn btn-info"
                        >
                            Start Review
                        </button>

                    </form>

                @endif


                {{-- ---------------------------------------------------- --}}
                {{-- UNDER REVIEW --}}
                {{-- ---------------------------------------------------- --}}

                @if($ncr->status === 'Under Review')

                    <form
                        method="POST"
                        action="{{ route(
                            'admin.projects.construction.quality.ncrs.require-corrective-action',
                            [
                                'project' => $project,
                                'ncr' => $ncr
                            ]
                        ) }}"
                    >

                        @csrf

                        <button
                            type="submit"
                            class="btn btn-warning"
                        >
                            Require Corrective Action
                        </button>

                    </form>

                @endif


                {{-- ---------------------------------------------------- --}}
                {{-- CORRECTIVE ACTION REQUIRED --}}
                {{-- ---------------------------------------------------- --}}

                @if(
                    $ncr->status ===
                    'Corrective Action Required'
                )

                    <button
                        type="button"
                        class="btn btn-primary"
                        data-bs-toggle="modal"
                        data-bs-target="#correctiveActionModal"
                    >
                        Submit Corrective Action
                    </button>

                @endif


                {{-- ---------------------------------------------------- --}}
                {{-- CORRECTIVE ACTION SUBMITTED --}}
                {{-- ---------------------------------------------------- --}}

                @if(
                    $ncr->status ===
                    'Corrective Action Submitted'
                )

                    <form
                        method="POST"
                        action="{{ route(
                            'admin.projects.construction.quality.ncrs.start-verification',
                            [
                                'project' => $project,
                                'ncr' => $ncr
                            ]
                        ) }}"
                    >

                        @csrf

                        <button
                            type="submit"
                            class="btn btn-info"
                        >
                            Start Verification
                        </button>

                    </form>

                @endif


                {{-- ---------------------------------------------------- --}}
                {{-- VERIFICATION --}}
                {{-- ---------------------------------------------------- --}}

                @if($ncr->status === 'Verification')

                    <button
                        type="button"
                        class="btn btn-success"
                        data-bs-toggle="modal"
                        data-bs-target="#verifyModal"
                    >
                        Verify & Close
                    </button>


                    <button
                        type="button"
                        class="btn btn-warning"
                        data-bs-toggle="modal"
                        data-bs-target="#returnCorrectionModal"
                    >
                        Return for Correction
                    </button>

                @endif


                {{-- ---------------------------------------------------- --}}
                {{-- CLOSED --}}
                {{-- ---------------------------------------------------- --}}

                @if($ncr->status === 'Closed')

                    <span class="badge bg-success fs-6 px-3 py-2">

                        ✓ NCR Closed

                    </span>

                @endif

            </div>

        </div>

    </div>


    {{-- ================================================================ --}}
    {{-- NCR DETAILS --}}
    {{-- ================================================================ --}}

    <div class="row g-4">


        {{-- ============================================================ --}}
        {{-- LEFT --}}
        {{-- ============================================================ --}}

        <div class="col-lg-8">


            {{-- -------------------------------------------------------- --}}
            {{-- NCR INFORMATION --}}
            {{-- -------------------------------------------------------- --}}

            <div class="card shadow-sm mb-4">

                <div class="card-header">

                    <h5 class="mb-0">
                        NCR Details
                    </h5>

                </div>


                <div class="card-body">

                    <div class="row g-3">


                        <div class="col-md-6">

                            <div class="text-muted small">
                                NCR Number
                            </div>

                            <div class="fw-semibold">
                                {{ $ncr->ncr_number }}
                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small">
                                NCR Date
                            </div>

                            <div>
                                {{ $ncr->ncr_date?->format('d-m-Y') }}
                            </div>

                        </div>


                        <div class="col-md-12">

                            <div class="text-muted small">
                                Title
                            </div>

                            <div class="fw-semibold">
                                {{ $ncr->title }}
                            </div>

                        </div>


                        <div class="col-md-12">

                            <div class="text-muted small">
                                Description
                            </div>

                            <div class="border rounded p-3 bg-light">

                                {!! nl2br(
                                    e($ncr->description)
                                ) !!}

                            </div>

                        </div>


                        @if($ncr->location)

                            <div class="col-md-6">

                                <div class="text-muted small">
                                    Location
                                </div>

                                <div>
                                    {{ $ncr->location }}
                                </div>

                            </div>

                        @endif


                        <div class="col-md-6">

                            <div class="text-muted small">
                                Severity
                            </div>

                            <span
                                class="badge {{ $severityClass }}"
                            >
                                {{ $ncr->severity }}
                            </span>

                        </div>

                    </div>

                </div>

            </div>


            {{-- -------------------------------------------------------- --}}
            {{-- PROJECT REFERENCES --}}
            {{-- -------------------------------------------------------- --}}

            <div class="card shadow-sm mb-4">

                <div class="card-header">

                    <h5 class="mb-0">
                        Project References
                    </h5>

                </div>


                <div class="card-body">

                    <div class="row g-3">


                        {{-- Contract --}}

                        <div class="col-md-6">

                            <div class="text-muted small">
                                Procurement Contract
                            </div>

                            @if($ncr->contract)

                                <div class="fw-semibold">

                                    {{ $ncr->contract->contract_number }}

                                </div>


                                @if($ncr->contract->bidder)

                                    <div class="small text-muted">

                                        {{ $ncr->contract->bidder->company_name }}

                                    </div>

                                @elseif($ncr->contract->bidder_name)

                                    <div class="small text-muted">

                                        {{ $ncr->contract->bidder_name }}

                                    </div>

                                @endif

                            @else

                                <span class="text-muted">
                                    —
                                </span>

                            @endif

                        </div>


                        {{-- Work Order --}}

                        <div class="col-md-6">

                            <div class="text-muted small">
                                Work Order
                            </div>

                            @if($ncr->workOrder)

                                <div class="fw-semibold">

                                    {{ $ncr->workOrder->work_order_number }}

                                </div>

                                <div class="small text-muted">

                                    {{ $ncr->workOrder->work_order_title }}

                                </div>

                            @else

                                <span class="text-muted">
                                    —
                                </span>

                            @endif

                        </div>


                        {{-- ITP --}}

                        <div class="col-md-6">

                            <div class="text-muted small">
                                ITP
                            </div>

                            @if($ncr->itp)

                                <div class="fw-semibold">

                                    {{ $ncr->itp->itp_number }}

                                </div>

                                <div class="small text-muted">

                                    {{ $ncr->itp->title }}

                                </div>

                            @else

                                <span class="text-muted">
                                    —
                                </span>

                            @endif

                        </div>


                        {{-- ITP Item --}}

                        <div class="col-md-6">

                            <div class="text-muted small">
                                ITP Item
                            </div>

                            @if($ncr->itpItem)

                                <div>

                                    {{ $ncr->itpItem->item_number }}

                                    @if($ncr->itpItem->activity)

                                        -
                                        {{ $ncr->itpItem->activity }}

                                    @endif

                                </div>

                            @else

                                <span class="text-muted">
                                    —
                                </span>

                            @endif

                        </div>


                        {{-- Inspection --}}

                        <div class="col-md-6">

                            <div class="text-muted small">
                                Related Inspection
                            </div>

                            @if($ncr->inspection)

                                <div class="fw-semibold">

                                    {{ $ncr->inspection->inspection_number }}

                                </div>

                                @if($ncr->inspection->title)

                                    <div class="small text-muted">

                                        {{ $ncr->inspection->title }}

                                    </div>

                                @endif

                                @if($ncr->inspection->result)

                                    <span
                                        class="badge bg-danger mt-1"
                                    >
                                        {{ $ncr->inspection->result }}
                                    </span>

                                @endif

                            @else

                                <span class="text-muted">
                                    —
                                </span>

                            @endif

                        </div>


                        {{-- Raised By --}}

                        <div class="col-md-6">

                            <div class="text-muted small">
                                Raised By
                            </div>

                            <div>
                                {{ $ncr->raisedBy?->name ?? '—' }}
                            </div>

                        </div>


                    </div>

                </div>

            </div>


            {{-- -------------------------------------------------------- --}}
            {{-- REQUIRED ACTION --}}
            {{-- -------------------------------------------------------- --}}

            <div class="card shadow-sm mb-4">

                <div class="card-header">

                    <h5 class="mb-0">
                        Required Corrective Action
                    </h5>

                </div>


                <div class="card-body">

                    @if($ncr->required_action)

                        <div class="border rounded p-3">

                            {!! nl2br(
                                e($ncr->required_action)
                            ) !!}

                        </div>

                    @else

                        <span class="text-muted">
                            No corrective action specified.
                        </span>

                    @endif


                    @if($ncr->due_date)

                        <div class="mt-3">

                            <strong>
                                Due Date:
                            </strong>

                            {{ $ncr->due_date->format('d-m-Y') }}

                            @if(
                                $ncr->due_date->isPast()
                                &&
                                $ncr->status !== 'Closed'
                            )

                                <span class="badge bg-danger ms-2">
                                    Overdue
                                </span>

                            @endif

                        </div>

                    @endif

                </div>

            </div>


            {{-- -------------------------------------------------------- --}}
            {{-- VERIFICATION --}}
            {{-- -------------------------------------------------------- --}}

            @if(
                $ncr->verification_remarks
                ||
                $ncr->verified_at
            )

                <div class="card shadow-sm mb-4">

                    <div class="card-header">

                        <h5 class="mb-0">
                            Verification
                        </h5>

                    </div>


                    <div class="card-body">

                        @if($ncr->verifiedBy)

                            <div class="mb-2">

                                <span class="text-muted">
                                    Verified By:
                                </span>

                                <strong>
                                    {{ $ncr->verifiedBy->name }}
                                </strong>

                            </div>

                        @endif


                        @if($ncr->verified_at)

                            <div class="mb-2">

                                <span class="text-muted">
                                    Verified At:
                                </span>

                                {{ $ncr->verified_at->format(
                                    'd-m-Y H:i'
                                ) }}

                            </div>

                        @endif


                        @if($ncr->verification_remarks)

                            <div class="border rounded p-3">

                                {!! nl2br(
                                    e($ncr->verification_remarks)
                                ) !!}

                            </div>

                        @endif

                    </div>

                </div>

            @endif


            {{-- -------------------------------------------------------- --}}
            {{-- CLOSURE --}}
            {{-- -------------------------------------------------------- --}}

            @if($ncr->closed_at)

                <div class="card shadow-sm mb-4">

                    <div class="card-header">

                        <h5 class="mb-0 text-success">
                            Closure
                        </h5>

                    </div>


                    <div class="card-body">

                        <div class="row g-3">

                            <div class="col-md-6">

                                <div class="text-muted small">
                                    Closed By
                                </div>

                                <div>
                                    {{ $ncr->closedBy?->name ?? '—' }}
                                </div>

                            </div>


                            <div class="col-md-6">

                                <div class="text-muted small">
                                    Closed At
                                </div>

                                <div>
                                    {{ $ncr->closed_at->format(
                                        'd-m-Y H:i'
                                    ) }}
                                </div>

                            </div>


                            @if($ncr->closure_remarks)

                                <div class="col-md-12">

                                    <div class="text-muted small">
                                        Closure Remarks
                                    </div>

                                    <div class="border rounded p-3">

                                        {!! nl2br(
                                            e($ncr->closure_remarks)
                                        ) !!}

                                    </div>

                                </div>

                            @endif

                        </div>

                    </div>

                </div>

            @endif

        </div>


        {{-- ============================================================ --}}
        {{-- RIGHT --}}
        {{-- ============================================================ --}}

        <div class="col-lg-4">


            {{-- -------------------------------------------------------- --}}
            {{-- STATUS CARD --}}
            {{-- -------------------------------------------------------- --}}

            <div class="card shadow-sm mb-4">

                <div class="card-header">

                    <h6 class="mb-0">
                        NCR Status
                    </h6>

                </div>


                <div class="card-body">

                    <div class="text-center mb-3">

                        <span
                            class="badge {{ $statusClass }} fs-6 px-3 py-2"
                        >
                            {{ $ncr->status }}
                        </span>

                    </div>


                    <div class="small">

                        <div class="d-flex justify-content-between mb-2">

                            <span class="text-muted">
                                Severity
                            </span>

                            <strong>
                                {{ $ncr->severity }}
                            </strong>

                        </div>


                        <div class="d-flex justify-content-between mb-2">

                            <span class="text-muted">
                                Responsible Party
                            </span>

                            <strong>
                                {{ $ncr->responsible_party ?? '—' }}
                            </strong>

                        </div>


                        <div class="d-flex justify-content-between">

                            <span class="text-muted">
                                Due Date
                            </span>

                            <strong>

                                {{ $ncr->due_date
                                    ? $ncr->due_date->format('d-m-Y')
                                    : '—'
                                }}

                            </strong>

                        </div>

                    </div>

                </div>

            </div>


            {{-- -------------------------------------------------------- --}}
            {{-- CORRECTIVE ACTION HISTORY --}}
            {{-- -------------------------------------------------------- --}}

            <div class="card shadow-sm">

                <div class="card-header">

                    <h6 class="mb-0">
                        Corrective Action History
                    </h6>

                </div>


                <div class="card-body">

                    @forelse(
                        $ncr->actions as $action
                    )

                        <div
                            class="border-start border-3 ps-3 mb-4"
                        >

                            <div class="fw-semibold">

                                {{ $action->action_type }}

                            </div>


                            <div class="small text-muted mb-2">

                                {{ $action->action_date?->format(
                                    'd-m-Y'
                                ) }}

                            </div>


                            <div class="small">

                                {!! nl2br(
                                    e(
                                        $action->action_description
                                    )
                                ) !!}

                            </div>


                            @if($action->responsible_party)

                                <div class="small mt-2">

                                    <span class="text-muted">
                                        Responsible:
                                    </span>

                                    {{ $action->responsible_party }}

                                </div>

                            @endif


                            @if($action->status)

                                <span
                                    class="badge bg-secondary mt-2"
                                >
                                    {{ $action->status }}
                                </span>

                            @endif

                        </div>

                    @empty

                        <div class="text-muted small">

                            No corrective action history yet.

                        </div>

                    @endforelse

                </div>

            </div>

        </div>

    </div>

</div>


{{-- ================================================================== --}}
{{-- CORRECTIVE ACTION MODAL --}}
{{-- ================================================================== --}}

@if(
    $ncr->status ===
    'Corrective Action Required'
)

<div
    class="modal fade"
    id="correctiveActionModal"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog modal-lg">

        <div class="modal-content">

            <form
                method="POST"
                action="{{ route(
                    'admin.projects.construction.quality.ncrs.submit-corrective-action',
                    [
                        'project' => $project,
                        'ncr' => $ncr
                    ]
                ) }}"
            >

                @csrf


                <div class="modal-header">

                    <h5 class="modal-title">
                        Submit Corrective Action
                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                    ></button>

                </div>


                <div class="modal-body">

                    <div class="mb-3">

                        <label class="form-label">
                            Corrective Action
                            <span class="text-danger">*</span>
                        </label>

                        <textarea
                            name="action_description"
                            rows="5"
                            class="form-control"
                            placeholder="Describe the corrective action taken..."
                            required
                        ></textarea>

                    </div>


                    <div class="row g-3">

                        <div class="col-md-6">

                            <label class="form-label">
                                Responsible Party
                            </label>

                            <input
                                type="text"
                                name="responsible_party"
                                class="form-control"
                                value="{{ $ncr->responsible_party }}"
                            >

                        </div>


                        <div class="col-md-6">

                            <label class="form-label">
                                Responsible User
                            </label>

                            <select
                                name="responsible_user_id"
                                class="form-select"
                            >

                                <option value="">
                                    Select User
                                </option>

                                @foreach($users as $user)

                                    <option
                                        value="{{ $user->id }}"
                                    >
                                        {{ $user->name }}
                                    </option>

                                @endforeach

                            </select>

                        </div>


                        <div class="col-md-6">

                            <label class="form-label">
                                Due Date
                            </label>

                            <input
                                type="date"
                                name="due_date"
                                class="form-control"
                                value="{{ $ncr->due_date?->format('Y-m-d') }}"
                            >

                        </div>


                        <div class="col-md-6">

                            <label class="form-label">
                                Completed Date
                            </label>

                            <input
                                type="date"
                                name="completed_date"
                                class="form-control"
                                value="{{ now()->format('Y-m-d') }}"
                            >

                        </div>

                    </div>

                </div>


                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-light border"
                        data-bs-dismiss="modal"
                    >
                        Cancel
                    </button>


                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        Submit Corrective Action
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endif


{{-- ================================================================== --}}
{{-- VERIFY MODAL --}}
{{-- ================================================================== --}}

@if($ncr->status === 'Verification')

<div
    class="modal fade"
    id="verifyModal"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog">

        <div class="modal-content">

            <form
                method="POST"
                action="{{ route(
                    'admin.projects.construction.quality.ncrs.verify',
                    [
                        'project' => $project,
                        'ncr' => $ncr
                    ]
                ) }}"
            >

                @csrf


                <div class="modal-header">

                    <h5 class="modal-title">
                        Verify & Close NCR
                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                    ></button>

                </div>


                <div class="modal-body">

                    <div class="alert alert-success">

                        Confirm that the corrective action has
                        been satisfactorily completed.

                    </div>


                    <label class="form-label">

                        Verification Remarks
                        <span class="text-danger">*</span>

                    </label>


                    <textarea
                        name="verification_remarks"
                        rows="5"
                        class="form-control"
                        placeholder="Enter verification and closure remarks..."
                        required
                    ></textarea>

                </div>


                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-light border"
                        data-bs-dismiss="modal"
                    >
                        Cancel
                    </button>


                    <button
                        type="submit"
                        class="btn btn-success"
                    >
                        Verify & Close
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endif


{{-- ================================================================== --}}
{{-- RETURN FOR CORRECTION MODAL --}}
{{-- ================================================================== --}}

@if($ncr->status === 'Verification')

<div
    class="modal fade"
    id="returnCorrectionModal"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog">

        <div class="modal-content">

            <form
                method="POST"
                action="{{ route(
                    'admin.projects.construction.quality.ncrs.return-for-correction',
                    [
                        'project' => $project,
                        'ncr' => $ncr
                    ]
                ) }}"
            >

                @csrf


                <div class="modal-header">

                    <h5 class="modal-title">
                        Return for Correction
                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                    ></button>

                </div>


                <div class="modal-body">

                    <div class="alert alert-warning">

                        The corrective action will be rejected
                        and the NCR will return to
                        <strong>
                            Corrective Action Required
                        </strong>.

                    </div>


                    <label class="form-label">

                        Reason
                        <span class="text-danger">*</span>

                    </label>


                    <textarea
                        name="verification_remarks"
                        rows="5"
                        class="form-control"
                        placeholder="Explain why further corrective action is required..."
                        required
                    ></textarea>

                </div>


                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-light border"
                        data-bs-dismiss="modal"
                    >
                        Cancel
                    </button>


                    <button
                        type="submit"
                        class="btn btn-warning"
                    >
                        Return for Correction
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endif

@endsection