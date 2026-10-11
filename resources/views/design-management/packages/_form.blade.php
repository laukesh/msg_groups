@php $isEdit = isset($package) && $package->exists; @endphp
<div class="row g-3">
    <div class="col-md-4">
        <label class="form-label">Package Code</label>
        <input type="text" name="package_code" class="form-control" value="{{ old('package_code', $package->package_code ?? '') }}" placeholder="Auto-generated if empty">
    </div>
    <div class="col-md-8">
        <label class="form-label">Package Name <span class="text-danger">*</span></label>
        <input type="text" name="package_name" class="form-control" required value="{{ old('package_name', $package->package_name ?? '') }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">Discipline</label>
        <select name="design_discipline_id" class="form-select">
            <option value="">All / Not Set</option>
            @foreach($disciplines as $discipline)
                <option value="{{ $discipline->id }}" @selected((string) old('design_discipline_id', $package->design_discipline_id ?? '') === (string) $discipline->id)>{{ $discipline->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label">Responsible Consultant</label>
        <select name="responsible_consultant_id" class="form-select">
            <option value="">Select Consultant</option>
            @foreach($consultants as $consultant)
                <option value="{{ $consultant->id }}" @selected((string) old('responsible_consultant_id', $package->responsible_consultant_id ?? '') === (string) $consultant->id)>{{ $consultant->company_name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        @if($isEdit)
            <label class="form-label">Status</label>
            <input type="text" class="form-control bg-light" value="{{ $package->workflowStatus() }}" readonly>
        @else
            <label class="form-label">Status</label>
            <input type="text" class="form-control bg-light" value="Draft" readonly>
        @endif
    </div>
    <div class="col-md-3">
        <label class="form-label">Version</label>
        @if($isEdit)
            <input type="text" class="form-control bg-light" value="{{ $package->version }}" readonly>
            <input type="hidden" name="version" value="{{ $package->version }}">
        @else
            <input type="text" class="form-control bg-light" value="{{ $package->version ?? '1.0' }}" readonly>
            <input type="hidden" name="version" value="{{ old('version', $package->version ?? '1.0') }}">
            <input type="hidden" name="version_number" value="{{ old('version_number', $package->version_number ?? 1) }}">
        @endif
    </div>
    <div class="col-md-3">
        <label class="form-label">Planned Submission</label>
        <input type="date" name="planned_submission_date" class="form-control" value="{{ old('planned_submission_date', optional($package->planned_submission_date ?? null)->format('Y-m-d')) }}">
    </div>
    <div class="col-md-3">
        <label class="form-label">Actual Submission</label>
        <input type="date" name="actual_submission_date" class="form-control" value="{{ old('actual_submission_date', optional($package->actual_submission_date ?? null)->format('Y-m-d')) }}">
    </div>
    <div class="col-12">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="3">{{ old('description', $package->description ?? '') }}</textarea>
    </div>
    <div class="col-12">
        <label class="form-label">Remarks</label>
        <textarea name="remarks" class="form-control" rows="2">{{ old('remarks', $package->remarks ?? '') }}</textarea>
    </div>
</div>
