@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                Technical Evaluations
            </h4>

            <div class="text-muted">
                Tender:
                <strong>
                    {{ $procurementTender->tender_number ?? $procurementTender->id }}
                </strong>
            </div>
        </div>

        <div class="d-flex gap-2">

            <a href="{{ route('admin.projects.procurement.tenders.technical-evaluations.create', [
                'project' => $project,
                'procurementTender' => $procurementTender,
            ]) }}"
               class="btn btn-primary">

                <i class="bi bi-plus-circle me-1"></i>
                New Technical Evaluation

            </a>

            <a href="{{ route('admin.projects.procurement.tenders.show', [
                'project' => $project,
                'procurementTender' => $procurementTender,
            ]) }}"
               class="btn btn-outline-secondary">

                <i class="bi bi-arrow-left me-1"></i>
                Back to Tender

            </a>

        </div>

    </div>


    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show">
            {{ session('warning') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"></button>
        </div>
    @endif


    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    {{-- Evaluation List --}}
    <div class="card shadow-sm">

        <div class="card-header d-flex justify-content-between align-items-center">

            <h5 class="mb-0">
                Technical Evaluation List
            </h5>

            <span class="badge bg-secondary">
                {{ $evaluations->count() }}
            </span>

        </div>


        <div class="card-body p-0">

            @if($evaluations->count())

                <div class="table-responsive">

                    <table class="table table-hover table-bordered align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th width="60">
                                    #
                                </th>

                                <th>
                                    Evaluation No.
                                </th>

                                <th>
                                    Submission
                                </th>

                                <th>
                                    Bidder
                                </th>

                                <th>
                                    Evaluation Date
                                </th>

                                <th>
                                    Score
                                </th>

                                <th>
                                    Result
                                </th>

                                <th>
                                    Compliance
                                </th>

                                <th>
                                    Status
                                </th>

                                <th width="180">
                                    Actions
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($evaluations as $evaluation)

                                <tr>

                                    <td>
                                        {{ $loop->iteration }}
                                    </td>


                                    <td>

                                        <a href="{{ route(
                                            'admin.projects.procurement.tenders.technical-evaluations.show',
                                            [
                                                'project' => $project,
                                                'procurementTender' => $procurementTender,
                                                'evaluation' => $evaluation,
                                            ]
                                        ) }}"
                                           class="fw-semibold text-decoration-none">

                                            {{ $evaluation->evaluation_number }}

                                        </a>

                                    </td>


                                    <td>

                                        @if($evaluation->submission)

                                            {{ $evaluation->submission->submission_number ?? ('Submission #' . $evaluation->submission->id) }}

                                        @else

                                            <span class="text-muted">
                                                —
                                            </span>

                                        @endif

                                    </td>


                                    <td>

                                        @if(
                                            $evaluation->submission &&
                                            $evaluation->submission->tenderBidder &&
                                            $evaluation->submission->tenderBidder->bidder
                                        )

                                            {{ $evaluation->submission->tenderBidder->bidder->name
                                                ?? $evaluation->submission->tenderBidder->bidder->company_name
                                                ?? '—' }}

                                        @else

                                            <span class="text-muted">
                                                —
                                            </span>

                                        @endif

                                    </td>


                                    <td>

                                        {{ $evaluation->evaluation_date
                                            ? $evaluation->evaluation_date->format('d-m-Y')
                                            : '—' }}

                                    </td>


                                    <td>

                                        <strong>
                                            {{ number_format((float) $evaluation->technical_score, 2) }}
                                        </strong>

                                        <span class="text-muted">
                                            /
                                            {{ number_format((float) $evaluation->maximum_score, 2) }}
                                        </span>

                                    </td>


                                    <td>

                                        @if($evaluation->result === 'Pass')

                                            <span class="badge bg-success">
                                                Pass
                                            </span>

                                        @elseif($evaluation->result === 'Fail')

                                            <span class="badge bg-danger">
                                                Fail
                                            </span>

                                        @else

                                            <span class="badge bg-secondary">
                                                {{ $evaluation->result ?? '—' }}
                                            </span>

                                        @endif

                                    </td>


                                    <td>

                                        @if($evaluation->technical_compliance)

                                            {{ $evaluation->technical_compliance }}

                                        @else

                                            <span class="text-muted">
                                                —
                                            </span>

                                        @endif

                                    </td>


                                    <td>

                                        @php
                                            $statusClass = match(strtolower($evaluation->status ?? '')) {
                                                'completed',
                                                'approved',
                                                'accepted' => 'success',

                                                'pending',
                                                'under review',
                                                'in review' => 'warning',

                                                'rejected',
                                                'cancelled' => 'danger',

                                                default => 'secondary',
                                            };
                                        @endphp

                                        <span class="badge bg-{{ $statusClass }}">
                                            {{ $evaluation->status ?? '—' }}
                                        </span>

                                    </td>


                                    <td>

                                        <div class="d-flex gap-1">

                                            {{-- Show --}}
                                            <a href="{{ route(
                                                'admin.projects.procurement.tenders.technical-evaluations.show',
                                                [
                                                    'project' => $project,
                                                    'procurementTender' => $procurementTender,
                                                    'evaluation' => $evaluation,
                                                ]
                                            ) }}"
                                               class="btn btn-sm btn-outline-primary"
                                               title="View">

                                                <i class="fas fa-eye"></i>

                                            </a>


                                            {{-- Edit --}}
                                            <a href="{{ route(
                                                'admin.projects.procurement.tenders.technical-evaluations.edit',
                                                [
                                                    'project' => $project,
                                                    'procurementTender' => $procurementTender,
                                                    'evaluation' => $evaluation,
                                                ]
                                            ) }}"
                                               class="btn btn-sm btn-outline-secondary"
                                               title="Edit">

                                                <i class="fas fa-edit"></i>

                                            </a>


                                            {{-- Delete --}}
                                            <form method="POST"
                                                  action="{{ route(
                                                      'admin.projects.procurement.tenders.technical-evaluations.destroy',
                                                      [
                                                          'project' => $project,
                                                          'procurementTender' => $procurementTender,
                                                          'evaluation' => $evaluation,
                                                      ]
                                                  ) }}"
                                                  onsubmit="return confirm('Are you sure you want to delete this technical evaluation?');">

                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        class="btn btn-sm btn-outline-danger"
                                                        title="Delete">

                                                    <i class="fas fa-trash"></i>

                                                </button>

                                            </form>

                                        </div>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="text-center py-5">

                    <div class="mb-3">
                        <i class="bi bi-clipboard-x fs-1 text-muted"></i>
                    </div>

                    <h5>
                        No Technical Evaluations Found
                    </h5>

                    <p class="text-muted mb-3">
                        No technical evaluation has been created for this tender yet.
                    </p>

                    <a href="{{ route(
                        'admin.projects.procurement.tenders.technical-evaluations.create',
                        [
                            'project' => $project,
                            'procurementTender' => $procurementTender,
                        ]
                    ) }}"
                       class="btn btn-primary">

                        <i class="bi bi-plus-circle me-1"></i>
                        Create Technical Evaluation

                    </a>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection