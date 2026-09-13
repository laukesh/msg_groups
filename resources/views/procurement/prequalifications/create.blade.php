@extends('layouts.app')

@section('content')

<div class="container-fluid">


    {{-- =========================================================
        HEADER
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                Create Prequalification
            </h4>


            <div class="text-muted">

                Tender:

                {{ $procurementTender->tender_number }}

                -

                {{ $procurementTender->tender_title }}

            </div>


            @if(
                isset($selectedTenderBidder)
                && $selectedTenderBidder
            )

                <div class="mt-2">

                    <span class="text-muted">
                        Bidder:
                    </span>


                    <strong>

                        {{
                            $selectedTenderBidder
                                ->bidder
                                ->company_name
                        }}

                    </strong>


                    <span class="text-muted">

                        (
                        {{
                            $selectedTenderBidder
                                ->bidder
                                ->bidder_code
                        }}
                        )

                    </span>

                </div>

            @endif

        </div>


        <div class="d-flex gap-2">


            {{-- Back to Tender --}}
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


            {{-- Back to Prequalification --}}
            <a
                href="{{ route(
                    'admin.procurement.tenders.prequalifications.index',
                    $procurementTender
                ) }}"
                class="btn btn-outline-secondary"
            >

                Prequalifications

            </a>

        </div>

    </div>



    {{-- =========================================================
        ERRORS
    ========================================================== --}}

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



    {{-- =========================================================
        FORM
    ========================================================== --}}

    <form
        method="POST"
        action="{{ route(
            'admin.procurement.tenders.prequalifications.store',
            $procurementTender
        ) }}"
    >

        @csrf


        <div class="card">


            <div class="card-header">

                <strong>
                    Prequalification Information
                </strong>

            </div>


            <div class="card-body">

                @include(
                    'procurement.prequalifications._form',
                    [
                        'prequalification' => null,

                        'availableBidders' =>
                            $availableBidders,

                        'selectedTenderBidder' =>
                            $selectedTenderBidder ?? null,
                    ]
                )

            </div>


            <div class="card-footer d-flex justify-content-end gap-2">


                <a
                    href="{{ route(
                        'admin.procurement.tenders.prequalifications.index',
                        $procurementTender
                    ) }}"
                    class="btn btn-outline-secondary"
                >

                    Cancel

                </a>


                <button
                    type="submit"
                    class="btn btn-primary"
                >

                    Create Prequalification

                </button>


            </div>

        </div>

    </form>

</div>

@endsection