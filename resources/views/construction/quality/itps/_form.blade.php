<div class="row g-3">

    {{-- ================================================================ --}}
    {{-- BASIC INFORMATION --}}
    {{-- ================================================================ --}}

    <div class="col-md-8">

        <label class="form-label">
            ITP Title <span class="text-danger">*</span>
        </label>

        <input
            type="text"
            name="title"
            value="{{ old('title', $itp->title ?? '') }}"
            class="form-control @error('title') is-invalid @enderror"
            placeholder="e.g. RCC Structural Works"
            required
        >

        @error('title')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    <div class="col-md-4">

        <label class="form-label">
            ITP Type
        </label>

        <select
            name="itp_type"
            class="form-select"
        >

            <option value="">
                Select Type
            </option>

            @foreach([
                'Civil Works',
                'Structural Works',
                'Architectural Works',
                'Electrical Works',
                'Mechanical Works',
                'Plumbing Works',
                'Fire Fighting Works',
                'External Works',
                'Finishing Works',
                'Other',
            ] as $type)

                <option
                    value="{{ $type }}"
                    @selected(
                        old(
                            'itp_type',
                            $itp->itp_type ?? ''
                        ) === $type
                    )
                >
                    {{ $type }}
                </option>

            @endforeach

        </select>

    </div>


    <div class="col-md-12">

        <label class="form-label">
            Description
        </label>

        <textarea
            name="description"
            rows="4"
            class="form-control"
            placeholder="Describe the scope covered by this ITP..."
        >{{ old(
            'description',
            $itp->description ?? ''
        ) }}</textarea>

    </div>

</div>


<hr class="my-4">


{{-- ================================================================ --}}
{{-- PROJECT REFERENCES --}}
{{-- ================================================================ --}}

<h6 class="fw-bold mb-3">
    Project References
</h6>


<div class="row g-3">

    {{-- Contract --}}
    <div class="col-md-6">

        <label class="form-label">
            Procurement Contract
        </label>

        <select
            name="procurement_contract_id"
            class="form-select @error('procurement_contract_id') is-invalid @enderror"
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
                            $itp->procurement_contract_id ?? ''
                        ) == $contract->id
                    )
                >

                    {{ $contract->contract_number }}

                    @if($contract->contract_title)
                        - {{ $contract->contract_title }}
                    @endif

                    @if($contract->bidder)
                        - {{ $contract->bidder->company_name }}
                    @elseif($contract->bidder_name)
                        - {{ $contract->bidder_name }}
                    @endif

                </option>

            @endforeach

        </select>

        @error('procurement_contract_id')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- Work Order --}}
    <div class="col-md-6">

        <label class="form-label">
            Work Order
        </label>

        <select
            name="work_order_id"
            class="form-select @error('work_order_id') is-invalid @enderror"
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
                            $itp->work_order_id ?? ''
                        ) == $workOrder->id
                    )
                >

                    {{ $workOrder->work_order_number }}

                    @if($workOrder->work_order_title)
                        - {{ $workOrder->work_order_title }}
                    @endif

                    @if($workOrder->contract?->bidder)
                        -
                        {{ $workOrder->contract->bidder->company_name }}
                    @endif

                </option>

            @endforeach

        </select>

        @error('work_order_id')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- Prepared By --}}
    <div class="col-md-6">

        <label class="form-label">
            Prepared By
        </label>

        <select
            name="prepared_by"
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
                            'prepared_by',
                            $itp->prepared_by ?? auth()->id()
                        ) == $user->id
                    )
                >
                    {{ $user->name }}
                </option>

            @endforeach

        </select>

    </div>


    {{-- Prepared Date --}}
    <div class="col-md-6">

        <label class="form-label">
            Prepared Date
        </label>

        <input
            type="date"
            name="prepared_date"
            value="{{ old(
                'prepared_date',
                isset($itp) && $itp->prepared_date
                    ? $itp->prepared_date->format('Y-m-d')
                    : now()->format('Y-m-d')
            ) }}"
            class="form-control"
        >

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
            placeholder="Additional remarks..."
        >{{ old(
            'remarks',
            $itp->remarks ?? ''
        ) }}</textarea>

    </div>

</div>