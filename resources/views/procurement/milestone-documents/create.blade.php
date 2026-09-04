@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ============================================================
        HEADER
    ============================================================= --}}

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">
                Contract:
                {{ $contract->contract_number }}
            </div>

            <h4 class="mb-1">
                Upload Deliverable
            </h4>

            <div class="text-muted">

                {{ $milestone->milestone_number }}

                -

                {{ $milestone->milestone_title }}

            </div>

        </div>


        <div class="d-flex flex-wrap gap-2">

            {{-- Back to Tender --}}
            <a
                href="{{ route(
                    'admin.procurement.tenders.show',
                    $procurementTender
                ) }}"
                class="btn btn-outline-primary"
            >
                <i class="bi bi-arrow-left me-1"></i>
                Back to Tender
            </a>


            {{-- Back to Contract --}}
            <a
                href="{{ route(
                    'admin.procurement.tenders.contracts.show',
                    [
                        'procurementTender' =>
                            $procurementTender,

                        'contract' =>
                            $contract,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                <i class="bi bi-file-earmark-text me-1"></i>
                Back to Contract
            </a>


            {{-- Back to Milestone --}}
            <a
                href="{{ route(
                    'admin.procurement.tenders.contracts.milestones.show',
                    [
                        'procurementTender' =>
                            $procurementTender,

                        'contract' =>
                            $contract,

                        'milestone' =>
                            $milestone,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                <i class="bi bi-flag me-1"></i>
                Back to Milestone
            </a>

        </div>

    </div>


    {{-- ============================================================
        ERRORS
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
        FORM
    ============================================================= --}}

    <form
        method="POST"
        enctype="multipart/form-data"
        action="{{ route(
            'admin.procurement.tenders.contracts.milestones.documents.store',
            [
                'procurementTender' =>
                    $procurementTender,

                'contract' =>
                    $contract,

                'milestone' =>
                    $milestone,
            ]
        ) }}"
    >

        @csrf


        <div class="card">

            <div class="card-header">

                <strong>
                    Deliverable Document Details
                </strong>

            </div>


            <div class="card-body">

                @include(
                    'procurement.milestone-documents._form'
                )

            </div>

        </div>


        {{-- ========================================================
            FOOTER
        ========================================================= --}}

        <div class="d-flex justify-content-end gap-2 mt-4">

            <a
                href="{{ route(
                    'admin.procurement.tenders.contracts.milestones.documents.index',
                    [
                        'procurementTender' =>
                            $procurementTender,

                        'contract' =>
                            $contract,

                        'milestone' =>
                            $milestone,
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
                <i class="bi bi-upload me-1"></i>
                Upload Document
            </button>

        </div>

    </form>

</div>

@endsection