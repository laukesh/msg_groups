@php

    $evaluation = $evaluation ?? null;

    $selectedSubmission =
        $selectedSubmission ?? null;

@endphp


<div class="row g-3">


    {{-- ============================================================= --}}
    {{-- Evaluation Number --}}
    {{-- ============================================================= --}}

    <div class="col-md-6">

        <label class="form-label">
            Evaluation Number
        </label>

        @if($evaluation)

            <input
                type="text"
                class="form-control"
                value="{{ $evaluation->evaluation_number }}"
                readonly
            >

            <div class="form-text">
                Automatically generated evaluation number.
            </div>

        @else

            <input
                type="text"
                class="form-control"
                value="Will be generated automatically"
                readonly
            >

            <div class="form-text">
                Evaluation number will be generated when saved.
            </div>

        @endif

    </div>


    {{-- ============================================================= --}}
    {{-- Evaluation Date --}}
    {{-- ============================================================= --}}

    <div class="col-md-6">

        <label class="form-label">
            Evaluation Date
        </label>

        <input
            type="date"
            name="evaluation_date"
            class="form-control @error('evaluation_date') is-invalid @enderror"
            value="{{ old(
                'evaluation_date',
                $evaluation?->evaluation_date?->format('Y-m-d')
            ) }}"
        >

        @error('evaluation_date')

            <div class="invalid-feedback">
                {{ $message }}
            </div>

        @enderror

    </div>


    {{-- ============================================================= --}}
    {{-- Evaluator --}}
    {{-- ============================================================= --}}

    <div class="col-md-6">

        <label class="form-label">
            Evaluator
        </label>

        <input
            type="text"
            name="evaluator_name"
            class="form-control @error('evaluator_name') is-invalid @enderror"
            value="{{ old(
                'evaluator_name',
                $evaluation?->evaluator_name
                    ?? auth()->user()?->name
            ) }}"
        >

        @error('evaluator_name')

            <div class="invalid-feedback">
                {{ $message }}
            </div>

        @enderror

    </div>


    {{-- ============================================================= --}}
    {{-- Currency --}}
    {{-- ============================================================= --}}

    <div class="col-md-6">

        <label class="form-label">
            Currency
        </label>

        <input
            type="text"
            id="currency"
            class="form-control"
            value="{{ old(
                'currency',
                $selectedSubmission?->currency
                    ?? $evaluation?->currency
                    ?? 'USD'
            ) }}"
            readonly
        >

        <div class="form-text">
            Currency is taken automatically from the Tender Submission.
        </div>

    </div>


    {{-- ============================================================= --}}
    {{-- Original Quoted Amount --}}
    {{-- ============================================================= --}}

    <div class="col-md-4">

        <label class="form-label">
            Original Quoted Amount
        </label>

        <input
            type="number"
            id="original_quoted_amount"
            class="form-control"
            value="{{ old(
                'original_quoted_amount',
                $selectedSubmission?->quoted_amount
                    ?? $evaluation?->quoted_amount
                    ?? '0.00'
            ) }}"
            readonly
        >

        <div class="form-text">
            Taken automatically from the Tender Submission.
        </div>

    </div>


    {{-- ============================================================= --}}
    {{-- Evaluated Amount --}}
    {{-- ============================================================= --}}

    <div class="col-md-4">

        <label class="form-label">
            Evaluated Amount
            <span class="text-danger">*</span>
        </label>

        <input
            type="number"
            name="evaluated_amount"
            class="form-control @error('evaluated_amount') is-invalid @enderror"
            value="{{ old(
                'evaluated_amount',
                $evaluation?->evaluated_amount ?? '0.00'
            ) }}"
            min="0"
            step="0.01"
            required
        >

        @error('evaluated_amount')

            <div class="invalid-feedback">
                {{ $message }}
            </div>

        @enderror

    </div>


    {{-- ============================================================= --}}
    {{-- Tax Amount --}}
    {{-- ============================================================= --}}

    <div class="col-md-4">

        <label class="form-label">
            Tax Amount
        </label>

        <input
            type="number"
            name="tax_amount"
            class="form-control @error('tax_amount') is-invalid @enderror"
            value="{{ old(
                'tax_amount',
                $evaluation?->tax_amount ?? '0.00'
            ) }}"
            min="0"
            step="0.01"
        >

        @error('tax_amount')

            <div class="invalid-feedback">
                {{ $message }}
            </div>

        @enderror

    </div>


    {{-- ============================================================= --}}
    {{-- Discount Amount --}}
    {{-- ============================================================= --}}

    <div class="col-md-4">

        <label class="form-label">
            Discount Amount
        </label>

        <input
            type="number"
            name="discount_amount"
            class="form-control @error('discount_amount') is-invalid @enderror"
            value="{{ old(
                'discount_amount',
                $evaluation?->discount_amount ?? '0.00'
            ) }}"
            min="0"
            step="0.01"
        >

        @error('discount_amount')

            <div class="invalid-feedback">
                {{ $message }}
            </div>

        @enderror

    </div>


    {{-- ============================================================= --}}
    {{-- Final Evaluated Amount --}}
    {{-- ============================================================= --}}

    <div class="col-md-4">

        <label class="form-label">
            Final Evaluated Amount
            <span class="text-danger">*</span>
        </label>

        <input
            type="number"
            name="final_evaluated_amount"
            class="form-control @error('final_evaluated_amount') is-invalid @enderror"
            value="{{ old(
                'final_evaluated_amount',
                $evaluation?->final_evaluated_amount ?? '0.00'
            ) }}"
            min="0"
            step="0.01"
            required
        >

        @error('final_evaluated_amount')

            <div class="invalid-feedback">
                {{ $message }}
            </div>

        @enderror

    </div>


    {{-- ============================================================= --}}
    {{-- Price Score --}}
    {{-- ============================================================= --}}

    <div class="col-md-4">

        <label class="form-label">
            Price Score
            <span class="text-danger">*</span>
        </label>

        <input
            type="number"
            name="price_score"
            class="form-control @error('price_score') is-invalid @enderror"
            value="{{ old(
                'price_score',
                $evaluation?->price_score ?? '0.00'
            ) }}"
            min="0"
            step="0.01"
            required
        >

        @error('price_score')

            <div class="invalid-feedback">
                {{ $message }}
            </div>

        @enderror

    </div>


    {{-- ============================================================= --}}
    {{-- Maximum Price Score --}}
    {{-- ============================================================= --}}

    <div class="col-md-4">

        <label class="form-label">
            Maximum Price Score
            <span class="text-danger">*</span>
        </label>

        <input
            type="number"
            name="maximum_price_score"
            class="form-control @error('maximum_price_score') is-invalid @enderror"
            value="{{ old(
                'maximum_price_score',
                $evaluation?->maximum_price_score ?? '100.00'
            ) }}"
            min="0.01"
            step="0.01"
            required
        >

        @error('maximum_price_score')

            <div class="invalid-feedback">
                {{ $message }}
            </div>

        @enderror

    </div>


    {{-- ============================================================= --}}
    {{-- Commercial Compliance --}}
    {{-- ============================================================= --}}

    <div class="col-md-4">

        <label class="form-label">
            Commercial Compliance
            <span class="text-danger">*</span>
        </label>

        <select
            name="commercial_compliance"
            class="form-select @error('commercial_compliance') is-invalid @enderror"
            required
        >

            @foreach([
                'Pending',
                'Compliant',
                'Partially Compliant',
                'Non-Compliant',
            ] as $compliance)

                <option
                    value="{{ $compliance }}"
                    @selected(
                        old(
                            'commercial_compliance',
                            $evaluation?->commercial_compliance
                                ?? 'Pending'
                        ) === $compliance
                    )
                >
                    {{ $compliance }}
                </option>

            @endforeach

        </select>

        @error('commercial_compliance')

            <div class="invalid-feedback">
                {{ $message }}
            </div>

        @enderror

    </div>


    {{-- ============================================================= --}}
    {{-- Status --}}
    {{-- ============================================================= --}}

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
                'Under Evaluation',
                'Completed',
                'Approved',
                'Rejected',
            ] as $status)

                <option
                    value="{{ $status }}"
                    @selected(
                        old(
                            'status',
                            $evaluation?->status ?? 'Draft'
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


    {{-- ============================================================= --}}
    {{-- Result --}}
    {{-- ============================================================= --}}

    <div class="col-md-6">

        <label class="form-label">
            Result
        </label>

        <input
            type="text"
            class="form-control"
            value="{{ $evaluation?->result ?? 'Will be calculated automatically' }}"
            readonly
        >

        <div class="form-text">
            Result is calculated automatically based on compliance
            and evaluation status.
        </div>

    </div>


    {{-- ============================================================= --}}
    {{-- Evaluation Summary --}}
    {{-- ============================================================= --}}

    <div class="col-12">

        <label class="form-label">
            Evaluation Summary
        </label>

        <textarea
            name="evaluation_summary"
            rows="4"
            class="form-control @error('evaluation_summary') is-invalid @enderror"
            placeholder="Enter commercial evaluation summary"
        >{{ old(
            'evaluation_summary',
            $evaluation?->evaluation_summary
        ) }}</textarea>

        @error('evaluation_summary')

            <div class="invalid-feedback">
                {{ $message }}
            </div>

        @enderror

    </div>


    {{-- ============================================================= --}}
    {{-- Strengths --}}
    {{-- ============================================================= --}}

    <div class="col-md-6">

        <label class="form-label">
            Strengths
        </label>

        <textarea
            name="strengths"
            rows="4"
            class="form-control @error('strengths') is-invalid @enderror"
            placeholder="Enter commercial strengths"
        >{{ old(
            'strengths',
            $evaluation?->strengths
        ) }}</textarea>

        @error('strengths')

            <div class="invalid-feedback">
                {{ $message }}
            </div>

        @enderror

    </div>


    {{-- ============================================================= --}}
    {{-- Weaknesses --}}
    {{-- ============================================================= --}}

    <div class="col-md-6">

        <label class="form-label">
            Weaknesses
        </label>

        <textarea
            name="weaknesses"
            rows="4"
            class="form-control @error('weaknesses') is-invalid @enderror"
            placeholder="Enter commercial weaknesses"
        >{{ old(
            'weaknesses',
            $evaluation?->weaknesses
        ) }}</textarea>

        @error('weaknesses')

            <div class="invalid-feedback">
                {{ $message }}
            </div>

        @enderror

    </div>


    {{-- ============================================================= --}}
    {{-- Remarks --}}
    {{-- ============================================================= --}}

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
            $evaluation?->remarks
        ) }}</textarea>

        @error('remarks')

            <div class="invalid-feedback">
                {{ $message }}
            </div>

        @enderror

    </div>

</div>