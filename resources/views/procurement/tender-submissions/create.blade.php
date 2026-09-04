@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                Add Tender Submission
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
                    'admin.procurement.tenders.submissions.index',
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
            'admin.procurement.tenders.submissions.store',
            $procurementTender
        ) }}"
    >

        @csrf


        <div class="card">

            <div class="card-header">

                <strong>
                    Tender Submission
                </strong>

            </div>


            <div class="card-body">

                {{-- Tender Bidder --}}
                <div class="row g-3 mb-4">

                    <div class="col-md-12">

                        <label class="form-label">

                            Tender Bidder

                            <span class="text-danger">
                                *
                            </span>

                        </label>


                        <select
                            name="procurement_tender_bidder_id"
                            class="form-select @error('procurement_tender_bidder_id') is-invalid @enderror"
                            required
                        >

                            <option value="">
                                -- Select Bidder --
                            </option>


                            @foreach(
                                $availableBidders
                                as $tenderBidder
                            )

                                <option
                                    value="{{ $tenderBidder->id }}"
                                    @selected(
                                        old(
                                            'procurement_tender_bidder_id'
                                        ) == $tenderBidder->id
                                    )
                                >

                                    {{
                                        $tenderBidder
                                            ->bidder
                                            ->company_name
                                    }}

                                    @if(
                                        $tenderBidder
                                            ->bidder
                                            ->bidder_code
                                    )

                                        -
                                        {{
                                            $tenderBidder
                                                ->bidder
                                                ->bidder_code
                                        }}

                                    @endif

                                    @if(
                                        $tenderBidder
                                            ->bidder_reference_no
                                    )

                                        -
                                        {{
                                            $tenderBidder
                                                ->bidder_reference_no
                                        }}

                                    @endif

                                </option>

                            @endforeach

                        </select>


                        @if($availableBidders->isEmpty())

                            <div class="form-text text-danger">

                                All assigned bidders already have
                                submissions, or no bidders have been
                                assigned to this Tender.

                            </div>

                        @else

                            <div class="form-text">

                                Only bidders assigned to this Tender
                                without an existing submission are shown.

                            </div>

                        @endif


                        @error('procurement_tender_bidder_id')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>


                {{-- Submission Form --}}
                @include(
                    'procurement.tender-submissions._form',
                    [
                        'submission' => null,
                    ]
                )

            </div>


            {{-- Footer --}}
            <div class="card-footer d-flex justify-content-end gap-2">

                <a
                    href="{{ route(
                        'admin.procurement.tenders.submissions.index',
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
                        $availableBidders->isEmpty()
                    )
                >
                    Save Submission
                </button>

            </div>

        </div>

    </form>

</div>

@endsection