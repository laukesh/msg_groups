@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">
                Tender:
                {{ $procurementTender->tender_number }}
            </div>

            <h4 class="mb-1">
                Create Negotiation
            </h4>

            <div class="text-muted">
                {{ $procurementTender->tender_title }}
            </div>

        </div>


        <a
            href="{{ route(
                'admin.procurement.tenders.negotiations.index',
                $procurementTender
            ) }}"
            class="btn btn-outline-secondary"
        >
            Back
        </a>

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


    @if($comparisons->isEmpty())

        <div class="alert alert-warning">

            <h6 class="alert-heading">
                No Bid Comparison Available
            </h6>

            <p class="mb-0">

                Negotiation can only be started after a
                <strong>Completed</strong>
                or
                <strong>Approved</strong>
                Bid Comparison with a recommended bidder exists.

            </p>

        </div>

    @else

        <form
            method="POST"
            action="{{ route(
                'admin.procurement.tenders.negotiations.store',
                $procurementTender
            ) }}"
        >

            @csrf


            <div class="card">

                <div class="card-header">

                    <strong>
                        Negotiation Details
                    </strong>

                </div>


                <div class="card-body">

                    @include(
                        'procurement.negotiations._form',
                        [
                            'comparisons' =>
                                $comparisons,
                        ]
                    )

                </div>

            </div>


            <div class="d-flex justify-content-end gap-2 mt-4">

                <a
                    href="{{ route(
                        'admin.procurement.tenders.negotiations.index',
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
                    Create Negotiation
                </button>

            </div>

        </form>

    @endif

</div>

@endsection