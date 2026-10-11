@extends('layouts.app')

@section('title', 'Edit Lead')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                <i class="ri-edit-line me-1"></i>
                Edit Lead
            </h4>

            <p class="text-muted mb-0">
                Update lead and tenant requirements.
            </p>

        </div>

        <div class="d-flex gap-2">

            <a href="{{ route('admin.leasing.leads.show', $lead) }}"
               class="btn btn-outline-primary">

                <i class="ri-eye-line me-1"></i>
                View

            </a>

            <a href="{{ route('admin.leasing.leads.index') }}"
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
          action="{{ route('admin.leasing.leads.update', $lead) }}">

        @csrf
        @method('PUT')


        <div class="card shadow-sm border-0 mb-4">

            <div class="card-header bg-white">

                <strong>
                    Company & Contact
                </strong>

            </div>

            <div class="card-body">

                <div class="row g-3">

                    <div class="col-md-6">

                        <label class="form-label">
                            Company Name *
                        </label>

                        <input type="text"
                               name="company_name"
                               class="form-control"
                               value="{{ old('company_name', $lead->company_name) }}"
                               required>

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">
                            Contact Person
                        </label>

                        <input type="text"
                               name="contact_name"
                               class="form-control"
                               value="{{ old('contact_name', $lead->contact_name) }}">

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">
                            Email
                        </label>

                        <input type="email"
                               name="email"
                               class="form-control"
                               value="{{ old('email', $lead->email) }}">

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">
                            Phone
                        </label>

                        <input type="text"
                               name="phone"
                               class="form-control"
                               value="{{ old('phone', $lead->phone) }}">

                    </div>

                </div>

            </div>

        </div>


        <div class="card shadow-sm border-0 mb-4">

            <div class="card-header bg-white">

                <strong>
                    Business Requirements
                </strong>

            </div>

            <div class="card-body">

                <div class="row g-3">

                    <div class="col-md-6">

                        <label class="form-label">
                            Brand Name
                        </label>

                        <input type="text"
                               name="brand_name"
                               class="form-control"
                               value="{{ old('brand_name', $lead->brand_name) }}">

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">
                            Category
                        </label>

                        <input type="text"
                               name="category"
                               class="form-control"
                               value="{{ old('category', $lead->category) }}">

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Area Required
                        </label>

                        <input type="number"
                               name="area_need"
                               class="form-control"
                               min="0"
                               step="0.01"
                               value="{{ old('area_need', $lead->area_need) }}">

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Budget
                        </label>

                        <input type="number"
                               name="budget"
                               class="form-control"
                               min="0"
                               step="0.01"
                               value="{{ old('budget', $lead->budget) }}">

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Preferred Location
                        </label>

                        <input type="text"
                               name="preferred_location"
                               class="form-control"
                               value="{{ old('preferred_location', $lead->preferred_location) }}">

                    </div>

                </div>

            </div>

        </div>


        <div class="card shadow-sm border-0 mb-4">

            <div class="card-header bg-white">

                <strong>
                    Lead Information
                </strong>

            </div>

            <div class="card-body">

                <div class="row g-3">

                    <div class="col-md-4">

                        <label class="form-label">
                            Source
                        </label>

                        <select name="source"
                                class="form-select">

                            <option value="">
                                Select Source
                            </option>

                            @foreach([
                                'Website',
                                'Walk-in',
                                'Referral',
                                'Broker',
                                'Social Media',
                                'Advertisement',
                                'Existing Tenant',
                                'Other'
                            ] as $source)

                                <option value="{{ $source }}"
                                    {{ old('source', $lead->source) == $source ? 'selected' : '' }}>

                                    {{ $source }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Status *
                        </label>

                        <select name="status"
                                class="form-select"
                                required>

                            @foreach([
                                'NEW',
                                'CONTACTED',
                                'QUALIFIED',
                                'PROPOSAL',
                                'NEGOTIATION',
                                'CONVERTED',
                                'LOST',
                                'ON_HOLD'
                            ] as $status)

                                <option value="{{ $status }}"
                                    {{ old('status', $lead->status) == $status ? 'selected' : '' }}>

                                    {{ str_replace('_', ' ', $status) }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Next Follow-up
                        </label>

                        <input type="datetime-local"
                               name="next_follow_up_at"
                               class="form-control"
                               value="{{ old(
                                   'next_follow_up_at',
                                   $lead->next_follow_up_at
                                       ? $lead->next_follow_up_at->format('Y-m-d\TH:i')
                                       : ''
                               ) }}">

                    </div>


                    <div class="col-12">

                        <label class="form-label">
                            Notes
                        </label>

                        <textarea name="notes"
                                  rows="4"
                                  class="form-control">{{ old('notes', $lead->notes) }}</textarea>

                    </div>

                </div>

            </div>

        </div>


        <div class="d-flex justify-content-end gap-2">

            <a href="{{ route('admin.leasing.leads.show', $lead) }}"
               class="btn btn-light border">

                Cancel

            </a>

            <button type="submit"
                    class="btn btn-primary">

                <i class="ri-save-line me-1"></i>
                Update Lead

            </button>

        </div>

    </form>

</div>

@endsection