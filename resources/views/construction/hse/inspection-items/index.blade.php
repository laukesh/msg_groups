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
                Inspection Checklist
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
                    'admin.projects.construction.hse.inspections.show',
                    [
                        'project' => $project,
                        'inspection' => $inspection,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >

                <i class="bi bi-arrow-left me-1"></i>

                Inspection

            </a>


            <a
                href="{{ route(
                    'admin.projects.construction.hse.inspections.items.create',
                    [
                        'project' => $project,
                        'inspection' => $inspection,
                    ]
                ) }}"
                class="btn btn-primary"
            >

                <i class="bi bi-plus-lg me-1"></i>

                Add Checklist Item

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
        SUMMARY
    ========================================================== --}}

    @php

        $totalItems = $items->count();

        $compliant = $items
            ->where('response', 'Compliant')
            ->count();

        $nonCompliant = $items
            ->where('response', 'Non-Compliant')
            ->count();

        $partial = $items
            ->where('response', 'Partially Compliant')
            ->count();

        $notApplicable = $items
            ->where('response', 'Not Applicable')
            ->count();

        $correctiveRequired = $items
            ->where('corrective_required', true)
            ->count();

    @endphp


    <div class="row g-3 mb-4">


        <div class="col-md">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Total Items
                    </div>

                    <h4 class="mb-0">
                        {{ $totalItems }}
                    </h4>

                </div>

            </div>

        </div>


        <div class="col-md">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Compliant
                    </div>

                    <h4 class="mb-0 text-success">
                        {{ $compliant }}
                    </h4>

                </div>

            </div>

        </div>


        <div class="col-md">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Non-Compliant
                    </div>

                    <h4 class="mb-0 text-danger">
                        {{ $nonCompliant }}
                    </h4>

                </div>

            </div>

        </div>


        <div class="col-md">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Partially Compliant
                    </div>

                    <h4 class="mb-0">
                        {{ $partial }}
                    </h4>

                </div>

            </div>

        </div>


        <div class="col-md">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Corrective Required
                    </div>

                    <h4 class="mb-0 text-warning">
                        {{ $correctiveRequired }}
                    </h4>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        REGISTER
    ========================================================== --}}

    <div class="card">

        <div class="card-header">

            <strong>
                Checklist Items
            </strong>

            <span class="badge bg-primary ms-2">
                {{ $totalItems }}
            </span>

        </div>


        <div class="card-body p-0">

            @if($items->isNotEmpty())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                        <tr>

                            <th>
                                #
                            </th>

                            <th>
                                Category
                            </th>

                            <th>
                                Checklist Question
                            </th>

                            <th>
                                Response
                            </th>

                            <th>
                                Severity
                            </th>

                            <th>
                                Corrective Action
                            </th>

                            <th class="text-end">
                                Action
                            </th>

                        </tr>

                        </thead>


                        <tbody>

                        @foreach($items as $item)

                            <tr>


                                <td>
                                    {{ $item->item_number }}
                                </td>


                                <td>
                                    {{ $item->checklist_category ?? '—' }}
                                </td>


                                <td style="min-width: 280px;">

                                    <a
                                        href="{{ route(
                                            'admin.projects.construction.hse.inspections.items.show',
                                            [
                                                'project' => $project,
                                                'inspection' => $inspection,
                                                'item' => $item,
                                            ]
                                        ) }}"
                                        class="fw-semibold text-decoration-none"
                                    >

                                        {{ $item->checklist_question }}

                                    </a>

                                </td>


                                <td>

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

                                    @endphp


                                    <span
                                        class="badge {{ $responseClass }}"
                                    >
                                        {{ $item->response ?? 'Not Assessed' }}
                                    </span>

                                </td>


                                <td>

                                    @if($item->severity)

                                        @php

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


                                        <span
                                            class="badge {{ $severityClass }}"
                                        >
                                            {{ $item->severity }}
                                        </span>

                                    @else

                                        —

                                    @endif

                                </td>


                                <td>

                                    @if($item->corrective_required)

                                        <span class="badge bg-danger">
                                            Required
                                        </span>

                                    @else

                                        <span class="badge bg-success">
                                            No
                                        </span>

                                    @endif

                                </td>


                                <td class="text-end">

                                    <a
                                        href="{{ route(
                                            'admin.projects.construction.hse.inspections.items.show',
                                            [
                                                'project' => $project,
                                                'inspection' => $inspection,
                                                'item' => $item,
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
                        class="bi bi-list-check"
                        style="font-size: 42px;"
                    ></i>


                    <h6 class="mt-3">
                        No Checklist Items
                    </h6>


                    <p class="text-muted">
                        No checklist items have been added
                        to this inspection yet.
                    </p>


                    <a
                        href="{{ route(
                            'admin.projects.construction.hse.inspections.items.create',
                            [
                                'project' => $project,
                                'inspection' => $inspection,
                            ]
                        ) }}"
                        class="btn btn-primary"
                    >

                        <i class="bi bi-plus-lg me-1"></i>

                        Add First Checklist Item

                    </a>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection