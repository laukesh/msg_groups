@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3 class="mb-1">
                Legal Due Diligence
            </h3>

            <p class="text-muted mb-0">

                {{ $land->land_code }}
                -
                {{ $land->land_name }}

            </p>

        </div>


        <div>

            <a
                href="{{ route(
                    'admin.land.lands.legal-due-diligences.create',
                    $land
                ) }}"
                class="btn btn-primary"
            >
                + Add Due Diligence
            </a>


            <a
                href="{{ route(
                    'admin.land.lands.show',
                    $land
                ) }}"
                class="btn btn-secondary"
            >
                Back to Land
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


    <div class="card">

        <div class="card-header">

            <strong>
                Legal Due Diligence Records
            </strong>

            <span class="badge bg-primary ms-2">
                {{ $legalDueDiligences->total() }}
            </span>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

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

                            <th>
                                Recommendation
                            </th>

                            <th class="text-end">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                    @forelse(
                        $legalDueDiligences
                        as $dueDiligence
                    )

                        <tr>

                            <td>

                                <strong>
                                    {{ $dueDiligence->reference_no ?? '-' }}
                                </strong>

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

                                @if($dueDiligence->status === 'Approved')

                                    <span class="badge bg-success">
                                        Approved
                                    </span>

                                @elseif($dueDiligence->status === 'Rejected')

                                    <span class="badge bg-danger">
                                        Rejected
                                    </span>

                                @elseif($dueDiligence->status === 'Completed')

                                    <span class="badge bg-primary">
                                        Completed
                                    </span>

                                @elseif($dueDiligence->status === 'Under Review')

                                    <span class="badge bg-warning text-dark">
                                        Under Review
                                    </span>

                                @elseif($dueDiligence->status === 'Pending')

                                    <span class="badge bg-info text-dark">
                                        Pending
                                    </span>

                                @else

                                    <span class="badge bg-secondary">
                                        {{ $dueDiligence->status }}
                                    </span>

                                @endif

                            </td>


                            <td>

                                {{ $dueDiligence->recommendations
                                    ? \Illuminate\Support\Str::limit(
                                        $dueDiligence->recommendations,
                                        80
                                    )
                                    : '-'
                                }}

                            </td>


                            <td class="text-end">

                                <a
                                    href="{{ route(
                                        'admin.land.lands.legal-due-diligences.show',
                                        [
                                            'land' => $land,
                                            'legal_due_diligence' => $dueDiligence
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
                                            'land' => $land,
                                            'legal_due_diligence' => $dueDiligence
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

                                <div class="text-muted mb-3">
                                    No legal due diligence records found.
                                </div>


                                <a
                                    href="{{ route(
                                        'admin.land.lands.legal-due-diligences.create',
                                        $land
                                    ) }}"
                                    class="btn btn-sm btn-primary"
                                >
                                    + Add First Review
                                </a>

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        @if($legalDueDiligences->hasPages())

            <div class="card-footer">

                {{ $legalDueDiligences->links() }}

            </div>

        @endif

    </div>

</div>

@endsection