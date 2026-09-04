@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">
                Contract:
                <strong>
                    {{ $contract->contract_number }}
                </strong>
            </div>

            <h4 class="mb-1">
                Add Milestone
            </h4>

            <div class="text-muted">
                {{ $contract->contract_title }}
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
                Back to Contract
            </a>


            {{-- Back to Milestones --}}
            <a
                href="{{ route(
                    'admin.procurement.tenders.contracts.milestones.index',
                    [
                        'procurementTender' =>
                            $procurementTender,

                        'contract' =>
                            $contract,
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


    {{-- Success --}}
    @if(session('success'))

        <div class="alert alert-success">

            {{ session('success') }}

        </div>

    @endif


    {{-- Error --}}
    @if(session('error'))

        <div class="alert alert-danger">

            {{ session('error') }}

        </div>

    @endif


    <form
        method="POST"
        action="{{ route(
            'admin.procurement.tenders.contracts.milestones.store',
            [
                'procurementTender' =>
                    $procurementTender,

                'contract' =>
                    $contract,
            ]
        ) }}"
    >

        @csrf


        <div class="card">

            <div class="card-header">

                <strong>
                    Milestone Details
                </strong>

            </div>


            <div class="card-body">

                @include(
                    'procurement.contract-milestones._form',
                    [
                        'contract' =>
                            $contract,
                    ]
                )

            </div>


            <div class="card-footer">

                <div class="d-flex justify-content-between align-items-center">

                    <div class="text-muted small">

                        <i class="bi bi-info-circle me-1"></i>

                        Milestone number will be generated
                        automatically.

                    </div>


                    <div class="d-flex gap-2">

                        <a
                            href="{{ route(
                                'admin.procurement.tenders.contracts.milestones.index',
                                [
                                    'procurementTender' =>
                                        $procurementTender,

                                    'contract' =>
                                        $contract,
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
                            <i class="bi bi-plus-circle me-1"></i>
                            Create Milestone
                        </button>

                    </div>

                </div>

            </div>

        </div>

    </form>

</div>

@endsection