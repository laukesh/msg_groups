@extends('layouts.app')

@section('title', 'Edit Leasing Opportunity')

@section('content')

<div class="container-fluid py-4">

    {{-- =========================================================
        HEADER
    ========================================================== --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1 fw-semibold">

                <i class="ri-edit-line me-2"></i>
                Edit Leasing Opportunity

            </h4>

            <p class="text-muted mb-0">

                Update leasing pipeline information.

            </p>

        </div>

        <div class="d-flex gap-2">

            <a href="{{ route(
                'admin.leasing.pipeline.show',
                $pipeline->id
            ) }}"
               class="btn btn-light border">

                <i class="ri-eye-line me-1"></i>
                View

            </a>

            <a href="{{ route(
                'admin.leasing.pipeline.index'
            ) }}"
               class="btn btn-light border">

                <i class="ri-arrow-left-line me-1"></i>
                Back

            </a>

        </div>

    </div>


    {{-- =========================================================
        ERRORS
    ========================================================== --}}
    @if($errors->any())

        <div class="alert alert-danger">

            <strong>
                Please correct the following errors:
            </strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    <form method="POST"
          action="{{ route(
              'admin.leasing.pipeline.update',
              $pipeline->id
          ) }}">

        @csrf
        @method('PUT')


        <div class="row g-4">

            {{-- =================================================
                MAIN
            ================================================== --}}
            <div class="col-xl-8">

                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white py-3">

                        <h6 class="mb-0 fw-semibold">

                            <i class="ri-flow-chart me-2"></i>
                            Opportunity Information

                        </h6>

                    </div>


                    <div class="card-body">

                        <div class="row g-3">

                            {{-- Prospect --}}
                            <div class="col-md-6">

                                <label class="form-label fw-semibold">
                                    Prospect
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="text"
                                       name="prospect_name"
                                       value="{{ old(
                                           'prospect_name',
                                           $pipeline->prospect_name
                                       ) }}"
                                       class="form-control @error('prospect_name') is-invalid @enderror"
                                       required>

                                @error('prospect_name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- Company --}}
                            <div class="col-md-6">

                                <label class="form-label fw-semibold">
                                    Company
                                </label>

                                <input type="text"
                                       name="company_name"
                                       value="{{ old(
                                           'company_name',
                                           $pipeline->company_name
                                       ) }}"
                                       class="form-control">

                            </div>


                            {{-- Stage --}}
                            <div class="col-md-6">

                                <label class="form-label fw-semibold">
                                    Pipeline Stage
                                    <span class="text-danger">*</span>
                                </label>

                                <select name="stage"
                                        class="form-select"
                                        required>

                                    @foreach([
                                        'lead' => 'Lead',
                                        'prospect' => 'Prospect',
                                        'negotiation' => 'Negotiation',
                                        'agreement' => 'Agreement',
                                        'opening' => 'Opening',
                                        'won' => 'Won',
                                        'lost' => 'Lost',
                                    ] as $key => $label)

                                        <option value="{{ $key }}"
                                            @selected(
                                                old(
                                                    'stage',
                                                    $pipeline->stage
                                                ) === $key
                                            )>

                                            {{ $label }}

                                        </option>

                                    @endforeach

                                </select>

                            </div>


                            {{-- Unit --}}
                            <div class="col-md-6">

                                <label class="form-label fw-semibold">
                                    Unit
                                </label>

                                <select name="unit_id"
                                        class="form-select">

                                    <option value="">
                                        Select Unit
                                    </option>

                                    @foreach(($units ?? []) as $unit)

                                        <option value="{{ $unit->id }}"
                                            @selected(
                                                old(
                                                    'unit_id',
                                                    $pipeline->unit_id
                                                ) == $unit->id
                                            )>

                                            {{ $unit->unit_no}}

                                        </option>

                                    @endforeach

                                </select>

                            </div>


                            {{-- Area --}}
                            <div class="col-md-6">

                                <label class="form-label fw-semibold">
                                    Area
                                </label>

                                <div class="input-group">

                                    <input type="number"
                                           step="0.01"
                                           min="0"
                                           name="area"
                                           value="{{ old(
                                               'area',
                                               $pipeline->area
                                           ) }}"
                                           class="form-control">

                                    <span class="input-group-text">
                                        sq.ft
                                    </span>

                                </div>

                            </div>


                            {{-- Rent --}}
                            <div class="col-md-6">

                                <label class="form-label fw-semibold">
                                    Expected Rent
                                </label>

                                <input type="number"
                                       step="0.01"
                                       min="0"
                                       name="expected_rent"
                                       value="{{ old(
                                           'expected_rent',
                                           $pipeline->expected_rent
                                       ) }}"
                                       class="form-control">

                            </div>


                            {{-- Rent Period --}}
                            <div class="col-md-6">

                                <label class="form-label fw-semibold">
                                    Rent Period
                                </label>

                                <select name="rent_period"
                                        class="form-select">

                                    @foreach([
                                        'monthly' => 'Monthly',
                                        'quarterly' => 'Quarterly',
                                        'yearly' => 'Yearly',
                                    ] as $key => $label)

                                        <option value="{{ $key }}"
                                            @selected(
                                                old(
                                                    'rent_period',
                                                    $pipeline->rent_period
                                                ) === $key
                                            )>

                                            {{ $label }}

                                        </option>

                                    @endforeach

                                </select>

                            </div>


                            {{-- Probability --}}
                            <div class="col-md-6">

                                <label class="form-label fw-semibold">
                                    Probability
                                </label>

                                <div class="input-group">

                                    <input type="number"
                                           name="probability"
                                           min="0"
                                           max="100"
                                           value="{{ old(
                                               'probability',
                                               $pipeline->probability ?? 0
                                           ) }}"
                                           class="form-control">

                                    <span class="input-group-text">
                                        %
                                    </span>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                    ACTION
                ================================================== --}}
                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white py-3">

                        <h6 class="mb-0 fw-semibold">

                            <i class="ri-calendar-check-line me-2"></i>
                            Action & Forecast

                        </h6>

                    </div>


                    <div class="card-body">

                        <div class="row g-3">

                            {{-- Next Action --}}
                            <div class="col-md-8">

                                <label class="form-label fw-semibold">
                                    Next Action
                                </label>

                                <input type="text"
                                       name="next_action"
                                       value="{{ old(
                                           'next_action',
                                           $pipeline->next_action
                                       ) }}"
                                       class="form-control">

                            </div>


                            {{-- Owner --}}
                            <div class="col-md-4">

                                <label class="form-label fw-semibold">
                                    Owner
                                </label>

                                <select name="owner_id"
                                        class="form-select">

                                    <option value="">
                                        Select Owner
                                    </option>

                                    @foreach(($owners ?? []) as $owner)

                                        <option value="{{ $owner->id }}"
                                            @selected(
                                                old(
                                                    'owner_id',
                                                    $pipeline->owner_id
                                                ) == $owner->id
                                            )>

                                            {{ $owner->name }}

                                        </option>

                                    @endforeach

                                </select>

                            </div>


                            {{-- Forecast --}}
                            <div class="col-md-6">

                                <label class="form-label fw-semibold">
                                    Forecast Opening
                                </label>

                                <input type="date"
                                       name="forecast_opening"
                                       value="{{ old(
                                           'forecast_opening',
                                           $pipeline->forecast_opening
                                               ? \Carbon\Carbon::parse(
                                                   $pipeline->forecast_opening
                                               )->format('Y-m-d')
                                               : ''
                                       ) }}"
                                       class="form-control">

                            </div>


                            {{-- Expected Opening --}}
                            <div class="col-md-6">

                                <label class="form-label fw-semibold">
                                    Expected Opening Date
                                </label>

                                <input type="date"
                                       name="expected_opening_date"
                                       value="{{ old(
                                           'expected_opening_date',
                                           $pipeline->expected_opening_date
                                               ? \Carbon\Carbon::parse(
                                                   $pipeline->expected_opening_date
                                               )->format('Y-m-d')
                                               : ''
                                       ) }}"
                                       class="form-control">

                            </div>


                            {{-- Notes --}}
                            <div class="col-12">

                                <label class="form-label fw-semibold">
                                    Notes
                                </label>

                                <textarea name="notes"
                                          rows="5"
                                          class="form-control">{{ old(
                                              'notes',
                                              $pipeline->notes
                                          ) }}</textarea>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================================
                SIDE PANEL
            ================================================== --}}
            <div class="col-xl-4">

                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white py-3">

                        <h6 class="mb-0 fw-semibold">

                            <i class="ri-information-line me-2"></i>
                            Opportunity Summary

                        </h6>

                    </div>


                    <div class="card-body">

                        <div class="d-flex justify-content-between mb-3">

                            <span class="text-muted">
                                Opportunity ID
                            </span>

                            <strong>
                                #{{ $pipeline->id }}
                            </strong>

                        </div>


                        <div class="d-flex justify-content-between mb-3">

                            <span class="text-muted">
                                Created
                            </span>

                            <span>

                                {{ optional(
                                    $pipeline->created_at
                                )->format('d M Y') }}

                            </span>

                        </div>


                        <div class="d-flex justify-content-between mb-3">

                            <span class="text-muted">
                                Aging
                            </span>

                            <strong>

                                {{ $pipeline->aging_days
                                    ?? (
                                        $pipeline->created_at
                                            ? $pipeline->created_at
                                                ->diffInDays(now())
                                            : 0
                                    ) }}

                                days

                            </strong>

                        </div>


                        <div class="d-flex justify-content-between">

                            <span class="text-muted">
                                Probability
                            </span>

                            <strong>
                                {{ $pipeline->probability ?? 0 }}%
                            </strong>

                        </div>

                    </div>

                </div>


                <div class="card border-0 shadow-sm">

                    <div class="card-body">

                        @can('pipeline.edit')

                            <button type="submit"
                                    class="btn btn-primary w-100 mb-2">

                                <i class="ri-save-line me-1"></i>
                                Update Opportunity

                            </button>

                        @endcan


                        <a href="{{ route(
                            'admin.leasing.pipeline.show',
                            $pipeline->id
                        ) }}"
                           class="btn btn-light border w-100">

                            Cancel

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </form>

</div>

@endsection