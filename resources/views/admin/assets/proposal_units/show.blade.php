@extends('layouts.app')

@section('title', 'Proposal Unit Details')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h1 class="h3 mb-1">
                Proposal Unit Details
            </h1>

            <p class="text-muted mb-0">
                View proposal unit information.
            </p>
        </div>

        <div class="d-flex gap-2">

            <a
                href="{{ route('admin.assets.proposal_units.edit', $item->id) }}"
                class="btn btn-primary"
            >
                Edit
            </a>

            <a
                href="{{ route('admin.assets.proposal_units.index') }}"
                class="btn btn-secondary"
            >
                ← Back
            </a>

        </div>

    </div>


    {{-- Proposal Unit Information --}}
    <div class="card mb-4">

        <div class="card-header">
            <h5 class="mb-0">
                Proposal Unit Information
            </h5>
        </div>

        <div class="card-body">

            <div class="row">

                {{-- Proposal --}}
                <div class="col-md-6 mb-3">

                    <strong>Proposal</strong>

                    <div class="mt-1">

                        @if($item->proposal)

                            {{ $item->proposal->title }}

                        @else

                            {{ $item->proposal_id }}

                        @endif

                    </div>

                </div>


                {{-- Unit --}}
                <div class="col-md-6 mb-3">

                    <strong>Unit</strong>

                    <div class="mt-1">

                        @if($item->unit)

                            {{ $item->unit->unit_no }}

                            @if($item->unit->shop_name)

                                <small class="text-muted d-block">
                                    {{ $item->unit->shop_name }}
                                </small>

                            @endif

                        @else

                            {{ $item->unit_id }}

                        @endif

                    </div>

                </div>


                {{-- Proposed Rent --}}
                <div class="col-md-4 mb-3">

                    <strong>Proposed Rent</strong>

                    <div class="mt-1">
                        {{ number_format((float) $item->proposed_rent, 2) }}
                    </div>

                </div>


                {{-- CAM Rate --}}
                <div class="col-md-4 mb-3">

                    <strong>Proposed CAM Rate</strong>

                    <div class="mt-1">
                        {{ number_format((float) $item->proposed_cam_rate, 2) }}
                    </div>

                </div>


                {{-- Security Deposit --}}
                <div class="col-md-4 mb-3">

                    <strong>Proposed Security Deposit</strong>

                    <div class="mt-1">
                        {{ number_format((float) $item->proposed_security_deposit, 2) }}
                    </div>

                </div>


                {{-- Rent Free Days --}}
                <div class="col-md-4 mb-3">

                    <strong>Rent Free Days</strong>

                    <div class="mt-1">
                        {{ $item->rent_free_days ?? 0 }} days
                    </div>

                </div>


                {{-- Fitout Period --}}
                <div class="col-md-4 mb-3">

                    <strong>Fitout Period</strong>

                    <div class="mt-1">
                        {{ $item->fitout_period_days ?? 0 }} days
                    </div>

                </div>


                {{-- Remarks --}}
                <div class="col-md-12 mb-3">

                    <strong>Remarks</strong>

                    <div class="mt-1">

                        @if($item->remarks)

                            {{ $item->remarks }}

                        @else

                            <span class="text-muted">
                                No remarks provided.
                            </span>

                        @endif

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Audit Information --}}
    <div class="card">

        <div class="card-header">
            <h5 class="mb-0">
                Audit Information
            </h5>
        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-3 mb-3">

                    <strong>Created By</strong>

                    <div class="mt-1">
                        {{ $item->creator->name ?? $item->created_by ?? '-' }}
                    </div>

                </div>


                <div class="col-md-3 mb-3">

                    <strong>Updated By</strong>

                    <div class="mt-1">
                        {{ $item->updater->name ?? $item->updated_by ?? '-' }}
                    </div>

                </div>


                <div class="col-md-3 mb-3">

                    <strong>Created At</strong>

                    <div class="mt-1">
                        {{ $item->created_at?->format('d M Y H:i') ?? '-' }}
                    </div>

                </div>


                <div class="col-md-3 mb-3">

                    <strong>Updated At</strong>

                    <div class="mt-1">
                        {{ $item->updated_at?->format('d M Y H:i') ?? '-' }}
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection