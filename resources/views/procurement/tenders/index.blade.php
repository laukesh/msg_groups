@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- =========================================================
        HEADER
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small mb-1">
                Procurement Management
            </div>

            <h3 class="mb-1">
                Tender Management
            </h3>

            <div class="text-muted">
                {{ $project->project_name }}
                @if($project->project_code)
                    · {{ $project->project_code }}
                @endif
            </div>

        </div>

        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.projects.procurement.tenders.create',
                    $project
                ) }}"
                class="btn btn-primary"
            >
                <i class="ri-add-line me-1"></i>
                New Tender
            </a>

        </div>

    </div>


    {{-- =========================================================
        ALERTS
    ========================================================== --}}

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


    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    {{-- =========================================================
        INTRO
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <div class="row align-items-center">

                <div class="col-lg-8">

                    <h5 class="mb-2">
                        Project Tender Register
                    </h5>

                    <p class="text-muted mb-0">
                        Manage all procurement tenders for this project.
                        Each tender is strictly linked to the project's
                        Procurement Plan and Procurement Package.
                    </p>

                </div>

                <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">

                    <div class="small text-muted">
                        Project
                    </div>

                    <div class="fw-semibold">
                        {{ $project->project_name }}
                    </div>

                    @if($project->project_code)

                        <div class="small text-muted">
                            {{ $project->project_code }}
                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        KPI
    ========================================================== --}}

    <div class="row g-3 mb-4">

        <div class="col-md-2">

            <div class="card h-100 shadow-sm border-0">

                <div class="card-body">

                    <div class="text-muted small">
                        Total
                    </div>

                    <div class="fs-4 fw-bold">
                        {{ $totalTenders }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-2">

            <div class="card h-100 shadow-sm border-0">

                <div class="card-body">

                    <div class="text-muted small">
                        Draft
                    </div>

                    <div class="fs-4 fw-bold text-secondary">
                        {{ $draftTenders }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-2">

            <div class="card h-100 shadow-sm border-0">

                <div class="card-body">

                    <div class="text-muted small">
                        Published
                    </div>

                    <div class="fs-4 fw-bold text-primary">
                        {{ $publishedTenders }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-2">

            <div class="card h-100 shadow-sm border-0">

                <div class="card-body">

                    <div class="text-muted small">
                        Open
                    </div>

                    <div class="fs-4 fw-bold text-success">
                        {{ $openTenders }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-2">

            <div class="card h-100 shadow-sm border-0">

                <div class="card-body">

                    <div class="text-muted small">
                        Evaluation
                    </div>

                    <div class="fs-4 fw-bold text-warning">
                        {{ $evaluationTenders }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-2">

            <div class="card h-100 shadow-sm border-0">

                <div class="card-body">

                    <div class="text-muted small">
                        Awarded
                    </div>

                    <div class="fs-4 fw-bold text-success">
                        {{ $awardedTenders }}
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        FILTERS
    ========================================================== --}}

    <div class="card shadow-sm border-0 mb-4">

        <div class="card-header bg-white">

            <strong>
                Filter Tenders
            </strong>

        </div>

        <div class="card-body">

            <form
                method="GET"
                action="{{ route(
                    'admin.projects.procurement.tenders.index',
                    $project
                ) }}"
            >

                <div class="row g-3">

                    {{-- Search --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            Search
                        </label>

                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            placeholder="Tender number or title"
                            value="{{ request('search') }}"
                        >

                    </div>


                    {{-- Package --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            Procurement Package
                        </label>

                        <select
                            name="procurement_package_id"
                            class="form-select"
                        >

                            <option value="">
                                All Packages
                            </option>

                            @foreach($packages as $package)

                                <option
                                    value="{{ $package->id }}"
                                    @selected(
                                        request(
                                            'procurement_package_id'
                                        ) == $package->id
                                    )
                                >

                                    {{ $package->package_number }}

                                    @if($package->package_title)
                                        -
                                        {{ $package->package_title }}
                                    @endif

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Status --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            Status
                        </label>

                        <select
                            name="status"
                            class="form-select"
                        >

                            <option value="">
                                All Statuses
                            </option>

                            @foreach([
                                'Draft',
                                'Published',
                                'Open',
                                'Closed',
                                'Under Evaluation',
                                'Awarded',
                                'Cancelled'
                            ] as $status)

                                <option
                                    value="{{ $status }}"
                                    @selected(
                                        request('status') === $status
                                    )
                                >
                                    {{ $status }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-12 d-flex gap-2">

                        <button
                            type="submit"
                            class="btn btn-primary"
                        >
                            Apply Filters
                        </button>

                        <a
                            href="{{ route(
                                'admin.projects.procurement.tenders.index',
                                $project
                            ) }}"
                            class="btn btn-outline-secondary"
                        >
                            Reset
                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- =========================================================
        TENDER TABLE
    ========================================================== --}}

    <div class="card shadow-sm border-0">

        <div class="card-header bg-white">

            <strong>
                Tenders
            </strong>

            <span class="text-muted small ms-2">
                {{ $tenders->total() }} record(s)
            </span>

        </div>


        <div class="card-body p-0">

            @if($tenders->count())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th>#</th>

                                <th>
                                    Tender No.
                                </th>

                                <th>
                                    Tender Title
                                </th>

                                <th>
                                    Package
                                </th>

                                <th>
                                    Estimated Value
                                </th>

                                <th>
                                    Submission Deadline
                                </th>

                                <th>
                                    Status
                                </th>

                                <th class="text-end">
                                    Actions
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($tenders as $tender)

                                @php

                                    $statusClass = match(
                                        $tender->status
                                    ) {

                                        'Published'
                                            => 'bg-primary',

                                        'Open'
                                            => 'bg-success',

                                        'Under Evaluation'
                                            => 'bg-warning text-dark',

                                        'Awarded'
                                            => 'bg-info',

                                        'Cancelled'
                                            => 'bg-danger',

                                        'Closed'
                                            => 'bg-secondary',

                                        default
                                            => 'bg-secondary',
                                    };

                                @endphp


                                <tr>

                                    <td>
                                        {{
                                            $tenders->firstItem()
                                            + $loop->index
                                        }}
                                    </td>


                                    <td>

                                        <a
                                            href="{{ route(
                                                'admin.projects.procurement.tenders.show',
                                                [
                                                    'project' => $project,
                                                    'procurementTender' => $tender,
                                                ]
                                            ) }}"
                                            class="fw-semibold text-decoration-none"
                                        >
                                            {{ $tender->tender_number }}
                                        </a>

                                    </td>


                                    <td>

                                        <div class="fw-semibold">
                                            {{ $tender->tender_title }}
                                        </div>

                                        @if($tender->tender_type)

                                            <div class="small text-muted">
                                                {{ $tender->tender_type }}
                                            </div>

                                        @endif

                                    </td>


                                    <td>

                                        @if($tender->procurementPackage)

                                            <div class="fw-semibold">
                                                {{
                                                    $tender
                                                        ->procurementPackage
                                                        ->package_number
                                                }}
                                            </div>

                                            @if(
                                                $tender
                                                    ->procurementPackage
                                                    ->package_title
                                            )

                                                <div class="small text-muted">

                                                    {{
                                                        $tender
                                                            ->procurementPackage
                                                            ->package_title
                                                    }}

                                                </div>

                                            @endif

                                        @else

                                            —

                                        @endif

                                    </td>


                                    <td>

                                        <span class="fw-semibold">

                                            {{
                                                $tender->currency
                                            }}

                                            {{
                                                number_format(
                                                    (float)
                                                    $tender->estimated_value,
                                                    2
                                                )
                                            }}

                                        </span>

                                    </td>


                                    <td>

                                        {{
                                            $tender->submission_deadline
                                                ? $tender
                                                    ->submission_deadline
                                                    ->format('d-m-Y')
                                                : '—'
                                        }}

                                    </td>


                                    <td>

                                        <span
                                            class="badge {{ $statusClass }}"
                                        >
                                            {{ $tender->status }}
                                        </span>

                                    </td>


                                    <td class="text-end">

                                        <div class="d-flex justify-content-end gap-1">

                                            <a
                                                href="{{ route(
                                                    'admin.projects.procurement.tenders.show',
                                                    [
                                                        'project' => $project,
                                                        'procurementTender' => $tender,
                                                    ]
                                                ) }}"
                                                class="btn btn-sm btn-outline-primary"
                                                title="View"
                                            >
                                                View
                                            </a>


                                            @if(
                                                $tender->status === 'Draft'
                                            )

                                                <a
                                                    href="{{ route(
                                                        'admin.projects.procurement.tenders.edit',
                                                        [
                                                            'project' => $project,
                                                            'procurementTender' => $tender,
                                                        ]
                                                    ) }}"
                                                    class="btn btn-sm btn-outline-secondary"
                                                >
                                                    Edit
                                                </a>


                                                <form
                                                    method="POST"
                                                    action="{{ route(
                                                        'admin.projects.procurement.tenders.destroy',
                                                        [
                                                            'project' => $project,
                                                            'procurementTender' => $tender,
                                                        ]
                                                    ) }}"
                                                    onsubmit="return confirm('Delete this Tender?');"
                                                >

                                                    @csrf
                                                    @method('DELETE')

                                                    <button
                                                        type="submit"
                                                        class="btn btn-sm btn-outline-danger"
                                                    >
                                                        Delete
                                                    </button>

                                                </form>

                                            @endif

                                        </div>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>


                <div class="p-3">

                    {{ $tenders->links() }}

                </div>

            @else

                <div class="text-center py-5">

                    <div class="fs-1 text-muted mb-3">
                        <i class="ri-file-list-3-line"></i>
                    </div>

                    <h5>
                        No Tenders Found
                    </h5>

                    <p class="text-muted">
                        No procurement tenders have been created
                        for this project yet.
                    </p>

                    <a
                        href="{{ route(
                            'admin.projects.procurement.tenders.create',
                            $project
                        ) }}"
                        class="btn btn-primary"
                    >
                        Create First Tender
                    </a>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection