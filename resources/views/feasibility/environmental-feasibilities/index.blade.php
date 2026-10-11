@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3>
                Environmental Feasibilities
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
                    'admin.land.lands.feasibility-assessments.environmental-feasibilities.create',
                    [
                        'land' => $land->id,
                        'feasibilityAssessment' =>
                            $feasibilityAssessment->id,
                    ]
                ) }}"
                class="btn btn-primary"
            >
                + New Environmental Feasibility
            </a>

        </div>

    </div>


    {{-- Success --}}
    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


    {{-- Table --}}
    <div class="card">

        <div class="card-header">

            <strong>
                Environmental Feasibility Assessments
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
                                Environmental Status
                            </th>

                            <th>
                                Clearance
                            </th>

                            <th>
                                Environmental Score
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
                        $environmentalFeasibilities
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

                                @if(
                                    $environmental->status === 'Draft'
                                )

                                    <span class="badge bg-secondary">
                                        Draft
                                    </span>

                                @elseif(
                                    $environmental->status === 'Submitted'
                                )

                                    <span class="badge bg-warning text-dark">
                                        Submitted
                                    </span>

                                @elseif(
                                    $environmental->status === 'Approved'
                                )

                                    <span class="badge bg-success">
                                        Approved
                                    </span>

                                @elseif(
                                    $environmental->status === 'Rejected'
                                )

                                    <span class="badge bg-danger">
                                        Rejected
                                    </span>

                                @else

                                    <span class="badge bg-secondary">
                                        {{ $environmental->status }}
                                    </span>

                                @endif

                            </td>


                            {{-- Actions --}}
                            <td>

                                <div class="d-flex gap-1">

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
                                    records found.

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
                                    + Create Environmental Feasibility
                                </a>

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        @if($environmentalFeasibilities->hasPages())

            <div class="card-footer">

                {{ $environmentalFeasibilities->links() }}

            </div>

        @endif

    </div>

</div>

@endsection