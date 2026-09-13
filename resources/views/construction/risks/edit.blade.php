@extends('layouts.app')

@section('title', 'Edit Risk')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                Edit Risk
            </h4>

            <div class="text-muted">
                {{ $risk->risk_number }}
                -
                {{ $risk->risk_title }}
            </div>
        </div>

        <a href="{{ route(
            'admin.projects.construction.risks.show',
            [$project, $risk]
        ) }}"
        class="btn btn-outline-secondary">

            <i class="bi bi-arrow-left"></i>
            Back to Risk

        </a>

    </div>


    @if($errors->any())

        <div class="alert alert-danger">

            <strong>Please fix the following:</strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

    @endif


    <form method="POST"
          action="{{ route(
              'admin.projects.construction.risks.update',
              [$project, $risk]
          ) }}">

        @csrf
        @method('PUT')


        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white">
                <strong>Risk Information</strong>
            </div>

            <div class="card-body">

                <div class="row g-3">

                    <div class="col-md-8">

                        <label class="form-label">
                            Risk Title <span class="text-danger">*</span>
                        </label>

                        <input type="text"
                               name="risk_title"
                               class="form-control"
                               value="{{ old('risk_title', $risk->risk_title) }}"
                               required>

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Risk Date <span class="text-danger">*</span>
                        </label>

                        <input type="date"
                               name="risk_date"
                               class="form-control"
                               value="{{ old(
                                   'risk_date',
                                   optional($risk->risk_date)->format('Y-m-d')
                               ) }}"
                               required>

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Risk Category
                            <span class="text-danger">*</span>
                        </label>

                        <select name="risk_category"
                                class="form-select"
                                required>

                            @foreach([
                                'Design',
                                'Technical',
                                'Construction',
                                'Procurement',
                                'Material',
                                'Equipment',
                                'Manpower',
                                'Financial',
                                'Commercial',
                                'Contract',
                                'Schedule',
                                'Quality',
                                'HSE',
                                'Environmental',
                                'Regulatory',
                                'Authority',
                                'Stakeholder',
                                'Site Condition',
                                'External',
                                'Other'
                            ] as $value)

                                <option value="{{ $value }}"
                                    @selected(
                                        old(
                                            'risk_category',
                                            $risk->risk_category
                                        ) === $value
                                    )>
                                    {{ $value }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Work Order
                        </label>

                        <select name="construction_work_order_id"
                                class="form-select">

                            <option value="">
                                Not Linked
                            </option>

                            @foreach($workOrders as $workOrder)

                                <option value="{{ $workOrder->id }}"
                                    @selected(
                                        old(
                                            'construction_work_order_id',
                                            $risk->construction_work_order_id
                                        ) == $workOrder->id
                                    )>

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
                                class="form-select">

                            <option value="">
                                Not Linked
                            </option>

                            @foreach($scheduleActivities as $activity)

                                <option value="{{ $activity->id }}"
                                    @selected(
                                        old(
                                            'construction_schedule_activity_id',
                                            $risk->construction_schedule_activity_id
                                        ) == $activity->id
                                    )>

                                    {{ $activity->activity_code }}
                                    -
                                    {{ $activity->activity_name }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-3">

                        <label class="form-label">
                            Probability
                            <span class="text-danger">*</span>
                        </label>

                        <select name="probability"
                                class="form-select"
                                required>

                            @foreach([
                                'Rare',
                                'Unlikely',
                                'Possible',
                                'Likely',
                                'Almost Certain'
                            ] as $value)

                                <option value="{{ $value }}"
                                    @selected(
                                        old(
                                            'probability',
                                            $risk->probability
                                        ) === $value
                                    )>
                                    {{ $value }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-3">

                        <label class="form-label">
                            Impact Level
                            <span class="text-danger">*</span>
                        </label>

                        <select name="impact_level"
                                class="form-select"
                                required>

                            @foreach([
                                'Insignificant',
                                'Minor',
                                'Moderate',
                                'Major',
                                'Severe'
                            ] as $value)

                                <option value="{{ $value }}"
                                    @selected(
                                        old(
                                            'impact_level',
                                            $risk->impact_level
                                        ) === $value
                                    )>
                                    {{ $value }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-3">

                        <label class="form-label">
                            Potential Cost Impact ($)
                        </label>

                        <input type="number"
                               name="potential_cost_impact"
                               class="form-control"
                               min="0"
                               step="0.01"
                               value="{{ old(
                                   'potential_cost_impact',
                                   $risk->potential_cost_impact
                               ) }}">

                    </div>


                    <div class="col-md-3">

                        <label class="form-label">
                            Potential Delay (Days)
                        </label>

                        <input type="number"
                               name="potential_delay_days"
                               class="form-control"
                               min="0"
                               value="{{ old(
                                   'potential_delay_days',
                                   $risk->potential_delay_days
                               ) }}">

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">
                            Risk Description
                        </label>

                        <textarea name="risk_description"
                                  class="form-control"
                                  rows="4">{{ old(
                                      'risk_description',
                                      $risk->risk_description
                                  ) }}</textarea>

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">
                            Risk Cause
                        </label>

                        <textarea name="risk_cause"
                                  class="form-control"
                                  rows="4">{{ old(
                                      'risk_cause',
                                      $risk->risk_cause
                                  ) }}</textarea>

                    </div>


                    <div class="col-12">

                        <label class="form-label">
                            Potential Impact
                        </label>

                        <textarea name="potential_impact"
                                  class="form-control"
                                  rows="4">{{ old(
                                      'potential_impact',
                                      $risk->potential_impact
                                  ) }}</textarea>

                    </div>

                </div>

            </div>

        </div>


        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white">
                <strong>Risk Response</strong>
            </div>

            <div class="card-body">

                <div class="row g-3">

                    <div class="col-md-4">

                        <label class="form-label">
                            Response Strategy
                        </label>

                        <select name="response_strategy"
                                class="form-select">

                            <option value="">
                                Select Strategy
                            </option>

                            @foreach([
                                'Avoid',
                                'Mitigate',
                                'Transfer',
                                'Accept',
                                'Escalate'
                            ] as $value)

                                <option value="{{ $value }}"
                                    @selected(
                                        old(
                                            'response_strategy',
                                            $risk->response_strategy
                                        ) === $value
                                    )>
                                    {{ $value }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Owner Type
                        </label>

                        <select name="owner_type"
                                class="form-select">

                            <option value="">
                                Select Owner
                            </option>

                            @foreach([
                                'Client',
                                'Consultant',
                                'Contractor',
                                'Supplier',
                                'Project Team',
                                'Other'
                            ] as $value)

                                <option value="{{ $value }}"
                                    @selected(
                                        old(
                                            'owner_type',
                                            $risk->owner_type
                                        ) === $value
                                    )>
                                    {{ $value }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Owner Name
                        </label>

                        <input type="text"
                               name="owner_name"
                               class="form-control"
                               value="{{ old(
                                   'owner_name',
                                   $risk->owner_name
                               ) }}">

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">
                            Target Resolution Date
                        </label>

                        <input type="date"
                               name="target_resolution_date"
                               class="form-control"
                               value="{{ old(
                                   'target_resolution_date',
                                   optional(
                                       $risk->target_resolution_date
                                   )->format('Y-m-d')
                               ) }}">

                    </div>


                    <div class="col-md-3">

                        <label class="form-label">
                            Priority
                        </label>

                        <select name="priority"
                                class="form-select">

                            @foreach([
                                'Low',
                                'Medium',
                                'High',
                                'Critical'
                            ] as $value)

                                <option value="{{ $value }}"
                                    @selected(
                                        old(
                                            'priority',
                                            $risk->priority
                                        ) === $value
                                    )>
                                    {{ $value }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-12">

                        <label class="form-label">
                            Response / Mitigation Plan
                        </label>

                        <textarea name="response_plan"
                                  class="form-control"
                                  rows="4">{{ old(
                                      'response_plan',
                                      $risk->response_plan
                                  ) }}</textarea>

                    </div>


                    <div class="col-12">

                        <label class="form-label">
                            Remarks
                        </label>

                        <textarea name="remarks"
                                  class="form-control"
                                  rows="3">{{ old(
                                      'remarks',
                                      $risk->remarks
                                  ) }}</textarea>

                    </div>

                </div>

            </div>

        </div>


        <div class="d-flex justify-content-end gap-2">

            <a href="{{ route(
                'admin.projects.construction.risks.show',
                [$project, $risk]
            ) }}"
            class="btn btn-outline-secondary">

                Cancel

            </a>

            <button type="submit"
                    class="btn btn-primary">

                <i class="bi bi-save"></i>
                Update Risk

            </button>

        </div>

    </form>

</div>

@endsection