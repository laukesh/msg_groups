@php
    $finding = $finding ?? null;
    $items = $items ?? collect();
    $users = $users ?? collect();
@endphp

<form method="POST" action="{{ $action }}">

    @csrf

    @if($method)
        @method($method)
    @endif

    <div class="card">

        <div class="card-header">
            <strong>Inspection Finding Details</strong>
        </div>

        <div class="card-body">

            <div class="row g-3">

                {{-- Finding Number --}}
                <div class="col-md-4">

                    <label class="form-label">
                        Finding Number
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        value="{{ $findingNumber ?? $finding?->finding_number }}"
                        readonly
                    >

                </div>


                {{-- Finding Date --}}
                <div class="col-md-4">

                    <label class="form-label">
                        Finding Date
                        <span class="text-danger">*</span>
                    </label>

                    <input
                        type="date"
                        name="finding_date"
                        class="form-control @error('finding_date') is-invalid @enderror"
                        value="{{ old(
                            'finding_date',
                            $finding?->finding_date?->format('Y-m-d')
                        ) }}"
                        required
                    >

                    @error('finding_date')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- Finding Type --}}
                <div class="col-md-4">

                    <label class="form-label">
                        Finding Type
                    </label>

                    <select
                        name="finding_type"
                        class="form-select @error('finding_type') is-invalid @enderror"
                    >

                        <option value="">
                            -- Select Finding Type --
                        </option>

                        @foreach([
                            'Safety Non-Compliance',
                            'Unsafe Condition',
                            'Unsafe Act',
                            'PPE Violation',
                            'Fire Safety',
                            'Electrical Safety',
                            'Work at Height',
                            'Housekeeping',
                            'Environmental',
                            'Other',
                        ] as $type)

                            <option
                                value="{{ $type }}"
                                @selected(
                                    old(
                                        'finding_type',
                                        $finding?->finding_type
                                    ) === $type
                                )
                            >
                                {{ $type }}
                            </option>

                        @endforeach

                    </select>

                    @error('finding_type')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- Checklist Item --}}
                <div class="col-12">

                    <label class="form-label">
                        Related Checklist Item
                    </label>

                    <select
                        name="construction_hse_inspection_item_id"
                        class="form-select @error('construction_hse_inspection_item_id') is-invalid @enderror"
                    >

                        <option value="">
                            -- Select Checklist Item --
                        </option>

                        @foreach($items as $item)

                            <option
                                value="{{ $item->id }}"
                                @selected(
                                    old(
                                        'construction_hse_inspection_item_id',
                                        $finding?->construction_hse_inspection_item_id
                                    ) == $item->id
                                )
                            >

                                {{ $item->item_number }}

                                -

                                {{ \Illuminate\Support\Str::limit(
                                    $item->checklist_question,
                                    120
                                ) }}

                            </option>

                        @endforeach

                    </select>

                    @error('construction_hse_inspection_item_id')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- Finding Title --}}
                <div class="col-12">

                    <label class="form-label">
                        Finding Title
                        <span class="text-danger">*</span>
                    </label>

                    <input
                        type="text"
                        name="finding_title"
                        class="form-control @error('finding_title') is-invalid @enderror"
                        value="{{ old(
                            'finding_title',
                            $finding?->finding_title
                        ) }}"
                        placeholder="Enter finding title"
                        required
                    >

                    @error('finding_title')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- Description --}}
                <div class="col-12">

                    <label class="form-label">
                        Finding Description
                        <span class="text-danger">*</span>
                    </label>

                    <textarea
                        name="finding_description"
                        rows="5"
                        class="form-control @error('finding_description') is-invalid @enderror"
                        placeholder="Describe the finding in detail..."
                        required
                    >{{ old(
                        'finding_description',
                        $finding?->finding_description
                    ) }}</textarea>

                    @error('finding_description')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- Severity --}}
                <div class="col-md-4">

                    <label class="form-label">
                        Severity
                        <span class="text-danger">*</span>
                    </label>

                    <select
                        name="severity"
                        class="form-select @error('severity') is-invalid @enderror"
                        required
                    >

                        @foreach([
                            'Low',
                            'Medium',
                            'High',
                            'Critical',
                        ] as $severity)

                            <option
                                value="{{ $severity }}"
                                @selected(
                                    old(
                                        'severity',
                                        $finding?->severity ?? 'Medium'
                                    ) === $severity
                                )
                            >
                                {{ $severity }}
                            </option>

                        @endforeach

                    </select>

                    @error('severity')
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
                            'Open',
                            'In Progress',
                            'Action Required',
                            'Resolved',
                            'Verified',
                            'Closed',
                        ] as $status)

                            <option
                                value="{{ $status }}"
                                @selected(
                                    old(
                                        'status',
                                        $finding?->status ?? 'Open'
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


                {{-- Due Date --}}
                <div class="col-md-4">

                    <label class="form-label">
                        Due Date
                    </label>

                    <input
                        type="date"
                        name="due_date"
                        class="form-control @error('due_date') is-invalid @enderror"
                        value="{{ old(
                            'due_date',
                            $finding?->due_date?->format('Y-m-d')
                        ) }}"
                    >

                    @error('due_date')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- Immediate Action --}}
                <div class="col-md-6">

                    <label class="form-label">
                        Immediate Action
                    </label>

                    <textarea
                        name="immediate_action"
                        rows="4"
                        class="form-control @error('immediate_action') is-invalid @enderror"
                        placeholder="Action taken immediately after identifying the finding..."
                    >{{ old(
                        'immediate_action',
                        $finding?->immediate_action
                    ) }}</textarea>

                    @error('immediate_action')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- Recommended Action --}}
                <div class="col-md-6">

                    <label class="form-label">
                        Recommended Action
                    </label>

                    <textarea
                        name="recommended_action"
                        rows="4"
                        class="form-control @error('recommended_action') is-invalid @enderror"
                        placeholder="Recommended corrective/preventive action..."
                    >{{ old(
                        'recommended_action',
                        $finding?->recommended_action
                    ) }}</textarea>

                    @error('recommended_action')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- Responsible User --}}
                <div class="col-md-6">

                    <label class="form-label">
                        Responsible Person
                    </label>

                    <select
                        name="responsible_user_id"
                        class="form-select @error('responsible_user_id') is-invalid @enderror"
                    >

                        <option value="">
                            -- Select Responsible Person --
                        </option>

                        @foreach($users as $user)

                            <option
                                value="{{ $user->id }}"
                                @selected(
                                    old(
                                        'responsible_user_id',
                                        $finding?->responsible_user_id
                                    ) == $user->id
                                )
                            >
                                {{ $user->name }}
                            </option>

                        @endforeach

                    </select>

                    @error('responsible_user_id')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- Responsible Name --}}
                <div class="col-md-6">

                    <label class="form-label">
                        Responsible Name
                    </label>

                    <input
                        type="text"
                        name="responsible_name"
                        class="form-control"
                        value="{{ old(
                            'responsible_name',
                            $finding?->responsible_name
                        ) }}"
                        placeholder="Optional manual name"
                    >

                </div>


                {{-- Verification Status --}}
                <div class="col-md-4">

                    <label class="form-label">
                        Verification Status
                    </label>

                    <select
                        name="verification_status"
                        class="form-select"
                    >

                        <option value="">
                            -- Select --
                        </option>

                        @foreach([
                            'Pending',
                            'Verified',
                            'Rejected',
                        ] as $verification)

                            <option
                                value="{{ $verification }}"
                                @selected(
                                    old(
                                        'verification_status',
                                        $finding?->verification_status
                                    ) === $verification
                                )
                            >
                                {{ $verification }}
                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- Verified Date --}}
                <div class="col-md-4">

                    <label class="form-label">
                        Verified Date
                    </label>

                    <input
                        type="date"
                        name="verified_date"
                        class="form-control"
                        value="{{ old(
                            'verified_date',
                            $finding?->verified_date?->format('Y-m-d')
                        ) }}"
                    >

                </div>


                {{-- Verification Remarks --}}
                <div class="col-md-4">

                    <label class="form-label">
                        Verification Remarks
                    </label>

                    <textarea
                        name="verification_remarks"
                        rows="2"
                        class="form-control"
                    >{{ old(
                        'verification_remarks',
                        $finding?->verification_remarks
                    ) }}</textarea>

                </div>


                {{-- Remarks --}}
                <div class="col-12">

                    <label class="form-label">
                        Remarks
                    </label>

                    <textarea
                        name="remarks"
                        rows="3"
                        class="form-control"
                        placeholder="Additional remarks..."
                    >{{ old(
                        'remarks',
                        $finding?->remarks
                    ) }}</textarea>

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