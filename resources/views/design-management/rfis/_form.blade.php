@php $isEdit = isset($rfi) && $rfi->exists; @endphp
<div class="row g-3">
    <div class="col-md-4">
        <label class="form-label">RFI Number</label>
        <input type="text" name="rfi_number" class="form-control" value="{{ old('rfi_number', $rfi->rfi_number ?? '') }}" placeholder="Auto-generated if empty">
    </div>
    <div class="col-md-8">
        <label class="form-label">Subject <span class="text-danger">*</span></label>
        <input type="text" name="subject" class="form-control" required value="{{ old('subject', $rfi->subject ?? '') }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">Discipline</label>
        <select name="design_discipline_id" class="form-select">
            <option value="">Select Discipline</option>
            @foreach($disciplines as $discipline)
                <option value="{{ $discipline->id }}" @selected((string) old('design_discipline_id', $rfi->design_discipline_id ?? '') === (string) $discipline->id)>{{ $discipline->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label">Consultant</label>
        <select name="consultant_id" class="form-select">
            <option value="">Select Consultant</option>
            @foreach($consultants as $consultant)
                <option value="{{ $consultant->id }}" @selected((string) old('consultant_id', $rfi->consultant_id ?? '') === (string) $consultant->id)>{{ $consultant->company_name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        @if($isEdit)
            <label class="form-label">Status</label>
            <input type="text" class="form-control bg-light" value="{{ $rfi->workflowStatus() }}" readonly>
        @else
            <label class="form-label">Status</label>
            <input type="text" class="form-control bg-light" value="Open" readonly>
        @endif
    </div>
    <div class="col-12">
        <label class="form-label d-block">Priority <span class="text-danger">*</span></label>
        <div class="d-flex flex-wrap gap-3">
            @foreach($priorities as $priority)
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="priority" id="priority_{{ $priority }}" value="{{ $priority }}" @checked(old('priority', $rfi->priority ?? 'Normal') === $priority)>
                    <label class="form-check-label" for="priority_{{ $priority }}">{{ $priority }}</label>
                </div>
            @endforeach
        </div>
    </div>
    <div class="col-md-3">
        <label class="form-label">Raised Date</label>
        <input type="date" name="raised_date" class="form-control" value="{{ old('raised_date', optional($rfi->raised_date ?? null)->format('Y-m-d')) }}">
    </div>
    <div class="col-md-3">
        <label class="form-label">Required Response Date</label>
        <input type="date" name="required_response_date" class="form-control" value="{{ old('required_response_date', optional($rfi->required_response_date ?? null)->format('Y-m-d')) }}">
    </div>
    <div class="col-md-3">
        <label class="form-label">Response Date</label>
        <input type="date" name="response_date" class="form-control" value="{{ old('response_date', optional($rfi->response_date ?? null)->format('Y-m-d')) }}">
    </div>
  @if(isset($users))
    <div class="col-md-3">
        <label class="form-label">Responded By</label>
        <select name="responded_by" class="form-select">
            <option value="">Select User</option>
            @foreach($users as $user)
                <option value="{{ $user->id }}" @selected((string) old('responded_by', $rfi->responded_by ?? '') === (string) $user->id)>{{ $user->name }}</option>
            @endforeach
        </select>
    </div>
  @endif
    <div class="col-md-6">
        <label class="form-label">Reference Document</label>
        <input type="text" name="reference_document" class="form-control" value="{{ old('reference_document', $rfi->reference_document ?? '') }}">
    </div>
    <div class="col-md-6">
        <label class="form-label">Reference Drawing</label>
        <input type="text" name="reference_drawing" class="form-control" value="{{ old('reference_drawing', $rfi->reference_drawing ?? '') }}">
    </div>
    <div class="col-12">
        <label class="form-label">Question <span class="text-danger">*</span></label>
        <textarea name="question" class="form-control" rows="4" required>{{ old('question', $rfi->question ?? '') }}</textarea>
    </div>
    <div class="col-12">
        <label class="form-label">Response</label>
        <textarea name="response" class="form-control" rows="4">{{ old('response', $rfi->response ?? '') }}</textarea>
    </div>
    <div class="col-12">
        <label class="form-label">Remarks</label>
        <textarea name="remarks" class="form-control" rows="2">{{ old('remarks', $rfi->remarks ?? '') }}</textarea>
    </div>
</div>
