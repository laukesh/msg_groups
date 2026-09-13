<div class="row g-3">

    {{-- ================================================================ --}}
    {{-- BASIC NCR INFORMATION --}}
    {{-- ================================================================ --}}

    <div class="col-md-4">

        <label class="form-label">
            NCR Date <span class="text-danger">*</span>
        </label>

        <input
            type="date"
            name="ncr_date"
            value="{{ old(
                'ncr_date',
                isset($ncr) && $ncr->ncr_date
                    ? $ncr->ncr_date->format('Y-m-d')
                    : now()->format('Y-m-d')
            ) }}"
            class="form-control @error('ncr_date') is-invalid @enderror"
            required
        >

        @error('ncr_date')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    <div class="col-md-8">

        <label class="form-label">
            NCR Title <span class="text-danger">*</span>
        </label>

        <input
            type="text"
            name="title"
            value="{{ old(
                'title',
                $ncr->title ?? ''
            ) }}"
            class="form-control @error('title') is-invalid @enderror"
            placeholder="e.g. Reinforcement spacing does not comply with approved drawing"
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
            Severity <span class="text-danger">*</span>
        </label>

        <select
            name="severity"
            class="form-select @error('severity') is-invalid @enderror"
            required
        >

            @foreach([
                'Minor',
                'Major',
                'Critical',
            ] as $severity)

                <option
                    value="{{ $severity }}"
                    @selected(
                        old(
                            'severity',
                            $ncr->severity ?? 'Minor'
                        ) === $severity
                    )
                >
                    {{ $severity }}
                </option>

            @endforeach

        </select>

        @error('severity')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    <div class="col-md-8">

        <label class="form-label">
            Location
        </label>

        <input
            type="text"
            name="location"
            value="{{ old(
                'location',
                $ncr->location ?? ''
            ) }}"
            class="form-control"
            placeholder="e.g. Tower A, Level 3, Grid B-4"
        >

    </div>


    <div class="col-md-12">

        <label class="form-label">
            Description <span class="text-danger">*</span>
        </label>

        <textarea
            name="description"
            rows="5"
            class="form-control @error('description') is-invalid @enderror"
            placeholder="Describe the non-conformance in detail..."
            required
        >{{ old(
            'description',
            $ncr->description ?? ''
        ) }}</textarea>

        @error('description')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- ================================================================ --}}
    {{-- PROJECT REFERENCES --}}
    {{-- ================================================================ --}}

    <div class="col-12 mt-4">

        <h6 class="fw-bold mb-0">
            Project References
        </h6>

        <div class="text-muted small">
            Link the NCR to the relevant construction records.
        </div>

    </div>


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
                            $ncr->procurement_contract_id ?? ''
                        ) == $contract->id
                    )
                >

                    {{ $contract->contract_number }}

                    @if($contract->contract_title)
                        - {{ $contract->contract_title }}
                    @endif

                    @if($contract->bidder)

                        -
                        {{ $contract->bidder->company_name }}

                    @elseif($contract->bidder_name)

                        -
                        {{ $contract->bidder_name }}

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
                            $ncr->work_order_id ?? ''
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


    {{-- ================================================================ --}}
    {{-- ITP --}}
    {{-- ================================================================ --}}

    <div class="col-md-6">

        <label class="form-label">
            Inspection & Test Plan
        </label>

        <select
            name="construction_quality_itp_id"
            id="construction_quality_itp_id"
            class="form-select"
        >

            <option value="">
                Select ITP
            </option>

            @foreach($itps as $itp)

                <option
                    value="{{ $itp->id }}"
                    @selected(
                        old(
                            'construction_quality_itp_id',
                            $ncr->construction_quality_itp_id ?? ''
                        ) == $itp->id
                    )
                >

                    {{ $itp->itp_number }}
                    -
                    {{ $itp->title }}

                </option>

            @endforeach

        </select>

    </div>


    {{-- ITP Item --}}
    <div class="col-md-6">

        <label class="form-label">
            ITP Item
        </label>

        <select
            name="construction_quality_itp_item_id"
            id="construction_quality_itp_item_id"
            class="form-select"
        >

            <option value="">
                Select ITP Item
            </option>

            @foreach($itps as $itp)

                @foreach($itp->items as $item)

                    <option
                        value="{{ $item->id }}"
                        data-itp="{{ $itp->id }}"
                        @selected(
                            old(
                                'construction_quality_itp_item_id',
                                $ncr->construction_quality_itp_item_id ?? ''
                            ) == $item->id
                        )
                    >

                        {{ $itp->itp_number }}
                        -
                        Item {{ $item->item_number }}
                        -
                        {{ $item->activity }}

                    </option>

                @endforeach

            @endforeach

        </select>

    </div>


    {{-- ================================================================ --}}
    {{-- INSPECTION --}}
    {{-- ================================================================ --}}

    <div class="col-md-12">

        <label class="form-label">
            Related Inspection
        </label>

        <select
            name="construction_inspection_id"
            class="form-select @error('construction_inspection_id') is-invalid @enderror"
        >

            <option value="">
                Select Inspection
            </option>

            @foreach($inspections as $inspection)

                <option
                    value="{{ $inspection->id }}"
                    @selected(
                        old(
                            'construction_inspection_id',
                            $ncr->construction_inspection_id ?? ''
                        ) == $inspection->id
                    )
                >

                    {{ $inspection->inspection_number }}

                    @if($inspection->title)
                        - {{ $inspection->title }}
                    @endif

                    @if($inspection->result)
                        ({{ $inspection->result }})
                    @endif

                </option>

            @endforeach

        </select>

        @error('construction_inspection_id')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

        <div class="form-text">
            Select the inspection that identified this non-conformance,
            if applicable.
        </div>

    </div>


    {{-- ================================================================ --}}
    {{-- RESPONSIBILITY --}}
    {{-- ================================================================ --}}

    <div class="col-md-6">

        <label class="form-label">
            Raised By
        </label>

        <select
            name="raised_by"
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
                            'raised_by',
                            $ncr->raised_by ?? auth()->id()
                        ) == $user->id
                    )
                >
                    {{ $user->name }}
                </option>

            @endforeach

        </select>

    </div>


    <div class="col-md-6">

        <label class="form-label">
            Responsible Party
        </label>

        <select
            name="responsible_party"
            class="form-select"
        >

            <option value="">
                Select Responsible Party
            </option>

            @foreach([
                'Contractor',
                'Consultant',
                'Client',
                'QA/QC',
                'HSE',
                'Third Party',
            ] as $party)

                <option
                    value="{{ $party }}"
                    @selected(
                        old(
                            'responsible_party',
                            $ncr->responsible_party ?? ''
                        ) === $party
                    )
                >
                    {{ $party }}
                </option>

            @endforeach

        </select>

    </div>


    {{-- ================================================================ --}}
    {{-- CORRECTIVE ACTION --}}
    {{-- ================================================================ --}}

    <div class="col-md-12 mt-4">

        <h6 class="fw-bold mb-0">
            Required Corrective Action
        </h6>

        <div class="text-muted small">
            Describe what needs to be done to rectify the non-conformance.
        </div>

    </div>


    <div class="col-md-8">

        <label class="form-label">
            Required Action
        </label>

        <textarea
            name="required_action"
            rows="5"
            class="form-control"
            placeholder="Describe the required corrective action..."
        >{{ old(
            'required_action',
            $ncr->required_action ?? ''
        ) }}</textarea>

    </div>


    <div class="col-md-4">

        <label class="form-label">
            Corrective Action Due Date
        </label>

        <input
            type="date"
            name="due_date"
            value="{{ old(
                'due_date',
                isset($ncr) && $ncr->due_date
                    ? $ncr->due_date->format('Y-m-d')
                    : ''
            ) }}"
            class="form-control"
        >

    </div>


    {{-- ================================================================ --}}
    {{-- REMARKS --}}
    {{-- ================================================================ --}}

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
            $ncr->remarks ?? ''
        ) }}</textarea>

    </div>

