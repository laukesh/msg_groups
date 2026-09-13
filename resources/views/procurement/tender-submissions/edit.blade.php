@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                Edit Tender Submission
            </h4>

            <div class="text-muted">

                {{ $submission->submission_number }}

                -

                {{
                    $submission
                        ->tenderBidder
                        ->bidder
                        ->company_name
                }}

            </div>

        </div>


        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.procurement.tenders.show',
                    $procurementTender
                ) }}"
                class="btn btn-outline-primary"
            >
                <i class="ri-arrow-left-line me-1"></i>
                Back to Tender
            </a>


            <a
                href="{{ route(
                    'admin.procurement.tenders.submissions.show',
                    [
                        'procurementTender' =>
                            $procurementTender,

                        'submission' =>
                            $submission,
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


    <form
        method="POST"
        action="{{ route(
            'admin.procurement.tenders.submissions.update',
            [
                'procurementTender' =>
                    $procurementTender,

                'submission' =>
                    $submission,
            ]
        ) }}"
    >

        @csrf

        @method('PUT')


        <div class="card">

            <div class="card-header">

                <strong>
                    Tender Submission
                </strong>

            </div>


            <div class="card-body">

                {{-- Tender / Bidder --}}
                <div class="row g-3 mb-4">

                    <div class="col-md-6">

                        <label class="form-label">
                            Bidder
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="{{
                                $submission
                                    ->tenderBidder
                                    ->bidder
                                    ->company_name
                            }}"
                            readonly
                        >

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">
                            Tender
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="{{
                                $procurementTender
                                    ->tender_number
                            }}"
                            readonly
                        >

                    </div>

                </div>


                @include(
                    'procurement.tender-submissions._form',
                    [
                        'submission' => $submission,
                    ]
                )

            </div>


            <div class="card-footer d-flex justify-content-end gap-2">

                <a
                    href="{{ route(
                        'admin.procurement.tenders.submissions.show',
                        [
                            'procurementTender' =>
                                $procurementTender,

                            'submission' =>
                                $submission,
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
                    Update Submission
                </button>

            </div>

        </div>

    </form>

</div>

@endsection