@php $isEdit = isset($submittal) && $submittal->exists; @endphp
<div class="row g-3">
    <div class="col-md-4">
        <label class="form-label">Submittal Number</label>
        <input type="text" name="submittal_number" class="form-control" value="{{ old('submittal_number', $submittal->submittal_number ?? '') }}" placeholder="Auto-generated if empty">
    </div>
    <div class="col-md-8">
        <label class="form-label">Subject <span class="text-danger">*</span></label>
        <input type="text" name="subject" class="form-control" required value="{{ old('subject', $submittal->subject ?? '') }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">Package</label>
        <select name="design_package_id" class="form-select">
            <option value="">Select Package</option>
            @foreach($packages as $package)
                <option value="{{ $package->id }}" @selected((string) old('design_package_id', $submittal->design_package_id ?? '') === (string) $package->id)>{{ $package->package_code }} — {{ $package->package_name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label">Discipline</label>
        <select name="design_discipline_id" class="form-select">
            <option value="">Select Discipline</option>
            @foreach($disciplines as $discipline)
                <option value="{{ $discipline->id }}" @selected((string) old('design_discipline_id', $submittal->design_discipline_id ?? '') === (string) $discipline->id)>{{ $discipline->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label">Consultant</label>
        <select name="consultant_id" class="form-select">
            <option value="">Select Consultant</option>
            @foreach($consultants as $consultant)
                <option value="{{ $consultant->id }}" @selected((string) old('consultant_id', $submittal->consultant_id ?? '') === (string) $consultant->id)>{{ $consultant->company_name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        @if($isEdit)
            <label class="form-label">Status</label>
            <input type="text" class="form-control bg-light" value="{{ $submittal->workflowStatus() }}" readonly>
        @else
            <label class="form-label">Status</label>
            <input type="text" class="form-control bg-light" value="Draft" readonly>
            <input type="hidden" name="version_number" value="{{ old('version_number', $submittal->version_number ?? 1) }}">
        @endif
    </div>
    <div class="col-md-3">
        <label class="form-label">Revision</label>
        <input type="text" name="revision" class="form-control" value="{{ old('revision', $submittal->revision ?? '') }}">
    </div>
    <div class="col-md-3">
        <label class="form-label">Submission Date</label>
        <input type="date" name="submission_date" class="form-control" value="{{ old('submission_date', optional($submittal->submission_date ?? null)->format('Y-m-d')) }}">
    </div>
    <div class="col-md-3">
        <label class="form-label">Due Date</label>
        <input type="date" name="due_date" class="form-control" value="{{ old('due_date', optional($submittal->due_date ?? null)->format('Y-m-d')) }}">
    </div>
    <div class="col-md-3">
        <label class="form-label">Reviewed Date</label>
        <input type="date" name="reviewed_date" class="form-control" value="{{ old('reviewed_date', optional($submittal->reviewed_date ?? null)->format('Y-m-d')) }}">
    </div>
    <div class="col-md-3">
        <label class="form-label">Final Decision</label>
        <select name="final_decision" class="form-select">
            <option value="">Select Decision</option>
            @foreach($decisions as $decision)
                <option value="{{ $decision }}" @selected(old('final_decision', $submittal->final_decision ?? '') === $decision)>{{ $decision }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-12">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="3">{{ old('description', $submittal->description ?? '') }}</textarea>
    </div>
    <div class="col-12">
        <label class="form-label">Decision Remarks</label>
        <textarea name="decision_remarks" class="form-control" rows="2">{{ old('decision_remarks', $submittal->decision_remarks ?? '') }}</textarea>
    </div>
</div>
