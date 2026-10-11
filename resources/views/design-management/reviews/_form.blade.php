@php $isEdit = isset($review) && $review->exists; @endphp
<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Submittal <span class="text-danger">*</span></label>
        <select name="design_submittal_id" class="form-select" required>
            <option value="">Select Submittal</option>
            @foreach($submittals as $submittal)
                <option value="{{ $submittal->id }}" @selected((string) old('design_submittal_id', optional($review ?? null)->design_submittal_id ?? ($selectedSubmittalId ?? '')) === (string) $submittal->id)>{{ $submittal->submittal_number }} — {{ $submittal->subject }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <label class="form-label">Review Number</label>
        <input type="text" name="review_number" class="form-control" value="{{ old('review_number', $review->review_number ?? '') }}" placeholder="Auto-generated if empty">
    </div>
    <div class="col-md-3">
        <label class="form-label">Review Date</label>
        <input type="date" name="review_date" class="form-control" value="{{ old('review_date', optional($review->review_date ?? null)->format('Y-m-d')) }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">Reviewer</label>
        <select name="reviewer_id" class="form-select">
            <option value="">Select Reviewer</option>
            @foreach($users as $user)
                <option value="{{ $user->id }}" @selected((string) old('reviewer_id', $review->reviewer_id ?? '') === (string) $user->id)>{{ $user->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        @if($isEdit)
            <label class="form-label">Review Status</label>
            <input type="text" class="form-control bg-light" value="{{ $review->workflowStatus() }}" readonly>
        @else
            <label class="form-label">Review Status</label>
            <input type="text" class="form-control bg-light" value="Under Review" readonly>
        @endif
    </div>
    <div class="col-md-4">
        <label class="form-label">Decision</label>
        <select name="decision" class="form-select">
            <option value="">Select Decision</option>
            @foreach($decisions as $decision)
                <option value="{{ $decision }}" @selected(old('decision', $review->decision ?? '') === $decision)>{{ $decision }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <label class="form-label">Response Due Date</label>
        <input type="date" name="response_due_date" class="form-control" value="{{ old('response_due_date', optional($review->response_due_date ?? null)->format('Y-m-d')) }}">
    </div>
    <div class="col-md-3">
        <label class="form-label">Responded Date</label>
        <input type="date" name="responded_date" class="form-control" value="{{ old('responded_date', optional($review->responded_date ?? null)->format('Y-m-d')) }}">
    </div>
    <div class="col-md-6 d-flex align-items-end">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="response_required" value="1" id="response_required" @checked(old('response_required', $review->response_required ?? false))>
            <label class="form-check-label" for="response_required">Response Required</label>
        </div>
    </div>
    <div class="col-12">
        <label class="form-label">General Comments</label>
        <textarea name="general_comments" class="form-control" rows="4">{{ old('general_comments', $review->general_comments ?? '') }}</textarea>
    </div>
    <div class="col-12">
        <label class="form-label">Remarks</label>
        <textarea name="remarks" class="form-control" rows="2">{{ old('remarks', $review->remarks ?? '') }}</textarea>
    </div>
</div>
