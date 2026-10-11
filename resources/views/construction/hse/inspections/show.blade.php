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

                {{ $inspection->inspection_title
                    ?? 'HSE Inspection'
                }}

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


            @if($inspection->status !== 'Closed')

                <a
                    href="{{ route(
                        'admin.projects.construction.hse.inspections.edit',
                        [
                            'project' => $project,
                            'inspection' => $inspection,
                        ]
                    ) }}"
                    class="btn btn-outline-primary"
                >

                    <i class="bi bi-pencil me-1"></i>

                    Edit

                </a>

            @endif


            <a
                href="{{ route(
                    'admin.projects.construction.hse.inspections.index',
                    [
                        'project' => $project,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                Inspections
            </a>


            <a
                href="{{ route(
                    'admin.projects.construction.hse.index',
                    [
                        'project' => $project,
                    ]
                ) }}"
                class="btn btn-secondary"
            >
                HSE
            </a>

        </div>

    </div>


    {{-- =========================================================
        SUCCESS / ERROR
    ========================================================== --}}

    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


    @if(session('error'))

        <div class="alert alert-danger">
            {{ session('error') }}
        </div>

    @endif


    {{-- =========================================================
        STATUS
    ========================================================== --}}

    <div class="card mb-4">

        <div class="card-body">

            <div class="row align-items-center">


                <div class="col-md-8">

                    <div class="text-muted small">
                        Inspection Number
                    </div>

                    <h4 class="mb-1">
                        {{ $inspection->inspection_number }}
                    </h4>

                    <div class="text-muted">

                        {{ $inspection->inspection_type }}

                        @if($inspection->inspection_date)

                            <span class="mx-1">
                                •
                            </span>

                            {{ $inspection->inspection_date->format('d-m-Y') }}

                        @endif

                    </div>

                </div>


                <div class="col-md-4 text-md-end">

                    <div class="text-muted small mb-1">
                        Current Status
                    </div>


                    @php

                        $statusClass =
                            match($inspection->status) {

                                'Planned' =>
                                    'bg-secondary',

                                'In Progress' =>
                                    'bg-warning text-dark',

                                'Completed' =>
                                    'bg-primary',

                                'Findings Raised' =>
                                    'bg-danger',

                                'Verified' =>
                                    'bg-success',

                                'Closed' =>
                                    'bg-dark',

                                default =>
                                    'bg-secondary',

                            };

                    @endphp


                    <span
                        class="badge {{ $statusClass }} fs-6"
                    >
                        {{ $inspection->status }}
                    </span>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        INSPECTION INFORMATION
    ========================================================== --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Inspection Information
            </strong>

        </div>


        <div class="card-body">

            <div class="row g-4">


                <div class="col-md-4">

                    <div class="text-muted small">
                        Inspection Type
                    </div>

                    <strong>
                        {{ $inspection->inspection_type }}
                    </strong>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Inspection Date
                    </div>

                    <strong>

                        {{ $inspection->inspection_date
                            ? $inspection->inspection_date->format('d-m-Y')
                            : '—'
                        }}

                    </strong>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Location
                    </div>

                    <strong>
                        {{ $inspection->location ?? '—' }}
                    </strong>

                </div>


                <div class="col-md-6">

                    <div class="text-muted small">
                        Inspector
                    </div>

                    <strong>

                        {{ $inspection->inspector_name
                            ?? $inspection->inspector?->name
                            ?? '—'
                        }}

                    </strong>

                </div>


                <div class="col-md-6">

                    <div class="text-muted small">
                        Procurement Contract
                    </div>

                    <strong>

                        {{ $inspection->procurementContract?->contract_number
                            ?? $inspection->procurementContract?->contract_no
                            ?? (
                                $inspection->procurement_contract_id
                                    ? 'Contract #' . $inspection->procurement_contract_id
                                    : '—'
                            )
                        }}

                    </strong>

                </div>


                <div class="col-12">

                    <div class="text-muted small mb-2">
                        Inspection Scope
                    </div>


                    @if($inspection->scope)

                        <div style="white-space: pre-line;">
                            {{ $inspection->scope }}
                        </div>

                    @else

                        <span class="text-muted">
                            No inspection scope recorded.
                        </span>

                    @endif

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        FINDINGS SUMMARY
    ========================================================== --}}

    @if($inspection->findings_summary)

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Findings Summary
                </strong>

            </div>


            <div class="card-body">

                <div style="white-space: pre-line;">
                    {{ $inspection->findings_summary }}
                </div>

            </div>

        </div>

    @endif


    {{-- =========================================================
        FUTURE CHILD MODULES
    ========================================================== --}}

    <div class="row g-4 mb-4">


        {{-- Checklist --}}

        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <h6>

                        <i class="bi bi-list-check me-1"></i>

                        Checklist

                    </h6>


                    <p class="text-muted small mb-3">

                        Inspection checklist and item-wise assessment.

                    </p>


                    <a
                        href="{{ route(
                            'admin.projects.construction.hse.inspections.items.index',
                            [
                                'project' => $project,
                                'inspection' => $inspection,
                            ]
                        ) }}"
                        class="btn btn-outline-primary btn-sm"
                    >

                        Open Checklist

                    </a>

                </div>

            </div>

        </div>


        {{-- Findings --}}

        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <h6>
                        <i class="bi bi-exclamation-circle me-1"></i>
                        Findings
                    </h6>

                    <p class="text-muted small mb-3">
                        Record inspection findings and observations.
                    </p>

                    <a
                        href="{{ route(
                            'admin.projects.construction.hse.inspections.findings.index',
                            [
                                'project' => $project,
                                'inspection' => $inspection,
                            ]
                        ) }}"
                        class="btn btn-outline-warning btn-sm"
                    >

                        <i class="bi bi-exclamation-diamond me-1"></i>

                        Open Findings

                    </a>

                </div>

            </div>

        </div>


        {{-- Actions --}}

        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <h6>
                        <i class="bi bi-check2-square me-1"></i>
                        Corrective Actions
                    </h6>

                    <p class="text-muted small mb-3">
                        Track corrective actions raised from findings.
                    </p>

                    <a
                href="{{ route(
                    'admin.projects.construction.hse.inspections.actions.index',
                    [
                        'project' => $project,
                        'inspection' => $inspection,
                    ]
                ) }}"
                class="btn btn-outline-warning btn-sm"
            >
                Open Corrective Actions
            </a>

                </div>

            </div>

        </div>


        {{-- Documents --}}

        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <h6>
                        <i class="bi bi-paperclip me-1"></i>
                        Documents
                    </h6>

                    <p class="text-muted small mb-3">
                        Inspection reports and supporting evidence.
                    </p>

                    <a
                        href="{{ route(
                            'admin.projects.construction.hse.inspections.documents.index',
                            [
                                'project' => $project,
                                'inspection' => $inspection,
                            ]
                        ) }}"
                        class="btn btn-outline-primary btn-sm"
                    >
                        <i class="bi bi-folder2-open me-1"></i>
                        Open Documents
                    </a>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        REMARKS
    ========================================================== --}}

    @if($inspection->remarks)

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Remarks
                </strong>

            </div>


            <div class="card-body">

                <div style="white-space: pre-line;">
                    {{ $inspection->remarks }}
                </div>

            </div>

        </div>

    @endif


    {{-- =========================================================
        AUDIT INFORMATION
    ========================================================== --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Record Information
            </strong>

        </div>


        <div class="card-body">

            <div class="row g-4">


                <div class="col-md-3">

                    <div class="text-muted small">
                        Created By
                    </div>

                    <strong>
                        {{ $inspection->creator?->name ?? '—' }}
                    </strong>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Created At
                    </div>

                    <strong>

                        {{ $inspection->created_at
                            ? $inspection->created_at->format('d-m-Y H:i')
                            : '—'
                        }}

                    </strong>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Updated By
                    </div>

                    <strong>
                        {{ $inspection->updater?->name ?? '—' }}
                    </strong>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Updated At
                    </div>

                    <strong>

                        {{ $inspection->updated_at
                            ? $inspection->updated_at->format('d-m-Y H:i')
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

    @if($inspection->status !== 'Closed')

        <div class="d-flex justify-content-end">

            <form
                method="POST"
                action="{{ route(
                    'admin.projects.construction.hse.inspections.destroy',
                    [
                        'project' => $project,
                        'inspection' => $inspection,
                    ]
                ) }}"
                onsubmit="return confirm(
                    'Are you sure you want to delete this inspection?'
                );"
            >

                @csrf

                @method('DELETE')


                <button
                    type="submit"
                    class="btn btn-outline-danger"
                >

                    <i class="bi bi-trash me-1"></i>

                    Delete Inspection

                </button>

            </form>

        </div>

    @endif

</div>

@endsection