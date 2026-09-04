@php

    $inspection = $inspection ?? null;
    $users = $users ?? collect();
    $contracts = $contracts ?? collect();

@endphp


<form
    method="POST"
    action="{{ $action }}"
>

    @csrf

    @if($method)

        @method($method)

    @endif


    {{-- =========================================================
        INSPECTION DETAILS
    ========================================================== --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Inspection Details
            </strong>

        </div>


        <div class="card-body">

            <div class="row g-3">


                {{-- Inspection Number --}}

                <div class="col-md-4">

                    <label class="form-label">
                        Inspection Number
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        value="{{ $inspectionNumber ?? $inspection?->inspection_number }}"
                        readonly
                    >

                </div>


                {{-- Inspection Date --}}

                <div class="col-md-4">

                    <label class="form-label">

                        Inspection Date

                        <span class="text-danger">*</span>

                    </label>

                    <input
                        type="date"
                        name="inspection_date"
                        class="form-control @error('inspection_date') is-invalid @enderror"
                        value="{{ old(
                            'inspection_date',
                            $inspection?->inspection_date?->format('Y-m-d')
                        ) }}"
                        required
                    >

                    @error('inspection_date')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- Inspection Type --}}

                <div class="col-md-4">

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
                            -- Select Inspection Type --
                        </option>


                        @foreach([
                            'Site Safety Inspection',
                            'Daily HSE Inspection',
                            'Weekly HSE Inspection',
                            'Work-at-Height Inspection',
                            'Scaffolding Inspection',
                            'Electrical Safety Inspection',
                            'Fire Safety Inspection',
                            'PPE Inspection',
                            'Housekeeping Inspection',
                            'Equipment Safety Inspection',
                            'Environmental Inspection',
                            'Other',
                        ] as $type)

                            <option
                                value="{{ $type }}"
                                @selected(
                                    old(
                                        'inspection_type',
                                        $inspection?->inspection_type
                                    ) === $type
                                )
                            >
                                {{ $type }}
                            </option>

                        @endforeach

                    </select>


                    @error('inspection_type')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- Inspection Title --}}

                <div class="col-md-8">

                    <label class="form-label">
                        Inspection Title
                    </label>

                    <input
                        type="text"
                        name="inspection_title"
                        class="form-control @error('inspection_title') is-invalid @enderror"
                        value="{{ old(
                            'inspection_title',
                            $inspection?->inspection_title
                        ) }}"
                        placeholder="Enter inspection title"
                    >

                    @error('inspection_title')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- Location --}}

                <div class="col-md-4">

                    <label class="form-label">
                        Location
                    </label>

                    <input
                        type="text"
                        name="location"
                        class="form-control @error('location') is-invalid @enderror"
                        value="{{ old(
                            'location',
                            $inspection?->location
                        ) }}"
                        placeholder="Inspection location"
                    >

                    @error('location')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- Contract --}}

                <div class="col-md-6">

                    <label class="form-label">
                        Procurement Contract
                    </label>

                    <select
                        name="procurement_contract_id"
                        class="form-select @error('procurement_contract_id') is-invalid @enderror"
                    >

                        <option value="">
                            -- Select Contract --
                        </option>


                        @foreach($contracts as $contract)

                            <option
                                value="{{ $contract->id }}"
                                @selected(
                                    old(
                                        'procurement_contract_id',
                                        $inspection?->procurement_contract_id
                                    ) == $contract->id
                                )
                            >

                                {{ $contract->contract_number
                                    ?? $contract->contract_no
                                    ?? ('Contract #' . $contract->id)
                                }}

                            </option>

                        @endforeach

                    </select>


                    @error('procurement_contract_id')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- Inspector --}}

                <div class="col-md-6">

                    <label class="form-label">
                        Inspector
                    </label>

                    <select
                        name="inspector_id"
                        class="form-select @error('inspector_id') is-invalid @enderror"
                    >

                        <option value="">
                            -- Select Inspector --
                        </option>


                        @foreach($users as $user)

                            <option
                                value="{{ $user->id }}"
                                @selected(
                                    old(
                                        'inspector_id',
                                        $inspection?->inspector_id
                                    ) == $user->id
                                )
                            >
                                {{ $user->name }}
                            </option>

                        @endforeach

                    </select>


                    @error('inspector_id')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- Scope --}}

                <div class="col-12">

                    <label class="form-label">
                        Inspection Scope
                    </label>

                    <textarea
                        name="scope"
                        rows="4"
                        class="form-control @error('scope') is-invalid @enderror"
                        placeholder="Describe the inspection scope..."
                    >{{ old(
                        'scope',
                        $inspection?->scope
                    ) }}</textarea>


                    @error('scope')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- Status --}}

                <div class="col-md-4">

                    <label class="form-label">

                        Status

                        <span class="text-danger">*</span>

                    </label>


                    <select
                        name="status"
                        class="form-select @error('status') is-invalid @enderror"
                        required
                    >

                        @foreach([
                            'Planned',
                            'In Progress',
                            'Completed',
                            'Findings Raised',
                            'Verified',
                            'Closed',
                        ] as $status)

                            <option
                                value="{{ $status }}"
                                @selected(
                                    old(
                                        'status',
                                        $inspection?->status ?? 'Planned'
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


                {{-- Findings Summary --}}

                <div class="col-md-8">

                    <label class="form-label">
                        Findings Summary
                    </label>

                    <textarea
                        name="findings_summary"
                        rows="3"
                        class="form-control @error('findings_summary') is-invalid @enderror"
                        placeholder="Brief summary of inspection findings..."
                    >{{ old(
                        'findings_summary',
                        $inspection?->findings_summary
                    ) }}</textarea>


                    @error('findings_summary')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- Remarks --}}

                <div class="col-12">

                    <label class="form-label">
                        Remarks
                    </label>

                    <textarea
                        name="remarks"
                        rows="4"
                        class="form-control @error('remarks') is-invalid @enderror"
                        placeholder="Additional remarks..."
                    >{{ old(
                        'remarks',
                        $inspection?->remarks
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