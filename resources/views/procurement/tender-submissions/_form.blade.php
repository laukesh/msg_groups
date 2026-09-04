@php
    $submission = $submission ?? null;
@endphp

<div class="row g-3">

    {{-- =========================================================
        BIDDER
    ========================================================== --}}

    <div class="col-md-6">

        <label class="form-label">
            Bidder
        </label>

        <input
            type="text"
            class="form-control"
            value="{{
                $submission?->tenderBidder?->bidder?->company_name
                ?? '—'
            }}"
            readonly
        >

        <div class="form-text">
            Bidder is linked to this Tender Bidder record.
        </div>

    </div>


    {{-- =========================================================
        SUBMISSION NUMBER
    ========================================================== --}}

    <div class="col-md-6">

        <label class="form-label">
            Submission Number
        </label>

        @if($submission)

            <input
                type="text"
                class="form-control"
                value="{{ $submission->submission_number }}"
                readonly
            >

            <div class="form-text">
                Submission number is system generated and cannot
                be changed.
            </div>

        @else

            <input
                type="text"
                class="form-control"
                value="Auto-generated"
                readonly
            >

            <div class="form-text">
                Submission number will be generated automatically
                when the submission is saved.
            </div>

        @endif

    </div>


    {{-- =========================================================
        SUBMISSION DATE
    ========================================================== --}}

    <div class="col-md-4">

        <label class="form-label">
            Submission Date
        </label>

        <input
            type="datetime-local"
            name="submission_date"
            class="form-control @error('submission_date') is-invalid @enderror"
            value="{{ old(
                'submission_date',
                $submission?->submission_date
                    ? $submission->submission_date->format('Y-m-d\TH:i')
                    : null
            ) }}"
        >

        @error('submission_date')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- =========================================================
        BID VALIDITY
    ========================================================== --}}

    <div class="col-md-4">

        <label class="form-label">
            Bid Validity (Days)
        </label>

        <input
            type="number"
            name="bid_validity_days"
            class="form-control @error('bid_validity_days') is-invalid @enderror"
            value="{{ old(
                'bid_validity_days',
                $submission?->bid_validity_days
            ) }}"
            min="1"
            placeholder="e.g. 90"
        >

        @error('bid_validity_days')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- =========================================================
        BID VALID UNTIL
    ========================================================== --}}

    <div class="col-md-4">

        <label class="form-label">
            Bid Valid Until
        </label>

        <input
            type="date"
            name="bid_valid_until"
            class="form-control @error('bid_valid_until') is-invalid @enderror"
            value="{{ old(
                'bid_valid_until',
                $submission?->bid_valid_until?->format('Y-m-d')
            ) }}"
        >

        @error('bid_valid_until')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- =========================================================
        QUOTED AMOUNT
    ========================================================== --}}

    <div class="col-md-6">

        <label class="form-label">
            Quoted Amount
            <span class="text-danger">*</span>
        </label>

        <input
            type="number"
            name="quoted_amount"
            class="form-control @error('quoted_amount') is-invalid @enderror"
            value="{{ old(
                'quoted_amount',
                $submission?->quoted_amount ?? '0.00'
            ) }}"
            min="0"
            step="0.01"
            required
        >

        @error('quoted_amount')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- =========================================================
        CURRENCY
    ========================================================== --}}

    <div class="col-md-6">

        <label class="form-label">
            Currency
            <span class="text-danger">*</span>
        </label>

        <select
            name="currency"
            class="form-select @error('currency') is-invalid @enderror"
            required
        >

            @foreach([
                'INR',
                'USD',
                'EUR',
                'GBP',
            ] as $currency)

                <option
                    value="{{ $currency }}"
                    @selected(
                        old(
                            'currency',
                            $submission?->currency ?? 'INR'
                        ) === $currency
                    )
                >
                    {{ $currency }}
                </option>

            @endforeach

        </select>

        @error('currency')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- =========================================================
        TECHNICAL SUBMISSION
    ========================================================== --}}

    <div class="col-12">

        <label class="form-label">
            Technical Submission
        </label>

        <textarea
            name="technical_submission"
            rows="5"
            class="form-control @error('technical_submission') is-invalid @enderror"
            placeholder="Enter technical submission details"
        >{{ old(
            'technical_submission',
            $submission?->technical_submission
        ) }}</textarea>

        @error('technical_submission')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- =========================================================
        COMMERCIAL SUBMISSION
    ========================================================== --}}

    <div class="col-12">

        <label class="form-label">
            Commercial Submission
        </label>

        <textarea
            name="commercial_submission"
            rows="5"
            class="form-control @error('commercial_submission') is-invalid @enderror"
            placeholder="Enter commercial submission details"
        >{{ old(
            'commercial_submission',
            $submission?->commercial_submission
        ) }}</textarea>

        @error('commercial_submission')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- =========================================================
        COMPLIANCE DECLARATION
    ========================================================== --}}

    <div class="col-12">

        <label class="form-label">
            Compliance Declaration
        </label>

        <textarea
            name="compliance_declaration"
            rows="4"
            class="form-control @error('compliance_declaration') is-invalid @enderror"
            placeholder="Enter bidder compliance declaration"
        >{{ old(
            'compliance_declaration',
            $submission?->compliance_declaration
        ) }}</textarea>

        @error('compliance_declaration')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- =========================================================
        SUBMISSION STATUS
    ========================================================== --}}

    <div class="col-md-6">

        <label class="form-label">
            Submission Status
            <span class="text-danger">*</span>
        </label>

        <select
            name="submission_status"
            class="form-select @error('submission_status') is-invalid @enderror"
            required
        >

            @foreach([
                'Draft',
                'Submitted',
                'Under Review',
                'Accepted',
                'Rejected',
                'Withdrawn',
            ] as $status)

                <option
                    value="{{ $status }}"
                    @selected(
                        old(
                            'submission_status',
                            $submission?->submission_status ?? 'Draft'
                        ) === $status
                    )
                >
                    {{ $status }}
                </option>

            @endforeach

        </select>

        @error('submission_status')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- =========================================================
        COMPLETE
    ========================================================== --}}

    <div class="col-md-6">

        <label class="form-label d-block">
            Submission
        </label>

        <div class="form-check form-switch mt-2">

            <input
                type="hidden"
                name="is_complete"
                value="0"
            >

            <input
                class="form-check-input"
                type="checkbox"
                name="is_complete"
                value="1"
                id="is_complete"
                @checked(
                    old(
                        'is_complete',
                        $submission?->is_complete ?? false
                    )
                )
            >

            <label
                class="form-check-label"
                for="is_complete"
            >
                Submission is complete
            </label>

        </div>

    </div>


    {{-- =========================================================
        REMARKS
    ========================================================== --}}

    <div class="col-12">

        <label class="form-label">
            Remarks
        </label>

        <textarea
            name="remarks"
            rows="3"
            class="form-control @error('remarks') is-invalid @enderror"
            placeholder="Additional remarks"
        >{{ old(
            'remarks',
            $submission?->remarks
        ) }}</textarea>

        @error('remarks')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>

</div>