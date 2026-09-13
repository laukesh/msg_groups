@php
    $compliance = $compliance ?? null;
@endphp

<form method="POST" action="{{ $formAction }}">
    @csrf

    @if($formMethod)
        @method($formMethod)
    @endif

    <div class="card">

        <div class="card-header">
            <strong>Environmental Compliance Details</strong>
        </div>

        <div class="card-body">

            <div class="row g-3">

                {{-- Compliance Number --}}
                <div class="col-md-4">

                    <label class="form-label">
                        Compliance Number
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        value="{{ $complianceNumber ?? $compliance?->compliance_number }}"
                        readonly
                    >

                    <input
                        type="hidden"
                        name="compliance_number"
                        value="{{ $complianceNumber ?? $compliance?->compliance_number }}"
                    >

                </div>


                {{-- Title --}}
                <div class="col-md-8">

                    <label class="form-label">
                        Compliance Title
                        <span class="text-danger">*</span>
                    </label>

                    <input
                        type="text"
                        name="compliance_title"
                        class="form-control @error('compliance_title') is-invalid @enderror"
                        value="{{ old(
                            'compliance_title',
                            $compliance?->compliance_title
                        ) }}"
                        placeholder="e.g. Environmental Clearance Compliance"
                        required
                    >

                    @error('compliance_title')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- Compliance Type --}}
                <div class="col-md-4">

                    <label class="form-label">
                        Compliance Type
                        <span class="text-danger">*</span>
                    </label>

                    <select
                        name="compliance_type"
                        class="form-select @error('compliance_type') is-invalid @enderror"
                        required
                    >

                        <option value="">
                            -- Select Type --
                        </option>

                        @foreach([
                            'Environmental Clearance',
                            'Consent / Authorization',
                            'Pollution Control',
                            'Waste Management',
                            'Water Management',
                            'Air Quality',
                            'Noise Compliance',
                            'Hazardous Waste',
                            'Construction Waste',
                            'Biodiversity',
                            'Tree / Vegetation',
                            'Energy / Emission',
                            'Permit / License',
                            'Legal Requirement',
                            'Other',
                        ] as $type)

                            <option
                                value="{{ $type }}"
                                @selected(
                                    old(
                                        'compliance_type',
                                        $compliance?->compliance_type
                                    ) === $type
                                )
                            >
                                {{ $type }}
                            </option>

                        @endforeach

                    </select>

                    @error('compliance_type')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- Regulatory Authority --}}
                <div class="col-md-4">

                    <label class="form-label">
                        Regulatory Authority
                    </label>

                    <input
                        type="text"
                        name="regulatory_authority"
                        class="form-control @error('regulatory_authority') is-invalid @enderror"
                        value="{{ old(
                            'regulatory_authority',
                            $compliance?->regulatory_authority
                        ) }}"
                        placeholder="Authority / Department"
                    >

                    @error('regulatory_authority')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- Legislation --}}
                <div class="col-md-4">

                    <label class="form-label">
                        Legislation / Reference
                    </label>

                    <input
                        type="text"
                        name="legislation_reference"
                        class="form-control @error('legislation_reference') is-invalid @enderror"
                        value="{{ old(
                            'legislation_reference',
                            $compliance?->legislation_reference
                        ) }}"
                        placeholder="Act / Rule / Notification"
                    >

                    @error('legislation_reference')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- Permit --}}
                <div class="col-md-6">

                    <label class="form-label">
                        Permit / License Number
                    </label>

                    <input
                        type="text"
                        name="permit_license_number"
                        class="form-control @error('permit_license_number') is-invalid @enderror"
                        value="{{ old(
                            'permit_license_number',
                            $compliance?->permit_license_number
                        ) }}"
                        placeholder="Permit or license number"
                    >

                    @error('permit_license_number')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- Applicable From --}}
                <div class="col-md-3">

                    <label class="form-label">
                        Applicable From
                    </label>

                    <input
                        type="date"
                        name="applicable_from"
                        class="form-control @error('applicable_from') is-invalid @enderror"
                        value="{{ old(
                            'applicable_from',
                            $compliance?->applicable_from?->format('Y-m-d')
                        ) }}"
                    >

                    @error('applicable_from')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- Due Date --}}
                <div class="col-md-3">

                    <label class="form-label">
                        Due Date
                    </label>

                    <input
                        type="date"
                        name="due_date"
                        class="form-control @error('due_date') is-invalid @enderror"
                        value="{{ old(
                            'due_date',
                            $compliance?->due_date?->format('Y-m-d')
                        ) }}"
                    >

                    @error('due_date')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- Review Date --}}
                <div class="col-md-3">

                    <label class="form-label">
                        Review Date
                    </label>

                    <input
                        type="date"
                        name="review_date"
                        class="form-control @error('review_date') is-invalid @enderror"
                        value="{{ old(
                            'review_date',
                            $compliance?->review_date?->format('Y-m-d')
                        ) }}"
                    >

                    @error('review_date')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- Compliance Status --}}
                <div class="col-md-3">

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
                                        $compliance?->compliance_status ?? 'Pending'
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


                {{-- Risk --}}
                <div class="col-md-4">

                    <label class="form-label">
                        Risk Level
                        <span class="text-danger">*</span>
                    </label>

                    <select
                        name="risk_level"
                        class="form-select @error('risk_level') is-invalid @enderror"
                        required
                    >

                        @foreach([
                            'Low',
                            'Medium',
                            'High',
                            'Critical',
                        ] as $risk)

                            <option
                                value="{{ $risk }}"
                                @selected(
                                    old(
                                        'risk_level',
                                        $compliance?->risk_level ?? 'Medium'
                                    ) === $risk
                                )
                            >
                                {{ $risk }}
                            </option>

                        @endforeach

                    </select>

                    @error('risk_level')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

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
                                        $compliance?->responsible_person_id
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


                {{-- Requirement --}}
                <div class="col-12">

                    <label class="form-label">
                        Requirement Description
                    </label>

                    <textarea
                        name="requirement_description"
                        rows="4"
                        class="form-control @error('requirement_description') is-invalid @enderror"
                        placeholder="Describe the environmental compliance requirement..."
                    >{{ old(
                        'requirement_description',
                        $compliance?->requirement_description
                    ) }}</textarea>

                    @error('requirement_description')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- Evidence --}}
                <div class="col-md-4">

                    <label class="form-label d-block">
                        Evidence Available
                    </label>

                    <div class="form-check form-switch">

                        <input
                            type="hidden"
                            name="evidence_available"
                            value="0"
                        >

                        <input
                            class="form-check-input"
                            type="checkbox"
                            name="evidence_available"
                            value="1"
                            id="evidence_available"
                            @checked(
                                old(
                                    'evidence_available',
                                    $compliance?->evidence_available ?? false
                                )
                            )
                        >

                        <label
                            class="form-check-label"
                            for="evidence_available"
                        >
                            Evidence Available
                        </label>

                    </div>

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
                                    $compliance?->corrective_action_required ?? false
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


                {{-- Record Status --}}
                <div class="col-md-4">

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
                                        $compliance?->status ?? 'Draft'
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


                {{-- Evidence Description --}}
                <div class="col-12">

                    <label class="form-label">
                        Evidence Description
                    </label>

                    <textarea
                        name="evidence_description"
                        rows="3"
                        class="form-control @error('evidence_description') is-invalid @enderror"
                        placeholder="Describe available evidence, certificates, permits, reports..."
                    >{{ old(
                        'evidence_description',
                        $compliance?->evidence_description
                    ) }}</textarea>

                    @error('evidence_description')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- Non Compliance --}}
                <div class="col-12">

                    <label class="form-label">
                        Non-Compliance Details
                    </label>

                    <textarea
                        name="non_compliance_details"
                        rows="4"
                        class="form-control @error('non_compliance_details') is-invalid @enderror"
                        placeholder="Describe any non-compliance, deviation or violation..."
                    >{{ old(
                        'non_compliance_details',
                        $compliance?->non_compliance_details
                    ) }}</textarea>

                    @error('non_compliance_details')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- Corrective Action --}}
                <div class="col-12">

                    <label class="form-label">
                        Corrective Action
                    </label>

                    <textarea
                        name="corrective_action"
                        rows="4"
                        class="form-control @error('corrective_action') is-invalid @enderror"
                        placeholder="Required corrective action..."
                    >{{ old(
                        'corrective_action',
                        $compliance?->corrective_action
                    ) }}</textarea>

                    @error('corrective_action')
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
                        rows="3"
                        class="form-control @error('remarks') is-invalid @enderror"
                    >{{ old(
                        'remarks',
                        $compliance?->remarks
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