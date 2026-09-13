@php $isEdit = isset($comment) && $comment->exists; @endphp
<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Review <span class="text-danger">*</span></label>
        <select name="design_review_id" class="form-select" required>
            <option value="">Select Review</option>
            @foreach($reviews as $review)
                <option value="{{ $review->id }}" @selected((string) old('design_review_id', $comment->design_review_id ?? ($selectedReviewId ?? '')) === (string) $review->id)>{{ $review->review_number }} — {{ $review->submittal?->subject }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <label class="form-label">Comment Number</label>
        <input type="text" name="comment_number" class="form-control" value="{{ old('comment_number', $comment->comment_number ?? '') }}">
    </div>
    <div class="col-md-3">
        <label class="form-label">Category</label>
        <select name="category" class="form-select">
            <option value="">Select Category</option>
            @foreach($categories as $category)
                <option value="{{ $category }}" @selected(old('category', $comment->category ?? '') === $category)>{{ $category }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label">Severity <span class="text-danger">*</span></label>
        <div class="d-flex gap-3 pt-2">
            @foreach($severities as $severity)
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="severity" id="severity_{{ $severity }}" value="{{ $severity }}" @checked(old('severity', $comment->severity ?? 'Minor') === $severity)>
                    <label class="form-check-label" for="severity_{{ $severity }}">{{ $severity }}</label>
                </div>
            @endforeach
        </div>
    </div>
    <div class="col-md-4">
        @if($isEdit)
            <label class="form-label">Status</label>
            <input type="text" class="form-control bg-light" value="{{ $comment->workflowStatus() }}" readonly>
        @else
            <label class="form-label">Status</label>
            <input type="text" class="form-control bg-light" value="Open" readonly>
        @endif
    </div>
    <div class="col-md-4 d-flex align-items-end">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="response_required" value="1" id="comment_response_required" @checked(old('response_required', $comment->response_required ?? true))>
            <label class="form-check-label" for="comment_response_required">Response Required</label>
        </div>
    </div>
    <div class="col-md-6">
        <label class="form-label">Location Reference</label>
        <input type="text" name="location_reference" class="form-control" value="{{ old('location_reference', $comment->location_reference ?? '') }}">
    </div>
    <div class="col-md-3">
        <label class="form-label">Response Date</label>
        <input type="date" name="response_date" class="form-control" value="{{ old('response_date', optional($comment->response_date ?? null)->format('Y-m-d')) }}">
    </div>
    <div class="col-md-3">
        <label class="form-label">Resolved Date</label>
        <input type="date" name="resolved_date" class="form-control" value="{{ old('resolved_date', optional($comment->resolved_date ?? null)->format('Y-m-d')) }}">
    </div>
    <div class="col-12">
        <label class="form-label">Comment <span class="text-danger">*</span></label>
        <textarea name="comment_text" class="form-control" rows="4" required>{{ old('comment_text', $comment->comment_text ?? '') }}</textarea>
    </div>
    <div class="col-12">
        <label class="form-label">Consultant Response</label>
        <textarea name="consultant_response" class="form-control" rows="3">{{ old('consultant_response', $comment->consultant_response ?? '') }}</textarea>
    </div>
    <div class="col-12">
        <label class="form-label">Remarks</label>
        <textarea name="remarks" class="form-control" rows="2">{{ old('remarks', $comment->remarks ?? '') }}</textarea>
    </div>
</div>
