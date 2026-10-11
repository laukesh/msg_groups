@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3>
                Market Studies
            </h3>

            <p class="text-muted mb-0">

                {{ $feasibilityAssessment->assessment_number }}
                -
                {{ $feasibilityAssessment->title }}

            </p>

        </div>


        <div>

            {{-- New Market Study --}}
            <a
                href="{{ route(
                    'admin.land.lands.feasibility-assessments.market-studies.create',
                    [
                        'land' => $land->id,
                        'feasibilityAssessment' => $feasibilityAssessment->id,
                    ]
                ) }}"
                class="btn btn-primary"
            >
                + New Market Study
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

                    <thead>

                        <tr>

                            <th>
                                Study Number
                            </th>

                            <th>
                                Title
                            </th>

                            <th>
                                Market Segment
                            </th>

                            <th>
                                Location
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

                    @forelse($marketStudies as $study)

                        <tr>

                            <td>
                                <strong>
                                    {{ $study->study_number }}
                                </strong>
                            </td>


                            <td>
                                {{ $study->title }}
                            </td>


                            <td>
                                {{ $study->market_segment ?? '-' }}
                            </td>


                            <td>
                                {{ $study->market_location ?? '-' }}
                            </td>


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


                            <td>

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

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="6"
                                class="text-center py-5"
                            >

                                <div class="text-muted">

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
                                    class="btn btn-primary btn-sm mt-3"
                                >
                                    Create First Market Study
                                </a>

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        @if($marketStudies->hasPages())

            <div class="card-footer">

                {{ $marketStudies->links() }}

            </div>

        @endif

    </div>

</div>

@endsection