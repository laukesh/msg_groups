@php
    $record = $record ?? null;
@endphp

<form
    method="POST"
    action="{{ $formAction }}"
>
    @csrf

    @if($formMethod)
        @method($formMethod)
    @endif

    <div class="card">

        <div class="card-header">
            <strong>Environmental Monitoring Record</strong>
        </div>

        <div class="card-body">

            <div class="row g-3">

                {{-- Record Number --}}
                <div class="col-md-4">

                    <label class="form-label">
                        Record Number
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        value="{{ $recordNumber ?? $record?->record_number }}"
                        readonly
                    >

                    <input
                        type="hidden"
                        name="record_number"
                        value="{{ $recordNumber ?? $record?->record_number }}"
                    >

                </div>


                {{-- Record Title --}}
                <div class="col-md-8">

                    <label class="form-label">
                        Record Title
                        <span class="text-danger">*</span>
                    </label>

                    <input
                        type="text"
                        name="record_title"
                        class="form-control @error('record_title') is-invalid @enderror"
                        value="{{ old(
                            'record_title',
                            $record?->record_title
                        ) }}"
                        placeholder="e.g. Site Noise Monitoring"
                        required
                    >

                    @error('record_title')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- Record Type --}}
                <div class="col-md-4">

                    <label class="form-label">
                        Record Type
                        <span class="text-danger">*</span>
                    </label>

                    <select
                        name="record_type"
                        class="form-select @error('record_type') is-invalid @enderror"
                        required
                    >

                        <option value="">
                            -- Select Type --
                        </option>

                        @foreach([
                            'Air Quality',
                            'Noise Monitoring',
                            'Water Quality',
                            'Waste Management',
                            'Dust Monitoring',
                            'Emission Monitoring',
                            'Soil Monitoring',
                            'Water Consumption',
                            'Energy Consumption',
                            'Environmental Inspection',
                            'Other',
                        ] as $type)

                            <option
                                value="{{ $type }}"
                                @selected(
                                    old(
                                        'record_type',
                                        $record?->record_type
                                    ) === $type
                                )
                            >
                                {{ $type }}
                            </option>

                        @endforeach

                    </select>

                    @error('record_type')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- Monitoring Date --}}
                <div class="col-md-4">

                    <label class="form-label">
                        Monitoring Date
                        <span class="text-danger">*</span>
                    </label>

                    <input
                        type="date"
                        name="monitoring_date"
                        class="form-control @error('monitoring_date') is-invalid @enderror"
                        value="{{ old(
                            'monitoring_date',
                            $record?->monitoring_date?->format('Y-m-d')
                        ) }}"
                        required
                    >

                    @error('monitoring_date')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- Monitoring Time --}}
                <div class="col-md-4">

                    <label class="form-label">
                        Monitoring Time
                    </label>

                    <input
                        type="time"
                        name="monitoring_time"
                        class="form-control @error('monitoring_time') is-invalid @enderror"
                        value="{{ old(
                            'monitoring_time',
                            $record?->monitoring_time
                        ) }}"
                    >

                    @error('monitoring_time')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- Location --}}
                <div class="col-md-6">

                    <label class="form-label">
                        Location
                    </label>

                    <input
                        type="text"
                        name="location"
                        class="form-control @error('location') is-invalid @enderror"
                        value="{{ old(
                            'location',
                            $record?->location
                        ) }}"
                        placeholder="Monitoring location"
                    >

                    @error('location')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- Monitoring Area --}}
                <div class="col-md-6">

                    <label class="form-label">
                        Monitoring Area
                    </label>

                    <input
                        type="text"
                        name="monitoring_area"
                        class="form-control @error('monitoring_area') is-invalid @enderror"
                        value="{{ old(
                            'monitoring_area',
                            $record?->monitoring_area
                        ) }}"
                        placeholder="Specific area / zone"
                    >

                    @error('monitoring_area')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- Environmental Parameter --}}
                <div class="col-md-6">

                    <label class="form-label">
                        Environmental Parameter
                    </label>

                    <input
                        type="text"
                        name="environmental_parameter"
                        class="form-control @error('environmental_parameter') is-invalid @enderror"
                        value="{{ old(
                            'environmental_parameter',
                            $record?->environmental_parameter
                        ) }}"
                        placeholder="e.g. Noise Level, PM10, pH"
                    >

                    @error('environmental_parameter')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- Parameter Value --}}
                <div class="col-md-3">

                    <label class="form-label">
                        Parameter Value
                    </label>

                    <input
                        type="number"
                        step="0.0001"
                        name="parameter_value"
                        class="form-control @error('parameter_value') is-invalid @enderror"
                        value="{{ old(
                            'parameter_value',
                            $record?->parameter_value
                        ) }}"
                    >

                    @error('parameter_value')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- Unit --}}
                <div class="col-md-3">

                    <label class="form-label">
                        Unit
                    </label>

                    <input
                        type="text"
                        name="unit"
                        class="form-control @error('unit') is-invalid @enderror"
                        value="{{ old(
                            'unit',
                            $record?->unit
                        ) }}"
                        placeholder="dB, mg/m³, pH"
                    >

                    @error('unit')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- Limit Value --}}
                <div class="col-md-4">

                    <label class="form-label">
                        Permissible Limit
                    </label>

                    <input
                        type="number"
                        step="0.0001"
                        name="limit_value"
                        class="form-control @error('limit_value') is-invalid @enderror"
                        value="{{ old(
                            'limit_value',
                            $record?->limit_value
                        ) }}"
                    >

                    @error('limit_value')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- Compliance Status --}}
                <div class="col-md-4">

                    <label class="form-label">
                        Compliance Status
                        <span class="text-danger">*</span>
                    </label>

                    <select
                        name="compliance_status"
                        class="form-select @error('compliance_status') is-invalid @enderror"
                        required
                    >

                        @foreach([
                            'Pending',
                            'Compliant',
                            'Non-Compliant',
                            'Not Applicable',
                        ] as $status)

                            <option
                                value="{{ $status }}"
                                @selected(
                                    old(
                                        'compliance_status',
                                        $record?->compliance_status ?? 'Pending'
                                    ) === $status
                                )
                            >
                                {{ $status }}
                            </option>

                        @endforeach

                    </select>

                    @error('compliance_status')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- Weather --}}
                <div class="col-md-4">

                    <label class="form-label">
                        Weather Condition
                    </label>

                    <input
                        type="text"
                        name="weather_condition"
                        class="form-control @error('weather_condition') is-invalid @enderror"
                        value="{{ old(
                            'weather_condition',
                            $record?->weather_condition
                        ) }}"
                        placeholder="Sunny, Cloudy, Rainy..."
                    >

                    @error('weather_condition')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- Observation --}}
                <div class="col-12">

                    <label class="form-label">
                        Observation
                    </label>

                    <textarea
                        name="observation"
                        rows="4"
                        class="form-control @error('observation') is-invalid @enderror"
                    >{{ old(
                        'observation',
                        $record?->observation
                    ) }}</textarea>

                    @error('observation')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- Corrective Action Required --}}
                <div class="col-md-4">

                    <label class="form-label d-block">
                        Corrective Action
                    </label>

                    <div class="form-check form-switch">

                        <input
                            type="hidden"
                            name="corrective_action_required"
                            value="0"
                        >

                        <input
                            class="form-check-input"
                            type="checkbox"
                            name="corrective_action_required"
                            value="1"
                            id="corrective_action_required"
                            @checked(
                                old(
                                    'corrective_action_required',
                                    $record?->corrective_action_required ?? false
                                )
                            )
                        >

                        <label
                            class="form-check-label"
                            for="corrective_action_required"
                        >
                            Corrective Action Required
                        </label>

                    </div>

                </div>


                {{-- Responsible Person --}}
                <div class="col-md-8">

                    <label class="form-label">
                        Responsible Person
                    </label>

                    <select
                        name="responsible_person_id"
                        class="form-select @error('responsible_person_id') is-invalid @enderror"
                    >

                        <option value="">
                            -- Select Responsible Person --
                        </option>

                        @foreach($users as $user)

                            <option
                                value="{{ $user->id }}"
                                @selected(
                                    (string) old(
                                        'responsible_person_id',
                                        $record?->responsible_person_id
                                    ) === (string) $user->id
                                )
                            >
                                {{ $user->name }}
                            </option>

                        @endforeach

                    </select>

                    @error('responsible_person_id')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- Corrective Action --}}
                <div class="col-12">

                    <label class="form-label">
                        Corrective Action Details
                    </label>

                    <textarea
                        name="corrective_action"
                        rows="4"
                        class="form-control @error('corrective_action') is-invalid @enderror"
                    >{{ old(
                        'corrective_action',
                        $record?->corrective_action
                    ) }}</textarea>

                    @error('corrective_action')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- Status --}}
                <div class="col-md-6">

                    <label class="form-label">
                        Record Status
                        <span class="text-danger">*</span>
                    </label>

                    <select
                        name="status"
                        class="form-select @error('status') is-invalid @enderror"
                        required
                    >

                        @foreach([
                            'Draft',
                            'Submitted',
                            'Approved',
                            'Closed',
                        ] as $status)

                            <option
                                value="{{ $status }}"
                                @selected(
                                    old(
                                        'status',
                                        $record?->status ?? 'Draft'
                                    ) === $status
                                )
                            >
                                {{ $status }}
                            </option>

                        @endforeach

                    </select>

                    @error('status')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- Remarks --}}
                <div class="col-md-6">

                    <label class="form-label">
                        Remarks
                    </label>

                    <textarea
                        name="remarks"
                        rows="3"
                        class="form-control @error('remarks') is-invalid @enderror"
                    >{{ old(
                        'remarks',
                        $record?->remarks
                    ) }}</textarea>

                    @error('remarks')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

            </div>

        </div>


        <div class="card-footer d-flex justify-content-end gap-2">

            <a
                href="{{ $cancelUrl }}"
                class="btn btn-outline-secondary"
            >
                Cancel
            </a>

            <button
                type="submit"
                class="btn btn-primary"
            >
                <i class="bi bi-save me-1"></i>
                {{ $submitLabel }}
            </button>

        </div>

    </div>

</form>