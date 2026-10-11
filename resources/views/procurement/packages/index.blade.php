@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- Header --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                Procurement Packages
            </h4>

            <div class="text-muted">

                Manage procurement packages for

                <strong>
                    {{ $project->project_name }}
                </strong>

                @if($project->project_code)
                    <span class="ms-1">
                        ({{ $project->project_code }})
                    </span>
                @endif

            </div>

        </div>


        <div class="d-flex gap-2">

            <a
    href="{{ route(
        'admin.projects.procurement.plans.index',
        $project
    ) }}"
    class="btn btn-outline-secondary"
>
    Procurement Plans
</a>


            <a
                href="{{ route(
                    'admin.projects.procurement.packages.create',
                    $project
                ) }}"
                class="btn btn-primary"
            >
                + New Package
            </a>

        </div>

    </div>


    {{-- Messages --}}

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

            <strong>
                Filters
            </strong>

        </div>


        <div class="card-body">

            <form
                method="GET"
                action="{{ route(
                    'admin.projects.procurement.packages.index',
                    $project
                ) }}"
            >

                <div class="row g-3">


                    {{-- Procurement Plan --}}

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

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Package Type --}}

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
                                    'admin.projects.procurement.packages.index',
                                    $project
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

            <strong>
                Packages
            </strong>

        </div>


        <div class="card-body p-0">

            @if($packages->count())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th>#</th>

                                <th>
                                    Package No.
                                </th>

                                <th>
                                    Package Title
                                </th>

                                <th>
                                    Project
                                </th>

                                <th>
                                    Plan
                                </th>

                                <th>
                                    Budget
                                </th>

                                <th>
                                    Budget Item
                                </th>

                                <th>
                                    Type
                                </th>

                                <th>
                                    Estimated Value
                                </th>

                                <th>
                                    Status
                                </th>

                                <th>
                                    Responsible
                                </th>

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


                                    {{-- Package Number --}}

                                    <td>

                                        <a
                                            href="{{ route(
                                                'admin.projects.procurement.packages.show',
                                                [
                                                    'project' => $project->id,
                                                    'procurementPackage' => $package->id,
                                                ]
                                            ) }}"
                                            class="fw-semibold text-decoration-none"
                                        >

                                            {{ $package->package_number }}

                                        </a>

                                    </td>


                                    {{-- Title --}}

                                    <td>

                                        {{ $package->package_title }}

                                    </td>


                                    {{-- Project --}}

                                    <td>

                                        <div class="fw-semibold">

                                            {{ $project->project_name }}

                                        </div>

                                        @if($project->project_code)

                                            <div class="small text-muted">

                                                {{ $project->project_code }}

                                            </div>

                                        @endif

                                    </td>


                                    {{-- Plan --}}

                                    <td>

                                        @if($package->procurementPlan)

                                            <a
                                                href="{{ route(
                                                    'admin.projects.procurement.plans.show',
                                                    [
                                                        'project' => $project->id,
                                                        'procurementPlan' =>
                                                            $package->procurement_plan_id,
                                                    ]
                                                ) }}"
                                                class="text-decoration-none"
                                            >

                                                {{ $package->procurementPlan->plan_number }}

                                            </a>

                                        @else

                                            —

                                        @endif

                                    </td>


                                    {{-- Budget --}}

                                    <td>

                                        @if($package->projectBudget)

                                            <div class="fw-semibold">
                                                {{ $package->projectBudget->budget_name
                                                    ?? $package->projectBudget->name
                                                    ?? '—' }}
                                            </div>

                                            @if(isset($package->projectBudget->budget_year))
                                                <div class="small text-muted">
                                                    Year: {{ $package->projectBudget->budget_year }}
                                                </div>
                                            @endif

                                        @else

                                            <span class="text-muted">Not linked</span>

                                        @endif

                                    </td>


                                    {{-- Budget Item --}}

                                    <td>

                                        @if($package->budgetItem)

                                            <div class="fw-semibold">

                                                @if(!empty($package->budgetItem->item_code))
                                                    {{ $package->budgetItem->item_code }} -
                                                @endif

                                                {{ $package->budgetItem->item_name
                                                    ?? $package->budgetItem->name
                                                    ?? '—' }}

                                            </div>

                                            @if($package->budgetItem->category)

                                                <div class="small text-muted">
                                                    Category:
                                                    {{ $package->budgetItem->category->name
                                                        ?? $package->budgetItem->category->category_name
                                                        ?? '—' }}
                                                </div>

                                            @endif

                                            @if(isset($package->budgetItem->approved_amount))

                                                <div class="small text-muted">
                                                    Approved:
                                                    {{ $package->currency }}
                                                    {{ number_format(
                                                        (float) $package->budgetItem->approved_amount,
                                                        2
                                                    ) }}
                                                </div>

                                            @elseif(isset($package->budgetItem->budget_amount))

                                                <div class="small text-muted">
                                                    Budget:
                                                    {{ $package->currency }}
                                                    {{ number_format(
                                                        (float) $package->budgetItem->budget_amount,
                                                        2
                                                    ) }}
                                                </div>

                                            @endif

                                        @else

                                            <span class="badge bg-warning text-dark">
                                                Not linked
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Type --}}

                                    <td>

                                        {{ $package->package_type ?: '—' }}

                                    </td>


                                    {{-- Estimated Value --}}

                                    <td>

                                        {{ $package->currency }}

                                        {{ number_format(
                                            (float) $package->estimated_value,
                                            2
                                        ) }}

                                    </td>


                                    {{-- Status --}}

                                    <td>

                                        <span
                                            class="badge {{ $statusClass }}"
                                        >

                                            {{ $package->status }}

                                        </span>

                                    </td>


                                    {{-- Responsible --}}

                                    <td>

                                        {{
                                            $package->responsibleUser->name
                                            ?? $package->responsible_name
                                            ?? '—'
                                        }}

                                    </td>


                                    {{-- Actions --}}

                                    <td class="text-end">

                                        <div class="d-inline-flex gap-1">


                                            {{-- View --}}

                                            <a
                                                href="{{ route(
                                                    'admin.projects.procurement.packages.show',
                                                    [
                                                        'project' => $project->id,
                                                        'procurementPackage' =>
                                                            $package->id,
                                                    ]
                                                ) }}"
                                                class="btn btn-sm btn-outline-primary"
                                            >

                                                View

                                            </a>


                                            {{-- Edit --}}

                                            @if(
                                                in_array(
                                                    $package->status,
                                                    [
                                                        'Draft',
                                                        'Planned'
                                                    ],
                                                    true
                                                )
                                            )

                                                <a
                                                    href="{{ route(
                                                        'admin.projects.procurement.packages.edit',
                                                        [
                                                            'project' => $project->id,
                                                            'procurementPackage' =>
                                                                $package->id,
                                                        ]
                                                    ) }}"
                                                    class="btn btn-sm btn-outline-secondary"
                                                >

                                                    Edit

                                                </a>

                                            @endif


                                            {{-- Delete --}}

                                            @if(
                                                $package->status === 'Draft'
                                            )

                                                <form
                                                    method="POST"
                                                    action="{{ route(
                                                        'admin.projects.procurement.packages.destroy',
                                                        [
                                                            'project' => $project->id,
                                                            'procurementPackage' =>
                                                                $package->id,
                                                        ]
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

                        No Procurement Packages found
                        for this project.

                    </div>


                    <a
                        href="{{ route(
                            'admin.projects.procurement.packages.create',
                            $project
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