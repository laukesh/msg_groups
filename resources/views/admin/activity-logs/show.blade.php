@extends('layouts.app')

@section('title', 'Activity Details')

@section('content')

<div class="container-fluid py-3">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="fw-bold mb-1">

                <i class="ri-file-search-line text-primary me-1"></i>

                Activity Details

            </h4>

            <div class="text-muted small">

                Activity #{{ $activityLog->id }}

            </div>

        </div>

        <a href="{{ route('admin.activity-logs.index') }}"
           class="btn btn-outline-secondary">

            <i class="ri-arrow-left-line me-1"></i>

            Back

        </a>

    </div>


    {{-- =========================================================
         SUMMARY
    ========================================================== --}}

    <div class="row g-3 mb-4">

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        User
                    </div>

                    <div class="fw-bold fs-5">

                        {{ $activityLog->user?->name ?? 'System' }}

                    </div>

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Module
                    </div>

                    <div class="fw-bold fs-5">

                        {{ $activityLog->module ?? 'System' }}

                    </div>

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Action
                    </div>

                    <div class="fw-bold fs-5">

                        {{ ucwords(str_replace('_', ' ', $activityLog->action)) }}

                    </div>

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Date / Time
                    </div>

                    <div class="fw-bold">

                        {{ $activityLog->created_at->format('d M Y h:i:s A') }}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         REQUEST INFORMATION
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <strong>

                <i class="ri-global-line me-1"></i>

                Request Information

            </strong>

        </div>

        <div class="card-body">

            <div class="row g-3">

                <div class="col-md-3">

                    <div class="text-muted small">
                        IP Address
                    </div>

                    <code>
                        {{ $activityLog->ip_address ?? '-' }}
                    </code>

                </div>

                <div class="col-md-3">

                    <div class="text-muted small">
                        Method
                    </div>

                    <span class="badge bg-dark">

                        {{ $activityLog->method ?? '-' }}

                    </span>

                </div>

                <div class="col-md-6">

                    <div class="text-muted small">
                        Route
                    </div>

                    <code>

                        {{ $activityLog->route ?? '-' }}

                    </code>

                </div>

                <div class="col-12">

                    <div class="text-muted small">
                        User Agent
                    </div>

                    <div class="small text-break">

                        {{ $activityLog->user_agent ?? '-' }}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         DESCRIPTION
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <strong>

                <i class="ri-information-line me-1"></i>

                Description

            </strong>

        </div>

        <div class="card-body">

            {{ $activityLog->description }}

        </div>

    </div>


    {{-- =========================================================
         OLD / NEW VALUES
    ========================================================== --}}

    <div class="row g-4">

        <div class="col-lg-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-danger-subtle text-danger">

                    <strong>

                        <i class="ri-arrow-left-circle-line me-1"></i>

                        Old Values

                    </strong>

                </div>

                <div class="card-body">

                    @if($activityLog->old_values)

                        <pre class="bg-light rounded p-3 mb-0"
                             style="max-height:500px;overflow:auto;">{{ json_encode($activityLog->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>

                    @else

                        <div class="text-muted">
                            No old values.
                        </div>

                    @endif

                </div>

            </div>

        </div>


        <div class="col-lg-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-success-subtle text-success">

                    <strong>

                        <i class="ri-arrow-right-circle-line me-1"></i>

                        New Values

                    </strong>

                </div>

                <div class="card-body">

                    @if($activityLog->new_values)

                        <pre class="bg-light rounded p-3 mb-0"
                             style="max-height:500px;overflow:auto;">{{ json_encode($activityLog->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>

                    @else

                        <div class="text-muted">
                            No new values.
                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>

</div>

@endsection