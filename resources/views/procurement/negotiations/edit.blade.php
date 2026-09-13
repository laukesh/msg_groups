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
                Edit Negotiation
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
            'admin.procurement.tenders.negotiations.update',
            [
                'procurementTender' =>
                    $procurementTender,

                'negotiation' =>
                    $negotiation,
            ]
        ) }}"
    >

        @csrf

        @method('PUT')


        {{-- =================================================
            NEGOTIATION INFORMATION
        ================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Negotiation Information
                </strong>

            </div>


            <div class="card-body">

                <div class="row g-3">


                    {{-- Negotiation Number --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            Negotiation Number
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="{{ $negotiation->negotiation_number }}"
                            readonly
                        >

                    </div>


                    {{-- Tender --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            Tender
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="{{ $procurementTender->tender_number }}"
                            readonly
                        >

                    </div>


                    {{-- Bidder --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            Bidder
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="{{ $negotiation->bidder_name }}"
                            readonly
                        >

                    </div>


                    {{-- Negotiation Title --}}
                    <div class="col-md-8">

                        <label class="form-label">

                            Negotiation Title

                            <span class="text-danger">
                                *
                            </span>

                        </label>

                        <input
                            type="text"
                            name="negotiation_title"
                            class="form-control @error('negotiation_title') is-invalid @enderror"
                            value="{{ old(
                                'negotiation_title',
                                $negotiation->negotiation_title
                            ) }}"
                            maxlength="255"
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
                                $negotiation->negotiation_date
                                    ?->format('Y-m-d')
                            ) }}"
                        >

                        @error('negotiation_date')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Negotiation Type --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            Negotiation Type
                        </label>

                        <input
                            type="text"
                            name="negotiation_type"
                            class="form-control @error('negotiation_type') is-invalid @enderror"
                            value="{{ old(
                                'negotiation_type',
                                $negotiation->negotiation_type
                            ) }}"
                            maxlength="100"
                            placeholder="e.g. Price Negotiation"
                        >

                        @error('negotiation_type')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- =================================================
                        STATUS
                    ================================================== --}}

                    <div class="col-md-4">

                        <label class="form-label">

                            Status

                            <span class="text-danger">
                                *
                            </span>

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
                                'Rejected',
                            ] as $status)

                                <option
                                    value="{{ $status }}"
                                    @selected(
                                        old(
                                            'status',
                                            $negotiation->status
                                        ) === $status
                                    )
                                >
                                    {{ $status }}
                                </option>

                            @endforeach

                        </select>


                        <div class="form-text">

                            <strong>
                                Approved
                            </strong>
                            is done using the
                            <strong>
                                Approve Negotiation
                            </strong>
                            button.

                        </div>


                        @error('status')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- =================================================
                        OUTCOME
                    ================================================== --}}

                    <div class="col-md-4">

                        <label class="form-label">

                            Outcome

                            <span class="text-danger">
                                *
                            </span>

                        </label>


                        <select
                            name="outcome"
                            class="form-select @error('outcome') is-invalid @enderror"
                            required
                        >

                            @foreach([
                                'Open',
                                'Agreed',
                                'Not Agreed',
                                'Rejected',
                            ] as $outcome)

                                <option
                                    value="{{ $outcome }}"
                                    @selected(
                                        old(
                                            'outcome',
                                            $negotiation->outcome
                                                ?? 'Open'
                                        ) === $outcome
                                    )
                                >
                                    {{ $outcome }}
                                </option>

                            @endforeach

                        </select>


                        <div class="form-text">

                            <strong>
                                Agreed
                            </strong>
                            requires the latest negotiation
                            round to be Agreed.

                        </div>


                        @error('outcome')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Original Amount --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            Original Amount
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="{{
                                number_format(
                                    (float)
                                    $negotiation->original_amount,
                                    2
                                )
                            }}
                            {{ $negotiation->currency }}"
                            readonly
                        >

                    </div>


                    {{-- Negotiated Amount --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            Negotiated Amount
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="{{
                                number_format(
                                    (float)
                                    $negotiation->negotiated_amount,
                                    2
                                )
                            }}
                            {{ $negotiation->currency }}"
                            readonly
                        >

                    </div>


                    {{-- Final Amount --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            Final Amount
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="{{
                                number_format(
                                    (float)
                                    $negotiation->final_amount,
                                    2
                                )
                            }}
                            {{ $negotiation->currency }}"
                            readonly
                        >

                    </div>


                    {{-- Summary --}}
                    <div class="col-12">

                        <label class="form-label">
                            Summary
                        </label>

                        <textarea
                            name="summary"
                            rows="4"
                            class="form-control @error('summary') is-invalid @enderror"
                            placeholder="Enter negotiation summary"
                        >{{ old(
                            'summary',
                            $negotiation->summary
                        ) }}</textarea>

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
                            rows="4"
                            class="form-control @error('remarks') is-invalid @enderror"
                            placeholder="Enter remarks"
                        >{{ old(
                            'remarks',
                            $negotiation->remarks
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
            NEGOTIATION ROUNDS
        ================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <div class="d-flex justify-content-between align-items-center">

                    <strong>
                        Negotiation Rounds
                    </strong>

                    <span class="badge bg-primary">

                        {{ $negotiation->items->count() }}

                        Rounds

                    </span>

                </div>

            </div>


            <div class="card-body p-0">

                @if($negotiation->items->isNotEmpty())

                    <div class="table-responsive">

                        <table class="table table-hover align-middle mb-0">

                            <thead class="table-light">

                                <tr>

                                    <th>
                                        Round
                                    </th>

                                    <th>
                                        Date
                                    </th>

                                    <th>
                                        Bidder Amount
                                    </th>

                                    <th>
                                        Negotiated Amount
                                    </th>

                                    <th>
                                        Final Amount
                                    </th>

                                    <th>
                                        Status
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @foreach(
                                    $negotiation->items
                                        ->sortBy('round_number')
                                    as $item
                                )

                                    <tr>

                                        <td>

                                            <span class="badge bg-dark">

                                                Round
                                                {{ $item->round_number }}

                                            </span>

                                        </td>


                                        <td>

                                            {{
                                                $item
                                                    ->round_date
                                                    ?->format('d-m-Y')
                                                ?? '—'
                                            }}

                                        </td>


                                        <td>

                                            {{
                                                number_format(
                                                    (float)
                                                    $item->bidder_amount,
                                                    2
                                                )
                                            }}

                                            {{ $item->currency }}

                                        </td>


                                        <td>

                                            {{
                                                number_format(
                                                    (float)
                                                    $item->negotiated_amount,
                                                    2
                                                )
                                            }}

                                            {{ $item->currency }}

                                        </td>


                                        <td>

                                            <strong>

                                                {{
                                                    number_format(
                                                        (float)
                                                        $item->final_amount,
                                                        2
                                                    )
                                                }}

                                            </strong>

                                            {{ $item->currency }}

                                        </td>


                                        <td>

                                            @php

                                                $roundClass = match(
                                                    $item->negotiation_status
                                                ) {

                                                    'Agreed' =>
                                                        'bg-success',

                                                    'Rejected' =>
                                                        'bg-danger',

                                                    'In Progress' =>
                                                        'bg-warning text-dark',

                                                    default =>
                                                        'bg-secondary',

                                                };

                                            @endphp


                                            <span
                                                class="badge {{ $roundClass }}"
                                            >
                                                {{ $item->negotiation_status }}
                                            </span>

                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                @else

                    <div class="text-center py-5 text-muted">

                        No negotiation rounds recorded.

                    </div>

                @endif

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

                Update Negotiation

            </button>

        </div>

    </form>

</div>

@endsection