@extends('layouts.app')

@section('title', 'Edit Delay')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">Edit Delay</h4>

            <div class="text-muted">
                {{ $project->project_number }}
                -
                {{ $project->project_name }}
            </div>
        </div>

        <div class="d-flex gap-2">

            <a href="{{ route('admin.projects.construction.delays.show', [$project, $delay]) }}"
               class="btn btn-outline-secondary">
                <i class="bi bi-eye"></i>
                View
            </a>

            <a href="{{ route('admin.projects.construction.delays.index', $project) }}"
               class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i>
                Back
            </a>

        </div>

    </div>


    @if ($errors->any())

        <div class="alert alert-danger">

            <strong>Please correct the following errors:</strong>

            <ul class="mb-0 mt-2">

                @foreach ($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    <form method="POST"
          action="{{ route('admin.projects.construction.delays.update', [$project, $delay]) }}">

        @csrf
        @method('PUT')


        {{-- Basic Information --}}
        <div class="card shadow-sm mb-4">

            <div class="card-header bg-white">

                <h5 class="mb-0">
                    Delay Information
                </h5>

            </div>

            <div class="card-body">

                <div class="row g-3">

                    <div class="col-md-4">

                        <label class="form-label">
                            Delay Number
                        </label>

                        <input type="text"
                               class="form-control"
                               value="{{ $delay->delay_number }}"
                               readonly>

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Delay Type <span class="text-danger">*</span>
                        </label>

                        <select name="delay_type"
                                class="form-select"
                                required>

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
                                    @selected(old('delay_type', $delay->delay_type) === $type)>
                                    {{ $type }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Priority <span class="text-danger">*</span>
                        </label>

                        <select name="priority"
                                class="form-select"
                                required>

                            @foreach(['Low','Medium','High','Critical'] as $priority)

                                <option value="{{ $priority }}"
                                    @selected(old('priority', $delay->priority) === $priority)>
                                    {{ $priority }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-8">

                        <label class="form-label">
                            Delay Title <span class="text-danger">*</span>
                        </label>

                        <input type="text"
                               name="delay_title"
                               class="form-control"
                               value="{{ old('delay_title', $delay->delay_title) }}"
                               required>

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Delay Date <span class="text-danger">*</span>
                        </label>

                        <input type="date"
                               name="delay_date"
                               class="form-control"
                               value="{{ old('delay_date', optional($delay->delay_date)->format('Y-m-d')) }}"
                               required>

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Start Date
                        </label>

                        <input type="date"
                               name="start_date"
                               class="form-control"
                               value="{{ old('start_date', optional($delay->start_date)->format('Y-m-d')) }}">

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            End Date
                        </label>

                        <input type="date"
                               name="end_date"
                               class="form-control"
                               value="{{ old('end_date', optional($delay->end_date)->format('Y-m-d')) }}">

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Reported Days
                        </label>

                        <input type="number"
                               name="reported_days"
                               class="form-control"
                               min="0"
                               value="{{ old('reported_days', $delay->reported_days) }}">

                    </div>

                </div>

            </div>

        </div>


        {{-- Project References --}}
        <div class="card shadow-sm mb-4">

            <div class="card-header bg-white">

                <h5 class="mb-0">
                    Project References
                </h5>

            </div>

            <div class="card-body">

                <div class="row g-3">

                    <div class="col-md-4">

                        <label class="form-label">
                            Work Order
                        </label>

                        <select name="construction_work_order_id"
                                id="work_order_id"
                                class="form-select">

                            <option value="">Select Work Order</option>

                            @foreach($workOrders as $workOrder)

                                <option value="{{ $workOrder->id }}"
                                    @selected(old(
                                        'construction_work_order_id',
                                        $delay->construction_work_order_id
                                    ) == $workOrder->id)>

                                    {{ $workOrder->work_order_number }}
                                    -
                                    {{ $workOrder->work_order_title }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Schedule Activity
                        </label>

                        <select name="construction_schedule_activity_id"
                                id="schedule_activity_id"
                                class="form-select">

                            <option value="">Select Activity</option>

                            @foreach($scheduleActivities as $activity)

                                <option value="{{ $activity->id }}"
                                        data-work-order="{{ $activity->construction_work_order_id }}"
                                    @selected(old(
                                        'construction_schedule_activity_id',
                                        $delay->construction_schedule_activity_id
                                    ) == $activity->id)>

                                    {{ $activity->activity_code }}
                                    -
                                    {{ $activity->activity_name }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Related Claim
                        </label>

                        <select name="construction_claim_id"
                                class="form-select">

                            <option value="">Select Claim</option>

                            @foreach($claims as $claim)

                                <option value="{{ $claim->id }}"
                                    @selected(old(
                                        'construction_claim_id',
                                        $delay->construction_claim_id
                                    ) == $claim->id)>

                                    {{ $claim->claim_number }}
                                    -
                                    {{ $claim->subject }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>

            </div>

        </div>


        {{-- Responsibility --}}
        <div class="card shadow-sm mb-4">

            <div class="card-header bg-white">

                <h5 class="mb-0">
                    Responsibility
                </h5>

            </div>

            <div class="card-body">

                <div class="row g-3">

                    <div class="col-md-6">

                        <label class="form-label">
                            Claimant Type
                        </label>

                        <select name="claimant_type"
                                class="form-select">

                            <option value="">Select</option>

                            @foreach(['Contractor','Consultant','Client','Supplier','Other'] as $type)

                                <option value="{{ $type }}"
                                    @selected(old('claimant_type', $delay->claimant_type) === $type)>
                                    {{ $type }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">
                            Claimant Name
                        </label>

                        <input type="text"
                               name="claimant_name"
                               class="form-control"
                               value="{{ old('claimant_name', $delay->claimant_name) }}">

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">
                            Responsible Party
                        </label>

                        <select name="responsible_party_type"
                                class="form-select">

                            <option value="">Select</option>

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
                                    @selected(old(
                                        'responsible_party_type',
                                        $delay->responsible_party_type
                                    ) === $type)>
                                    {{ $type }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">
                            Responsible Party Name
                        </label>

                        <input type="text"
                               name="responsible_party_name"
                               class="form-control"
                               value="{{ old(
                                   'responsible_party_name',
                                   $delay->responsible_party_name
                               ) }}">

                    </div>

                </div>

            </div>

        </div>


        {{-- Impact --}}
        <div class="card shadow-sm mb-4">

            <div class="card-header bg-white">

                <h5 class="mb-0">
                    Delay Impact
                </h5>

            </div>

            <div class="card-body">

                <div class="row g-3">

                    <div class="col-md-4">

                        <label class="form-label">
                            Cost Impact ($)
                        </label>

                        <input type="number"
                               step="0.01"
                               min="0"
                               name="cost_impact"
                               class="form-control"
                               value="{{ old('cost_impact', $delay->cost_impact) }}">

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            EOT Requested (Days)
                        </label>

                        <input type="number"
                               min="0"
                               name="eot_requested_days"
                               class="form-control"
                               value="{{ old(
                                   'eot_requested_days',
                                   $delay->eot_requested_days
                               ) }}">

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Schedule Impact
                        </label>

                        <select name="schedule_impact"
                                class="form-select">

                            <option value="">Select</option>

                            <option value="Yes"
                                @selected(old('schedule_impact', $delay->schedule_impact) === 'Yes')}>
                                Yes
                            </option>

                            <option value="No"
                                @selected(old('schedule_impact', $delay->schedule_impact) === 'No')}>
                                No
                            </option>

                        </select>

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">
                            Description
                        </label>

                        <textarea name="description"
                                  rows="4"
                                  class="form-control">{{ old('description', $delay->description) }}</textarea>

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">
                            Cause
                        </label>

                        <textarea name="cause"
                                  rows="4"
                                  class="form-control">{{ old('cause', $delay->cause) }}</textarea>

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">
                            Impact Description
                        </label>

                        <textarea name="impact_description"
                                  rows="4"
                                  class="form-control">{{ old(
                                      'impact_description',
                                      $delay->impact_description
                                  ) }}</textarea>

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">
                            Remarks
                        </label>

                        <textarea name="remarks"
                                  rows="4"
                                  class="form-control">{{ old('remarks', $delay->remarks) }}</textarea>

                    </div>

                </div>

            </div>

        </div>


        {{-- Actions --}}
        <div class="card shadow-sm">

            <div class="card-body d-flex justify-content-end gap-2">

                <a href="{{ route(
                    'admin.projects.construction.delays.show',
                    [$project, $delay]
                ) }}"
                   class="btn btn-outline-secondary">

                    Cancel

                </a>

                <button type="submit"
                        class="btn btn-primary">

                    <i class="bi bi-check-lg"></i>
                    Update Delay

                </button>

            </div>

        </div>

    </form>

</div>


<script>

document.addEventListener('DOMContentLoaded', function () {

    const workOrder = document.getElementById('work_order_id');
    const activity = document.getElementById('schedule_activity_id');

    function filterActivities() {

        const selectedWorkOrder = workOrder.value;

        Array.from(activity.options).forEach(function (option) {

            if (!option.value) {
                option.hidden = false;
                return;
            }

            option.hidden =
                selectedWorkOrder &&
                option.dataset.workOrder !== selectedWorkOrder;

        });

        if (
            activity.value &&
            selectedWorkOrder &&
            activity.options[activity.selectedIndex]?.dataset.workOrder !== selectedWorkOrder
        ) {
            activity.value = '';
        }
    }

    workOrder.addEventListener('change', filterActivities);

    filterActivities();

});

</script>

@endsection