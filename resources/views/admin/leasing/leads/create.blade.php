@extends('layouts.app')

@section('title', 'Add Lead')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                <i class="ri-user-add-line me-1"></i>
                Add Lead
            </h4>

            <p class="text-muted mb-0">
                Create a potential tenant or occupier record.
            </p>

        </div>

        <a href="{{ route('admin.leasing.leads.index') }}"
           class="btn btn-light border">

            <i class="ri-arrow-left-line me-1"></i>
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
          action="{{ route('admin.leasing.leads.store') }}">

        @csrf


        {{-- Company --}}
        <div class="card shadow-sm border-0 mb-4">

            <div class="card-header bg-white">

                <strong>
                    <i class="ri-building-line me-1"></i>
                    Company & Contact
                </strong>

            </div>


            <div class="card-body">

                <div class="row g-3">

                    <div class="col-md-6">

                        <label class="form-label">
                            Company Name <span class="text-danger">*</span>
                        </label>

                        <input type="text"
                               name="company_name"
                               class="form-control @error('company_name') is-invalid @enderror"
                               value="{{ old('company_name') }}"
                               required>

                        @error('company_name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">
                            Contact Person
                        </label>

                        <input type="text"
                               name="contact_name"
                               class="form-control"
                               value="{{ old('contact_name') }}">

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">
                            Email
                        </label>

                        <input type="email"
                               name="email"
                               class="form-control"
                               value="{{ old('email') }}">

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">
                            Phone
                        </label>

                        <input type="text"
                               name="phone"
                               class="form-control"
                               value="{{ old('phone') }}">

                    </div>

                </div>

            </div>

        </div>


        {{-- Business --}}
        <div class="card shadow-sm border-0 mb-4">

            <div class="card-header bg-white">

                <strong>
                    <i class="ri-store-2-line me-1"></i>
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
                               placeholder="Fashion, Food, Electronics...">

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Area Required
                        </label>

                        <div class="input-group">

                            <input type="number"
                                   name="area_need"
                                   class="form-control"
                                   value="{{ old('area_need') }}"
                                   min="0"
                                   step="0.01">

                            <span class="input-group-text">
                                Sq. Ft.
                            </span>

                        </div>

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Budget
                        </label>

                        <input type="number"
                               name="budget"
                               class="form-control"
                               value="{{ old('budget') }}"
                               min="0"
                               step="0.01">

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Preferred Location
                        </label>

                        <input type="text"
                               name="preferred_location"
                               class="form-control"
                               value="{{ old('preferred_location') }}">

                    </div>

                </div>

            </div>

        </div>


        {{-- Lead --}}
        <div class="card shadow-sm border-0 mb-4">

            <div class="card-header bg-white">

                <strong>
                    <i class="ri-flow-chart me-1"></i>
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
                                    {{ old('source') == $source ? 'selected' : '' }}>

                                    {{ $source }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Status <span class="text-danger">*</span>
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
                                    {{ old('status', 'NEW') == $status ? 'selected' : '' }}>

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
                               value="{{ old('next_follow_up_at') }}">

                    </div>


                    <div class="col-12">

                        <label class="form-label">
                            Notes
                        </label>

                        <textarea name="notes"
                                  rows="4"
                                  class="form-control"
                                  placeholder="Lead requirements, discussions, preferences...">{{ old('notes') }}</textarea>

                    </div>

                </div>

            </div>

        </div>


        <div class="d-flex justify-content-end gap-2">

            <a href="{{ route('admin.leasing.leads.index') }}"
               class="btn btn-light border">

                Cancel

            </a>

            <button type="submit"
                    class="btn btn-primary">

                <i class="ri-save-line me-1"></i>
                Save Lead

            </button>

        </div>

    </form>

</div>

@endsection