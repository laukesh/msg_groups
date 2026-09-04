@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- =====================================================
        HEADER
    ====================================================== --}}

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">

                Tender:
                <strong>
                    {{ $procurementTender->tender_number }}
                </strong>

            </div>

            <h4 class="mb-1">
                Edit Negotiation Round
            </h4>

            <div class="text-muted">

                {{ $negotiation->negotiation_number }}

                -

                {{ $negotiation->negotiation_title }}

            </div>

        </div>


        <div class="d-flex gap-2">

            {{-- Back to Tender --}}
            <a
                href="{{ route(
                    'admin.procurement.tenders.show',
                    $procurementTender
                ) }}"
                class="btn btn-outline-secondary"
            >
                <i class="ri-arrow-left-line me-1"></i>
                Back to Tender
            </a>


            {{-- Back to Negotiation --}}
            <a
                href="{{ route(
                    'admin.procurement.tenders.negotiations.show',
                    [
                        'procurementTender' =>
                            $procurementTender,

                        'negotiation' =>
                            $negotiation,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                Back
            </a>

        </div>

    </div>


    {{-- =====================================================
        ERRORS
    ====================================================== --}}

    @if($errors->any())

        <div class="alert alert-danger">

            <strong>
                Please correct the following:
            </strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- =====================================================
        FORM
    ====================================================== --}}

    <form
        method="POST"
        action="{{ route(
            'admin.procurement.tenders.negotiations.rounds.update',
            [
                'procurementTender' =>
                    $procurementTender,

                'negotiation' =>
                    $negotiation,

                'item' =>
                    $item,
            ]
        ) }}"
    >

        @csrf

        @method('PUT')


        {{-- =================================================
            ROUND INFORMATION
        ================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Round {{ $item->round_number }}
                </strong>

            </div>


            <div class="card-body">

                <div class="row g-3">


                    {{-- Round Number --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            Round Number
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="Round {{ $item->round_number }}"
                            readonly
                        >

                        <div class="form-text">
                            Round number cannot be changed.
                        </div>

                    </div>


                    {{-- Round Date --}}
                    <div class="col-md-4">

                        <label class="form-label">

                            Round Date

                            <span class="text-danger">
                                *
                            </span>

                        </label>

                        <input
                            type="date"
                            name="round_date"
                            class="form-control @error('round_date') is-invalid @enderror"
                            value="{{ old(
                                'round_date',
                                $item->round_date
                                    ?->format('Y-m-d')
                            ) }}"
                            required
                        >

                        @error('round_date')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Currency --}}
                    <div class="col-md-4">

                        <label class="form-label">

                            Currency

                            <span class="text-danger">
                                *
                            </span>

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
                                            $item->currency ?? 'INR'
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


                    {{-- Bidder Amount --}}
                    <div class="col-md-6">

                        <label class="form-label">

                            Bidder Amount

                            <span class="text-danger">
                                *
                            </span>

                        </label>

                        <input
                            type="number"
                            name="bidder_amount"
                            class="form-control @error('bidder_amount') is-invalid @enderror"
                            value="{{ old(
                                'bidder_amount',
                                $item->bidder_amount
                            ) }}"
                            min="0"
                            step="0.01"
                            required
                        >

                        @error('bidder_amount')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Negotiated Amount --}}
                    <div class="col-md-6">

                        <label class="form-label">

                            Negotiated Amount

                            <span class="text-danger">
                                *
                            </span>

                        </label>

                        <input
                            type="number"
                            name="negotiated_amount"
                            class="form-control @error('negotiated_amount') is-invalid @enderror"
                            value="{{ old(
                                'negotiated_amount',
                                $item->negotiated_amount
                            ) }}"
                            min="0"
                            step="0.01"
                            required
                        >

                        @error('negotiated_amount')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Discount --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            Discount Amount
                        </label>

                        <input
                            type="number"
                            name="discount_amount"
                            class="form-control @error('discount_amount') is-invalid @enderror"
                            value="{{ old(
                                'discount_amount',
                                $item->discount_amount ?? 0
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


                    {{-- Final Amount --}}
                    <div class="col-md-6">

                        <label class="form-label">

                            Final Amount

                            <span class="text-danger">
                                *
                            </span>

                        </label>

                        <input
                            type="number"
                            name="final_amount"
                            class="form-control @error('final_amount') is-invalid @enderror"
                            value="{{ old(
                                'final_amount',
                                $item->final_amount
                            ) }}"
                            min="0"
                            step="0.01"
                            required
                        >

                        @error('final_amount')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Round Status --}}
                    <div class="col-md-6">

                        <label class="form-label">

                            Round Status

                            <span class="text-danger">
                                *
                            </span>

                        </label>

                        <select
                            name="negotiation_status"
                            class="form-select @error('negotiation_status') is-invalid @enderror"
                            required
                        >

                            @foreach([
                                'In Progress',
                                'Agreed',
                                'Rejected',
                            ] as $status)

                                <option
                                    value="{{ $status }}"
                                    @selected(
                                        old(
                                            'negotiation_status',
                                            $item->negotiation_status
                                        ) === $status
                                    )
                                >
                                    {{ $status }}
                                </option>

                            @endforeach

                        </select>

                        @error('negotiation_status')

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
                            rows="4"
                            class="form-control @error('bidder_comments') is-invalid @enderror"
                        >{{ old(
                            'bidder_comments',
                            $item->bidder_comments
                        ) }}</textarea>

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
                            rows="4"
                            class="form-control @error('evaluator_comments') is-invalid @enderror"
                        >{{ old(
                            'evaluator_comments',
                            $item->evaluator_comments
                        ) }}</textarea>

                        @error('evaluator_comments')

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
                        >{{ old(
                            'remarks',
                            $item->remarks
                        ) }}</textarea>

                        @error('remarks')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>

            </div>

        </div>


        {{-- =================================================
            ACTIONS
        ================================================== --}}

        <div class="d-flex justify-content-end gap-2">

            <a
                href="{{ route(
                    'admin.procurement.tenders.negotiations.show',
                    [
                        'procurementTender' =>
                            $procurementTender,

                        'negotiation' =>
                            $negotiation,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                Cancel
            </a>


            <button
                type="submit"
                class="btn btn-primary"
            >

                <i class="ri-save-line me-1"></i>

                Update Round

            </button>

        </div>

    </form>

</div>

@endsection