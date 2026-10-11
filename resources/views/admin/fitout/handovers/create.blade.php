@extends('layouts.app')

@section('title', 'Create Handover')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                Create Handover
            </h4>

            <p class="text-muted mb-0">
                Create a fit-out handover record.
            </p>
        </div>

        <a
            href="{{ route('admin.fitout.handovers.index') }}"
            class="btn btn-secondary"
        >
            <i class="bi bi-arrow-left me-1"></i>
            Back
        </a>

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
        action="{{ route('admin.fitout.handovers.store') }}"
        method="POST"
        enctype="multipart/form-data"
    >

        @csrf


        <div class="row">

            {{-- ===================================================== --}}
            {{-- LEFT COLUMN --}}
            {{-- ===================================================== --}}

            <div class="col-lg-8">


                {{-- ================================================= --}}
                {{-- FIT-OUT DETAILS --}}
                {{-- ================================================= --}}

                <div class="card mb-4">

                    <div class="card-header">

                        <h5 class="mb-0">
                            Fit-Out Details
                        </h5>

                    </div>


                    <div class="card-body">

                        <div class="row">


                            {{-- Fit-Out Request --}}
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
                                                old('fitout_request_id')
                                                == $fitoutRequest->id
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

                                <small class="text-muted">
                                    Select the completed fit-out request for handover.
                                </small>

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
                                                old('unit_id')
                                                == $unit->id
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
                                                old('tenant_id')
                                                == $tenant->id
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
                                                old('contractor_id')
                                                == $contractor->id
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


                {{-- ================================================= --}}
                {{-- HANDOVER DETAILS --}}
                {{-- ================================================= --}}

                <div class="card mb-4">

                    <div class="card-header">

                        <h5 class="mb-0">
                            Handover Details
                        </h5>

                    </div>


                    <div class="card-body">

                        <div class="row">


                            {{-- Handover Type --}}
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
                                                    'Final Handover'
                                                ) === $type
                                            )
                                        >

                                            {{ $type }}

                                        </option>

                                    @endforeach

                                </select>

                            </div>


                            {{-- Handover Date --}}
                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Handover Date
                                </label>

                                <input
                                    type="date"
                                    name="handover_date"
                                    class="form-control"
                                    value="{{ old('handover_date') }}"
                                >

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
                                                    'final_inspection_id'
                                                )
                                                == $inspection->id
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

                                <small class="text-muted">

                                    Only passed final inspections should be selected.

                                </small>

                            </div>


                            {{-- Unit Condition --}}
                            <div class="col-md-6 mb-3">

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
                                                    'unit_condition'
                                                ) === $condition
                                            )
                                        >

                                            {{ $condition }}

                                        </option>

                                    @endforeach

                                </select>

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
                                        'In Progress'
                                    ] as $status)

                                        <option
                                            value="{{ $status }}"
                                            @selected(
                                                old(
                                                    'status',
                                                    'Pending'
                                                ) === $status
                                            )
                                        >

                                            {{ $status }}

                                        </option>

                                    @endforeach

                                </select>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- KEYS & ACCESS --}}
                {{-- ================================================= --}}

                <div class="card mb-4">

                    <div class="card-header">

                        <h5 class="mb-0">
                            Keys & Access
                        </h5>

                    </div>


                    <div class="card-body">

                        <div class="row">


                            {{-- Key Count --}}
                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Key Count
                                </label>

                                <input
                                    type="number"
                                    name="key_count"
                                    class="form-control"
                                    min="0"
                                    value="{{ old(
                                        'key_count',
                                        0
                                    ) }}"
                                >

                            </div>


                            {{-- Access Cards --}}
                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Access Card Count
                                </label>

                                <input
                                    type="number"
                                    name="access_card_count"
                                    class="form-control"
                                    min="0"
                                    value="{{ old(
                                        'access_card_count',
                                        0
                                    ) }}"
                                >

                            </div>

                        </div>

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- ELECTRICITY --}}
                {{-- ================================================= --}}

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
                                        'electricity_meter_no'
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
                                    class="form-control"
                                    step="0.01"
                                    min="0"
                                    value="{{ old(
                                        'electricity_meter_reading'
                                    ) }}"
                                >

                            </div>

                        </div>

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- WATER --}}
                {{-- ================================================= --}}

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
                                        'water_meter_no'
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
                                    class="form-control"
                                    step="0.01"
                                    min="0"
                                    value="{{ old(
                                        'water_meter_reading'
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

                        <label class="form-label">
                            Handover Document
                        </label>

                        <input
                            type="file"
                            name="handover_document"
                            class="form-control"
                            accept=".pdf,.jpg,.jpeg,.png"
                        >

                        <small class="text-muted">
                            Accepted formats: PDF, JPG, JPEG, PNG.
                            Maximum size: 10 MB.
                        </small>

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- REMARKS --}}
                {{-- ================================================= --}}

                <div class="card mb-4">

                    <div class="card-header">

                        <h5 class="mb-0">
                            Remarks
                        </h5>

                    </div>


                    <div class="card-body">

                        <textarea
                            name="remarks"
                            class="form-control"
                            rows="5"
                            placeholder="Enter handover remarks..."
                        >{{ old('remarks') }}</textarea>

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- SUBMIT --}}
                {{-- ================================================= --}}

                <div class="card mb-4">

                    <div class="card-body">

                        <div class="d-flex justify-content-end gap-2">

                            <a
                                href="{{ route(
                                    'admin.fitout.handovers.index'
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

                                Create Handover

                            </button>

                        </div>

                    </div>

                </div>

            </div>


            {{-- ===================================================== --}}
            {{-- RIGHT COLUMN --}}
            {{-- ===================================================== --}}

            <div class="col-lg-4">


                <div class="card mb-4">

                    <div class="card-header">

                        <h5 class="mb-0">
                            Handover Process
                        </h5>

                    </div>


                    <div class="card-body">

                        <div class="d-flex mb-3">

                            <div class="me-3">
                                <i class="bi bi-1-circle fs-4"></i>
                            </div>

                            <div>
                                <strong>
                                    Select Fit-Out Request
                                </strong>

                                <small class="d-block text-muted">
                                    Select the fit-out request ready for handover.
                                </small>
                            </div>

                        </div>


                        <div class="d-flex mb-3">

                            <div class="me-3">
                                <i class="bi bi-2-circle fs-4"></i>
                            </div>

                            <div>
                                <strong>
                                    Verify Final Inspection
                                </strong>

                                <small class="d-block text-muted">
                                    Select a passed final inspection.
                                </small>
                            </div>

                        </div>


                        <div class="d-flex mb-3">

                            <div class="me-3">
                                <i class="bi bi-3-circle fs-4"></i>
                            </div>

                            <div>
                                <strong>
                                    Record Unit Condition
                                </strong>

                                <small class="d-block text-muted">
                                    Record keys, access cards and meter readings.
                                </small>
                            </div>

                        </div>


                        <div class="d-flex">

                            <div class="me-3">
                                <i class="bi bi-4-circle fs-4"></i>
                            </div>

                            <div>
                                <strong>
                                    Complete Handover
                                </strong>

                                <small class="d-block text-muted">
                                    Tenant, contractor and mall approvals will follow.
                                </small>
                            </div>

                        </div>

                    </div>

                </div>


                <div class="alert alert-info">

                    <i class="bi bi-info-circle me-1"></i>

                    Handover should normally be created only after
                    the final inspection has passed and outstanding
                    snags have been resolved.

                </div>


                {{-- Selected Request Information --}}
                <div
                    class="card d-none"
                    id="requestSummary"
                >

                    <div class="card-header">

                        <h5 class="mb-0">
                            Selected Request
                        </h5>

                    </div>


                    <div class="card-body">

                        <div class="mb-2">

                            <small class="text-muted">
                                Request
                            </small>

                            <div id="summaryRequest">
                                -
                            </div>

                        </div>


                        <div class="mb-2">

                            <small class="text-muted">
                                Unit
                            </small>

                            <div id="summaryUnit">
                                -
                            </div>

                        </div>


                        <div>

                            <small class="text-muted">
                                Contractor
                            </small>

                            <div id="summaryContractor">
                                -
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </form>

