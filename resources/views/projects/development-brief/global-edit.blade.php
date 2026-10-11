@extends('layouts.app')

@section('title', 'Edit Development Brief')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Edit Development Brief</h1>

            <p class="text-muted mb-0">
                {{ $developmentBrief->brief_reference
                    ?? $developmentBrief->brief_title }}
            </p>
        </div>

        <div>
            <a href="{{ route('admin.development-briefs.show', $developmentBrief) }}"
               class="btn btn-info">
                <i class="fas fa-eye"></i>
                View
            </a>

            <a href="{{ route('admin.development-briefs.index') }}"
               class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i>
                Back
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Please correct the following errors:</strong>

            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.development-briefs.update', $developmentBrief) }}"
          method="POST">

        @csrf
        @method('PUT')

        <div class="row">

            {{-- Basic Information --}}
            <div class="col-md-12">
                <div class="card mb-4">

                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-file-alt"></i>
                            Basic Information
                        </h5>
                    </div>

                    <div class="card-body">
                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <label for="project_id" class="form-label">
                                    Project <span class="text-danger">*</span>
                                </label>

                                <select name="project_id"
                                        id="project_id"
                                        class="form-select @error('project_id') is-invalid @enderror"
                                        required>

                                    <option value="">Select Project</option>

                                    @foreach($projects as $project)
                                        <option value="{{ $project->id }}"
                                            @selected(old('project_id', $developmentBrief->project_id) == $project->id)>
                                            {{ $project->name
                                                ?? $project->project_name
                                                ?? ('Project #' . $project->id) }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('project_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="brief_reference" class="form-label">
                                    Brief Reference
                                </label>

                                <input type="text"
                                       name="brief_reference"
                                       id="brief_reference"
                                       class="form-control @error('brief_reference') is-invalid @enderror"
                                       value="{{ old('brief_reference', $developmentBrief->brief_reference) }}">

                                @error('brief_reference')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="brief_title" class="form-label">
                                    Brief Title <span class="text-danger">*</span>
                                </label>

                                <input type="text"
                                       name="brief_title"
                                       id="brief_title"
                                       class="form-control @error('brief_title') is-invalid @enderror"
                                       value="{{ old('brief_title', $developmentBrief->brief_title) }}"
                                       required>

                                @error('brief_title')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-md-2 mb-3">
                                <label for="version" class="form-label">
                                    Version
                                </label>

                                <input type="text"
                                       name="version"
                                       id="version"
                                       class="form-control @error('version') is-invalid @enderror"
                                       value="{{ old('version', $developmentBrief->version ?? '1.0') }}">

                                @error('version')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-md-2 mb-3">
                                <label for="revision_number" class="form-label">
                                    Revision Number
                                </label>

                                <input type="text"
                                       name="revision_number"
                                       id="revision_number"
                                       class="form-control @error('revision_number') is-invalid @enderror"
                                       value="{{ old('revision_number', $developmentBrief->revision_number ?? '0') }}">

                                @error('revision_number')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-md-2 mb-3">
                                <label for="document_date" class="form-label">
                                    Document Date
                                </label>

                                <input type="date"
                                       name="document_date"
                                       id="document_date"
                                       class="form-control @error('document_date') is-invalid @enderror"
                                       value="{{ old('document_date', optional($developmentBrief->document_date)->format('Y-m-d')) }}">

                                @error('document_date')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="prepared_by" class="form-label">
                                    Prepared By
                                </label>

                                <input type="number"
                                       name="prepared_by"
                                       id="prepared_by"
                                       class="form-control @error('prepared_by') is-invalid @enderror"
                                       value="{{ old('prepared_by', $developmentBrief->prepared_by) }}">

                                @error('prepared_by')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="target_use" class="form-label">
                                    Target Use
                                </label>

                                <textarea name="target_use"
                                          id="target_use"
                                          rows="3"
                                          class="form-control @error('target_use') is-invalid @enderror">{{ old('target_use', $developmentBrief->target_use) }}</textarea>

                                @error('target_use')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="target_users" class="form-label">
                                    Target Users
                                </label>

                                <textarea name="target_users"
                                          id="target_users"
                                          rows="3"
                                          class="form-control @error('target_users') is-invalid @enderror">{{ old('target_users', $developmentBrief->target_users) }}</textarea>

                                @error('target_users')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="business_need" class="form-label">
                                    Business Need
                                </label>

                                <textarea name="business_need"
                                          id="business_need"
                                          rows="4"
                                          class="form-control @error('business_need') is-invalid @enderror">{{ old('business_need', $developmentBrief->business_need) }}</textarea>

                                @error('business_need')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                        </div>
                    </div>
                </div>
            </div>


            {{-- Area and Space Requirements --}}
            <div class="col-md-12">
                <div class="card mb-4">

                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-ruler-combined"></i>
                            Area and Space Requirements
                        </h5>
                    </div>

                    <div class="card-body">

                        <div class="mb-3">
                            <label for="area_schedule" class="form-label">
                                Area Schedule
                            </label>

                            <textarea name="area_schedule"
                                      id="area_schedule"
                                      rows="6"
                                      class="form-control @error('area_schedule') is-invalid @enderror">{{ old('area_schedule', $developmentBrief->area_schedule) }}</textarea>

                            @error('area_schedule')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="room_unit_mix" class="form-label">
                                Room / Unit Mix
                            </label>

                            <textarea name="room_unit_mix"
                                      id="room_unit_mix"
                                      rows="6"
                                      class="form-control @error('room_unit_mix') is-invalid @enderror">{{ old('room_unit_mix', $developmentBrief->room_unit_mix) }}</textarea>

                            @error('room_unit_mix')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                    </div>
                </div>
            </div>


            {{-- Business and Functional Requirements --}}
            <div class="col-md-12">
                <div class="card mb-4">

                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-tasks"></i>
                            Business and Functional Requirements
                        </h5>
                    </div>

                    <div class="card-body">

                        <div class="mb-3">
                            <label for="business_requirements" class="form-label">
                                Business Requirements
                            </label>

                            <textarea name="business_requirements"
                                      id="business_requirements"
                                      rows="5"
                                      class="form-control @error('business_requirements') is-invalid @enderror">{{ old('business_requirements', $developmentBrief->business_requirements) }}</textarea>

                            @error('business_requirements')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="functional_requirements" class="form-label">
                                Functional Requirements
                            </label>

                            <textarea name="functional_requirements"
                                      id="functional_requirements"
                                      rows="5"
                                      class="form-control @error('functional_requirements') is-invalid @enderror">{{ old('functional_requirements', $developmentBrief->functional_requirements) }}</textarea>

                            @error('functional_requirements')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="operational_requirements" class="form-label">
                                Operational Requirements
                            </label>

                            <textarea name="operational_requirements"
                                      id="operational_requirements"
                                      rows="5"
                                      class="form-control @error('operational_requirements') is-invalid @enderror">{{ old('operational_requirements', $developmentBrief->operational_requirements) }}</textarea>

                            @error('operational_requirements')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                    </div>
                </div>
            </div>


            {{-- Design and Technical Requirements --}}
            <div class="col-md-12">
                <div class="card mb-4">

                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-drafting-compass"></i>
                            Design and Technical Requirements
                        </h5>
                    </div>

                    <div class="card-body">

                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <label for="quality_level" class="form-label">
                                    Quality Level
                                </label>

                                <select name="quality_level"
                                        id="quality_level"
                                        class="form-select @error('quality_level') is-invalid @enderror">

                                    <option value="">Select Quality Level</option>

                                    @foreach([
                                        'Basic',
                                        'Standard',
                                        'Premium',
                                        'Luxury',
                                        'Institutional',
                                        'Grade A',
                                        'Custom'
                                    ] as $qualityLevel)

                                        <option value="{{ $qualityLevel }}"
                                            @selected(old('quality_level', $developmentBrief->quality_level) === $qualityLevel)>
                                            {{ $qualityLevel }}
                                        </option>

                                    @endforeach
                                </select>

                                @error('quality_level')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="quality_requirements" class="form-label">
                                    Quality Requirements
                                </label>

                                <textarea name="quality_requirements"
                                          id="quality_requirements"
                                          rows="3"
                                          class="form-control @error('quality_requirements') is-invalid @enderror">{{ old('quality_requirements', $developmentBrief->quality_requirements) }}</textarea>

                                @error('quality_requirements')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="design_criteria" class="form-label">
                                    Design Criteria
                                </label>

                                <textarea name="design_criteria"
                                          id="design_criteria"
                                          rows="5"
                                          class="form-control @error('design_criteria') is-invalid @enderror">{{ old('design_criteria', $developmentBrief->design_criteria) }}</textarea>

                                @error('design_criteria')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="design_intent" class="form-label">
                                    Design Intent
                                </label>

                                <textarea name="design_intent"
                                          id="design_intent"
                                          rows="5"
                                          class="form-control @error('design_intent') is-invalid @enderror">{{ old('design_intent', $developmentBrief->design_intent) }}</textarea>

                                @error('design_intent')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="technical_requirements" class="form-label">
                                    Technical Requirements
                                </label>

                                <textarea name="technical_requirements"
                                          id="technical_requirements"
                                          rows="5"
                                          class="form-control @error('technical_requirements') is-invalid @enderror">{{ old('technical_requirements', $developmentBrief->technical_requirements) }}</textarea>

                                @error('technical_requirements')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                        </div>

                    </div>
                </div>
            </div>


            {{-- Sustainability and Commercial Requirements --}}
            <div class="col-md-12">
                <div class="card mb-4">

                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-leaf"></i>
                            Sustainability and Commercial Requirements
                        </h5>
                    </div>

                    <div class="card-body">

                        <div class="mb-3">
                            <label for="sustainability_requirements" class="form-label">
                                Sustainability Requirements
                            </label>

                            <textarea name="sustainability_requirements"
                                      id="sustainability_requirements"
                                      rows="5"
                                      class="form-control @error('sustainability_requirements') is-invalid @enderror">{{ old('sustainability_requirements', $developmentBrief->sustainability_requirements) }}</textarea>

                            @error('sustainability_requirements')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <label for="commercial_intent" class="form-label">
                                    Commercial Intent
                                </label>

                                <textarea name="commercial_intent"
                                          id="commercial_intent"
                                          rows="4"
                                          class="form-control @error('commercial_intent') is-invalid @enderror">{{ old('commercial_intent', $developmentBrief->commercial_intent) }}</textarea>

                                @error('commercial_intent')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="success_criteria" class="form-label">
                                    Success Criteria
                                </label>

                                <textarea name="success_criteria"
                                          id="success_criteria"
                                          rows="4"
                                          class="form-control @error('success_criteria') is-invalid @enderror">{{ old('success_criteria', $developmentBrief->success_criteria) }}</textarea>

                                @error('success_criteria')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                        </div>

                    </div>
                </div>
            </div>


            {{-- Constraints and Dependencies --}}
            <div class="col-md-12">
                <div class="card mb-4">

                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-exclamation-triangle"></i>
                            Constraints, Assumptions and Dependencies
                        </h5>
                    </div>

                    <div class="card-body">

                        <div class="row">

                            <div class="col-md-4 mb-3">
                                <label for="key_constraints" class="form-label">
                                    Key Constraints
                                </label>

                                <textarea name="key_constraints"
                                          id="key_constraints"
                                          rows="5"
                                          class="form-control @error('key_constraints') is-invalid @enderror">{{ old('key_constraints', $developmentBrief->key_constraints) }}</textarea>

                                @error('key_constraints')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="key_assumptions" class="form-label">
                                    Key Assumptions
                                </label>

                                <textarea name="key_assumptions"
                                          id="key_assumptions"
                                          rows="5"
                                          class="form-control @error('key_assumptions') is-invalid @enderror">{{ old('key_assumptions', $developmentBrief->key_assumptions) }}</textarea>

                                @error('key_assumptions')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="key_dependencies" class="form-label">
                                    Key Dependencies
                                </label>

                                <textarea name="key_dependencies"
                                          id="key_dependencies"
                                          rows="5"
                                          class="form-control @error('key_dependencies') is-invalid @enderror">{{ old('key_dependencies', $developmentBrief->key_dependencies) }}</textarea>

                                @error('key_dependencies')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="required_studies" class="form-label">
                                    Required Studies
                                </label>

                                <textarea name="required_studies"
                                          id="required_studies"
                                          rows="4"
                                          class="form-control @error('required_studies') is-invalid @enderror">{{ old('required_studies', $developmentBrief->required_studies) }}</textarea>

                                @error('required_studies')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                        </div>

                    </div>
                </div>
            </div>


            {{-- Approval and Revision Information --}}
            <div class="col-md-12">
                <div class="card mb-4">

                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-check-circle"></i>
                            Approval and Revision Information
                        </h5>
                    </div>

                    <div class="card-body">

                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <label for="approvals_required" class="form-label">
                                    Required Approvals
                                </label>

                                <textarea name="approvals_required"
                                          id="approvals_required"
                                          rows="4"
                                          class="form-control @error('approvals_required') is-invalid @enderror">{{ old('approvals_required', $developmentBrief->approvals_required) }}</textarea>

                                @error('approvals_required')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="approval_status" class="form-label">
                                    Approval Status
                                </label>

                                <select name="approval_status"
                                        id="approval_status"
                                        class="form-select @error('approval_status') is-invalid @enderror">

                                    @foreach([
                                        'Draft',
                                        'Under Review',
                                        'Revision Required',
                                        'Approved',
                                        'Rejected',
                                        'Archived'
                                    ] as $status)

                                        <option value="{{ $status }}"
                                            @selected(old('approval_status', $developmentBrief->approval_status ?? 'Draft') === $status)>
                                            {{ $status }}
                                        </option>

                                    @endforeach
                                </select>

                                @error('approval_status')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="approval_remarks" class="form-label">
                                    Approval Remarks
                                </label>

                                <textarea name="approval_remarks"
                                          id="approval_remarks"
                                          rows="4"
                                          class="form-control @error('approval_remarks') is-invalid @enderror">{{ old('approval_remarks', $developmentBrief->approval_remarks) }}</textarea>

                                @error('approval_remarks')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="revision_history" class="form-label">
                                    Revision History
                                </label>

                                <textarea name="revision_history"
                                          id="revision_history"
                                          rows="5"
                                          class="form-control @error('revision_history') is-invalid @enderror">{{ old('revision_history', $developmentBrief->revision_history) }}</textarea>

                                @error('revision_history')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                        </div>

                    </div>
                </div>
            </div>

        </div>


        <div class="d-flex justify-content-between mb-4">

            <a href="{{ route('admin.development-briefs.index') }}"
               class="btn btn-secondary">
                <i class="fas fa-times"></i>
                Cancel
            </a>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i>
                Update Development Brief
            </button>

        </div>

    </form>

</div>

@endsection