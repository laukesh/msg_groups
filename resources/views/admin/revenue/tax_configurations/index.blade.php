@extends('layouts.app')

@section('title', 'Tax Configurations')

@section('content')

<div class="container-fluid py-3">

    {{-- =========================================================
         HEADER
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1 fw-bold">
                Tax Configurations
            </h4>

            <p class="text-muted mb-0">
                Manage taxes applicable to different charge types.
            </p>
        </div>

        <a href="{{ url()->previous() }}"
           class="btn btn-outline-secondary">

            <i class="fas fa-arrow-left me-1"></i>

            Back

        </a>

    </div>


    {{-- =========================================================
         SUCCESS MESSAGE
    ========================================================== --}}

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            <i class="fas fa-check-circle me-1"></i>

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


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

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    <div class="row g-4">


        {{-- =====================================================
             ADD TAX CONFIGURATION
        ====================================================== --}}

        <div class="col-xl-4">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white py-3">

                    <h5 class="mb-1">

                        <i class="fas fa-percentage
                                  text-primary
                                  me-2"></i>

                        Add Tax Configuration

                    </h5>

                    <small class="text-muted">

                        Configure a tax for a charge type.

                    </small>

                </div>


                <div class="card-body">

                    <form method="POST"
                          action="{{ route(
                              'admin.revenue.tax-configurations.store'
                          ) }}">

                        @csrf


                        {{-- CHARGE TYPE --}}

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
                                                'charge_type_id'
                                            ) == $chargeType->id
                                        )>

                                        {{ $chargeType->charge_name }}
                                        ({{ $chargeType->charge_code }})

                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- TAX NAME --}}

                        <div class="mb-3">

                            <label class="form-label">

                                Tax Name
                                <span class="text-danger">*</span>

                            </label>

                            <input type="text"
                                   name="tax_name"
                                   value="{{ old('tax_name') }}"
                                   class="form-control"
                                   maxlength="100"
                                   placeholder="e.g. GST 18%"
                                   required>

                        </div>


                        {{-- TAX TYPE --}}

                        <div class="mb-3">

                            <label class="form-label">

                                Tax Type
                                <span class="text-danger">*</span>

                            </label>

                            <select name="tax_type"
                                    class="form-select"
                                    required>

                                <option value="">
                                    Select Tax Type
                                </option>

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
                                            old('tax_type')
                                            === $taxType
                                        )>

                                        {{ $taxType }}

                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- HSN / SAC --}}

                        <div class="mb-3">

                            <label class="form-label">

                                HSN / SAC Code

                            </label>

                            <input type="text"
                                   name="hsn_sac_code"
                                   value="{{ old(
                                       'hsn_sac_code'
                                   ) }}"
                                   class="form-control"
                                   maxlength="20"
                                   placeholder="Enter HSN/SAC code">

                        </div>


                        {{-- TAX PERCENTAGE --}}

                        <div class="mb-3">

                            <label class="form-label">

                                Tax Percentage
                                <span class="text-danger">*</span>

                            </label>

                            <div class="input-group">

                                <input type="number"
                                       name="tax_percentage"
                                       value="{{ old(
                                           'tax_percentage'
                                       ) }}"
                                       class="form-control"
                                       min="0"
                                       max="100"
                                       step="0.01"
                                       placeholder="18.00"
                                       required>

                                <span class="input-group-text">
                                    %
                                </span>

                            </div>

                        </div>


                        {{-- EFFECTIVE FROM --}}

                        <div class="mb-3">

                            <label class="form-label">

                                Effective From
                                <span class="text-danger">*</span>

                            </label>

                            <input type="date"
                                   name="effective_from"
                                   value="{{ old(
                                       'effective_from'
                                   ) }}"
                                   class="form-control"
                                   required>

                        </div>


                        {{-- EFFECTIVE TO --}}

                        <div class="mb-3">

                            <label class="form-label">

                                Effective To

                            </label>

                            <input type="date"
                                   name="effective_to"
                                   value="{{ old(
                                       'effective_to'
                                   ) }}"
                                   class="form-control">

                            <small class="text-muted">

                                Leave blank if this configuration
                                has no end date.

                            </small>

                        </div>


                        {{-- DEFAULT --}}

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
                                            'Yes'
                                        ) === 'Yes'
                                    )>

                                    Yes

                                </option>

                                <option value="No"
                                    @selected(
                                        old(
                                            'is_default'
                                        ) === 'No'
                                    )>

                                    No

                                </option>

                            </select>

                        </div>


                        {{-- STATUS --}}

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
                                            'Active'
                                        ) === 'Active'
                                    )>

                                    Active

                                </option>

                                <option value="Inactive"
                                    @selected(
                                        old('status')
                                        === 'Inactive'
                                    )>

                                    Inactive

                                </option>

                            </select>

                        </div>


                        {{-- REMARKS --}}

                        <div class="mb-3">

                            <label class="form-label">

                                Remarks

                            </label>

                            <textarea name="remarks"
                                      rows="3"
                                      class="form-control"
                                      placeholder="Optional remarks">{{ old(
                                          'remarks'
                                      ) }}</textarea>

                        </div>


                        {{-- SUBMIT --}}

                        <button type="submit"
                                class="btn btn-primary w-100">

                            <i class="fas fa-save me-1"></i>

                            Add Tax Configuration

                        </button>

                    </form>

                </div>

            </div>

        </div>


        {{-- =====================================================
             TAX CONFIGURATION LIST
        ====================================================== --}}

        <div class="col-xl-8">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white py-3">

                    <div class="d-flex
                                justify-content-between
                                align-items-center">

                        <div>

                            <h5 class="mb-1">
                                Tax Configurations
                            </h5>

                            <small class="text-muted">

                                {{ $taxConfigurations->count() }}
                                configuration(s)

                            </small>

                        </div>

                    </div>

                </div>


                <div class="card-body p-0">

                    @if(
                        $taxConfigurations->count() > 0
                    )

                        <div class="table-responsive">

                            <table class="table
                                          table-hover
                                          align-middle
                                          mb-0">

                                <thead class="table-light">

                                    <tr>

                                        <th>
                                            Charge Type
                                        </th>

                                        <th>
                                            Tax
                                        </th>

                                        <th>
                                            Rate
                                        </th>

                                        <th>
                                            Effective Period
                                        </th>

                                        <th>
                                            Default
                                        </th>

                                        <th>
                                            Status
                                        </th>

                                        <th class="text-end">
                                            Actions
                                        </th>

                                    </tr>

                                </thead>


                                <tbody>

                                    @foreach(
                                        $taxConfigurations
                                        as $tax
                                    )

                                        <tr>

                                            {{-- CHARGE TYPE --}}

                                            <td>

                                                @if(
                                                    $tax->chargeType
                                                )

                                                    <div class="fw-semibold">

                                                        {{
                                                            $tax
                                                                ->chargeType
                                                                ->charge_name
                                                        }}

                                                    </div>

                                                    <small class="text-muted">

                                                        {{
                                                            $tax
                                                                ->chargeType
                                                                ->charge_code
                                                        }}

                                                    </small>

                                                @else

                                                    <span class="text-danger">

                                                        Unknown

                                                    </span>

                                                @endif

                                            </td>


                                            {{-- TAX --}}

                                            <td>

                                                <div class="fw-semibold">

                                                    {{ $tax->tax_name }}

                                                </div>

                                                <small class="text-muted">

                                                    {{ $tax->tax_type }}

                                                    @if(
                                                        $tax->hsn_sac_code
                                                    )

                                                        • HSN/SAC:
                                                        {{
                                                            $tax
                                                                ->hsn_sac_code
                                                        }}

                                                    @endif

                                                </small>

                                            </td>


                                            {{-- RATE --}}

                                            <td>

                                                <span class="fw-bold">

                                                    {{
                                                        number_format(
                                                            $tax
                                                                ->tax_percentage,
                                                            2
                                                        )
                                                    }}%

                                                </span>

                                            </td>


                                            {{-- EFFECTIVE PERIOD --}}

                                            <td>

                                                <div>

                                                    {{
                                                        $tax
                                                            ->effective_from
                                                            ? $tax
                                                                ->effective_from
                                                                ->format(
                                                                    'd M Y'
                                                                )
                                                            : '-'
                                                    }}

                                                </div>

                                                <small class="text-muted">

                                                    to

                                                    {{
                                                        $tax
                                                            ->effective_to
                                                            ? $tax
                                                                ->effective_to
                                                                ->format(
                                                                    'd M Y'
                                                                )
                                                            : 'Open ended'
                                                    }}

                                                </small>

                                            </td>


                                            {{-- DEFAULT --}}

                                            <td>

                                                @if(
                                                    $tax->is_default
                                                    === 'Yes'
                                                )

                                                    <span class="badge
                                                                 bg-primary">

                                                        Default

                                                    </span>

                                                @else

                                                    <span class="badge
                                                                 bg-light
                                                                 text-dark">

                                                        No

                                                    </span>

                                                @endif

                                            </td>


                                            {{-- STATUS --}}

                                            <td>

                                                @if(
                                                    $tax->status
                                                    === 'Active'
                                                )

                                                    <span class="badge
                                                                 bg-success">

                                                        Active

                                                    </span>

                                                @else

                                                    <span class="badge
                                                                 bg-secondary">

                                                        Inactive

                                                    </span>

                                                @endif

                                            </td>


                                            {{-- ACTIONS --}}

                                            <td class="text-end">

                                                <div class="d-inline-flex
                                                            gap-1">

                                                    <a href="{{ route(
                                                        'admin.revenue.tax-configurations.edit',
                                                        $tax->id
                                                    ) }}"
                                                       class="btn btn-sm
                                                              btn-outline-warning">

                                                        <i class="fas fa-edit"></i>

                                                    </a>


                                                    <form method="POST"
                                                          action="{{ route(
                                                              'admin.revenue.tax-configurations.destroy',
                                                              $tax->id
                                                          ) }}"
                                                          onsubmit="return confirm(
                                                              'Are you sure you want to delete this tax configuration?'
                                                          );">

                                                        @csrf

                                                        @method('DELETE')

                                                        <button type="submit"
                                                                class="btn btn-sm
                                                                       btn-outline-danger">

                                                            <i class="fas fa-trash"></i>

                                                        </button>

                                                    </form>

                                                </div>

                                            </td>

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    @else

                        <div class="text-center
                                    text-muted
                                    py-5">

                            <i class="fas fa-percentage
                                      fa-3x
                                      d-block
                                      mb-3">
                            </i>

                            <h6>
                                No tax configurations found
                            </h6>

                            <p class="mb-0">

                                Add your first tax configuration
                                using the form.

                            </p>

                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>

</div>

@endsection