<div class="row g-3">

    <div class="col-md-6">

        <label class="form-label">
            Contract
            <span class="text-danger">*</span>
        </label>

        <select
            name="procurement_contract_id"
            class="form-select"
            required
        >

            <option value="">
                Select Contract
            </option>

            @foreach($contracts as $contract)

                <option
                    value="{{ $contract->id }}"
                    @selected(
                        old(
                            'procurement_contract_id'
                        ) == $contract->id
                    )
                >

                    {{ $contract->contract_number }}

                    —
                    {{
                        $contract->bidder
                            ?->company_name
                        ??
                        $contract->bidder_name
                        ??
                        'Contractor'
                    }}

                </option>

            @endforeach

        </select>

        <div class="form-text">
            Only contracts belonging to this project are shown.
        </div>

    </div>


    <div class="col-md-6">

        <label class="form-label">
            Work Order Title
            <span class="text-danger">*</span>
        </label>

        <input
            type="text"
            name="work_order_title"
            class="form-control"
            required
            value="{{ old('work_order_title') }}"
        >

    </div>


    <div class="col-md-4">

        <label class="form-label">
            Work Order Type
        </label>

        <select
            name="work_order_type"
            class="form-select"
        >

            <option value="">
                Select Type
            </option>

            @foreach([
                'Main Work',
                'Civil Work',
                'Electrical Work',
                'MEP Work',
                'Finishing Work',
                'Sub Work',
                'Variation Work',
                'Other',
            ] as $type)

                <option
                    value="{{ $type }}"
                    @selected(
                        old('work_order_type') === $type
                    )
                >
                    {{ $type }}
                </option>

            @endforeach

        </select>

    </div>


    <div class="col-md-4">

        <label class="form-label">
            Priority
        </label>

        <select
            name="priority"
            class="form-select"
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
                            'Normal'
                        ) === $priority
                    )
                >
                    {{ $priority }}
                </option>

            @endforeach

        </select>

    </div>


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
                'Issued',
                'In Progress',
                'Completed',
                'Cancelled',
            ] as $status)

                <option
                    value="{{ $status }}"
                    @selected(
                        old(
                            'status',
                            'Draft'
                        ) === $status
                    )
                >
                    {{ $status }}
                </option>

            @endforeach

        </select>

    </div>


    <div class="col-md-4">

        <label class="form-label">
            Issue Date
        </label>

        <input
            type="date"
            name="issue_date"
            class="form-control"
            value="{{ old('issue_date') }}"
        >

    </div>


    <div class="col-md-4">

        <label class="form-label">
            Start Date
        </label>

        <input
            type="date"
            name="start_date"
            class="form-control"
            value="{{ old('start_date') }}"
        >

    </div>


    <div class="col-md-4">

        <label class="form-label">
            Expected Completion
        </label>

        <input
            type="date"
            name="expected_completion_date"
            class="form-control"
            value="{{ old(
                'expected_completion_date'
            ) }}"
        >

    </div>


    <div class="col-md-4">

        <label class="form-label">
            Work Order Value
        </label>

        <input
            type="number"
            step="0.01"
            min="0"
            name="work_order_value"
            class="form-control"
            value="{{ old(
                'work_order_value',
                0
            ) }}"
        >

    </div>


    <div class="col-md-2">

        <label class="form-label">
            Currency
        </label>

        <input
            type="text"
            name="currency"
            class="form-control"
            value="{{ old(
                'currency',
                'USD'
            ) }}"
        >

    </div>


    <div class="col-md-6">

        <label class="form-label">
            Assigned To
        </label>

        <select
            name="assigned_to"
            class="form-select"
        >

            <option value="">
                Select User
            </option>

            @foreach($users as $user)

                <option
                    value="{{ $user->id }}"
                    @selected(
                        old(
                            'assigned_to'
                        ) == $user->id
                    )
                >
                    {{ $user->name }}
                </option>

            @endforeach

        </select>

    </div>


    <div class="col-md-12">

        <label class="form-label">
            Scope of Work
        </label>

        <textarea
            name="scope_of_work"
            rows="5"
            class="form-control"
        >{{ old('scope_of_work') }}</textarea>

    </div>


    <div class="col-md-12">

        <label class="form-label">
            Remarks
        </label>

        <textarea
            name="remarks"
            rows="3"
            class="form-control"
        >{{ old('remarks') }}</textarea>

    </div>

</div>