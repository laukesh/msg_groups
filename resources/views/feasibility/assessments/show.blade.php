@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3>
                {{ $feasibilityAssessment->title }}
            </h3>

            <p class="text-muted mb-0">

                {{ $feasibilityAssessment->assessment_number }}

                |

                {{ $land->land_code }}

            </p>

        </div>


        <!-- <div>

            <a
                href="{{ route(
                    'admin.land.lands.feasibility-assessments.edit',
                    [
                        $land,
                        $feasibilityAssessment
                    ]
                ) }}"
                class="btn btn-primary"
            >
                Edit
            </a>


            <a
                href="{{ route(
                    'admin.land.lands.feasibility-assessments.index',
                    $land
                ) }}"
                class="btn btn-secondary"
            >
                Back
            </a>

        </div> -->

        <div class="d-flex gap-2">

            {{-- Investment Decision --}}

            <a
                href="{{ route(
                    'admin.land.lands.feasibility-assessments.investment-decisions.index',
                    [
                        'land' => $land,
                        'feasibilityAssessment' => $feasibilityAssessment
                    ]
                ) }}"
                class="btn btn-success"
            >
                <i class="ri-checkbox-circle-line me-1"></i>
                Investment Decision
            </a>


            {{-- Edit --}}

            <a
                href="{{ route(
                    'admin.land.lands.feasibility-assessments.edit',
                    [
                        'land' => $land,
                        'feasibilityAssessment' => $feasibilityAssessment
                    ]
                ) }}"
                class="btn btn-outline-primary"
            >
                <i class="ri-edit-line me-1"></i>
                Edit
            </a>


            {{-- Back --}}

            <a
                href="{{ route(
                    'admin.land.lands.feasibility-assessments.index',
                    $land
                ) }}"
                class="btn btn-secondary"
            >
                <i class="ri-arrow-left-line me-1"></i>
                Back
            </a>

        </div>



    </div>


    {{-- STATUS --}}

    <div class="alert alert-info">

        <strong>
            Status:
        </strong>

        {{ $feasibilityAssessment->status }}

    </div>


    {{-- BASIC INFORMATION --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Assessment Information</strong>
        </div>


        <div class="card-body">

            <div class="row">

                <div class="col-md-4 mb-3">

                    <small class="text-muted">
                        Assessment Number
                    </small>

                    <div>
                        {{ $feasibilityAssessment->assessment_number }}
                    </div>

                </div>


                <div class="col-md-4 mb-3">

                    <small class="text-muted">
                        Development Type
                    </small>

                    <div>
                        {{ $feasibilityAssessment->development_type ?? '-' }}
                    </div>

                </div>


                <div class="col-md-4 mb-3">

                    <small class="text-muted">
                        Assessment Date
                    </small>

                    <div>

                        {{ $feasibilityAssessment->assessment_date
                            ? $feasibilityAssessment
                                ->assessment_date
                                ->format('d-m-Y')
                            : '-'
                        }}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- PROJECT CONCEPT --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Project Concept</strong>
        </div>

        <div class="card-body">

            {!! nl2br(
                e(
                    $feasibilityAssessment
                        ->project_concept ?? '-'
                )
            ) !!}

        </div>

    </div>


    {{-- SUMMARY --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Feasibility Summary</strong>
        </div>

        <div class="card-body">

            {!! nl2br(
                e(
                    $feasibilityAssessment
                        ->summary ?? '-'
                )
            ) !!}

        </div>

    </div>


    {{-- ASSUMPTIONS / RISKS --}}

    <div class="row">

        <div class="col-md-6">

            <div class="card mb-4">

                <div class="card-header">
                    <strong>Key Assumptions</strong>
                </div>

                <div class="card-body">

                    {!! nl2br(
                        e(
                            $feasibilityAssessment
                                ->key_assumptions ?? '-'
                        )
                    ) !!}

                </div>

            </div>

        </div>


        <div class="col-md-6">

            <div class="card mb-4">

                <div class="card-header">
                    <strong>Key Risks</strong>
                </div>

                <div class="card-body">

                    {!! nl2br(
                        e(
                            $feasibilityAssessment
                                ->key_risks ?? '-'
                        )
                    ) !!}

                </div>

            </div>

        </div>

    </div>


    {{-- RECOMMENDATION --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Recommendation</strong>
        </div>

        <div class="card-body">

            {!! nl2br(
                e(
                    $feasibilityAssessment
                        ->recommendation ?? '-'
                )
            ) !!}

        </div>

    </div>

    <div class="card mb-4">

        <div class="card-header">
            <strong>Source Land</strong>
        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-4">

                    <small class="text-muted">
                        Land Code
                    </small>

                    <div class="fw-semibold">
                        {{ $land->land_code }}
                    </div>

                </div>


                <div class="col-md-4">

                    <small class="text-muted">
                        Land Name
                    </small>

                    <div>
                        {{ $land->land_name }}
                    </div>

                </div>


                <div class="col-md-4">

                    <small class="text-muted">
                        Acquisition Status
                    </small>

                    <div>
                        {{ $land->acquisition_status }}
                    </div>

                </div>

            </div>

        </div>

    </div>
    

    <div class="card mb-4">

        <div class="card-header d-flex justify-content-between align-items-center">

            <div>
                <strong>Market Studies</strong>

                <div class="small text-muted">
                    Market studies associated with this feasibility assessment
                </div>
            </div>

            <a
                href="{{ route(
                    'admin.land.lands.feasibility-assessments.market-studies.create',
                    [
                        'land' => $land->id,
                        'feasibilityAssessment' => $feasibilityAssessment->id,
                    ]
                ) }}"
                class="btn btn-sm btn-primary"
            >
                + Add Market Study
            </a>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover table-bordered mb-0 align-middle">

                    <thead class="table-light">

                        <tr>

                            <th style="width: 150px;">
                                Study Number
                            </th>

                            <th>
                                Title
                            </th>

                            <th>
                                Market Segment
                            </th>

                            <th>
                                Market Location
                            </th>

                            <th style="width: 120px;">
                                Study Date
                            </th>

                            <th style="width: 110px;">
                                Status
                            </th>

                            <th style="width: 180px;">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                    @forelse($feasibilityAssessment->marketStudies as $study)

                        <tr>

                            {{-- Study Number --}}
                            <td>

                                <strong>
                                    {{ $study->study_number }}
                                </strong>

                            </td>


                            {{-- Title --}}
                            <td>

                                {{ $study->title }}

                            </td>


                            {{-- Market Segment --}}
                            <td>

                                {{ $study->market_segment ?? '-' }}

                            </td>


                            {{-- Market Location --}}
                            <td>

                                {{ $study->market_location ?? '-' }}

                            </td>


                            {{-- Study Date --}}
                            <td>

                                @if($study->study_date)

                                    {{ \Carbon\Carbon::parse($study->study_date)->format('d-m-Y') }}

                                @else

                                    -

                                @endif

                            </td>


                            {{-- Status --}}
                            <td>

                                @if($study->status === 'Draft')

                                    <span class="badge bg-secondary">
                                        Draft
                                    </span>

                                @elseif($study->status === 'Submitted')

                                    <span class="badge bg-warning text-dark">
                                        Submitted
                                    </span>

                                @elseif($study->status === 'Approved')

                                    <span class="badge bg-success">
                                        Approved
                                    </span>

                                @elseif($study->status === 'Rejected')

                                    <span class="badge bg-danger">
                                        Rejected
                                    </span>

                                @else

                                    <span class="badge bg-secondary">
                                        {{ $study->status ?? 'N/A' }}
                                    </span>

                                @endif

                            </td>


                            {{-- Actions --}}
                            <td>

                                <div class="d-flex gap-1">

                                    {{-- View --}}
                                    <a
                                        href="{{ route(
                                            'admin.land.lands.feasibility-assessments.market-studies.show',
                                            [
                                                'land' => $land->id,
                                                'feasibilityAssessment' => $feasibilityAssessment->id,
                                                'marketStudy' => $study->id,
                                            ]
                                        ) }}"
                                        class="btn btn-sm btn-outline-primary"
                                    >
                                        View
                                    </a>


                                    {{-- Edit --}}
                                    <a
                                        href="{{ route(
                                            'admin.land.lands.feasibility-assessments.market-studies.edit',
                                            [
                                                'land' => $land->id,
                                                'feasibilityAssessment' => $feasibilityAssessment->id,
                                                'marketStudy' => $study->id,
                                            ]
                                        ) }}"
                                        class="btn btn-sm btn-outline-secondary"
                                    >
                                        Edit
                                    </a>


                                    {{-- Delete --}}
                                    <form
                                        action="{{ route(
                                            'admin.land.lands.feasibility-assessments.market-studies.destroy',
                                            [
                                                'land' => $land->id,
                                                'feasibilityAssessment' => $feasibilityAssessment->id,
                                                'marketStudy' => $study->id,
                                            ]
                                        ) }}"
                                        method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('Are you sure you want to delete this market study?');"
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

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="7"
                                class="text-center py-5"
                            >

                                <div class="text-muted mb-3">
                                    No market studies found.
                                </div>

                                <a
                                    href="{{ route(
                                        'admin.land.lands.feasibility-assessments.market-studies.create',
                                        [
                                            'land' => $land->id,
                                            'feasibilityAssessment' => $feasibilityAssessment->id,
                                        ]
                                    ) }}"
                                    class="btn btn-sm btn-primary"
                                >
                                    + Create Market Study
                                </a>

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- If pagination is being used --}}
        @if(isset($marketStudies) && method_exists($marketStudies, 'links'))

            @if($marketStudies->hasPages())

                <div class="card-footer">

                    {{ $marketStudies->links() }}

                </div>

            @endif

        @endif

    </div>

    {{-- ================================================================ --}}
{{-- Location Analysis --}}
{{-- ================================================================ --}}

