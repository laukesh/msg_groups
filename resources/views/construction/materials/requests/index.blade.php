@extends('layouts.app')

@section('title', 'Material Requests')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                Material Requests
            </h4>

            <div class="text-muted">
                {{ $project->project_name }}
            </div>

        </div>


        <div class="d-flex gap-2">

            <a href="{{ route('admin.projects.construction.materials.index', [
                'project' => $project->id
            ]) }}"
               class="btn btn-outline-secondary">
                ← Materials
            </a>

            <a href="{{ route('admin.projects.construction.materials.requests.create', [
                'project' => $project->id
            ]) }}"
               class="btn btn-primary">
                + New Request
            </a>

        </div>

    </div>


    {{-- Summary --}}
    <div class="row g-3 mb-4">

        <div class="col-md-2">

            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">

                    <div class="text-muted small">
                        Total
                    </div>

                    <div class="fs-3 fw-bold">
                        {{ $summary['total'] }}
                    </div>

                </div>
            </div>

        </div>


        <div class="col-md-2">

            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">

                    <div class="text-muted small">
                        Draft
                    </div>

                    <div class="fs-3 fw-bold text-secondary">
                        {{ $summary['draft'] }}
                    </div>

                </div>
            </div>

        </div>


        <div class="col-md-2">

            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">

                    <div class="text-muted small">
                        Submitted
                    </div>

                    <div class="fs-3 fw-bold text-primary">
                        {{ $summary['submitted'] }}
                    </div>

                </div>
            </div>

        </div>


        <div class="col-md-2">

            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">

                    <div class="text-muted small">
                        Under Review
                    </div>

                    <div class="fs-3 fw-bold text-warning">
                        {{ $summary['under_review'] }}
                    </div>

                </div>
            </div>

        </div>


        <div class="col-md-2">

            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">

                    <div class="text-muted small">
                        Approved
                    </div>

                    <div class="fs-3 fw-bold text-success">
                        {{ $summary['approved'] }}
                    </div>

                </div>
            </div>

        </div>


        <div class="col-md-2">

            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">

                    <div class="text-muted small">
                        Changes
                    </div>

                    <div class="fs-3 fw-bold text-danger">
                        {{ $summary['changes_requested'] }}
                    </div>

                </div>
            </div>

        </div>

    </div>


    {{-- Filters --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <form method="GET"
                  action="{{ route('admin.projects.construction.materials.requests.index', [
                      'project' => $project->id
                  ]) }}">

                <div class="row g-3 align-items-end">

                    <div class="col-md-6">

                        <label class="form-label">
                            Search
                        </label>

                        <input type="text"
                               name="search"
                               value="{{ request('search') }}"
                               class="form-control"
                               placeholder="Request number, material or work order">

                    </div>


                    <div class="col-md-3">

                        <label class="form-label">
                            Status
                        </label>

                        <select name="status"
                                class="form-select">

                            <option value="">
                                All Statuses
                            </option>

                            @foreach([
                                'Draft',
                                'Submitted',
                                'Under Review',
                                'Changes Requested',
                                'Approved',
                                'Rejected',
                                'Cancelled',
                                'Completed',
                            ] as $status)

                                <option value="{{ $status }}"
                                    @selected(request('status') === $status)>
                                    {{ $status }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-3 d-flex gap-2">

                        <button type="submit"
                                class="btn btn-primary">
                            Search
                        </button>

                        <a href="{{ route('admin.projects.construction.materials.requests.index', [
                            'project' => $project->id
                        ]) }}"
                           class="btn btn-outline-secondary">
                            Reset
                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- Table --}}
    <div class="card border-0 shadow-sm">

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th>
                                Request No.
                            </th>

                            <th>
                                Date
                            </th>

                            <th>
                                Work Order
                            </th>

                            <th>
                                Materials
                            </th>

                            <th>
                                Requested By
                            </th>

                            <th>
                                Status
                            </th>

                            <th class="text-end">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                    @forelse($requests as $materialRequest)

                        <tr>

                            <td>

                                <div class="fw-semibold">
                                    {{ $materialRequest->request_number }}
                                </div>

                            </td>


                            <td>

                                {{ $materialRequest->request_date
                                    ? $materialRequest->request_date->format('d M Y')
                                    : '—'
                                }}

                            </td>


                            <td>

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

                            </td>


                            <td>

                                @foreach($materialRequest->items->take(2) as $item)

                                    <div class="small">

                                        {{ $item->material?->material_name ?? '—' }}

                                        -
                                        {{ number_format((float) $item->requested_quantity, 2) }}
                                        {{ $item->unit }}

                                        @if($item->materialRequirement)

                                            <span class="badge bg-light text-dark">
                                                Requirement Linked
                                            </span>

                                        @endif

                                    </div>

                                @endforeach

                                @if($materialRequest->items->count() > 2)

                                    <small class="text-muted">
                                        + {{ $materialRequest->items->count() - 2 }}
                                        more
                                    </small>

                                @endif

                            </td>


                            <td>

                                {{ $materialRequest->requestedBy?->name ?? '—' }}

                            </td>


                            <td>

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

                                <span class="badge {{ $badgeClass }}">
                                    {{ $materialRequest->status }}
                                </span>

                            </td>


                            <td class="text-end">

                                <a href="{{ route('admin.projects.construction.materials.requests.show', [
                                    'project' => $project->id,
                                    'materialRequest' => $materialRequest->id,
                                ]) }}"
                                   class="btn btn-sm btn-outline-primary">
                                    View
                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="7"
                                class="text-center py-5">

                                <div class="text-muted mb-2">
                                    No material requests found.
                                </div>

                                <a href="{{ route('admin.projects.construction.materials.requests.create', [
                                    'project' => $project->id
                                ]) }}"
                                   class="btn btn-primary btn-sm">
                                    + Create Request
                                </a>

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        @if($requests->hasPages())

            <div class="card-footer bg-white">

                {{ $requests->links() }}

            </div>

        @endif

    </div>

</div>

@endsection