@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">Procurement Packages</h4>
            <div class="text-muted">
                Manage procurement packages across procurement plans.
            </div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.procurement.plans.index') }}" class="btn btn-outline-secondary">Procurement Plans</a>
            <a
                href="{{ route('admin.procurement.packages.create') }}"
                class="btn btn-primary"
            >
                + New Package
            </a>
        </div>

    </div>


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
                action="{{ route('admin.procurement.packages.index') }}"
            >

                <div class="row g-3">

                    <div class="col-md-4">

                        <label class="form-label">
                            Procurement Plan
                        </label>

                        <select
                            name="procurement_plan_id"
                            class="form-select"
                        >

                            <option value="">
                                All Plans
                            </option>

                            @foreach($procurementPlans as $plan)

                                <option
                                    value="{{ $plan->id }}"
                                    @selected(
                                        request('procurement_plan_id')
                                        == $plan->id
                                    )
                                >
                                    {{ $plan->plan_number }}
                                    -
                                    {{ $plan->plan_title }}

                                    @if($plan->project)
                                        ({{ $plan->project->project_name }})
                                    @endif

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-3">

                        <label class="form-label">
                            Package Type
                        </label>

                        <select
                            name="package_type"
                            class="form-select"
                        >

                            <option value="">
                                All Types
                            </option>

                            @foreach([
                                'Works',
                                'Goods',
                                'Services',
                                'Consultancy',
                                'Mixed',
                            ] as $type)

                                <option
                                    value="{{ $type }}"
                                    @selected(
                                        request('package_type') === $type
                                    )
                                >
                                    {{ $type }}
                                </option>

                            @endforeach

                        </select>

                    </div>


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
                                'Planned',
                                'Tendering',
                                'Under Evaluation',
                                'Awarded',
                                'In Progress',
                                'Completed',
                                'Cancelled',
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
                                    'admin.procurement.packages.index'
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


    {{-- Table --}}
    <div class="card">

        <div class="card-header">
            <strong>Packages</strong>
        </div>

        <div class="card-body p-0">

            @if($packages->count())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th>#</th>

                                <th>Package No.</th>

                                <th>Package Title</th>

                                <th>Project</th>

                                <th>Plan</th>

                                <th>Type</th>

                                <th>Estimated Value</th>

                                <th>Status</th>

                                <th>Responsible</th>

                                <th class="text-end">
                                    Actions
                                </th>

                            </tr>

                        </thead>

                        <tbody>

                            @foreach($packages as $package)

                                @php

                                    $statusClass = match(
                                        $package->status
                                    ) {

                                        'Completed'
                                            => 'bg-success',

                                        'Awarded'
                                            => 'bg-primary',

                                        'In Progress'
                                            => 'bg-info',

                                        'Tendering'
                                            => 'bg-warning text-dark',

                                        'Under Evaluation'
                                            => 'bg-warning text-dark',

                                        'Cancelled'
                                            => 'bg-danger',

                                        default
                                            => 'bg-secondary',

                                    };

                                @endphp

                                <tr>

                                    <td>
                                        {{ $packages->firstItem() + $loop->index }}
                                    </td>


                                    <td>

                                        <a
                                            href="{{ route(
                                                'admin.procurement.packages.show',
                                                $package
                                            ) }}"
                                            class="fw-semibold text-decoration-none"
                                        >
                                            {{ $package->package_number }}
                                        </a>

                                    </td>


                                    <td>
                                        {{ $package->package_title }}
                                    </td>


                                    <td>

                                        @if($package->procurementPlan?->project)

                                            <div class="fw-semibold">
                                                {{ $package->procurementPlan->project->project_name }}
                                            </div>

                                            @if(
                                                $package->procurementPlan->project->project_code
                                            )

                                                <div class="small text-muted">
                                                    {{ $package->procurementPlan->project->project_code }}
                                                </div>

                                            @endif

                                        @else
                                            —
                                        @endif

                                    </td>


                                    <td>

                                        @if($package->procurementPlan)

                                            {{ $package->procurementPlan->plan_number }}

                                        @else
                                            —
                                        @endif

                                    </td>


                                    <td>
                                        {{ $package->package_type ?: '—' }}
                                    </td>


                                    <td>

                                        {{ $package->currency }}

                                        {{ number_format(
                                            (float) $package->estimated_value,
                                            2
                                        ) }}

                                    </td>


                                    <td>

                                        <span
                                            class="badge {{ $statusClass }}"
                                        >
                                            {{ $package->status }}
                                        </span>

                                    </td>


                                    <td>

                                        {{
                                            $package->responsibleUser->name
                                            ?? $package->responsible_name
                                            ?? '—'
                                        }}

                                    </td>


                                    <td class="text-end">

                                        <div class="d-inline-flex gap-1">

                                            <a
                                                href="{{ route(
                                                    'admin.procurement.packages.show',
                                                    $package
                                                ) }}"
                                                class="btn btn-sm btn-outline-primary"
                                            >
                                                View
                                            </a>


                                            @if(
                                                in_array(
                                                    $package->status,
                                                    ['Draft', 'Planned'],
                                                    true
                                                )
                                            )

                                                <a
                                                    href="{{ route(
                                                        'admin.procurement.packages.edit',
                                                        $package
                                                    ) }}"
                                                    class="btn btn-sm btn-outline-secondary"
                                                >
                                                    Edit
                                                </a>

                                            @endif


                                            @if($package->status === 'Draft')

                                                <form
                                                    method="POST"
                                                    action="{{ route(
                                                        'admin.procurement.packages.destroy',
                                                        $package
                                                    ) }}"
                                                >

                                                    @csrf
                                                    @method('DELETE')

                                                    <button
                                                        type="submit"
                                                        class="btn btn-sm btn-outline-danger"
                                                        onclick="return confirm('Delete this Procurement Package?')"
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
                        No Procurement Packages found.
                    </div>

                    <a
                        href="{{ route(
                            'admin.procurement.packages.create'
                        ) }}"
                        class="btn btn-primary"
                    >
                        Create Package
                    </a>

                </div>

            @endif

        </div>


        @if($packages->hasPages())

            <div class="card-footer">
                {{ $packages->links() }}
            </div>

        @endif

    </div>

</div>

@endsection