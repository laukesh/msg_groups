@extends('layouts.app')

@section('title', 'Edit Tax Configuration')

@section('content')

<div class="container-fluid py-3">

    {{-- =========================================================
         HEADER
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1 fw-bold">
                Edit Tax Configuration
            </h4>

            <p class="text-muted mb-0">
                Update tax configuration and applicability.
            </p>
        </div>

        <a href="{{ route(
            'admin.revenue.tax-configurations.index'
        ) }}"
           class="btn btn-outline-secondary">

            <i class="fas fa-arrow-left me-1"></i>

            Back to Tax Configurations

        </a>

    </div>


    {{-- =========================================================
         VALIDATION ERRORS
    ========================================================== --}}

    @if($errors->any())

        <div class="alert alert-danger">

            <strong>
                Please fix the following errors:
            </strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- =========================================================
         EDIT FORM
    ========================================================== --}}

    <div class="row justify-content-center">

        <div class="col-xl-7 col-lg-9">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white py-3">

                    <h5 class="mb-1">

                        <i class="fas fa-percentage
                                  text-primary
                                  me-2"></i>

                        Tax Configuration

                    </h5>

                    <small class="text-muted">

                        Update the selected tax configuration.

                    </small>

                </div>


                <div class="card-body">

                    <form method="POST"
                          action="{{ route(
                              'admin.revenue.tax-configurations.update',
                              $taxConfiguration->id
                          ) }}">

                        @csrf

                        @method('PUT')


                        {{-- =================================================
                             CHARGE TYPE
                        ================================================== --}}

                        <div class="mb-3">

                            <label class="form-label">

                                Charge Type
                                <span class="text-danger">*</span>

                            </label>

                            <select name="charge_type_id"
                                    class="form-select"
                                    required>

                                <option value="">
                                    Select Charge Type
                                </option>

                                @foreach(
                                    $chargeTypes as $chargeType
                                )

                                    <option
                                        value="{{ $chargeType->id }}"
                                        @selected(
                                            old(
                                                'charge_type_id',
                                                $taxConfiguration
                                                    ->charge_type_id
                                            ) == $chargeType->id
                                        )>

                                        {{ $chargeType->charge_name }}
                                        ({{ $chargeType->charge_code }})

                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- =================================================
                             TAX NAME
                        ================================================== --}}

                        <div class="mb-3">

                            <label class="form-label">

                                Tax Name
                                <span class="text-danger">*</span>

                            </label>

                            <input type="text"
                                   name="tax_name"
                                   value="{{ old(
                                       'tax_name',
                                       $taxConfiguration->tax_name
                                   ) }}"
                                   class="form-control"
                                   maxlength="100"
                                   required>

                        </div>


                        {{-- =================================================
                             TAX TYPE
                        ================================================== --}}

                        <div class="mb-3">

                            <label class="form-label">

                                Tax Type
                                <span class="text-danger">*</span>

                            </label>

                            <select name="tax_type"
                                    class="form-select"
                                    required>

                                @foreach([
                                    'GST',
                                    'CGST',
                                    'SGST',
                                    'IGST',
                                    'VAT',
                                    'Service Tax'
                                ] as $taxType)

                                    <option value="{{ $taxType }}"
                                        @selected(
                                            old(
                                                'tax_type',
                                                $taxConfiguration
                                                    ->tax_type
                                            ) === $taxType
                                        )>

                                        {{ $taxType }}

                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- =================================================
                             HSN / SAC
                        ================================================== --}}

                        <div class="mb-3">

                            <label class="form-label">

                                HSN / SAC Code

                            </label>

                            <input type="text"
                                   name="hsn_sac_code"
                                   value="{{ old(
                                       'hsn_sac_code',
                                       $taxConfiguration
                                           ->hsn_sac_code
                                   ) }}"
                                   class="form-control"
                                   maxlength="20"
                                   placeholder="Enter HSN/SAC code">

                        </div>


                        {{-- =================================================
                             TAX PERCENTAGE
                        ================================================== --}}

                        <div class="mb-3">

                            <label class="form-label">

                                Tax Percentage
                                <span class="text-danger">*</span>

                            </label>

                            <div class="input-group">

                                <input type="number"
                                       name="tax_percentage"
                                       value="{{ old(
                                           'tax_percentage',
                                           $taxConfiguration
                                               ->tax_percentage
                                       ) }}"
                                       class="form-control"
                                       min="0"
                                       max="100"
                                       step="0.01"
                                       required>

                                <span class="input-group-text">
                                    %
                                </span>

                            </div>

                        </div>


                        {{-- =================================================
                             EFFECTIVE FROM
                        ================================================== --}}

                        <div class="mb-3">

                            <label class="form-label">

                                Effective From
                                <span class="text-danger">*</span>

                            </label>

                            <input type="date"
                                   name="effective_from"
                                   value="{{ old(
                                       'effective_from',
                                       optional(
                                           $taxConfiguration
                                               ->effective_from
                                       )->format('Y-m-d')
                                   ) }}"
                                   class="form-control"
                                   required>

                        </div>


                        {{-- =================================================
                             EFFECTIVE TO
                        ================================================== --}}

                        <div class="mb-3">

                            <label class="form-label">

                                Effective To

                            </label>

                            <input type="date"
                                   name="effective_to"
                                   value="{{ old(
                                       'effective_to',
                                       optional(
                                           $taxConfiguration
                                               ->effective_to
                                       )->format('Y-m-d')
                                   ) }}"
                                   class="form-control">

                            <small class="text-muted">

                                Leave blank for an open-ended
                                configuration.

                            </small>

                        </div>


                        {{-- =================================================
                             DEFAULT
                        ================================================== --}}

                        <div class="mb-3">

                            <label class="form-label">

                                Default
                                <span class="text-danger">*</span>

                            </label>

                            <select name="is_default"
                                    class="form-select"
                                    required>

                                <option value="Yes"
                                    @selected(
                                        old(
                                            'is_default',
                                            $taxConfiguration
                                                ->is_default
                                        ) === 'Yes'
                                    )>

                                    Yes

                                </option>

                                <option value="No"
                                    @selected(
                                        old(
                                            'is_default',
                                            $taxConfiguration
                                                ->is_default
                                        ) === 'No'
                                    )>

                                    No

                                </option>

                            </select>

                        </div>


                        {{-- =================================================
                             STATUS
                        ================================================== --}}

                        <div class="mb-3">

                            <label class="form-label">

                                Status
                                <span class="text-danger">*</span>

                            </label>

                            <select name="status"
                                    class="form-select"
                                    required>

                                <option value="Active"
                                    @selected(
                                        old(
                                            'status',
                                            $taxConfiguration->status
                                        ) === 'Active'
                                    )>

                                    Active

                                </option>

                                <option value="Inactive"
                                    @selected(
                                        old(
                                            'status',
                                            $taxConfiguration->status
                                        ) === 'Inactive'
                                    )>

                                    Inactive

                                </option>

                            </select>

                        </div>


                        {{-- =================================================
                             REMARKS
                        ================================================== --}}

                        <div class="mb-4">

                            <label class="form-label">

                                Remarks

                            </label>

                            <textarea name="remarks"
                                      rows="4"
                                      class="form-control"
                                      placeholder="Optional remarks">{{ old(
                                          'remarks',
                                          $taxConfiguration->remarks
                                      ) }}</textarea>

                        </div>


                        {{-- =================================================
                             ACTIONS
                        ================================================== --}}

                        <div class="d-flex
                                    justify-content-end
                                    gap-2">

                            <a href="{{ route(
                                'admin.revenue.tax-configurations.index'
                            ) }}"
                               class="btn btn-secondary">

                                Cancel

                            </a>


                            <button type="submit"
                                    class="btn btn-primary">

                                <i class="fas fa-save me-1"></i>

                                Update Tax Configuration

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection