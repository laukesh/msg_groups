@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">Edit Tender</h4>

            <div class="text-muted">
                {{ $procurementTender->tender_number }}
            </div>
        </div>

        <a href="{{ route(
            'admin.procurement.tenders.show',
            $procurementTender
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
              'admin.procurement.tenders.update',
              $procurementTender
          ) }}">

        @csrf
        @method('PUT')


        <div class="card">

            <div class="card-header">
                <strong>Tender Details</strong>
            </div>

            <div class="card-body">

                <div class="row g-3">

                    <div class="col-12">

                        <label class="form-label">
                            Procurement Package
                            <span class="text-danger">*</span>
                        </label>

                        <select name="procurement_package_id"
                                class="form-select"
                                required>

                            @foreach($packages as $package)

                                <option value="{{ $package->id }}"
                                    @selected(
                                        old(
                                            'procurement_package_id',
                                            $procurementTender
                                                ->procurement_package_id
                                        ) == $package->id
                                    )>

                                    {{ $package->package_number }}
                                    -
                                    {{ $package->package_title }}

                                    @if($package->procurementPlan?->project)
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
                            value="{{ $procurementTender->tender_number }}"
                            readonly
                        >

                        <div class="form-text">
                            Tender number cannot be changed.
                        </div>

                    </div>


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
                                        old(
                                            'tender_type',
                                            $procurementTender->tender_type
                                        ) === $type
                                    )>
                                    {{ $type }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Procurement Method
                        </label>

                        <input type="text"
                               name="procurement_method"
                               class="form-control"
                               maxlength="100"
                               value="{{ old(
                                   'procurement_method',
                                   $procurementTender
                                       ->procurement_method
                               ) }}">

                    </div>


                    <div class="col-12">

                        <label class="form-label">
                            Tender Title
                            <span class="text-danger">*</span>
                        </label>

                        <input type="text"
                               name="tender_title"
                               class="form-control"
                               maxlength="255"
                               value="{{ old(
                                   'tender_title',
                                   $procurementTender->tender_title
                               ) }}"
                               required>

                    </div>


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
                                   $procurementTender->estimated_value
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
                                   $procurementTender->tender_fee
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
                                   $procurementTender->emd_amount
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
                                   $procurementTender->currency
                               ) }}"
                               required>

                    </div>


                    @php
                        $dateFields = [
                            'issue_date' =>
                                'Issue Date',

                            'submission_start_date' =>
                                'Submission Start Date',

                            'submission_deadline' =>
                                'Submission Deadline',

                            'opening_date' =>
                                'Opening Date',

                            'technical_evaluation_date' =>
                                'Technical Evaluation Date',

                            'commercial_evaluation_date' =>
                                'Commercial Evaluation Date',

                            'planned_award_date' =>
                                'Planned Award Date',
                        ];
                    @endphp


                    @foreach($dateFields as $field => $label)

                        <div class="col-md-4">

                            <label class="form-label">
                                {{ $label }}
                            </label>

                            <input type="date"
                                   name="{{ $field }}"
                                   class="form-control"
                                   value="{{ old(
                                       $field,
                                       optional(
                                           $procurementTender->{$field}
                                       )->format('Y-m-d')
                                   ) }}">

                        </div>

                    @endforeach


                    <div class="col-md-4">

                        <label class="form-label d-block">
                            Prequalification
                        </label>

                        <div class="form-check form-switch mt-2">

                            <input type="hidden"
                                   name="prequalification_required"
                                   value="0">

                            <input class="form-check-input"
                                   type="checkbox"
                                   name="prequalification_required"
                                   value="1"
                                   @checked(
                                       old(
                                           'prequalification_required',
                                           $procurementTender
                                               ->prequalification_required
                                       )
                                   )>

                            <label class="form-check-label">
                                Required
                            </label>

                        </div>

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">
                            Description
                        </label>

                        <textarea name="description"
                                  rows="5"
                                  class="form-control">{{ old(
                                      'description',
                                      $procurementTender->description
                                  ) }}</textarea>

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">
                            Scope of Work
                        </label>

                        <textarea name="scope_of_work"
                                  rows="5"
                                  class="form-control">{{ old(
                                      'scope_of_work',
                                      $procurementTender->scope_of_work
                                  ) }}</textarea>

                    </div>


                    <div class="col-12">

                        <label class="form-label">
                            Terms & Conditions
                        </label>

                        <textarea name="terms_and_conditions"
                                  rows="5"
                                  class="form-control">{{ old(
                                      'terms_and_conditions',
                                      $procurementTender
                                          ->terms_and_conditions
                                  ) }}</textarea>

                    </div>


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
                                            'responsible_user_id',
                                            $procurementTender
                                                ->responsible_user_id
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
                               maxlength="255"
                               value="{{ old(
                                   'responsible_name',
                                   $procurementTender
                                       ->responsible_name
                               ) }}">

                    </div>


                    <div class="col-12">

                        <label class="form-label">
                            Remarks
                        </label>

                        <textarea name="remarks"
                                  rows="4"
                                  class="form-control">{{ old(
                                      'remarks',
                                      $procurementTender->remarks
                                  ) }}</textarea>

                    </div>

                </div>

            </div>


            <div class="card-footer d-flex justify-content-end gap-2">

                <a href="{{ route(
                    'admin.procurement.tenders.show',
                    $procurementTender
                ) }}"
                   class="btn btn-outline-secondary">
                    Cancel
                </a>

                <button type="submit"
                        class="btn btn-primary">
                    Update Tender
                </button>

            </div>

        </div>

    </form>

</div>

@endsection