@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ============================================================
        HEADER
    ============================================================= --}}

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">

                Tender:

                <strong>
                    {{ $procurementTender->tender_number }}
                </strong>

            </div>


            <h4 class="mb-1">
                Create Contract
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
                class="btn btn-outline-primary"
            >
                Back to Tender
            </a>


            {{-- Back to Contracts --}}
            <a
                href="{{ route(
                    'admin.procurement.tenders.contracts.index',
                    $procurementTender
                ) }}"
                class="btn btn-outline-secondary"
            >
                Back
            </a>

        </div>

    </div>


    {{-- ============================================================
        VALIDATION ERRORS
    ============================================================= --}}

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


    {{-- ============================================================
        NO AWARDS
    ============================================================= --}}

    @if($awards->isEmpty())

        <div class="alert alert-warning">

            <h6 class="alert-heading">
                No LOA Issued Award Available
            </h6>


            <p class="mb-0">

                A Contract can only be created after
                an Award has been approved and the
                Letter of Award has been issued.

            </p>

        </div>

    @else


        {{-- ========================================================
            FORM
        ========================================================= --}}

        <form
            method="POST"
            action="{{ route(
                'admin.procurement.tenders.contracts.store',
                $procurementTender
            ) }}"
        >

            @csrf


            <div class="card">

                <div class="card-header">

                    <strong>
                        Contract Details
                    </strong>

                </div>


                <div class="card-body">

                    @include(
                        'procurement.contracts._form',
                        [
                            'awards' => $awards,
                        ]
                    )

                </div>

            </div>


            {{-- ====================================================
                ACTIONS
            ===================================================== --}}

            <div class="d-flex justify-content-end gap-2 mt-4">


                <a
                    href="{{ route(
                        'admin.procurement.tenders.contracts.index',
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
                    <i class="ri-save-line me-1"></i>
                    Create Contract
                </button>

            </div>

        </form>

    @endif

</div>

@endsection