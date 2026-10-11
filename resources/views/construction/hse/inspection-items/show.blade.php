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

                Checklist Item

                <span class="text-muted">
                    {{ $item->item_number }}
                </span>

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


            <a
                href="{{ route(
                    'admin.projects.construction.hse.inspections.items.edit',
                    [
                        'project' => $project,
                        'inspection' => $inspection,
                        'item' => $item,
                    ]
                ) }}"
                class="btn btn-outline-primary"
            >

                <i class="bi bi-pencil me-1"></i>

                Edit

            </a>


            <a
                href="{{ route(
                    'admin.projects.construction.hse.inspections.items.index',
                    [
                        'project' => $project,
                        'inspection' => $inspection,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                Checklist
            </a>


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
        MESSAGES
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
        RESPONSE STATUS
    ========================================================== --}}

    @php

        $responseClass =
            match($item->response) {

                'Compliant' =>
                    'bg-success',

                'Non-Compliant' =>
                    'bg-danger',

                'Partially Compliant' =>
                    'bg-warning text-dark',

                'Not Applicable' =>
                    'bg-secondary',

                default =>
                    'bg-light text-dark',

            };


        $severityClass =
            match($item->severity) {

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

    @endphp


    <div class="card mb-4">

        <div class="card-body">

            <div class="row align-items-center">


                <div class="col-md-8">

                    <div class="text-muted small">
                        Checklist Item
                    </div>

                    <h4 class="mb-1">
                        {{ $item->item_number }}
                    </h4>

                    @if($item->checklist_category)

                        <div class="text-muted">
                            {{ $item->checklist_category }}
                        </div>

                    @endif

                </div>


                <div class="col-md-4 text-md-end">


                    <div class="mb-2">

                        <span
                            class="badge {{ $responseClass }} fs-6"
                        >
                            {{ $item->response ?? 'Not Assessed' }}
                        </span>

                    </div>


                    @if($item->severity)

                        <span
                            class="badge {{ $severityClass }}"
                        >
                            Severity: {{ $item->severity }}
                        </span>

                    @endif


                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        QUESTION
    ========================================================== --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Checklist Question
            </strong>

        </div>


        <div class="card-body">

            <div
                class="fs-5"
                style="white-space: pre-line;"
            >
                {{ $item->checklist_question }}
            </div>

        </div>

    </div>


    {{-- =========================================================
        OBSERVATION
    ========================================================== --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Observation / Finding
            </strong>

        </div>


        <div class="card-body">

            @if($item->observation)

                <div style="white-space: pre-line;">
                    {{ $item->observation }}
                </div>

            @else

                <span class="text-muted">
                    No observation recorded.
                </span>

            @endif

        </div>

    </div>


    {{-- =========================================================
        CORRECTIVE ACTION
    ========================================================== --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Corrective Action Requirement
            </strong>

        </div>


        <div class="card-body">

            @if($item->corrective_required)

                <div class="alert alert-warning mb-0">

                    <i class="bi bi-exclamation-triangle me-1"></i>

                    Corrective action is required for this checklist item.

                </div>

            @else

                <div class="alert alert-success mb-0">

                    <i class="bi bi-check-circle me-1"></i>

                    No corrective action is currently required.

                </div>

            @endif

        </div>

    </div>


    {{-- =========================================================
        REMARKS
    ========================================================== --}}

    @if($item->remarks)

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Remarks
                </strong>

            </div>


            <div class="card-body">

                <div style="white-space: pre-line;">
                    {{ $item->remarks }}
                </div>

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


                <div class="col-md-3">

                    <div class="text-muted small">
                        Created By
                    </div>

                    <strong>
                        {{ $item->creator?->name ?? '—' }}
                    </strong>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Created At
                    </div>

                    <strong>

                        {{ $item->created_at
                            ? $item->created_at->format('d-m-Y H:i')
                            : '—'
                        }}

                    </strong>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Updated By
                    </div>

                    <strong>
                        {{ $item->updater?->name ?? '—' }}
                    </strong>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Updated At
                    </div>

                    <strong>

                        {{ $item->updated_at
                            ? $item->updated_at->format('d-m-Y H:i')
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

    <div class="d-flex justify-content-end">

        <form
            method="POST"
            action="{{ route(
                'admin.projects.construction.hse.inspections.items.destroy',
                [
                    'project' => $project,
                    'inspection' => $inspection,
                    'item' => $item,
                ]
            ) }}"
            onsubmit="return confirm(
                'Are you sure you want to delete this checklist item?'
            );"
        >

            @csrf

            @method('DELETE')


            <button
                type="submit"
                class="btn btn-outline-danger"
            >

                <i class="bi bi-trash me-1"></i>

                Delete Checklist Item

            </button>

        </form>

    </div>

</div>

@endsection