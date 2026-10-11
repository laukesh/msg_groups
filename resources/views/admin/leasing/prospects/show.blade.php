@extends('layouts.app')

@section('title', 'Lead / Prospect Details')

@section('content')

<div class="container-fluid">

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                {{ $prospect->company_name ?: ($prospect->contact_name ?: 'Lead / Prospect') }}
            </h4>

            <div class="text-muted">
                {{ $prospect->contact_name ?: 'No contact name' }}

                @if($prospect->brand_name)
                    · {{ $prospect->brand_name }}
                @endif
            </div>
        </div>

        <div class="d-flex gap-2">

            @can('prospects.edit')
                <a href="{{ route('admin.leasing.prospects.edit', $prospect) }}"
                   class="btn btn-primary">
                    <i class="ri-edit-line me-1"></i>
                    Edit
                </a>
            @endcan

            <a href="{{ route('admin.leasing.prospects.index') }}"
               class="btn btn-light border">
                <i class="ri-arrow-left-line me-1"></i>
                Back
            </a>

        </div>

    </div>


    {{-- =========================================================
        FLASH MESSAGES
    ========================================================== --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="ri-checkbox-circle-line me-1"></i>
            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="ri-error-warning-line me-1"></i>
            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"></button>
        </div>
    @endif


    {{-- =========================================================
        SUMMARY CARDS
    ========================================================== --}}
    <div class="row g-3 mb-4">

        {{-- Status --}}
        <div class="col-md-6 col-xl-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <small class="text-muted d-block mb-2">
                        Status
                    </small>

                    @php
                        $status = strtolower($prospect->status ?? 'lead');

                        $statusClass = match($status) {
                            'converted' => 'bg-success',
                            'lost' => 'bg-danger',
                            'inactive' => 'bg-secondary',
                            'negotiation' => 'bg-warning text-dark',
                            'qualified' => 'bg-info text-dark',
                            'site_visit' => 'bg-primary',
                            'prospect' => 'bg-primary',
                            default => 'bg-light text-dark',
                        };
                    @endphp

                    <span class="badge {{ $statusClass }} fs-6">
                        {{ $prospect->status_label }}
                    </span>

                </div>

            </div>

        </div>


        {{-- Area --}}
        <div class="col-md-6 col-xl-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <small class="text-muted d-block">
                        Area Required
                    </small>

                    <h5 class="mb-0 mt-2">

                        @if($prospect->area_need !== null)
                            {{ number_format((float) $prospect->area_need, 2) }}
                            <small class="text-muted">
                                {{ $prospect->area_unit }}
                            </small>
                        @else
                            -
                        @endif

                    </h5>

                </div>

            </div>

        </div>


        {{-- Budget --}}
        <div class="col-md-6 col-xl-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <small class="text-muted d-block">
                        Budget
                    </small>

                    <h5 class="mb-0 mt-2">
                        {{ $prospect->budget_range }}
                    </h5>

                </div>

            </div>

        </div>


        {{-- Activities --}}
        <div class="col-md-6 col-xl-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <small class="text-muted d-block">
                        Activities
                    </small>

                    <h5 class="mb-0 mt-2">
                        {{ $prospect->activities->count() }}
                    </h5>

                </div>

            </div>

        </div>

    </div>


    <div class="row g-4">

        {{-- =====================================================
            LEFT COLUMN
        ====================================================== --}}
        <div class="col-lg-8">


            {{-- =================================================
                CONTACT & COMPANY
            ================================================== --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white py-3">

                    <h5 class="mb-0">
                        <i class="ri-user-3-line me-1"></i>
                        Contact & Company
                    </h5>

                </div>

                <div class="card-body">

                    <div class="row g-4">

                        {{-- Company --}}
                        <div class="col-md-6">

                            <small class="text-muted d-block mb-1">
                                Company
                            </small>

                            <div class="fw-semibold">
                                {{ $prospect->company_name ?: '-' }}
                            </div>

                        </div>


                        {{-- Contact --}}
                        <div class="col-md-6">

                            <small class="text-muted d-block mb-1">
                                Contact Person
                            </small>

                            <div class="fw-semibold">
                                {{ $prospect->contact_name ?: '-' }}
                            </div>

                        </div>


                        {{-- Brand --}}
                        <div class="col-md-6">

                            <small class="text-muted d-block mb-1">
                                Brand
                            </small>

                            <div>
                                {{ $prospect->brand_name ?: '-' }}
                            </div>

                        </div>


                        {{-- Category --}}
                        <div class="col-md-6">

                            <small class="text-muted d-block mb-1">
                                Category
                            </small>

                            <div>
                                {{ $prospect->category ?: '-' }}
                            </div>

                        </div>


                        {{-- Email --}}
                        <div class="col-md-6">

                            <small class="text-muted d-block mb-1">
                                Email
                            </small>

                            @if($prospect->email)

                                <a href="mailto:{{ $prospect->email }}">
                                    {{ $prospect->email }}
                                </a>

                            @else
                                -
                            @endif

                        </div>


                        {{-- Phone --}}
                        <div class="col-md-6">

                            <small class="text-muted d-block mb-1">
                                Phone
                            </small>

                            @if($prospect->phone)

                                <a href="tel:{{ $prospect->phone }}">
                                    {{ $prospect->phone }}
                                </a>

                            @else
                                -
                            @endif

                        </div>


                        {{-- Source --}}
                        <div class="col-md-6">

                            <small class="text-muted d-block mb-1">
                                Lead Source
                            </small>

                            <div>
                                {{ $prospect->source ?: '-' }}
                            </div>

                        </div>


                        {{-- Owner --}}
                        <div class="col-md-6">

                            <small class="text-muted d-block mb-1">
                                Leasing Owner
                            </small>

                            <div>
                                {{ $prospect->assignedUser?->name ?: '-' }}
                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================================
                REQUIREMENTS
            ================================================== --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white py-3">

                    <h5 class="mb-0">
                        <i class="ri-building-2-line me-1"></i>
                        Leasing Requirements
                    </h5>

                </div>

                <div class="card-body">

                    <div class="row g-4">

                        {{-- Area --}}
                        <div class="col-md-6">

                            <small class="text-muted d-block mb-1">
                                Required Area
                            </small>

                            <div class="fw-semibold">

                                @if($prospect->area_need !== null)
                                    {{ number_format((float) $prospect->area_need, 2) }}
                                    {{ $prospect->area_unit }}
                                @else
                                    -
                                @endif

                            </div>

                        </div>


                        {{-- Budget --}}
                        <div class="col-md-6">

                            <small class="text-muted d-block mb-1">
                                Budget
                            </small>

                            <div class="fw-semibold">
                                {{ $prospect->budget_range }}
                            </div>

                        </div>


                        {{-- Preferred Location --}}
                        <div class="col-md-12">

                            <small class="text-muted d-block mb-1">
                                Preferred Location
                            </small>

                            <div>
                                {{ $prospect->preferred_location ?: '-' }}
                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================================
                NOTES
            ================================================== --}}
            @if($prospect->notes)

                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white py-3">

                        <h5 class="mb-0">
                            <i class="ri-sticky-note-line me-1"></i>
                            Notes
                        </h5>

                    </div>

                    <div class="card-body">

                        <div class="text-muted"
                             style="white-space: pre-line;">
                            {{ $prospect->notes }}
                        </div>

                    </div>

                </div>

            @endif


            {{-- =================================================
                ACTIVITIES
            ================================================== --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white py-3
                            d-flex justify-content-between
                            align-items-center">

                    <h5 class="mb-0">
                        <i class="ri-history-line me-1"></i>
                        Activity History
                    </h5>

                    <span class="badge bg-primary">
                        {{ $prospect->activities->count() }}
                    </span>

                </div>

                <div class="card-body">

                    @forelse($prospect->activities as $activity)

                        <div class="border-start border-3 ps-3 mb-4">

                            <div class="d-flex
                                        justify-content-between
                                        align-items-start">

                                <div>

                                    <h6 class="mb-1">

                                        {{ $activity->subject ?: 'Activity' }}

                                    </h6>

                                    <div class="small text-muted">

                                        <span class="badge bg-light text-dark">
                                            {{ $activity->activity_type_label }}
                                        </span>

                                        <span class="ms-2">
                                            {{ $activity->activity_date_label }}
                                        </span>

                                    </div>

                                </div>

                                <div class="small text-muted">

                                    {{ $activity->createdBy?->name ?: '-' }}

                                </div>

                            </div>


                            @if($activity->description)

                                <div class="mt-3">
                                    {{ $activity->description }}
                                </div>

                            @endif


                            @if($activity->outcome)

                                <div class="mt-2">

                                    <strong>
                                        Outcome:
                                    </strong>

                                    {{ $activity->outcome }}

                                </div>

                            @endif


                            @if($activity->next_action)

                                <div class="mt-2">

                                    <strong>
                                        Next Action:
                                    </strong>

                                    {{ $activity->next_action }}

                                </div>

                            @endif


                            @if($activity->next_action_date)

                                <div class="small text-primary mt-2">

                                    <i class="ri-calendar-line me-1"></i>

                                    Next Action:
                                    {{ $activity->next_action_date_label }}

                                </div>

                            @endif

                        </div>

                    @empty

                        <div class="text-center text-muted py-5">

                            <i class="ri-chat-history-line fs-1 d-block mb-2"></i>

                            No activities recorded.

                        </div>

                    @endforelse

                </div>

            </div>


            {{-- =================================================
                SITE VISITS
            ================================================== --}}
            @if($prospect->siteVisits->count())

                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white py-3
                                d-flex justify-content-between
                                align-items-center">

                        <h5 class="mb-0">
                            <i class="ri-map-pin-user-line me-1"></i>
                            Site Visits
                        </h5>

                        <span class="badge bg-primary">
                            {{ $prospect->siteVisits->count() }}
                        </span>

                    </div>

                    <div class="table-responsive">

                        <table class="table table-hover align-middle mb-0">

                            <thead class="table-light">

                                <tr>
                                    <th>Date</th>
                                    <th>Unit</th>
                                    <th>Status</th>
                                    <th>Interest</th>
                                    <th>Officer</th>
                                </tr>

                            </thead>

                            <tbody>

                                @foreach($prospect->siteVisits as $visit)

                                    <tr>

                                        <td>
                                            {{ $visit->visit_date
                                                ? $visit->visit_date->format('d M Y, h:i A')
                                                : '-' }}
                                        </td>

                                        <td>
                                            {{ $visit->unit?->name
                                                ?: ($visit->unit?->unit_no ?: '-') }}
                                        </td>

                                        <td>
                                            @php
                                                $visitStatus = strtoupper(
                                                    $visit->status ?? 'SCHEDULED'
                                                );

                                                $visitStatusClass = match($visitStatus) {
                                                    'COMPLETED' => 'bg-success',
                                                    'CANCELLED' => 'bg-danger',
                                                    'NO_SHOW' => 'bg-warning text-dark',
                                                    'RESCHEDULED' => 'bg-info text-dark',
                                                    default => 'bg-primary',
                                                };
                                            @endphp

                                            <span class="badge {{ $visitStatusClass }}">
                                                {{ str_replace('_', ' ', $visitStatus) }}
                                            </span>
                                        </td>

                                        <td>
                                            @if($visit->interest)
                                                {{ str_replace(
                                                    '_',
                                                    ' ',
                                                    ucfirst(strtolower($visit->interest))
                                                ) }}
                                            @else
                                                -
                                            @endif
                                        </td>

                                        <td>
                                            {{ $visit->assignedOfficer?->name ?: '-' }}
                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                </div>

            @endif


            {{-- =================================================
                LEASING PIPELINE
            ================================================== --}}
            @if($prospect->pipelines->count())

                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white py-3">

                        <h5 class="mb-0">
                            <i class="ri-flow-chart me-1"></i>
                            Leasing Pipeline
                        </h5>

                    </div>

                    <div class="table-responsive">

                        <table class="table table-hover align-middle mb-0">

                            <thead class="table-light">

                                <tr>
                                    <th>ID</th>
                                    <th>Unit</th>
                                    <th>Stage</th>
                                    <th>Expected Rent</th>
                                    <th>Probability</th>
                                    <th class="text-end">
                                        Action
                                    </th>
                                </tr>

                            </thead>

                            <tbody>

                                @foreach($prospect->pipelines as $pipeline)

                                    <tr>

                                        <td>
                                            #{{ $pipeline->id }}
                                        </td>

                                        <td>
                                            {{ $pipeline->unit?->name
                                                ?: ($pipeline->unit?->unit_no ?: '-') }}
                                        </td>

                                        <td>
                                            {{ $pipeline->stage_label }}
                                        </td>

                                        <td>

                                            @if($pipeline->expected_rent !== null)
                                                {{ number_format(
                                                    (float) $pipeline->expected_rent,
                                                    2
                                                ) }}
                                            @else
                                                -
                                            @endif

                                        </td>

                                        <td>

                                            <div class="d-flex align-items-center gap-2">

                                                <div class="progress"
                                                     style="width:80px;height:6px;">

                                                    <div class="progress-bar"
                                                         role="progressbar"
                                                         style="width: {{ min(100, max(0, (float) $pipeline->probability)) }}%;">
                                                    </div>

                                                </div>

                                                <span>
                                                    {{ $pipeline->probability ?? 0 }}%
                                                </span>

                                            </div>

                                        </td>

                                        <td class="text-end">

                                            <a href="{{ route(
                                                'admin.leasing.pipeline.show',
                                                $pipeline
                                            ) }}"
                                               class="btn btn-sm btn-success">

                                                <i class="ri-eye-line"></i>

                                            </a>

                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                </div>

            @endif

        </div>


        {{-- =====================================================
            RIGHT COLUMN
        ====================================================== --}}
        <div class="col-lg-4">


            {{-- =================================================
                LEAD INFORMATION
            ================================================== --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white py-3">

                    <h5 class="mb-0">
                        <i class="ri-information-line me-1"></i>
                        Lead Information
                    </h5>

                </div>

                <div class="card-body">

                    <div class="mb-3">

                        <small class="text-muted d-block">
                            Status
                        </small>

                        <strong>
                            {{ $prospect->status_label }}
                        </strong>

                    </div>


                    <div class="mb-3">

                        <small class="text-muted d-block">
                            Source
                        </small>

                        <span>
                            {{ $prospect->source ?: '-' }}
                        </span>

                    </div>


                    <div class="mb-3">

                        <small class="text-muted d-block">
                            Owner
                        </small>

                        <span>
                            {{ $prospect->assignedUser?->name ?: '-' }}
                        </span>

                    </div>


                    <div class="mb-3">

                        <small class="text-muted d-block">
                            Next Follow-up
                        </small>

                        @if($prospect->next_follow_up_at)

                            <span class="text-primary">

                                <i class="ri-calendar-line me-1"></i>

                                {{ $prospect->next_follow_up_at
                                    ->format('d M Y, h:i A') }}

                            </span>

                        @else
                            -
                        @endif

                    </div>


                    @if($prospect->created_at)

                        <div class="mb-3">

                            <small class="text-muted d-block">
                                Created
                            </small>

                            <span>
                                {{ $prospect->created_at
                                    ->format('d M Y, h:i A') }}
                            </span>

                        </div>

                    @endif


                    @if($prospect->updated_at)

                        <div>

                            <small class="text-muted d-block">
                                Last Updated
                            </small>

                            <span>
                                {{ $prospect->updated_at
                                    ->format('d M Y, h:i A') }}
                            </span>

                        </div>

                    @endif

                </div>

            </div>


            {{-- =================================================
                ADD ACTIVITY
            ================================================== --}}
            @can('prospects.activity')

                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white py-3">

                        <h5 class="mb-0">
                            <i class="ri-add-circle-line me-1"></i>
                            Add Activity
                        </h5>

                    </div>

                    <div class="card-body">

                        <form method="POST"
                              action="{{ route(
                                'admin.leasing.prospects.activities.store',
                                $prospect
                              ) }}">

                            @csrf


                            {{-- Activity Type --}}
                            <div class="mb-3">

                                <label class="form-label">
                                    Activity Type
                                    <span class="text-danger">*</span>
                                </label>

                                <select name="activity_type"
                                        class="form-select"
                                        required>

                                    <option value="CALL">
                                        Call
                                    </option>

                                    <option value="EMAIL">
                                        Email
                                    </option>

                                    <option value="MEETING">
                                        Meeting
                                    </option>

                                    <option value="WHATSAPP">
                                        WhatsApp
                                    </option>

                                    <option value="SITE_VISIT">
                                        Site Visit
                                    </option>

                                    <option value="FOLLOW_UP">
                                        Follow-up
                                    </option>

                                    <option value="NOTE">
                                        Note
                                    </option>

                                    <option value="PROPOSAL">
                                        Proposal
                                    </option>

                                    <option value="NEGOTIATION">
                                        Negotiation
                                    </option>

                                    <option value="DOCUMENT">
                                        Document
                                    </option>

                                    <option value="OTHER">
                                        Other
                                    </option>

                                </select>

                            </div>


                            {{-- Subject --}}
                            <div class="mb-3">

                                <label class="form-label">
                                    Subject
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="text"
                                       name="subject"
                                       class="form-control"
                                       value="{{ old('subject') }}"
                                       maxlength="255"
                                       required>

                            </div>


                            {{-- Activity Date --}}
                            <div class="mb-3">

                                <label class="form-label">
                                    Activity Date
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="datetime-local"
                                       name="activity_date"
                                       class="form-control"
                                       value="{{ old(
                                           'activity_date',
                                           now()->format('Y-m-d\TH:i')
                                       ) }}"
                                       required>

                            </div>


                            {{-- Description --}}
                            <div class="mb-3">

                                <label class="form-label">
                                    Description
                                </label>

                                <textarea name="description"
                                          rows="3"
                                          class="form-control">{{ old('description') }}</textarea>

                            </div>


                            {{-- Outcome --}}
                            <div class="mb-3">

                                <label class="form-label">
                                    Outcome
                                </label>

                                <input type="text"
                                       name="outcome"
                                       class="form-control"
                                       value="{{ old('outcome') }}"
                                       maxlength="255">

                            </div>


                            {{-- Next Action --}}
                            <div class="mb-3">

                                <label class="form-label">
                                    Next Action
                                </label>

                                <input type="text"
                                       name="next_action"
                                       class="form-control"
                                       value="{{ old('next_action') }}"
                                       maxlength="255">

                            </div>


                            {{-- Next Action Date --}}
                            <div class="mb-3">

                                <label class="form-label">
                                    Next Action Date
                                </label>

                                <input type="datetime-local"
                                       name="next_action_date"
                                       class="form-control"
                                       value="{{ old('next_action_date') }}">

                            </div>


                            <button type="submit"
                                    class="btn btn-primary w-100">

                                <i class="ri-add-line me-1"></i>
                                Add Activity

                            </button>

                        </form>

                    </div>

                </div>

            @endcan


            {{-- =================================================
                CONVERT TO PIPELINE
            ================================================== --}}
            @if(!in_array(
                strtolower($prospect->status ?? ''),
                ['converted', 'lost', 'inactive']
            ))

                @can('pipeline.create')

                    <div class="card border-0 shadow-sm mb-4">

                        <div class="card-header bg-white py-3">

                            <h5 class="mb-0">
                                <i class="ri-arrow-right-up-line me-1"></i>
                                Convert to Pipeline
                            </h5>

                        </div>

                        <div class="card-body">

                            <p class="text-muted small">
                                Create a leasing pipeline opportunity
                                from this lead / prospect.
                            </p>

                            <form method="POST"
                                  action="{{ route(
                                    'admin.leasing.prospects.convert',
                                    $prospect
                                  ) }}">

                                @csrf

                                @method('PATCH')


                                {{-- Stage --}}
                                <div class="mb-3">

                                    <label class="form-label">
                                        Stage
                                    </label>

                                    <select name="stage"
                                            class="form-select">

                                        <option value="prospect">
                                            Prospect
                                        </option>

                                        <option value="qualified">
                                            Qualified
                                        </option>

                                        <option value="site_visit">
                                            Site Visit
                                        </option>

                                        <option value="negotiation">
                                            Negotiation
                                        </option>

                                        <option value="agreement">
                                            Agreement
                                        </option>

                                    </select>

                                </div>


                                {{-- Probability --}}
                                <div class="mb-3">

                                    <label class="form-label">
                                        Probability (%)
                                    </label>

                                    <input type="number"
                                           name="probability"
                                           value="{{ old('probability', 30) }}"
                                           min="0"
                                           max="100"
                                           step="1"
                                           class="form-control">

                                </div>


                                <button type="submit"
                                        class="btn btn-success w-100">

                                    <i class="ri-arrow-right-up-line me-1"></i>
                                    Convert to Pipeline

                                </button>

                            </form>

                        </div>

                    </div>

                @endcan

            @endif


            {{-- =================================================
                DELETE
            ================================================== --}}
            @can('prospects.delete')

                <div class="card border-0 shadow-sm">

                    <div class="card-body">

                        <form method="POST"
                              action="{{ route(
                                'admin.leasing.prospects.destroy',
                                $prospect
                              ) }}"
                              onsubmit="return confirm(
                                  'Are you sure you want to delete this lead / prospect?'
                              );">

                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                    class="btn btn-outline-danger w-100">

                                <i class="ri-delete-bin-line me-1"></i>
                                Delete Lead / Prospect

                            </button>

                        </form>

                    </div>

                </div>

            @endcan

        </div>

    </div>

</div>

@endsection