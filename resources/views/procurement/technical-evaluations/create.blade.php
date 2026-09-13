@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                Add Technical Evaluation
            </h4>

            <div class="text-muted">

                Tender:
                <strong>
                    {{ $procurementTender->tender_number }}
                </strong>

                -

                {{ $procurementTender->tender_title }}

            </div>
        </div>


        <div>

            <a
                href="{{ route(
                    'admin.procurement.tenders.technical-evaluations.index',
                    $procurementTender
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
                Please correct the following errors:
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


    <form
        method="POST"
        action="{{ route(
            'admin.procurement.tenders.technical-evaluations.store',
            $procurementTender
        ) }}"
    >

        @csrf


        <div class="card">

            <div class="card-header">

                <strong>
                    Technical Evaluation Details
                </strong>

            </div>


            <div class="card-body">


                {{-- Submission Selection --}}
                <div class="row mb-4">

                    <div class="col-md-12">

                        <label class="form-label">

                            Tender Submission

                            <span class="text-danger">
                                *
                            </span>

                        </label>


                        <select
                            name="procurement_tender_submission_id"
                            class="form-select @error('procurement_tender_submission_id') is-invalid @enderror"
                            required
                        >

                            <option value="">
                                -- Select Tender Submission --
                            </option>


                            @foreach(
                                $availableSubmissions
                                as $submission
                            )

                                <option
                                    value="{{ $submission->id }}"
                                    @selected(
                                        old(
                                            'procurement_tender_submission_id'
                                        ) == $submission->id
                                    )
                                >

                                    {{ $submission->submission_number }}

                                    -

                                    {{
                                        $submission
                                            ->tenderBidder
                                            ->bidder
                                            ->company_name
                                    }}

                                    -

                                    {{
                                        number_format(
                                            $submission->quoted_amount,
                                            2
                                        )
                                    }}

                                    {{ $submission->currency }}

                                </option>

                            @endforeach

                        </select>


                        @error(
                            'procurement_tender_submission_id'
                        )

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror


                        @if($availableSubmissions->isEmpty())

                            <div class="form-text text-danger mt-2">

                                No eligible Tender Submissions are
                                available for technical evaluation.

                            </div>

                        @else

                            <div class="form-text mt-2">

                                Only submitted Tender Submissions
                                which have not yet been technically
                                evaluated are available.

                            </div>

                        @endif

                    </div>

                </div>


                {{-- Evaluation Form --}}
                @include(
                    'procurement.technical-evaluations._form',
                    [
                        'evaluation' => null,
                    ]
                )

            </div>


            {{-- Footer --}}
            <div class="card-footer">

                <div class="d-flex justify-content-end gap-2">

                    <a
                        href="{{ route(
                            'admin.procurement.tenders.technical-evaluations.index',
                            $procurementTender
                        ) }}"
                        class="btn btn-outline-secondary"
                    >
                        Cancel
                    </a>


                    <button
                        type="submit"
                        class="btn btn-primary"
                        @disabled(
                            $availableSubmissions->isEmpty()
                        )
                    >

                        Save Technical Evaluation

                    </button>

                </div>

            </div>

        </div>

    </form>

</div>

@endsection