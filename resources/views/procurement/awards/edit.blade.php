@extends('layouts.app')

@section('content')

<div class="container-fluid">

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
                {{ $award->award_number }}
                —
                {{ $award->award_title }}
            </div>

        </div>


        <div>

            <a
                href="{{ route(
                    'admin.projects.procurement.tenders.awards.show',
                    [
                        'project' => $project,
                        'procurementTender' => $procurementTender,
                        'award' => $award,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                <i class="ri-arrow-left-line me-1"></i>
                Back
            </a>

        </div>

    </div>


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


    @if(!in_array($award->status, ['Draft', 'Under Review']))

        <div class="alert alert-warning">

            This Award cannot be edited because its current status is
            <strong>{{ $award->status }}</strong>.

        </div>

    @else

        <form
            method="POST"
            action="{{ route(
                'admin.projects.procurement.tenders.awards.update',
                [
                    'project' => $project,
                    'procurementTender' => $procurementTender,
                    'award' => $award,
                ]
            ) }}"
        >

            @csrf
            @method('PUT')


            {{-- Context --}}
            <div class="card mb-4">

                <div class="card-header">
                    <strong>
                        Project & Tender Context
                    </strong>
                </div>

                <div class="card-body">

                    <div class="row g-4">

                        <div class="col-md-4">

                            <div class="text-muted small">
                                Project
                            </div>

                            <div class="fw-semibold">
                                {{ $project->project_name }}
                            </div>

                        </div>

                        <div class="col-md-2">

                            <div class="text-muted small">
                                Project Code
                            </div>

                            <div>
                                {{ $project->project_code ?? '—' }}
                            </div>

                        </div>

                        <div class="col-md-3">

                            <div class="text-muted small">
                                Tender
                            </div>

                            <div class="fw-semibold">
                                {{ $procurementTender->tender_number }}
                            </div>

                        </div>

                        <div class="col-md-3">

                            <div class="text-muted small">
                                Status
                            </div>

                            <span class="badge bg-secondary">
                                {{ $award->status }}
                            </span>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Award --}}
            <div class="card">

                <div class="card-header">
                    <strong>
                        Award Details
                    </strong>
                </div>

                <div class="card-body">

                    <div class="row g-3">

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

                        </div>


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

                        </div>


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

                        </div>


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
                                required
                            >

                            @error('award_title')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        <div class="col-md-4">

                            <label class="form-label">
                                Award Date
                            </label>

                            <input
                                type="date"
                                name="award_date"
                                class="form-control"
                                value="{{ old(
                                    'award_date',
                                    $award->award_date?->format('Y-m-d')
                                ) }}"
                            >

                        </div>


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

                        </div>


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


                        <div class="col-md-6">

                            <label class="form-label">
                                LOA Number
                            </label>

                            <input
                                type="text"
                                name="loa_number"
                                class="form-control"
                                value="{{ old(
                                    'loa_number',
                                    $award->loa_number
                                ) }}"
                            >

                        </div>


                        <div class="col-md-6">

                            <label class="form-label">
                                LOA Date
                            </label>

                            <input
                                type="date"
                                name="loa_date"
                                class="form-control"
                                value="{{ old(
                                    'loa_date',
                                    $award->loa_date?->format('Y-m-d')
                                ) }}"
                            >

                        </div>


                        <div class="col-md-6">

                            <label class="form-label">
                                Acceptance Deadline
                            </label>

                            <input
                                type="date"
                                name="acceptance_deadline"
                                class="form-control"
                                value="{{ old(
                                    'acceptance_deadline',
                                    $award->acceptance_deadline?->format('Y-m-d')
                                ) }}"
                            >

                        </div>


                        <div class="col-12">

                            <label class="form-label">
                                Description
                            </label>

                            <textarea
                                name="description"
                                rows="4"
                                class="form-control"
                            >{{ old('description', $award->description) }}</textarea>

                        </div>


                        <div class="col-12">

                            <label class="form-label">
                                Terms & Conditions
                            </label>

                            <textarea
                                name="terms_and_conditions"
                                rows="5"
                                class="form-control"
                            >{{ old(
                                'terms_and_conditions',
                                $award->terms_and_conditions
                            ) }}</textarea>

                        </div>


                        <div class="col-12">

                            <label class="form-label">
                                Remarks
                            </label>

                            <textarea
                                name="remarks"
                                rows="4"
                                class="form-control"
                            >{{ old('remarks', $award->remarks) }}</textarea>

                        </div>

                    </div>

                </div>

            </div>


            <div class="d-flex justify-content-end gap-2 mt-4">

                <a
                    href="{{ route(
                        'admin.projects.procurement.tenders.awards.show',
                        [
                            'project' => $project,
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