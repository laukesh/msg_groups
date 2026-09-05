@extends('layouts.app')

@section('title', 'Create Lease Proposal')

@section('content')

<div class="container-fluid">

  {{-- ========================================================= --}}
  {{-- Page Header --}}
  {{-- ========================================================= --}}

  <div class="d-flex justify-content-between align-items-center mb-4">

    <div>

      <h4 class="mb-1">
        Create Lease Proposal
      </h4>

      <div class="text-muted">
        Create a new lease proposal for a tenant.
      </div>

    </div>

    <div>

      <a href="{{ route('admin.leasing.proposals.index') }}" class="btn btn-secondary">

        <i class="fas fa-arrow-left"></i>
        Back

      </a>

    </div>

  </div>


  {{-- ========================================================= --}}
  {{-- Validation Errors --}}
  {{-- ========================================================= --}}

  @if($errors->any())

  <div class="alert alert-danger alert-dismissible fade show">

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

    <button type="button" class="btn-close" data-bs-dismiss="alert">
    </button>

  </div>

  @endif


  {{-- ========================================================= --}}
  {{-- Session Error --}}
  {{-- ========================================================= --}}

  @if(session('error'))

  <div class="alert alert-danger alert-dismissible fade show">

    {{ session('error') }}

    <button type="button" class="btn-close" data-bs-dismiss="alert">
    </button>

  </div>

  @endif


  {{-- ========================================================= --}}
  {{-- Main Form --}}
  {{-- ========================================================= --}}

  <form method="POST" action="{{ route('admin.leasing.proposals.store') }}">

    @csrf


    {{-- ===================================================== --}}
    {{-- 1. PROPOSAL INFORMATION --}}
    {{-- ===================================================== --}}

    <div class="card mb-4">

      <div class="card-header">

        <h5 class="mb-0">
          <i class="fas fa-file-contract me-1"></i>
          Proposal Information
        </h5>

      </div>


      <div class="card-body">

        <div class="row g-3">


          {{-- Proposal Date --}}
          <div class="col-md-4">

            <label class="form-label">

              Proposal Date

              <span class="text-danger">
                *
              </span>

            </label>

            <input type="date" name="proposal_date" class="form-control @error('proposal_date') is-invalid @enderror"
              value="{{ old(
                                   'proposal_date',
                                   date('Y-m-d')
                               ) }}" required>

            @error('proposal_date')

            <div class="invalid-feedback">
              {{ $message }}
            </div>

            @enderror

          </div>


          {{-- Tenant --}}
          <div class="col-md-8">

            <label class="form-label">

              Tenant

              <span class="text-danger">
                *
              </span>

            </label>

            <select name="tenant_id" class="form-select @error('tenant_id') is-invalid @enderror" required>

              <option value="">
                -- Select Tenant --
              </option>

              @foreach($tenants as $tenant)

              <option value="{{ $tenant->id }}" {{ old('tenant_id') == $tenant->id
                                        ? 'selected'
                                        : '' }}>

                {{ $tenant->company_name }}

                @if(!empty($tenant->brand_name))

                - {{ $tenant->brand_name }}

                @endif

              </option>

              @endforeach

            </select>

            @error('tenant_id')

            <div class="invalid-feedback">
              {{ $message }}
            </div>

            @enderror

          </div>


          {{-- Valid Until --}}
          <div class="col-md-4">

            <label class="form-label">

              Valid Until

            </label>

            <input type="date" name="valid_until" class="form-control @error('valid_until') is-invalid @enderror"
              value="{{ old('valid_until') }}">

            <small class="text-muted">

              Date until which the proposal remains valid.

            </small>

            @error('valid_until')

            <div class="invalid-feedback">
              {{ $message }}
            </div>

            @enderror

          </div>


          {{-- Proposal Title --}}
            <div class="col-md-8">

                <label class="form-label">
                    Proposal Title
                    <span class="text-danger">*</span>
                </label>

                <input type="text"
                       name="proposal_title"
                       class="form-control @error('proposal_title') is-invalid @enderror"
                       value="{{ old('proposal_title') }}"
                       placeholder="Enter proposal title"
                       maxlength="200"
                       required>

                @error('proposal_title')

                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>

                @enderror

            </div>

        </div>

      </div>

    </div>


    {{-- ===================================================== --}}
    {{-- 2. LEASE PERIOD --}}
    {{-- ===================================================== --}}

    <div class="card mb-4">

      <div class="card-header">

        <h5 class="mb-0">
          <i class="fas fa-calendar-alt me-1"></i>
          Lease Period
        </h5>

      </div>


      <div class="card-body">

        <div class="row g-3">


          {{-- Lease Start Date --}}
          <div class="col-md-4">

            <label class="form-label">

              Lease Start Date

            </label>

            <input type="date" name="lease_start_date" id="lease_start_date"
              class="form-control @error('lease_start_date') is-invalid @enderror"
              value="{{ old('lease_start_date') }}">

            @error('lease_start_date')

            <div class="invalid-feedback">
              {{ $message }}
            </div>

            @enderror

          </div>


          {{-- Lease End Date --}}
          <div class="col-md-4">

            <label class="form-label">

              Lease End Date

            </label>

            <input type="date" name="lease_end_date" id="lease_end_date"
              class="form-control @error('lease_end_date') is-invalid @enderror" value="{{ old('lease_end_date') }}">

            @error('lease_end_date')

            <div class="invalid-feedback">
              {{ $message }}
            </div>

            @enderror

          </div>


          {{-- Lease Period --}}
          <div class="col-md-4">

            <label class="form-label">

              Lease Period (Months)

            </label>

            <input type="number" name="lease_period_months" id="lease_period_months"
              class="form-control @error('lease_period_months') is-invalid @enderror"
              value="{{ old('lease_period_months') }}" readonly>

            <small class="text-muted">

              Automatically calculated from start and end date.

            </small>

            @error('lease_period_months')

            <div class="invalid-feedback">
              {{ $message }}
            </div>

            @enderror

          </div>

        </div>

      </div>

    </div>


    {{-- ===================================================== --}}
    {{-- 3. FINANCIAL DETAILS --}}
    {{-- ===================================================== --}}

    <div class="card mb-4">

      <div class="card-header">

        <h5 class="mb-0">
          <i class="fas fa-rupee-sign me-1"></i>
          Financial Details
        </h5>

      </div>


      <div class="card-body">

        <div class="row g-3">


          {{-- Monthly Rent --}}
          <div class="col-md-4">

            <label class="form-label">

              Monthly Rent

            </label>

            <div class="input-group">

              <span class="input-group-text">
                $
              </span>

              <input type="number" name="monthly_rent" id="monthly_rent" step="0.01" min="0"
                class="form-control @error('monthly_rent') is-invalid @enderror" value="{{ old(
                                       'monthly_rent',
                                       0
                                   ) }}">

            </div>

            @error('monthly_rent')

            <div class="text-danger small mt-1">
              {{ $message }}
            </div>

            @enderror

          </div>


          {{-- CAM Amount --}}
          <div class="col-md-4">

            <label class="form-label">

              CAM Amount

            </label>

            <div class="input-group">

              <span class="input-group-text">
                $
              </span>

              <input type="number" name="cam_amount" id="cam_amount" step="0.01" min="0"
                class="form-control @error('cam_amount') is-invalid @enderror" value="{{ old(
                                       'cam_amount',
                                       0
                                   ) }}">

            </div>

            @error('cam_amount')

            <div class="text-danger small mt-1">
              {{ $message }}
            </div>

            @enderror

          </div>


          {{-- Security Deposit --}}
          <div class="col-md-4">

            <label class="form-label">

              Security Deposit

            </label>

            <div class="input-group">

              <span class="input-group-text">
                $
              </span>

              <input type="number" name="security_deposit" id="security_deposit" step="0.01" min="0"
                class="form-control @error('security_deposit') is-invalid @enderror" value="{{ old(
                                       'security_deposit',
                                       0
                                   ) }}">

            </div>

            @error('security_deposit')

            <div class="text-danger small mt-1">
              {{ $message }}
            </div>

            @enderror

          </div>


          {{-- Fit-out Period --}}
          <div class="col-md-4">

            <label class="form-label">

              Fit-out Period (Days)

            </label>

            <input type="number" name="fitout_period_days" min="0"
              class="form-control @error('fitout_period_days') is-invalid @enderror" value="{{ old(
                                   'fitout_period_days',
                                   0
                               ) }}">

            @error('fitout_period_days')

            <div class="invalid-feedback">
              {{ $message }}
            </div>

            @enderror

          </div>


          {{-- Rent Free --}}
          <div class="col-md-4">

            <label class="form-label">

              Rent Free Period (Days)

            </label>

            <input type="number" name="rent_free_days" min="0"
              class="form-control @error('rent_free_days') is-invalid @enderror" value="{{ old(
                                   'rent_free_days',
                                   0
                               ) }}">

            @error('rent_free_days')

            <div class="invalid-feedback">
              {{ $message }}
            </div>

            @enderror

          </div>


          {{-- Escalation --}}
          <div class="col-md-4">

            <label class="form-label">

              Annual Escalation

            </label>

            <div class="input-group">

              <input type="number" name="escalation_percentage" step="0.01" min="0" max="100"
                class="form-control @error('escalation_percentage') is-invalid @enderror" value="{{ old(
                                       'escalation_percentage'
                                   ) }}">

              <span class="input-group-text">
                %
              </span>

            </div>

            @error('escalation_percentage')

            <div class="text-danger small mt-1">
              {{ $message }}
            </div>

            @enderror

          </div>

        </div>


        {{-- ================================================= --}}
        {{-- Monthly Total --}}
        {{-- ================================================= --}}

        <div class="row mt-4">

          <div class="col-md-5 ms-auto">

            <div class="border rounded p-3 bg-light">

              <div class="d-flex justify-content-between">

                <span>
                  Monthly Rent
                </span>

                <strong>
                  $<span id="display_rent">
                    0.00
                  </span>
                </strong>

              </div>


              <div class="d-flex justify-content-between mt-2">

                <span>
                  CAM
                </span>

                <strong>
                  $<span id="display_cam">
                    0.00
                  </span>
                </strong>

              </div>


              <hr>


              <div class="d-flex justify-content-between">

                <strong>
                  Monthly Total
                </strong>

                <strong class="text-primary fs-5">

                  $<span id="monthly_total">
                    0.00
                  </span>

                </strong>

              </div>

            </div>

          </div>

        </div>

      </div>

    </div>


    {{-- ===================================================== --}}
    {{-- 4. UNIT SELECTION --}}
    {{-- ===================================================== --}}

    @php

    $selectedUnitIds = old(
    'unit_ids',
    []
    );

    @endphp


    <div class="card mb-4">

      <div class="card-header">

        <div class="d-flex justify-content-between align-items-center">

          <h5 class="mb-0">

            <i class="fas fa-store me-1"></i>

            Select Units

          </h5>


          <span class="badge bg-primary" id="selectedUnitCount">

            0 Selected

          </span>

        </div>

      </div>


      <div class="card-body p-0">


        {{-- Unit Validation --}}
        @error('unit_ids')

        <div class="alert alert-danger m-3">

          {{ $message }}

        </div>

        @enderror


        <div class="table-responsive">

          <table class="table table-bordered table-hover mb-0">

            <thead class="table-light">

              <tr>

                <th width="50" class="text-center">

                  <input type="checkbox" id="selectAllUnits" class="form-check-input">

                </th>

                <th>
                  Unit No.
                </th>

                <th>
                  Shop Name
                </th>

                <th>
                  Floor
                </th>

                <th>
                  Carpet Area
                </th>

                <th>
                  Built-up Area
                </th>

                <th>
                  Monthly Rent
                </th>

                <th>
                  Status
                </th>

              </tr>

            </thead>


            <tbody>

              @forelse($units as $unit)

              <tr>


                {{-- Checkbox --}}
                <td class="text-center">

                  <input type="checkbox" name="unit_ids[]" value="{{ $unit->id }}"
                    class="form-check-input unit-checkbox" {{ in_array(
                                                $unit->id,
                                                $selectedUnitIds
                                            ) ? 'checked' : '' }}>

                </td>


                {{-- Unit --}}
                <td>

                  <strong>
                    {{ $unit->unit_no }}
                  </strong>

                </td>


                {{-- Shop --}}
                <td>

                  {{ $unit->shop_name ?? '-' }}

                </td>


                {{-- Floor --}}
                <td>

                  {{ $unit->floor->floor_name ?? '-' }}

                </td>


                {{-- Carpet Area --}}
                <td>

                  {{ number_format(
                                            $unit->carpet_area ?? 0,
                                            2
                                        ) }}

                </td>


                {{-- Built-up Area --}}
                <td>

                  {{ number_format(
                                            $unit->builtup_area ?? 0,
                                            2
                                        ) }}

                </td>


                {{-- Rent --}}
                <td>

                  ${{ number_format(
                                            $unit->monthly_rent ?? 0,
                                            2
                                        ) }}

                </td>


                {{-- Status --}}
                <td>

                  @if($unit->current_status === 'Vacant')

                  <span class="badge bg-success">

                    Vacant

                  </span>

                  @elseif($unit->current_status === 'Reserved')

                  <span class="badge bg-warning text-dark">

                    Reserved

                  </span>

                  @else

                  <span class="badge bg-secondary">

                    {{ $unit->current_status }}

                  </span>

                  @endif

                </td>

              </tr>

              @empty

              <tr>

                <td colspan="8" class="text-center py-5 text-muted">

                  <i class="fas fa-store-slash fa-2x mb-2"></i>

                  <br>

                  No vacant units available.

                </td>

              </tr>

              @endforelse

            </tbody>

          </table>

        </div>

      </div>

    </div>


    {{-- ===================================================== --}}
    {{-- 5. REMARKS --}}
    {{-- ===================================================== --}}

    <div class="card mb-4">

      <div class="card-header">

        <h5 class="mb-0">

          <i class="fas fa-comment-alt me-1"></i>

          Additional Remarks

        </h5>

      </div>


      <div class="card-body">

        <textarea name="remarks" rows="4" class="form-control @error('remarks') is-invalid @enderror"
          placeholder="Enter additional remarks...">{{ old('remarks') }}</textarea>

        @error('remarks')

        <div class="invalid-feedback">

          {{ $message }}

        </div>

        @enderror

      </div>

    </div>


    {{-- ===================================================== --}}
    {{-- 6. ACTION BUTTONS --}}
    {{-- ===================================================== --}}

    <div class="card mb-4">

      <div class="card-body">

        <div class="d-flex justify-content-between align-items-center">


          <div class="text-muted">

            <i class="fas fa-info-circle"></i>

            Proposal will be created with
            <strong>Draft</strong> status.

          </div>


          <div class="d-flex gap-2">


            <a href="{{ route(
                            'admin.leasing.proposals.index'
                        ) }}" class="btn btn-secondary">

              Cancel

            </a>


            <button type="submit" class="btn btn-primary">

              <i class="fas fa-save"></i>

              Save Draft

            </button>

          </div>

        </div>

      </div>

    </div>


  </form>

