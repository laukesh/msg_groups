@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ============================================================= --}}
    {{-- HEADER --}}
    {{-- ============================================================= --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <div class="d-flex align-items-center gap-2">

                <h4 class="mb-0">
                    {{ $inspection->inspection_number }}
                </h4>

                @php
                    $statusClass = match($inspection->status) {

                        'Planned' =>
                            'bg-secondary',

                        'Scheduled' =>
                            'bg-primary',

                        'Conducted' =>
                            'bg-warning text-dark',

                        'Closed' =>
                            'bg-success',

                        default =>
                            'bg-light text-dark',
                    };

                    $resultClass = match($inspection->result) {

                        'Passed' =>
                            'bg-success',

                        'Failed' =>
                            'bg-danger',

                        'Conditional' =>
                            'bg-warning text-dark',

                        default =>
                            'bg-light text-dark',
                    };
                @endphp

                <span class="badge {{ $statusClass }}">
                    {{ $inspection->status }}
                </span>

                @if($inspection->result)

                    <span class="badge {{ $resultClass }}">
                        {{ $inspection->result }}
                    </span>

                @endif

            </div>

            <div class="text-muted mt-1">

                {{ $inspection->title }}

            </div>

            <div class="small text-muted">

                Project:
                {{ $project->name ?? $project->project_name ?? 'Project' }}

            </div>

        </div>


        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.projects.construction.inspections.index',
                    $project
                ) }}"
                class="btn btn-outline-secondary"
            >
                ← Back
            </a>


            @if(
                in_array(
                    $inspection->status,
                    ['Planned', 'Scheduled'],
                    true
                )
            )

                <a
                    href="{{ route(
                        'admin.projects.construction.inspections.edit',
                        [
                            'project' =>
                                $project,

                            'inspection' =>
                                $inspection,
                        ]
                    ) }}"
                    class="btn btn-primary"
                >
                    Edit
                </a>

            @endif

        </div>

    </div>


    {{-- ============================================================= --}}
    {{-- FLASH MESSAGES --}}
    {{-- ============================================================= --}}

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

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


    <div class="row g-4">

        {{-- ========================================================= --}}
        {{-- LEFT COLUMN --}}
        {{-- ========================================================= --}}

        <div class="col-lg-8">


            {{-- ===================================================== --}}
            {{-- BASIC DETAILS --}}
            {{-- ===================================================== --}}

            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white">

                    <h5 class="mb-0">
                        Inspection Details
                    </h5>

                </div>


                <div class="card-body">

                    <div class="row g-4">

                        <div class="col-md-4">

                            <div class="text-muted small">
                                Inspection Number
                            </div>

                            <div class="fw-semibold">
                                {{ $inspection->inspection_number }}
                            </div>

                        </div>


                        <div class="col-md-4">

                            <div class="text-muted small">
                                Inspection Date
                            </div>

                            <div class="fw-semibold">

                                {{ optional(
                                    $inspection->inspection_date
                                )->format('d M Y') ?? '—' }}

                            </div>

                        </div>


                        <div class="col-md-4">

                            <div class="text-muted small">
                                Inspection Type
                            </div>

                            <div class="fw-semibold">

                                {{ $inspection->inspection_type ?? '—' }}

                            </div>

                        </div>


                        <div class="col-md-12">

                            <div class="text-muted small">
                                Title
                            </div>

                            <div class="fw-semibold">
                                {{ $inspection->title }}
                            </div>

                        </div>


                        <div class="col-md-12">

                            <div class="text-muted small">
                                Description
                            </div>

                            <div style="white-space: pre-wrap;">
                                {{ $inspection->description ?? '—' }}
                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small">
                                Location
                            </div>

                            <div>
                                {{ $inspection->location ?? '—' }}
                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small">
                                Priority
                            </div>

                            <div>

                                <span class="badge
                                    @if($inspection->priority === 'Critical')
                                        bg-danger
                                    @elseif($inspection->priority === 'High')
                                        bg-warning text-dark
                                    @elseif($inspection->priority === 'Low')
                                        bg-secondary
                                    @else
                                        bg-primary
                                    @endif
                                ">
                                    {{ $inspection->priority }}
                                </span>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- ===================================================== --}}
            {{-- PROJECT REFERENCES --}}
            {{-- ===================================================== --}}

            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white">

                    <h5 class="mb-0">
                        Project References
                    </h5>

                </div>


                <div class="card-body">

                    <div class="row g-4">

                        {{-- Contractor --}}
                        <div class="col-md-6">

                            <div class="text-muted small">
                                Contractor
                            </div>

                            @if(
                                $inspection->contract?->bidder
                            )

                                <div class="fw-semibold">

                                    {{
                                        $inspection
                                            ->contract
                                            ->bidder
                                            ->company_name
                                    }}

                                </div>

                                <div class="small text-muted">

                                    {{
                                        $inspection
                                            ->contract
                                            ->contract_number
                                    }}

                                </div>

                            @elseif(
                                $inspection->contract?->bidder_name
                            )

                                <div class="fw-semibold">

                                    {{
                                        $inspection
                                            ->contract
                                            ->bidder_name
                                    }}

                                </div>

                            @else

                                <span class="text-muted">
                                    —
                                </span>

                            @endif

                        </div>


                        {{-- Consultant --}}
                        <div class="col-md-6">

                            <div class="text-muted small">
                                Consultant
                            </div>

                            @if($inspection->consultant)

                                <div class="fw-semibold">

                                    {{
                                        $inspection
                                            ->consultant
                                            ->company_name
                                    }}

                                </div>

                                @if(
                                    $inspection
                                        ->consultant
                                        ->consultant_name
                                )

                                    <div class="small text-muted">

                                        {{
                                            $inspection
                                                ->consultant
                                                ->consultant_name
                                        }}

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

                            @if($inspection->workOrder)

                                <div class="fw-semibold">

                                    {{
                                        $inspection
                                            ->workOrder
                                            ->work_order_number
                                    }}

                                </div>

                                <div class="small text-muted">

                                    {{
                                        $inspection
                                            ->workOrder
                                            ->work_order_title
                                    }}

                                </div>

                            @else

                                <span class="text-muted">
                                    —
                                </span>

                            @endif

                        </div>


                        {{-- Schedule Activity --}}
                        <div class="col-md-6">

                            <div class="text-muted small">
                                Schedule Activity
                            </div>

                            @if($inspection->scheduleActivity)

                                <div class="fw-semibold">

                                    {{
                                        $inspection
                                            ->scheduleActivity
                                            ->activity_name
                                        ??
                                        $inspection
                                            ->scheduleActivity
                                            ->name
                                        ??
                                        ('Activity #' .
                                            $inspection
                                                ->scheduleActivity
                                                ->id)
                                    }}

                                </div>

                            @else

                                <span class="text-muted">
                                    —
                                </span>

                            @endif

                        </div>

                    </div>

                </div>

            </div>


            {{-- ===================================================== --}}
            {{-- SCHEDULING --}}
            {{-- ===================================================== --}}

            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white">

                    <h5 class="mb-0">
                        Scheduling & Personnel
                    </h5>

                </div>


                <div class="card-body">

                    <div class="row g-4">

                        <div class="col-md-4">

                            <div class="text-muted small">
                                Planned Date
                            </div>

                            <div>
                                {{ optional(
                                    $inspection->planned_date
                                )->format('d M Y') ?? '—' }}
                            </div>

                        </div>


                        <div class="col-md-4">

                            <div class="text-muted small">
                                Scheduled Date
                            </div>

                            <div>
                                {{ optional(
                                    $inspection->scheduled_date
                                )->format('d M Y') ?? '—' }}
                            </div>

                        </div>


                        <div class="col-md-4">

                            <div class="text-muted small">
                                Conducted Date
                            </div>

                            <div>
                                {{ optional(
                                    $inspection->conducted_date
                                )->format('d M Y') ?? '—' }}
                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small">
                                Inspector
                            </div>

                            <div class="fw-semibold">

                                {{
                                    $inspection
                                        ->inspector
                                        ?->name
                                    ?? '—'
                                }}

                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small">
                                Witnessed By
                            </div>

                            <div class="fw-semibold">

                                {{
                                    $inspection
                                        ->witness
                                        ?->name
                                    ?? '—'
                                }}

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- ===================================================== --}}
            {{-- FINDINGS --}}
            {{-- ===================================================== --}}

            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white">

                    <h5 class="mb-0">
                        Inspection Findings
                    </h5>

                </div>


                <div class="card-body">

                    <div class="mb-4">

                        <div class="text-muted small mb-1">
                            Observations
                        </div>

                        <div style="white-space: pre-wrap;">

                            {{ $inspection->observations ?? '—' }}

                        </div>

                    </div>


                    <div class="mb-4">

                        <div class="text-muted small mb-1">
                            Non-Conformance / Deficiencies
                        </div>

                        <div style="white-space: pre-wrap;">

                            {{ $inspection->non_conformance ?? '—' }}

                        </div>

                    </div>


                    <div>

                        <div class="text-muted small mb-1">
                            Corrective Action
                        </div>

                        <div style="white-space: pre-wrap;">

                            {{ $inspection->corrective_action ?? '—' }}

                        </div>

                    </div>

                </div>

            </div>


            {{-- ===================================================== --}}
            {{-- CORRECTIVE ACTION --}}
            {{-- ===================================================== --}}

            @if(
                $inspection->corrective_action ||
                $inspection->corrective_action_due_date ||
                $inspection->corrective_action_date
            )

                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white">

                        <h5 class="mb-0">
                            Corrective Action
                        </h5>

                    </div>


                    <div class="card-body">

                        <div class="row g-4">

                            <div class="col-md-6">

                                <div class="text-muted small">
                                    Due Date
                                </div>

                                <div>

                                    {{
                                        optional(
                                            $inspection
                                                ->corrective_action_due_date
                                        )->format('d M Y')
                                        ?? '—'
                                    }}

                                </div>

                            </div>


                            <div class="col-md-6">

                                <div class="text-muted small">
                                    Completed Date
                                </div>

                                <div>

                                    {{
                                        optional(
                                            $inspection
                                                ->corrective_action_date
                                        )->format('d M Y')
                                        ?? '—'
                                    }}

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            @endif


            {{-- ===================================================== --}}
            {{-- RE-INSPECTION --}}
            {{-- ===================================================== --}}

            @if(
                $inspection->reinspection_required ||
                $inspection->reinspection_date
            )

                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white">

                        <h5 class="mb-0">
                            Re-inspection
                        </h5>

                    </div>


                    <div class="card-body">

                        <div class="row g-4">

                            <div class="col-md-6">

                                <div class="text-muted small">
                                    Required
                                </div>

                                <span class="badge bg-warning text-dark">
                                    Yes
                                </span>

                            </div>


                            <div class="col-md-6">

                                <div class="text-muted small">
                                    Re-inspection Date
                                </div>

                                <div>

                                    {{
                                        optional(
                                            $inspection
                                                ->reinspection_date
                                        )->format('d M Y')
                                        ?? '—'
                                    }}

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            @endif


            {{-- ===================================================== --}}
            {{-- REMARKS --}}
            {{-- ===================================================== --}}

            @if($inspection->remarks)

                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white">

                        <h5 class="mb-0">
                            Remarks
                        </h5>

                    </div>

                    <div class="card-body">

                        <div style="white-space: pre-wrap;">

                            {{ $inspection->remarks }}

                        </div>

                    </div>

                </div>

            @endif

        </div>


        {{-- ========================================================= --}}
        {{-- RIGHT COLUMN --}}
        {{-- ========================================================= --}}

        <div class="col-lg-4">


            {{-- ===================================================== --}}
            {{-- CURRENT STATUS --}}
            {{-- ===================================================== --}}

            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white">

                    <h5 class="mb-0">
                        Inspection Status
                    </h5>

                </div>


                <div class="card-body text-center">

                    <span
                        class="badge {{ $statusClass }} fs-6 px-3 py-2"
                    >
                        {{ $inspection->status }}
                    </span>


                    @if($inspection->result)

                        <div class="mt-3">

                            <div class="text-muted small">
                                Result
                            </div>

                            <span
                                class="badge {{ $resultClass }} fs-6 px-3 py-2"
                            >
                                {{ $inspection->result }}
                            </span>

                        </div>

                    @endif

                </div>

            </div>


            {{-- ===================================================== --}}
            {{-- AUDIT --}}
            {{-- ===================================================== --}}

            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white">

                    <h5 class="mb-0">
                        Record Information
                    </h5>

                </div>


                <div class="card-body">

                    <div class="mb-3">

                        <div class="text-muted small">
                            Created By
                        </div>

                        <div>

                            {{
                                $inspection
                                    ->creator
                                    ?->name
                                ?? '—'
                            }}

                        </div>

                    </div>


                    <div>

                        <div class="text-muted small">
                            Created At
                        </div>

                        <div>

                            {{
                                optional(
                                    $inspection->created_at
                                )->format('d M Y H:i')
                                ?? '—'
                            }}

                        </div>

                    </div>

                </div>

            </div>

            {{-- ===================================================== --}}
            {{-- WORKFLOW --}}
            {{-- ===================================================== --}}

            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white">

                    <h5 class="mb-0">
                        Workflow Actions
                    </h5>

                </div>


                <div class="card-body">

                    {{-- ================================================= --}}
                    {{-- PLANNED --}}
                    {{-- ================================================= --}}

                    @if($inspection->status === 'Planned')

                        <div class="mb-3">

                            <div class="text-muted small mb-3">
                                This inspection is planned and can now be scheduled.
                            </div>

                            <button
                                type="button"
                                class="btn btn-primary w-100"
                                data-bs-toggle="modal"
                                data-bs-target="#scheduleInspectionModal"
                            >
                                Schedule Inspection
                            </button>

                        </div>

                    @endif


                    {{-- ================================================= --}}
                    {{-- SCHEDULED --}}
                    {{-- ================================================= --}}

                    @if($inspection->status === 'Scheduled')

                        <div class="mb-3">

                            <div class="text-muted small mb-3">
                                The inspection has been scheduled and can now be conducted.
                            </div>

                            <button
                                type="button"
                                class="btn btn-primary w-100"
                                data-bs-toggle="modal"
                                data-bs-target="#conductInspectionModal"
                            >
                                Conduct Inspection
                            </button>

                        </div>

                    @endif


                    {{-- ================================================= --}}
                    {{-- CONDUCTED - NO RESULT --}}
                    {{-- ================================================= --}}

                    @if(
                        $inspection->status === 'Conducted'
                        &&
                        !$inspection->result
                    )

                        <div class="mb-3">

                            <div class="text-muted small mb-3">
                                Record the inspection result.
                            </div>


                            <div class="d-grid gap-2">

                                <button
                                    type="button"
                                    class="btn btn-success"
                                    data-bs-toggle="modal"
                                    data-bs-target="#passInspectionModal"
                                >
                                    ✓ Pass Inspection
                                </button>


                                <button
                                    type="button"
                                    class="btn btn-danger"
                                    data-bs-toggle="modal"
                                    data-bs-target="#failInspectionModal"
                                >
                                    ✕ Fail Inspection
                                </button>

                            </div>

                        </div>

                    @endif


                    {{-- ================================================= --}}
                    {{-- FAILED - CORRECTIVE ACTION PENDING --}}
                    {{-- ================================================= --}}

                    @if(
                        $inspection->result === 'Failed'
                        &&
                        $inspection->reinspection_required
                        &&
                        !$inspection->corrective_action_date
                    )

                        <div class="mb-3">

                            <div class="alert alert-danger">

                                <div class="fw-semibold mb-1">
                                    Corrective Action Required
                                </div>

                                <div class="small">
                                    The inspection failed and corrective action
                                    must be completed before re-inspection.
                                </div>

                            </div>


                            <button
                                type="button"
                                class="btn btn-warning w-100"
                                data-bs-toggle="modal"
                                data-bs-target="#correctiveActionModal"
                            >
                                Record Corrective Action
                            </button>

                        </div>

                    @endif


                    {{-- ================================================= --}}
                    {{-- FAILED - READY FOR REINSPECTION --}}
                    {{-- ================================================= --}}

                    @if(
                        $inspection->result === 'Failed'
                        &&
                        $inspection->reinspection_required
                        &&
                        $inspection->corrective_action_date
                    )

                        <div class="mb-3">

                            <div class="alert alert-warning">

                                <div class="fw-semibold mb-1">
                                    Ready for Re-inspection
                                </div>

                                <div class="small">
                                    Corrective action has been completed.
                                    A re-inspection can now be recorded.
                                </div>

                            </div>


                            <button
                                type="button"
                                class="btn btn-primary w-100"
                                data-bs-toggle="modal"
                                data-bs-target="#reinspectionModal"
                            >
                                Conduct Re-inspection
                            </button>

                        </div>

                    @endif


                    {{-- ================================================= --}}
                    {{-- PASSED - CLOSE --}}
                    {{-- ================================================= --}}

                    @if(
                        $inspection->status === 'Conducted'
                        &&
                        $inspection->result === 'Passed'
                        &&
                        !$inspection->reinspection_required
                    )

                        <div class="mb-3">

                            <div class="alert alert-success">

                                <div class="fw-semibold mb-1">
                                    Inspection Passed
                                </div>

                                <div class="small">
                                    This inspection is ready to be closed.
                                </div>

                            </div>


                            <form
                                method="POST"
                                action="{{ route(
                                    'admin.projects.construction.inspections.close',
                                    [
                                        'project' =>
                                            $project,

                                        'inspection' =>
                                            $inspection,
                                    ]
                                ) }}"
                                onsubmit="return confirm(
                                    'Close this inspection?'
                                );"
                            >

                                @csrf

                                <button
                                    type="submit"
                                    class="btn btn-success w-100"
                                >
                                    Close Inspection
                                </button>

                            </form>

                        </div>

                    @endif


                    {{-- ================================================= --}}
                    {{-- CLOSED --}}
                    {{-- ================================================= --}}

                    @if($inspection->status === 'Closed')

                        <div class="text-center">

                            <div class="text-success mb-2">

                                <i class="bi bi-check-circle-fill fs-2"></i>

                            </div>

                            <div class="fw-semibold">
                                Inspection Closed
                            </div>

                            <div class="text-muted small mt-1">
                                No further workflow action is required.
                            </div>

                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>

