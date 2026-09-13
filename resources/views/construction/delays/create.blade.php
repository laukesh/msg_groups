@extends('layouts.app')

@section('title', 'Report Construction Delay')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                Report Construction Delay
            </h4>

            <div class="text-muted">
                {{ $project->project_number ?? $project->project_code }}
                -
                {{ $project->project_name }}
            </div>

        </div>


        <a href="{{ route(
            'admin.projects.construction.delays.index',
            $project
        ) }}"
           class="btn btn-light border">

            <i class="bi bi-arrow-left me-1"></i>
            Back to Delays

        </a>

    </div>


    {{-- Errors --}}
    @if($errors->any())

        <div class="alert alert-danger">

            <div class="fw-semibold mb-2">
                Please fix the following errors:
            </div>

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    <form method="POST"
          action="{{ route(
              'admin.projects.construction.delays.store',
              $project
          ) }}">

        @csrf


        <div class="row">

            {{-- Main Form --}}
            <div class="col-lg-8">

                {{-- Basic Information --}}
                <div class="card shadow-sm border-0 mb-4">

                    <div class="card-header bg-white">

                        <h5 class="mb-0">
                            Delay Information
                        </h5>

                    </div>


                    <div class="card-body">

                        <div class="row">

                            {{-- Delay Type --}}
                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Delay Type
                                    <span class="text-danger">*</span>
                                </label>

                                <select name="delay_type"
                                        class="form-select @error('delay_type') is-invalid @enderror"
                                        required>

                                    <option value="">
                                        Select Delay Type
                                    </option>

                                    @foreach([
                                        'Design',
                                        'Client',
                                        'Consultant',
                                        'Contractor',
                                        'Material',
                                        'Equipment',
                                        'Manpower',
                                        'Weather',
                                        'Authority',
                                        'Site Condition',
                                        'Financial',
                                        'Procurement',
                                        'Force Majeure',
                                        'Other'
                                    ] as $type)

                                        <option value="{{ $type }}"
                                            @selected(old('delay_type') === $type)>

                                            {{ $type }}

                                        </option>

                                    @endforeach

                                </select>

                                @error('delay_type')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            {{-- Priority --}}
                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Priority
                                    <span class="text-danger">*</span>
                                </label>

                                <select name="priority"
                                        class="form-select"
                                        required>

                                    @foreach([
                                        'Low',
                                        'Medium',
                                        'High',
                                        'Critical'
                                    ] as $priority)

                                        <option value="{{ $priority }}"
                                            @selected(
                                                old(
                                                    'priority',
                                                    'Medium'
                                                ) === $priority
                                            )>

                                            {{ $priority }}

                                        </option>

                                    @endforeach

                                </select>

                            </div>


                            {{-- Delay Title --}}
                            <div class="col-md-12 mb-3">

                                <label class="form-label">
                                    Delay Title
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="text"
                                       name="delay_title"
                                       value="{{ old('delay_title') }}"
                                       class="form-control @error('delay_title') is-invalid @enderror"
                                       placeholder="Enter delay title"
                                       required>

                                @error('delay_title')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            {{-- Delay Date --}}
                            <div class="col-md-4 mb-3">

                                <label class="form-label">
                                    Delay Date
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="date"
                                       name="delay_date"
                                       value="{{ old(
                                           'delay_date',
                                           now()->format('Y-m-d')
                                       ) }}"
                                       class="form-control @error('delay_date') is-invalid @enderror"
                                       required>

                                @error('delay_date')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            {{-- Start Date --}}
                            <div class="col-md-4 mb-3">

                                <label class="form-label">
                                    Delay Start Date
                                </label>

                                <input type="date"
                                       name="start_date"
                                       value="{{ old('start_date') }}"
                                       class="form-control @error('start_date') is-invalid @enderror">

                                @error('start_date')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            {{-- End Date --}}
                            <div class="col-md-4 mb-3">

                                <label class="form-label">
                                    Delay End Date
                                </label>

                                <input type="date"
                                       name="end_date"
                                       value="{{ old('end_date') }}"
                                       class="form-control @error('end_date') is-invalid @enderror">

                                @error('end_date')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            {{-- Reported Days --}}
                            <div class="col-md-4 mb-3">

                                <label class="form-label">
                                    Reported Delay Days
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="number"
                                       name="reported_days"
                                       value="{{ old('reported_days', 0) }}"
                                       class="form-control @error('reported_days') is-invalid @enderror"
                                       min="0"
                                       required>

                                @error('reported_days')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            {{-- Cost Impact --}}
                            <div class="col-md-4 mb-3">

                                <label class="form-label">
                                    Estimated Cost Impact
                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">
                                        $
                                    </span>

                                    <input type="number"
                                           name="cost_impact"
                                           value="{{ old('cost_impact', 0) }}"
                                           class="form-control @error('cost_impact') is-invalid @enderror"
                                           min="0"
                                           step="0.01">

                                </div>

                                @error('cost_impact')

                                    <div class="text-danger small mt-1">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            {{-- EOT Requested --}}
                            <div class="col-md-4 mb-3">

                                <label class="form-label">
                                    EOT Requested
                                </label>

                                <div class="input-group">

                                    <input type="number"
                                           name="eot_requested_days"
                                           value="{{ old(
                                               'eot_requested_days',
                                               0
                                           ) }}"
                                           class="form-control"
                                           min="0">

                                    <span class="input-group-text">
                                        Days
                                    </span>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- Project References --}}
                <div class="card shadow-sm border-0 mb-4">

                    <div class="card-header bg-white">

                        <h5 class="mb-0">
                            Project References
                        </h5>

                    </div>


                    <div class="card-body">

                        <div class="row">

                            {{-- Work Order --}}
                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Work Order
                                </label>

                                <select name="construction_work_order_id"
                                        id="work_order_id"
                                        class="form-select">

                                    <option value="">
                                        Select Work Order
                                    </option>

                                    @foreach($workOrders as $workOrder)

                                        <option value="{{ $workOrder->id }}"
                                            @selected(
                                                old(
                                                    'construction_work_order_id'
                                                ) == $workOrder->id
                                            )>

                                            {{ $workOrder->work_order_number }}
                                            -
                                            {{ $workOrder->work_order_title }}

                                        </option>

                                    @endforeach

                                </select>

                            </div>


                            {{-- Schedule Activity --}}
                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Schedule Activity
                                </label>

                                <select name="construction_schedule_activity_id"
                                        id="schedule_activity_id"
                                        class="form-select" required>

                                    <option value="">
                                        Select Schedule Activity
                                    </option>

                                    @foreach($scheduleActivities as $activity)

                                        <option value="{{ $activity->id }}"
                                                data-work-order="{{ $activity->construction_work_order_id }}"
                                            @selected(
                                                old(
                                                    'construction_schedule_activity_id'
                                                ) == $activity->id
                                            )>

                                            {{ $activity->activity_code }}
                                            -
                                            {{ $activity->activity_name }}

                                        </option>

                                    @endforeach

                                </select>

                            </div>


                            {{-- Claim --}}
                            <div class="col-md-12 mb-3">

                                <label class="form-label">
                                    Related Claim
                                </label>

                                <select name="construction_claim_id"
                                        class="form-select">

                                    <option value="">
                                        No Claim / Select Later
                                    </option>

                                    @foreach($claims as $claim)

                                        <option value="{{ $claim->id }}"
                                            @selected(
                                                old(
                                                    'construction_claim_id'
                                                ) == $claim->id
                                            )>

                                            {{ $claim->claim_number }}
                                            -
                                            {{ $claim->subject }}

                                        </option>

                                    @endforeach

                                </select>

                                <small class="text-muted">
                                    A delay does not require a claim.
                                    A claim can be linked later.
                                </small>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- Responsibility --}}
                <div class="card shadow-sm border-0 mb-4">

                    <div class="card-header bg-white">

                        <h5 class="mb-0">
                            Responsibility
                        </h5>

                    </div>


                    <div class="card-body">

                        <div class="row">

                            {{-- Claimant Type --}}
                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Reported By / Claimant Type
                                </label>

                                <select name="claimant_type"
                                        class="form-select">

                                    <option value="">
                                        Select
                                    </option>

                                    @foreach([
                                        'Contractor',
                                        'Consultant',
                                        'Client',
                                        'Supplier',
                                        'Other'
                                    ] as $type)

                                        <option value="{{ $type }}"
                                            @selected(
                                                old('claimant_type') === $type
                                            )>

                                            {{ $type }}

                                        </option>

                                    @endforeach

                                </select>

                            </div>


                            {{-- Claimant Name --}}
                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Claimant / Reporting Party
                                </label>

                                <input type="text"
                                       name="claimant_name"
                                       value="{{ old('claimant_name') }}"
                                       class="form-control"
                                       placeholder="Enter name">

                            </div>


                            {{-- Responsible Party --}}
                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Responsible Party
                                </label>

                                <select name="responsible_party_type"
                                        class="form-select">

                                    <option value="">
                                        Select
                                    </option>

                                    @foreach([
                                        'Client',
                                        'Consultant',
                                        'Contractor',
                                        'Supplier',
                                        'Authority',
                                        'Third Party',
                                        'Force Majeure',
                                        'Other',
                                        'Unknown'
                                    ] as $type)

                                        <option value="{{ $type }}"
                                            @selected(
                                                old(
                                                    'responsible_party_type'
                                                ) === $type
                                            )>

                                            {{ $type }}

                                        </option>

                                    @endforeach

                                </select>

                            </div>


                            {{-- Responsible Name --}}
                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Responsible Party Name
                                </label>

                                <input type="text"
                                       name="responsible_party_name"
                                       value="{{ old(
                                           'responsible_party_name'
                                       ) }}"
                                       class="form-control"
                                       placeholder="Enter responsible party">

                            </div>

                        </div>

                    </div>

                </div>


                {{-- Description & Impact --}}
                <div class="card shadow-sm border-0 mb-4">

                    <div class="card-header bg-white">

                        <h5 class="mb-0">
                            Delay Analysis
                        </h5>

                    </div>


                    <div class="card-body">

                        <div class="mb-3">

                            <label class="form-label">
                                Description
                            </label>

                            <textarea name="description"
                                      rows="4"
                                      class="form-control"
                                      placeholder="Describe the delay event">{{ old('description') }}</textarea>

                        </div>


                        <div class="mb-3">

                            <label class="form-label">
                                Cause
                            </label>

                            <textarea name="cause"
                                      rows="4"
                                      class="form-control"
                                      placeholder="Explain the cause of delay">{{ old('cause') }}</textarea>

                        </div>


                        <div class="mb-3">

                            <label class="form-label">
                                Impact Description
                            </label>

                            <textarea name="impact_description"
                                      rows="4"
                                      class="form-control"
                                      placeholder="Describe the impact on construction">{{ old('impact_description') }}</textarea>

                        </div>


                        <div class="mb-3">

                            <label class="form-label">
                                Schedule Impact
                            </label>

                            <textarea name="schedule_impact"
                                      rows="4"
                                      class="form-control"
                                      placeholder="Describe the impact on project schedule">{{ old('schedule_impact') }}</textarea>

                        </div>


                        <div>

                            <label class="form-label">
                                Remarks
                            </label>

                            <textarea name="remarks"
                                      rows="3"
                                      class="form-control"
                                      placeholder="Additional remarks">{{ old('remarks') }}</textarea>

                        </div>

                    </div>

                </div>


                {{-- Buttons --}}
                <div class="d-flex justify-content-end gap-2 mb-4">

                    <a href="{{ route(
                        'admin.projects.construction.delays.index',
                        $project
                    ) }}"
                       class="btn btn-light border">

                        Cancel

                    </a>

                    <button type="submit"
                            class="btn btn-primary">

                        <i class="bi bi-save me-1"></i>
                        Save Delay

                    </button>

                </div>

            </div>


            {{-- Side Information --}}
            <div class="col-lg-4">

                <div class="card shadow-sm border-0 mb-4">

                    <div class="card-header bg-white">

                        <h6 class="mb-0">
                            Delay Workflow
                        </h6>

                    </div>

                    <div class="card-body">

                        <div class="d-flex mb-3">

                            <span class="badge bg-secondary me-2">
                                1
                            </span>

                            <div>
                                <strong>Draft</strong>
                                <div class="small text-muted">
                                    Record the delay event.
                                </div>
                            </div>

                        </div>


                        <div class="d-flex mb-3">

                            <span class="badge bg-info text-dark me-2">
                                2
                            </span>

                            <div>
                                <strong>Submitted</strong>
                                <div class="small text-muted">
                                    Submit for review.
                                </div>
                            </div>

                        </div>


                        <div class="d-flex mb-3">

                            <span class="badge bg-warning text-dark me-2">
                                3
                            </span>

                            <div>
                                <strong>Assessment</strong>
                                <div class="small text-muted">
                                    Assess time and cost impact.
                                </div>
                            </div>

                        </div>


                        <div class="d-flex mb-3">

                            <span class="badge bg-success me-2">
                                4
                            </span>

                            <div>
                                <strong>Approval</strong>
                                <div class="small text-muted">
                                    Approve or partially approve.
                                </div>
                            </div>

                        </div>


                        <div class="d-flex">

                            <span class="badge bg-dark me-2">
                                5
                            </span>

                            <div>
                                <strong>Closed</strong>
                                <div class="small text-muted">
                                    Complete the delay record.
                                </div>
                            </div>

                        </div>

                    </div>

                </div>


                <div class="card shadow-sm border-0">

                    <div class="card-body">

                        <div class="d-flex">

                            <i class="bi bi-info-circle text-primary me-2"></i>

                            <div class="small text-muted">

                                A delay records an actual
                                schedule-impacting event.

                                It does not automatically create
                                a claim or approve an extension
                                of time.

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </form>

</div>


{{-- Activity Filtering --}}
<script>

document.addEventListener('DOMContentLoaded', function () {

    const workOrder =
        document.getElementById('work_order_id');

    const activity =
        document.getElementById('schedule_activity_id');

    if (!workOrder || !activity) {
        return;
    }

    function filterActivities() {

        const selectedWorkOrder =
            workOrder.value;

        Array.from(
            activity.options
        ).forEach(function (option, index) {

            if (index === 0) {
                option.hidden = false;
                return;
            }

            const activityWorkOrder =
                option.dataset.workOrder;

            if (
                !selectedWorkOrder ||
                !activityWorkOrder ||
                activityWorkOrder === selectedWorkOrder
            ) {
                option.hidden = false;
            } else {
                option.hidden = true;

                if (option.selected) {
                    activity.value = '';
                }
            }

        });

    }

    workOrder.addEventListener(
        'change',
        filterActivities
    );

    filterActivities();

});

</script>

@endsection