</div>


{{-- ================================================================== --}}
{{-- ITP ITEM FILTER --}}
{{-- ================================================================== --}}

<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        const itpSelect =
            document.getElementById(
                'construction_quality_itp_id'
            );

        const itemSelect =
            document.getElementById(
                'construction_quality_itp_item_id'
            );


        if (!itpSelect || !itemSelect) {
            return;
        }


        const originalOptions =
            Array.from(
                itemSelect.options
            ).map(
                option => option.cloneNode(true)
            );


        function filterItems()
        {

            const selectedItp =
                itpSelect.value;


            const currentItem =
                itemSelect.value;


            itemSelect.innerHTML = '';


            const placeholder =
                document.createElement(
                    'option'
                );

            placeholder.value = '';

            placeholder.textContent =
                'Select ITP Item';

            itemSelect.appendChild(
                placeholder
            );


            originalOptions
                .slice(1)
                .forEach(
                    function (option) {

                        if (
                            !selectedItp
                            ||
                            option.dataset.itp ===
                                selectedItp
                        ) {

                            itemSelect.appendChild(
                                option
                            );

                        }

                    }
                );


            if (
                Array.from(
                    itemSelect.options
                ).some(
                    option =>
                        option.value ===
                        currentItem
                )
            ) {

                itemSelect.value =
                    currentItem;

            }

        }


        itpSelect.addEventListener(
            'change',
            filterItems
        );


        filterItems();

    }
);

</script>