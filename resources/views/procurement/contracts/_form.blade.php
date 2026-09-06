<div class="row g-3">

    {{-- ============================================================
        LOA / AWARD
    ============================================================= --}}

    <div class="col-md-6">

        <label class="form-label">

            LOA / Award

            <span class="text-danger">*</span>

        </label>


        <select
            name="procurement_award_id"
            id="procurement_award_id"
            class="form-select @error('procurement_award_id') is-invalid @enderror"
            required
        >

            <option value="">
                -- Select LOA Issued Award --
            </option>


            @foreach($awards as $award)

                <option
                    value="{{ $award->id }}"

                    data-bidder="{{ $award->bidder_name }}"

                    data-amount="{{ $award->awarded_amount }}"

                    data-currency="{{ $award->currency }}"

                    data-loa="{{ $award->loa_number }}"

                    data-loa-date="{{
                        $award->loa_date?->format('Y-m-d')
                    }}"

                    @selected(
                        old(
                            'procurement_award_id'
                        ) == $award->id
                    )
                >

                    {{ $award->award_number }}

                    -

                    {{ $award->bidder_name }}

                </option>

            @endforeach

        </select>


        @error('procurement_award_id')

            <div class="invalid-feedback">
                {{ $message }}
            </div>

        @enderror

    </div>


    {{-- ============================================================
        CONTRACT NUMBER
    ============================================================= --}}

    <div class="col-md-6">

        <label class="form-label">

            Contract Number

        </label>


        <input
            type="text"
            class="form-control"
            value="Will be generated automatically"
            readonly
        >


        <div class="form-text">

            Contract number will be generated automatically
            when the Contract is saved.

        </div>

    </div>


    {{-- ============================================================
        CONTRACT TITLE
    ============================================================= --}}

    <div class="col-md-8">

        <label class="form-label">

            Contract Title

            <span class="text-danger">*</span>

        </label>


        <input
            type="text"
            name="contract_title"
            class="form-control @error('contract_title') is-invalid @enderror"
            value="{{ old('contract_title') }}"
            placeholder="Procurement Contract"
            required
        >


        @error('contract_title')

            <div class="invalid-feedback">
                {{ $message }}
            </div>

        @enderror

    </div>


    {{-- ============================================================
        CONTRACT TYPE
    ============================================================= --}}

    <div class="col-md-4">

        <label class="form-label">
            Contract Type
        </label>


        <select
            name="contract_type"
            class="form-select"
        >

            <option
                value="Procurement Contract"
                @selected(
                    old(
                        'contract_type',
                        'Procurement Contract'
                    ) === 'Procurement Contract'
                )
            >
                Procurement Contract
            </option>


            <option
                value="Works Contract"
                @selected(
                    old('contract_type')
                    === 'Works Contract'
                )
            >
                Works Contract
            </option>


            <option
                value="Service Contract"
                @selected(
                    old('contract_type')
                    === 'Service Contract'
                )
            >
                Service Contract
            </option>


            <option
                value="Supply Contract"
                @selected(
                    old('contract_type')
                    === 'Supply Contract'
                )
            >
                Supply Contract
            </option>

        </select>

    </div>


    {{-- ============================================================
        SELECTED AWARD SUMMARY
    ============================================================= --}}

    <div class="col-12">

        <div class="alert alert-info">

            <div class="row">

                {{-- Bidder --}}
                <div class="col-md-3">

                    <small class="text-muted d-block">
                        Bidder
                    </small>

                    <strong id="selectedBidder">
                        —
                    </strong>

                </div>


                {{-- Contract Amount --}}
                <div class="col-md-3">

                    <small class="text-muted d-block">
                        Contract Amount
                    </small>

                    <strong id="selectedAmount">
                        —
                    </strong>

                </div>


                {{-- Currency --}}
                <div class="col-md-3">

                    <small class="text-muted d-block">
                        Currency
                    </small>

                    <strong id="selectedCurrency">
                        —
                    </strong>

                </div>


                {{-- LOA --}}
                <div class="col-md-3">

                    <small class="text-muted d-block">
                        LOA
                    </small>

                    <strong id="selectedLoa">
                        —
                    </strong>

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================
        CONTRACT START DATE
    ============================================================= --}}

    <div class="col-md-4">

        <label class="form-label">
            Contract Start Date
        </label>


        <input
            type="date"
            name="contract_start_date"
            class="form-control @error('contract_start_date') is-invalid @enderror"
            value="{{ old('contract_start_date') }}"
        >


        @error('contract_start_date')

            <div class="invalid-feedback">
                {{ $message }}
            </div>

        @enderror

    </div>


    {{-- ============================================================
        CONTRACT END DATE
    ============================================================= --}}

    <div class="col-md-4">

        <label class="form-label">
            Contract End Date
        </label>


        <input
            type="date"
            name="contract_end_date"
            class="form-control @error('contract_end_date') is-invalid @enderror"
            value="{{ old('contract_end_date') }}"
        >


        @error('contract_end_date')

            <div class="invalid-feedback">
                {{ $message }}
            </div>

        @enderror

    </div>


    {{-- ============================================================
        DURATION
    ============================================================= --}}

    <div class="col-md-4">

        <label class="form-label">
            Duration (Days)
        </label>


        <input
            type="number"
            name="contract_duration_days"
            class="form-control @error('contract_duration_days') is-invalid @enderror"
            min="1"
            value="{{ old('contract_duration_days') }}"
        >


        @error('contract_duration_days')

            <div class="invalid-feedback">
                {{ $message }}
            </div>

        @enderror

    </div>


    {{-- ============================================================
        SIGNING DATE
    ============================================================= --}}

    <div class="col-md-4">

        <label class="form-label">
            Signing Date
        </label>


        <input
            type="date"
            name="signing_date"
            class="form-control @error('signing_date') is-invalid @enderror"
            value="{{ old('signing_date') }}"
        >


        @error('signing_date')

            <div class="invalid-feedback">
                {{ $message }}
            </div>

        @enderror

    </div>


    {{-- ============================================================
        PERFORMANCE SECURITY
    ============================================================= --}}

    <div class="col-md-4">

        <label class="form-label">
            Performance Security
        </label>


        <select
            name="performance_security_required"
            class="form-select"
        >

            <option
                value="0"
                @selected(
                    old(
                        'performance_security_required',
                        '0'
                    ) == '0'
                )
            >
                Not Required
            </option>


            <option
                value="1"
                @selected(
                    old(
                        'performance_security_required'
                    ) == '1'
                )
            >
                Required
            </option>

        </select>

    </div>


    {{-- ============================================================
        PERFORMANCE SECURITY AMOUNT
    ============================================================= --}}

    <div class="col-md-4">

        <label class="form-label">
            Performance Security Amount
        </label>


        <input
            type="number"
            step="0.01"
            min="0"
            name="performance_security_amount"
            class="form-control"
            value="{{ old(
                'performance_security_amount',
                0
            ) }}"
        >

    </div>


    {{-- ============================================================
        RETENTION
    ============================================================= --}}

    <div class="col-md-4">

        <label class="form-label">
            Retention
        </label>


        <select
            name="retention_required"
            class="form-select"
        >

            <option
                value="0"
                @selected(
                    old(
                        'retention_required',
                        '0'
                    ) == '0'
                )
            >
                Not Required
            </option>


            <option
                value="1"
                @selected(
                    old(
                        'retention_required'
                    ) == '1'
                )
            >
                Required
            </option>

        </select>

    </div>


    {{-- ============================================================
        RETENTION %
    ============================================================= --}}

    <div class="col-md-4">

        <label class="form-label">
            Retention %
        </label>


        <input
            type="number"
            step="0.01"
            min="0"
            max="100"
            name="retention_percentage"
            class="form-control"
            value="{{ old(
                'retention_percentage',
                0
            ) }}"
        >

    </div>


    {{-- ============================================================
        SCOPE
    ============================================================= --}}

    <div class="col-12">

        <label class="form-label">
            Scope of Work
        </label>


        <textarea
            name="scope_of_work"
            rows="4"
            class="form-control"
            placeholder="Enter scope of work"
        >{{ old('scope_of_work') }}</textarea>

    </div>


    {{-- ============================================================
        TERMS
    ============================================================= --}}

    <div class="col-12">

        <label class="form-label">
            Terms & Conditions
        </label>


        <textarea
            name="terms_and_conditions"
            rows="5"
            class="form-control"
            placeholder="Enter contract terms and conditions"
        >{{ old('terms_and_conditions') }}</textarea>

    </div>


    {{-- ============================================================
        SPECIAL CONDITIONS
    ============================================================= --}}

    <div class="col-12">

        <label class="form-label">
            Special Conditions
        </label>


        <textarea
            name="special_conditions"
            rows="4"
            class="form-control"
            placeholder="Enter special conditions"
        >{{ old('special_conditions') }}</textarea>

    </div>


    {{-- ============================================================
        REMARKS
    ============================================================= --}}

    <div class="col-12">

        <label class="form-label">
            Remarks
        </label>


        <textarea
            name="remarks"
            rows="3"
            class="form-control"
            placeholder="Additional remarks"
        >{{ old('remarks') }}</textarea>

    </div>

</div>


{{-- ================================================================
    JAVASCRIPT
================================================================ --}}

<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        const select =
            document.getElementById(
                'procurement_award_id'
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


        const loa =
            document.getElementById(
                'selectedLoa'
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

                bidder.textContent =
                    '—';

                amount.textContent =
                    '—';

                currency.textContent =
                    '—';

                loa.textContent =
                    '—';

                return;
            }


            bidder.textContent =
                option.dataset.bidder
                || '—';


            const amountValue =
                Number(
                    option.dataset.amount
                    || 0
                );


            amount.textContent =
                amountValue.toLocaleString(
                    undefined,
                    {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }
                );


            currency.textContent =
                option.dataset.currency
                || 'USD';


            loa.textContent =
                option.dataset.loa
                || '—';
        }


        select.addEventListener(
            'change',
            updateAward
        );


        updateAward();

    }
);

</script>