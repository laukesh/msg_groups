@extends('layouts.app')

@section('title', 'Material Request')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                {{ $materialRequest->request_number }}
            </h4>

            <div class="text-muted">
                {{ $project->project_name }}
            </div>

        </div>


        <div class="d-flex gap-2">

            <a href="{{ route('admin.projects.construction.materials.requests.index', [
                'project' => $project->id
            ]) }}"
               class="btn btn-secondary">
                ← Back to Requests
            </a>

            @if(in_array(
                $materialRequest->status,
                ['Draft', 'Changes Requested']
            ))

                <a href="{{ route('admin.projects.construction.materials.requests.edit', [
                    'project' => $project->id,
                    'materialRequest' => $materialRequest->id,
                ]) }}"
                   class="btn btn-outline-primary">
                    Edit
                </a>

            @endif

        </div>

    </div>


    {{-- Summary --}}
    <div class="row g-3 mb-4">

        <div class="col-md-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Request Number
                    </div>

                    <div class="fw-bold fs-5">
                        {{ $materialRequest->request_number }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Request Date
                    </div>

                    <div class="fw-bold fs-5">

                        {{ $materialRequest->request_date
                            ? $materialRequest->request_date->format('d M Y')
                            : '—'
                        }}

                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Required Date
                    </div>

                    <div class="fw-bold fs-5">

                        {{ $materialRequest->required_date
                            ? $materialRequest->required_date->format('d M Y')
                            : '—'
                        }}

                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Status
                    </div>

                    @php

                        $badgeClass = match(
                            $materialRequest->status
                        ) {

                            'Draft' =>
                                'bg-secondary',

                            'Submitted' =>
                                'bg-primary',

                            'Under Review' =>
                                'bg-warning text-dark',

                            'Changes Requested' =>
                                'bg-danger',

                            'Approved' =>
                                'bg-success',

                            'Rejected' =>
                                'bg-danger',

                            'Cancelled' =>
                                'bg-dark',

                            'Completed' =>
                                'bg-success',

                            default =>
                                'bg-secondary',
                        };

                    @endphp

                    <span class="badge {{ $badgeClass }} fs-6">
                        {{ $materialRequest->status }}
                    </span>

                </div>

            </div>

        </div>

    </div>


    <div class="row">

        <div class="col-lg-8">

            {{-- Request Information --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white">

                    <h5 class="mb-0">
                        Request Information
                    </h5>

                </div>

                <div class="card-body">

                    <div class="row g-4">

                        <div class="col-md-6">

                            <div class="text-muted small">
                                Project
                            </div>

                            <div class="fw-semibold">
                                {{ $project->project_name }}
                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small">
                                Work Order
                            </div>

                            @if($materialRequest->workOrder)

                                <div class="fw-semibold">
                                    {{ $materialRequest->workOrder->work_order_number }}
                                </div>

                                <small class="text-muted">
                                    {{ $materialRequest->workOrder->work_order_title }}
                                </small>

                            @else

                                <span class="text-muted">
                                    General Project
                                </span>

                            @endif

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small">
                                Requested By
                            </div>

                            <div class="fw-semibold">
                                {{ $materialRequest->requestedBy?->name ?? '—' }}
                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small">
                                Approved By
                            </div>

                            <div class="fw-semibold">
                                {{ $materialRequest->approvedBy?->name ?? '—' }}
                            </div>

                        </div>


                        <div class="col-12">

                            <div class="text-muted small">
                                Remarks
                            </div>

                            <div>
                                {{ $materialRequest->remarks ?: '—' }}
                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Items --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white">

                    <h5 class="mb-0">
                        Requested Materials
                    </h5>

                </div>


                <div class="card-body p-0">

                    <div class="table-responsive">

                        <table class="table table-hover align-middle mb-0">

                            <thead class="table-light">

                                <tr>

                                    <th>
                                        Material
                                    </th>

                                    <th>
                                        Requirement
                                    </th>

                                    <th>
                                        Required
                                    </th>

                                    <th>
                                        Requested
                                    </th>

                                    <th>
                                        Remaining
                                    </th>

                                    <th>
                                        Remarks
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                            @forelse($materialRequest->items as $item)

                                <tr>

                                    <td>

                                        <div class="fw-semibold">
                                            {{ $item->material?->material_name ?? '—' }}
                                        </div>

                                        <small class="text-muted">
                                            {{ $item->material?->material_code ?? '' }}
                                        </small>

                                    </td>


                                    <td>

                                        @if($item->materialRequirement)

                                            <a href="{{ route('admin.projects.construction.materials.requirements.show', [
                                                'project' => $project->id,
                                                'requirement' => $item->materialRequirement->id,
                                            ]) }}">

                                                Requirement #{{ $item->materialRequirement->id }}

                                            </a>

                                        @else

                                            <span class="text-muted">
                                                Not Linked
                                            </span>

                                        @endif

                                    </td>


                                    <td>

                                        @if(
                                            isset(
                                                $requirementSummary[
                                                    $item->materialRequirement?->id
                                                ]
                                            )
                                        )

                                            {{ number_format(
                                                $requirementSummary[
                                                    $item->materialRequirement->id
                                                ]['required'],
                                                4
                                            ) }}

                                            {{ $item->unit }}

                                        @else

                                            —

                                        @endif

                                    </td>


                                    <td>

                                        {{ number_format(
                                            (float) $item->requested_quantity,
                                            4
                                        ) }}

                                        {{ $item->unit }}

                                    </td>


                                    <td>

                                        @if(
                                            isset(
                                                $requirementSummary[
                                                    $item->materialRequirement?->id
                                                ]
                                            )
                                        )

                                            {{ number_format(
                                                $requirementSummary[
                                                    $item->materialRequirement->id
                                                ]['remaining'],
                                                4
                                            ) }}

                                            {{ $item->unit }}

                                        @else

                                            —

                                        @endif

                                    </td>


                                    <td>

                                        {{ $item->remarks ?: '—' }}

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="6"
                                        class="text-center py-4 text-muted">

                                        No material items found.

                                    </td>

                                </tr>

                            @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>


            {{-- Record Information --}}
            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white">

                    <h6 class="mb-0">
                        Record Information
                    </h6>

                </div>

                <div class="card-body">

                    <div class="row g-3">

                        <div class="col-md-6">

                            <div class="text-muted small">
                                Created By
                            </div>

                            {{ $materialRequest->creator?->name ?? '—' }}

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small">
                                Created At
                            </div>

                            {{ $materialRequest->created_at?->format('d M Y H:i') ?? '—' }}

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small">
                                Updated By
                            </div>

                            {{ $materialRequest->updater?->name ?? '—' }}

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small">
                                Updated At
                            </div>

                            {{ $materialRequest->updated_at?->format('d M Y H:i') ?? '—' }}

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Workflow --}}
        <div class="col-lg-4">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white">

                    <h5 class="mb-0">
                        Workflow
                    </h5>

                </div>

                <div class="card-body">


                    @if($materialRequest->status === 'Draft')

                        <div class="alert alert-secondary">
                            Request is being prepared.
                        </div>

                        <form method="POST"
                              action="{{ route('admin.projects.construction.materials.requests.submit', [
                                  'project' => $project->id,
                                  'materialRequest' => $materialRequest->id,
                              ]) }}"
                              class="mb-2">

                            @csrf

                            <button type="submit"
                                    class="btn btn-primary w-100">
                                Submit for Review
                            </button>

                        </form>


                    @elseif($materialRequest->status === 'Submitted')

                        <div class="alert alert-primary">
                            Request has been submitted for review.
                        </div>

                        <form method="POST"
                              action="{{ route('admin.projects.construction.materials.requests.review', [
                                  'project' => $project->id,
                                  'materialRequest' => $materialRequest->id,
                              ]) }}">

                            @csrf

                            <button type="submit"
                                    class="btn btn-primary w-100">
                                Start Review
                            </button>

                        </form>


                    @elseif($materialRequest->status === 'Under Review')

                        <div class="alert alert-warning">

                            <strong>
                                Under Review
                            </strong>

                            <div class="small mt-1">
                                Review the requested materials before approval.
                            </div>

                        </div>


                        <form method="POST"
                              action="{{ route('admin.projects.construction.materials.requests.approve', [
                                  'project' => $project->id,
                                  'materialRequest' => $materialRequest->id,
                              ]) }}"
                              class="mb-2">

                            @csrf

                            <button type="submit"
                                    class="btn btn-success w-100">
                                Approve Request
                            </button>

                        </form>


                        <form method="POST"
                              action="{{ route('admin.projects.construction.materials.requests.request-changes', [
                                  'project' => $project->id,
                                  'materialRequest' => $materialRequest->id,
                              ]) }}"
                              class="mb-2">

                            @csrf

                            <button type="submit"
                                    class="btn btn-warning w-100">
                                Request Changes
                            </button>

                        </form>


                        <form method="POST"
                              action="{{ route('admin.projects.construction.materials.requests.reject', [
                                  'project' => $project->id,
                                  'materialRequest' => $materialRequest->id,
                              ]) }}">

                            @csrf

                            <button type="submit"
                                    class="btn btn-outline-danger w-100">
                                Reject Request
                            </button>

                        </form>


                    @elseif($materialRequest->status === 'Changes Requested')

                        <div class="alert alert-danger">

                            Changes have been requested.

                        </div>


                        <a href="{{ route('admin.projects.construction.materials.requests.edit', [
                            'project' => $project->id,
                            'materialRequest' => $materialRequest->id,
                        ]) }}"
                           class="btn btn-primary w-100">
                            Edit & Resubmit
                        </a>


                    @elseif($materialRequest->status === 'Approved')

                        <div class="alert alert-success">

                            <strong>
                                Approved
                            </strong>

                            <div class="small mt-1">
                                This request is approved and can proceed to material delivery.
                            </div>

                        </div>


                        <a href="{{ route('admin.projects.construction.materials.deliveries.create', [
                            'project' => $project->id,
                            'materialRequest' => $materialRequest->id,
                        ]) }}"
                           class="btn btn-primary w-100">
                            Create Material Delivery
                        </a>


                    @elseif($materialRequest->status === 'Rejected')

                        <div class="alert alert-danger mb-0">
                            This material request has been rejected.
                        </div>


                    @elseif($materialRequest->status === 'Cancelled')

                        <div class="alert alert-dark mb-0">
                            This material request has been cancelled.
                        </div>


                    @elseif($materialRequest->status === 'Completed')

                        <div class="alert alert-success mb-0">
                            This material request has been completed.
                        </div>

                    @endif


                    @if(
                        !in_array(
                            $materialRequest->status,
                            [
                                'Approved',
                                'Rejected',
                                'Cancelled',
                                'Completed',
                            ],
                            true
                        )
                    )

                        <hr>

                        <form method="POST"
                              action="{{ route('admin.projects.construction.materials.requests.cancel', [
                                  'project' => $project->id,
                                  'materialRequest' => $materialRequest->id,
                              ]) }}">

                            @csrf

                            <button type="submit"
                                    class="btn btn-outline-danger w-100"
                                    onclick="return confirm('Cancel this material request?')">
                                Cancel Request
                            </button>

                        </form>

                    @endif

                </div>

            </div>

        </div>

    </div>

</div>

@endsection