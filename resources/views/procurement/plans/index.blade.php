@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                Procurement Plans
            </h4>

            <div class="text-muted">
                Manage procurement plans across projects.
            </div>
        </div>

        


        <div class="d-flex gap-2">

            {{-- Tenders --}}
            <a
                href="{{ route(
                    'admin.procurement.packages.index'
                ) }}"
                class="btn btn-outline-primary"
            >
                <i class="ri-archive-line me-1"></i>
                Packages
            </a>

            <a
                href="{{ route(
                    'admin.procurement.tenders.index'
                ) }}"
                class="btn btn-success"
            >
                <i class="ri-auction-line me-1"></i>
                Tenders
            </a>

            <a
                href="{{ route('admin.procurement.plans.create') }}"
                class="btn btn-primary"
            >
                + New Procurement Plan
            </a>

        </div>

    </div>


    {{-- Flash messages --}}

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


    {{-- Filters --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Filters</strong>
        </div>

        <div class="card-body">

            <form
                method="GET"
                action="{{ route('admin.procurement.plans.index') }}"
            >

                <div class="row g-3">

                    {{-- Project --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            Project
                        </label>

                        <select
                            name="project_id"
                            class="form-select"
                        >

                            <option value="">
                                All Projects
                            </option>

                            @foreach($projects as $project)

                                <option
                                    value="{{ $project->id }}"
                                    @selected(
                                        request('project_id')
                                        == $project->id
                                    )
                                >
                                    {{ $project->project_name }}

                                    @if($project->project_code)
                                        ({{ $project->project_code }})
                                    @endif

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Year --}}

                    <div class="col-md-3">

                        <label class="form-label">
                            Procurement Year
                        </label>

                        <input
                            type="number"
                            name="procurement_year"
                            class="form-control"
                            value="{{ request('procurement_year') }}"
                            min="2000"
                            max="2100"
                        >

                    </div>


                    {{-- Status --}}

                    <div class="col-md-3">

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
                                'Submitted',
                                'Under Review',
                                'Revision Required',
                                'Approved',
                                'Rejected',
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


                    {{-- Buttons --}}

                    <div class="col-md-2 d-flex align-items-end">

                        <div class="d-flex gap-2 w-100">

                            <button
                                type="submit"
                                class="btn btn-primary flex-grow-1"
                            >
                                Filter
                            </button>

                            <a
                                href="{{ route(
                                    'admin.procurement.plans.index'
                                ) }}"
                                class="btn btn-outline-secondary"
                            >
                                Reset
                            </a>

                        </div>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- Procurement Plans --}}

    <div class="card">

        <div class="card-header">

            <strong>
                Procurement Plans
            </strong>

        </div>

        <div class="card-body p-0">

            @if($plans->count())

                <div class="table-responsive">

                    <table class="table table-hover mb-0 align-middle">

                        <thead class="table-light">

                            <tr>

                                <th>
                                    #
                                </th>

                                <th>
                                    Plan Number
                                </th>

                                <th>
                                    Project
                                </th>

                                <th>
                                    Title
                                </th>

                                <th>
                                    Year
                                </th>

                                <th>
                                    Estimated Value
                                </th>

                                <th>
                                    Status
                                </th>

                                <th>
                                    Prepared By
                                </th>

                                <th class="text-end">
                                    Actions
                                </th>

                            </tr>

                        </thead>

                        <tbody>

                            @foreach($plans as $plan)

                                @php

                                    $statusClass = match(
                                        $plan->status
                                    ) {

                                        'Approved'
                                            => 'bg-success',

                                        'Rejected'
                                            => 'bg-danger',

                                        'Submitted'
                                            => 'bg-info',

                                        'Under Review'
                                            => 'bg-warning text-dark',

                                        'Revision Required'
                                            => 'bg-warning text-dark',

                                        default
                                            => 'bg-secondary',

                                    };

                                @endphp

                                <tr>

                                    <td>
                                        {{ $plans->firstItem() + $loop->index }}
                                    </td>


                                    <td>

                                        <a
                                            href="{{ route(
                                                'admin.procurement.plans.show',
                                                $plan
                                            ) }}"
                                            class="fw-semibold text-decoration-none"
                                        >
                                            {{ $plan->plan_number }}
                                        </a>

                                    </td>


                                    <td>

                                        @if($plan->project)

                                            <div class="fw-semibold">
                                                {{ $plan->project->project_name }}
                                            </div>

                                            @if($plan->project->project_code)

                                                <div class="small text-muted">
                                                    {{ $plan->project->project_code }}
                                                </div>

                                            @endif

                                        @else

                                            —

                                        @endif

                                    </td>


                                    <td>
                                        {{ $plan->plan_title }}
                                    </td>


                                    <td>
                                        {{ $plan->procurement_year }}
                                    </td>


                                    <td>

                                        @if($plan->total_estimated_value !== null)

                                            {{ $plan->currency }}
                                            {{ number_format(
                                                (float) $plan->total_estimated_value,
                                                2
                                            ) }}

                                        @else

                                            —

                                        @endif

                                    </td>


                                    <td>

                                        <span
                                            class="badge {{ $statusClass }}"
                                        >
                                            {{ $plan->status }}
                                        </span>

                                    </td>


                                    <td>

                                        {{ $plan->preparedBy->name ?? '—' }}

                                    </td>


                                    <td class="text-end">

                                        <div class="d-inline-flex gap-1">

                                            <a
                                                href="{{ route(
                                                    'admin.procurement.plans.show',
                                                    $plan
                                                ) }}"
                                                class="btn btn-sm btn-outline-primary"
                                            >
                                                View
                                            </a>


                                            @if($plan->status !== 'Approved')

                                                <a
                                                    href="{{ route(
                                                        'admin.procurement.plans.edit',
                                                        $plan
                                                    ) }}"
                                                    class="btn btn-sm btn-outline-secondary"
                                                >
                                                    Edit
                                                </a>

                                            @endif


                                            @if($plan->status === 'Draft')

                                                <form
                                                    method="POST"
                                                    action="{{ route(
                                                        'admin.procurement.plans.destroy',
                                                        $plan
                                                    ) }}"
                                                    class="d-inline"
                                                >

                                                    @csrf

                                                    @method('DELETE')

                                                    <button
                                                        type="submit"
                                                        class="btn btn-sm btn-outline-danger"
                                                        onclick="return confirm(
                                                            'Delete this Procurement Plan?'
                                                        )"
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

            @else

                <div class="text-center py-5">

                    <div class="text-muted mb-3">
                        No Procurement Plans found.
                    </div>

                    <a
                        href="{{ route(
                            'admin.procurement.plans.create'
                        ) }}"
                        class="btn btn-primary"
                    >
                        Create Procurement Plan
                    </a>

                </div>

            @endif

        </div>


        @if($plans->hasPages())

            <div class="card-footer">

                {{ $plans->links() }}

            </div>

        @endif

    </div>

</div>

@endsection