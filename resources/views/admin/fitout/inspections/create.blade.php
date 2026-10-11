@extends('layouts.app')

@section('title', 'Schedule Inspection')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                Schedule Inspection
            </h4>

            <p class="text-muted mb-0">
                Schedule a new fit-out inspection.
            </p>
        </div>

        <a
            href="{{ route('admin.fitout.inspections.index') }}"
            class="btn btn-secondary"
        >
            <i class="bi bi-arrow-left me-1"></i>
            Back
        </a>

    </div>


    {{-- Validation Errors --}}
    @if ($errors->any())

        <div class="alert alert-danger">

            <strong>
                Please fix the following errors:
            </strong>

            <ul class="mb-0 mt-2">

                @foreach ($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    <form
        action="{{ route('admin.fitout.inspections.store') }}"
        method="POST"
    >

        @csrf


        <div class="row">


            {{-- LEFT SIDE --}}
            <div class="col-lg-8">


                {{-- Fit-Out Request --}}
                <div class="card mb-4">

                    <div class="card-header">

                        <h5 class="mb-0">
                            Fit-Out Request
                        </h5>

                    </div>


                    <div class="card-body">

                        <div class="mb-3">

                            <label class="form-label">
                                Fit-Out Request
                                <span class="text-danger">*</span>
                            </label>

                            <select
                                name="fitout_request_id"
                                id="fitout_request_id"
                                class="form-select @error('fitout_request_id') is-invalid @enderror"
                                required
                            >

                                <option value="">
                                    Select Fit-Out Request
                                </option>

                                @foreach($fitoutRequests as $request)

                                    <option
                                        value="{{ $request->id }}"
                                        {{ old('fitout_request_id') == $request->id ? 'selected' : '' }}
                                    >

                                        {{ $request->request_no }}

                                        @if($request->tenant)
                                            -
                                            {{ $request->tenant->company_name ?? $request->tenant->company_name ?? '' }}
                                        @endif

                                    </option>

                                @endforeach

                            </select>


                            @error('fitout_request_id')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- Request Information --}}
                        <div
                            id="requestInformation"
                            class="border rounded p-3 bg-light d-none"
                        >

                            <div class="row">

                                <div class="col-md-6 mb-2">

                                    <small class="text-muted">
                                        Request No.
                                    </small>

                                    <div
                                        id="requestNo"
                                        class="fw-semibold"
                                    >
                                        -
                                    </div>

                                </div>


                                <div class="col-md-6 mb-2">

                                    <small class="text-muted">
                                        Tenant
                                    </small>

                                    <div
                                        id="tenantName"
                                        class="fw-semibold"
                                    >
                                        -
                                    </div>

                                </div>


                                <div class="col-md-6 mb-2">

                                    <small class="text-muted">
                                        Unit
                                    </small>

                                    <div
                                        id="unitName"
                                        class="fw-semibold"
                                    >
                                        -
                                    </div>

                                </div>


                                <div class="col-md-6 mb-2">

                                    <small class="text-muted">
                                        Contractor
                                    </small>

                                    <div
                                        id="contractorName"
                                        class="fw-semibold"
                                    >
                                        -
                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- Inspection Details --}}
                <div class="card mb-4">

                    <div class="card-header">

                        <h5 class="mb-0">
                            Inspection Details
                        </h5>

                    </div>


                    <div class="card-body">

                        <div class="row">


                            {{-- Inspection Type --}}
                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Inspection Type
                                    <span class="text-danger">*</span>
                                </label>

                                <select
                                    name="inspection_type"
                                    class="form-select @error('inspection_type') is-invalid @enderror"
                                    required
                                >

                                    <option value="">
                                        Select Inspection Type
                                    </option>

                                    <option
                                        value="Initial Site Inspection"
                                        {{ old('inspection_type') == 'Initial Site Inspection' ? 'selected' : '' }}
                                    >
                                        Initial Site Inspection
                                    </option>

                                    <option
                                        value="Civil Inspection"
                                        {{ old('inspection_type') == 'Civil Inspection' ? 'selected' : '' }}
                                    >
                                        Civil Inspection
                                    </option>

                                    <option
                                        value="Electrical Inspection"
                                        {{ old('inspection_type') == 'Electrical Inspection' ? 'selected' : '' }}
                                    >
                                        Electrical Inspection
                                    </option>

                                    <option
                                        value="Plumbing Inspection"
                                        {{ old('inspection_type') == 'Plumbing Inspection' ? 'selected' : '' }}
                                    >
                                        Plumbing Inspection
                                    </option>

                                    <option
                                        value="HVAC Inspection"
                                        {{ old('inspection_type') == 'HVAC Inspection' ? 'selected' : '' }}
                                    >
                                        HVAC Inspection
                                    </option>

                                    <option
                                        value="Fire & Safety Inspection"
                                        {{ old('inspection_type') == 'Fire & Safety Inspection' ? 'selected' : '' }}
                                    >
                                        Fire & Safety Inspection
                                    </option>

                                    <option
                                        value="Shop Front Inspection"
                                        {{ old('inspection_type') == 'Shop Front Inspection' ? 'selected' : '' }}
                                    >
                                        Shop Front Inspection
                                    </option>

                                    <option
                                        value="Signage Inspection"
                                        {{ old('inspection_type') == 'Signage Inspection' ? 'selected' : '' }}
                                    >
                                        Signage Inspection
                                    </option>

                                    <option
                                        value="Final Inspection"
                                        {{ old('inspection_type') == 'Final Inspection' ? 'selected' : '' }}
                                    >
                                        Final Inspection
                                    </option>

                                    <option
                                        value="Re-Inspection"
                                        {{ old('inspection_type') == 'Re-Inspection' ? 'selected' : '' }}
                                    >
                                        Re-Inspection
                                    </option>

                                </select>


                                @error('inspection_type')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            {{-- Stage --}}
                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Fit-Out Stage
                                </label>

                                <select
                                    name="fitout_stage_id"
                                    id="fitout_stage_id"
                                    class="form-select @error('fitout_stage_id') is-invalid @enderror"
                                >

                                    <option value="">
                                        Select Stage
                                    </option>

                                </select>


                                @error('fitout_stage_id')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                        </div>


                        {{-- Schedule --}}
                        <div class="row">


                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Scheduled Date
                                    <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="date"
                                    name="scheduled_date"
                                    class="form-control @error('scheduled_date') is-invalid @enderror"
                                    value="{{ old('scheduled_date') }}"
                                    required
                                >

                                @error('scheduled_date')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Scheduled Time
                                </label>

                                <input
                                    type="time"
                                    name="scheduled_time"
                                    class="form-control @error('scheduled_time') is-invalid @enderror"
                                    value="{{ old('scheduled_time') }}"
                                >

                                @error('scheduled_time')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                        </div>


                        {{-- Inspector --}}
                        <div class="mb-3">

                            <label class="form-label">
                                Inspector
                            </label>

                            <select
                                name="inspector_id"
                                class="form-select @error('inspector_id') is-invalid @enderror"
                            >

                                <option value="">
                                    Select Inspector
                                </option>

                                @foreach($users as $user)

                                    <option
                                        value="{{ $user->id }}"
                                        {{ old('inspector_id') == $user->id ? 'selected' : '' }}
                                    >

                                        {{ $user->name }}

                                        @if($user->email)
                                            - {{ $user->email }}
                                        @endif

                                    </option>

                                @endforeach

                            </select>


                            @error('inspector_id')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- Observations --}}
                        <div class="mb-3">

                            <label class="form-label">
                                Initial Observations
                            </label>

                            <textarea
                                name="observations"
                                rows="4"
                                class="form-control @error('observations') is-invalid @enderror"
                                placeholder="Enter any initial observations..."
                            >{{ old('observations') }}</textarea>


                            @error('observations')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- Recommendations --}}
                        <div class="mb-3">

                            <label class="form-label">
                                Recommendations
                            </label>

                            <textarea
                                name="recommendations"
                                rows="4"
                                class="form-control @error('recommendations') is-invalid @enderror"
                                placeholder="Enter any recommendations..."
                            >{{ old('recommendations') }}</textarea>


                            @error('recommendations')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>

                    </div>

                </div>

            </div>


            {{-- RIGHT SIDE --}}
            <div class="col-lg-4">


                {{-- Inspection Number --}}
                <div class="card mb-4">

                    <div class="card-header">

                        <h5 class="mb-0">
                            Inspection Information
                        </h5>

                    </div>


                    <div class="card-body">

                        <div class="alert alert-info mb-0">

                            <i class="bi bi-info-circle me-1"></i>

                            Inspection number will be generated
                            automatically after saving.

                        </div>

                    </div>

                </div>


                {{-- Status --}}
                <div class="card mb-4">

                    <div class="card-header">

                        <h5 class="mb-0">
                            Initial Status
                        </h5>

                    </div>


                    <div class="card-body">

                        <div class="d-flex align-items-center">

                            <span class="badge bg-primary">
                                Scheduled
                            </span>

                            <span class="text-muted ms-2">
                                New inspections start as Scheduled.
                            </span>

                        </div>

                    </div>

                </div>


                {{-- Actions --}}
                <div class="card">

                    <div class="card-body">

                        <button
                            type="submit"
                            class="btn btn-primary w-100 mb-2"
                        >

                            <i class="bi bi-calendar-check me-1"></i>

                            Schedule Inspection

                        </button>


                        <a
                            href="{{ route('admin.fitout.inspections.index') }}"
                            class="btn btn-light border w-100"
                        >

                            Cancel

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </form>

</div>


{{-- Data --}}
<script>

    const fitoutRequests = @json($fitoutRequestData);


    const requestSelect =
        document.getElementById('fitout_request_id');

    const stageSelect =
        document.getElementById('fitout_stage_id');

    const requestInformation =
        document.getElementById('requestInformation');


    requestSelect.addEventListener(
        'change',
        function () {

            const requestId =
                parseInt(this.value);

            stageSelect.innerHTML =
                '<option value="">Select Stage</option>';


            if (!requestId) {

                requestInformation.classList.add('d-none');

                return;
            }


            const selectedRequest =
                fitoutRequests.find(
                    request => request.id === requestId
                );


            if (!selectedRequest) {

                requestInformation.classList.add('d-none');

                return;
            }


            /*
            |--------------------------------------------------------------------------
            | Request Information
            |--------------------------------------------------------------------------
            */

            document.getElementById('requestNo').textContent =
                selectedRequest.request_no || '-';


            document.getElementById('tenantName').textContent =
                selectedRequest.tenant || '-';


            document.getElementById('unitName').textContent =
                selectedRequest.unit || '-';


            document.getElementById('contractorName').textContent =
                selectedRequest.contractor || '-';


            requestInformation.classList.remove('d-none');


            /*
            |--------------------------------------------------------------------------
            | Stages
            |--------------------------------------------------------------------------
            */

            if (
                selectedRequest.stages &&
                selectedRequest.stages.length
            ) {

                selectedRequest.stages
                    .sort(function (a, b) {

                        return (
                            a.stage_sequence -
                            b.stage_sequence
                        );

                    })
                    .forEach(function (stage) {

                        const option =
                            document.createElement('option');

                        option.value =
                            stage.id;

                        option.textContent =
                            stage.stage_sequence +
                            ' - ' +
                            stage.stage_name;

                        stageSelect.appendChild(option);

                    });

            }

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Restore old request after validation
    |--------------------------------------------------------------------------
    */

    document.addEventListener(
        'DOMContentLoaded',
        function () {

            const oldRequest =
                "{{ old('fitout_request_id') }}";

            if (oldRequest) {

                requestSelect.value =
                    oldRequest;

                requestSelect.dispatchEvent(
                    new Event('change')
                );


                const oldStage =
                    "{{ old('fitout_stage_id') }}";

                if (oldStage) {

                    setTimeout(function () {

                        stageSelect.value =
                            oldStage;

                    }, 100);

                }

            }

        }
    );

</script>

@endsection