</div>


{{-- ============================================================= --}}
{{-- JAVASCRIPT --}}
{{-- ============================================================= --}}

<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        const requestSelect =
            document.getElementById(
                'fitout_request_id'
            );

        const unitSelect =
            document.getElementById(
                'unit_id'
            );

        const tenantSelect =
            document.getElementById(
                'tenant_id'
            );

        const contractorSelect =
            document.getElementById(
                'contractor_id'
            );

        const inspectionSelect =
            document.getElementById(
                'final_inspection_id'
            );

        const requestSummary =
            document.getElementById(
                'requestSummary'
            );


        function updateRequestData()
        {
            const selectedOption =
                requestSelect.options[
                    requestSelect.selectedIndex
                ];


            if (
                !selectedOption ||
                !selectedOption.value
            ) {

                requestSummary.classList.add(
                    'd-none'
                );

                return;
            }


            const unitId =
                selectedOption.dataset.unitId;

            const tenantId =
                selectedOption.dataset.tenantId;

            const contractorId =
                selectedOption.dataset.contractorId;


            /*
            |--------------------------------------------------------------------------
            | Set Unit
            |--------------------------------------------------------------------------
            */

            if (unitId) {

                unitSelect.value =
                    unitId;

            }


            /*
            |--------------------------------------------------------------------------
            | Set Tenant
            |--------------------------------------------------------------------------
            */

            if (tenantId) {

                tenantSelect.value =
                    tenantId;

            }


            /*
            |--------------------------------------------------------------------------
            | Set Contractor
            |--------------------------------------------------------------------------
            */

            if (contractorId) {

                contractorSelect.value =
                    contractorId;

            }


            /*
            |--------------------------------------------------------------------------
            | Summary
            |--------------------------------------------------------------------------
            */

            const requestText =
                selectedOption.textContent.trim();

            const unitText =
                unitSelect.options[
                    unitSelect.selectedIndex
                ]?.textContent.trim() || '-';

            const contractorText =
                contractorSelect.options[
                    contractorSelect.selectedIndex
                ]?.textContent.trim() || '-';


            document.getElementById(
                'summaryRequest'
            ).textContent =
                requestText;

            document.getElementById(
                'summaryUnit'
            ).textContent =
                unitText;

            document.getElementById(
                'summaryContractor'
            ).textContent =
                contractorText;


            requestSummary.classList.remove(
                'd-none'
            );


            /*
            |--------------------------------------------------------------------------
            | Filter Final Inspections
            |--------------------------------------------------------------------------
            */

            const options =
                inspectionSelect.querySelectorAll(
                    'option[data-request-id]'
                );


            options.forEach(
                function (option) {

                    if (
                        option.dataset.requestId ===
                        selectedOption.value
                    ) {

                        option.hidden = false;

                    } else {

                        option.hidden = true;

                    }

                }
            );


            /*
            |--------------------------------------------------------------------------
            | Clear invalid inspection
            |--------------------------------------------------------------------------
            */

            const currentInspection =
                inspectionSelect.options[
                    inspectionSelect.selectedIndex
                ];


            if (
                currentInspection &&
                currentInspection.dataset.requestId &&
                currentInspection.dataset.requestId !==
                    selectedOption.value
            ) {

                inspectionSelect.value = '';

            }

        }


        requestSelect.addEventListener(
            'change',
            updateRequestData
        );


        /*
        |--------------------------------------------------------------------------
        | Initial Load
        |--------------------------------------------------------------------------
        */

        if (requestSelect.value) {

            updateRequestData();

        }

    }
);

</script>

@endsection