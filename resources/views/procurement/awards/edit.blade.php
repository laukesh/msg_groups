@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small mb-1">
                Tender:
                <strong>
                    {{ $procurementTender->tender_number }}
                </strong>
            </div>

            <h4 class="mb-1">
                Edit Award
            </h4>

            <div class="text-muted">
                {{ $procurementTender->tender_title }}
            </div>

        </div>

        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.procurement.tenders.awards.show',
                    [
                        'procurementTender' => $procurementTender,
                        'award' => $award,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                Back
            </a>

        </div>

    </div>


    {{-- Validation Errors --}}
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


    {{-- Status restriction --}}
    @if(!in_array($award->status, ['Draft', 'Under Review']))

        <div class="alert alert-warning">

            This Award cannot be edited because its current status is
            <strong>{{ $award->status }}</strong>.

        </div>

    @else

        <form
            method="POST"
            action="{{ route(
                'admin.procurement.tenders.awards.update',
                [
                    'procurementTender' => $procurementTender,
                    'award' => $award,
                ]
            ) }}"
        >

            @csrf
            @method('PUT')


            <div class="card">

                <div class="card-header">

                    <strong>
                        Award Details
                    </strong>

                </div>


                <div class="card-body">

                    <div class="row g-3">


                        {{-- Award Number --}}
                        <div class="col-md-6">

                            <label class="form-label">
                                Award Number
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                value="{{ $award->award_number }}"
                                readonly
                            >

                            <div class="form-text">
                                Award number cannot be changed.
                            </div>

                        </div>


                        {{-- Negotiation --}}
                        <div class="col-md-6">

                            <label class="form-label">
                                Approved Negotiation
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                value="{{ $award->negotiation?->negotiation_number ?? '—' }}"
                                readonly
                            >

                            <div class="form-text">
                                The Award remains linked to its original approved negotiation.
                            </div>

                        </div>


                        {{-- Bidder --}}
                        <div class="col-md-6">

                            <label class="form-label">
                                Awarded Bidder
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                value="{{ $award->bidder_name }}"
                                readonly
                            >

                        </div>


                        {{-- Awarded Amount --}}
                        <div class="col-md-6">

                            <label class="form-label">
                                Awarded Amount
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                value="{{ number_format((float) $award->awarded_amount, 2) }} {{ $award->currency }}"
                                readonly
                            >

                            <div class="form-text">
                                Amount is taken from the approved negotiation.
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
                                value="{{ old('award_title', $award->award_title) }}"
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
                                    $award->award_date?->format('Y-m-d')
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
                                class="form-select @error('award_type') is-invalid @enderror"
                            >

                                <option
                                    value="Letter of Award"
                                    @selected(
                                        old(
                                            'award_type',
                                            $award->award_type
                                        ) === 'Letter of Award'
                                    )
                                >
                                    Letter of Award
                                </option>

                                <option
                                    value="Work Order"
                                    @selected(
                                        old(
                                            'award_type',
                                            $award->award_type
                                        ) === 'Work Order'
                                    )
                                >
                                    Work Order
                                </option>

                                <option
                                    value="Purchase Order"
                                    @selected(
                                        old(
                                            'award_type',
                                            $award->award_type
                                        ) === 'Purchase Order'
                                    )
                                >
                                    Purchase Order
                                </option>

                            </select>

                            @error('award_type')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

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
                                            $award->contract_required
                                        ) == 1
                                    )
                                >
                                    Yes
                                </option>

                                <option
                                    value="0"
                                    @selected(
                                        old(
                                            'contract_required',
                                            $award->contract_required
                                        ) == 0
                                    )
                                >
                                    No
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
                                value="{{ old('loa_number', $award->loa_number) }}"
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
                                value="{{ old(
                                    'loa_date',
                                    $award->loa_date?->format('Y-m-d')
                                ) }}"
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
                                value="{{ old(
                                    'acceptance_deadline',
                                    $award->acceptance_deadline?->format('Y-m-d')
                                ) }}"
                            >

                            @error('acceptance_deadline')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Description --}}
                        <div class="col-12">

                            <label class="form-label">
                                Description
                            </label>

                            <textarea
                                name="description"
                                rows="4"
                                class="form-control @error('description') is-invalid @enderror"
                                placeholder="Award description"
                            >{{ old('description', $award->description) }}</textarea>

                            @error('description')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Terms & Conditions --}}
                        <div class="col-12">

                            <label class="form-label">
                                Terms & Conditions
                            </label>

                            <textarea
                                name="terms_and_conditions"
                                rows="5"
                                class="form-control @error('terms_and_conditions') is-invalid @enderror"
                                placeholder="Enter award terms and conditions"
                            >{{ old('terms_and_conditions', $award->terms_and_conditions) }}</textarea>

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
                                rows="4"
                                class="form-control @error('remarks') is-invalid @enderror"
                                placeholder="Additional remarks"
                            >{{ old('remarks', $award->remarks) }}</textarea>

                            @error('remarks')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                    </div>

                </div>

            </div>


            {{-- Actions --}}
            <div class="d-flex justify-content-end gap-2 mt-4">

                <a
                    href="{{ route(
                        'admin.procurement.tenders.awards.show',
                        [
                            'procurementTender' => $procurementTender,
                            'award' => $award,
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
                    Save Changes
                </button>

            </div>

        </form>

    @endif

</div>

@endsection