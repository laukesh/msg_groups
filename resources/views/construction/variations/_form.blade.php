<div class="row g-3">

    <div class="col-md-4">

        <label class="form-label">
            Variation Date <span class="text-danger">*</span>
        </label>

        <input
            type="date"
            name="variation_date"
            class="form-control"
            required
            value="{{ old(
                'variation_date',
                $variation?->variation_date?->format('Y-m-d')
                ?? now()->format('Y-m-d')
            ) }}"
        >

        @error('variation_date')
            <div class="text-danger small">
                {{ $message }}
            </div>
        @enderror

    </div>


    <div class="col-md-4">

        <label class="form-label">
            Variation Type <span class="text-danger">*</span>
        </label>

        <select
            name="variation_type"
            class="form-select"
            required
        >

            <option value="">
                Select Type
            </option>

            @foreach([
                'Addition',
                'Omission',
                'Substitution',
                'Design Change',
                'Scope Change',
                'Quantity Change',
                'Other',
            ] as $type)

                <option
                    value="{{ $type }}"
                    @selected(
                        old(
                            'variation_type',
                            $variation?->variation_type
                        ) === $type
                    )
                >
                    {{ $type }}
                </option>

            @endforeach

        </select>

        @error('variation_type')
            <div class="text-danger small">
                {{ $message }}
            </div>
        @enderror

    </div>


    <div class="col-md-4">

        <label class="form-label">
            Status
        </label>

        <input
            type="text"
            class="form-control"
            value="{{ $variation?->status ?? 'Draft' }}"
            readonly
        >

        <input
            type="hidden"
            name="status"
            value="{{ $variation?->status ?? 'Draft' }}"
        >

        <div class="form-text">
            Status changes through the approval workflow.
        </div>

    </div>


    <div class="col-md-12">

        <label class="form-label">
            Title <span class="text-danger">*</span>
        </label>

        <input
            type="text"
            name="title"
            class="form-control"
            maxlength="255"
            required
            placeholder="Variation title"
            value="{{ old(
                'title',
                $variation?->title
            ) }}"
        >

        @error('title')
            <div class="text-danger small">
                {{ $message }}
            </div>
        @enderror

    </div>


    <div class="col-md-6">

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
                            $variation?->construction_work_order_id
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
            <div class="text-danger small">
                {{ $message }}
            </div>
        @enderror

    </div>


    <div class="col-md-6">

        <label class="form-label">
            Procurement Contract
        </label>

        <select
            name="procurement_contract_id"
            class="form-select"
        >

            <option value="">
                Not Linked
            </option>

            @foreach($contracts as $contract)

                <option
                    value="{{ $contract->id }}"
                    @selected(
                        old(
                            'procurement_contract_id',
                            $variation?->procurement_contract_id
                        ) == $contract->id
                    )
                >

                    {{ $contract->contract_number }}

                    -

                    {{ $contract->contract_title }}

                </option>

            @endforeach

        </select>

        @error('procurement_contract_id')
            <div class="text-danger small">
                {{ $message }}
            </div>
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
                $variation?->amount
            ) }}"
        >

        @error('amount')
            <div class="text-danger small">
                {{ $message }}
            </div>
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
                $variation?->currency ?? 'USD'
            ) }}"
        >

        @error('currency')
            <div class="text-danger small">
                {{ $message }}
            </div>
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
            placeholder="Describe the variation..."
        >{{ old(
            'description',
            $variation?->description
        ) }}</textarea>

        @error('description')
            <div class="text-danger small">
                {{ $message }}
            </div>
        @enderror

    </div>


    <div class="col-md-12">

        <label class="form-label">
            Reason
        </label>

        <textarea
            name="reason"
            class="form-control"
            rows="4"
            placeholder="Why is this variation required?"
        >{{ old(
            'reason',
            $variation?->reason
        ) }}</textarea>

        @error('reason')
            <div class="text-danger small">
                {{ $message }}
            </div>
        @enderror

    </div>


    <div class="col-md-12">

        <label class="form-label">
            Remarks
        </label>

        <textarea
            name="remarks"
            class="form-control"
            rows="3"
        >{{ old(
            'remarks',
            $variation?->remarks
        ) }}</textarea>

        @error('remarks')
            <div class="text-danger small">
                {{ $message }}
            </div>
        @enderror

    </div>

</div>