@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3>
                Demand & Supply Analysis
            </h3>

            <p class="text-muted mb-0">
                {{ $feasibilityAssessment->assessment_number }}
                -
                {{ $feasibilityAssessment->title }}
            </p>

        </div>


        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.land.lands.feasibility-assessments.show',
                    [
                        'land' => $land->id,
                        'feasibilityAssessment' =>
                            $feasibilityAssessment->id,
                    ]
                ) }}"
                class="btn btn-secondary"
            >
                ← Back
            </a>


            <a
                href="{{ route(
                    'admin.land.lands.feasibility-assessments.demand-supply-analyses.create',
                    [
                        'land' => $land->id,
                        'feasibilityAssessment' =>
                            $feasibilityAssessment->id,
                    ]
                ) }}"
                class="btn btn-primary"
            >
                + New Analysis
            </a>

        </div>

    </div>


    {{-- Success Message --}}
    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


    {{-- Table --}}
    <div class="card">

        <div class="card-header">

            <strong>
                Analysis Records
            </strong>

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

                    @forelse($analyses as $analysis)

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

                                @if($analysis->projected_demand !== null)

                                    {{ number_format(
                                        $analysis->projected_demand,
                                        2
                                    ) }}

                                @elseif($analysis->current_demand !== null)

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

                                @if($analysis->future_supply !== null)

                                    {{ number_format(
                                        $analysis->future_supply,
                                        2
                                    ) }}

                                @elseif($analysis->current_supply !== null)

                                    {{ number_format(
                                        $analysis->current_supply,
                                        2
                                    ) }}

                                @else

                                    -

                                @endif

                            </td>


                            {{-- Gap --}}
                            <td>

                                @if($analysis->demand_supply_gap !== null)

                                    <span
                                        class="
                                            {{ $analysis->demand_supply_gap > 0
                                                ? 'text-success'
                                                : (
                                                    $analysis->demand_supply_gap < 0
                                                        ? 'text-danger'
                                                        : ''
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
                                        ->overall_demand_supply_score !== null
                                )

                                    {{ number_format(
                                        $analysis
                                            ->overall_demand_supply_score,
                                        2
                                    ) }}

                                    / 100

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
                                    records found.

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
                                    + Create Analysis
                                </a>

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        @if($analyses->hasPages())

            <div class="card-footer">

                {{ $analyses->links() }}

            </div>

        @endif

    </div>

</div>

@endsection