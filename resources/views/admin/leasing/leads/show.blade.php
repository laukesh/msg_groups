@extends('layouts.app')

@section('title', 'Lead Details')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                <i class="ri-user-search-line me-1"></i>
                Lead Details
            </h4>

            <p class="text-muted mb-0">
                {{ $lead->company_name }}
            </p>

        </div>


        <div class="d-flex gap-2">

            <a href="{{ route('admin.leasing.leads.edit', $lead) }}"
               class="btn btn-warning">

                <i class="ri-edit-line me-1"></i>
                Edit

            </a>

            <a href="{{ route('admin.leasing.leads.index') }}"
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


        {{-- Company --}}
        <div class="col-lg-8">

            <div class="card shadow-sm border-0 mb-4">

                <div class="card-header bg-white">

                    <strong>
                        <i class="ri-building-line me-1"></i>
                        Company & Contact
                    </strong>

                </div>


                <div class="card-body">

                    <div class="row g-4">

                        <div class="col-md-6">

                            <small class="text-muted">
                                Company
                            </small>

                            <div class="fw-semibold fs-5">
                                {{ $lead->company_name }}
                            </div>

                        </div>


                        <div class="col-md-6">

                            <small class="text-muted">
                                Contact Person
                            </small>

                            <div>
                                {{ $lead->contact_name ?? '-' }}
                            </div>

                        </div>


                        <div class="col-md-6">

                            <small class="text-muted">
                                Email
                            </small>

                            <div>

                                @if($lead->email)

                                    <a href="mailto:{{ $lead->email }}">
                                        {{ $lead->email }}
                                    </a>

                                @else

                                    -

                                @endif

                            </div>

                        </div>


                        <div class="col-md-6">

                            <small class="text-muted">
                                Phone
                            </small>

                            <div>

                                @if($lead->phone)

                                    <a href="tel:{{ $lead->phone }}">
                                        {{ $lead->phone }}
                                    </a>

                                @else

                                    -

                                @endif

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Requirements --}}
            <div class="card shadow-sm border-0 mb-4">

                <div class="card-header bg-white">

                    <strong>
                        <i class="ri-store-2-line me-1"></i>
                        Business Requirements
                    </strong>

                </div>


                <div class="card-body">

                    <div class="row g-4">

                        <div class="col-md-6">

                            <small class="text-muted">
                                Brand
                            </small>

                            <div>
                                {{ $lead->brand_name ?? '-' }}
                            </div>

                        </div>


                        <div class="col-md-6">

                            <small class="text-muted">
                                Category
                            </small>

                            <div>
                                {{ $lead->category ?? '-' }}
                            </div>

                        </div>


                        <div class="col-md-4">

                            <small class="text-muted">
                                Area Required
                            </small>

                            <div class="fw-semibold">

                                @if($lead->area_need !== null)

                                    {{ number_format((float) $lead->area_need, 2) }}

                                @else

                                    -

                                @endif

                            </div>

                        </div>


                        <div class="col-md-4">

                            <small class="text-muted">
                                Budget
                            </small>

                            <div class="fw-semibold">

                                @if($lead->budget !== null)

                                    {{ number_format((float) $lead->budget, 2) }}

                                @else

                                    -

                                @endif

                            </div>

                        </div>


                        <div class="col-md-4">

                            <small class="text-muted">
                                Preferred Location
                            </small>

                            <div>
                                {{ $lead->preferred_location ?? '-' }}
                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Notes --}}
            <div class="card shadow-sm border-0">

                <div class="card-header bg-white">

                    <strong>
                        <i class="ri-sticky-note-line me-1"></i>
                        Notes
                    </strong>

                </div>


                <div class="card-body">

                    @if($lead->notes)

                        <div style="white-space: pre-line;">
                            {{ $lead->notes }}
                        </div>

                    @else

                        <span class="text-muted">
                            No notes available.
                        </span>

                    @endif

                </div>

            </div>

        </div>


        {{-- Status --}}
        <div class="col-lg-4">

            <div class="card shadow-sm border-0 mb-4">

                <div class="card-header bg-white">

                    <strong>
                        Lead Status
                    </strong>

                </div>


                <div class="card-body text-center">

                    @php

                        $statusClass = match($lead->status) {

                            'NEW' => 'bg-primary',
                            'CONTACTED' => 'bg-info',
                            'QUALIFIED' => 'bg-success',
                            'PROPOSAL' => 'bg-warning text-dark',
                            'NEGOTIATION' => 'bg-warning text-dark',
                            'CONVERTED' => 'bg-success',
                            'LOST' => 'bg-danger',
                            'ON_HOLD' => 'bg-secondary',

                            default => 'bg-secondary',

                        };

                    @endphp


                    <span class="badge {{ $statusClass }} fs-6 px-3 py-2">

                        {{ str_replace('_', ' ', $lead->status) }}

                    </span>

                </div>

            </div>


            <div class="card shadow-sm border-0 mb-4">

                <div class="card-header bg-white">

                    <strong>
                        Lead Information
                    </strong>

                </div>


                <div class="card-body">

                    <div class="mb-3">

                        <small class="text-muted">
                            Source
                        </small>

                        <div>
                            {{ $lead->source ?? '-' }}
                        </div>

                    </div>


                    <div class="mb-3">

                        <small class="text-muted">
                            Next Follow-up
                        </small>

                        <div>

                            @if($lead->next_follow_up_at)

                                {{ $lead->next_follow_up_at->format('d M Y, h:i A') }}

                            @else

                                -

                            @endif

                        </div>

                    </div>


                    <div class="mb-3">

                        <small class="text-muted">
                            Created
                        </small>

                        <div>

                            {{ $lead->created_at
                                ? $lead->created_at->format('d M Y, h:i A')
                                : '-' }}

                        </div>

                    </div>


                    <div>

                        <small class="text-muted">
                            Last Updated
                        </small>

                        <div>

                            {{ $lead->updated_at
                                ? $lead->updated_at->format('d M Y, h:i A')
                                : '-' }}

                        </div>

                    </div>

                </div>

            </div>


            {{-- Delete --}}
            <div class="card shadow-sm border-0">

                <div class="card-body">

                    <form method="POST"
                          action="{{ route('admin.leasing.leads.destroy', $lead) }}"
                          onsubmit="return confirm('Are you sure you want to delete this lead?');">

                        @csrf
                        @method('DELETE')

                        <button type="submit"
                                class="btn btn-outline-danger w-100">

                            <i class="ri-delete-bin-line me-1"></i>
                            Delete Lead

                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection