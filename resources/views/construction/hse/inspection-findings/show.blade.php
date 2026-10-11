@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- =========================================================
        HEADER
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">
                Inspection:
                <strong>
                    {{ $inspection->inspection_number }}
                </strong>
            </div>

            <h3 class="mb-1">
                {{ $finding->finding_number }}
            </h3>

            <div class="text-muted">

                {{ $project->project_code ?? '—' }}

                -

                {{ $project->project_name
                    ?? $project->name
                    ?? 'Project'
                }}

            </div>

        </div>


        <div class="d-flex gap-2">

            {{-- Edit Finding --}}

            <a
                href="{{ route(
                    'admin.projects.construction.hse.inspections.findings.edit',
                    [
                        'project' => $project,
                        'inspection' => $inspection,
                        'finding' => $finding,
                    ]
                ) }}"
                class="btn btn-outline-primary"
            >
                <i class="bi bi-pencil me-1"></i>
                Edit
            </a>


            {{-- Back to Findings --}}

            <a
                href="{{ route(
                    'admin.projects.construction.hse.inspections.findings.index',
                    [
                        'project' => $project,
                        'inspection' => $inspection,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                Findings
            </a>


            {{-- Back to Inspection --}}

            <a
                href="{{ route(
                    'admin.projects.construction.hse.inspections.show',
                    [
                        'project' => $project,
                        'inspection' => $inspection,
                    ]
                ) }}"
                class="btn btn-secondary"
            >
                Inspection
            </a>

        </div>

    </div>


    {{-- =========================================================
        SUCCESS MESSAGE
    ========================================================== --}}

    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


    {{-- =========================================================
        ERROR MESSAGE
    ========================================================== --}}

    @if(session('error'))

        <div class="alert alert-danger">
            {{ session('error') }}
        </div>

    @endif


    {{-- =========================================================
        STATUS CLASSES
    ========================================================== --}}

    @php

        $severityClass =
            match($finding->severity) {

                'Critical' =>
                    'bg-danger',

                'High' =>
                    'bg-warning text-dark',

                'Medium' =>
                    'bg-info text-dark',

                'Low' =>
                    'bg-secondary',

                default =>
                    'bg-secondary',

            };


        $statusClass =
            match($finding->status) {

                'Open' =>
                    'bg-primary',

                'In Progress' =>
                    'bg-warning text-dark',

                'Action Required' =>
                    'bg-warning text-dark',

                'Resolved' =>
                    'bg-success',

                'Verified' =>
                    'bg-success',

                'Closed' =>
                    'bg-dark',

                default =>
                    'bg-secondary',

            };


        /*
        |--------------------------------------------------------------------------
        | Actions
        |--------------------------------------------------------------------------
        |
        | The controller should normally eager-load this relationship.
        | The fallback keeps the view safe if it was not loaded.
        |
        */

        $actions = $finding->relationLoaded('actions')
            ? $finding->actions
            : collect();

    @endphp


    {{-- =========================================================
        SUMMARY
    ========================================================== --}}

    <div class="card mb-4">

        <div class="card-body">

            <div class="row align-items-center">

                <div class="col-md-8">

                    <div class="text-muted small">
                        Finding
                    </div>

                    <h4 class="mb-2">
                        {{ $finding->finding_title }}
                    </h4>


                    @if($finding->finding_type)

                        <span class="text-muted">
                            {{ $finding->finding_type }}
                        </span>

                    @endif

                </div>


                <div class="col-md-4 text-md-end">

                    <div class="mb-2">

                        <span
                            class="badge {{ $severityClass }} fs-6"
                        >
                            {{ $finding->severity }}
                        </span>

                    </div>


                    <span
                        class="badge {{ $statusClass }} fs-6"
                    >
                        {{ $finding->status }}
                    </span>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        FINDING DETAILS
    ========================================================== --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Finding Details
            </strong>

        </div>


        <div class="card-body">

            <div class="row g-4">


                {{-- Finding Number --}}

                <div class="col-md-4">

                    <div class="text-muted small">
                        Finding Number
                    </div>

                    <strong>
                        {{ $finding->finding_number }}
                    </strong>

                </div>


                {{-- Finding Date --}}

                <div class="col-md-4">

                    <div class="text-muted small">
                        Finding Date
                    </div>

                    <strong>

                        {{ $finding->finding_date
                            ? $finding->finding_date->format('d-m-Y')
                            : '—'
                        }}

                    </strong>

                </div>


                {{-- Due Date --}}

                <div class="col-md-4">

                    <div class="text-muted small">
                        Due Date
                    </div>

                    <strong>

                        {{ $finding->due_date
                            ? $finding->due_date->format('d-m-Y')
                            : '—'
                        }}


                        @if($finding->isOverdue())

                            <span class="badge bg-danger ms-1">
                                Overdue
                            </span>

                        @endif

                    </strong>

                </div>


                {{-- Description --}}

                <div class="col-12">

                    <div class="text-muted small mb-1">
                        Description
                    </div>

                    <div style="white-space:pre-line;">
                        {{ $finding->finding_description }}
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        RELATED CHECKLIST ITEM
    ========================================================== --}}

    @if($finding->inspectionItem)

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Related Checklist Item
                </strong>

            </div>


            <div class="card-body">

                <div class="text-muted small">

                    {{ $finding->inspectionItem->item_number }}

                </div>


                <div class="mt-1">

                    {{ $finding->inspectionItem->checklist_question }}

                </div>


                @if($finding->inspectionItem->response)

                    <div class="mt-3">

                        Checklist Response:

                        <span class="badge bg-secondary">

                            {{ $finding->inspectionItem->response }}

                        </span>

                    </div>

                @endif

            </div>

        </div>

    @endif


    {{-- =========================================================
        ACTION SUMMARY
    ========================================================== --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Finding Actions
            </strong>

        </div>


        <div class="card-body">

            <div class="row g-4">


                {{-- Immediate Action --}}

                <div class="col-md-6">

                    <div class="text-muted small">
                        Immediate Action
                    </div>

                    <div
                        class="mt-1"
                        style="white-space:pre-line;"
                    >
                        {{ $finding->immediate_action ?? '—' }}
                    </div>

                </div>


                {{-- Recommended Action --}}

                <div class="col-md-6">

                    <div class="text-muted small">
                        Recommended Action
                    </div>

                    <div
                        class="mt-1"
                        style="white-space:pre-line;"
                    >
                        {{ $finding->recommended_action ?? '—' }}
                    </div>

                </div>


                {{-- Responsible Person --}}

                <div class="col-md-6">

                    <div class="text-muted small">
                        Responsible Person
                    </div>

                    <strong>

                        {{ $finding->responsible_name
                            ?? $finding->responsibleUser?->name
                            ?? '—'
                        }}

                    </strong>

                </div>


                {{-- Corrective Action Summary --}}

                <div class="col-md-6">

                    <div class="text-muted small">
                        Corrective Actions
                    </div>

                    <div class="mt-1">

                        @if($actions->isNotEmpty())

                            <span class="badge bg-warning text-dark">

                                {{ $actions->count() }}

                                Action(s)

                            </span>

                        @else

                            <span class="text-muted">
                                No corrective action created yet.
                            </span>

                        @endif

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        CORRECTIVE ACTIONS
    ========================================================== --}}

    <div class="card mb-4">

        <div class="card-header d-flex justify-content-between align-items-center">

            <div>

                <strong>

                    <i class="bi bi-check2-square me-1"></i>

                    Corrective Actions

                </strong>


                <span class="badge bg-primary ms-2">

                    {{ $actions->count() }}

                </span>

            </div>


            <a
                href="{{ route(
                    'admin.projects.construction.hse.inspections.findings.actions.create',
                    [
                        'project' => $project,
                        'inspection' => $inspection,
                        'finding' => $finding,
                    ]
                ) }}"
                class="btn btn-sm btn-primary"
            >

                <i class="bi bi-plus-lg me-1"></i>

                Add Action

            </a>

        </div>


        <div class="card-body p-0">


            @if($actions->isNotEmpty())

                <div class="table-responsive">

                    <table
                        class="table table-hover align-middle mb-0"
                    >

                        <thead class="table-light">

                        <tr>

                            <th>
                                #
                            </th>

                            <th>
                                Action
                            </th>

                            <th>
                                Responsible
                            </th>

                            <th>
                                Due Date
                            </th>

                            <th>
                                Status
                            </th>

                            <th>
                                Verification
                            </th>

                            <th class="text-end">
                                Action
                            </th>

                        </tr>

                        </thead>


                        <tbody>

                        @foreach($actions as $action)

                            @php

                                $actionStatusClass =
                                    match($action->status) {

                                        'Open' =>
                                            'bg-primary',

                                        'In Progress' =>
                                            'bg-warning text-dark',

                                        'Completed' =>
                                            'bg-success',

                                        'Closed' =>
                                            'bg-dark',

                                        default =>
                                            'bg-secondary',

                                    };


                                $actionVerificationClass =
                                    match(
                                        $action->verification_status
                                    ) {

                                        'Verified' =>
                                            'bg-success',

                                        'Rejected' =>
                                            'bg-danger',

                                        default =>
                                            'bg-warning text-dark',

                                    };

                            @endphp


                            <tr>

                                {{-- Number --}}

                                <td>

                                    {{ $loop->iteration }}

                                </td>


                                {{-- Action --}}

                                <td>

                                    <a
                                        href="{{ route(
                                            'admin.projects.construction.hse.inspections.findings.actions.show',
                                            [
                                                'project' => $project,
                                                'inspection' => $inspection,
                                                'finding' => $finding,
                                                'action' => $action,
                                            ]
                                        ) }}"
                                        class="fw-semibold text-decoration-none"
                                    >

                                        {{ $action->action_number }}

                                    </a>


                                    <div class="small text-muted">

                                        {{ \Illuminate\Support\Str::limit(
                                            $action->action_description,
                                            100
                                        ) }}

                                    </div>

                                </td>


                                {{-- Responsible --}}

                                <td>

                                    {{ $action->responsible_name
                                        ?? $action->responsibleUser?->name
                                        ?? '—'
                                    }}

                                </td>


                                {{-- Due Date --}}

                                <td>

                                    {{ $action->due_date
                                        ? $action->due_date->format('d-m-Y')
                                        : '—'
                                    }}


                                    @if($action->isOverdue())

                                        <span class="badge bg-danger ms-1">
                                            Overdue
                                        </span>

                                    @endif

                                </td>


                                {{-- Status --}}

                                <td>

                                    <span
                                        class="badge {{ $actionStatusClass }}"
                                    >
                                        {{ $action->status }}
                                    </span>

                                </td>


                                {{-- Verification --}}

                                <td>

                                    <span
                                        class="badge {{ $actionVerificationClass }}"
                                    >
                                        {{ $action->verification_status
                                            ?? 'Pending'
                                        }}
                                    </span>

                                </td>


                                {{-- Action Button --}}

                                <td class="text-end">

                                    <a
                                        href="{{ route(
                                            'admin.projects.construction.hse.inspections.findings.actions.show',
                                            [
                                                'project' => $project,
                                                'inspection' => $inspection,
                                                'finding' => $finding,
                                                'action' => $action,
                                            ]
                                        ) }}"
                                        class="btn btn-sm btn-outline-primary"
                                    >

                                        View

                                    </a>

                                </td>

                            </tr>

                        @endforeach

                        </tbody>

                    </table>

                </div>


            @else

                <div class="text-center py-5">

                    <i
                        class="bi bi-check2-square"
                        style="font-size:40px;"
                    ></i>


                    <h6 class="mt-3">
                        No Corrective Actions
                    </h6>


                    <p class="text-muted mb-3">

                        No corrective action has been created
                        for this finding yet.

                    </p>


                    <a
                        href="{{ route(
                            'admin.projects.construction.hse.inspections.findings.actions.create',
                            [
                                'project' => $project,
                                'inspection' => $inspection,
                                'finding' => $finding,
                            ]
                        ) }}"
                        class="btn btn-primary"
                    >

                        <i class="bi bi-plus-lg me-1"></i>

                        Add First Action

                    </a>

                </div>

            @endif

        </div>

    </div>


    {{-- =========================================================
        VERIFICATION
    ========================================================== --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Verification
            </strong>

        </div>


        <div class="card-body">

            <div class="row g-4">


                {{-- Verification Status --}}

                <div class="col-md-4">

                    <div class="text-muted small">
                        Verification Status
                    </div>

                    <strong>
                        {{ $finding->verification_status ?? 'Pending' }}
                    </strong>

                </div>


                {{-- Verified Date --}}

                <div class="col-md-4">

                    <div class="text-muted small">
                        Verified Date
                    </div>

                    <strong>

                        {{ $finding->verified_date
                            ? $finding->verified_date->format('d-m-Y')
                            : '—'
                        }}

                    </strong>

                </div>


                {{-- Verified By --}}

                <div class="col-md-4">

                    <div class="text-muted small">
                        Verified By
                    </div>

                    <strong>

                        {{ $finding->verifiedBy?->name ?? '—' }}

                    </strong>

                </div>


                {{-- Verification Remarks --}}

                <div class="col-12">

                    <div class="text-muted small">
                        Verification Remarks
                    </div>

                    <div
                        class="mt-1"
                        style="white-space:pre-line;"
                    >

                        {{ $finding->verification_remarks ?? '—' }}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        REMARKS
    ========================================================== --}}

    @if($finding->remarks)

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Remarks
                </strong>

            </div>


            <div
                class="card-body"
                style="white-space:pre-line;"
            >

                {{ $finding->remarks }}

            </div>

        </div>

    @endif


    {{-- =========================================================
        RECORD INFORMATION
    ========================================================== --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Record Information
            </strong>

        </div>


        <div class="card-body">

            <div class="row g-4">


                {{-- Created By --}}

                <div class="col-md-3">

                    <div class="text-muted small">
                        Created By
                    </div>

                    <strong>

                        {{ $finding->creator?->name ?? '—' }}

                    </strong>

                </div>


                {{-- Created At --}}

                <div class="col-md-3">

                    <div class="text-muted small">
                        Created At
                    </div>

                    <strong>

                        {{ $finding->created_at
                            ? $finding->created_at->format('d-m-Y H:i')
                            : '—'
                        }}

                    </strong>

                </div>


                {{-- Updated By --}}

                <div class="col-md-3">

                    <div class="text-muted small">
                        Updated By
                    </div>

                    <strong>

                        {{ $finding->updater?->name ?? '—' }}

                    </strong>

                </div>


                {{-- Updated At --}}

                <div class="col-md-3">

                    <div class="text-muted small">
                        Updated At
                    </div>

                    <strong>

                        {{ $finding->updated_at
                            ? $finding->updated_at->format('d-m-Y H:i')
                            : '—'
                        }}

                    </strong>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        DELETE
    ========================================================== --}}

    @if($finding->status !== 'Closed')

        <div class="d-flex justify-content-end">

            <form
                method="POST"
                action="{{ route(
                    'admin.projects.construction.hse.inspections.findings.destroy',
                    [
                        'project' => $project,
                        'inspection' => $inspection,
                        'finding' => $finding,
                    ]
                ) }}"
                onsubmit="return confirm(
                    'Are you sure you want to delete this finding?'
                );"
            >

                @csrf

                @method('DELETE')


                <button
                    type="submit"
                    class="btn btn-outline-danger"
                >

                    <i class="bi bi-trash me-1"></i>

                    Delete Finding

                </button>

            </form>

        </div>

    @endif

</div>

@endsection