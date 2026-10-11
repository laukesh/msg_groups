@extends('layouts.app')

@section('title', 'Create Lead / Prospect')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                <i class="ri-user-search-line me-1"></i>
                Create Lead / Prospect
            </h4>

            <p class="text-muted mb-0">
                Add a potential tenant or occupier.
            </p>
        </div>

        <a href="{{ route('admin.leasing.prospects.index') }}"
           class="btn btn-light">
            <i class="ri-arrow-left-line"></i>
            Back
        </a>

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
          action="{{ route('admin.leasing.prospects.store') }}">

        @csrf

        <div class="row">

            {{-- Contact --}}
            <div class="col-lg-8">

                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white">
                        <h5 class="mb-0">
                            Contact Information
                        </h5>
                    </div>

                    <div class="card-body">

                        <div class="row g-3">

                            <div class="col-md-6">
                                <label class="form-label">
                                    Company
                                </label>

                                <input type="text"
                                       name="company_name"
                                       class="form-control"
                                       value="{{ old('company_name') }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">
                                    Contact Name *
                                </label>

                                <input type="text"
                                       name="contact_name"
                                       class="form-control"
                                       value="{{ old('contact_name') }}"
                                       required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">
                                    Designation
                                </label>

                                <input type="text"
                                       name="designation"
                                       class="form-control"
                                       value="{{ old('designation') }}">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">
                                    Email
                                </label>

                                <input type="email"
                                       name="email"
                                       class="form-control"
                                       value="{{ old('email') }}">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">
                                    Phone
                                </label>

                                <input type="text"
                                       name="phone"
                                       class="form-control"
                                       value="{{ old('phone') }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">
                                    Brand
                                </label>

                                <input type="text"
                                       name="brand_name"
                                       class="form-control"
                                       value="{{ old('brand_name') }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">
                                    Category
                                </label>

                                <input type="text"
                                       name="category"
                                       class="form-control"
                                       value="{{ old('category') }}"
                                       placeholder="Retail, F&B, Fashion...">
                            </div>

                        </div>

                    </div>
                </div>

                {{-- Requirements --}}
                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white">
                        <h5 class="mb-0">
                            Space Requirements
                        </h5>
                    </div>

                    <div class="card-body">

                        <div class="row g-3">

                            <div class="col-md-6">
                                <label class="form-label">
                                    Area Required
                                </label>

                                <input type="number"
                                       step="0.01"
                                       min="0"
                                       name="area_required"
                                       class="form-control"
                                       value="{{ old('area_required') }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">
                                    Area Unit
                                </label>

                                <select name="area_unit"
                                        class="form-select">
                                    <option value="">Select</option>
                                    <option value="sqft">Sq. Ft.</option>
                                    <option value="sqm">Sq. M.</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">
                                    Minimum Budget
                                </label>

                                <input type="number"
                                       step="0.01"
                                       name="budget_min"
                                       class="form-control"
                                       value="{{ old('budget_min') }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">
                                    Maximum Budget
                                </label>

                                <input type="number"
                                       step="0.01"
                                       name="budget_max"
                                       class="form-control"
                                       value="{{ old('budget_max') }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">
                                    Budget Period
                                </label>

                                <select name="budget_period"
                                        class="form-select">

                                    <option value="">
                                        Select
                                    </option>

                                    <option value="monthly">
                                        Monthly
                                    </option>

                                    <option value="quarterly">
                                        Quarterly
                                    </option>

                                    <option value="yearly">
                                        Yearly
                                    </option>

                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">
                                    Preferred Location
                                </label>

                                <input type="text"
                                       name="preferred_location"
                                       class="form-control"
                                       value="{{ old('preferred_location') }}"
                                       placeholder="Building / Floor / Zone">
                            </div>

                        </div>

                    </div>
                </div>

                {{-- Notes --}}
                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white">
                        <h5 class="mb-0">
                            Notes
                        </h5>
                    </div>

                    <div class="card-body">

                        <textarea name="notes"
                                  rows="5"
                                  class="form-control"
                                  placeholder="Requirements, preferences, discussions...">{{ old('notes') }}</textarea>

                    </div>
                </div>

            </div>

            {{-- CRM --}}
            <div class="col-lg-4">

                <div class="card border-0 shadow-sm">

                    <div class="card-header bg-white">
                        <h5 class="mb-0">
                            CRM Details
                        </h5>
                    </div>

                    <div class="card-body">

                        <div class="mb-3">

                            <label class="form-label">
                                Source
                            </label>

                            <select name="source"
                                    class="form-select">

                                <option value="">Select Source</option>
                                <option value="website">Website</option>
                                <option value="referral">Referral</option>
                                <option value="walk_in">Walk-in</option>
                                <option value="broker">Broker</option>
                                <option value="social_media">Social Media</option>
                                <option value="event">Event</option>
                                <option value="existing_tenant">Existing Tenant</option>
                                <option value="other">Other</option>

                            </select>

                        </div>
                        
                        <div class="mb-3">

                            <label class="form-label">
                                Status *
                            </label>

                            <select name="status"
                                    class="form-select"
                                    required>

                                <option value="lead">Lead</option>
                                <option value="prospect">Prospect</option>
                                <option value="qualified">Qualified</option>
                                <option value="site_visit">Site Visit</option>
                                <option value="negotiation">Negotiation</option>
                                <option value="converted">Converted</option>
                                <option value="lost">Lost</option>
                                <option value="inactive">Inactive</option>

                            </select>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">
                                Priority
                            </label>

                            <select name="priority"
                                    class="form-select">

                                <option value="low">Low</option>
                                <option value="medium"
                                        selected>
                                    Medium
                                </option>
                                <option value="high">High</option>
                                <option value="urgent">Urgent</option>

                            </select>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">
                                Owner
                            </label>

                            <select name="owner_id"
                                    class="form-select">

                                <option value="">
                                    Select Owner
                                </option>

                                @foreach($owners as $owner)
                                    <option value="{{ $owner->id }}">
                                        {{ $owner->name }}
                                    </option>
                                @endforeach

                            </select>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">
                                Next Follow-up
                            </label>

                            <input type="date"
                                   name="next_follow_up"
                                   class="form-control"
                                   value="{{ old('next_follow_up') }}">

                        </div>

                        <button type="submit"
                                class="btn btn-primary w-100">

                            <i class="ri-save-line me-1"></i>
                            Save Lead / Prospect

                        </button>

                    </div>
                </div>

            </div>

        </div>

    </form>

</div>

@endsection