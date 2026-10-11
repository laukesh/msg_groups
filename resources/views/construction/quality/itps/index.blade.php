@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ================================================================ --}}
    {{-- HEADER --}}
    {{-- ================================================================ --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                Inspection & Test Plans
            </h4>

            <div class="text-muted">
                Project:
                {{ $project->name ?? $project->project_name ?? 'Project' }}
            </div>

        </div>

        <div class="d-flex gap-2">
            <a
                href="{{ route(
                    'admin.projects.construction.dashboard',
                    [
                        'project' => $project,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                <i class="bi bi-arrow-left me-1"></i>
                Construction Dashboard
            </a>
            <a
                href="{{ route(
                    'admin.projects.construction.quality.itps.create',
                    $project
                ) }}"
                class="btn btn-primary"
            >
                + Create ITP
            </a>
        </div>

    </div>


    {{-- ================================================================ --}}
    {{-- SUCCESS MESSAGE --}}
    {{-- ================================================================ --}}

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


    {{-- ================================================================ --}}
    {{-- ERROR MESSAGE --}}
    {{-- ================================================================ --}}

    @if($errors->any())

        <div class="alert alert-danger alert-dismissible fade show">

            @foreach($errors->all() as $error)

                <div>
                    {{ $error }}
                </div>

            @endforeach

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    {{-- ================================================================ --}}
    {{-- SUMMARY --}}
    {{-- ================================================================ --}}

    <div class="row g-3 mb-4">

        <div class="col-md-2">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Total ITPs
                    </div>

                    <div class="fs-4 fw-bold">
                        {{ $summary['total'] ?? 0 }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-2">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Draft
                    </div>

                    <div class="fs-4 fw-bold text-secondary">
                        {{ $summary['draft'] ?? 0 }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-2">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Submitted
                    </div>

                    <div class="fs-4 fw-bold text-primary">
                        {{ $summary['submitted'] ?? 0 }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-2">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Under Review
                    </div>

                    <div class="fs-4 fw-bold text-warning">
                        {{ $summary['under_review'] ?? 0 }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-2">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Approved
                    </div>

                    <div class="fs-4 fw-bold text-success">
                        {{ $summary['approved'] ?? 0 }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-2">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Rejected
                    </div>

                    <div class="fs-4 fw-bold text-danger">
                        {{ $summary['rejected'] ?? 0 }}
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ================================================================ --}}
    {{-- ITP LIST --}}
    {{-- ================================================================ --}}

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white">

            <div class="d-flex justify-content-between align-items-center">

                <h5 class="mb-0">
                    ITP Register
                </h5>

                <span class="text-muted small">
                    {{ $itps->count() }} record(s)
                </span>

            </div>

        </div>


        <div class="card-body p-0">

            @if($itps->count())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th>
                                    ITP Number
                                </th>

                                <th>
                                    Title
                                </th>

                                <th>
                                    Type
                                </th>

                                <th>
                                    Contract
                                </th>

                                <th>
                                    Work Order
                                </th>

                                <th class="text-center">
                                    Items
                                </th>

                                <th>
                                    Prepared By
                                </th>

                                <th>
                                    Status
                                </th>

                                <th class="text-end">
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($itps as $itp)

                                <tr>

                                    {{-- ITP Number --}}
                                    <td>

                                        <a
                                            href="{{ route(
                                                'admin.projects.construction.quality.itps.show',
                                                [
                                                    'project' =>
                                                        $project,

                                                    'itp' =>
                                                        $itp,
                                                ]
                                            ) }}"
                                            class="fw-semibold text-decoration-none"
                                        >
                                            {{ $itp->itp_number }}
                                        </a>

                                    </td>


                                    {{-- Title --}}
                                    <td>

                                        <div class="fw-semibold">
                                            {{ $itp->title }}
                                        </div>

                                        @if($itp->description)

                                            <div class="text-muted small">

                                                {{ \Illuminate\Support\Str::limit(
                                                    $itp->description,
                                                    80
                                                ) }}

                                            </div>

                                        @endif

                                    </td>


                                    {{-- Type --}}
                                    <td>

                                        {{ $itp->itp_type ?: '—' }}

                                    </td>


                                    {{-- Contract --}}
                                    <td>

                                        @if($itp->contract)

                                            <div class="fw-semibold">

                                                {{ $itp->contract->contract_number }}

                                            </div>

                                            <div class="text-muted small">

                                                {{
                                                    $itp->contract->bidder?->company_name
                                                    ??
                                                    $itp->contract->bidder_name
                                                    ??
                                                    '—'
                                                }}

                                            </div>

                                        @else

                                            <span class="text-muted">
                                                —
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Work Order --}}
                                    <td>

                                        @if($itp->workOrder)

                                            <div class="fw-semibold">

                                                {{ $itp->workOrder->work_order_number }}

                                            </div>

                                            <div class="text-muted small">

                                                {{ $itp->workOrder->work_order_title }}

                                            </div>

                                        @else

                                            <span class="text-muted">
                                                —
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Items --}}
                                    <td class="text-center">

                                        <span class="badge bg-light text-dark">

                                            {{ $itp->items->count() }}

                                        </span>

                                    </td>


                                    {{-- Prepared By --}}
                                    <td>

                                        {{ $itp->preparer?->name ?? '—' }}

                                    </td>


                                    {{-- Status --}}
                                    <td>

                                        @switch($itp->status)

                                            @case('Draft')

                                                <span class="badge bg-secondary">
                                                    Draft
                                                </span>

                                                @break


                                            @case('Submitted')

                                                <span class="badge bg-primary">
                                                    Submitted
                                                </span>

                                                @break


                                            @case('Under Review')

                                                <span class="badge bg-warning text-dark">
                                                    Under Review
                                                </span>

                                                @break


                                            @case('Approved')

                                                <span class="badge bg-success">
                                                    Approved
                                                </span>

                                                @break


                                            @case('Rejected')

                                                <span class="badge bg-danger">
                                                    Rejected
                                                </span>

                                                @break


                                            @default

                                                <span class="badge bg-light text-dark">
                                                    {{ $itp->status }}
                                                </span>

                                        @endswitch

                                    </td>


                                    {{-- Actions --}}
                                    <td class="text-end">

                                        <div class="dropdown">

                                            <button
                                                class="btn btn-sm btn-outline-secondary dropdown-toggle"
                                                type="button"
                                                data-bs-toggle="dropdown"
                                                aria-expanded="false"
                                            >
                                                Actions
                                            </button>


                                            <ul class="dropdown-menu dropdown-menu-end">

                                                <li>

                                                    <a
                                                        class="dropdown-item"
                                                        href="{{ route(
                                                            'admin.projects.construction.quality.itps.show',
                                                            [
                                                                'project' =>
                                                                    $project,

                                                                'itp' =>
                                                                    $itp,
                                                            ]
                                                        ) }}"
                                                    >
                                                        View ITP
                                                    </a>

                                                </li>


                                                @if(
                                                    in_array(
                                                        $itp->status,
                                                        [
                                                            'Draft',
                                                            'Rejected',
                                                        ],
                                                        true
                                                    )
                                                )

                                                    <li>

                                                        <a
                                                            class="dropdown-item"
                                                            href="{{ route(
                                                                'admin.projects.construction.quality.itps.edit',
                                                                [
                                                                    'project' =>
                                                                        $project,

                                                                    'itp' =>
                                                                        $itp,
                                                                ]
                                                            ) }}"
                                                        >
                                                            Edit
                                                        </a>

                                                    </li>

                                                @endif


                                                @if(
                                                    in_array(
                                                        $itp->status,
                                                        [
                                                            'Draft',
                                                            'Rejected',
                                                        ],
                                                        true
                                                    )
                                                )

                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>


                                                    <li>

                                                        <form
                                                            method="POST"
                                                            action="{{ route(
                                                                'admin.projects.construction.quality.itps.destroy',
                                                                [
                                                                    'project' =>
                                                                        $project,

                                                                    'itp' =>
                                                                        $itp,
                                                                ]
                                                            ) }}"
                                                            onsubmit="return confirm(
                                                                'Are you sure you want to delete this ITP?'
                                                            );"
                                                        >

                                                            @csrf

                                                            @method('DELETE')

                                                            <button
                                                                type="submit"
                                                                class="dropdown-item text-danger"
                                                            >
                                                                Delete
                                                            </button>

                                                        </form>

                                                    </li>

                                                @endif

                                            </ul>

                                        </div>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="text-center py-5">

                    <div class="fs-5 fw-semibold mb-2">
                        No ITPs found
                    </div>

                    <div class="text-muted mb-3">
                        Create an Inspection & Test Plan for this project.
                    </div>

                    <a
                        href="{{ route(
                            'admin.projects.construction.quality.itps.create',
                            $project
                        ) }}"
                        class="btn btn-primary"
                    >
                        + Create First ITP
                    </a>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection