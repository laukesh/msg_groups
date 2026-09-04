@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ========================================================= --}}
    {{-- Header --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3>
                Financial Feasibility
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
                    'admin.land.lands.feasibility-assessments.financial-feasibilities.create',
                    [
                        'land' => $land->id,
                        'feasibilityAssessment' =>
                            $feasibilityAssessment->id,
                    ]
                ) }}"
                class="btn btn-primary"
            >
                + New Financial Analysis
            </a>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Success --}}
    {{-- ========================================================= --}}

    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- Table --}}
    {{-- ========================================================= --}}

    <div class="card">

        <div class="card-header">

            <strong>
                Financial Feasibility Records
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

                            <th style="width: 190px;">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                    @forelse(
                        $financialFeasibilities as $financial
                    )

                        <tr>

                            {{-- Number --}}
                            <td>

                                <strong>
                                    {{ $financial->analysis_number }}
                                </strong>

                            </td>


                            {{-- Title --}}
                            <td>
                                {{ $financial->title }}
                            </td>


                            {{-- Project Cost --}}
                            <td>

                                @if(
                                    $financial->total_project_cost
                                    !== null
                                )

                                    {{ number_format(
                                        $financial->total_project_cost,
                                        2
                                    ) }}

                                @else

                                    -

                                @endif

                            </td>


                            {{-- Revenue --}}
                            <td>

                                @if(
                                    $financial->total_revenue
                                    !== null
                                )

                                    {{ number_format(
                                        $financial->total_revenue,
                                        2
                                    ) }}

                                @else

                                    -

                                @endif

                            </td>


                            {{-- Net Profit --}}
                            <td>

                                @if(
                                    $financial->net_profit !== null
                                )

                                    <span
                                        class="
                                            {{
                                                $financial->net_profit >= 0
                                                    ? 'text-success'
                                                    : 'text-danger'
                                            }}
                                        "
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


                            {{-- ROI --}}
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


                            {{-- IRR --}}
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


                            {{-- Score --}}
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


                            {{-- Status --}}
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


                            {{-- Actions --}}
                            <td>

                                <div class="d-flex gap-1">

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

                                    No financial feasibility
                                    records found.

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
                                    + Create Financial Analysis
                                </a>

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        @if($financialFeasibilities->hasPages())

            <div class="card-footer">

                {{ $financialFeasibilities->links() }}

            </div>

        @endif

    </div>

</div>

@endsection