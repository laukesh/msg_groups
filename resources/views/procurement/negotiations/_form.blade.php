<div class="row g-3">

    {{-- Bid Comparison --}}
    <div class="col-md-6">

        <label class="form-label">
            Bid Comparison
            <span class="text-danger">*</span>
        </label>

        <select
            name="procurement_bid_comparison_id"
            id="procurement_bid_comparison_id"
            class="form-select @error('procurement_bid_comparison_id') is-invalid @enderror"
            required
        >

            <option value="">
                -- Select Bid Comparison --
            </option>

            @foreach($comparisons as $comparison)

                @php

                    $recommended =
                        $comparison
                            ->recommendedSubmission
                            ?->tenderBidder
                            ?->bidder
                            ?->company_name
                        ?? 'Unknown Bidder';

                @endphp

                <option
                    value="{{ $comparison->id }}"
                    data-amount="{{ $comparison->lowest_evaluated_amount }}"
                    data-currency="{{ $comparison->currency }}"
                    data-bidder="{{ $recommended }}"
                    @selected(
                        old(
                            'procurement_bid_comparison_id'
                        ) == $comparison->id
                    )
                >

                    {{ $comparison->comparison_number }}

                    -

                    {{ $comparison->comparison_title }}

                    |

                    {{ $recommended }}

                </option>

            @endforeach

        </select>

        @error('procurement_bid_comparison_id')

            <div class="invalid-feedback">
                {{ $message }}
            </div>

        @enderror

    </div>


    {{-- Negotiation Number --}}
    <div class="col-md-6">

        <label class="form-label">
            Negotiation Number
        </label>

        <div class="form-control bg-light text-muted">
            Auto-generated after saving
        </div>

        <div class="form-text">
            The Negotiation Number will be generated automatically.
        </div>

    </div>


    {{-- Negotiation Title --}}
    <div class="col-md-8">

        <label class="form-label">
            Negotiation Title
            <span class="text-danger">*</span>
        </label>

        <input
            type="text"
            name="negotiation_title"
            class="form-control @error('negotiation_title') is-invalid @enderror"
            value="{{ old('negotiation_title') }}"
            placeholder="Negotiation with Recommended Bidder"
            required
        >

        @error('negotiation_title')

            <div class="invalid-feedback">
                {{ $message }}
            </div>

        @enderror

    </div>


    {{-- Negotiation Date --}}
    <div class="col-md-4">

        <label class="form-label">
            Negotiation Date
        </label>

        <input
            type="date"
            name="negotiation_date"
            class="form-control @error('negotiation_date') is-invalid @enderror"
            value="{{ old(
                'negotiation_date',
                now()->format('Y-m-d')
            ) }}"
        >

        @error('negotiation_date')

            <div class="invalid-feedback">
                {{ $message }}
            </div>

        @enderror

    </div>


    {{-- Negotiation Type --}}
    <div class="col-md-6">

        <label class="form-label">
            Negotiation Type
        </label>

        <select
            name="negotiation_type"
            class="form-select @error('negotiation_type') is-invalid @enderror"
        >

            <option value="">
                -- Select Type --
            </option>

            @foreach([
                'Price Negotiation',
                'Commercial Negotiation',
                'Technical & Commercial Negotiation',
                'Final Negotiation',
            ] as $type)

                <option
                    value="{{ $type }}"
                    @selected(
                        old('negotiation_type') === $type
                    )
                >
                    {{ $type }}
                </option>

            @endforeach

        </select>

        @error('negotiation_type')

            <div class="invalid-feedback">
                {{ $message }}
            </div>

        @enderror

    </div>


    {{-- Status --}}
    <div class="col-md-6">

        <label class="form-label">
            Status
            <span class="text-danger">*</span>
        </label>

        <select
            name="status"
            class="form-select @error('status') is-invalid @enderror"
            required
        >

            @foreach([
                'Draft',
                'Under Review',
                'Completed',
                'Approved',
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

        @error('status')

            <div class="invalid-feedback">
                {{ $message }}
            </div>

        @enderror

    </div>


    {{-- Recommended Bidder Information --}}
    <div class="col-12">

        <div class="alert alert-info">

            <div class="row">

                <div class="col-md-4">

                    <small class="text-muted d-block">
                        Recommended Bidder
                    </small>

                    <strong id="selectedBidder">
                        —
                    </strong>

                </div>


                <div class="col-md-4">

                    <small class="text-muted d-block">
                        Original Amount
                    </small>

                    <strong id="originalAmount">
                        —
                    </strong>

                </div>


                <div class="col-md-4">

                    <small class="text-muted d-block">
                        Currency
                    </small>

                    <strong id="originalCurrency">
                        —
                    </strong>

                </div>

            </div>

        </div>

    </div>


    {{-- Bidder Amount --}}
    <div class="col-md-4">

        <label class="form-label">
            Bidder Amount
            <span class="text-danger">*</span>
        </label>

        <input
            type="number"
            step="0.01"
            min="0"
            name="bidder_amount"
            id="bidder_amount"
            class="form-control @error('bidder_amount') is-invalid @enderror"
            value="{{ old('bidder_amount') }}"
            required
        >

        @error('bidder_amount')

            <div class="invalid-feedback">
                {{ $message }}
            </div>

        @enderror

    </div>


    {{-- Negotiated Amount --}}
    <div class="col-md-4">

        <label class="form-label">
            Negotiated Amount
            <span class="text-danger">*</span>
        </label>

        <input
            type="number"
            step="0.01"
            min="0"
            name="negotiated_amount"
            id="negotiated_amount"
            class="form-control @error('negotiated_amount') is-invalid @enderror"
            value="{{ old('negotiated_amount') }}"
            required
        >

        @error('negotiated_amount')

            <div class="invalid-feedback">
                {{ $message }}
            </div>

        @enderror

    </div>


    {{-- Discount --}}
    <div class="col-md-4">

        <label class="form-label">
            Discount Amount
        </label>

        <input
            type="number"
            step="0.01"
            min="0"
            name="discount_amount"
            class="form-control @error('discount_amount') is-invalid @enderror"
            value="{{ old('discount_amount', 0) }}"
        >

        @error('discount_amount')

            <div class="invalid-feedback">
                {{ $message }}
            </div>

        @enderror

    </div>


    {{-- Bidder Comments --}}
    <div class="col-12">

        <label class="form-label">
            Bidder Comments
        </label>

        <textarea
            name="bidder_comments"
            rows="3"
            class="form-control @error('bidder_comments') is-invalid @enderror"
        >{{ old('bidder_comments') }}</textarea>

        @error('bidder_comments')

            <div class="invalid-feedback">
                {{ $message }}
            </div>

        @enderror

    </div>


    {{-- Evaluator Comments --}}
    <div class="col-12">

        <label class="form-label">
            Evaluator Comments
        </label>

        <textarea
            name="evaluator_comments"
            rows="3"
            class="form-control @error('evaluator_comments') is-invalid @enderror"
        >{{ old('evaluator_comments') }}</textarea>

        @error('evaluator_comments')

            <div class="invalid-feedback">
                {{ $message }}
            </div>

        @enderror

    </div>


    {{-- Summary --}}
    <div class="col-12">

        <label class="form-label">
            Summary
        </label>

        <textarea
            name="summary"
            rows="3"
            class="form-control @error('summary') is-invalid @enderror"
        >{{ old('summary') }}</textarea>

        @error('summary')

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

        const comparison =
            document.getElementById(
                'procurement_bid_comparison_id'
            );


        const bidder =
            document.getElementById(
                'selectedBidder'
            );


        const amount =
            document.getElementById(
                'originalAmount'
            );


        const currency =
            document.getElementById(
                'originalCurrency'
            );


        const bidderAmount =
            document.getElementById(
                'bidder_amount'
            );


        const negotiatedAmount =
            document.getElementById(
                'negotiated_amount'
            );


        function updateComparison()
        {
            const option =
                comparison.options[
                    comparison.selectedIndex
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

                return;
            }


            const selectedBidder =
                option.dataset.bidder
                || '—';


            const selectedAmount =
                option.dataset.amount
                || '0';


            const selectedCurrency =
                option.dataset.currency
                || 'USD';


            bidder.textContent =
                selectedBidder;


            amount.textContent =
                Number(
                    selectedAmount
                ).toLocaleString(
                    undefined,
                    {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }
                );


            currency.textContent =
                selectedCurrency;


            /*
             * Automatically populate Bidder Amount
             * with original evaluated amount.
             */
            if (!bidderAmount.value) {

                bidderAmount.value =
                    selectedAmount;
            }


            /*
             * Automatically populate Negotiated Amount
             * with original amount.
             */
            if (!negotiatedAmount.value) {

                negotiatedAmount.value =
                    selectedAmount;
            }
        }


        comparison.addEventListener(
            'change',
            updateComparison
        );


        updateComparison();

    }
);

</script>