@php
    $instruction = $siteInstruction ?? null;
@endphp

<div class="row g-4">

    {{-- Instruction Date --}}
    <div class="col-md-4">

        <label class="form-label">
            Instruction Date
            <span class="text-danger">*</span>
        </label>

        <input
            type="date"
            name="instruction_date"
            class="form-control @error('instruction_date') is-invalid @enderror"
            value="{{ old(
                'instruction_date',
                $instruction?->instruction_date?->format('Y-m-d')
                ?? now()->format('Y-m-d')
            ) }}"
            required
        >

        @error('instruction_date')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- Instruction Number --}}
    @if($instruction)

        <div class="col-md-4">

            <label class="form-label">
                Instruction Number
            </label>

            <input
                type="text"
                class="form-control"
                value="{{ $instruction->instruction_number }}"
                readonly
            >

        </div>

    @endif


    {{-- Instruction Type --}}
    <div class="col-md-4">

        <label class="form-label">
            Instruction Type
        </label>

        <select
            name="instruction_type"
            class="form-select @error('instruction_type') is-invalid @enderror"
        >

            <option value="">
                Select Type
            </option>

            @foreach([
                'General',
                'Design',
                'Construction',
                'Quality',
                'Safety',
                'Material',
                'Programme',
                'Commercial',
                'Coordination',
                'Other',
            ] as $type)

                <option
                    value="{{ $type }}"
                    @selected(
                        old(
                            'instruction_type',
                            $instruction?->instruction_type
                        ) === $type
                    )
                >
                    {{ $type }}
                </option>

            @endforeach

        </select>

        @error('instruction_type')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- Subject --}}
    <div class="col-12">

        <label class="form-label">
            Subject
            <span class="text-danger">*</span>
        </label>

        <input
            type="text"
            name="subject"
            class="form-control @error('subject') is-invalid @enderror"
            value="{{ old(
                'subject',
                $instruction?->subject
            ) }}"
            maxlength="255"
            required
        >

        @error('subject')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- Description --}}
    <div class="col-12">

        <label class="form-label">
            Description / Instruction
        </label>

        <textarea
            name="description"
            rows="5"
            class="form-control @error('description') is-invalid @enderror"
        >{{ old(
            'description',
            $instruction?->description
        ) }}</textarea>

        @error('description')
            <div class="invalid-feedback">
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
            id="procurement_contract_id"
            class="form-select @error('procurement_contract_id') is-invalid @enderror"
        >

            <option value="">
                Select Contract
            </option>

            @foreach($contracts as $contract)

                <option
                    value="{{ $contract->id }}"
                    @selected(
                        (string) old(
                            'procurement_contract_id',
                            $instruction?->procurement_contract_id
                        )
                        ===
                        (string) $contract->id
                    )
                >

                    {{ $contract->contract_number }}

                    -
                    
                    {{
                        $contract->bidder?->company_name
                        ?? $contract->bidder_name
                        ?? 'Contractor'
                    }}

                </option>

            @endforeach

        </select>

        @error('procurement_contract_id')

            <div class="invalid-feedback">
                {{ $message }}
            </div>

        @enderror

    </div>


    <div class="col-md-6">

        <label class="form-label">
            Contractor
        </label>

        <input
            type="text"
            id="contractor_name"
            class="form-control"
            value=""
            readonly
            placeholder="Select a contract"
        >

        <div class="form-text">
            Contractor is automatically taken from the selected contract.
        </div>

    </div>


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
                        (string) old(
                            'work_order_id',
                            $instruction?->work_order_id
                        )
                        ===
                        (string) $workOrder->id
                    )
                >

                    {{ $workOrder->work_order_number }}

                    -

                    {{ $workOrder->work_order_title }}

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
                    (string) old(
                        'consultant_id',
                        $instruction?->consultant_id
                    )
                    ===
                    (string) $consultant->id
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
            Schedule Activity ID
        </label>

        <input
            type="number"
            name="schedule_activity_id"
            class="form-control @error('schedule_activity_id') is-invalid @enderror"
            value="{{ old(
                'schedule_activity_id',
                $instruction?->schedule_activity_id
            ) }}"
        >

        @error('schedule_activity_id')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

        <div class="form-text">
            Link this instruction to the relevant schedule activity.
        </div>

    </div>


    {{-- Location --}}
    <div class="col-md-6">

        <label class="form-label">
            Location / Area
        </label>

        <input
            type="text"
            name="location"
            class="form-control @error('location') is-invalid @enderror"
            value="{{ old(
                'location',
                $instruction?->location
            ) }}"
            maxlength="255"
        >

        @error('location')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- Priority --}}
    <div class="col-md-6">

        <label class="form-label">
            Priority
            <span class="text-danger">*</span>
        </label>

        <select
            name="priority"
            class="form-select @error('priority') is-invalid @enderror"
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
                            $instruction?->priority
                            ?? 'Normal'
                        ) === $priority
                    )
                >
                    {{ $priority }}
                </option>

            @endforeach

        </select>

        @error('priority')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- Required Action --}}
    <div class="col-12">

        <label class="form-label">
            Required Action
        </label>

        <textarea
            name="required_action"
            rows="4"
            class="form-control @error('required_action') is-invalid @enderror"
        >{{ old(
            'required_action',
            $instruction?->required_action
        ) }}</textarea>

        @error('required_action')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- Due Date --}}
    <div class="col-md-4">

        <label class="form-label">
            Due Date
        </label>

        <input
            type="date"
            name="due_date"
            class="form-control @error('due_date') is-invalid @enderror"
            value="{{ old(
                'due_date',
                $instruction?->due_date?->format('Y-m-d')
            ) }}"
        >

        @error('due_date')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- Remarks --}}
    <div class="col-md-8">

        <label class="form-label">
            Remarks
        </label>

        <textarea
            name="remarks"
            rows="2"
            class="form-control @error('remarks') is-invalid @enderror"
        >{{ old(
            'remarks',
            $instruction?->remarks
        ) }}</textarea>

        @error('remarks')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>

</div>

<script>
document.addEventListener(
    'DOMContentLoaded',
    function () {

        const contractSelect =
            document.getElementById(
                'procurement_contract_id'
            );

        const contractorName =
            document.getElementById(
                'contractor_name'
            );

        const contracts = @json(
            $contracts->map(
                function ($contract) {

                    return [

                        'id' =>
                            $contract->id,

                        'contractor' =>
                            $contract->bidder?->company_name
                            ??
                            $contract->bidder_name
                            ??
                            '—',

                    ];

                }
            )->values()
        );


        function updateContractor()
        {
            const selectedId =
                contractSelect.value;


            const selected =
                contracts.find(
                    function (contract) {

                        return String(
                            contract.id
                        )
                        ===
                        String(
                            selectedId
                        );

                    }
                );


            contractorName.value =
                selected
                    ? selected.contractor
                    : '';
        }


        contractSelect.addEventListener(
            'change',
            updateContractor
        );


        updateContractor();

    }
);
</script>