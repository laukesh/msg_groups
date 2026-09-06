@extends('layouts.app')

@section('title', 'Delay Details')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                {{ $delay->delay_number }}
            </h4>

            <div class="text-muted">
                {{ $delay->delay_title }}
            </div>

        </div>

        <div class="d-flex gap-2">

            @if(in_array($delay->status, ['Draft', 'Rejected']))

                <a href="{{ route(
                    'admin.projects.construction.delays.edit',
                    [$project, $delay]
                ) }}"
                   class="btn btn-outline-primary">

                    <i class="bi bi-pencil"></i>
                    Edit

                </a>

            @endif


            <a href="{{ route(
                'admin.projects.construction.delays.documents.index',
                [$project, $delay]
            ) }}"
               class="btn btn-outline-secondary">

                <i class="bi bi-paperclip"></i>
                Documents
                <span class="badge bg-secondary">
                    {{ $delay->documents->count() }}
                </span>

            </a>


            <a href="{{ route(
                'admin.projects.construction.delays.index',
                $project
            ) }}"
               class="btn btn-outline-secondary">

                Back

            </a>

        </div>

    </div>


    {{-- Status --}}
    <div class="card shadow-sm mb-4">

        <div class="card-body">

            <div class="row align-items-center">

                <div class="col-md-3">

                    <small class="text-muted d-block">
                        Status
                    </small>

                    <span class="badge
                        @if($delay->status === 'Approved') bg-success
                        @elseif($delay->status === 'Partially Approved') bg-warning text-dark
                        @elseif($delay->status === 'Rejected') bg-danger
                        @elseif($delay->status === 'Closed') bg-dark
                        @elseif($delay->status === 'Under Review') bg-info text-dark
                        @elseif($delay->status === 'Under Assessment') bg-primary
                        @elseif($delay->status === 'Submitted') bg-warning text-dark
                        @else bg-secondary
                        @endif">

                        {{ $delay->status }}

                    </span>

                </div>


                <div class="col-md-3">

                    <small class="text-muted d-block">
                        Priority
                    </small>

                    <span class="badge
                        @if($delay->priority === 'Critical') bg-danger
                        @elseif($delay->priority === 'High') bg-warning text-dark
                        @elseif($delay->priority === 'Medium') bg-info text-dark
                        @else bg-secondary
                        @endif">

                        {{ $delay->priority }}

                    </span>

                </div>


                <div class="col-md-3">

                    <small class="text-muted d-block">
                        Delay Type
                    </small>

                    {{ $delay->delay_type }}

                </div>


                <div class="col-md-3">

                    <small class="text-muted d-block">
                        Reported By
                    </small>

                    {{ optional($delay->reportedBy)->name ?? '-' }}

                </div>

            </div>

        </div>

    </div>


    {{-- Basic Information --}}
    <div class="card shadow-sm mb-4">

        <div class="card-header bg-white">
            <h5 class="mb-0">Delay Information</h5>
        </div>

        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-4">
                    <small class="text-muted d-block">Delay Date</small>
                    {{ optional($delay->delay_date)->format('d-m-Y') ?? '-' }}
                </div>

                <div class="col-md-4">
                    <small class="text-muted d-block">Start Date</small>
                    {{ optional($delay->start_date)->format('d-m-Y') ?? '-' }}
                </div>

                <div class="col-md-4">
                    <small class="text-muted d-block">End Date</small>
                    {{ optional($delay->end_date)->format('d-m-Y') ?? '-' }}
                </div>

                <div class="col-md-4">
                    <small class="text-muted d-block">Reported Days</small>
                    {{ $delay->reported_days ?? 0 }}
                </div>

                <div class="col-md-4">
                    <small class="text-muted d-block">Assessed Days</small>
                    {{ $delay->assessed_days ?? 0 }}
                </div>

                <div class="col-md-4">
                    <small class="text-muted d-block">Approved Days</small>
                    {{ $delay->approved_days ?? 0 }}
                </div>

            </div>

        </div>

    </div>


    {{-- Project References --}}
    <div class="card shadow-sm mb-4">

        <div class="card-header bg-white">
            <h5 class="mb-0">Project References</h5>
        </div>

        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-4">

                    <small class="text-muted d-block">
                        Work Order
                    </small>

                    @if($delay->workOrder)

                        {{ $delay->workOrder->work_order_number }}
                        -
                        {{ $delay->workOrder->work_order_title }}

                    @else
                        -
                    @endif

                </div>


                <div class="col-md-4">

                    <small class="text-muted d-block">
                        Schedule Activity
                    </small>

                    @if($delay->scheduleActivity)

                        {{ $delay->scheduleActivity->activity_code }}
                        -
                        {{ $delay->scheduleActivity->activity_name }}

                    @else
                        -
                    @endif

                </div>


                <div class="col-md-4">

                    <small class="text-muted d-block">
                        Related Claim
                    </small>

                    @if($delay->claim)

                        {{ $delay->claim->claim_number }}
                        -
                        {{ $delay->claim->subject }}

                    @else
                        -
                    @endif

                </div>

            </div>

        </div>

    </div>


    {{-- Responsibility --}}
    <div class="card shadow-sm mb-4">

        <div class="card-header bg-white">
            <h5 class="mb-0">Responsibility</h5>
        </div>

        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-3">
                    <small class="text-muted d-block">Claimant Type</small>
                    {{ $delay->claimant_type ?? '-' }}
                </div>

                <div class="col-md-3">
                    <small class="text-muted d-block">Claimant Name</small>
                    {{ $delay->claimant_name ?? '-' }}
                </div>

                <div class="col-md-3">
                    <small class="text-muted d-block">Responsible Party</small>
                    {{ $delay->responsible_party_type ?? '-' }}
                </div>

                <div class="col-md-3">
                    <small class="text-muted d-block">Responsible Name</small>
                    {{ $delay->responsible_party_name ?? '-' }}
                </div>

            </div>

        </div>

    </div>


    {{-- Impact --}}
    <div class="card shadow-sm mb-4">

        <div class="card-header bg-white">
            <h5 class="mb-0">Impact & EOT</h5>
        </div>

        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-3">
                    <small class="text-muted d-block">
                        Cost Impact
                    </small>
                    ${{ number_format($delay->cost_impact ?? 0, 2) }}
                </div>

                <div class="col-md-3">
                    <small class="text-muted d-block">
                        Assessed Cost Impact
                    </small>
                    ${{ number_format($delay->assessed_cost_impact ?? 0, 2) }}
                </div>

                <div class="col-md-3">
                    <small class="text-muted d-block">
                        Approved Cost Impact
                    </small>
                    ${{ number_format($delay->approved_cost_impact ?? 0, 2) }}
                </div>

                <div class="col-md-3">
                    <small class="text-muted d-block">
                        EOT Requested
                    </small>
                    {{ $delay->eot_requested_days ?? 0 }} days
                </div>

                <div class="col-md-3">
                    <small class="text-muted d-block">
                        EOT Assessed
                    </small>
                    {{ $delay->eot_assessed_days ?? 0 }} days
                </div>

                <div class="col-md-3">
                    <small class="text-muted d-block">
                        EOT Approved
                    </small>
                    {{ $delay->eot_approved_days ?? 0 }} days
                </div>

                <div class="col-md-3">
                    <small class="text-muted d-block">
                        Excusable
                    </small>

                    @if($delay->is_excusable)
                        <span class="badge bg-success">Yes</span>
                    @else
                        <span class="badge bg-secondary">No</span>
                    @endif

                </div>

                <div class="col-md-3">
                    <small class="text-muted d-block">
                        Compensable
                    </small>

                    @if($delay->is_compensable)
                        <span class="badge bg-success">Yes</span>
                    @else
                        <span class="badge bg-secondary">No</span>
                    @endif

                </div>

            </div>

        </div>

    </div>


    {{-- Description --}}
    <div class="card shadow-sm mb-4">

        <div class="card-header bg-white">
            <h5 class="mb-0">Description & Cause</h5>
        </div>

        <div class="card-body">

            <div class="mb-4">

                <strong>Description</strong>

                <div class="border rounded p-3 mt-2 bg-light">

                    {!! nl2br(e($delay->description ?? '-')) !!}

                </div>

            </div>


            <div class="mb-4">

                <strong>Cause</strong>

                <div class="border rounded p-3 mt-2 bg-light">

                    {!! nl2br(e($delay->cause ?? '-')) !!}

                </div>

            </div>


            <div>

                <strong>Impact Description</strong>

                <div class="border rounded p-3 mt-2 bg-light">

                    {!! nl2br(e($delay->impact_description ?? '-')) !!}

                </div>

            </div>

        </div>

    </div>


    {{-- Assessment --}}
    <div class="card shadow-sm mb-4">

        <div class="card-header bg-white">
            <h5 class="mb-0">Assessment</h5>
        </div>

        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-3">
                    <small class="text-muted d-block">Assessed By</small>
                    {{ optional($delay->assessedBy)->name ?? '-' }}
                </div>

                <div class="col-md-3">
                    <small class="text-muted d-block">Assessment Date</small>
                    {{ optional($delay->assessment_date)->format('d-m-Y') ?? '-' }}
                </div>

                <div class="col-md-3">
                    <small class="text-muted d-block">Excusable Days</small>
                    {{ $delay->excusable_days ?? 0 }}
                </div>

                <div class="col-md-3">
                    <small class="text-muted d-block">Compensable Days</small>
                    {{ $delay->compensable_days ?? 0 }}
                </div>

            </div>


            @if($delay->assessment_remarks)

                <hr>

                <strong>Assessment Remarks</strong>

                <div class="border rounded p-3 mt-2 bg-light">
                    {!! nl2br(e($delay->assessment_remarks)) !!}
                </div>

            @endif

        </div>

    </div>


    {{-- Approval --}}
    <div class="card shadow-sm mb-4">

        <div class="card-header bg-white">
            <h5 class="mb-0">Approval</h5>
        </div>

        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-3">
                    <small class="text-muted d-block">Approved By</small>
                    {{ optional($delay->approvedBy)->name ?? '-' }}
                </div>

                <div class="col-md-3">
                    <small class="text-muted d-block">Approval Date</small>
                    {{ optional($delay->approval_date)->format('d-m-Y') ?? '-' }}
                </div>

                <div class="col-md-3">
                    <small class="text-muted d-block">Approved Days</small>
                    {{ $delay->approved_days ?? 0 }}
                </div>

                <div class="col-md-3">
                    <small class="text-muted d-block">Approved Cost</small>
                    ${{ number_format($delay->approved_cost_impact ?? 0, 2) }}
                </div>

            </div>


            @if($delay->approval_remarks)

                <hr>

                <strong>Approval Remarks</strong>

                <div class="border rounded p-3 mt-2 bg-light">
                    {!! nl2br(e($delay->approval_remarks)) !!}
                </div>

            @endif


            @if($delay->rejection_remarks)

                <hr>

                <strong>Rejection Remarks</strong>

                <div class="border rounded p-3 mt-2 bg-light">
                    {!! nl2br(e($delay->rejection_remarks)) !!}
                </div>

            @endif

        </div>

    </div>


    {{-- Workflow --}}
    <div class="card shadow-sm mb-4">

        <div class="card-header bg-white">
            <h5 class="mb-0">Workflow</h5>
        </div>

        <div class="card-body">

            <div class="d-flex flex-wrap gap-2">

                @if($delay->status === 'Draft')

                    <form method="POST"
                          action="{{ route(
                              'admin.projects.construction.delays.submit',
                              [$project, $delay]
                          ) }}">

                        @csrf

                        <button class="btn btn-primary">
                            Submit for Review
                        </button>

                    </form>

                @endif


                @if($delay->status === 'Submitted')

                    <form method="POST"
                          action="{{ route(
                              'admin.projects.construction.delays.review',
                              [$project, $delay]
                          ) }}">

                        @csrf

                        <button class="btn btn-info">
                            Start Review
                        </button>

                    </form>

                @endif


                @if($delay->status === 'Under Review')

                    <a href="{{ route(
                        'admin.projects.construction.delays.assessment',
                        [$project, $delay]
                    ) }}"
                       class="btn btn-primary">

                        Assessment

                    </a>

                @endif


                @if($delay->status === 'Under Assessment')

                    <a href="{{ route(
                        'admin.projects.construction.delays.approval',
                        [$project, $delay]
                    ) }}"
                       class="btn btn-success">

                        Approval

                    </a>

                @endif


                @if(in_array($delay->status, [
                    'Submitted',
                    'Under Review',
                    'Under Assessment'
                ]))

                    <a href="{{ route(
                        'admin.projects.construction.delays.rejection',
                        [$project, $delay]
                    ) }}"
                       class="btn btn-outline-danger">

                        Reject

                    </a>

                @endif


                @if(in_array($delay->status, [
                    'Approved',
                    'Partially Approved'
                ]))

                    <form method="POST"
                          action="{{ route(
                              'admin.projects.construction.delays.close',
                              [$project, $delay]
                          ) }}">

                        @csrf

                        <button class="btn btn-dark">
                            Close Delay
                        </button>

                    </form>

                @endif

            </div>

        </div>

    </div>


    {{-- History --}}
    <div class="card shadow-sm">

        <div class="card-header bg-white">

            <h5 class="mb-0">
                Workflow History
            </h5>

        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover mb-0">

                    <thead class="table-light">

                        <tr>
                            <th>Date</th>
                            <th>Action</th>
                            <th>Status</th>
                            <th>Remarks</th>
                            <th>Performed By</th>
                        </tr>

                    </thead>

                    <tbody>

                        @forelse($delay->history as $history)

                            <tr>

                                <td>
                                    {{ optional($history->performed_at)->format('d-m-Y H:i') }}
                                </td>

                                <td>
                                    {{ $history->action }}
                                </td>

                                <td>

                                    @if($history->old_status)
                                        {{ $history->old_status }}
                                        →
                                    @endif

                                    {{ $history->new_status }}

                                </td>

                                <td>
                                    {{ $history->remarks ?? '-' }}
                                </td>

                                <td>
                                    {{ optional($history->performedBy)->name ?? '-' }}
                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="5"
                                    class="text-center text-muted py-4">

                                    No workflow history available.

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