<div class="card mb-4">

    <div class="card-header d-flex justify-content-between align-items-center">

        <div>

            <strong>
                Location Analysis
            </strong>

            <div class="small text-muted">
                Assessment of accessibility, connectivity,
                surrounding development, competition and location suitability.
            </div>

        </div>


        {{-- Add Location Analysis --}}
        <a
            href="{{ route(
                'admin.land.lands.feasibility-assessments.location-analyses.create',
                [
                    'land' => $land->id,
                    'feasibilityAssessment' =>
                        $feasibilityAssessment->id,
                ]
            ) }}"
            class="btn btn-sm btn-primary"
        >
            + Add Location Analysis
        </a>

    </div>


    <div class="card-body p-0">

        <div class="table-responsive">

            <table class="table table-hover table-bordered mb-0 align-middle">

                <thead class="table-light">

                    <tr>

                        <th style="width: 150px;">
                            Analysis Number
                        </th>

                        <th>
                            Title
                        </th>

                        <th>
                            Location Type
                        </th>

                        <th>
                            Score
                        </th>

                        <th>
                            Status
                        </th>

                        <th style="width: 190px;">
                            Action
                        </th>

                    </tr>

                </thead>


                <tbody>

                @forelse($feasibilityAssessment->locationAnalyses as $analysis)

                    <tr>

                        {{-- Analysis Number --}}
                        <td>

                            <strong>
                                {{ $analysis->analysis_number }}
                            </strong>

                        </td>


                        {{-- Title --}}
                        <td>

                            {{ $analysis->title }}

                        </td>


                        {{-- Location Type --}}
                        <td>

                            {{ $analysis->location_type ?? '-' }}

                        </td>


                        {{-- Score --}}
                        <td>

                            @if(
                                $analysis->overall_location_score !== null
                            )

                                <span class="fw-semibold">

                                    {{ number_format(
                                        $analysis->overall_location_score,
                                        2
                                    ) }}

                                </span>

                                <span class="text-muted">
                                    / 100
                                </span>

                            @else

                                -

                            @endif

                        </td>


                        {{-- Status --}}
                        <td>

                            @if($analysis->status === 'Draft')

                                <span class="badge bg-secondary">
                                    Draft
                                </span>

                            @elseif($analysis->status === 'Submitted')

                                <span class="badge bg-warning text-dark">
                                    Submitted
                                </span>

                            @elseif($analysis->status === 'Approved')

                                <span class="badge bg-success">
                                    Approved
                                </span>

                            @elseif($analysis->status === 'Rejected')

                                <span class="badge bg-danger">
                                    Rejected
                                </span>

                            @else

                                <span class="badge bg-secondary">
                                    {{ $analysis->status ?? 'N/A' }}
                                </span>

                            @endif

                        </td>


                        {{-- Actions --}}
                        <td>

                            <div class="d-flex gap-1">

                                {{-- View --}}
                                <a
                                    href="{{ route(
                                        'admin.land.lands.feasibility-assessments.location-analyses.show',
                                        [
                                            'land' => $land->id,

                                            'feasibilityAssessment' =>
                                                $feasibilityAssessment->id,

                                            'locationAnalysis' =>
                                                $analysis->id,
                                        ]
                                    ) }}"
                                    class="btn btn-sm btn-outline-primary"
                                >
                                    View
                                </a>


                                {{-- Edit --}}
                                <a
                                    href="{{ route(
                                        'admin.land.lands.feasibility-assessments.location-analyses.edit',
                                        [
                                            'land' => $land->id,

                                            'feasibilityAssessment' =>
                                                $feasibilityAssessment->id,

                                            'locationAnalysis' =>
                                                $analysis->id,
                                        ]
                                    ) }}"
                                    class="btn btn-sm btn-outline-secondary"
                                >
                                    Edit
                                </a>


                                {{-- Delete --}}
                                <form
                                    action="{{ route(
                                        'admin.land.lands.feasibility-assessments.location-analyses.destroy',
                                        [
                                            'land' => $land->id,

                                            'feasibilityAssessment' =>
                                                $feasibilityAssessment->id,

                                            'locationAnalysis' =>
                                                $analysis->id,
                                        ]
                                    ) }}"
                                    method="POST"
                                    class="d-inline"
                                    onsubmit="return confirm('Are you sure you want to delete this location analysis?');"
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

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="6"
                            class="text-center py-5"
                        >

                            <div class="text-muted mb-3">

                                No location analysis has been added
                                to this feasibility assessment.

                            </div>


                            <a
                                href="{{ route(
                                    'admin.land.lands.feasibility-assessments.location-analyses.create',
                                    [
                                        'land' => $land->id,

                                        'feasibilityAssessment' =>
                                            $feasibilityAssessment->id,
                                    ]
                                ) }}"
                                class="btn btn-sm btn-primary"
                            >
                                + Create Location Analysis
                            </a>

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

{{-- ================================================================ --}}
{{-- Demand & Supply Analysis --}}
{{-- ================================================================ --}}

<div class="card mb-4">

    <div class="card-header d-flex justify-content-between align-items-center">

        <div>

            <strong>
                Demand & Supply Analysis
            </strong>

            <div class="small text-muted">
                Assessment of market demand, existing supply,
                future supply, demand-supply gap and market capacity.
            </div>

        </div>


        {{-- Add Demand & Supply Analysis --}}
        <a
            href="{{ route(
                'admin.land.lands.feasibility-assessments.demand-supply-analyses.create',
                [
                    'land' => $land->id,
                    'feasibilityAssessment' =>
                        $feasibilityAssessment->id,
                ]
            ) }}"
            class="btn btn-sm btn-primary"
        >
            + Add Demand & Supply Analysis
        </a>

    </div>


    <div class="card-body p-0">

        <div class="table-responsive">

            <table class="table table-hover table-bordered mb-0 align-middle">

                <thead class="table-light">

                    <tr>

                        <th style="width: 150px;">
                            Analysis Number
                        </th>

                        <th>
                            Title
                        </th>

                        <th>
                            Market Type
                        </th>

                        <th>
                            Demand
                        </th>

                        <th>
                            Supply
                        </th>

                        <th>
                            Gap
                        </th>

                        <th>
                            Score
                        </th>

                        <th>
                            Status
                        </th>

                        <th style="width: 190px;">
                            Action
                        </th>

                    </tr>

                </thead>


                <tbody>

                @forelse(
                   $feasibilityAssessment->demandSupplyAnalyses as $analysis
                )

                    <tr>

                        {{-- Analysis Number --}}
                        <td>

                            <strong>
                                {{ $analysis->analysis_number }}
                            </strong>

                        </td>


                        {{-- Title --}}
                        <td>

                            {{ $analysis->title }}

                        </td>


                        {{-- Market Type --}}
                        <td>

                            {{ $analysis->market_type ?? '-' }}

                        </td>


                        {{-- Demand --}}
                        <td>

                            @if(
                                $analysis->projected_demand !== null
                            )

                                {{ number_format(
                                    $analysis->projected_demand,
                                    2
                                ) }}

                            @elseif(
                                $analysis->current_demand !== null
                            )

                                {{ number_format(
                                    $analysis->current_demand,
                                    2
                                ) }}

                            @else

                                -

                            @endif

                        </td>


                        {{-- Supply --}}
                        <td>

                            @if(
                                $analysis->future_supply !== null
                            )

                                {{ number_format(
                                    $analysis->future_supply,
                                    2
                                ) }}

                            @elseif(
                                $analysis->current_supply !== null
                            )

                                {{ number_format(
                                    $analysis->current_supply,
                                    2
                                ) }}

                            @else

                                -

                            @endif

                        </td>


                        {{-- Demand Supply Gap --}}
                        <td>

                            @if(
                                $analysis->demand_supply_gap !== null
                            )

                                <span
                                    class="
                                        fw-semibold
                                        {{
                                            $analysis->demand_supply_gap > 0
                                                ? 'text-success'
                                                : (
                                                    $analysis->demand_supply_gap < 0
                                                        ? 'text-danger'
                                                        : 'text-muted'
                                                )
                                        }}
                                    "
                                >

                                    {{ number_format(
                                        $analysis->demand_supply_gap,
                                        2
                                    ) }}

                                </span>

                            @else

                                -

                            @endif

                        </td>


                        {{-- Score --}}
                        <td>

                            @if(
                                $analysis
                                    ->overall_demand_supply_score
                                    !== null
                            )

                                <strong>

                                    {{ number_format(
                                        $analysis
                                            ->overall_demand_supply_score,
                                        2
                                    ) }}

                                </strong>

                                <span class="text-muted">
                                    / 100
                                </span>

                            @else

                                -

                            @endif

                        </td>


                        {{-- Status --}}
                        <td>

                            @if($analysis->status === 'Draft')

                                <span class="badge bg-secondary">
                                    Draft
                                </span>

                            @elseif($analysis->status === 'Submitted')

                                <span class="badge bg-warning text-dark">
                                    Submitted
                                </span>

                            @elseif($analysis->status === 'Approved')

                                <span class="badge bg-success">
                                    Approved
                                </span>

                            @elseif($analysis->status === 'Rejected')

                                <span class="badge bg-danger">
                                    Rejected
                                </span>

                            @else

                                <span class="badge bg-secondary">
                                    {{ $analysis->status ?? 'N/A' }}
                                </span>

                            @endif

                        </td>


                        {{-- Actions --}}
                        <td>

                            <div class="d-flex gap-1">

                                {{-- View --}}
                                <a
                                    href="{{ route(
                                        'admin.land.lands.feasibility-assessments.demand-supply-analyses.show',
                                        [
                                            'land' => $land->id,

                                            'feasibilityAssessment' =>
                                                $feasibilityAssessment->id,

                                            'demandSupplyAnalysis' =>
                                                $analysis->id,
                                        ]
                                    ) }}"
                                    class="btn btn-sm btn-outline-primary"
                                >
                                    View
                                </a>


                                {{-- Edit --}}
                                <a
                                    href="{{ route(
                                        'admin.land.lands.feasibility-assessments.demand-supply-analyses.edit',
                                        [
                                            'land' => $land->id,

                                            'feasibilityAssessment' =>
                                                $feasibilityAssessment->id,

                                            'demandSupplyAnalysis' =>
                                                $analysis->id,
                                        ]
                                    ) }}"
                                    class="btn btn-sm btn-outline-secondary"
                                >
                                    Edit
                                </a>


                                {{-- Delete --}}
                                <form
                                    action="{{ route(
                                        'admin.land.lands.feasibility-assessments.demand-supply-analyses.destroy',
                                        [
                                            'land' => $land->id,

                                            'feasibilityAssessment' =>
                                                $feasibilityAssessment->id,

                                            'demandSupplyAnalysis' =>
                                                $analysis->id,
                                        ]
                                    ) }}"
                                    method="POST"
                                    class="d-inline"
                                    onsubmit="return confirm('Are you sure you want to delete this Demand & Supply Analysis?');"
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

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="9"
                            class="text-center py-5"
                        >

                            <div class="text-muted mb-3">

                                No Demand & Supply Analysis
                                has been added to this
                                feasibility assessment.

                            </div>


                            <a
                                href="{{ route(
                                    'admin.land.lands.feasibility-assessments.demand-supply-analyses.create',
                                    [
                                        'land' => $land->id,

                                        'feasibilityAssessment' =>
                                            $feasibilityAssessment->id,
                                    ]
                                ) }}"
                                class="btn btn-sm btn-primary"
                            >
                                + Create Demand & Supply Analysis
                            </a>

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

{{-- ================================================================ --}}
{{-- Financial Feasibility --}}
{{-- ================================================================ --}}

<div class="card mb-4">

    <div class="card-header d-flex justify-content-between align-items-center">

        <div>

            <strong>
                Financial Feasibility
            </strong>

            <div class="small text-muted">
                Project cost, revenue, profitability,
                investment returns and financial viability.
            </div>

        </div>


        <a
            href="{{ route(
                'admin.land.lands.feasibility-assessments.financial-feasibilities.create',
                [
                    'land' => $land->id,

                    'feasibilityAssessment' =>
                        $feasibilityAssessment->id,
                ]
            ) }}"
            class="btn btn-sm btn-primary"
        >
            + Add Financial Feasibility
        </a>

    </div>


    <div class="card-body p-0">

        <div class="table-responsive">

            <table class="table table-hover table-bordered mb-0 align-middle">

                <thead class="table-light">

                    <tr>

                        <th>
                            Analysis Number
                        </th>

                        <th>
                            Title
                        </th>

                        <th>
                            Project Cost
                        </th>

                        <th>
                            Revenue
                        </th>

                        <th>
                            Net Profit
                        </th>

                        <th>
                            ROI
                        </th>

                        <th>
                            IRR
                        </th>

                        <th>
                            Score
                        </th>

                        <th>
                            Status
                        </th>

                        <th style="width: 210px;">
                            Action
                        </th>

                    </tr>

                </thead>


                <tbody>

                @forelse(
                    $feasibilityAssessment->financialFeasibilities as $financial
                )

                    <tr>

                        {{-- ================================================= --}}
                        {{-- Analysis Number --}}
                        {{-- ================================================= --}}

                        <td>

                            <strong>
                                {{ $financial->analysis_number }}
                            </strong>

                        </td>


                        {{-- ================================================= --}}
                        {{-- Title --}}
                        {{-- ================================================= --}}

                        <td>

                            {{ $financial->title }}

                        </td>


                        {{-- ================================================= --}}
                        {{-- Project Cost --}}
                        {{-- ================================================= --}}

                        <td>

                            @if(
                                $financial->total_project_cost !== null
                            )

                                {{ number_format(
                                    $financial->total_project_cost,
                                    2
                                ) }}

                            @else

                                -

                            @endif

                        </td>


                        {{-- ================================================= --}}
                        {{-- Revenue --}}
                        {{-- ================================================= --}}

                        <td>

                            @if(
                                $financial->total_revenue !== null
                            )

                                {{ number_format(
                                    $financial->total_revenue,
                                    2
                                ) }}

                            @else

                                -

                            @endif

                        </td>


                        {{-- ================================================= --}}
                        {{-- Net Profit --}}
                        {{-- ================================================= --}}

                        <td>

                            @if(
                                $financial->net_profit !== null
                            )

                                <span
                                    class="fw-semibold
                                    {{
                                        $financial->net_profit >= 0
                                            ? 'text-success'
                                            : 'text-danger'
                                    }}"
                                >

                                    {{ number_format(
                                        $financial->net_profit,
                                        2
                                    ) }}

                                </span>

                            @else

                                -

                            @endif

                        </td>


                        {{-- ================================================= --}}
                        {{-- ROI --}}
                        {{-- ================================================= --}}

                        <td>

                            @if($financial->roi !== null)

                                {{ number_format(
                                    $financial->roi,
                                    2
                                ) }}%

                            @else

                                -

                            @endif

                        </td>


                        {{-- ================================================= --}}
                        {{-- IRR --}}
                        {{-- ================================================= --}}

                        <td>

                            @if($financial->irr !== null)

                                {{ number_format(
                                    $financial->irr,
                                    2
                                ) }}%

                            @else

                                -

                            @endif

                        </td>


                        {{-- ================================================= --}}
                        {{-- Score --}}
                        {{-- ================================================= --}}

                        <td>

                            @if(
                                $financial
                                    ->overall_financial_score
                                    !== null
                            )

                                <strong>

                                    {{ number_format(
                                        $financial
                                            ->overall_financial_score,
                                        2
                                    ) }}

                                </strong>

                                <span class="text-muted">
                                    / 100
                                </span>

                            @else

                                -

                            @endif

                        </td>


                        {{-- ================================================= --}}
                        {{-- Status --}}
                        {{-- ================================================= --}}

                        <td>

                            @if($financial->status === 'Draft')

                                <span class="badge bg-secondary">
                                    Draft
                                </span>

                            @elseif($financial->status === 'Submitted')

                                <span class="badge bg-warning text-dark">
                                    Submitted
                                </span>

                            @elseif($financial->status === 'Approved')

                                <span class="badge bg-success">
                                    Approved
                                </span>

                            @elseif($financial->status === 'Rejected')

                                <span class="badge bg-danger">
                                    Rejected
                                </span>

                            @else

                                <span class="badge bg-secondary">
                                    {{ $financial->status ?? 'N/A' }}
                                </span>

                            @endif

                        </td>


                        {{-- ================================================= --}}
                        {{-- Actions --}}
                        {{-- ================================================= --}}

                        <td>

                            <div class="d-flex gap-1">

                                {{-- View --}}
                                <a
                                    href="{{ route(
                                        'admin.land.lands.feasibility-assessments.financial-feasibilities.show',
                                        [
                                            'land' => $land->id,

                                            'feasibilityAssessment' =>
                                                $feasibilityAssessment->id,

                                            'financialFeasibility' =>
                                                $financial->id,
                                        ]
                                    ) }}"
                                    class="btn btn-sm btn-outline-primary"
                                >
                                    View
                                </a>


                                {{-- Edit --}}
                                <a
                                    href="{{ route(
                                        'admin.land.lands.feasibility-assessments.financial-feasibilities.edit',
                                        [
                                            'land' => $land->id,

                                            'feasibilityAssessment' =>
                                                $feasibilityAssessment->id,

                                            'financialFeasibility' =>
                                                $financial->id,
                                        ]
                                    ) }}"
                                    class="btn btn-sm btn-outline-secondary"
                                >
                                    Edit
                                </a>


                                {{-- Delete --}}
                                <form
                                    action="{{ route(
                                        'admin.land.lands.feasibility-assessments.financial-feasibilities.destroy',
                                        [
                                            'land' => $land->id,

                                            'feasibilityAssessment' =>
                                                $feasibilityAssessment->id,

                                            'financialFeasibility' =>
                                                $financial->id,
                                        ]
                                    ) }}"
                                    method="POST"
                                    class="d-inline"
                                    onsubmit="return confirm('Are you sure you want to delete this Financial Feasibility?');"
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

                            </div>

                        </td>

                    </tr>


                @empty

                    <tr>

                        <td
                            colspan="10"
                            class="text-center py-5"
                        >

                            <div class="text-muted mb-3">

                                No Financial Feasibility
                                has been added to this
                                assessment.

                            </div>


                            <a
                                href="{{ route(
                                    'admin.land.lands.feasibility-assessments.financial-feasibilities.create',
                                    [
                                        'land' => $land->id,

                                        'feasibilityAssessment' =>
                                            $feasibilityAssessment->id,
                                    ]
                                ) }}"
                                class="btn btn-sm btn-primary"
                            >
                                + Create Financial Feasibility
                            </a>

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

{{-- ================================================================ --}}
{{-- Legal & Regulatory Feasibility --}}
{{-- ================================================================ --}}

<div class="card mb-4">

    <div class="card-header d-flex justify-content-between align-items-center">

        <div>

            <strong>
                Legal & Regulatory Feasibility
            </strong>

            <div class="small text-muted">
                Ownership, title, zoning, approvals,
                statutory compliance and legal risks.
            </div>

        </div>


        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.land.lands.feasibility-assessments.legal-regulatory-feasibilities.index',
                    [
                        'land' => $land->id,

                        'feasibilityAssessment' =>
                            $feasibilityAssessment->id,
                    ]
                ) }}"
                class="btn btn-sm btn-outline-secondary"
            >
                View All
            </a>


            <a
                href="{{ route(
                    'admin.land.lands.feasibility-assessments.legal-regulatory-feasibilities.create',
                    [
                        'land' => $land->id,

                        'feasibilityAssessment' =>
                            $feasibilityAssessment->id,
                    ]
                ) }}"
                class="btn btn-sm btn-primary"
            >
                + Add Legal Analysis
            </a>

        </div>

    </div>


    <div class="card-body p-0">

        <div class="table-responsive">

            <table class="table table-hover table-bordered mb-0 align-middle">

                <thead class="table-light">

                    <tr>

                        <th>
                            Analysis Number
                        </th>

                        <th>
                            Title
                        </th>

                        <th>
                            Ownership
                        </th>

                        <th>
                            Title
                        </th>

                        <th>
                            Zoning
                        </th>

                        <th>
                            Compliance
                        </th>

                        <th>
                            Legal Score
                        </th>

                        <th>
                            Status
                        </th>

                        <th style="width: 210px;">
                            Action
                        </th>

                    </tr>

                </thead>


                <tbody>

                @forelse(
                    $feasibilityAssessment->legalRegulatoryFeasibilities
                    as $legal
                )

                    <tr>

                        {{-- ================================================= --}}
                        {{-- Analysis Number --}}
                        {{-- ================================================= --}}

                        <td>

                            <strong>
                                {{ $legal->analysis_number }}
                            </strong>

                        </td>


                        {{-- ================================================= --}}
                        {{-- Title --}}
                        {{-- ================================================= --}}

                        <td>
                            {{ $legal->title }}
                        </td>


                        {{-- ================================================= --}}
                        {{-- Ownership --}}
                        {{-- ================================================= --}}

                        <td>

                            @if(
                                $legal->ownership_status
                            )

                                @if(
                                    $legal->ownership_status === 'Clear'
                                )

                                    <span class="badge bg-success">
                                        Clear
                                    </span>

                                @elseif(
                                    $legal->ownership_status === 'Disputed'
                                )

                                    <span class="badge bg-danger">
                                        Disputed
                                    </span>

                                @elseif(
                                    $legal->ownership_status === 'Pending'
                                )

                                    <span class="badge bg-warning text-dark">
                                        Pending
                                    </span>

                                @else

                                    <span class="badge bg-secondary">
                                        {{ $legal->ownership_status }}
                                    </span>

                                @endif

                            @else

                                -

                            @endif

                        </td>


                        {{-- ================================================= --}}
                        {{-- Title Verification --}}
                        {{-- ================================================= --}}

                        <td>

                            @if(
                                $legal->title_verification_status
                            )

                                @if(
                                    $legal->title_verification_status === 'Verified'
                                )

                                    <span class="badge bg-success">
                                        Verified
                                    </span>

                                @elseif(
                                    $legal->title_verification_status === 'Issue Found'
                                )

                                    <span class="badge bg-danger">
                                        Issue Found
                                    </span>

                                @elseif(
                                    $legal->title_verification_status === 'Pending'
                                )

                                    <span class="badge bg-warning text-dark">
                                        Pending
                                    </span>

                                @else

                                    <span class="badge bg-secondary">
                                        {{
                                            $legal
                                                ->title_verification_status
                                        }}
                                    </span>

                                @endif

                            @else

                                -

                            @endif

                        </td>


                        {{-- ================================================= --}}
                        {{-- Zoning --}}
                        {{-- ================================================= --}}

                        <td>

                            @if(
                                $legal->zoning_type
                            )

                                <strong>
                                    {{ $legal->zoning_type }}
                                </strong>

                            @else

                                -

                            @endif


                            @if(
                                $legal->zoning_status
                            )

                                <div class="small text-muted">

                                    {{ $legal->zoning_status }}

                                </div>

                            @endif

                        </td>


                        {{-- ================================================= --}}
                        {{-- Compliance --}}
                        {{-- ================================================= --}}

                        <td>

                            @if(
                                $legal->compliance_status
                            )

                                @if(
                                    $legal->compliance_status === 'Compliant'
                                )

                                    <span class="badge bg-success">
                                        Compliant
                                    </span>

                                @elseif(
                                    $legal->compliance_status === 'Non-Compliant'
                                )

                                    <span class="badge bg-danger">
                                        Non-Compliant
                                    </span>

                                @elseif(
                                    $legal->compliance_status === 'Partially Compliant'
                                )

                                    <span class="badge bg-warning text-dark">
                                        Partially Compliant
                                    </span>

                                @else

                                    <span class="badge bg-secondary">
                                        {{
                                            $legal->compliance_status
                                        }}
                                    </span>

                                @endif

                            @else

                                -

                            @endif

                        </td>


                        {{-- ================================================= --}}
                        {{-- Legal Score --}}
                        {{-- ================================================= --}}

                        <td>

                            @if(
                                $legal->overall_legal_score !== null
                            )

                                <strong>

                                    {{
                                        number_format(
                                            $legal
                                                ->overall_legal_score,
                                            2
                                        )
                                    }}

                                </strong>

                                <span class="text-muted">
                                    / 100
                                </span>

                            @else

                                -

                            @endif

                        </td>


                        {{-- ================================================= --}}
                        {{-- Status --}}
                        {{-- ================================================= --}}

                        <td>

                            @if(
                                $legal->status === 'Draft'
                            )

                                <span class="badge bg-secondary">
                                    Draft
                                </span>

                            @elseif(
                                $legal->status === 'Submitted'
                            )

                                <span class="badge bg-warning text-dark">
                                    Submitted
                                </span>

                            @elseif(
                                $legal->status === 'Approved'
                            )

                                <span class="badge bg-success">
                                    Approved
                                </span>

                            @elseif(
                                $legal->status === 'Rejected'
                            )

                                <span class="badge bg-danger">
                                    Rejected
                                </span>

                            @else

                                <span class="badge bg-secondary">
                                    {{ $legal->status ?? 'N/A' }}
                                </span>

                            @endif

                        </td>


                        {{-- ================================================= --}}
                        {{-- Actions --}}
                        {{-- ================================================= --}}

                        <td>

                            <div class="d-flex gap-1">

                                {{-- View --}}
                                <a
                                    href="{{ route(
                                        'admin.land.lands.feasibility-assessments.legal-regulatory-feasibilities.show',
                                        [
                                            'land' => $land->id,

                                            'feasibilityAssessment' =>
                                                $feasibilityAssessment->id,

                                            'legalRegulatoryFeasibility' =>
                                                $legal->id,
                                        ]
                                    ) }}"
                                    class="btn btn-sm btn-outline-primary"
                                >
                                    View
                                </a>


                                {{-- Edit --}}
                                <a
                                    href="{{ route(
                                        'admin.land.lands.feasibility-assessments.legal-regulatory-feasibilities.edit',
                                        [
                                            'land' => $land->id,

                                            'feasibilityAssessment' =>
                                                $feasibilityAssessment->id,

                                            'legalRegulatoryFeasibility' =>
                                                $legal->id,
                                        ]
                                    ) }}"
                                    class="btn btn-sm btn-outline-secondary"
                                >
                                    Edit
                                </a>


                                {{-- Delete --}}
                                <form
                                    action="{{ route(
                                        'admin.land.lands.feasibility-assessments.legal-regulatory-feasibilities.destroy',
                                        [
                                            'land' => $land->id,

                                            'feasibilityAssessment' =>
                                                $feasibilityAssessment->id,

                                            'legalRegulatoryFeasibility' =>
                                                $legal->id,
                                        ]
                                    ) }}"
                                    method="POST"
                                    class="d-inline"
                                    onsubmit="return confirm('Are you sure you want to delete this Legal & Regulatory Feasibility?');"
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

                            </div>

                        </td>

                    </tr>


                @empty

                    <tr>

                        <td
                            colspan="9"
                            class="text-center py-5"
                        >

                            <div class="text-muted mb-3">

                                No Legal & Regulatory
                                Feasibility has been added.

                            </div>


                            <a
                                href="{{ route(
                                    'admin.land.lands.feasibility-assessments.legal-regulatory-feasibilities.create',
                                    [
                                        'land' => $land->id,

                                        'feasibilityAssessment' =>
                                            $feasibilityAssessment->id,
                                    ]
                                ) }}"
                                class="btn btn-sm btn-primary"
                            >
                                + Create Legal Analysis
                            </a>

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

{{-- ========================================================= --}}
{{-- Technical Feasibility --}}
{{-- ========================================================= --}}

<div class="card mb-4">

    <div class="card-header d-flex justify-content-between align-items-center">

        <div>

            <strong>
                Technical Feasibility
            </strong>

            <div class="small text-muted">
                Technical assessment of the proposed project
            </div>

        </div>


        <a
            href="{{ route(
                'admin.land.lands.feasibility-assessments.technical-feasibilities.create',
                [
                    'land' => $land->id,

                    'feasibilityAssessment' =>
                        $feasibilityAssessment->id,
                ]
            ) }}"
            class="btn btn-sm btn-primary"
        >
            + Add Technical Feasibility
        </a>

    </div>


    <div class="card-body p-0">

        <div class="table-responsive">

            <table class="table table-hover table-bordered mb-0 align-middle">

                <thead class="table-light">

                    <tr>

                        <th>
                            Analysis Number
                        </th>

                        <th>
                            Title
                        </th>

                        <th>
                            Construction
                        </th>

                        <th>
                            Infrastructure
                        </th>

                        <th>
                            Technical Score
                        </th>

                        <th>
                            Status
                        </th>

                        <th style="width: 220px;">
                            Action
                        </th>

                    </tr>

                </thead>


                <tbody>

                @forelse(
                    $feasibilityAssessment->technicalFeasibilities
                    as $technical
                )

                    <tr>

                        {{-- Analysis Number --}}
                        <td>

                            <strong>
                                {{ $technical->analysis_number }}
                            </strong>

                        </td>


                        {{-- Title --}}
                        <td>
                            {{ $technical->title }}
                        </td>


                        {{-- Construction --}}
                        <td>

                            @if(
                                $technical
                                    ->construction_feasibility_status
                            )

                                <span class="badge bg-secondary">

                                    {{
                                        $technical
                                            ->construction_feasibility_status
                                    }}

                                </span>

                            @else

                                -

                            @endif

                        </td>


                        {{-- Infrastructure --}}
                        <td>

                            @if(
                                $technical
                                    ->infrastructure_status
                            )

                                <span class="badge bg-secondary">

                                    {{
                                        $technical
                                            ->infrastructure_status
                                    }}

                                </span>

                            @else

                                -

                            @endif

                        </td>


                        {{-- Score --}}
                        <td>

                            @if(
                                $technical
                                    ->overall_technical_score
                                    !== null
                            )

                                <strong>

                                    {{
                                        number_format(
                                            $technical
                                                ->overall_technical_score,
                                            2
                                        )
                                    }}

                                </strong>

                                <span class="text-muted">
                                    / 100
                                </span>

                            @else

                                -

                            @endif

                        </td>


                        {{-- Status --}}
                        <td>

                            @if(
                                $technical->status === 'Draft'
                            )

                                <span class="badge bg-secondary">
                                    Draft
                                </span>

                            @elseif(
                                $technical->status === 'Submitted'
                            )

                                <span class="badge bg-warning text-dark">
                                    Submitted
                                </span>

                            @elseif(
                                $technical->status === 'Approved'
                            )

                                <span class="badge bg-success">
                                    Approved
                                </span>

                            @elseif(
                                $technical->status === 'Rejected'
                            )

                                <span class="badge bg-danger">
                                    Rejected
                                </span>

                            @else

                                <span class="badge bg-secondary">
                                    {{ $technical->status }}
                                </span>

                            @endif

                        </td>


                        {{-- Actions --}}
                        <td>

                            <div class="d-flex gap-1">

                                {{-- View --}}
                                <a
                                    href="{{ route(
                                        'admin.land.lands.feasibility-assessments.technical-feasibilities.show',
                                        [
                                            'land' =>
                                                $land->id,

                                            'feasibilityAssessment' =>
                                                $feasibilityAssessment->id,

                                            'technicalFeasibility' =>
                                                $technical->id,
                                        ]
                                    ) }}"
                                    class="btn btn-sm btn-outline-primary"
                                >
                                    View
                                </a>


                                {{-- Edit --}}
                                <a
                                    href="{{ route(
                                        'admin.land.lands.feasibility-assessments.technical-feasibilities.edit',
                                        [
                                            'land' =>
                                                $land->id,

                                            'feasibilityAssessment' =>
                                                $feasibilityAssessment->id,

                                            'technicalFeasibility' =>
                                                $technical->id,
                                        ]
                                    ) }}"
                                    class="btn btn-sm btn-outline-secondary"
                                >
                                    Edit
                                </a>


                                {{-- Delete --}}
                                <form
                                    action="{{ route(
                                        'admin.land.lands.feasibility-assessments.technical-feasibilities.destroy',
                                        [
                                            'land' =>
                                                $land->id,

                                            'feasibilityAssessment' =>
                                                $feasibilityAssessment->id,

                                            'technicalFeasibility' =>
                                                $technical->id,
                                        ]
                                    ) }}"
                                    method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this Technical Feasibility?');"
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

                            </div>

                        </td>

                    </tr>


                @empty

                    <tr>

                        <td
                            colspan="7"
                            class="text-center py-4"
                        >

                            <div class="text-muted mb-2">

                                No technical feasibility
                                assessment has been added.

                            </div>


                            <a
                                href="{{ route(
                                    'admin.land.lands.feasibility-assessments.technical-feasibilities.create',
                                    [
                                        'land' => $land->id,

                                        'feasibilityAssessment' =>
                                            $feasibilityAssessment->id,
                                    ]
                                ) }}"
                                class="btn btn-sm btn-primary"
                            >
                                + Add Technical Feasibility
                            </a>

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

{{-- ========================================================= --}}
{{-- Environmental Feasibility --}}
{{-- ========================================================= --}}

<div class="card mb-4">

    <div class="card-header d-flex justify-content-between align-items-center">

        <div>

            <strong>
                Environmental Feasibility
            </strong>

            <div class="small text-muted">
                Environmental impact, sustainability,
                compliance and environmental risk assessment
            </div>

        </div>


        <a
            href="{{ route(
                'admin.land.lands.feasibility-assessments.environmental-feasibilities.create',
                [
                    'land' => $land->id,
                    'feasibilityAssessment' =>
                        $feasibilityAssessment->id,
                ]
            ) }}"
            class="btn btn-sm btn-primary"
        >
            + Add Environmental Feasibility
        </a>

    </div>


    <div class="card-body p-0">

        <div class="table-responsive">

            <table class="table table-hover table-bordered mb-0 align-middle">

                <thead class="table-light">

                    <tr>

                        <th>
                            Analysis Number
                        </th>

                        <th>
                            Title
                        </th>

                        <th>
                            Environmental Status
                        </th>

                        <th>
                            Clearance
                        </th>

                        <th>
                            Score
                        </th>

                        <th>
                            Status
                        </th>

                        <th style="width: 220px;">
                            Action
                        </th>

                    </tr>

                </thead>


                <tbody>

                @forelse(
                    $feasibilityAssessment->environmentalFeasibilities
                    as $environmental
                )

                    <tr>

                        {{-- Analysis Number --}}
                        <td>

                            <strong>
                                {{ $environmental->analysis_number }}
                            </strong>

                        </td>


                        {{-- Title --}}
                        <td>
                            {{ $environmental->title }}
                        </td>


                        {{-- Environmental Status --}}
                        <td>

                            @if(
                                $environmental
                                    ->environmental_status
                            )

                                <span class="badge bg-secondary">

                                    {{
                                        $environmental
                                            ->environmental_status
                                    }}

                                </span>

                            @else

                                -

                            @endif

                        </td>


                        {{-- Clearance --}}
                        <td>

                            @if(
                                $environmental
                                    ->environmental_clearance_status
                            )

                                <span class="badge bg-secondary">

                                    {{
                                        $environmental
                                            ->environmental_clearance_status
                                    }}

                                </span>

                            @else

                                -

                            @endif

                        </td>


                        {{-- Score --}}
                        <td>

                            @if(
                                $environmental
                                    ->overall_environmental_score
                                    !== null
                            )

                                <strong>

                                    {{
                                        number_format(
                                            $environmental
                                                ->overall_environmental_score,
                                            2
                                        )
                                    }}

                                </strong>

                                <span class="text-muted">
                                    / 100
                                </span>

                            @else

                                -

                            @endif

                        </td>


                        {{-- Status --}}
                        <td>

                            @switch(
                                $environmental->status
                            )

                                @case('Draft')

                                    <span class="badge bg-secondary">
                                        Draft
                                    </span>

                                    @break


                                @case('Submitted')

                                    <span class="badge bg-warning text-dark">
                                        Submitted
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

                                    <span class="badge bg-secondary">
                                        {{ $environmental->status }}
                                    </span>

                            @endswitch

                        </td>


                        {{-- Actions --}}
                        <td>

                            <div class="d-flex gap-1">

                                {{-- View --}}
                                <a
                                    href="{{ route(
                                        'admin.land.lands.feasibility-assessments.environmental-feasibilities.show',
                                        [
                                            'land' =>
                                                $land->id,

                                            'feasibilityAssessment' =>
                                                $feasibilityAssessment->id,

                                            'environmentalFeasibility' =>
                                                $environmental->id,
                                        ]
                                    ) }}"
                                    class="btn btn-sm btn-outline-primary"
                                >
                                    View
                                </a>


                                {{-- Edit --}}
                                <a
                                    href="{{ route(
                                        'admin.land.lands.feasibility-assessments.environmental-feasibilities.edit',
                                        [
                                            'land' =>
                                                $land->id,

                                            'feasibilityAssessment' =>
                                                $feasibilityAssessment->id,

                                            'environmentalFeasibility' =>
                                                $environmental->id,
                                        ]
                                    ) }}"
                                    class="btn btn-sm btn-outline-secondary"
                                >
                                    Edit
                                </a>


                                {{-- Delete --}}
                                <form
                                    action="{{ route(
                                        'admin.land.lands.feasibility-assessments.environmental-feasibilities.destroy',
                                        [
                                            'land' =>
                                                $land->id,

                                            'feasibilityAssessment' =>
                                                $feasibilityAssessment->id,

                                            'environmentalFeasibility' =>
                                                $environmental->id,
                                        ]
                                    ) }}"
                                    method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this Environmental Feasibility?');"
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

                            </div>

                        </td>

                    </tr>


                @empty

                    <tr>

                        <td
                            colspan="7"
                            class="text-center py-5"
                        >

                            <div class="text-muted mb-3">

                                No environmental feasibility
                                assessment has been added.

                            </div>


                            <a
                                href="{{ route(
                                    'admin.land.lands.feasibility-assessments.environmental-feasibilities.create',
                                    [
                                        'land' =>
                                            $land->id,

                                        'feasibilityAssessment' =>
                                            $feasibilityAssessment->id,
                                    ]
                                ) }}"
                                class="btn btn-sm btn-primary"
                            >
                                + Add Environmental Feasibility
                            </a>

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

{{-- ========================================================= --}}
{{-- Risk Assessment --}}
{{-- ========================================================= --}}

<div class="card mb-4">

    <div class="card-header d-flex justify-content-between align-items-center">

        <div>

            <strong>
                Risk Assessment
            </strong>

            <div class="small text-muted">
                Identification, evaluation and mitigation
                of project risks
            </div>

        </div>


        <a
            href="{{ route(
                'admin.land.lands.feasibility-assessments.risk-assessments.create',
                [
                    'land' => $land->id,
                    'feasibilityAssessment' =>
                        $feasibilityAssessment->id,
                ]
            ) }}"
            class="btn btn-sm btn-primary"
        >
            + Add Risk Assessment
        </a>

    </div>


    <div class="card-body p-0">

        <div class="table-responsive">

            <table class="table table-hover table-bordered mb-0 align-middle">

                <thead class="table-light">

                    <tr>

                        <th>
                            Analysis Number
                        </th>

                        <th>
                            Title
                        </th>

                        <th>
                            Overall Risk
                        </th>

                        <th>
                            Score
                        </th>

                        <th>
                            Critical Risks
                        </th>

                        <th>
                            Status
                        </th>

                        <th style="width: 230px;">
                            Action
                        </th>

                    </tr>

                </thead>


                <tbody>

                @forelse(
                    $feasibilityAssessment->riskAssessments as $risk
                )

                    <tr>

                        {{-- Analysis Number --}}
                        <td>

                            <strong>
                                {{ $risk->analysis_number }}
                            </strong>

                        </td>


                        {{-- Title --}}
                        <td>

                            {{ $risk->title }}

                        </td>


                        {{-- Overall Risk --}}
                        <td>

                            @if(
                                $risk->overall_risk_rating === 'Low'
                            )

                                <span class="badge bg-success">
                                    Low
                                </span>

                            @elseif(
                                $risk->overall_risk_rating === 'Medium'
                            )

                                <span class="badge bg-warning text-dark">
                                    Medium
                                </span>

                            @elseif(
                                $risk->overall_risk_rating === 'High'
                            )

                                <span class="badge bg-danger">
                                    High
                                </span>

                            @elseif(
                                $risk->overall_risk_rating === 'Critical'
                            )

                                <span class="badge bg-dark">
                                    Critical
                                </span>

                            @elseif(
                                $risk->overall_risk_rating
                            )

                                <span class="badge bg-secondary">
                                    {{ $risk->overall_risk_rating }}
                                </span>

                            @else

                                -

                            @endif

                        </td>


                        {{-- Score --}}
                        <td>

                            @if(
                                $risk->overall_risk_score !== null
                            )

                                <strong>

                                    {{
                                        number_format(
                                            $risk->overall_risk_score,
                                            2
                                        )
                                    }}

                                </strong>

                            @else

                                -

                            @endif

                        </td>


                        {{-- Critical Risks --}}
                        <td style="max-width: 250px;">

                            @if($risk->critical_risks)

                                <span
                                    title="{{ $risk->critical_risks }}"
                                >

                                    {{
                                        \Illuminate\Support\Str::limit(
                                            $risk->critical_risks,
                                            80
                                        )
                                    }}

                                </span>

                            @else

                                <span class="text-muted">
                                    None specified
                                </span>

                            @endif

                        </td>


                        {{-- Status --}}
                        <td>

                            @switch($risk->status)

                                @case('Draft')

                                    <span class="badge bg-secondary">
                                        Draft
                                    </span>

                                    @break


                                @case('Submitted')

                                    <span class="badge bg-warning text-dark">
                                        Submitted
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

                                    <span class="badge bg-secondary">
                                        {{ $risk->status }}
                                    </span>

                            @endswitch

                        </td>


                        {{-- Actions --}}
                        <td>

                            <div class="d-flex gap-1">

                                {{-- View --}}
                                <a
                                    href="{{ route(
                                        'admin.land.lands.feasibility-assessments.risk-assessments.show',
                                        [
                                            'land' =>
                                                $land->id,

                                            'feasibilityAssessment' =>
                                                $feasibilityAssessment->id,

                                            'riskAssessment' =>
                                                $risk->id,
                                        ]
                                    ) }}"
                                    class="btn btn-sm btn-outline-primary"
                                >
                                    View
                                </a>


                                {{-- Edit --}}
                                <a
                                    href="{{ route(
                                        'admin.land.lands.feasibility-assessments.risk-assessments.edit',
                                        [
                                            'land' =>
                                                $land->id,

                                            'feasibilityAssessment' =>
                                                $feasibilityAssessment->id,

                                            'riskAssessment' =>
                                                $risk->id,
                                        ]
                                    ) }}"
                                    class="btn btn-sm btn-outline-secondary"
                                >
                                    Edit
                                </a>


                                {{-- Delete --}}
                                <form
                                    action="{{ route(
                                        'admin.land.lands.feasibility-assessments.risk-assessments.destroy',
                                        [
                                            'land' =>
                                                $land->id,

                                            'feasibilityAssessment' =>
                                                $feasibilityAssessment->id,

                                            'riskAssessment' =>
                                                $risk->id,
                                        ]
                                    ) }}"
                                    method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this Risk Assessment?');"
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

                            </div>

                        </td>

                    </tr>


                @empty

                    <tr>

                        <td
                            colspan="7"
                            class="text-center py-5"
                        >

                            <div class="text-muted mb-3">

                                No risk assessment has been
                                added yet.

                            </div>


                            <a
                                href="{{ route(
                                    'admin.land.lands.feasibility-assessments.risk-assessments.create',
                                    [
                                        'land' =>
                                            $land->id,

                                        'feasibilityAssessment' =>
                                            $feasibilityAssessment->id,
                                    ]
                                ) }}"
                                class="btn btn-sm btn-primary"
                            >
                                + Add Risk Assessment
                            </a>

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

{{-- ========================================================= --}}
{{-- Investment Analysis --}}
{{-- ========================================================= --}}

<div class="card mb-4">

    <div class="card-header d-flex justify-content-between align-items-center">

        <div>

            <strong>
                Investment Analysis
            </strong>

            <div class="small text-muted">
                Investment requirement, returns, valuation and scenarios
            </div>

        </div>


        <a
            href="{{ route(
                'admin.land.lands.feasibility-assessments.investment-analyses.create',
                [
                    'land' => $land->id,
                    'feasibilityAssessment' =>
                        $feasibilityAssessment->id,
                ]
            ) }}"
            class="btn btn-sm btn-primary"
        >
            + Add Investment Analysis
        </a>

    </div>


    <div class="card-body p-0">

        <div class="table-responsive">

            <table class="table table-hover table-bordered mb-0 align-middle">

                <thead class="table-light">

                    <tr>

                        <th>
                            Analysis Number
                        </th>

                        <th>
                            Title
                        </th>

                        <th>
                            Investment
                        </th>

                        <th>
                            ROI
                        </th>

                        <th>
                            IRR
                        </th>

                        <th>
                            Risk
                        </th>

                        <th>
                            Score
                        </th>

                        <th>
                            Status
                        </th>

                        <th style="width: 230px;">
                            Action
                        </th>

                    </tr>

                </thead>


                <tbody>

                @forelse(
                    $feasibilityAssessment->investmentAnalyses as $analysis
                )

                    <tr>

                        {{-- Analysis Number --}}
                        <td>

                            <strong>
                                {{ $analysis->analysis_number }}
                            </strong>

                        </td>


                        {{-- Title --}}
                        <td>

                            {{ $analysis->title }}

                        </td>


                        {{-- Investment --}}
                        <td>

                            @if(
                                $analysis->total_investment !== null
                            )

                                ₹{{
                                    number_format(
                                        $analysis->total_investment,
                                        2
                                    )
                                }}

                            @else

                                -

                            @endif

                        </td>


                        {{-- ROI --}}
                        <td>

                            @if(
                                $analysis->roi !== null
                            )

                                {{
                                    number_format(
                                        $analysis->roi,
                                        2
                                    )
                                }}%

                            @else

                                -

                            @endif

                        </td>


                        {{-- IRR --}}
                        <td>

                            @if(
                                $analysis->irr !== null
                            )

                                {{
                                    number_format(
                                        $analysis->irr,
                                        2
                                    )
                                }}%

                            @else

                                -

                            @endif

                        </td>


                        {{-- Risk --}}
                        <td>

                            @if(
                                $analysis->investment_risk_rating === 'Low'
                            )

                                <span class="badge bg-success">
                                    Low
                                </span>

                            @elseif(
                                $analysis->investment_risk_rating === 'Medium'
                            )

                                <span class="badge bg-warning text-dark">
                                    Medium
                                </span>

                            @elseif(
                                $analysis->investment_risk_rating === 'High'
                            )

                                <span class="badge bg-danger">
                                    High
                                </span>

                            @elseif(
                                $analysis->investment_risk_rating === 'Critical'
                            )

                                <span class="badge bg-dark">
                                    Critical
                                </span>

                            @elseif(
                                $analysis->investment_risk_rating
                            )

                                <span class="badge bg-secondary">
                                    {{ $analysis->investment_risk_rating }}
                                </span>

                            @else

                                -

                            @endif

                        </td>


                        {{-- Score --}}
                        <td>

                            @if(
                                $analysis->overall_investment_score
                                !== null
                            )

                                <strong>
                                    {{
                                        number_format(
                                            $analysis
                                                ->overall_investment_score,
                                            2
                                        )
                                    }}
                                </strong>

                            @else

                                -

                            @endif

                        </td>


                        {{-- Status --}}
                        <td>

                            @switch($analysis->status)

                                @case('Draft')

                                    <span class="badge bg-secondary">
                                        Draft
                                    </span>

                                    @break


                                @case('Submitted')

                                    <span class="badge bg-warning text-dark">
                                        Submitted
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

                                    <span class="badge bg-secondary">
                                        {{ $analysis->status }}
                                    </span>

                            @endswitch

                        </td>


                        {{-- Actions --}}
                        <td>

                            <div class="d-flex gap-1">

                                <a
                                    href="{{ route(
                                        'admin.land.lands.feasibility-assessments.investment-analyses.show',
                                        [
                                            'land' =>
                                                $land->id,

                                            'feasibilityAssessment' =>
                                                $feasibilityAssessment->id,

                                            'investmentAnalysis' =>
                                                $analysis->id,
                                        ]
                                    ) }}"
                                    class="btn btn-sm btn-outline-primary"
                                >
                                    View
                                </a>


                                <a
                                    href="{{ route(
                                        'admin.land.lands.feasibility-assessments.investment-analyses.edit',
                                        [
                                            'land' =>
                                                $land->id,

                                            'feasibilityAssessment' =>
                                                $feasibilityAssessment->id,

                                            'investmentAnalysis' =>
                                                $analysis->id,
                                        ]
                                    ) }}"
                                    class="btn btn-sm btn-outline-secondary"
                                >
                                    Edit
                                </a>


                                <form
                                    action="{{ route(
                                        'admin.land.lands.feasibility-assessments.investment-analyses.destroy',
                                        [
                                            'land' =>
                                                $land->id,

                                            'feasibilityAssessment' =>
                                                $feasibilityAssessment->id,

                                            'investmentAnalysis' =>
                                                $analysis->id,
                                        ]
                                    ) }}"
                                    method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this Investment Analysis?');"
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

                            </div>

                        </td>

                    </tr>


                @empty

                    <tr>

                        <td
                            colspan="9"
                            class="text-center py-5"
                        >

                            <div class="text-muted mb-3">

                                No investment analysis
                                has been added yet.

                            </div>


                            <a
                                href="{{ route(
                                    'admin.land.lands.feasibility-assessments.investment-analyses.create',
                                    [
                                        'land' =>
                                            $land->id,

                                        'feasibilityAssessment' =>
                                            $feasibilityAssessment->id,
                                    ]
                                ) }}"
                                class="btn btn-sm btn-primary"
                            >
                                + Add Investment Analysis
                            </a>

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

{{-- ========================================================= --}}
{{-- Investment Decisions --}}
{{-- ========================================================= --}}

<div class="card mb-4">

    <div class="card-header d-flex justify-content-between align-items-center">

        <div>

            <strong>
                Investment Decision
            </strong>

            <div class="small text-muted">
                Final investment decision and committee recommendation
            </div>

        </div>


        <a
            href="{{ route(
                'admin.land.lands.feasibility-assessments.investment-decisions.create',
                [
                    'land' => $land->id,
                    'feasibilityAssessment' =>
                        $feasibilityAssessment->id,
                ]
            ) }}"
            class="btn btn-sm btn-primary"
        >
            + Add Investment Decision
        </a>

    </div>


    <div class="card-body p-0">

        <div class="table-responsive">

            <table class="table table-hover table-bordered mb-0 align-middle">

                <thead class="table-light">

                    <tr>

                        <th>
                            Decision Number
                        </th>

                        <th>
                            Title
                        </th>

                        <th>
                            Decision
                        </th>

                        <th>
                            Recommendation
                        </th>

                        <th>
                            Investment
                        </th>

                        <th>
                            ROI
                        </th>

                        <th>
                            IRR
                        </th>

                        <th>
                            Score
                        </th>

                        <th>
                            Status
                        </th>

                        <th style="width:220px;">
                            Action
                        </th>

                    </tr>

                </thead>


                <tbody>

                @forelse(
                    $feasibilityAssessment->investmentDecisions as $decision
                )

                    <tr>

                        {{-- Decision Number --}}

                        <td>

                            <strong>
                                {{ $decision->decision_number }}
                            </strong>

                        </td>


                        {{-- Title --}}

                        <td>
                            {{ $decision->title }}
                        </td>


                        {{-- Decision --}}

                        <td>

                            @if(
                                $decision->decision === 'Go'
                            )

                                <span class="badge bg-success">
                                    Go
                                </span>

                            @elseif(
                                $decision->decision ===
                                'Conditional Go'
                            )

                                <span class="badge bg-warning text-dark">
                                    Conditional Go
                                </span>

                            @elseif(
                                $decision->decision ===
                                'No-Go'
                            )

                                <span class="badge bg-danger">
                                    No-Go
                                </span>

                            @elseif(
                                $decision->decision ===
                                'Defer'
                            )

                                <span class="badge bg-secondary">
                                    Defer
                                </span>

                            @elseif(
                                $decision->decision
                            )

                                <span class="badge bg-secondary">
                                    {{ $decision->decision }}
                                </span>

                            @else

                                -

                            @endif

                        </td>


                        {{-- Recommendation --}}

                        <td>

                            {{
                                $decision
                                    ->investment_recommendation
                                ?? '-'
                            }}

                        </td>


                        {{-- Investment --}}

                        <td>

                            @if(
                                $decision
                                    ->approved_investment
                                !== null
                            )

                                ₹{{
                                    number_format(
                                        $decision
                                            ->approved_investment,
                                        2
                                    )
                                }}

                            @elseif(
                                $decision
                                    ->recommended_investment
                                !== null
                            )

                                ₹{{
                                    number_format(
                                        $decision
                                            ->recommended_investment,
                                        2
                                    )
                                }}

                            @else

                                -

                            @endif

                        </td>


                        {{-- ROI --}}

                        <td>

                            @if(
                                $decision->expected_roi
                                !== null
                            )

                                {{
                                    number_format(
                                        $decision
                                            ->expected_roi,
                                        2
                                    )
                                }}%

                            @else

                                -

                            @endif

                        </td>


                        {{-- IRR --}}

                        <td>

                            @if(
                                $decision->expected_irr
                                !== null
                            )

                                {{
                                    number_format(
                                        $decision
                                            ->expected_irr,
                                        2
                                    )
                                }}%

                            @else

                                -

                            @endif

                        </td>


                        {{-- Score --}}

                        <td>

                            @if(
                                $decision->overall_score
                                !== null
                            )

                                <strong>
                                    {{
                                        number_format(
                                            $decision
                                                ->overall_score,
                                            2
                                        )
                                    }}
                                </strong>

                                <small class="text-muted">
                                    /100
                                </small>

                            @else

                                -

                            @endif

                        </td>


                        {{-- Status --}}

                        <td>

                            @switch($decision->status)

                                @case('Draft')

                                    <span class="badge bg-secondary">
                                        Draft
                                    </span>

                                    @break


                                @case('Submitted')

                                    <span class="badge bg-warning text-dark">
                                        Submitted
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

                                    <span class="badge bg-secondary">
                                        {{ $decision->status }}
                                    </span>

                            @endswitch

                        </td>


                        {{-- Actions --}}

                        <td>

                            <div class="d-flex gap-1">

                                <a
                                    href="{{ route(
                                        'admin.land.lands.feasibility-assessments.investment-decisions.show',
                                        [
                                            'land' =>
                                                $land->id,

                                            'feasibilityAssessment' =>
                                                $feasibilityAssessment->id,

                                            'investmentDecision' =>
                                                $decision->id,
                                        ]
                                    ) }}"
                                    class="btn btn-sm btn-outline-primary"
                                >
                                    View
                                </a>


                                <a
                                    href="{{ route(
                                        'admin.land.lands.feasibility-assessments.investment-decisions.edit',
                                        [
                                            'land' =>
                                                $land->id,

                                            'feasibilityAssessment' =>
                                                $feasibilityAssessment->id,

                                            'investmentDecision' =>
                                                $decision->id,
                                        ]
                                    ) }}"
                                    class="btn btn-sm btn-outline-secondary"
                                >
                                    Edit
                                </a>


                                <form
                                    action="{{ route(
                                        'admin.land.lands.feasibility-assessments.investment-decisions.destroy',
                                        [
                                            'land' =>
                                                $land->id,

                                            'feasibilityAssessment' =>
                                                $feasibilityAssessment->id,

                                            'investmentDecision' =>
                                                $decision->id,
                                        ]
                                    ) }}"
                                    method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this Investment Decision?');"
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

                            </div>

                        </td>

                    </tr>


                @empty

                    <tr>

                        <td
                            colspan="10"
                            class="text-center py-5"
                        >

                            <div class="text-muted mb-3">
                                No investment decision has been recorded.
                            </div>


                            <a
                                href="{{ route(
                                    'admin.land.lands.feasibility-assessments.investment-decisions.create',
                                    [
                                        'land' =>
                                            $land->id,

                                        'feasibilityAssessment' =>
                                            $feasibilityAssessment->id,
                                    ]
                                ) }}"
                                class="btn btn-sm btn-primary"
                            >
                                + Add Investment Decision
                            </a>

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

</div>

@endsection