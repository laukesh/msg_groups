@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- =========================================================
         HEADER
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3 class="mb-1">
                {{ $land->land_name }}
            </h3>

            <span class="text-muted">
                {{ $land->land_code }}
            </span>

        </div>


        <!-- <div>

            <a
                href="{{ route('admin.land.lands.edit', $land) }}"
                class="btn btn-primary"
            >
                Edit
            </a>

            <a
                href="{{ route('admin.land.lands.index') }}"
                class="btn btn-secondary"
            >
                Back
            </a>

        </div> -->

        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.land.lands.feasibility-assessments.index',
                    $land
                ) }}"
                class="btn btn-primary"
            >
                <i class="ri-line-chart-line me-1"></i>
                Feasibility & Investment
            </a>


            <a
                href="{{ route(
                    'admin.land.lands.edit',
                    $land
                ) }}"
                class="btn btn-outline-primary"
            >
                <i class="ri-edit-line me-1"></i>
                Edit
            </a>


            <a
                href="{{ route(
                    'admin.land.lands.index'
                ) }}"
                class="btn btn-secondary"
            >
                <i class="ri-arrow-left-line me-1"></i>
                Back
            </a>

        </div>



    </div>


    {{-- =========================================================
         TOP SUMMARY CARDS
    ========================================================== --}}

    <div class="row g-3 mb-4">


        {{-- =====================================================
             OPPORTUNITY
        ====================================================== --}}

        <div class="col-md-4">

            <div class="card h-100 shadow-sm">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-start">

                        <div>

                            <small class="text-muted d-block mb-1">
                                Opportunity
                            </small>

                            @if($land->opportunity)

                                <h5 class="mb-1">

                                    {{ $land->opportunity->opportunity_no }}

                                </h5>

                                <div class="text-muted">

                                    {{ $land->opportunity->opportunity_name }}

                                </div>

                            @else

                                <h5 class="text-muted mb-0">
                                    Not Linked
                                </h5>

                            @endif

                        </div>


                        <div class="text-primary">

                            <i class="ri-lightbulb-line fs-3"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
             ACQUISITION STATUS
        ====================================================== --}}

        <div class="col-md-4">

            <div class="card h-100 shadow-sm">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-start">

                        <div>

                            <small class="text-muted d-block mb-1">
                                Acquisition Status
                            </small>

                            <div class="mt-2">

                                <span
                                    class="badge
                                        @if($land->acquisition_status === 'Acquired')
                                            bg-success
                                        @elseif($land->acquisition_status === 'Rejected')
                                            bg-danger
                                        @elseif($land->acquisition_status === 'Withdrawn')
                                            bg-dark
                                        @elseif($land->acquisition_status === 'Approved')
                                            bg-primary
                                        @elseif($land->acquisition_status === 'Approval Pending')
                                            bg-warning text-dark
                                        @else
                                            bg-secondary
                                        @endif
                                    "
                                >

                                    {{ $land->acquisition_status }}

                                </span>

                            </div>

                        </div>


                        <div class="text-success">

                            <i class="ri-checkbox-circle-line fs-3"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
             SUMMARY
        ====================================================== --}}

        <div class="col-md-4">

            <div class="card h-100 shadow-sm">

                <div class="card-body">

                    <small class="text-muted d-block mb-3">
                        Summary
                    </small>


                    <div class="row text-center">


                        {{-- Plots --}}

                        <div class="col-4">

                            <div class="fw-bold fs-5">

                                {{ $land->plots->count() }}

                            </div>

                            <small class="text-muted">
                                Plots
                            </small>

                        </div>


                        {{-- Owners --}}

                        <div class="col-4">

                            <div class="fw-bold fs-5">

                                {{ $land->owners->count() }}

                            </div>

                            <small class="text-muted">
                                Owners
                            </small>

                        </div>


                        {{-- Zoning --}}

                        <div class="col-4">

                            <div class="fw-bold fs-5">

                                {{ $land->zonings->count() }}

                            </div>

                            <small class="text-muted">
                                Zoning
                            </small>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         MAIN CONTENT
    ========================================================== --}}

    <div class="row">

        <div class="col-12">


            {{-- =================================================
                 LAND INFORMATION
            ================================================== --}}

            <div class="card mb-4">

                <div class="card-header">

                    <strong>
                        Land Information
                    </strong>

                </div>


                <div class="card-body">

                    <div class="row">


                        {{-- Land Code --}}

                        <div class="col-md-6 mb-3">

                            <small class="text-muted">
                                Land Code
                            </small>

                            <div>

                                {{ $land->land_code }}

                            </div>

                        </div>


                        {{-- Land Name --}}

                        <div class="col-md-6 mb-3">

                            <small class="text-muted">
                                Land Name
                            </small>

                            <div>

                                {{ $land->land_name }}

                            </div>

                        </div>


                        {{-- Land Type --}}

                        <div class="col-md-6 mb-3">

                            <small class="text-muted">
                                Land Type
                            </small>

                            <div>

                                {{ $land->land_type ?? '-' }}

                            </div>

                        </div>


                        {{-- Total Area --}}

                        <div class="col-md-6 mb-3">

                            <small class="text-muted">
                                Total Area
                            </small>

                            <div>

                                {{ $land->total_area ?? '-' }}

                                {{ $land->area_unit }}

                            </div>

                        </div>


                        {{-- Acquisition Date --}}

                        <div class="col-md-6 mb-3">

                            <small class="text-muted">
                                Acquisition Date
                            </small>

                            <div>

                                {{ $land->acquisition_date
                                    ? $land->acquisition_date->format('d-m-Y')
                                    : '-'
                                }}

                            </div>

                        </div>


                    </div>

                </div>

            </div>


            {{-- =================================================
                 LOCATION
            ================================================== --}}

            <div class="card mb-4">

                <div class="card-header">

                    <strong>
                        Location
                    </strong>

                </div>


                <div class="card-body">

                    {{ $land->address_line1 }}

                    @if($land->address_line2)

                        <br>

                        {{ $land->address_line2 }}

                    @endif

                    <br>

                    {{ $land->locality }}

                    @if($land->city)
                        , {{ $land->city }}
                    @endif

                    @if($land->state)
                        , {{ $land->state }}
                    @endif

                    <br>

                    {{ $land->country }}

                    {{ $land->postal_code }}

                </div>

            </div>


            {{-- =================================================
                 OWNERSHIP
            ================================================== --}}

            <div class="card mb-4">

                <div class="card-header d-flex justify-content-between align-items-center">

                    <strong>
                        Ownership
                    </strong>


                    <a
                        href="{{ route(
                            'admin.land.lands.owners.create',
                            $land
                        ) }}"
                        class="btn btn-sm btn-primary"
                    >
                        + Add Owner
                    </a>

                </div>


                <div class="card-body p-0">

                    <div class="table-responsive">

                        <table class="table table-hover mb-0">

                            <thead>

                                <tr>

                                    <th>
                                        Owner
                                    </th>

                                    <th>
                                        Type
                                    </th>

                                    <th>
                                        Ownership
                                    </th>

                                    <th>
                                        Title Reference
                                    </th>

                                    <th width="150">
                                        Action
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @forelse($land->owners as $owner)

                                    <tr>

                                        <td>

                                            <strong>
                                                {{ $owner->owner_name }}
                                            </strong>

                                        </td>


                                        <td>

                                            {{ $owner->owner_type }}

                                        </td>


                                        <td>

                                            {{ number_format(
                                                $owner->ownership_percentage ?? 0,
                                                2
                                            ) }}%

                                        </td>


                                        <td>

                                            {{ $owner->title_reference ?? '-' }}

                                        </td>


                                        <td>

                                            <a
                                                href="{{ route(
                                                    'admin.land.lands.owners.show',
                                                    [
                                                        $land,
                                                        $owner
                                                    ]
                                                ) }}"
                                                class="btn btn-sm btn-outline-primary"
                                            >
                                                View
                                            </a>


                                            <a
                                                href="{{ route(
                                                    'admin.land.lands.owners.edit',
                                                    [
                                                        $land,
                                                        $owner
                                                    ]
                                                ) }}"
                                                class="btn btn-sm btn-outline-secondary"
                                            >
                                                Edit
                                            </a>

                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td
                                            colspan="5"
                                            class="text-center py-4"
                                        >

                                            No ownership records found.

                                            <br>

                                            <a
                                                href="{{ route(
                                                    'admin.land.lands.owners.create',
                                                    $land
                                                ) }}"
                                                class="btn btn-sm btn-primary mt-2"
                                            >
                                                Add First Owner
                                            </a>

                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>


                @if($land->owners->count() > 0)

                    <div class="card-footer">

                        <strong>
                            Total Ownership:
                        </strong>

                        {{ number_format(
                            $land->owners->sum('ownership_percentage'),
                            2
                        ) }}%

                    </div>

                @endif

            </div>


            {{-- =================================================
                 PLOT INFORMATION
            ================================================== --}}

            <div class="card mb-4">

                <div class="card-header d-flex justify-content-between align-items-center">

                    <strong>
                        Plot Information
                    </strong>


                    <a
                        href="{{ route(
                            'admin.land.lands.plots.create',
                            $land
                        ) }}"
                        class="btn btn-sm btn-primary"
                    >
                        + Add Plot
                    </a>

                </div>


                <div class="card-body p-0">

                    <div class="table-responsive">

                        <table class="table table-hover mb-0">

                            <thead>

                                <tr>

                                    <th>
                                        Plot No.
                                    </th>

                                    <th>
                                        Survey No.
                                    </th>

                                    <th>
                                        Parcel No.
                                    </th>

                                    <th>
                                        Area
                                    </th>

                                    <th>
                                        Type
                                    </th>

                                    <th width="150">
                                        Action
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @forelse($land->plots as $plot)

                                    <tr>

                                        <td>

                                            <strong>
                                                {{ $plot->plot_number ?? '-' }}
                                            </strong>

                                        </td>


                                        <td>
                                            {{ $plot->survey_number ?? '-' }}
                                        </td>


                                        <td>
                                            {{ $plot->parcel_number ?? '-' }}
                                        </td>


                                        <td>

                                            {{ $plot->plot_area ?? '-' }}

                                            {{ $plot->area_unit ?? '' }}

                                        </td>


                                        <td>
                                            {{ $plot->plot_type ?? '-' }}
                                        </td>


                                        <td>

                                            <a
                                                href="{{ route(
                                                    'admin.land.lands.plots.show',
                                                    [
                                                        $land,
                                                        $plot
                                                    ]
                                                ) }}"
                                                class="btn btn-sm btn-outline-primary"
                                            >
                                                View
                                            </a>


                                            <a
                                                href="{{ route(
                                                    'admin.land.lands.plots.edit',
                                                    [
                                                        $land,
                                                        $plot
                                                    ]
                                                ) }}"
                                                class="btn btn-sm btn-outline-secondary"
                                            >
                                                Edit
                                            </a>

                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td
                                            colspan="6"
                                            class="text-center py-4"
                                        >

                                            No plot information found.

                                            <br>

                                            <a
                                                href="{{ route(
                                                    'admin.land.lands.plots.create',
                                                    $land
                                                ) }}"
                                                class="btn btn-sm btn-primary mt-2"
                                            >
                                                Add First Plot
                                            </a>

                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>


                @if($land->plots->count())

                    <div class="card-footer">

                        <strong>
                            Total Plot Area:
                        </strong>

                        {{ number_format(
                            $land->plots->sum('plot_area'),
                            4
                        ) }}

                    </div>

                @endif

            </div>


            {{-- =================================================
                 ZONING INFORMATION
            ================================================== --}}

            <div class="card mb-4">

                <div class="card-header d-flex justify-content-between align-items-center">

                    <strong>
                        Zoning Information
                    </strong>


                    <a
                        href="{{ route(
                            'admin.land.lands.zonings.create',
                            $land
                        ) }}"
                        class="btn btn-sm btn-primary"
                    >
                        + Add Zoning
                    </a>

                </div>


                <div class="card-body p-0">

                    <div class="table-responsive">

                        <table class="table table-hover mb-0">

                            <thead>

                                <tr>

                                    <th>
                                        Zoning Code
                                    </th>

                                    <th>
                                        Zoning Type
                                    </th>

                                    <th>
                                        Authority
                                    </th>

                                    <th>
                                        Effective
                                    </th>

                                    <th>
                                        Expiry
                                    </th>

                                    <th width="150">
                                        Action
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @forelse($land->zonings as $zoning)

                                    <tr>

                                        <td>

                                            <strong>
                                                {{ $zoning->zoning_code ?? '-' }}
                                            </strong>

                                        </td>


                                        <td>
                                            {{ $zoning->zoning_type }}
                                        </td>


                                        <td>
                                            {{ $zoning->authority ?? '-' }}
                                        </td>


                                        <td>

                                            {{ $zoning->effective_date
                                                ? $zoning->effective_date->format('d-m-Y')
                                                : '-'
                                            }}

                                        </td>


                                        <td>

                                            {{ $zoning->expiry_date
                                                ? $zoning->expiry_date->format('d-m-Y')
                                                : '-'
                                            }}

                                        </td>


                                        <td>

                                            <a
                                                href="{{ route(
                                                    'admin.land.lands.zonings.show',
                                                    [
                                                        $land,
                                                        $zoning
                                                    ]
                                                ) }}"
                                                class="btn btn-sm btn-outline-primary"
                                            >
                                                View
                                            </a>


                                            <a
                                                href="{{ route(
                                                    'admin.land.lands.zonings.edit',
                                                    [
                                                        $land,
                                                        $zoning
                                                    ]
                                                ) }}"
                                                class="btn btn-sm btn-outline-secondary"
                                            >
                                                Edit
                                            </a>

                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td
                                            colspan="6"
                                            class="text-center py-4"
                                        >

                                            No zoning information found.

                                            <br>

                                            <a
                                                href="{{ route(
                                                    'admin.land.lands.zonings.create',
                                                    $land
                                                ) }}"
                                                class="btn btn-sm btn-primary mt-2"
                                            >
                                                Add First Zoning
                                            </a>

                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>


            {{-- =================================================
                 DEVELOPMENT RIGHTS
            ================================================== --}}

            <div class="card mb-4">

                <div class="card-header d-flex justify-content-between align-items-center">

                    <strong>
                        Development Rights
                    </strong>


                    <a
                        href="{{ route(
                            'admin.land.lands.development-rights.create',
                            $land
                        ) }}"
                        class="btn btn-sm btn-primary"
                    >
                        + Add Development Right
                    </a>

                </div>


                <div class="card-body p-0">

                    <div class="table-responsive">

                        <table class="table table-hover mb-0">

                            <thead>

                                <tr>

                                    <th>
                                        Right Type
                                    </th>

                                    <th>
                                        Authority
                                    </th>

                                    <th>
                                        Reference
                                    </th>

                                    <th>
                                        Effective
                                    </th>

                                    <th>
                                        Expiry
                                    </th>

                                    <th width="150">
                                        Action
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @forelse(
                                    $land->developmentRights
                                    as $developmentRight
                                )

                                    <tr>

                                        <td>

                                            <strong>
                                                {{ $developmentRight->right_type }}
                                            </strong>

                                        </td>


                                        <td>
                                            {{ $developmentRight->authority ?? '-' }}
                                        </td>


                                        <td>
                                            {{ $developmentRight->reference_number ?? '-' }}
                                        </td>


                                        <td>

                                            {{ $developmentRight->effective_date
                                                ? $developmentRight->effective_date->format('d-m-Y')
                                                : '-'
                                            }}

                                        </td>


                                        <td>

                                            {{ $developmentRight->expiry_date
                                                ? $developmentRight->expiry_date->format('d-m-Y')
                                                : '-'
                                            }}

                                        </td>


                                        <td>

                                            <a
                                                href="{{ route(
                                                    'admin.land.lands.development-rights.show',
                                                    [
                                                        $land,
                                                        $developmentRight
                                                    ]
                                                ) }}"
                                                class="btn btn-sm btn-outline-primary"
                                            >
                                                View
                                            </a>


                                            <a
                                                href="{{ route(
                                                    'admin.land.lands.development-rights.edit',
                                                    [
                                                        $land,
                                                        $developmentRight
                                                    ]
                                                ) }}"
                                                class="btn btn-sm btn-outline-secondary"
                                            >
                                                Edit
                                            </a>

                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td
                                            colspan="6"
                                            class="text-center py-4"
                                        >

                                            No development rights found.

                                            <br>

                                            <a
                                                href="{{ route(
                                                    'admin.land.lands.development-rights.create',
                                                    $land
                                                ) }}"
                                                class="btn btn-sm btn-primary mt-2"
                                            >
                                                Add First Development Right
                                            </a>

                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>


            {{-- =================================================
                 ACQUISITION COST
            ================================================== --}}

            <div class="card mb-4">

                <div class="card-header d-flex justify-content-between align-items-center">

                    <strong>
                        Acquisition Cost
                    </strong>


                    <a
                        href="{{ route(
                            'admin.land.lands.acquisition-costs.create',
                            $land
                        ) }}"
                        class="btn btn-sm btn-primary"
                    >
                        + Add Cost
                    </a>

                </div>


                <div class="card-body p-0">

                    <div class="table-responsive">

                        <table class="table table-hover mb-0">

                            <thead>

                                <tr>

                                    <th>
                                        Category
                                    </th>

                                    <th>
                                        Amount
                                    </th>

                                    <th>
                                        Tax
                                    </th>

                                    <th>
                                        Total
                                    </th>

                                    <th>
                                        Payment
                                    </th>

                                    <th width="150">
                                        Action
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @forelse(
                                    $land->acquisitionCosts
                                    as $cost
                                )

                                    <tr>

                                        <td>

                                            <strong>
                                                {{ $cost->cost_category }}
                                            </strong>

                                            @if($cost->cost_description)

                                                <br>

                                                <small class="text-muted">

                                                    {{ $cost->cost_description }}

                                                </small>

                                            @endif

                                        </td>


                                        <td>

                                            {{ $cost->currency }}

                                            {{ number_format(
                                                $cost->amount,
                                                2
                                            ) }}

                                        </td>


                                        <td>

                                            {{ $cost->currency }}

                                            {{ number_format(
                                                $cost->tax_amount,
                                                2
                                            ) }}

                                        </td>


                                        <td>

                                            <strong>

                                                {{ $cost->currency }}

                                                {{ number_format(
                                                    $cost->total_amount,
                                                    2
                                                ) }}

                                            </strong>

                                        </td>


                                        <td>

                                            @if($cost->payment_status === 'Paid')

                                                <span class="badge bg-success">
                                                    Paid
                                                </span>

                                            @elseif(
                                                $cost->payment_status === 'Partially Paid'
                                            )

                                                <span class="badge bg-warning text-dark">
                                                    Partially Paid
                                                </span>

                                            @else

                                                <span class="badge bg-secondary">
                                                    Pending
                                                </span>

                                            @endif

                                        </td>


                                        <td>

                                            <a
                                                href="{{ route(
                                                    'admin.land.lands.acquisition-costs.show',
                                                    [
                                                        $land,
                                                        $cost
                                                    ]
                                                ) }}"
                                                class="btn btn-sm btn-outline-primary"
                                            >
                                                View
                                            </a>


                                            <a
                                                href="{{ route(
                                                    'admin.land.lands.acquisition-costs.edit',
                                                    [
                                                        $land,
                                                        $cost
                                                    ]
                                                ) }}"
                                                class="btn btn-sm btn-outline-secondary"
                                            >
                                                Edit
                                            </a>

                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td
                                            colspan="6"
                                            class="text-center py-4"
                                        >

                                            No acquisition cost records found.

                                            <br>

                                            <a
                                                href="{{ route(
                                                    'admin.land.lands.acquisition-costs.create',
                                                    $land
                                                ) }}"
                                                class="btn btn-sm btn-primary mt-2"
                                            >
                                                Add First Cost
                                            </a>

                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>


                @if($land->acquisitionCosts->count())

                    <div class="card-footer">

                        <div class="d-flex justify-content-between">

                            <strong>
                                Total Acquisition Cost
                            </strong>

                            <strong>

                                INR

                                {{ number_format(
                                    $land->acquisitionCosts
                                        ->sum('total_amount'),
                                    2
                                ) }}

                            </strong>

                        </div>

                    </div>

                @endif

            </div>


            {{-- =================================================
                 LEGAL DUE DILIGENCE
            ================================================== --}}

            <div class="card mb-4">

                <div class="card-header d-flex justify-content-between align-items-center">

                    <strong>
                        Legal Due Diligence
                    </strong>

                    <a
                        href="{{ route(
                            'admin.land.lands.legal-due-diligences.create',
                            $land
                        ) }}"
                        class="btn btn-sm btn-primary"
                    >
                        + Add Legal Review
                    </a>

                </div>


                <div class="card-body p-0">

                    <div class="table-responsive">

                        <table class="table table-hover mb-0">

                            <thead>

                                <tr>

                                    <th>
                                        Reference
                                    </th>

                                    <th>
                                        Assessment Date
                                    </th>

                                    <th>
                                        Conducted By
                                    </th>

                                    <th>
                                        Findings
                                    </th>

                                    <th>
                                        Recommendations
                                    </th>

                                    <th>
                                        Status
                                    </th>

                                    <th width="150">
                                        Action
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @forelse(
                                    $land->dueDiligences->where(
                                        'type',
                                        'Legal'
                                    ) as $dueDiligence
                                )

                                    <tr>

                                        {{-- Reference --}}

                                        <td>

                                            <strong>
                                                {{ $dueDiligence->reference_no ?? '-' }}
                                            </strong>

                                        </td>


                                        {{-- Assessment Date --}}

                                        <td>

                                            {{ $dueDiligence->assessment_date
                                                ? $dueDiligence->assessment_date->format('d-m-Y')
                                                : '-'
                                            }}

                                        </td>


                                        {{-- Conducted By --}}

                                        <td>
                                            {{ $dueDiligence->conducted_by ?? '-' }}
                                        </td>


                                        {{-- Findings --}}

                                        <td>

                                            @if($dueDiligence->findings)

                                                <span
                                                    title="{{ $dueDiligence->findings }}"
                                                >
                                                    {{ \Illuminate\Support\Str::limit(
                                                        $dueDiligence->findings,
                                                        80
                                                    ) }}
                                                </span>

                                            @else

                                                -

                                            @endif

                                        </td>


                                        {{-- Recommendations --}}

                                        <td>

                                            @if($dueDiligence->recommendations)

                                                <span
                                                    title="{{ $dueDiligence->recommendations }}"
                                                >
                                                    {{ \Illuminate\Support\Str::limit(
                                                        $dueDiligence->recommendations,
                                                        80
                                                    ) }}
                                                </span>

                                            @else

                                                -

                                            @endif

                                        </td>


                                        {{-- Status --}}

                                        <td>

                                            @php
                                                $statusClass = match(
                                                    $dueDiligence->status
                                                ) {
                                                    'Completed' => 'bg-success',
                                                    'Approved' => 'bg-success',
                                                    'Pending' => 'bg-warning text-dark',
                                                    'In Progress' => 'bg-info text-dark',
                                                    'Rejected' => 'bg-danger',
                                                    'On Hold' => 'bg-secondary',
                                                    default => 'bg-secondary',
                                                };
                                            @endphp

                                            <span class="badge {{ $statusClass }}">
                                                {{ $dueDiligence->status ?? '-' }}
                                            </span>

                                        </td>


                                        {{-- Actions --}}

                                        <td>

                                            <a
                                                href="{{ route(
                                                    'admin.land.lands.legal-due-diligences.show',
                                                    [
                                                        $land,
                                                        $dueDiligence
                                                    ]
                                                ) }}"
                                                class="btn btn-sm btn-outline-primary"
                                            >
                                                View
                                            </a>


                                            <a
                                                href="{{ route(
                                                    'admin.land.lands.legal-due-diligences.edit',
                                                    [
                                                        $land,
                                                        $dueDiligence
                                                    ]
                                                ) }}"
                                                class="btn btn-sm btn-outline-secondary"
                                            >
                                                Edit
                                            </a>

                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td
                                            colspan="7"
                                            class="text-center py-4"
                                        >

                                            No legal due diligence records found.

                                            <br>

                                            <a
                                                href="{{ route(
                                                    'admin.land.lands.legal-due-diligences.create',
                                                    $land
                                                ) }}"
                                                class="btn btn-sm btn-primary mt-2"
                                            >
                                                Add First Review
                                            </a>

                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>


            {{-- =================================================
                 TECHNICAL DUE DILIGENCE
            ================================================== --}}

            <div class="card mb-4">

                <div class="card-header d-flex justify-content-between align-items-center">

                    <strong>
                        Technical Due Diligence
                    </strong>


                    <a
                        href="{{ route(
                            'admin.land.lands.technical-due-diligences.create',
                            $land
                        ) }}"
                        class="btn btn-sm btn-primary"
                    >
                        + Add Assessment
                    </a>

                </div>


                <div class="card-body p-0">

                    <div class="table-responsive">

                        <table class="table table-hover mb-0">

                            <thead>

                                <tr>

                                    <th>
                                        Reference
                                    </th>

                                    <th>
                                        Assessment Date
                                    </th>

                                    <th>
                                        Conducted By
                                    </th>

                                    <th>
                                        Status
                                    </th>

                                    <th width="100">
                                        Action
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @forelse(
                                    $land->dueDiligences->where(
                                        'type',
                                        'Technical'
                                    )
                                    as $dueDiligence
                                )

                                    <tr>

                                        <td>
                                            {{ $dueDiligence->reference_no ?? '-' }}
                                        </td>


                                        <td>

                                            {{ $dueDiligence->assessment_date
                                                ? $dueDiligence->assessment_date->format('d-m-Y')
                                                : '-'
                                            }}

                                        </td>


                                        <td>
                                            {{ $dueDiligence->conducted_by ?? '-' }}
                                        </td>


                                        <td>
                                            {{ $dueDiligence->status }}
                                        </td>


                                        <td>

                                            <a
                                                href="{{ route(
                                                    'admin.land.lands.technical-due-diligences.show',
                                                    [
                                                        $land,
                                                        $dueDiligence
                                                    ]
                                                ) }}"
                                                class="btn btn-sm btn-outline-primary"
                                            >
                                                View
                                            </a>

                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td
                                            colspan="5"
                                            class="text-center py-4"
                                        >

                                            No technical due diligence records found.

                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>


            {{-- =================================================
                 ENVIRONMENTAL ASSESSMENT
            ================================================== --}}

            <div class="card mb-4">

                <div class="card-header d-flex justify-content-between align-items-center">

                    <strong>
                        Environmental Assessment
                    </strong>


                    <a
                        href="{{ route(
                            'admin.land.lands.environmental-assessments.create',
                            $land
                        ) }}"
                        class="btn btn-sm btn-primary"
                    >
                        + Add Assessment
                    </a>

                </div>


                <div class="card-body p-0">

                    <div class="table-responsive">

                        <table class="table table-hover mb-0">

                            <thead>

                                <tr>

                                    <th>
                                        Reference
                                    </th>

                                    <th>
                                        Assessment Date
                                    </th>

                                    <th>
                                        Conducted By
                                    </th>

                                    <th>
                                        Status
                                    </th>

                                    <th width="100">
                                        Action
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @forelse(
                                    $land->dueDiligences->where(
                                        'type',
                                        'Environmental'
                                    )
                                    as $dueDiligence
                                )

                                    <tr>

                                        <td>
                                            {{ $dueDiligence->reference_no ?? '-' }}
                                        </td>


                                        <td>

                                            {{ $dueDiligence->assessment_date
                                                ? $dueDiligence->assessment_date->format('d-m-Y')
                                                : '-'
                                            }}

                                        </td>


                                        <td>
                                            {{ $dueDiligence->conducted_by ?? '-' }}
                                        </td>


                                        <td>
                                            {{ $dueDiligence->status }}
                                        </td>


                                        <td>

                                            <a
                                                href="{{ route(
                                                    'admin.land.lands.environmental-assessments.show',
                                                    [
                                                        $land,
                                                        $dueDiligence
                                                    ]
                                                ) }}"
                                                class="btn btn-sm btn-outline-primary"
                                            >
                                                View
                                            </a>

                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td
                                            colspan="5"
                                            class="text-center py-4"
                                        >

                                            No environmental assessments found.

                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>


            {{-- =================================================
                 ACQUISITION DOCUMENTS
            ================================================== --}}

            <div class="card mb-4">

                <div class="card-header d-flex justify-content-between align-items-center">

                    <strong>
                        Acquisition Documents
                    </strong>


                    <a
                        href="{{ route(
                            'admin.land.lands.documents.create',
                            $land
                        ) }}"
                        class="btn btn-sm btn-primary"
                    >
                        + Upload Document
                    </a>

                </div>


                <div class="card-body p-0">

                    <div class="table-responsive">

                        <table class="table table-hover mb-0">

                            <thead>

                                <tr>

                                    <th>
                                        Document
                                    </th>

                                    <th>
                                        Type
                                    </th>

                                    <th>
                                        Version
                                    </th>

                                    <th>
                                        Status
                                    </th>

                                    <th>
                                        Action
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @forelse(
                                    $land->documents
                                    as $document
                                )

                                    <tr>

                                        <td>
                                            {{ $document->title }}
                                        </td>


                                        <td>
                                            {{ $document->document_type }}
                                        </td>


                                        <td>
                                            {{ $document->version }}
                                        </td>


                                        <td>
                                            {{ $document->approval_status }}
                                        </td>


                                        <td>

                                            <a
                                                href="{{ route(
                                                    'admin.land.lands.documents.show',
                                                    [
                                                        $land,
                                                        $document
                                                    ]
                                                ) }}"
                                                class="btn btn-sm btn-outline-primary"
                                            >
                                                View
                                            </a>

                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td
                                            colspan="5"
                                            class="text-center py-4"
                                        >

                                            No acquisition documents found.

                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>


            {{-- =================================================
                 FEASIBILITY & INVESTMENT
            ================================================== --}}

            <div class="card mb-4">

                <div class="card-header d-flex justify-content-between align-items-center">

                    <strong>
                        Feasibility & Investment
                    </strong>


                    <a
                        href="{{ route(
                            'admin.land.lands.feasibility-assessments.create',
                            $land
                        ) }}"
                        class="btn btn-sm btn-primary"
                    >
                        + New Feasibility
                    </a>

                </div>


                <div class="card-body p-0">

                    <div class="table-responsive">

                        <table class="table table-hover mb-0">

                            <thead>

                                <tr>

                                    <th>
                                        Assessment No.
                                    </th>

                                    <th>
                                        Title
                                    </th>

                                    <th>
                                        Development Type
                                    </th>

                                    <th>
                                        Status
                                    </th>

                                    <th>
                                        Date
                                    </th>

                                    <th>
                                        Action
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @forelse(
                                    $land->feasibilityAssessments
                                    as $feasibility
                                )

                                    <tr>

                                        <td>

                                            <strong>
                                                {{ $feasibility->assessment_number }}
                                            </strong>

                                        </td>


                                        <td>
                                            {{ $feasibility->title }}
                                        </td>


                                        <td>
                                            {{ $feasibility->development_type ?? '-' }}
                                        </td>


                                        <td>

                                            <span class="badge bg-secondary">

                                                {{ $feasibility->status }}

                                            </span>

                                        </td>


                                        <td>

                                            {{ $feasibility->assessment_date
                                                ? $feasibility->assessment_date->format('d-m-Y')
                                                : '-'
                                            }}

                                        </td>


                                        <td>

                                            <a
                                                href="{{ route(
                                                    'admin.land.lands.feasibility-assessments.show',
                                                    [
                                                        $land,
                                                        $feasibility
                                                    ]
                                                ) }}"
                                                class="btn btn-sm btn-outline-primary"
                                            >
                                                View
                                            </a>


                                            <a
                                                href="{{ route(
                                                    'admin.land.lands.feasibility-assessments.edit',
                                                    [
                                                        $land,
                                                        $feasibility
                                                    ]
                                                ) }}"
                                                class="btn btn-sm btn-outline-secondary"
                                            >
                                                Edit
                                            </a>

                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td
                                            colspan="6"
                                            class="text-center py-4"
                                        >

                                            No feasibility assessments found.

                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>


        </div>

    </div>

</div>

@endsection