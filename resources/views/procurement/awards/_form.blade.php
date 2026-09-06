<div class="row g-3">

    {{-- Approved Negotiation --}}
    <div class="col-md-6">

        <label class="form-label">
            Approved Negotiation
            <span class="text-danger">*</span>
        </label>

        <select
            name="procurement_negotiation_id"
            id="procurement_negotiation_id"
            class="form-select @error('procurement_negotiation_id') is-invalid @enderror"
            required
        >

            <option value="">
                -- Select Approved Negotiation --
            </option>

            @foreach($negotiations as $negotiation)

                <option
                    value="{{ $negotiation->id }}"
                    data-bidder="{{ $negotiation->bidder_name }}"
                    data-amount="{{ $negotiation->final_amount }}"
                    data-currency="{{ $negotiation->currency }}"
                    @selected(
                        old('procurement_negotiation_id')
                        == $negotiation->id
                    )
                >

                    {{ $negotiation->negotiation_number }}

                    -

                    {{ $negotiation->bidder_name }}

                </option>

            @endforeach

        </select>

        @error('procurement_negotiation_id')

            <div class="invalid-feedback">
                {{ $message }}
            </div>

        @enderror

        <div class="form-text">
            Only approved negotiations are available for Award.
        </div>

    </div>


    {{-- Auto Generated Award Number --}}
    <div class="col-md-6">

        <label class="form-label">
            Award Number
        </label>

        <input
            type="text"
            class="form-control"
            value="Auto Generated"
            readonly
        >

        <div class="form-text">
            Award number will be generated automatically
            after saving.
        </div>

    </div>


    {{-- Award Title --}}
    <div class="col-md-8">

        <label class="form-label">

            Award Title

            <span class="text-danger">*</span>

        </label>

        <input
            type="text"
            name="award_title"
            class="form-control @error('award_title') is-invalid @enderror"
            value="{{ old('award_title') }}"
            placeholder="Award for Procurement Tender"
            required
        >

        @error('award_title')

            <div class="invalid-feedback">
                {{ $message }}
            </div>

        @enderror

    </div>


    {{-- Award Date --}}
    <div class="col-md-4">

        <label class="form-label">
            Award Date
        </label>

        <input
            type="date"
            name="award_date"
            class="form-control @error('award_date') is-invalid @enderror"
            value="{{ old(
                'award_date',
                now()->format('Y-m-d')
            ) }}"
        >

        @error('award_date')

            <div class="invalid-feedback">
                {{ $message }}
            </div>

        @enderror

    </div>


    {{-- Award Type --}}
    <div class="col-md-6">

        <label class="form-label">
            Award Type
        </label>

        <select
            name="award_type"
            class="form-select"
        >

            <option
                value="Letter of Award"
                @selected(
                    old(
                        'award_type',
                        'Letter of Award'
                    ) === 'Letter of Award'
                )
            >
                Letter of Award
            </option>

            <option
                value="Work Order"
                @selected(
                    old(
                        'award_type'
                    ) === 'Work Order'
                )
            >
                Work Order
            </option>

            <option
                value="Purchase Order"
                @selected(
                    old(
                        'award_type'
                    ) === 'Purchase Order'
                )
            >
                Purchase Order
            </option>

        </select>

    </div>


    {{-- LOA Number --}}
    <div class="col-md-6">

        <label class="form-label">
            LOA Number
        </label>

        <input
            type="text"
            name="loa_number"
            class="form-control @error('loa_number') is-invalid @enderror"
            value="{{ old('loa_number') }}"
            placeholder="Will be issued later"
        >

        @error('loa_number')

            <div class="invalid-feedback">
                {{ $message }}
            </div>

        @enderror

    </div>


    {{-- LOA Date --}}
    <div class="col-md-6">

        <label class="form-label">
            LOA Date
        </label>

        <input
            type="date"
            name="loa_date"
            class="form-control @error('loa_date') is-invalid @enderror"
            value="{{ old('loa_date') }}"
        >

        @error('loa_date')

            <div class="invalid-feedback">
                {{ $message }}
            </div>

        @enderror

    </div>


    {{-- Acceptance Deadline --}}
    <div class="col-md-6">

        <label class="form-label">
            Acceptance Deadline
        </label>

        <input
            type="date"
            name="acceptance_deadline"
            class="form-control @error('acceptance_deadline') is-invalid @enderror"
            value="{{ old('acceptance_deadline') }}"
        >

        @error('acceptance_deadline')

            <div class="invalid-feedback">
                {{ $message }}
            </div>

        @enderror

    </div>


    {{-- Selected Negotiation Information --}}
    <div class="col-12">

        <div class="alert alert-info mb-0">

            <div class="row">

                <div class="col-md-4">

                    <small class="text-muted d-block">
                        Awarded Bidder
                    </small>

                    <strong id="selectedBidder">
                        —
                    </strong>

                </div>


                <div class="col-md-4">

                    <small class="text-muted d-block">
                        Final Negotiated Amount
                    </small>

                    <strong id="selectedAmount">
                        —
                    </strong>

                </div>


                <div class="col-md-4">

                    <small class="text-muted d-block">
                        Currency
                    </small>

                    <strong id="selectedCurrency">
                        —
                    </strong>

                </div>

            </div>

        </div>

    </div>


    {{-- Contract Required --}}
    <div class="col-md-6">

        <label class="form-label">
            Contract Required?
        </label>

        <select
            name="contract_required"
            class="form-select"
        >

            <option
                value="1"
                @selected(
                    old(
                        'contract_required',
                        '1'
                    ) == '1'
                )
            >
                Yes
            </option>

            <option
                value="0"
                @selected(
                    old(
                        'contract_required'
                    ) === '0'
                )
            >
                No
            </option>

        </select>

    </div>


    {{-- Description --}}
    <div class="col-12">

        <label class="form-label">
            Description
        </label>

        <textarea
            name="description"
            rows="3"
            class="form-control @error('description') is-invalid @enderror"
            placeholder="Award description"
        >{{ old('description') }}</textarea>

        @error('description')

            <div class="invalid-feedback">
                {{ $message }}
            </div>

        @enderror

    </div>


    {{-- Terms --}}
    <div class="col-12">

        <label class="form-label">
            Terms & Conditions
        </label>

        <textarea
            name="terms_and_conditions"
            rows="5"
            class="form-control @error('terms_and_conditions') is-invalid @enderror"
            placeholder="Enter award terms and conditions"
        >{{ old('terms_and_conditions') }}</textarea>

        @error('terms_and_conditions')

            <div class="invalid-feedback">
                {{ $message }}
            </div>

        @enderror

    </div>


    {{-- Remarks --}}
    <div class="col-12">

        <label class="form-label">
            Remarks
        </label>

        <textarea
            name="remarks"
            rows="3"
            class="form-control @error('remarks') is-invalid @enderror"
            placeholder="Additional remarks"
        >{{ old('remarks') }}</textarea>

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

        const select =
            document.getElementById(
                'procurement_negotiation_id'
            );

        const bidder =
            document.getElementById(
                'selectedBidder'
            );

        const amount =
            document.getElementById(
                'selectedAmount'
            );

        const currency =
            document.getElementById(
                'selectedCurrency'
            );


        function updateAward()
        {
            const option =
                select.options[
                    select.selectedIndex
                ];


            if (
                !option ||
                !option.value
            ) {

                bidder.textContent = '—';
                amount.textContent = '—';
                currency.textContent = '—';

                return;
            }


            bidder.textContent =
                option.dataset.bidder || '—';


            const numericAmount =
                Number(
                    option.dataset.amount || 0
                );


            amount.textContent =
                numericAmount.toLocaleString(
                    'en-IN',
                    {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }
                );


            currency.textContent =
                option.dataset.currency || 'USD';
        }


        select.addEventListener(
            'change',
            updateAward
        );


        updateAward();

    }
);

</script>