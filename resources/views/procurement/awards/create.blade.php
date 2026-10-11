@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small mb-1">
                Procurement Award
            </div>

            <h4 class="mb-1">
                Create Award
            </h4>

            <div class="text-muted">
                Tender:
                <strong>
                    {{ $procurementTender->tender_number }}
                </strong>
                —
                {{ $procurementTender->tender_title }}
            </div>

        </div>


        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.projects.procurement.tenders.show',
                    [
                        'project' => $project,
                        'procurementTender' => $procurementTender,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                <i class="ri-arrow-left-line me-1"></i>
                Tender
            </a>

            <a
                href="{{ route(
                    'admin.projects.procurement.tenders.awards.index',
                    [
                        'project' => $project,
                        'procurementTender' => $procurementTender,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                Awards
            </a>

        </div>

    </div>


    {{-- Errors --}}
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

        </div>

    @else

        <form
            method="POST"
            action="{{ route(
                'admin.projects.procurement.tenders.awards.store',
                [
                    'project' => $project,
                    'procurementTender' => $procurementTender,
                ]
            ) }}"
        >

            @csrf


            {{-- Project Context --}}
            <div class="card mb-4">

                <div class="card-header">
                    <strong>
                        Project & Tender Context
                    </strong>
                </div>

                <div class="card-body">

                    <div class="row g-4">

                        <div class="col-md-4">

                            <div class="text-muted small">
                                Project
                            </div>

                            <div class="fw-semibold">
                                {{ $project->project_name }}
                            </div>

                        </div>

                        <div class="col-md-2">

                            <div class="text-muted small">
                                Project Code
                            </div>

                            <div>
                                {{ $project->project_code ?? '—' }}
                            </div>

                        </div>

                        <div class="col-md-3">

                            <div class="text-muted small">
                                Tender Number
                            </div>

                            <div class="fw-semibold">
                                {{ $procurementTender->tender_number }}
                            </div>

                        </div>

                        <div class="col-md-3">

                            <div class="text-muted small">
                                Tender Status
                            </div>

                            <span class="badge bg-secondary">
                                {{ $procurementTender->status }}
                            </span>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Award Details --}}
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
                            'negotiations' => $negotiations,
                        ]
                    )

                </div>

            </div>


            <div class="d-flex justify-content-end gap-2 mt-4">

                <a
                    href="{{ route(
                        'admin.projects.procurement.tenders.awards.index',
                        [
                            'project' => $project,
                            'procurementTender' => $procurementTender,
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
                    <i class="ri-award-line me-1"></i>
                    Create Award
                </button>

            </div>

        </form>

    @endif

</div>

@endsection