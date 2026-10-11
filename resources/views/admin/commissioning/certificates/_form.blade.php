@php
    $certificate = $certificate ?? null;
@endphp

@csrf

<div class="card border-0 shadow-sm mb-4">

    <div class="card-header bg-white">
        <h6 class="mb-0 fw-semibold">
            <i class="bi bi-award me-1"></i>
            Certificate Information
        </h6>
    </div>

    <div class="card-body">

        <div class="row g-3">

            <div class="col-md-4">

                <label class="form-label">
                    Certificate Number
                    <span class="text-danger">*</span>
                </label>

                <input
                    type="text"
                    name="certificate_no"
                    value="{{ old('certificate_no', $certificate?->certificate_no) }}"
                    class="form-control @error('certificate_no') is-invalid @enderror"
                    placeholder="CERT-001"
                    required
                >

                @error('certificate_no')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

            </div>


            <div class="col-md-4">

                <label class="form-label">
                    Certificate Type
                    <span class="text-danger">*</span>
                </label>

                <select
                    name="certificate_type"
                    class="form-select"
                    required
                >

                    <option value="">
                        Select Type
                    </option>

                    @foreach([
                        'Commissioning Certificate',
                        'Testing Certificate',
                        'Performance Certificate',
                        'Inspection Certificate',
                        'System Acceptance Certificate',
                        'Equipment Commissioning Certificate',
                        'Completion Certificate',
                        'Other'
                    ] as $type)

                        <option
                            value="{{ $type }}"
                            @selected(old('certificate_type', $certificate?->certificate_type) === $type)
                        >
                            {{ $type }}
                        </option>

                    @endforeach

                </select>

            </div>


            <div class="col-md-4">

                <label class="form-label">
                    Commissioning Scope
                </label>

                <select
                    name="commissioning_scope_id"
                    class="form-select"
                >

                    <option value="">
                        Project Level
                    </option>

                    @foreach($scopes as $scope)

                        <option
                            value="{{ $scope->id }}"
                            @selected((string) old('commissioning_scope_id', $certificate?->commissioning_scope_id) === (string) $scope->id)
                        >
                            {{ $scope->scope_code }}
                            -
                            {{ $scope->scope_name }}
                        </option>

                    @endforeach

                </select>

            </div>


            <div class="col-md-4">

                <label class="form-label">
                    Issue Date
                </label>

                <input
                    type="date"
                    name="issue_date"
                    value="{{ old('issue_date', $certificate?->issue_date?->format('Y-m-d')) }}"
                    class="form-control"
                >

            </div>


            <div class="col-md-4">

                <label class="form-label">
                    Valid Until
                </label>

                <input
                    type="date"
                    name="valid_until"
                    value="{{ old('valid_until', $certificate?->valid_until?->format('Y-m-d')) }}"
                    class="form-control"
                >

            </div>


            <div class="col-md-4">

                <label class="form-label">
                    Issued By
                </label>

                <select
                    name="issued_by"
                    class="form-select"
                >

                    <option value="">
                        Select User
                    </option>

                    @foreach($users as $user)

                        <option
                            value="{{ $user->id }}"
                            @selected((string) old('issued_by', $certificate?->issued_by) === (string) $user->id)
                        >
                            {{ $user->name }}
                        </option>

                    @endforeach

                </select>

            </div>


            <div class="col-12">

                <label class="form-label">
                    Description
                </label>

                <textarea
                    name="description"
                    rows="4"
                    class="form-control"
                    placeholder="Describe the scope and purpose of this certificate..."
                >{{ old('description', $certificate?->description) }}</textarea>

            </div>

        </div>

    </div>

</div>


<div class="card border-0 shadow-sm mb-4">

    <div class="card-header bg-white">

        <h6 class="mb-0 fw-semibold">
            <i class="bi bi-file-earmark-text me-1"></i>
            Document
        </h6>

    </div>

    <div class="card-body">

        <div class="row g-3">

            <div class="col-md-6">

                <label class="form-label">
                    Document Reference
                </label>

                <input
                    type="text"
                    name="document_reference"
                    value="{{ old('document_reference', $certificate?->document_reference) }}"
                    class="form-control"
                    placeholder="DOC-COM-001"
                >

            </div>


            <div class="col-md-6">

                <label class="form-label">
                    Document Path
                </label>

                <input
                    type="text"
                    name="document_path"
                    value="{{ old('document_path', $certificate?->document_path) }}"
                    class="form-control"
                    placeholder="storage/commissioning/certificates/..."
                >

            </div>


            <div class="col-12">

                <label class="form-label">
                    Remarks
                </label>

                <textarea
                    name="remarks"
                    rows="4"
                    class="form-control"
                >{{ old('remarks', $certificate?->remarks) }}</textarea>

            </div>

        </div>

    </div>

</div>