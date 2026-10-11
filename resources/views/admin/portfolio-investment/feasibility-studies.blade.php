@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Feasibility Studies</h4>
            <p class="text-muted mb-0">
                Review and monitor project feasibility assessments.
            </p>
        </div>
    </div>

    <div class="row g-3 mb-4">

        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <small class="text-muted">Total Studies</small>
                    <h3 class="mb-0">{{ $stats['total'] }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <small class="text-muted">Completed</small>
                    <h3 class="mb-0 text-success">
                        {{ $stats['completed'] }}
                    </h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <small class="text-muted">In Progress</small>
                    <h3 class="mb-0 text-warning">
                        {{ $stats['in_progress'] }}
                    </h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <small class="text-muted">Pending</small>
                    <h3 class="mb-0 text-danger">
                        {{ $stats['pending'] }}
                    </h3>
                </div>
            </div>
        </div>

    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0">Feasibility Study Register</h5>
        </div>

        <div class="card-body">
            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Land</th>
                            <th>Assessment No.</th>
                            <th>Title</th>
                            <th>Development Type</th>
                            <th>Assessment Date</th>
                            <th>Target Completion</th>
                            <th>Status</th>
                            <th>Investment Analyses</th>
                            <th>Investment Decisions</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($feasibilityStudies as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>

                                <td>
                                    <strong>{{ $item->land_code }}</strong>
                                    <br>
                                    <small class="text-muted">
                                        {{ $item->land_name }}
                                    </small>
                                </td>

                                <td>
                                    {{ $item->assessment_number ?? '—' }}
                                </td>

                                <td>
                                    {{ $item->title ?? '—' }}
                                </td>

                                <td>
                                    {{ $item->development_type ?? '—' }}
                                </td>

                                <td>
                                    {{ $item->assessment_date
                                        ? \Carbon\Carbon::parse($item->assessment_date)->format('d-m-Y')
                                        : '—'
                                    }}
                                </td>

                                <td>
                                    {{ $item->target_completion_date
                                        ? \Carbon\Carbon::parse($item->target_completion_date)->format('d-m-Y')
                                        : '—'
                                    }}
                                </td>

                                <td>
                                    @php
                                        $status = strtolower((string) $item->status);
                                    @endphp

                                    @if(in_array($status, ['completed', 'complete', 'approved']))
                                        <span class="badge bg-success">
                                            {{ ucfirst($item->status) }}
                                        </span>
                                    @elseif(in_array($status, ['in progress', 'under review', 'ongoing']))
                                        <span class="badge bg-warning text-dark">
                                            {{ ucfirst($item->status) }}
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">
                                            {{ ucfirst($item->status ?? 'N/A') }}
                                        </span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    <span class="badge bg-info">
                                        {{ $item->investment_analysis_count }}
                                    </span>
                                </td>

                                <td class="text-center">
                                    <span class="badge bg-primary">
                                        {{ $item->investment_decision_count }}
                                    </span>
                                </td>

                                <td>
                                    <a href="{{ route(
                                        'admin.land.lands.feasibility-assessments.show',
                                        [
                                            'land' => $item->land_id,
                                            'feasibilityAssessment' => $item->id,
                                        ]
                                    ) }}"
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="ri-eye-line"></i>
                                        View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center text-muted">
                                    No feasibility studies found.
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