</div>

{{-- ================================================================ --}}
{{-- SCHEDULE INSPECTION MODAL --}}
{{-- ================================================================ --}}

<div
    class="modal fade"
    id="scheduleInspectionModal"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">
                    Schedule Inspection
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                ></button>

            </div>


            <form
                method="POST"
                action="{{ route(
                    'admin.projects.construction.inspections.schedule',
                    [
                        'project' =>
                            $project,

                        'inspection' =>
                            $inspection,
                    ]
                ) }}"
            >

                @csrf

                <div class="modal-body">

                    <div class="mb-3">

                        <label class="form-label">
                            Scheduled Date
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="date"
                            name="scheduled_date"
                            class="form-control"
                            value="{{ old(
                                'scheduled_date',
                                $inspection->scheduled_date?->format('Y-m-d')
                            ) }}"
                            required
                        >

                    </div>

                </div>


                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-light"
                        data-bs-dismiss="modal"
                    >
                        Cancel
                    </button>

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        Schedule Inspection
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

{{-- ================================================================ --}}
{{-- CONDUCT INSPECTION MODAL --}}
{{-- ================================================================ --}}

<div
    class="modal fade"
    id="conductInspectionModal"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog modal-lg">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">
                    Conduct Inspection
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                ></button>

            </div>


            <form
                method="POST"
                action="{{ route(
                    'admin.projects.construction.inspections.conduct',
                    [
                        'project' =>
                            $project,

                        'inspection' =>
                            $inspection,
                    ]
                ) }}"
            >

                @csrf


                <div class="modal-body">

                    <div class="row g-3">

                        <div class="col-md-6">

                            <label class="form-label">
                                Conducted Date
                                <span class="text-danger">*</span>
                            </label>

                            <input
                                type="date"
                                name="conducted_date"
                                class="form-control"
                                value="{{ now()->format('Y-m-d') }}"
                                required
                            >

                        </div>


                        <div class="col-md-6">

                            <label class="form-label">
                                Inspector
                                <span class="text-danger">*</span>
                            </label>

                            <select
                                name="inspected_by"
                                class="form-select"
                                required
                            >

                                <option value="">
                                    Select Inspector
                                </option>

                                @foreach($users as $user)

                                    <option
                                        value="{{ $user->id }}"
                                        @selected(
                                            $inspection->inspected_by == $user->id
                                        )
                                    >
                                        {{ $user->name }}
                                    </option>

                                @endforeach

                            </select>

                        </div>


                        <div class="col-md-6">

                            <label class="form-label">
                                Witnessed By
                            </label>

                            <select
                                name="witnessed_by"
                                class="form-select"
                            >

                                <option value="">
                                    Select Witness
                                </option>

                                @foreach($users as $user)

                                    <option
                                        value="{{ $user->id }}"
                                        @selected(
                                            $inspection->witnessed_by == $user->id
                                        )
                                    >
                                        {{ $user->name }}
                                    </option>

                                @endforeach

                            </select>

                        </div>


                        <div class="col-md-12">

                            <label class="form-label">
                                Observations
                            </label>

                            <textarea
                                name="observations"
                                rows="4"
                                class="form-control"
                                placeholder="Enter inspection observations..."
                            >{{ $inspection->observations }}</textarea>

                        </div>


                        <div class="col-md-12">

                            <label class="form-label">
                                Non-Conformance / Deficiencies
                            </label>

                            <textarea
                                name="non_conformance"
                                rows="4"
                                class="form-control"
                                placeholder="Record any deficiencies..."
                            >{{ $inspection->non_conformance }}</textarea>

                        </div>


                        <div class="col-md-12">

                            <label class="form-label">
                                Remarks
                            </label>

                            <textarea
                                name="remarks"
                                rows="3"
                                class="form-control"
                            >{{ $inspection->remarks }}</textarea>

                        </div>

                    </div>

                </div>


                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-light"
                        data-bs-dismiss="modal"
                    >
                        Cancel
                    </button>

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        Mark as Conducted
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

