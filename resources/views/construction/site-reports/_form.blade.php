<div class="row g-3">

    {{-- Work Order --}}
    <div class="col-md-6">

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
                            $report?->construction_work_order_id
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


    {{-- Report Date --}}
    <div class="col-md-3">

        <label class="form-label">
            Report Date
            <span class="text-danger">*</span>
        </label>

        <input
            type="date"
            name="report_date"
            class="form-control"
            required
            value="{{ old(
                'report_date',
                $report?->report_date?->format('Y-m-d')
                ?? now()->format('Y-m-d')
            ) }}"
        >

    </div>


    {{-- Report Type --}}
    <div class="col-md-3">

        <label class="form-label">
            Report Type
            <span class="text-danger">*</span>
        </label>

        <select
            name="report_type"
            class="form-select"
            required
        >

            @foreach([
                'Daily Site Report',
                'Weekly Site Report',
                'Special Site Report',
            ] as $type)

                <option
                    value="{{ $type }}"
                    @selected(
                        old(
                            'report_type',
                            $report?->report_type
                            ?? 'Daily Site Report'
                        ) === $type
                    )
                >
                    {{ $type }}
                </option>

            @endforeach

        </select>

    </div>


    {{-- Prepared By --}}
    <div class="col-md-4">

        <label class="form-label">
            Prepared By
        </label>

        <select
            name="prepared_by"
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
                            'prepared_by',
                            $report?->prepared_by
                        ) == $user->id
                    )
                >
                    {{ $user->name }}
                </option>

            @endforeach

        </select>

    </div>


    {{-- Weather --}}
    <div class="col-md-4">

        <label class="form-label">
            Weather Condition
        </label>

        <select
            name="weather_condition"
            class="form-select"
        >

            <option value="">
                Select
            </option>

            @foreach([
                'Clear',
                'Sunny',
                'Cloudy',
                'Partly Cloudy',
                'Rain',
                'Heavy Rain',
                'Storm',
                'Other',
            ] as $weather)

                <option
                    value="{{ $weather }}"
                    @selected(
                        old(
                            'weather_condition',
                            $report?->weather_condition
                        ) === $weather
                    )
                >
                    {{ $weather }}
                </option>

            @endforeach

        </select>

    </div>


    {{-- Temperature --}}
    <div class="col-md-4">

        <label class="form-label">
            Temperature
        </label>

        <input
            type="text"
            name="temperature"
            class="form-control"
            value="{{ old(
                'temperature',
                $report?->temperature
            ) }}"
            placeholder="e.g. 32°C"
        >

    </div>


    {{-- Site Condition --}}
    <div class="col-md-8">

        <label class="form-label">
            Site Condition
        </label>

        <input
            type="text"
            name="site_condition"
            class="form-control"
            value="{{ old(
                'site_condition',
                $report?->site_condition
            ) }}"
            placeholder="e.g. Normal / Wet / Restricted"
        >

    </div>


    {{-- Progress --}}
    <div class="col-md-4">

        <label class="form-label">
            Overall Progress (%)
            <span class="text-danger">*</span>
        </label>

        <input
            type="number"
            name="overall_progress"
            class="form-control"
            min="0"
            max="100"
            step="0.01"
            required
            value="{{ old(
                'overall_progress',
                $report?->overall_progress ?? 0
            ) }}"
        >

    </div>


    {{-- Work Summary --}}
    <div class="col-md-12">

        <label class="form-label">
            Work Summary
        </label>

        <textarea
            name="work_summary"
            rows="4"
            class="form-control"
        >{{ old(
            'work_summary',
            $report?->work_summary
        ) }}</textarea>

    </div>


    {{-- Activities Completed --}}
    <div class="col-md-6">

        <label class="form-label">
            Activities Completed
        </label>

        <textarea
            name="activities_completed"
            rows="5"
            class="form-control"
            placeholder="Activities completed today..."
        >{{ old(
            'activities_completed',
            $report?->activities_completed
        ) }}</textarea>

    </div>


    {{-- Activities Planned --}}
    <div class="col-md-6">

        <label class="form-label">
            Activities Planned
        </label>

        <textarea
            name="activities_planned"
            rows="5"
            class="form-control"
            placeholder="Activities planned for next working day..."
        >{{ old(
            'activities_planned',
            $report?->activities_planned
        ) }}</textarea>

    </div>


    {{-- Manpower --}}
    <div class="col-md-6">

        <label class="form-label">
            Manpower Summary
        </label>

        <textarea
            name="manpower_summary"
            rows="5"
            class="form-control"
            placeholder="Workers, supervisors, engineers, etc."
        >{{ old(
            'manpower_summary',
            $report?->manpower_summary
        ) }}</textarea>

    </div>


    {{-- Equipment --}}
    <div class="col-md-6">

        <label class="form-label">
            Equipment Summary
        </label>

        <textarea
            name="equipment_summary"
            rows="5"
            class="form-control"
            placeholder="Equipment deployed / idle..."
        >{{ old(
            'equipment_summary',
            $report?->equipment_summary
        ) }}</textarea>

    </div>


    {{-- Materials --}}
    <div class="col-md-6">

        <label class="form-label">
            Material Summary
        </label>

        <textarea
            name="material_summary"
            rows="5"
            class="form-control"
            placeholder="Materials received / consumed / pending..."
        >{{ old(
            'material_summary',
            $report?->material_summary
        ) }}</textarea>

    </div>


    {{-- Safety --}}
    <div class="col-md-6">

        <label class="form-label">
            Safety Observations
        </label>

        <textarea
            name="safety_observations"
            rows="5"
            class="form-control"
        >{{ old(
            'safety_observations',
            $report?->safety_observations
        ) }}</textarea>

    </div>


    {{-- Quality --}}
    <div class="col-md-6">

        <label class="form-label">
            Quality Observations
        </label>

        <textarea
            name="quality_observations"
            rows="5"
            class="form-control"
        >{{ old(
            'quality_observations',
            $report?->quality_observations
        ) }}</textarea>

    </div>


    {{-- Delays --}}
    <div class="col-md-6">

        <label class="form-label">
            Delays
        </label>

        <textarea
            name="delays"
            rows="5"
            class="form-control"
        >{{ old(
            'delays',
            $report?->delays
        ) }}</textarea>

    </div>


    {{-- Issues --}}
    <div class="col-md-6">

        <label class="form-label">
            Issues
        </label>

        <textarea
            name="issues"
            rows="5"
            class="form-control"
        >{{ old(
            'issues',
            $report?->issues
        ) }}</textarea>

    </div>


    {{-- Corrective Actions --}}
    <div class="col-md-6">

        <label class="form-label">
            Corrective Actions
        </label>

        <textarea
            name="corrective_actions"
            rows="5"
            class="form-control"
        >{{ old(
            'corrective_actions',
            $report?->corrective_actions
        ) }}</textarea>

    </div>


    {{-- Visitors --}}
    <div class="col-md-6">

        <label class="form-label">
            Visitors
        </label>

        <textarea
            name="visitors"
            rows="5"
            class="form-control"
        >{{ old(
            'visitors',
            $report?->visitors
        ) }}</textarea>

    </div>


    {{-- Instructions --}}
    <div class="col-md-12">

        <label class="form-label">
            Instructions
        </label>

        <textarea
            name="instructions"
            rows="4"
            class="form-control"
        >{{ old(
            'instructions',
            $report?->instructions
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
            $report?->remarks
        ) }}</textarea>

    </div>


    {{-- Status only on edit --}}
    @if(isset($report))

        <div class="col-md-4">

            <label class="form-label">
                Status
            </label>

            <select
                name="status"
                class="form-select"
            >

                @foreach([
                    'Draft',
                    'Revision Required',
                ] as $status)

                    <option
                        value="{{ $status }}"
                        @selected(
                            old(
                                'status',
                                $report->status
                            ) === $status
                        )
                    >
                        {{ $status }}
                    </option>

                @endforeach

            </select>

        </div>

    @endif

</div>