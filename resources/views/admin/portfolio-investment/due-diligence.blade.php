@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">Due Diligence</h4>

            <p class="text-muted mb-0">
                Legal, technical and environmental due diligence assessments
            </p>
        </div>

        <a href="{{ route('admin.portfolio-investment.overview') }}"
           class="btn btn-outline-secondary">

            <i class="ri-arrow-left-line"></i>
            Portfolio Overview

        </a>

    </div>


    {{-- Summary Cards --}}
    <div class="row g-3 mb-4">

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-muted mb-2">
                        Total Assessments
                    </div>

                    <h3 class="mb-0">
                        {{ number_format($stats['total']) }}
                    </h3>
                </div>
            </div>
        </div>


        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-muted mb-2">
                        Completed
                    </div>

                    <h3 class="mb-0 text-success">
                        {{ number_format($stats['completed']) }}
                    </h3>
                </div>
            </div>
        </div>


        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-muted mb-2">
                        In Progress
                    </div>

                    <h3 class="mb-0 text-warning">
                        {{ number_format($stats['in_progress']) }}
                    </h3>
                </div>
            </div>
        </div>


        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-muted mb-2">
                        Pending
                    </div>

                    <h3 class="mb-0 text-danger">
                        {{ number_format($stats['pending']) }}
                    </h3>
                </div>
            </div>
        </div>

    </div>


    {{-- Due Diligence Register --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white d-flex justify-content-between align-items-center">

            <h5 class="mb-0">
                Due Diligence Register
            </h5>

            <span class="badge bg-light text-dark">
                {{ $dueDiligences->count() }} Records
            </span>

        </div>


        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle">

                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Land</th>
                            <th>Assessment Type</th>
                            <th>Reference No.</th>
                            <th>Assessment Date</th>
                            <th>Conducted By</th>
                            <th>Status</th>
                            <th>Findings</th>
                            <th>Recommendations</th>
                            <th>Action</th>
                        </tr>
                    </thead>


                    <tbody>

                        @forelse($dueDiligences as $index => $item)

                            <tr>

                                <td>
                                    {{ $index + 1 }}
                                </td>


                                <td>
                                    <strong>
                                        {{ $item->land_name ?? 'N/A' }}
                                    </strong>

                                    <br>

                                    <small class="text-muted">
                                        {{ $item->land_code ?? '' }}
                                    </small>
                                </td>


                                <td>
                                    <span class="badge bg-info">
                                        {{ $item->type ?? 'N/A' }}
                                    </span>
                                </td>


                                <td>
                                    {{ $item->reference_no ?? 'N/A' }}
                                </td>


                                <td>
                                    {{ $item->assessment_date
                                        ? \Carbon\Carbon::parse($item->assessment_date)->format('d-m-Y')
                                        : 'N/A'
                                    }}
                                </td>


                                <td>
                                    {{ $item->conducted_by ?? 'N/A' }}
                                </td>


                                <td>
                                    @php
                                        $status = strtolower(
                                            (string) $item->status
                                        );
                                    @endphp

                                    @if(in_array($status, ['completed', 'complete', 'approved']))
                                        <span class="badge bg-success">
                                            {{ $item->status }}
                                        </span>
                                    @elseif(in_array($status, ['in progress', 'under review']))
                                        <span class="badge bg-warning text-dark">
                                            {{ $item->status }}
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">
                                            {{ $item->status ?? 'N/A' }}
                                        </span>
                                    @endif
                                </td>


                                <td>
                                    {{ \Illuminate\Support\Str::limit(
                                        $item->findings ?? 'N/A',
                                        100
                                    ) }}
                                </td>


                                <td>
                                    {{ \Illuminate\Support\Str::limit(
                                        $item->recommendations ?? 'N/A',
                                        100
                                    ) }}
                                </td>

                                <td>
                                    @php
                                        $type = strtolower(trim((string) $item->type));
                                        $normalizedType = str_replace([' ', '_', '-'], '', $type);
                                    @endphp

                                    @if(in_array($normalizedType, ['technical', 'technicalduediligence']))
                                        <a href="{{ route(
                                            'admin.land.lands.technical-due-diligences.show',
                                            [
                                                'land' => $item->land_id,
                                                'dueDiligence' => $item->id,
                                            ]
                                        ) }}"
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="ri-eye-line"></i>
                                            View
                                        </a>

                                    @elseif(in_array($normalizedType, ['legal', 'legalduediligence']))
                                        <a href="{{ route(
                                            'admin.land.lands.legal-due-diligences.show',
                                            [
                                                'land' => $item->land_id,
                                                'legal_due_diligence' => $item->id,
                                            ]
                                        ) }}"
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="ri-eye-line"></i>
                                            View
                                        </a>

                                    @elseif(in_array($normalizedType, ['environmental', 'environmentalassessment', 'environmentalassessments']))
                                        <a href="{{ url(
                                            'admin/land/lands/' . $item->land_id .
                                            '/environmental-assessments/' . $item->id
                                        ) }}"
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="ri-eye-line"></i>
                                            View
                                        </a>

                                    @else
                                        <span class="text-muted">
                                            Not Available
                                        </span>
                                    @endif
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="10"
                                    class="text-center text-muted py-4">
                                    No due diligence records found.
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