{{-- ================================================================ --}}
{{-- PASS INSPECTION MODAL --}}
{{-- ================================================================ --}}

<div
    class="modal fade"
    id="passInspectionModal"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title text-success">
                    Pass Inspection
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                ></button>

            </div>


            <form
                method="POST"
                action="{{ route(
                    'admin.projects.construction.inspections.pass',
                    [
                        'project' =>
                            $project,

                        'inspection' =>
                            $inspection,
                    ]
                ) }}"
            >

                @csrf


                <div class="modal-body">

                    <div class="alert alert-success">

                        Are you sure this inspection has passed?

                    </div>


                    <div class="mb-3">

                        <label class="form-label">
                            Remarks
                        </label>

                        <textarea
                            name="remarks"
                            rows="4"
                            class="form-control"
                            placeholder="Optional remarks..."
                        ></textarea>

                    </div>

                </div>


                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-light"
                        data-bs-dismiss="modal"
                    >
                        Cancel
                    </button>

                    <button
                        type="submit"
                        class="btn btn-success"
                    >
                        Pass Inspection
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

{{-- ================================================================ --}}
{{-- FAIL INSPECTION MODAL --}}
{{-- ================================================================ --}}

<div
    class="modal fade"
    id="failInspectionModal"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog modal-lg">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title text-danger">
                    Fail Inspection
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                ></button>

            </div>


            <form
                method="POST"
                action="{{ route(
                    'admin.projects.construction.inspections.fail',
                    [
                        'project' =>
                            $project,

                        'inspection' =>
                            $inspection,
                    ]
                ) }}"
            >

                @csrf


                <div class="modal-body">

                    <div class="alert alert-danger">

                        A failed inspection must have a
                        non-conformance and corrective action.

                    </div>


                    <div class="mb-3">

                        <label class="form-label">
                            Non-Conformance
                            <span class="text-danger">*</span>
                        </label>

                        <textarea
                            name="non_conformance"
                            rows="4"
                            class="form-control"
                            required
                            placeholder="Describe the non-conformance..."
                        >{{ $inspection->non_conformance }}</textarea>

                    </div>


                    <div class="mb-3">

                        <label class="form-label">
                            Corrective Action
                            <span class="text-danger">*</span>
                        </label>

                        <textarea
                            name="corrective_action"
                            rows="4"
                            class="form-control"
                            required
                            placeholder="Describe the corrective action required..."
                        >{{ $inspection->corrective_action }}</textarea>

                    </div>


                    <div class="mb-3">

                        <label class="form-label">
                            Corrective Action Due Date
                        </label>

                        <input
                            type="date"
                            name="corrective_action_due_date"
                            class="form-control"
                        >

                    </div>


                    <div class="mb-0">

                        <label class="form-label">
                            Remarks
                        </label>

                        <textarea
                            name="remarks"
                            rows="3"
                            class="form-control"
                        >{{ $inspection->remarks }}</textarea>

                    </div>

                </div>


                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-light"
                        data-bs-dismiss="modal"
                    >
                        Cancel
                    </button>

                    <button
                        type="submit"
                        class="btn btn-danger"
                    >
                        Fail Inspection
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

