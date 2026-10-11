@extends('layouts.app')

@section('title', 'Edit Site Visit')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                <i class="ri-edit-line me-1"></i>
                Edit Site Visit
            </h4>

            <p class="text-muted mb-0">
                Update visit schedule and outcome.
            </p>

        </div>

        <div class="d-flex gap-2">

            <a href="{{ route('admin.leasing.site-visits.show', $siteVisit) }}"
               class="btn btn-outline-primary">

                <i class="ri-eye-line me-1"></i>
                View

            </a>

            <a href="{{ route('admin.leasing.site-visits.index') }}"
               class="btn btn-light border">

                Back

            </a>

        </div>

    </div>


    @if($errors->any())

        <div class="alert alert-danger">

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    <form method="POST"
          action="{{ route('admin.leasing.site-visits.update', $siteVisit) }}">

        @csrf
        @method('PUT')


        <div class="card shadow-sm border-0 mb-4">

            <div class="card-header bg-white">

                <strong>
                    Visit Details
                </strong>

            </div>


            <div class="card-body">

                <div class="row g-3">

                    <div class="col-md-4">

                        <label class="form-label">
                            Prospect *
                        </label>

                        <select name="prospect_id"
                                class="form-select"
                                required>

                            @foreach($prospects as $prospect)

                                <option value="{{ $prospect->id }}"
                                    @selected(old('prospect_id', $siteVisit->prospect_id) == $prospect->id)>

                                    {{ $prospect->company_name }}

                                    @if($prospect->brand_name)
                                        - {{ $prospect->brand_name }}
                                    @endif

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Unit *
                        </label>

                        <select name="unit_id"
                                class="form-select"
                                required>

                            @foreach($units as $unit)

                                <option value="{{ $unit->id }}"
                                    @selected(old('unit_id', $siteVisit->unit_id) == $unit->id)>

                                    {{ $unit->unit_no }}

                                    @if(isset($unit->shop_name) && $unit->shop_name)
                                        - {{ $unit->shop_name }}
                                    @endif

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Visit Date & Time *
                        </label>

                        <input type="datetime-local"
                               name="visit_date"
                               class="form-control"
                               value="{{ old(
                                   'visit_date',
                                   optional($siteVisit->visit_date)->format('Y-m-d\TH:i')
                               ) }}"
                               required>

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">
                            Attendees
                        </label>

                        <textarea name="attendees"
                                  rows="3"
                                  class="form-control">{{ old('attendees', $siteVisit->attendees) }}</textarea>

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">
                            Assigned Leasing Officer
                        </label>

                        <select name="assigned_to"
                                class="form-select">

                            <option value="">
                                Select Officer
                            </option>

                            @foreach($officers as $officer)

                                <option value="{{ $officer->id }}"
                                    @selected(old('assigned_to', $siteVisit->assigned_to) == $officer->id)>

                                    {{ $officer->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>

            </div>

        </div>


        <div class="card shadow-sm border-0 mb-4">

            <div class="card-header bg-white">

                <strong>
                    Visit Outcome
                </strong>

            </div>


            <div class="card-body">

                <div class="row g-3">

                    <div class="col-md-4">

                        <label class="form-label">
                            Status
                        </label>

                        <select name="status"
                                class="form-select">

                            @foreach([
                                'SCHEDULED',
                                'COMPLETED',
                                'CANCELLED',
                                'NO_SHOW',
                                'RESCHEDULED'
                            ] as $status)

                                <option value="{{ $status }}"
                                    @selected(old('status', $siteVisit->status) == $status)>

                                    {{ str_replace('_', ' ', $status) }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Interest
                        </label>

                        <select name="interest"
                                class="form-select">

                            <option value="">
                                Not Recorded
                            </option>

                            @foreach([
                                'HIGH',
                                'MEDIUM',
                                'LOW',
                                'NOT_INTERESTED'
                            ] as $interest)

                                <option value="{{ $interest }}"
                                    @selected(old('interest', $siteVisit->interest) == $interest)>

                                    {{ str_replace('_', ' ', $interest) }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Follow-up Date
                        </label>

                        <input type="datetime-local"
                               name="follow_up_date"
                               class="form-control"
                               value="{{ old(
                                   'follow_up_date',
                                   optional($siteVisit->follow_up_date)->format('Y-m-d\TH:i')
                               ) }}">

                    </div>


                    <div class="col-md-12">

                        <label class="form-label">
                            Outcome
                        </label>

                        <input type="text"
                               name="outcome"
                               class="form-control"
                               value="{{ old('outcome', $siteVisit->outcome) }}">

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">
                            Feedback
                        </label>

                        <textarea name="feedback"
                                  rows="4"
                                  class="form-control">{{ old('feedback', $siteVisit->feedback) }}</textarea>

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">
                            Notes
                        </label>

                        <textarea name="notes"
                                  rows="4"
                                  class="form-control">{{ old('notes', $siteVisit->notes) }}</textarea>

                    </div>

                </div>

            </div>

        </div>


        <div class="d-flex justify-content-end gap-2">

            <a href="{{ route('admin.leasing.site-visits.show', $siteVisit) }}"
               class="btn btn-light border">

                Cancel

            </a>

            <button type="submit"
                    class="btn btn-primary">

                <i class="ri-save-line me-1"></i>
                Update Visit

            </button>

        </div>

    </form>

</div>

@endsection