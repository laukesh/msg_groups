@php $isEdit = isset($drawing) && $drawing->exists; @endphp
<div class="row g-3">
    <div class="col-md-3">
        <label class="form-label">Drawing Number <span class="text-danger">*</span></label>
        <input type="text" name="drawing_number" class="form-control" required value="{{ old('drawing_number', $drawing->drawing_number ?? '') }}">
    </div>
    <div class="col-md-5">
        <label class="form-label">Drawing Title <span class="text-danger">*</span></label>
        <input type="text" name="drawing_title" class="form-control" required value="{{ old('drawing_title', $drawing->drawing_title ?? '') }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">Drawing Type</label>
        <select name="drawing_type" class="form-select">
            <option value="">Select Type</option>
            @foreach($drawingTypes as $type)
                <option value="{{ $type }}" @selected(old('drawing_type', $drawing->drawing_type ?? '') === $type)>{{ $type }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <label class="form-label">Package</label>
        <select name="design_package_id" class="form-select">
            <option value="">Select Package</option>
            @foreach($packages as $package)
                <option value="{{ $package->id }}" @selected((string) old('design_package_id', $drawing->design_package_id ?? '') === (string) $package->id)>{{ $package->package_code }} — {{ $package->package_name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <label class="form-label">Discipline</label>
        <select name="design_discipline_id" class="form-select">
            <option value="">Select Discipline</option>
            @foreach($disciplines as $discipline)
                <option value="{{ $discipline->id }}" @selected((string) old('design_discipline_id', $drawing->design_discipline_id ?? '') === (string) $discipline->id)>{{ $discipline->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <label class="form-label">Consultant</label>
        <select name="prepared_by_consultant_id" class="form-select">
            <option value="">Select Consultant</option>
            @foreach($consultants as $consultant)
                <option value="{{ $consultant->id }}" @selected((string) old('prepared_by_consultant_id', $drawing->prepared_by_consultant_id ?? '') === (string) $consultant->id)>{{ $consultant->company_name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        @if($isEdit)
            <label class="form-label">Status</label>
            <input type="text" class="form-control bg-light" value="{{ $drawing->workflowStatus() }}" readonly>
        @else
            <label class="form-label">Status</label>
            <input type="text" class="form-control bg-light" value="Draft" readonly>
        @endif
    </div>
    <div class="col-md-2">
        <label class="form-label">Revision</label>
        @if($isEdit)
            <input type="text" class="form-control bg-light" value="{{ $drawing->revision }}" readonly>
            <input type="hidden" name="revision" value="{{ $drawing->revision }}">
        @else
            <input type="text" class="form-control bg-light" value="{{ $drawing->revision ?? 'R00' }}" readonly>
            <input type="hidden" name="revision" value="{{ old('revision', $drawing->revision ?? 'R00') }}">
            <input type="hidden" name="version_number" value="{{ old('version_number', $drawing->version_number ?? 1) }}">
        @endif
    </div>
    <div class="col-md-2">
        <label class="form-label">Revision Date</label>
        <input type="date" name="revision_date" class="form-control" value="{{ old('revision_date', optional($drawing->revision_date ?? null)->format('Y-m-d')) }}">
    </div>
    <div class="col-md-2">
        <label class="form-label">Planned Date</label>
        <input type="date" name="planned_date" class="form-control" value="{{ old('planned_date', optional($drawing->planned_date ?? null)->format('Y-m-d')) }}">
    </div>
    <div class="col-md-2">
        <label class="form-label">Submitted Date</label>
        <input type="date" name="submitted_date" class="form-control" value="{{ old('submitted_date', optional($drawing->submitted_date ?? null)->format('Y-m-d')) }}">
    </div>
    <div class="col-md-2">
        <label class="form-label">Approved Date</label>
        <input type="date" name="approved_date" class="form-control" value="{{ old('approved_date', optional($drawing->approved_date ?? null)->format('Y-m-d')) }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">Drawing File</label>
        <input type="file" name="drawing_file" class="form-control">
        @if(!empty($drawing?->file_name))
            <div class="form-text">Current: {{ $drawing->file_name }}</div>
        @endif
    </div>
    <div class="col-md-4 d-flex align-items-end">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="current_revision" value="1" id="current_revision" @checked(old('current_revision', $drawing->current_revision ?? true))>
            <label class="form-check-label" for="current_revision">Current Revision</label>
        </div>
    </div>
    <div class="col-12">
        <label class="form-label">Remarks</label>
        <textarea name="remarks" class="form-control" rows="2">{{ old('remarks', $drawing->remarks ?? '') }}</textarea>
    </div>
</div>