{{-- ================================================================ --}}
{{-- CORRECTIVE ACTION MODAL --}}
{{-- ================================================================ --}}

<div
    class="modal fade"
    id="correctiveActionModal"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">
                    Complete Corrective Action
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                ></button>

            </div>


            <form
                method="POST"
                action="{{ route(
                    'admin.projects.construction.inspections.corrective-action',
                    [
                        'project' =>
                            $project,

                        'inspection' =>
                            $inspection,
                    ]
                ) }}"
            >

                @csrf


                <div class="modal-body">

                    <div class="mb-3">

                        <label class="form-label">
                            Corrective Action Completed Date
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="date"
                            name="corrective_action_date"
                            class="form-control"
                            value="{{ now()->format('Y-m-d') }}"
                            required
                        >

                    </div>


                    <div class="mb-3">

                        <label class="form-label">
                            Re-inspection Date
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="date"
                            name="reinspection_date"
                            class="form-control"
                            required
                        >

                    </div>


                    <div>

                        <label class="form-label">
                            Remarks
                        </label>

                        <textarea
                            name="remarks"
                            rows="4"
                            class="form-control"
                        ></textarea>

                    </div>

                </div>


                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-light"
                        data-bs-dismiss="modal"
                    >
                        Cancel
                    </button>

                    <button
                        type="submit"
                        class="btn btn-warning"
                    >
                        Record Corrective Action
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

