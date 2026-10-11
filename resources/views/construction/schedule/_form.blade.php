<div class="row g-3">

    {{-- Activity Name --}}
    <div class="col-md-8">

        <label class="form-label">
            Activity Name
            <span class="text-danger">*</span>
        </label>

        <input
            type="text"
            name="activity_name"
            class="form-control"
            required
            value="{{ old(
                'activity_name',
                $activity?->activity_name
            ) }}"
            placeholder="e.g. Foundation Excavation"
        >

    </div>


    {{-- Work Order --}}
    <div class="col-md-4">

        <label class="form-label">
            Work Order
        </label>

        <select
            name="construction_work_order_id"
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
                            'construction_work_order_id',
                            $activity?->construction_work_order_id
                        ) == $workOrder->id
                    )
                >
                    {{ $workOrder->work_order_number }}
                    -
                    {{ $workOrder->work_order_title }}
                </option>

            @endforeach

        </select>

    </div>


    {{-- WBS Code --}}
    <div class="col-md-4">

        <label class="form-label">
            WBS Code
        </label>

        <input
            type="text"
            name="wbs_code"
            class="form-control"
            value="{{ old(
                'wbs_code',
                $activity?->wbs_code
            ) }}"
            placeholder="e.g. 1.2.3"
        >

    </div>


    {{-- Phase --}}
    <div class="col-md-4">

        <label class="form-label">
            Phase
        </label>

        <input
            type="text"
            name="phase"
            class="form-control"
            value="{{ old(
                'phase',
                $activity?->phase
            ) }}"
            placeholder="e.g. Foundation"
        >

    </div>


    {{-- Priority --}}
    <div class="col-md-4">

        <label class="form-label">
            Priority
            <span class="text-danger">*</span>
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
                'Critical',
            ] as $priority)

                <option
                    value="{{ $priority }}"
                    @selected(
                        old(
                            'priority',
                            $activity?->priority
                            ?? 'Normal'
                        ) === $priority
                    )
                >
                    {{ $priority }}
                </option>

            @endforeach

        </select>

    </div>


    {{-- Planned Start --}}
    <div class="col-md-3">

        <label class="form-label">
            Planned Start
        </label>

        <input
            type="date"
            name="planned_start_date"
            class="form-control"
            value="{{ old(
                'planned_start_date',
                $activity?->planned_start_date?->format('Y-m-d')
            ) }}"
        >

    </div>


    {{-- Planned Finish --}}
    <div class="col-md-3">

        <label class="form-label">
            Planned Finish
        </label>

        <input
            type="date"
            name="planned_finish_date"
            class="form-control"
            value="{{ old(
                'planned_finish_date',
                $activity?->planned_finish_date?->format('Y-m-d')
            ) }}"
        >

    </div>


    {{-- Actual Start --}}
    <div class="col-md-3">

        <label class="form-label">
            Actual Start
        </label>

        <input
            type="date"
            name="actual_start_date"
            class="form-control"
            value="{{ old(
                'actual_start_date',
                $activity?->actual_start_date?->format('Y-m-d')
            ) }}"
        >

    </div>


    {{-- Actual Finish --}}
    <div class="col-md-3">

        <label class="form-label">
            Actual Finish
        </label>

        <input
            type="date"
            name="actual_finish_date"
            class="form-control"
            value="{{ old(
                'actual_finish_date',
                $activity?->actual_finish_date?->format('Y-m-d')
            ) }}"
        >

    </div>


    {{-- Progress --}}
    <div class="col-md-4">

        <label class="form-label">
            Progress (%)
            <span class="text-danger">*</span>
        </label>

        <input
            type="number"
            name="progress_percentage"
            class="form-control"
            min="0"
            max="100"
            step="0.01"
            required
            value="{{ old(
                'progress_percentage',
                $activity?->progress_percentage ?? 0
            ) }}"
        >

    </div>


    {{-- Status --}}
    <div class="col-md-4">

        <label class="form-label">
            Status
            <span class="text-danger">*</span>
        </label>

        <select
            name="status"
            class="form-select"
            required
        >

            @foreach([
                'Not Started',
                'In Progress',
                'Completed',
                'On Hold',
                'Delayed',
                'Cancelled',
            ] as $status)

                <option
                    value="{{ $status }}"
                    @selected(
                        old(
                            'status',
                            $activity?->status
                            ?? 'Not Started'
                        ) === $status
                    )
                >
                    {{ $status }}
                </option>

            @endforeach

        </select>

    </div>


    {{-- Delay --}}
    <div class="col-md-4">

        <label class="form-label">
            Delay Days
        </label>

        <input
            type="number"
            name="delay_days"
            class="form-control"
            min="0"
            value="{{ old(
                'delay_days',
                $activity?->delay_days ?? 0
            ) }}"
        >

    </div>


    {{-- Responsible --}}
    <div class="col-md-6">

        <label class="form-label">
            Responsible Person
        </label>

        <select
            name="responsible_user_id"
            class="form-select"
        >

            <option value="">
                Select Responsible Person
            </option>

            @foreach($users as $user)

                <option
                    value="{{ $user->id }}"
                    @selected(
                        old(
                            'responsible_user_id',
                            $activity?->responsible_user_id
                        ) == $user->id
                    )
                >
                    {{ $user->name }}
                </option>

            @endforeach

        </select>

    </div>


    {{-- Predecessor --}}
    <div class="col-md-6">

        <label class="form-label">
            Predecessor Activity
        </label>

        <select
            name="predecessor_activity_id"
            class="form-select"
        >

            <option value="">
                No Predecessor
            </option>

            @foreach($activities as $previousActivity)

                <option
                    value="{{ $previousActivity->id }}"
                    @selected(
                        old(
                            'predecessor_activity_id',
                            $activity?->predecessor_activity_id
                        ) == $previousActivity->id
                    )
                >
                    {{ $previousActivity->activity_code }}
                    -
                    {{ $previousActivity->activity_name }}
                </option>

            @endforeach

        </select>

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
            placeholder="Describe the planned activity..."
        >{{ old(
            'description',
            $activity?->description
        ) }}</textarea>

    </div>


    {{-- Remarks --}}
    <div class="col-md-12">

        <label class="form-label">
            Remarks
        </label>

        <textarea
            name="remarks"
            rows="4"
            class="form-control"
        >{{ old(
            'remarks',
            $activity?->remarks
        ) }}</textarea>

    </div>

</div>