</div>


{{-- ============================================================= --}}
{{-- JavaScript --}}
{{-- ============================================================= --}}

<script>
  document.addEventListener('DOMContentLoaded', function () {
  
  
      /* ============================================================
         LEASE PERIOD CALCULATION
         ============================================================ */
  
      const startDate =
          document.getElementById('lease_start_date');
  
      const endDate =
          document.getElementById('lease_end_date');
  
      const period =
          document.getElementById('lease_period_months');
  
  
      function calculateLeasePeriod()
      {
  
          if (
              !startDate ||
              !endDate ||
              !period
          ) {
              return;
          }
  
  
          if (
              !startDate.value ||
              !endDate.value
          ) {
  
              period.value = '';
  
              return;
          }
  
  
          const start =
              new Date(startDate.value);
  
          const end =
              new Date(endDate.value);
  
  
          if (end < start) {
  
              period.value = '';
  
              return;
          }
  
  
          let months =
              (end.getFullYear() - start.getFullYear()) * 12;
  
          months +=
              end.getMonth() - start.getMonth();
  
  
          if (
              end.getDate() >=
              start.getDate()
          ) {
  
              months++;
  
          }
  
  
          period.value = months;
  
      }
  
  
      if (startDate) {
  
          startDate.addEventListener(
              'change',
              calculateLeasePeriod
          );
  
      }
  
  
      if (endDate) {
  
          endDate.addEventListener(
              'change',
              calculateLeasePeriod
          );
  
      }
  
  
      calculateLeasePeriod();
  
  
      /* ============================================================
         MONTHLY RENT + CAM
         ============================================================ */
  
      const rentInput =
          document.getElementById('monthly_rent');
  
      const camInput =
          document.getElementById('cam_amount');
  
      const displayRent =
          document.getElementById('display_rent');
  
      const displayCam =
          document.getElementById('display_cam');
  
      const monthlyTotal =
          document.getElementById('monthly_total');
  
  
      function formatCurrency(value)
      {
  
          return value.toLocaleString(
              'en-IN',
              {
                  minimumFractionDigits: 2,
                  maximumFractionDigits: 2
              }
          );
  
      }
  
  
      function calculateMonthlyTotal()
      {
  
          if (
              !rentInput ||
              !camInput
          ) {
              return;
          }
  
  
          const rent =
              parseFloat(
                  rentInput.value
              ) || 0;
  
  
          const cam =
              parseFloat(
                  camInput.value
              ) || 0;
  
  
          const total =
              rent + cam;
  
  
          displayRent.textContent =
              formatCurrency(rent);
  
  
          displayCam.textContent =
              formatCurrency(cam);
  
  
          monthlyTotal.textContent =
              formatCurrency(total);
  
      }
  
  
      if (rentInput) {
  
          rentInput.addEventListener(
              'input',
              calculateMonthlyTotal
          );
  
      }
  
  
      if (camInput) {
  
          camInput.addEventListener(
              'input',
              calculateMonthlyTotal
          );
  
      }
  
  
      calculateMonthlyTotal();
  
  
      /* ============================================================
         UNIT SELECTION
         ============================================================ */
  
      const selectAll =
          document.getElementById(
              'selectAllUnits'
          );
  
  
      const checkboxes =
          document.querySelectorAll(
              '.unit-checkbox'
          );
  
  
      const selectedCount =
          document.getElementById(
              'selectedUnitCount'
          );
  
  
      function updateSelectedCount()
      {
  
          const checked =
              document.querySelectorAll(
                  '.unit-checkbox:checked'
              ).length;
  
  
          selectedCount.textContent =
              checked + ' Selected';
  
  
          if (
              selectAll &&
              checkboxes.length > 0
          ) {
  
              selectAll.checked =
                  checked === checkboxes.length;
  
  
              selectAll.indeterminate =
                  checked > 0 &&
                  checked < checkboxes.length;
  
          }
  
      }
  
  
      if (selectAll) {
  
          selectAll.addEventListener(
              'change',
              function ()
              {
  
                  checkboxes.forEach(
                      function (checkbox)
                      {
  
                          checkbox.checked =
                              selectAll.checked;
  
                      }
                  );
  
  
                  updateSelectedCount();
  
              }
          );
  
      }
  
  
      checkboxes.forEach(
          function (checkbox)
          {
  
              checkbox.addEventListener(
                  'change',
                  updateSelectedCount
              );
  
          }
      );
  
  
      updateSelectedCount();
  
  
  });
  
</script>

@endsection