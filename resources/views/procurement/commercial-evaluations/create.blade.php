@extends('layouts.app')

@section('content')

<div class="container-fluid">


    {{-- ============================================================= --}}
    {{-- Header --}}
    {{-- ============================================================= --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                Add Commercial Evaluation
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


            {{-- Back to Commercial Evaluations --}}
            <a
                href="{{ route(
                    'admin.procurement.tenders.commercial-evaluations.index',
                    $procurementTender
                ) }}"
                class="btn btn-outline-secondary"
            >
                Back
            </a>

        </div>

    </div>


    {{-- ============================================================= --}}
    {{-- Validation Errors --}}
    {{-- ============================================================= --}}

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


    {{-- ============================================================= --}}
    {{-- Form --}}
    {{-- ============================================================= --}}

    <form
        method="POST"
        action="{{ route(
            'admin.procurement.tenders.commercial-evaluations.store',
            $procurementTender
        ) }}"
    >

        @csrf


        <div class="card">


            {{-- Card Header --}}
            <div class="card-header">

                <strong>
                    Commercial Evaluation
                </strong>

            </div>


            {{-- Card Body --}}
            <div class="card-body">


                {{-- ================================================= --}}
                {{-- Qualified Submission --}}
                {{-- ================================================= --}}

                <div class="mb-4">

                    <label class="form-label">

                        Technically Qualified Submission

                        <span class="text-danger">
                            *
                        </span>

                    </label>


                    <select
                        name="procurement_tender_submission_id"
                        id="procurement_tender_submission_id"
                        class="form-select @error('procurement_tender_submission_id') is-invalid @enderror"
                        required
                    >

                        <option value="">
                            -- Select Qualified Submission --
                        </option>


                        @foreach(
                            $availableSubmissions
                            as $submission
                        )

                            <option
                                value="{{ $submission->id }}"
                                data-quoted-amount="{{ $submission->quoted_amount }}"
                                data-currency="{{ $submission->currency }}"
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

                                |

                                Technical Score:

                                {{
                                    number_format(
                                        $submission
                                            ->technicalEvaluation
                                            ->technical_score,
                                        2
                                    )
                                }}

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

                        <div class="text-danger small mt-2">

                            No technically qualified submissions
                            are available for Commercial Evaluation.

                        </div>

                    @else

                        <div class="text-muted small mt-2">

                            Only submissions with a
                            <strong>Qualified</strong>
                            Technical Evaluation are shown.

                        </div>

                    @endif

                </div>


                {{-- ================================================= --}}
                {{-- Commercial Evaluation Form --}}
                {{-- ================================================= --}}

                @include(
                    'procurement.commercial-evaluations._form',
                    [
                        'evaluation' => null,
                        'selectedSubmission' => null,
                    ]
                )

            </div>


            {{-- ===================================================== --}}
            {{-- Footer --}}
            {{-- ===================================================== --}}

            <div class="card-footer d-flex justify-content-end gap-2">


                <a
                    href="{{ route(
                        'admin.procurement.tenders.commercial-evaluations.index',
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
                    Save Commercial Evaluation
                </button>

            </div>

        </div>

    </form>

</div>


{{-- ================================================================ --}}
{{-- Submission → Quote / Currency --}}
{{-- ================================================================ --}}

<script>

document.addEventListener('DOMContentLoaded', function () {

    const submissionSelect =
        document.getElementById(
            'procurement_tender_submission_id'
        );

    const quotedAmount =
        document.getElementById(
            'original_quoted_amount'
        );

    const currency =
        document.getElementById(
            'currency'
        );


    if (
        !submissionSelect ||
        !quotedAmount ||
        !currency
    ) {
        return;
    }


    function updateSubmissionDetails() {

        const selectedOption =
            submissionSelect.options[
                submissionSelect.selectedIndex
            ];


        if (
            !selectedOption ||
            !selectedOption.value
        ) {

            quotedAmount.value = '0.00';

            currency.value = 'USD';

            return;
        }


        quotedAmount.value =
            selectedOption.dataset.quotedAmount
            || '0.00';


        currency.value =
            selectedOption.dataset.currency
            || 'USD';
    }


    submissionSelect.addEventListener(
        'change',
        updateSubmissionDetails
    );


    /*
     * Also populate values when the page
     * reloads after validation errors.
     */

    updateSubmissionDetails();

});

</script>

@endsection