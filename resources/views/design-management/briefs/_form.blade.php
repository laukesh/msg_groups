@php
    $isEdit = isset($brief) && $brief->exists;
@endphp

{{-- Basic Information --}}
<div class="card shadow-sm mb-4">
    <div class="card-header bg-white">
        <h5 class="mb-0">
            <i class="ri-file-text-line me-2"></i>
            Basic Information
        </h5>
    </div>
    <div class="card-body">
        <div class="row g-3">
            @if($isEdit)
                <div class="col-md-4">
                    <label class="form-label">Brief Code</label>
                    <input type="text" class="form-control bg-light" value="{{ $brief->brief_code }}" readonly>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Version</label>
                    <input type="text" class="form-control bg-light" value="V{{ $brief->version }}" readonly>
                    <input type="hidden" name="version" value="{{ $brief->version }}">
                    <input type="hidden" name="version_number" value="{{ $brief->version_number }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Status</label>
                    <input type="text" class="form-control bg-light" value="{{ $brief->status }}" readonly>
                </div>
            @else
                <div class="col-md-4">
                    <label class="form-label">Brief Code</label>
                    <input type="text" name="brief_code" class="form-control" value="{{ old('brief_code') }}" placeholder="Auto-generated if empty">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Version</label>
                    <input type="text" class="form-control bg-light" value="V{{ $brief->version ?? '1.0' }}" readonly>
                    <input type="hidden" name="version" value="{{ old('version', $brief->version ?? '1.0') }}">
                    <input type="hidden" name="version_number" value="{{ old('version_number', $brief->version_number ?? 1) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Status</label>
                    <input type="text" class="form-control bg-light" value="Draft" readonly>
                </div>
            @endif

            <div class="col-12">
                <label class="form-label">Title <span class="text-danger">*</span></label>
                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" required value="{{ old('title', $brief->title ?? '') }}">
                @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>
</div>

{{-- Project Requirements --}}
<div class="card shadow-sm mb-4">
    <div class="card-header bg-white">
        <h5 class="mb-0">
            <i class="ri-list-check-2 me-2"></i>
            Project Requirements
        </h5>
    </div>
    <div class="card-body">
        <label class="form-label">Project Requirements</label>
        <textarea name="project_requirements" class="form-control" rows="5" placeholder="Describe overall project requirements...">{{ old('project_requirements', $brief->project_requirements ?? '') }}</textarea>
    </div>
</div>

{{-- Design Objectives --}}
<div class="card shadow-sm mb-4">
    <div class="card-header bg-white">
        <h5 class="mb-0">
            <i class="ri-compasses-2-line me-2"></i>
            Design Objectives & Functional Requirements
        </h5>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-12">
                <label class="form-label">Design Objectives</label>
                <textarea name="design_objectives" class="form-control" rows="4" placeholder="Key design objectives and goals...">{{ old('design_objectives', $brief->design_objectives ?? '') }}</textarea>
            </div>
            <div class="col-12">
                <label class="form-label">Functional Requirements</label>
                <textarea name="functional_requirements" class="form-control" rows="4" placeholder="Functional and operational requirements...">{{ old('functional_requirements', $brief->functional_requirements ?? '') }}</textarea>
            </div>
        </div>
    </div>
</div>

{{-- Technical & Standards --}}
<div class="card shadow-sm mb-4">
    <div class="card-header bg-white">
        <h5 class="mb-0">
            <i class="ri-settings-3-line me-2"></i>
            Technical Requirements & Design Standards
        </h5>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-12">
                <label class="form-label">Technical Requirements</label>
                <textarea name="technical_requirements" class="form-control" rows="4" placeholder="Technical specifications and constraints...">{{ old('technical_requirements', $brief->technical_requirements ?? '') }}</textarea>
            </div>
            <div class="col-12">
                <label class="form-label">Design Standards</label>
                <textarea name="design_standards" class="form-control" rows="4" placeholder="Applicable codes, standards and guidelines...">{{ old('design_standards', $brief->design_standards ?? '') }}</textarea>
            </div>
        </div>
    </div>
</div>

{{-- Authority Requirements --}}
<div class="card shadow-sm mb-4">
    <div class="card-header bg-white">
        <h5 class="mb-0">
            <i class="ri-government-line me-2"></i>
            Authority Requirements
        </h5>
    </div>
    <div class="card-body">
        <label class="form-label">Authority Requirements</label>
        <textarea name="authority_requirements" class="form-control" rows="4" placeholder="Statutory, regulatory and authority requirements...">{{ old('authority_requirements', $brief->authority_requirements ?? '') }}</textarea>
    </div>
</div>

{{-- Remarks --}}
<div class="card shadow-sm mb-4">
    <div class="card-header bg-white">
        <h5 class="mb-0">
            <i class="ri-chat-3-line me-2"></i>
            Remarks
        </h5>
    </div>
    <div class="card-body">
        <textarea name="remarks" class="form-control" rows="3" placeholder="Additional notes or comments...">{{ old('remarks', $brief->remarks ?? '') }}</textarea>
    </div>
</div>