{{-- ================================================================ --}}
{{-- RE-INSPECTION MODAL --}}
{{-- ================================================================ --}}

<div
    class="modal fade"
    id="reinspectionModal"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog modal-lg">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">
                    Conduct Re-inspection
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                ></button>

            </div>


            <form
                method="POST"
                action="{{ route(
                    'admin.projects.construction.inspections.reinspection',
                    [
                        'project' =>
                            $project,

                        'inspection' =>
                            $inspection,
                    ]
                ) }}"
            >

                @csrf


                <div class="modal-body">

                    <div class="mb-3">

                        <label class="form-label">
                            Re-inspection Date
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="date"
                            name="reinspection_date"
                            class="form-control"
                            value="{{ now()->format('Y-m-d') }}"
                            required
                        >

                    </div>


                    <div class="mb-3">

                        <label class="form-label">
                            Re-inspection Result
                            <span class="text-danger">*</span>
                        </label>

                        <select
                            name="reinspection_result"
                            class="form-select"
                            required
                        >

                            <option value="">
                                Select Result
                            </option>

                            <option value="Passed">
                                Passed
                            </option>

                            <option value="Failed">
                                Failed
                            </option>

                        </select>

                    </div>


                    <div class="mb-3">

                        <label class="form-label">
                            Observations
                        </label>

                        <textarea
                            name="observations"
                            rows="4"
                            class="form-control"
                        ></textarea>

                    </div>


                    <div class="mb-3">

                        <label class="form-label">
                            Non-Conformance
                        </label>

                        <textarea
                            name="non_conformance"
                            rows="4"
                            class="form-control"
                        ></textarea>

                    </div>


                    <div class="mb-3">

                        <label class="form-label">
                            Corrective Action
                        </label>

                        <textarea
                            name="corrective_action"
                            rows="4"
                            class="form-control"
                        ></textarea>

                    </div>


                    <div>

                        <label class="form-label">
                            Remarks
                        </label>

                        <textarea
                            name="remarks"
                            rows="3"
                            class="form-control"
                        ></textarea>

                    </div>

                </div>


                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-light"
                        data-bs-dismiss="modal"
                    >
                        Cancel
                    </button>

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        Save Re-inspection
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection