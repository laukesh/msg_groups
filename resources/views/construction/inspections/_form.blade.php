<div class="row g-3">

    {{-- Inspection Date --}}
    <div class="col-md-4">

        <label class="form-label">
            Inspection Date <span class="text-danger">*</span>
        </label>

        <input
            type="date"
            name="inspection_date"
            value="{{ old(
                'inspection_date',
                isset($inspection) && $inspection->inspection_date
                    ? $inspection->inspection_date->format('Y-m-d')
                    : now()->format('Y-m-d')
            ) }}"
            class="form-control @error('inspection_date') is-invalid @enderror"
            required
        >

        @error('inspection_date')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- Inspection Type --}}
    <div class="col-md-4">

        <label class="form-label">
            Inspection Type
        </label>

        <select
            name="inspection_type"
            class="form-select"
        >

            <option value="">
                Select Type
            </option>

            @foreach([
                'Material Inspection',
                'Workmanship Inspection',
                'Site Inspection',
                'Safety Inspection',
                'Quality Inspection',
                'Structural Inspection',
                'Electrical Inspection',
                'Mechanical Inspection',
                'Pre-Installation Inspection',
                'Final Inspection',
                'Other'
            ] as $type)

                <option
                    value="{{ $type }}"
                    @selected(
                        old(
                            'inspection_type',
                            $inspection->inspection_type ?? ''
                        ) === $type
                    )
                >
                    {{ $type }}
                </option>

            @endforeach

        </select>

    </div>


    {{-- Priority --}}
    <div class="col-md-4">

        <label class="form-label">
            Priority <span class="text-danger">*</span>
        </label>

        <select
            name="priority"
            class="form-select"
            required
        >

            @foreach([
                'Low',
                'Normal',
                'High',
                'Critical'
            ] as $priority)

                <option
                    value="{{ $priority }}"
                    @selected(
                        old(
                            'priority',
                            $inspection->priority ?? 'Normal'
                        ) === $priority
                    )
                >
                    {{ $priority }}
                </option>

            @endforeach

        </select>

    </div>


    {{-- Title --}}
    <div class="col-md-12">

        <label class="form-label">
            Inspection Title <span class="text-danger">*</span>
        </label>

        <input
            type="text"
            name="title"
            value="{{ old(
                'title',
                $inspection->title ?? ''
            ) }}"
            class="form-control @error('title') is-invalid @enderror"
            placeholder="Enter inspection title"
            required
        >

        @error('title')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- Description --}}
    <div class="col-md-12">

        <label class="form-label">
            Description
        </label>

        <textarea
            name="description"
            rows="4"
            class="form-control"
            placeholder="Describe the inspection..."
        >{{ old(
            'description',
            $inspection->description ?? ''
        ) }}</textarea>

    </div>

</div>


<hr class="my-4">


<h6 class="fw-bold mb-3">
    Project & Construction Details
</h6>


