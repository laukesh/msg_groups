<div class="row g-3">

    {{-- ================================================================
         WORK ORDER
    ================================================================= --}}

    <div class="col-md-6">

        <label class="form-label">
            Work Order
            <span class="text-danger">*</span>
        </label>

        <select
            name="construction_work_order_id"
            class="form-select"
            required
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
                            $progress?->construction_work_order_id
                        ) == $workOrder->id
                    )
                >

                    {{ $workOrder->work_order_number }}

                    —

                    {{ $workOrder->work_order_title }}

                    @if(
                        $workOrder->contract?->contract_number
                    )

                        —

                        {{ $workOrder->contract->contract_number }}

                    @endif

                </option>

            @endforeach

        </select>

        <div class="form-text">
            Only Work Orders belonging to this project are available.
        </div>

    </div>


    {{-- ================================================================
         PROGRESS DATE
    ================================================================= --}}

    <div class="col-md-3">

        <label class="form-label">
            Progress Date
            <span class="text-danger">*</span>
        </label>

        <input
            type="date"
            name="progress_date"
            class="form-control"
            required
            value="{{ old(
                'progress_date',
                $progress?->progress_date?->format('Y-m-d')
                ?? now()->format('Y-m-d')
            ) }}"
        >

    </div>


    {{-- ================================================================
         OVERALL PROGRESS
    ================================================================= --}}

    <div class="col-md-3">

        <label class="form-label">
            Overall Progress %
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
                $progress?->progress_percentage ?? 0
            ) }}"
        >

    </div>


    {{-- ================================================================
         PLANNED
    ================================================================= --}}

    <div class="col-md-4">

        <label class="form-label">
            Planned Progress %
        </label>

        <input
            type="number"
            name="planned_percentage"
            class="form-control"
            min="0"
            max="100"
            step="0.01"
            value="{{ old(
                'planned_percentage',
                $progress?->planned_percentage
            ) }}"
        >

    </div>


    {{-- ================================================================
         PHYSICAL
    ================================================================= --}}

    <div class="col-md-4">

        <label class="form-label">
            Physical Progress %
        </label>

        <input
            type="number"
            name="physical_progress"
            class="form-control"
            min="0"
            max="100"
            step="0.01"
            value="{{ old(
                'physical_progress',
                $progress?->physical_progress
            ) }}"
        >

    </div>


    {{-- ================================================================
         FINANCIAL
    ================================================================= --}}

    <div class="col-md-4">

        <label class="form-label">
            Financial Progress %
        </label>

        <input
            type="number"
            name="financial_progress"
            class="form-control"
            min="0"
            max="100"
            step="0.01"
            value="{{ old(
                'financial_progress',
                $progress?->financial_progress
            ) }}"
        >

    </div>


    {{-- ================================================================
         STATUS
    ================================================================= --}}

    <div class="col-md-4">

        <label class="form-label">
            Status
        </label>

        <select
            name="status"
            class="form-select"
        >

            @foreach([
                'In Progress',
                'Delayed',
                'On Hold',
                'Completed',
            ] as $status)

                <option
                    value="{{ $status }}"
                    @selected(
                        old(
                            'status',
                            $progress?->status ?? 'In Progress'
                        ) === $status
                    )
                >
                    {{ $status }}
                </option>

            @endforeach

        </select>

    </div>


    {{-- ================================================================
         WEATHER
    ================================================================= --}}

    <div class="col-md-4">

        <label class="form-label">
            Weather Condition
        </label>

        <input
            type="text"
            name="weather_condition"
            class="form-control"
            value="{{ old(
                'weather_condition',
                $progress?->weather_condition
            ) }}"
            placeholder="e.g. Clear / Rainy"
        >

    </div>


    {{-- ================================================================
         REPORTED BY
    ================================================================= --}}

    <div class="col-md-4">

        <label class="form-label">
            Reported By
        </label>

        <select
            name="reported_by"
            class="form-select"
        >

            <option value="">
                Current User
            </option>

            @foreach($users as $user)

                <option
                    value="{{ $user->id }}"
                    @selected(
                        old(
                            'reported_by',
                            $progress?->reported_by
                        ) == $user->id
                    )
                >
                    {{ $user->name }}
                </option>

            @endforeach

        </select>

    </div>


    {{-- ================================================================
         WORK DESCRIPTION
    ================================================================= --}}

    <div class="col-md-12">

        <label class="form-label">
            Work Description
        </label>

        <textarea
            name="work_description"
            rows="4"
            class="form-control"
        >{{ old(
            'work_description',
            $progress?->work_description
        ) }}</textarea>

    </div>


    {{-- ================================================================
         ISSUES
    ================================================================= --}}

    <div class="col-md-4">

        <label class="form-label">
            Issues
        </label>

        <textarea
            name="issues"
            rows="4"
            class="form-control"
        >{{ old(
            'issues',
            $progress?->issues
        ) }}</textarea>

    </div>


    {{-- ================================================================
         CORRECTIVE ACTION
    ================================================================= --}}

    <div class="col-md-4">

        <label class="form-label">
            Corrective Action
        </label>

        <textarea
            name="corrective_action"
            rows="4"
            class="form-control"
        >{{ old(
            'corrective_action',
            $progress?->corrective_action
        ) }}</textarea>

    </div>


    {{-- ================================================================
         NEXT ACTION
    ================================================================= --}}

    <div class="col-md-4">

        <label class="form-label">
            Next Action
        </label>

        <textarea
            name="next_action"
            rows="4"
            class="form-control"
        >{{ old(
            'next_action',
            $progress?->next_action
        ) }}</textarea>

    </div>


    {{-- ================================================================
         REMARKS
    ================================================================= --}}

    <div class="col-md-12">

        <label class="form-label">
            Remarks
        </label>

        <textarea
            name="remarks"
            rows="3"
            class="form-control"
        >{{ old(
            'remarks',
            $progress?->remarks
        ) }}</textarea>

    </div>

</div>