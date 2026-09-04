@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">
                Tender:
                <strong>
                    {{ $procurementTender->tender_number }}
                </strong>
            </div>

            <h4 class="mb-1">
                Create Award
            </h4>

            <div class="text-muted">
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
                Tender
            </a>


            {{-- Back to Awards --}}
            <a
                href="{{ route(
                    'admin.procurement.tenders.awards.index',
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


    {{-- No Approved Negotiation --}}
    @if($negotiations->isEmpty())

        <div class="alert alert-warning">

            <h6 class="alert-heading">
                No Approved Negotiation Available
            </h6>

            <p class="mb-2">

                An Award can only be created after a
                Negotiation has been finalized and approved.

            </p>

            <a
                href="{{ route(
                    'admin.procurement.tenders.negotiations.index',
                    $procurementTender
                ) }}"
                class="btn btn-sm btn-warning"
            >
                View Negotiations
            </a>

        </div>

    @else

        <form
            method="POST"
            action="{{ route(
                'admin.procurement.tenders.awards.store',
                $procurementTender
            ) }}"
        >

            @csrf


            <div class="card">

                <div class="card-header">

                    <strong>
                        Award Details
                    </strong>

                </div>


                <div class="card-body">

                    @include(
                        'procurement.awards._form',
                        [
                            'negotiations' =>
                                $negotiations,
                        ]
                    )

                </div>

            </div>


            {{-- Actions --}}
            <div class="d-flex justify-content-end gap-2 mt-4">

                <a
                    href="{{ route(
                        'admin.procurement.tenders.awards.index',
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
                    Create Award
                </button>

            </div>

        </form>

    @endif

</div>

@endsection