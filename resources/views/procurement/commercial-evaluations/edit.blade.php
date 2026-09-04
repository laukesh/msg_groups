@extends('layouts.app')

@section('content')

<div class="container-fluid">


    {{-- ============================================================= --}}
    {{-- Header --}}
    {{-- ============================================================= --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                Edit Commercial Evaluation
            </h4>

            <div class="text-muted">

                {{ $evaluation->evaluation_number }}

                -

                {{
                    $evaluation
                        ->submission
                        ->tenderBidder
                        ->bidder
                        ->company_name
                }}

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


            {{-- Back to Commercial Evaluation --}}
            <a
                href="{{ route(
                    'admin.procurement.tenders.commercial-evaluations.show',
                    [
                        'procurementTender' =>
                            $procurementTender,

                        'evaluation' =>
                            $evaluation,
                    ]
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
            'admin.procurement.tenders.commercial-evaluations.update',
            [
                'procurementTender' =>
                    $procurementTender,

                'evaluation' =>
                    $evaluation,
            ]
        ) }}"
    >

        @csrf

        @method('PUT')


        <div class="card">


            {{-- ===================================================== --}}
            {{-- Card Header --}}
            {{-- ===================================================== --}}

            <div class="card-header">

                <strong>
                    Commercial Evaluation
                </strong>

            </div>


            {{-- ===================================================== --}}
            {{-- Card Body --}}
            {{-- ===================================================== --}}

            <div class="card-body">


                {{-- ================================================= --}}
                {{-- Existing Submission --}}
                {{-- ================================================= --}}

                <div class="row mb-4">


                    {{-- Tender Submission --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            Tender Submission
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="{{
                                $evaluation
                                    ->submission
                                    ->submission_number
                            }}"
                            readonly
                        >

                    </div>


                    {{-- Bidder --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            Bidder
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="{{
                                $evaluation
                                    ->submission
                                    ->tenderBidder
                                    ->bidder
                                    ->company_name
                            }}"
                            readonly
                        >

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- Commercial Evaluation Form --}}
                {{-- ================================================= --}}

                @include(
                    'procurement.commercial-evaluations._form',
                    [
                        'evaluation' =>
                            $evaluation,

                        'selectedSubmission' =>
                            $evaluation->submission,
                    ]
                )

            </div>


            {{-- ===================================================== --}}
            {{-- Footer --}}
            {{-- ===================================================== --}}

            <div class="card-footer d-flex justify-content-end gap-2">


                <a
                    href="{{ route(
                        'admin.procurement.tenders.commercial-evaluations.show',
                        [
                            'procurementTender' =>
                                $procurementTender,

                            'evaluation' =>
                                $evaluation,
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
                    Update Commercial Evaluation
                </button>

            </div>

        </div>

    </form>

</div>

@endsection