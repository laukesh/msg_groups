@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">Create Tender</h4>
            <div class="text-muted">
                Create a tender against a Procurement Package.
            </div>
        </div>

        <a href="{{ route(
            'admin.procurement.tenders.index'
        ) }}"
           class="btn btn-outline-secondary">
            Back
        </a>

    </div>


    @if($errors->any())

        <div class="alert alert-danger">

            <strong>Please correct the following:</strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

    @endif


    <form method="POST"
          action="{{ route(
              'admin.procurement.tenders.store'
          ) }}">

        @csrf

        <div class="card">

            <div class="card-header">
                <strong>Tender Details</strong>
            </div>

            <div class="card-body">

                <div class="row g-3">

                    {{-- Package --}}

                    <div class="col-12">

                        <label class="form-label">
                            Procurement Package
                            <span class="text-danger">*</span>
                        </label>

                        <select name="procurement_package_id"
                                class="form-select"
                                required>

                            <option value="">
                                Select Procurement Package
                            </option>

                            @foreach($packages as $package)

                                <option value="{{ $package->id }}"
                                    @selected(
                                        old(
                                            'procurement_package_id',
                                            $selectedPackageId
                                        ) == $package->id
                                    )>

                                    {{ $package->package_number }}
                                    -
                                    {{ $package->package_title }}

                                    @if(
                                        $package->procurementPlan?->project
                                    )
                                        |
                                        {{
                                            $package
                                                ->procurementPlan
                                                ->project
                                                ->project_name
                                        }}
                                    @endif

                                </option>

                            @endforeach

                        </select>

                    </div>

                    {{-- Tender Number --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            Tender Number
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="Auto-generated"
                            readonly
                        >

                        <div class="form-text">
                            Tender number will be generated automatically after saving.
                        </div>

                    </div>


                    {{-- Tender Type --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            Tender Type
                        </label>

                        <select name="tender_type"
                                class="form-select">

                            <option value="">
                                Select Type
                            </option>

                            @foreach([
                                'Open Tender',
                                'Limited Tender',
                                'Request for Quotation',
                                'Single Source',
                                'Two Stage Tender',
                                'Other',
                            ] as $type)

                                <option value="{{ $type }}"
                                    @selected(
                                        old('tender_type')
                                        === $type
                                    )>
                                    {{ $type }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Method --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            Procurement Method
                        </label>

                        <input type="text"
                               name="procurement_method"
                               class="form-control"
                               value="{{ old(
                                   'procurement_method'
                               ) }}"
                               maxlength="100">

                    </div>


                    {{-- Title --}}

                    <div class="col-12">

                        <label class="form-label">
                            Tender Title
                            <span class="text-danger">*</span>
                        </label>

                        <input type="text"
                               name="tender_title"
                               class="form-control"
                               value="{{ old('tender_title') }}"
                               maxlength="255"
                               required>

                    </div>


                    {{-- Financial --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            Estimated Value
                        </label>

                        <input type="number"
                               name="estimated_value"
                               class="form-control"
                               step="0.01"
                               min="0"
                               value="{{ old(
                                   'estimated_value',
                                   '0.00'
                               ) }}">

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Tender Fee
                        </label>

                        <input type="number"
                               name="tender_fee"
                               class="form-control"
                               step="0.01"
                               min="0"
                               value="{{ old(
                                   'tender_fee',
                                   '0.00'
                               ) }}">

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            EMD Amount
                        </label>

                        <input type="number"
                               name="emd_amount"
                               class="form-control"
                               step="0.01"
                               min="0"
                               value="{{ old(
                                   'emd_amount',
                                   '0.00'
                               ) }}">

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Currency
                        </label>

                        <input type="text"
                               name="currency"
                               class="form-control"
                               maxlength="10"
                               value="{{ old(
                                   'currency',
                                   'INR'
                               ) }}"
                               required>

                    </div>


                    {{-- Dates --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            Issue Date
                        </label>

                        <input type="date"
                               name="issue_date"
                               class="form-control"
                               value="{{ old('issue_date') }}">

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Submission Start Date
                        </label>

                        <input type="date"
                               name="submission_start_date"
                               class="form-control"
                               value="{{ old(
                                   'submission_start_date'
                               ) }}">

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Submission Deadline
                        </label>

                        <input type="date"
                               name="submission_deadline"
                               class="form-control"
                               value="{{ old(
                                   'submission_deadline'
                               ) }}">

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Opening Date
                        </label>

                        <input type="date"
                               name="opening_date"
                               class="form-control"
                               value="{{ old(
                                   'opening_date'
                               ) }}">

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Technical Evaluation Date
                        </label>

                        <input type="date"
                               name="technical_evaluation_date"
                               class="form-control"
                               value="{{ old(
                                   'technical_evaluation_date'
                               ) }}">

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Commercial Evaluation Date
                        </label>

                        <input type="date"
                               name="commercial_evaluation_date"
                               class="form-control"
                               value="{{ old(
                                   'commercial_evaluation_date'
                               ) }}">

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Planned Award Date
                        </label>

                        <input type="date"
                               name="planned_award_date"
                               class="form-control"
                               value="{{ old(
                                   'planned_award_date'
                               ) }}">

                    </div>


                    {{-- Prequalification --}}

                    <div class="col-md-4">

                        <label class="form-label d-block">
                            Prequalification
                        </label>

                        <div class="form-check form-switch mt-2">

                            <input
                                type="hidden"
                                name="prequalification_required"
                                value="0"
                            >

                            <input
                                class="form-check-input"
                                type="checkbox"
                                name="prequalification_required"
                                value="1"
                                @checked(
                                    old(
                                        'prequalification_required'
                                    )
                                )
                            >

                            <label class="form-check-label">
                                Prequalification Required
                            </label>

                        </div>

                    </div>


                    {{-- Description --}}

                    <div class="col-md-6">

                        <label class="form-label">
                            Description
                        </label>

                        <textarea name="description"
                                  rows="5"
                                  class="form-control">{{ old(
                                      'description'
                                  ) }}</textarea>

                    </div>


                    {{-- Scope --}}

                    <div class="col-md-6">

                        <label class="form-label">
                            Scope of Work
                        </label>

                        <textarea name="scope_of_work"
                                  rows="5"
                                  class="form-control">{{ old(
                                      'scope_of_work'
                                  ) }}</textarea>

                    </div>


                    {{-- Terms --}}

                    <div class="col-12">

                        <label class="form-label">
                            Terms & Conditions
                        </label>

                        <textarea name="terms_and_conditions"
                                  rows="5"
                                  class="form-control">{{ old(
                                      'terms_and_conditions'
                                  ) }}</textarea>

                    </div>


                    {{-- Responsible --}}

                    <div class="col-md-6">

                        <label class="form-label">
                            Responsible User
                        </label>

                        <select name="responsible_user_id"
                                class="form-select">

                            <option value="">
                                Select User
                            </option>

                            @foreach($users as $user)

                                <option value="{{ $user->id }}"
                                    @selected(
                                        old(
                                            'responsible_user_id'
                                        ) == $user->id
                                    )>
                                    {{ $user->name }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">
                            Responsible Name
                        </label>

                        <input type="text"
                               name="responsible_name"
                               class="form-control"
                               value="{{ old(
                                   'responsible_name'
                               ) }}"
                               maxlength="255">

                    </div>


                    {{-- Remarks --}}

                    <div class="col-12">

                        <label class="form-label">
                            Remarks
                        </label>

                        <textarea name="remarks"
                                  rows="4"
                                  class="form-control">{{ old(
                                      'remarks'
                                  ) }}</textarea>

                    </div>

                </div>

            </div>

            <div class="card-footer d-flex justify-content-end gap-2">

                <a href="{{ route(
                    'admin.procurement.tenders.index'
                ) }}"
                   class="btn btn-outline-secondary">
                    Cancel
                </a>

                <button type="submit"
                        class="btn btn-primary">
                    Create Tender
                </button>

            </div>

        </div>

    </form>

</div>

@endsection