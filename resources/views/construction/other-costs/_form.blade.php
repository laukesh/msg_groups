<div class="row g-3">

    <div class="col-md-4">

        <label class="form-label">
            Cost Date <span class="text-danger">*</span>
        </label>

        <input
            type="date"
            name="cost_date"
            class="form-control"
            required
            value="{{ old(
                'cost_date',
                $cost?->cost_date?->format('Y-m-d')
                ?? now()->format('Y-m-d')
            ) }}"
        >

        @error('cost_date')
            <div class="text-danger small">{{ $message }}</div>
        @enderror

    </div>


    <div class="col-md-4">

        <label class="form-label">
            Cost Type <span class="text-danger">*</span>
        </label>

        <select
            name="cost_type"
            class="form-select"
            required
        >

            <option value="">
                Select Cost Type
            </option>

            @foreach([
                'Direct Labour',
                'Equipment Rental',
                'Temporary Works',
                'Direct Material Purchase',
                'Site Expense',
                'Consultant Expense',
                'Other',
            ] as $type)

                <option
                    value="{{ $type }}"
                    @selected(
                        old(
                            'cost_type',
                            $cost?->cost_type
                        ) === $type
                    )
                >
                    {{ $type }}
                </option>

            @endforeach

        </select>

        @error('cost_type')
            <div class="text-danger small">{{ $message }}</div>
        @enderror

    </div>


    <div class="col-md-4">

        <label class="form-label">
            Work Order
        </label>

        <select
            name="construction_work_order_id"
            class="form-select"
        >

            <option value="">
                Not Linked
            </option>

            @foreach($workOrders as $workOrder)

                <option
                    value="{{ $workOrder->id }}"
                    @selected(
                        old(
                            'construction_work_order_id',
                            $cost?->construction_work_order_id
                        ) == $workOrder->id
                    )
                >
                    {{ $workOrder->work_order_number }}
                    -
                    {{ $workOrder->work_order_title }}
                </option>

            @endforeach

        </select>

        @error('construction_work_order_id')
            <div class="text-danger small">{{ $message }}</div>
        @enderror

    </div>


    <div class="col-md-6">

        <label class="form-label">
            Amount <span class="text-danger">*</span>
        </label>

        <input
            type="number"
            name="amount"
            class="form-control"
            min="0.01"
            step="0.01"
            required
            value="{{ old(
                'amount',
                $cost?->amount
            ) }}"
        >

        @error('amount')
            <div class="text-danger small">{{ $message }}</div>
        @enderror

    </div>


    <div class="col-md-6">

        <label class="form-label">
            Currency <span class="text-danger">*</span>
        </label>

        <input
            type="text"
            name="currency"
            class="form-control"
            maxlength="10"
            required
            value="{{ old(
                'currency',
                $cost?->currency ?? 'INR'
            ) }}"
        >

        @error('currency')
            <div class="text-danger small">{{ $message }}</div>
        @enderror

    </div>


    <div class="col-md-12">

        <label class="form-label">
            Description
        </label>

        <textarea
            name="description"
            class="form-control"
            rows="4"
            placeholder="Describe the construction expense..."
        >{{ old(
            'description',
            $cost?->description
        ) }}</textarea>

        @error('description')
            <div class="text-danger small">{{ $message }}</div>
        @enderror

    </div>


    <div class="col-md-6">

        <label class="form-label">
            Status <span class="text-danger">*</span>
        </label>

        <select
            name="status"
            class="form-select"
            required
        >

            @foreach([
                'Draft',
                'Submitted',
                'Approved',
                'Rejected',
            ] as $status)

                <option
                    value="{{ $status }}"
                    @selected(
                        old(
                            'status',
                            $cost?->status ?? 'Draft'
                        ) === $status
                    )
                >
                    {{ $status }}
                </option>

            @endforeach

        </select>

        @error('status')
            <div class="text-danger small">{{ $message }}</div>
        @enderror

    </div>


    <div class="col-md-12">

        <label class="form-label">
            Remarks
        </label>

        <textarea
            name="remarks"
            class="form-control"
            rows="4"
            placeholder="Additional remarks..."
        >{{ old(
            'remarks',
            $cost?->remarks
        ) }}</textarea>

        @error('remarks')
            <div class="text-danger small">{{ $message }}</div>
        @enderror

    </div>

</div>