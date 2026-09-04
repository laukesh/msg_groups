@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <!-- <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3>
                Location Analyses
            </h3>

            <p class="text-muted mb-0">

                {{ $feasibilityAssessment->assessment_number }}
                -
                {{ $feasibilityAssessment->title }}

            </p>

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
            class="btn btn-primary"
        >
            + New Location Analysis
        </a>

    </div> -->

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3>
                Location Analyses
            </h3>

            <p class="text-muted mb-0">

                {{ $feasibilityAssessment->assessment_number }}
                -
                {{ $feasibilityAssessment->title }}

            </p>

        </div>


        <div class="d-flex gap-2">

            {{-- Back to Feasibility Assessment --}}

            <a
                href="{{ route(
                    'admin.land.lands.feasibility-assessments.show',
                    [
                        'land' => $land->id,
                        'feasibilityAssessment' => $feasibilityAssessment->id,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                ← Back to Feasibility
            </a>


            {{-- New Location Analysis --}}

            <a
                href="{{ route(
                    'admin.land.lands.feasibility-assessments.location-analyses.create',
                    [
                        'land' => $land->id,
                        'feasibilityAssessment' =>
                            $feasibilityAssessment->id,
                    ]
                ) }}"
                class="btn btn-primary"
            >
                + New Location Analysis
            </a>

        </div>

    </div>


    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


    <div class="card">

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover mb-0">

                    <thead class="table-light">

                        <tr>

                            <th>
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

                            <th>
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                    @forelse(
                        $locationAnalyses as $analysis
                    )

                        <tr>

                            <td>
                                <strong>
                                    {{ $analysis->analysis_number }}
                                </strong>
                            </td>


                            <td>
                                {{ $analysis->title }}
                            </td>


                            <td>
                                {{ $analysis->location_type ?? '-' }}
                            </td>


                            <td>

                                @if(
                                    $analysis->overall_location_score !== null
                                )

                                    {{ number_format(
                                        $analysis->overall_location_score,
                                        2
                                    ) }}

                                    / 100

                                @else

                                    -

                                @endif

                            </td>


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


                            <td>

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

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="6"
                                class="text-center py-5"
                            >

                                No location analyses found.

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        @if($locationAnalyses->hasPages())

            <div class="card-footer">

                {{ $locationAnalyses->links() }}

            </div>

        @endif

    </div>

</div>

@endsection