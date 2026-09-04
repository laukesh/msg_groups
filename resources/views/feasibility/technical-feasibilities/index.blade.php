@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3>
                Technical Feasibilities
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
                    'admin.land.lands.feasibility-assessments.technical-feasibilities.create',
                    [
                        'land' => $land->id,
                        'feasibilityAssessment' =>
                            $feasibilityAssessment->id,
                    ]
                ) }}"
                class="btn btn-primary"
            >
                + New Technical Feasibility
            </a>

        </div>

    </div>


    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


    {{-- Table --}}
    <div class="card">

        <div class="card-header">
            <strong>Technical Feasibility Assessments</strong>
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
                                Site Development
                            </th>

                            <th>
                                Construction
                            </th>

                            <th>
                                Infrastructure
                            </th>

                            <th>
                                Utilities
                            </th>

                            <th>
                                Technical Score
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
                        $technicalFeasibilities
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


                            {{-- Site Development --}}
                            <td>

                                @if(
                                    $technical->site_development_status
                                )

                                    <span class="badge bg-secondary">
                                        {{
                                            $technical
                                                ->site_development_status
                                        }}
                                    </span>

                                @else
                                    -
                                @endif

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
                                    $technical->infrastructure_status
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


                            {{-- Utilities --}}
                            <td>

                                @if(
                                    $technical->electricity_status
                                )

                                    <span class="badge bg-secondary">

                                        Electricity:
                                        {{
                                            $technical
                                                ->electricity_status
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

                                    <a
                                        href="{{ route(
                                            'admin.land.lands.feasibility-assessments.technical-feasibilities.show',
                                            [
                                                'land' => $land->id,

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


                                    <a
                                        href="{{ route(
                                            'admin.land.lands.feasibility-assessments.technical-feasibilities.edit',
                                            [
                                                'land' => $land->id,

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


                                    <form
                                        action="{{ route(
                                            'admin.land.lands.feasibility-assessments.technical-feasibilities.destroy',
                                            [
                                                'land' => $land->id,

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
                                colspan="9"
                                class="text-center py-5"
                            >

                                <div class="text-muted mb-3">
                                    No technical feasibility records found.
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
                                    + Create Technical Feasibility
                                </a>

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        @if($technicalFeasibilities->hasPages())

            <div class="card-footer">

                {{ $technicalFeasibilities->links() }}

            </div>

        @endif

    </div>

</div>

@endsection