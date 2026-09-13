@extends('layouts.app')

@section('title', 'Edit Handover')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                Edit Handover
            </h4>

            <p class="text-muted mb-0">
                {{ $handover->handover_number }}
            </p>
        </div>

        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.fitout.handovers.show',
                    $handover->id
                ) }}"
                class="btn btn-info"
            >
                <i class="bi bi-eye me-1"></i>
                View
            </a>

            <a
                href="{{ route(
                    'admin.fitout.handovers.index'
                ) }}"
                class="btn btn-secondary"
            >
                <i class="bi bi-arrow-left me-1"></i>
                Back
            </a>

        </div>

    </div>


    {{-- Validation Errors --}}
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


    <form
        action="{{ route(
            'admin.fitout.handovers.update',
            $handover->id
        ) }}"
        method="POST"
        enctype="multipart/form-data"
    >

        @csrf
        @method('PUT')


        <div class="row">

            {{-- ================================================= --}}
            {{-- LEFT --}}
            {{-- ================================================= --}}

            <div class="col-lg-8">


                {{-- Fit-Out Details --}}
                <div class="card mb-4">

                    <div class="card-header">
                        <h5 class="mb-0">
                            Fit-Out Details
                        </h5>
                    </div>

                    <div class="card-body">

                        <div class="row">

                            {{-- Request --}}
                            <div class="col-md-12 mb-3">

                                <label class="form-label">
                                    Fit-Out Request
                                    <span class="text-danger">*</span>
                                </label>

                                <select
                                    name="fitout_request_id"
                                    id="fitout_request_id"
                                    class="form-select"
                                    required
                                >

                                    <option value="">
                                        Select Fit-Out Request
                                    </option>

                                    @foreach($fitoutRequests as $fitoutRequest)

                                        <option
                                            value="{{ $fitoutRequest->id }}"
                                            data-unit-id="{{ $fitoutRequest->unit_id }}"
                                            data-tenant-id="{{ $fitoutRequest->tenant_id }}"
                                            data-contractor-id="{{ $fitoutRequest->contractor_id }}"
                                            @selected(
                                                old(
                                                    'fitout_request_id',
                                                    $handover->fitout_request_id
                                                ) == $fitoutRequest->id
                                            )
                                        >

                                            {{ $fitoutRequest->request_no }}

                                            @if($fitoutRequest->unit)
                                                -
                                                {{ $fitoutRequest->unit->unit_no }}
                                            @endif

                                        </option>

                                    @endforeach

                                </select>

                            </div>


                            {{-- Unit --}}
                            <div class="col-md-4 mb-3">

                                <label class="form-label">
                                    Unit
                                    <span class="text-danger">*</span>
                                </label>

                                <select
                                    name="unit_id"
                                    id="unit_id"
                                    class="form-select"
                                    required
                                >

                                    <option value="">
                                        Select Unit
                                    </option>

                                    @foreach($units as $unit)

                                        <option
                                            value="{{ $unit->id }}"
                                            @selected(
                                                old(
                                                    'unit_id',
                                                    $handover->unit_id
                                                ) == $unit->id
                                            )
                                        >

                                            {{ $unit->unit_no }}

                                        </option>

                                    @endforeach

                                </select>

                            </div>


                            {{-- Tenant --}}
                            <div class="col-md-4 mb-3">

                                <label class="form-label">
                                    Tenant
                                    <span class="text-danger">*</span>
                                </label>

                                <select
                                    name="tenant_id"
                                    id="tenant_id"
                                    class="form-select"
                                    required
                                >

                                    <option value="">
                                        Select Tenant
                                    </option>

                                    @foreach($tenants as $tenant)

                                        <option
                                            value="{{ $tenant->id }}"
                                            @selected(
                                                old(
                                                    'tenant_id',
                                                    $handover->tenant_id
                                                ) == $tenant->id
                                            )
                                        >

                                            {{
                                                $tenant->company_name
                                                ??
                                                $tenant->tenant_name
                                                ??
                                                $tenant->name
                                                ??
                                                'Tenant #' . $tenant->id
                                            }}

                                        </option>

                                    @endforeach

                                </select>

                            </div>


                            {{-- Contractor --}}
                            <div class="col-md-4 mb-3">

                                <label class="form-label">
                                    Contractor
                                </label>

                                <select
                                    name="contractor_id"
                                    id="contractor_id"
                                    class="form-select"
                                >

                                    <option value="">
                                        Select Contractor
                                    </option>

                                    @foreach($contractors as $contractor)

                                        <option
                                            value="{{ $contractor->id }}"
                                            @selected(
                                                old(
                                                    'contractor_id',
                                                    $handover->contractor_id
                                                ) == $contractor->id
                                            )
                                        >

                                            {{
                                                $contractor->company_name
                                                ??
                                                $contractor->contractor_name
                                                ??
                                                $contractor->name
                                                ??
                                                'Contractor #' . $contractor->id
                                            }}

                                        </option>

                                    @endforeach

                                </select>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- Handover Details --}}
                <div class="card mb-4">

                    <div class="card-header">
                        <h5 class="mb-0">
                            Handover Details
                        </h5>
                    </div>

                    <div class="card-body">

                        <div class="row">

                            {{-- Number --}}
                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Handover Number
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    value="{{ $handover->handover_number }}"
                                    readonly
                                >

                            </div>


                            {{-- Type --}}
                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Handover Type
                                    <span class="text-danger">*</span>
                                </label>

                                <select
                                    name="handover_type"
                                    class="form-select"
                                    required
                                >

                                    @foreach([
                                        'Fit-Out Handover',
                                        'Final Handover',
                                        'Partial Handover'
                                    ] as $type)

                                        <option
                                            value="{{ $type }}"
                                            @selected(
                                                old(
                                                    'handover_type',
                                                    $handover->handover_type
                                                ) === $type
                                            )
                                        >

                                            {{ $type }}

                                        </option>

                                    @endforeach

                                </select>

                            </div>


                            {{-- Date --}}
                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Handover Date
                                </label>

                                <input
                                    type="date"
                                    name="handover_date"
                                    class="form-control"
                                    value="{{ old(
                                        'handover_date',
                                        optional(
                                            $handover->handover_date
                                        )->format('Y-m-d')
                                    ) }}"
                                >

                            </div>


                            {{-- Status --}}
                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Status
                                </label>

                                <select
                                    name="status"
                                    class="form-select"
                                >

                                    @foreach([
                                        'Pending',
                                        'Scheduled',
                                        'In Progress',
                                        'Accepted',
                                        'Rejected',
                                        'Completed',
                                        'Cancelled'
                                    ] as $status)

                                        <option
                                            value="{{ $status }}"
                                            @selected(
                                                old(
                                                    'status',
                                                    $handover->status
                                                ) === $status
                                            )
                                        >

                                            {{ $status }}

                                        </option>

                                    @endforeach

                                </select>

                            </div>


                            {{-- Final Inspection --}}
                            <div class="col-md-12 mb-3">

                                <label class="form-label">
                                    Final Inspection
                                </label>

                                <select
                                    name="final_inspection_id"
                                    id="final_inspection_id"
                                    class="form-select"
                                >

                                    <option value="">
                                        Select Final Inspection
                                    </option>

                                    @foreach($inspections as $inspection)

                                        <option
                                            value="{{ $inspection->id }}"
                                            data-request-id="{{ $inspection->fitout_request_id }}"
                                            @selected(
                                                old(
                                                    'final_inspection_id',
                                                    $handover->final_inspection_id
                                                ) == $inspection->id
                                            )
                                        >

                                            {{ $inspection->inspection_number }}

                                            -

                                            {{ $inspection->inspection_type }}

                                            @if($inspection->fitoutRequest)

                                                |
                                                {{ $inspection->fitoutRequest->request_no }}

                                            @endif

                                        </option>

                                    @endforeach

                                </select>

                            </div>


                            {{-- Condition --}}
                            <div class="col-md-12 mb-3">

                                <label class="form-label">
                                    Unit Condition
                                </label>

                                <select
                                    name="unit_condition"
                                    class="form-select"
                                >

                                    <option value="">
                                        Select Condition
                                    </option>

                                    @foreach([
                                        'Good',
                                        'Minor Issues',
                                        'Major Issues',
                                        'Not Ready'
                                    ] as $condition)

                                        <option
                                            value="{{ $condition }}"
                                            @selected(
                                                old(
                                                    'unit_condition',
                                                    $handover->unit_condition
                                                ) === $condition
                                            )
                                        >

                                            {{ $condition }}

                                        </option>

                                    @endforeach

                                </select>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- Keys --}}
                <div class="card mb-4">

                    <div class="card-header">
                        <h5 class="mb-0">
                            Keys & Access
                        </h5>
                    </div>

                    <div class="card-body">

                        <div class="row">

                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Key Count
                                </label>

                                <input
                                    type="number"
                                    name="key_count"
                                    min="0"
                                    class="form-control"
                                    value="{{ old(
                                        'key_count',
                                        $handover->key_count ?? 0
                                    ) }}"
                                >

                            </div>


                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Access Card Count
                                </label>

                                <input
                                    type="number"
                                    name="access_card_count"
                                    min="0"
                                    class="form-control"
                                    value="{{ old(
                                        'access_card_count',
                                        $handover->access_card_count ?? 0
                                    ) }}"
                                >

                            </div>

                        </div>

                    </div>

                </div>


                {{-- Electricity --}}
                <div class="card mb-4">

                    <div class="card-header">
                        <h5 class="mb-0">
                            Electricity Meter
                        </h5>
                    </div>

                    <div class="card-body">

                        <div class="row">

                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Meter Number
                                </label>

                                <input
                                    type="text"
                                    name="electricity_meter_no"
                                    class="form-control"
                                    maxlength="100"
                                    value="{{ old(
                                        'electricity_meter_no',
                                        $handover->electricity_meter_no
                                    ) }}"
                                >

                            </div>


                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Meter Reading
                                </label>

                                <input
                                    type="number"
                                    name="electricity_meter_reading"
                                    step="0.01"
                                    min="0"
                                    class="form-control"
                                    value="{{ old(
                                        'electricity_meter_reading',
                                        $handover->electricity_meter_reading
                                    ) }}"
                                >

                            </div>

                        </div>

                    </div>

                </div>


                {{-- Water --}}
                <div class="card mb-4">

                    <div class="card-header">
                        <h5 class="mb-0">
                            Water Meter
                        </h5>
                    </div>

                    <div class="card-body">

                        <div class="row">

                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Meter Number
                                </label>

                                <input
                                    type="text"
                                    name="water_meter_no"
                                    class="form-control"
                                    maxlength="100"
                                    value="{{ old(
                                        'water_meter_no',
                                        $handover->water_meter_no
                                    ) }}"
                                >

                            </div>


                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Meter Reading
                                </label>

                                <input
                                    type="number"
                                    name="water_meter_reading"
                                    step="0.01"
                                    min="0"
                                    class="form-control"
                                    value="{{ old(
                                        'water_meter_reading',
                                        $handover->water_meter_reading
                                    ) }}"
                                >

                            </div>

                        </div>

                    </div>

                </div>

                {{-- ================================================= --}}
                {{-- HANDOVER DOCUMENT --}}
                {{-- ================================================= --}}

                <div class="card mb-4">

                    <div class="card-header">

                        <h5 class="mb-0">
                            Handover Document
                        </h5>

                    </div>

                    <div class="card-body">

                        @if($handover->handover_document_path)

                            <div class="alert alert-success">

                                <i class="bi bi-file-earmark-check me-1"></i>

                                Existing document uploaded.

                                <a
                                    href="{{ asset(
                                        'storage/' .
                                        $handover->handover_document_path
                                    ) }}"
                                    target="_blank"
                                    class="ms-2"
                                >
                                    View Document
                                </a>

                            </div>

                        @endif


                        <label class="form-label">
                            Replace Document
                        </label>

                        <input
                            type="file"
                            name="handover_document"
                            class="form-control"
                            accept=".pdf,.jpg,.jpeg,.png"
                        >

                        <small class="text-muted">
                            Uploading a new document will replace the existing document.
                            Maximum size: 10 MB.
                        </small>

                    </div>

                </div>


                {{-- Remarks --}}
                <div class="card mb-4">

                    <div class="card-header">
                        <h5 class="mb-0">
                            Remarks
                        </h5>
                    </div>

                    <div class="card-body">

                        <textarea
                            name="remarks"
                            rows="5"
                            class="form-control"
                            placeholder="Enter remarks..."
                        >{{ old(
                            'remarks',
                            $handover->remarks
                        ) }}</textarea>

                    </div>

                </div>


                {{-- Submit --}}
                <div class="card mb-4">

                    <div class="card-body">

                        <div class="d-flex justify-content-end gap-2">

                            <a
                                href="{{ route(
                                    'admin.fitout.handovers.show',
                                    $handover->id
                                ) }}"
                                class="btn btn-secondary"
                            >
                                Cancel
                            </a>

                            <button
                                type="submit"
                                class="btn btn-primary"
                            >

                                <i class="bi bi-save me-1"></i>

                                Update Handover

                            </button>

                        </div>

                    </div>

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- RIGHT --}}
            {{-- ================================================= --}}

            <div class="col-lg-4">

                <div class="card mb-4">

                    <div class="card-header">
                        <h5 class="mb-0">
                            Handover Information
                        </h5>
                    </div>

                    <div class="card-body">

                        <div class="mb-3">

                            <small class="text-muted d-block">
                                Handover Number
                            </small>

                            <strong>
                                {{ $handover->handover_number }}
                            </strong>

                        </div>


                        <div class="mb-3">

                            <small class="text-muted d-block">
                                Current Status
                            </small>

                            @php

                                $statusClass = match(
                                    $handover->status
                                ) {
                                    'Pending' =>
                                        'bg-warning text-dark',

                                    'Scheduled' =>
                                        'bg-info text-dark',

                                    'In Progress' =>
                                        'bg-primary',

                                    'Accepted' =>
                                        'bg-success',

                                    'Completed' =>
                                        'bg-success',

                                    'Rejected' =>
                                        'bg-danger',

                                    'Cancelled' =>
                                        'bg-secondary',

                                    default =>
                                        'bg-secondary',
                                };

                            @endphp

                            <span
                                class="badge {{ $statusClass }}"
                            >
                                {{ $handover->status }}
                            </span>

                        </div>


                        <div class="mb-3">

                            <small class="text-muted d-block">
                                Created At
                            </small>

                            {{
                                $handover->created_at
                                    ? $handover->created_at
                                        ->format('d M Y H:i')
                                    : '-'
                            }}

                        </div>


                        <div>

                            <small class="text-muted d-block">
                                Last Updated
                            </small>

                            {{
                                $handover->updated_at
                                    ? $handover->updated_at
                                        ->format('d M Y H:i')
                                    : '-'
                            }}

                        </div>

                    </div>

                </div>


                <div class="alert alert-info">

                    <i class="bi bi-info-circle me-1"></i>

                    Changing the handover status should normally
                    be done through the workflow actions rather than
                    directly from the edit form.

                </div>

            </div>

        </div>

    </form>

</div>


<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        const requestSelect =
            document.getElementById(
                'fitout_request_id'
            );

        const inspectionSelect =
            document.getElementById(
                'final_inspection_id'
            );


        function filterInspections()
        {
            const requestId =
                requestSelect.value;


            const options =
                inspectionSelect.querySelectorAll(
                    'option[data-request-id]'
                );


            options.forEach(
                function (option) {

                    option.hidden =
                        option.dataset.requestId !==
                        requestId;

                }
            );


            const selected =
                inspectionSelect.options[
                    inspectionSelect.selectedIndex
                ];


            if (
                selected &&
                selected.dataset.requestId &&
                selected.dataset.requestId !== requestId
            ) {

                inspectionSelect.value = '';

            }
        }


        requestSelect.addEventListener(
            'change',
            filterInspections
        );


        filterInspections();

    }
);

</script>

@endsection