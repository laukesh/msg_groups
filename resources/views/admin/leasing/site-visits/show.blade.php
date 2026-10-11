@extends('layouts.app')

@section('title', 'Site Visit Details')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                <i class="ri-map-pin-user-line me-1"></i>
                Site Visit Details
            </h4>

            <p class="text-muted mb-0">
                Visit record and prospect feedback.
            </p>

        </div>


        <div class="d-flex gap-2">

            <a href="{{ route('admin.leasing.site-visits.edit', $siteVisit) }}"
               class="btn btn-warning">

                <i class="ri-edit-line me-1"></i>
                Edit

            </a>

            <a href="{{ route('admin.leasing.site-visits.index') }}"
               class="btn btn-light border">

                <i class="ri-arrow-left-line me-1"></i>
                Back

            </a>

        </div>

    </div>


    @if(session('success'))

        <div class="alert alert-success">

            <i class="ri-checkbox-circle-line me-1"></i>

            {{ session('success') }}

        </div>

    @endif


    <div class="row g-4">

        {{-- Main --}}
        <div class="col-lg-8">


            {{-- Prospect --}}
            <div class="card shadow-sm border-0 mb-4">

                <div class="card-header bg-white">

                    <strong>
                        <i class="ri-user-search-line me-1"></i>
                        Prospect
                    </strong>

                </div>


                <div class="card-body">

                    @if($siteVisit->prospect)

                        <div class="row g-4">

                            <div class="col-md-6">

                                <small class="text-muted">
                                    Company
                                </small>

                                <div class="fw-semibold fs-5">

                                    {{ $siteVisit->prospect->company_name }}

                                </div>

                            </div>


                            <div class="col-md-6">

                                <small class="text-muted">
                                    Contact
                                </small>

                                <div>

                                    {{ $siteVisit->prospect->contact_name ?? '-' }}

                                </div>

                            </div>


                            <div class="col-md-6">

                                <small class="text-muted">
                                    Brand
                                </small>

                                <div>

                                    {{ $siteVisit->prospect->brand_name ?? '-' }}

                                </div>

                            </div>


                            <div class="col-md-6">

                                <small class="text-muted">
                                    Phone
                                </small>

                                <div>

                                    {{ $siteVisit->prospect->phone ?? '-' }}

                                </div>

                            </div>

                        </div>

                    @else

                        <span class="text-muted">
                            Prospect not available.
                        </span>

                    @endif

                </div>

            </div>


            {{-- Unit --}}
            <div class="card shadow-sm border-0 mb-4">

                <div class="card-header bg-white">

                    <strong>
                        <i class="ri-building-2-line me-1"></i>
                        Property / Unit
                    </strong>

                </div>


                <div class="card-body">

                    <div class="row g-4">

                        <div class="col-md-4">

                            <small class="text-muted">
                                Unit
                            </small>

                            <div class="fw-semibold">

                                {{ optional($siteVisit->unit)->unit_no ?? '-' }}

                            </div>

                        </div>


                        <div class="col-md-4">

                            <small class="text-muted">
                                Shop Name
                            </small>

                            <div>

                                {{ optional($siteVisit->unit)->shop_name ?? '-' }}

                            </div>

                        </div>


                        <div class="col-md-4">

                            <small class="text-muted">
                                Visit Date
                            </small>

                            <div class="fw-semibold">

                                {{ optional($siteVisit->visit_date)
                                    ->format('d M Y, h:i A') }}

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Feedback --}}
            <div class="card shadow-sm border-0 mb-4">

                <div class="card-header bg-white">

                    <strong>
                        <i class="ri-feedback-line me-1"></i>
                        Feedback & Outcome
                    </strong>

                </div>


                <div class="card-body">

                    <div class="mb-4">

                        <small class="text-muted">
                            Outcome
                        </small>

                        <div class="fw-semibold">

                            {{ $siteVisit->outcome ?? '-' }}

                        </div>

                    </div>


                    <div class="mb-4">

                        <small class="text-muted">
                            Prospect Feedback
                        </small>

                        <div style="white-space: pre-line;">

                            {{ $siteVisit->feedback ?? 'No feedback recorded.' }}

                        </div>

                    </div>


                    <div>

                        <small class="text-muted">
                            Internal Notes
                        </small>

                        <div style="white-space: pre-line;">

                            {{ $siteVisit->notes ?? 'No notes recorded.' }}

                        </div>

                    </div>

                </div>

            </div>


            {{-- Attendees --}}
            <div class="card shadow-sm border-0">

                <div class="card-header bg-white">

                    <strong>
                        <i class="ri-group-line me-1"></i>
                        Attendees
                    </strong>

                </div>


                <div class="card-body">

                    <div style="white-space: pre-line;">

                        {{ $siteVisit->attendees ?? 'No attendees recorded.' }}

                    </div>

                </div>

            </div>

        </div>


        {{-- Sidebar --}}
        <div class="col-lg-4">


            {{-- Status --}}
            <div class="card shadow-sm border-0 mb-4">

                <div class="card-header bg-white">

                    <strong>
                        Visit Status
                    </strong>

                </div>


                <div class="card-body text-center">

                    @php

                        $statusClass = match($siteVisit->status) {

                            'SCHEDULED' => 'bg-primary',
                            'COMPLETED' => 'bg-success',
                            'CANCELLED' => 'bg-danger',
                            'NO_SHOW' => 'bg-warning text-dark',
                            'RESCHEDULED' => 'bg-info',

                            default => 'bg-secondary',

                        };

                    @endphp


                    <span class="badge {{ $statusClass }} fs-6 px-3 py-2">

                        {{ str_replace('_', ' ', $siteVisit->status) }}

                    </span>

                </div>

            </div>


            {{-- Interest --}}
            <div class="card shadow-sm border-0 mb-4">

                <div class="card-header bg-white">

                    <strong>
                        Interest Level
                    </strong>

                </div>


                <div class="card-body text-center">

                    @php

                        $interestClass = match($siteVisit->interest) {

                            'HIGH' => 'bg-success',
                            'MEDIUM' => 'bg-warning text-dark',
                            'LOW' => 'bg-secondary',
                            'NOT_INTERESTED' => 'bg-danger',

                            default => 'bg-light text-dark',

                        };

                    @endphp


                    <span class="badge {{ $interestClass }} fs-6 px-3 py-2">

                        {{ $siteVisit->interest
                            ? str_replace('_', ' ', $siteVisit->interest)
                            : 'Not Recorded' }}

                    </span>

                </div>

            </div>


            {{-- Follow-up --}}
            <div class="card shadow-sm border-0 mb-4">

                <div class="card-header bg-white">

                    <strong>
                        Follow-up
                    </strong>

                </div>


                <div class="card-body">

                    <div class="mb-3">

                        <small class="text-muted">
                            Follow-up Date
                        </small>

                        <div class="fw-semibold">

                            {{ optional($siteVisit->follow_up_date)
                                ->format('d M Y, h:i A') ?? '-' }}

                        </div>

                    </div>


                    <div>

                        <small class="text-muted">
                            Leasing Officer
                        </small>

                        <div>

                            {{ optional($siteVisit->assignedOfficer)->name ?? '-' }}

                        </div>

                    </div>

                </div>

            </div>


            {{-- Delete --}}
            <div class="card shadow-sm border-0">

                <div class="card-body">

                    <form method="POST"
                          action="{{ route('admin.leasing.site-visits.destroy', $siteVisit) }}"
                          onsubmit="return confirm('Are you sure you want to delete this site visit?');">

                        @csrf
                        @method('DELETE')

                        <button class="btn btn-outline-danger w-100">

                            <i class="ri-delete-bin-line me-1"></i>
                            Delete Visit

                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection