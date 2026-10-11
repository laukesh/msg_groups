@extends('layouts.app')

@section('title', 'Commissioning Audit Log')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                Commissioning Audit Log
            </h4>

            <div class="text-muted">
                {{ $project->project_code ?? '' }}
                -
                {{ $project->project_name ?? '' }}
            </div>
        </div>

        <a href="{{ route('admin.projects.commissioning.index', $project) }}"
           class="btn btn-outline-secondary">

            <i class="bi bi-arrow-left"></i>
            Back to Commissioning

        </a>

    </div>


    {{-- KPI Cards --}}
    <div class="row g-3 mb-4">

        <div class="col-md-2">

            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">

                    <div class="text-muted small">
                        Total Logs
                    </div>

                    <h4 class="mb-0">
                        {{ number_format($totalLogs) }}
                    </h4>

                </div>
            </div>

        </div>


        <div class="col-md-2">

            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">

                    <div class="text-muted small">
                        Today
                    </div>

                    <h4 class="mb-0">
                        {{ number_format($todayLogs) }}
                    </h4>

                </div>
            </div>

        </div>


        <div class="col-md-2">

            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">

                    <div class="text-muted small">
                        Created
                    </div>

                    <h4 class="mb-0">
                        {{ number_format($createdLogs) }}
                    </h4>

                </div>
            </div>

        </div>


        <div class="col-md-2">

            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">

                    <div class="text-muted small">
                        Updated
                    </div>

                    <h4 class="mb-0">
                        {{ number_format($updatedLogs) }}
                    </h4>

                </div>
            </div>

        </div>


        <div class="col-md-2">

            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">

                    <div class="text-muted small">
                        Workflow
                    </div>

                    <h4 class="mb-0">
                        {{ number_format($workflowLogs) }}
                    </h4>

                </div>
            </div>

        </div>

    </div>


    {{-- Filters --}}
    <div class="card shadow-sm border-0 mb-4">

        <div class="card-body">

            <form method="GET">

                <div class="row g-3">

                    <div class="col-md-3">

                        <label class="form-label">
                            Search
                        </label>

                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            placeholder="Action, user, description..."
                            value="{{ request('search') }}"
                        >

                    </div>


                    <div class="col-md-2">

                        <label class="form-label">
                            Action
                        </label>

                        <select name="action"
                                class="form-select">

                            <option value="">
                                All Actions
                            </option>

                            @foreach($actions as $action)

                                <option value="{{ $action }}"
                                    @selected(request('action') === $action)>

                                    {{ ucwords(str_replace('_', ' ', $action)) }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-2">

                        <label class="form-label">
                            From Date
                        </label>

                        <input
                            type="date"
                            name="from_date"
                            class="form-control"
                            value="{{ request('from_date') }}"
                        >

                    </div>


                    <div class="col-md-2">

                        <label class="form-label">
                            To Date
                        </label>

                        <input
                            type="date"
                            name="to_date"
                            class="form-control"
                            value="{{ request('to_date') }}"
                        >

                    </div>


                    <div class="col-md-3 d-flex align-items-end gap-2">

                        <button class="btn btn-primary">

                            <i class="bi bi-search"></i>
                            Search

                        </button>

                        <a
                            href="{{ route('admin.projects.commissioning.audit.index', $project) }}"
                            class="btn btn-outline-secondary">

                            Reset

                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- Audit Table --}}
    <div class="card shadow-sm border-0">

        <div class="card-header bg-white">

            <div class="d-flex justify-content-between align-items-center">

                <strong>
                    Activity History
                </strong>

                <span class="text-muted small">

                    {{ $logs->total() }} records

                </span>

            </div>

        </div>


        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">

                    <tr>

                        <th width="170">
                            Date & Time
                        </th>

                        <th width="160">
                            User
                        </th>

                        <th width="120">
                            Action
                        </th>

                        <th width="180">
                            Record
                        </th>

                        <th>
                            Description
                        </th>

                        <th width="130">
                            IP Address
                        </th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($logs as $log)

                        @php

                            $actionClass = match($log->action) {

                                'created' => 'success',

                                'updated' => 'primary',

                                'deleted' => 'danger',

                                'submitted' => 'warning',

                                'approved' => 'success',

                                'rejected' => 'danger',

                                'cancelled' => 'secondary',

                                default => 'info',

                            };

                            $recordType = class_basename(
                                $log->auditable_type ?? ''
                            );

                        @endphp


                        <tr>

                            <td>

                                @if($log->created_at)

                                    <div>
                                        {{ $log->created_at->format('d M Y') }}
                                    </div>

                                    <small class="text-muted">
                                        {{ $log->created_at->format('h:i A') }}
                                    </small>

                                @endif

                            </td>


                            <td>

                                @if($log->user)

                                    <div class="fw-semibold">
                                        {{ $log->user->name }}
                                    </div>

                                    @if($log->user->email)

                                        <small class="text-muted">
                                            {{ $log->user->email }}
                                        </small>

                                    @endif

                                @else

                                    <span class="text-muted">
                                        System
                                    </span>

                                @endif

                            </td>


                            <td>

                                <span class="badge bg-{{ $actionClass }}">

                                    {{ ucwords(
                                        str_replace('_', ' ', $log->action)
                                    ) }}

                                </span>

                            </td>


                            <td>

                                <div class="fw-semibold">

                                    {{ $recordType ?: '—' }}

                                </div>

                                @if($log->auditable_id)

                                    <small class="text-muted">

                                        ID:
                                        {{ $log->auditable_id }}

                                    </small>

                                @endif

                            </td>


                            <td>

                                {{ $log->description ?: '—' }}

                                @if($log->old_values || $log->new_values)

                                    <div class="mt-1">

                                        <button
                                            type="button"
                                            class="btn btn-sm btn-link p-0"
                                            data-bs-toggle="modal"
                                            data-bs-target="#auditModal{{ $log->id }}">

                                            View Changes

                                        </button>

                                    </div>

                                @endif

                            </td>


                            <td>

                                <small>
                                    {{ $log->ip_address ?: '—' }}
                                </small>

                            </td>

                        </tr>


                        {{-- Changes Modal --}}
                        @if($log->old_values || $log->new_values)

                            <div
                                class="modal fade"
                                id="auditModal{{ $log->id }}"
                                tabindex="-1">

                                <div class="modal-dialog modal-lg">

                                    <div class="modal-content">

                                        <div class="modal-header">

                                            <h5 class="modal-title">
                                                Audit Changes
                                            </h5>

                                            <button
                                                type="button"
                                                class="btn-close"
                                                data-bs-dismiss="modal">
                                            </button>

                                        </div>


                                        <div class="modal-body">

                                            @if($log->old_values)

                                                <h6>
                                                    Previous Values
                                                </h6>

                                                <pre class="bg-light p-3 rounded small">{{ json_encode($log->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>

                                            @endif


                                            @if($log->new_values)

                                                <h6 class="mt-3">
                                                    New Values
                                                </h6>

                                                <pre class="bg-light p-3 rounded small">{{ json_encode($log->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>

                                            @endif

                                        </div>


                                        <div class="modal-footer">

                                            <button
                                                type="button"
                                                class="btn btn-secondary"
                                                data-bs-dismiss="modal">

                                                Close

                                            </button>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        @endif

                    @empty

                        <tr>

                            <td
                                colspan="6"
                                class="text-center py-5 text-muted">

                                <i class="bi bi-clock-history fs-2 d-block mb-2"></i>

                                No audit activity found.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        @if($logs->hasPages())

            <div class="card-footer bg-white">

                {{ $logs->links() }}

            </div>

        @endif

    </div>

</div>

@endsection