<div class="row g-3">

    {{-- Procurement Contract --}}
    <div class="col-md-6">

        <label class="form-label">
            Procurement Contract
        </label>

        <select
            name="procurement_contract_id"
            class="form-select"
        >

            <option value="">
                Select Contract
            </option>

            @foreach($contracts as $contract)

                <option
                    value="{{ $contract->id }}"
                    @selected(
                        old(
                            'procurement_contract_id',
                            $inspection->procurement_contract_id ?? ''
                        ) == $contract->id
                    )
                >

                    {{ $contract->contract_number }}

                    @if($contract->contract_title)
                        - {{ $contract->contract_title }}
                    @endif

                    @if($contract->bidder)
                        - {{ $contract->bidder->company_name }}
                    @endif

                </option>

            @endforeach

        </select>

    </div>


    {{-- Work Order --}}
    <div class="col-md-6">

        <label class="form-label">
            Work Order
        </label>

        <select
            name="work_order_id"
            class="form-select"
        >

            <option value="">
                Select Work Order
            </option>

            @foreach($workOrders as $workOrder)

                <option
                    value="{{ $workOrder->id }}"
                    @selected(
                        old(
                            'work_order_id',
                            $inspection->work_order_id ?? ''
                        ) == $workOrder->id
                    )
                >

                    {{ $workOrder->work_order_number }}

                    @if($workOrder->work_order_title)
                        - {{ $workOrder->work_order_title }}
                    @endif

                </option>

            @endforeach

        </select>

    </div>


    {{-- Consultant --}}
    <div class="col-md-6">

        <label class="form-label">
            Consultant
        </label>

        <select
            name="consultant_id"
            class="form-select @error('consultant_id') is-invalid @enderror"
        >

            <option value="">
                Select Consultant
            </option>

            @foreach($consultants as $consultant)

                <option
                    value="{{ $consultant->id }}"
                    @selected(
                        old(
                            'consultant_id',
                            $inspection->consultant_id ?? ''
                        ) == $consultant->id
                    )
                >

                    {{ $consultant->company_name }}

                    @if($consultant->consultant_name)
                        - {{ $consultant->consultant_name }}
                    @endif

                </option>

            @endforeach

        </select>

        @error('consultant_id')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- Schedule Activity --}}
    <div class="col-md-6">

        <label class="form-label">
            Schedule Activity
        </label>

        <select
            name="schedule_activity_id"
            class="form-select"
        >

            <option value="">
                Select Activity
            </option>

            @foreach($scheduleActivities as $activity)

                <option
                    value="{{ $activity->id }}"
                    @selected(
                        old(
                            'schedule_activity_id',
                            $inspection->schedule_activity_id ?? ''
                        ) == $activity->id
                    )
                >

                    {{ $activity->activity_name
                        ?? $activity->name
                        ?? ('Activity #' . $activity->id)
                    }}

                </option>

            @endforeach

        </select>

    </div>


    {{-- Location --}}
    <div class="col-md-12">

        <label class="form-label">
            Location
        </label>

        <input
            type="text"
            name="location"
            value="{{ old(
                'location',
                $inspection->location ?? ''
            ) }}"
            class="form-control"
            placeholder="Building / Floor / Area / Location"
        >

    </div>

</div>


<hr class="my-4">


<h6 class="fw-bold mb-3">
    Inspection Scheduling
</h6>


<div class="row g-3">

    {{-- Planned Date --}}
    <div class="col-md-4">

        <label class="form-label">
            Planned Date
        </label>

        <input
            type="date"
            name="planned_date"
            value="{{ old(
                'planned_date',
                isset($inspection) && $inspection->planned_date
                    ? $inspection->planned_date->format('Y-m-d')
                    : ''
            ) }}"
            class="form-control"
        >

    </div>


    {{-- Scheduled Date --}}
    <div class="col-md-4">

        <label class="form-label">
            Scheduled Date
        </label>

        <input
            type="date"
            name="scheduled_date"
            value="{{ old(
                'scheduled_date',
                isset($inspection) && $inspection->scheduled_date
                    ? $inspection->scheduled_date->format('Y-m-d')
                    : ''
            ) }}"
            class="form-control"
        >

    </div>


    {{-- Conducted Date --}}
    <div class="col-md-4">

        <label class="form-label">
            Conducted Date
        </label>

        <input
            type="date"
            name="conducted_date"
            value="{{ old(
                'conducted_date',
                isset($inspection) && $inspection->conducted_date
                    ? $inspection->conducted_date->format('Y-m-d')
                    : ''
            ) }}"
            class="form-control"
        >

    </div>


    {{-- Inspector --}}
    <div class="col-md-6">

        <label class="form-label">
            Inspector
        </label>

        <select
            name="inspected_by"
            class="form-select"
        >

            <option value="">
                Select Inspector
            </option>

            @foreach($users as $user)

                <option
                    value="{{ $user->id }}"
                    @selected(
                        old(
                            'inspected_by',
                            $inspection->inspected_by ?? ''
                        ) == $user->id
                    )
                >
                    {{ $user->name }}
                </option>

            @endforeach

        </select>

    </div>


    {{-- Witness --}}
    <div class="col-md-6">

        <label class="form-label">
            Witnessed By
        </label>

        <select
            name="witnessed_by"
            class="form-select"
        >

            <option value="">
                Select Witness
            </option>

            @foreach($users as $user)

                <option
                    value="{{ $user->id }}"
                    @selected(
                        old(
                            'witnessed_by',
                            $inspection->witnessed_by ?? ''
                        ) == $user->id
                    )
                >
                    {{ $user->name }}
                </option>

            @endforeach

        </select>

    </div>

</div>


<hr class="my-4">


<h6 class="fw-bold mb-3">
    Inspection Findings
</h6>


<div class="row g-3">

    {{-- Observations --}}
    <div class="col-md-12">

        <label class="form-label">
            Observations
        </label>

        <textarea
            name="observations"
            rows="5"
            class="form-control"
            placeholder="Record inspection observations..."
        >{{ old(
            'observations',
            $inspection->observations ?? ''
        ) }}</textarea>

    </div>


    {{-- Non Conformance --}}
    <div class="col-md-12">

        <label class="form-label">
            Non-Conformance / Deficiencies
        </label>

        <textarea
            name="non_conformance"
            rows="5"
            class="form-control"
            placeholder="Record any non-conformance or deficiencies..."
        >{{ old(
            'non_conformance',
            $inspection->non_conformance ?? ''
        ) }}</textarea>

    </div>


    {{-- Corrective Action --}}
    <div class="col-md-12">

        <label class="form-label">
            Corrective Action
        </label>

        <textarea
            name="corrective_action"
            rows="5"
            class="form-control"
            placeholder="Describe required corrective action..."
        >{{ old(
            'corrective_action',
            $inspection->corrective_action ?? ''
        ) }}</textarea>

    </div>


    {{-- Corrective Action Due --}}
    <div class="col-md-6">

        <label class="form-label">
            Corrective Action Due Date
        </label>

        <input
            type="date"
            name="corrective_action_due_date"
            value="{{ old(
                'corrective_action_due_date',
                isset($inspection) && $inspection->corrective_action_due_date
                    ? $inspection->corrective_action_due_date->format('Y-m-d')
                    : ''
            ) }}"
            class="form-control"
        >

    </div>


    {{-- Corrective Action Date --}}
    <div class="col-md-6">

        <label class="form-label">
            Corrective Action Completed Date
        </label>

        <input
            type="date"
            name="corrective_action_date"
            value="{{ old(
                'corrective_action_date',
                isset($inspection) && $inspection->corrective_action_date
                    ? $inspection->corrective_action_date->format('Y-m-d')
                    : ''
            ) }}"
            class="form-control"
        >

    </div>

</div>


<hr class="my-4">


<h6 class="fw-bold mb-3">
    Re-inspection
</h6>


<div class="row g-3">

    <div class="col-md-6">

        <label class="form-label">
            Re-inspection Required
        </label>

        <select
            name="reinspection_required"
            class="form-select"
        >

            <option
                value="0"
                @selected(
                    old(
                        'reinspection_required',
                        $inspection->reinspection_required ?? 0
                    ) == 0
                )
            >
                No
            </option>

            <option
                value="1"
                @selected(
                    old(
                        'reinspection_required',
                        $inspection->reinspection_required ?? 0
                    ) == 1
                )
            >
                Yes
            </option>

        </select>

    </div>


    <div class="col-md-6">

        <label class="form-label">
            Re-inspection Date
        </label>

        <input
            type="date"
            name="reinspection_date"
            value="{{ old(
                'reinspection_date',
                isset($inspection) && $inspection->reinspection_date
                    ? $inspection->reinspection_date->format('Y-m-d')
                    : ''
            ) }}"
            class="form-control"
        >

    </div>

</div>


<hr class="my-4">


<div>

    <label class="form-label">
        Remarks
    </label>

    <textarea
        name="remarks"
        rows="4"
        class="form-control"
        placeholder="Additional remarks..."
    >{{ old(
        'remarks',
        $inspection->remarks ?? ''
    ) }}</textarea>

</div>