@extends('layouts.app')

@section('title', 'Risk Details')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small mb-1">
                Risk Management
            </div>

            <h4 class="mb-1">
                {{ $risk->risk_title }}
            </h4>

            <div class="text-muted">
                {{ $risk->risk_number }}
                |
                {{ $project->project_code ?? $project->project_number }}
                -
                {{ $project->project_name }}
            </div>

        </div>


        <div class="d-flex gap-2">

            <a href="{{ route(
                'admin.projects.construction.risks.index',
                $project
            ) }}"
            class="btn btn-outline-secondary">

                Back

            </a>

            @if(in_array($risk->status, [
                'Draft',
                'Identified',
                'Rejected'
            ]))

                <a href="{{ route(
                    'admin.projects.construction.risks.edit',
                    [$project, $risk]
                ) }}"
                class="btn btn-outline-primary">

                    Edit

                </a>

            @endif

        </div>

    </div>


    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- Risk Summary --}}
    <div class="row g-3 mb-4">

        <div class="col-lg-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Risk Rating
                    </div>

                    @php
                        $ratingClass = match($risk->risk_rating) {
                            'Critical' => 'danger',
                            'High' => 'warning',
                            'Medium' => 'info',
                            default => 'success',
                        };
                    @endphp

                    <div class="mt-2">
                        <span class="badge bg-{{ $ratingClass }} fs-6">
                            {{ $risk->risk_rating }}
                        </span>
                    </div>

                </div>

            </div>

        </div>


        <div class="col-lg-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Risk Score
                    </div>

                    <h3 class="mb-0 mt-1">
                        {{ $risk->risk_score }}
                        <small class="text-muted">/ 25</small>
                    </h3>

                </div>

            </div>

        </div>


        <div class="col-lg-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Priority
                    </div>

                    <h5 class="mb-0 mt-2">
                        {{ $risk->priority }}
                    </h5>

                </div>

            </div>

        </div>


        <div class="col-lg-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Status
                    </div>

                    <h5 class="mb-0 mt-2">
                        {{ $risk->status }}
                    </h5>

                </div>

            </div>

        </div>

    </div>


    <div class="row g-4">

        {{-- Main --}}
        <div class="col-lg-8">


            {{-- Risk Information --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white">
                    <strong>Risk Information</strong>
                </div>

                <div class="card-body">

                    <div class="row g-4">

                        <div class="col-md-6">

                            <div class="text-muted small">
                                Category
                            </div>

                            <div class="fw-semibold">
                                {{ $risk->risk_category }}
                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small">
                                Risk Date
                            </div>

                            <div class="fw-semibold">
                                {{ optional($risk->risk_date)->format('d-m-Y') }}
                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small">
                                Work Order
                            </div>

                            <div class="fw-semibold">

                                @if($risk->workOrder)

                                    {{ $risk->workOrder->work_order_number }}
                                    -
                                    {{ $risk->workOrder->work_order_title }}

                                @else

                                    <span class="text-muted">
                                        Not Linked
                                    </span>

                                @endif

                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small">
                                Schedule Activity
                            </div>

                            <div class="fw-semibold">

                                @if($risk->scheduleActivity)

                                    {{ $risk->scheduleActivity->activity_code }}
                                    -
                                    {{ $risk->scheduleActivity->activity_name }}

                                @else

                                    <span class="text-muted">
                                        Not Linked
                                    </span>

                                @endif

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Assessment --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white">
                    <strong>Risk Assessment</strong>
                </div>

                <div class="card-body">

                    <div class="row g-3 mb-4">

                        <div class="col-md-4">

                            <div class="border rounded p-3">

                                <div class="text-muted small">
                                    Probability
                                </div>

                                <div class="fw-semibold mt-1">
                                    {{ $risk->probability }}
                                </div>

                            </div>

                        </div>


                        <div class="col-md-4">

                            <div class="border rounded p-3">

                                <div class="text-muted small">
                                    Impact
                                </div>

                                <div class="fw-semibold mt-1">
                                    {{ $risk->impact_level }}
                                </div>

                            </div>

                        </div>


                        <div class="col-md-4">

                            <div class="border rounded p-3">

                                <div class="text-muted small">
                                    Score
                                </div>

                                <div class="fw-semibold mt-1">
                                    {{ $risk->risk_score }} / 25
                                </div>

                            </div>

                        </div>

                    </div>


                    <div class="mb-4">

                        <div class="text-muted small mb-1">
                            Risk Description
                        </div>

                        <div>
                            {!! nl2br(e($risk->risk_description ?: '—')) !!}
                        </div>

                    </div>


                    <div class="mb-4">

                        <div class="text-muted small mb-1">
                            Risk Cause
                        </div>

                        <div>
                            {!! nl2br(e($risk->risk_cause ?: '—')) !!}
                        </div>

                    </div>


                    <div>

                        <div class="text-muted small mb-1">
                            Potential Impact
                        </div>

                        <div>
                            {!! nl2br(e($risk->potential_impact ?: '—')) !!}
                        </div>

                    </div>

                </div>

            </div>


            {{-- Financial / Schedule Impact --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white">
                    <strong>Potential Impact</strong>
                </div>

                <div class="card-body">

                    <div class="row g-3">

                        <div class="col-md-6">

                            <div class="text-muted small">
                                Potential Cost Impact
                            </div>

                            <div class="fs-5 fw-semibold">
                                $ {{ number_format(
                                    $risk->potential_cost_impact ?? 0,
                                    2
                                ) }}
                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small">
                                Potential Delay
                            </div>

                            <div class="fs-5 fw-semibold">
                                {{ $risk->potential_delay_days ?? 0 }}
                                days
                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Response --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white">
                    <strong>Risk Response</strong>
                </div>

                <div class="card-body">

                    <div class="row g-3">

                        <div class="col-md-4">

                            <div class="text-muted small">
                                Strategy
                            </div>

                            <div class="fw-semibold">
                                {{ $risk->response_strategy ?: '—' }}
                            </div>

                        </div>


                        <div class="col-md-4">

                            <div class="text-muted small">
                                Owner Type
                            </div>

                            <div class="fw-semibold">
                                {{ $risk->owner_type ?: '—' }}
                            </div>

                        </div>


                        <div class="col-md-4">

                            <div class="text-muted small">
                                Owner
                            </div>

                            <div class="fw-semibold">
                                {{ $risk->owner_name ?: '—' }}
                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small">
                                Target Resolution
                            </div>

                            <div class="fw-semibold">

                                @if($risk->target_resolution_date)

                                    {{ $risk->target_resolution_date->format('d-m-Y') }}

                                @else

                                    —

                                @endif

                            </div>

                        </div>


                        <div class="col-12">

                            <div class="text-muted small mb-1">
                                Response / Mitigation Plan
                            </div>

                            <div>
                                {!! nl2br(e($risk->response_plan ?: '—')) !!}
                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Actions --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white">

                    <div class="d-flex justify-content-between align-items-center">

                        <strong>
                            Risk Actions
                        </strong>

                        <a href="{{ route(
                            'admin.projects.construction.risks.actions.create',
                            [$project, $risk]
                        ) }}"
                        class="btn btn-sm btn-primary">

                            Add Action

                        </a>

                    </div>

                </div>

                <div class="card-body p-0">

                    @if($risk->actions->count())

                        <div class="table-responsive">

                            <table class="table table-hover mb-0">

                                <thead class="table-light">

                                    <tr>
                                        <th>Action</th>
                                        <th>Type</th>
                                        <th>Assigned To</th>
                                        <th>Target Date</th>
                                        <th>Status</th>
                                        <th></th>
                                    </tr>

                                </thead>

                                <tbody>

                                    @foreach($risk->actions as $action)

                                        <tr>

                                            <td>
                                                <strong>
                                                    {{ $action->action_title }}
                                                </strong>
                                            </td>

                                            <td>
                                                {{ $action->action_type }}
                                            </td>

                                            <td>
                                                {{ $action->assignedTo->name
                                                    ?? $action->assigned_to_name
                                                    ?? '—' }}
                                            </td>

                                            <td>
                                                {{ $action->target_date
                                                    ? $action->target_date->format('d-m-Y')
                                                    : '—' }}
                                            </td>

                                            <td>
                                                <span class="badge bg-secondary">
                                                    {{ $action->status }}
                                                </span>
                                            </td>

                                            <td>

                                                <a href="{{ route(
                                                    'admin.projects.construction.risks.actions.edit',
                                                    [$project, $risk, $action]
                                                ) }}"
                                                class="btn btn-sm btn-outline-primary">
                                                    Edit
                                                </a>

                                            </td>

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    @else

                        <div class="text-center text-muted py-4">
                            No risk actions added.
                        </div>

                    @endif

                </div>

            </div>


            {{-- Documents --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white">

                    <div class="d-flex justify-content-between align-items-center">

                        <strong>
                            Risk Documents
                        </strong>

                        <a href="{{ route(
                            'admin.projects.construction.risks.documents.create',
                            [$project, $risk]
                        ) }}"
                        class="btn btn-sm btn-primary">

                            Upload Document

                        </a>

                    </div>

                </div>

                <div class="card-body">

                    @if($risk->documents->count())

                        @foreach($risk->documents as $document)

                            <div class="d-flex justify-content-between align-items-center border-bottom py-3">

                                <div>

                                    <div class="fw-semibold">
                                        {{ $document->document_title }}
                                    </div>

                                    <div class="text-muted small">

                                        {{ $document->document_type }}

                                        @if($document->file_size)
                                            ·
                                            {{ number_format(
                                                $document->file_size / 1024,
                                                1
                                            ) }} KB
                                        @endif

                                    </div>

                                </div>


                                <div class="d-flex gap-2">

                                    <a href="{{ route(
                                        'admin.projects.construction.risks.documents.view',
                                        [$project, $risk, $document]
                                    ) }}"
                                    target="_blank"
                                    class="btn btn-sm btn-outline-secondary">
                                        View
                                    </a>

                                    <a href="{{ route(
                                        'admin.projects.construction.risks.documents.download',
                                        [$project, $risk, $document]
                                    ) }}"
                                    class="btn btn-sm btn-outline-primary">
                                        Download
                                    </a>

                                </div>

                            </div>

                        @endforeach

                    @else

                        <div class="text-center text-muted py-4">
                            No documents uploaded.
                        </div>

                    @endif

                </div>

            </div>

        </div>


        {{-- Sidebar --}}
        <div class="col-lg-4">


            {{-- Workflow --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white">
                    <strong>Risk Workflow</strong>
                </div>

                <div class="card-body">

                    @if($risk->status === 'Draft')

                        <form method="POST"
                              action="{{ route(
                                  'admin.projects.construction.risks.submit',
                                  [$project, $risk]
                              ) }}">

                            @csrf

                            <button class="btn btn-primary w-100">
                                Submit Risk
                            </button>

                        </form>


                    @elseif($risk->status === 'Identified')

                        <form method="POST"
                              action="{{ route(
                                  'admin.projects.construction.risks.assess',
                                  [$project, $risk]
                              ) }}"
                              class="mb-2">

                            @csrf

                            <button class="btn btn-primary w-100">
                                Start Assessment
                            </button>

                        </form>


                        <form method="POST"
                              action="{{ route(
                                  'admin.projects.construction.risks.escalate',
                                  [$project, $risk]
                              ) }}">

                            @csrf

                            <button class="btn btn-outline-danger w-100">
                                Escalate
                            </button>

                        </form>


                    @elseif($risk->status === 'Under Assessment')

                        <form method="POST"
                              action="{{ route(
                                  'admin.projects.construction.risks.mitigation',
                                  [$project, $risk]
                              ) }}"
                              class="mb-2">

                            @csrf

                            <button class="btn btn-primary w-100">
                                Plan Mitigation
                            </button>

                        </form>


                        <form method="POST"
                              action="{{ route(
                                  'admin.projects.construction.risks.escalate',
                                  [$project, $risk]
                              ) }}">

                            @csrf

                            <button class="btn btn-outline-danger w-100">
                                Escalate
                            </button>

                        </form>


                    @elseif($risk->status === 'Mitigation Planned')

                        <form method="POST"
                              action="{{ route(
                                  'admin.projects.construction.risks.monitor',
                                  [$project, $risk]
                              ) }}"
                              class="mb-2">

                            @csrf

                            <button class="btn btn-primary w-100">
                                Start Monitoring
                            </button>

                        </form>


                        <form method="POST"
                              action="{{ route(
                                  'admin.projects.construction.risks.accept',
                                  [$project, $risk]
                              ) }}">

                            @csrf

                            <button class="btn btn-outline-secondary w-100">
                                Accept Risk
                            </button>

                        </form>


                    @elseif($risk->status === 'Monitoring')

                        <form method="POST"
                              action="{{ route(
                                  'admin.projects.construction.risks.close',
                                  [$project, $risk]
                              ) }}"
                              class="mb-2">

                            @csrf

                            <button class="btn btn-success w-100">
                                Close Risk
                            </button>

                        </form>


                        <form method="POST"
                              action="{{ route(
                                  'admin.projects.construction.risks.escalate',
                                  [$project, $risk]
                              ) }}">

                            @csrf

                            <button class="btn btn-outline-danger w-100">
                                Escalate
                            </button>

                        </form>


                    @elseif($risk->status === 'Accepted')

                        <form method="POST"
                              action="{{ route(
                                  'admin.projects.construction.risks.close',
                                  [$project, $risk]
                              ) }}">

                            @csrf

                            <button class="btn btn-success w-100">
                                Close Risk
                            </button>

                        </form>


                    @elseif($risk->status === 'Escalated')

                        <div class="alert alert-danger mb-0">
                            This risk has been escalated.
                        </div>

                    @elseif($risk->status === 'Closed')

                        <div class="alert alert-success mb-0">
                            This risk is closed.
                        </div>

                    @endif

                </div>

            </div>


            {{-- Residual Risk --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white">
                    <strong>Residual Risk</strong>
                </div>

                <div class="card-body">

                    <div class="d-flex justify-content-between mb-3">

                        <span class="text-muted">
                            Probability
                        </span>

                        <strong>
                            {{ $risk->residual_probability ?: '—' }}
                        </strong>

                    </div>


                    <div class="d-flex justify-content-between mb-3">

                        <span class="text-muted">
                            Impact
                        </span>

                        <strong>
                            {{ $risk->residual_impact_level ?: '—' }}
                        </strong>

                    </div>


                    <div class="d-flex justify-content-between mb-3">

                        <span class="text-muted">
                            Score
                        </span>

                        <strong>
                            {{ $risk->residual_risk_score ?? 0 }}
                        </strong>

                    </div>


                    <div class="d-flex justify-content-between">

                        <span class="text-muted">
                            Rating
                        </span>

                        <strong>
                            {{ $risk->residual_risk_rating ?: '—' }}
                        </strong>

                    </div>

                </div>

            </div>


            {{-- Audit --}}
            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white">
                    <strong>Record Information</strong>
                </div>

                <div class="card-body">

                    <div class="mb-3">

                        <div class="text-muted small">
                            Identified By
                        </div>

                        <div>
                            {{ $risk->identifiedBy->name ?? '—' }}
                        </div>

                    </div>


                    <div class="mb-3">

                        <div class="text-muted small">
                            Created By
                        </div>

                        <div>
                            {{ $risk->creator->name ?? '—' }}
                        </div>

                    </div>


                    <div class="mb-3">

                        <div class="text-muted small">
                            Created At
                        </div>

                        <div>
                            {{ optional($risk->created_at)->format('d-m-Y H:i') }}
                        </div>

                    </div>


                    <div>

                        <div class="text-muted small">
                            Last Updated
                        </div>

                        <div>
                            {{ optional($risk->updated_at)->format('d-m-Y H:i') }}
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection