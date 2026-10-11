@php $isEdit = isset($change) && $change->exists; @endphp
<div class="row g-3">
    <div class="col-md-4">
        <label class="form-label">Change Code</label>
        <input type="text" name="change_code" class="form-control" value="{{ old('change_code', $change->change_code ?? '') }}" placeholder="Auto-generated if empty">
    </div>
    <div class="col-md-8">
        <label class="form-label">Change Title <span class="text-danger">*</span></label>
        <input type="text" name="change_title" class="form-control" required value="{{ old('change_title', $change->change_title ?? '') }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">Change Type</label>
        <select name="change_type" class="form-select">
            <option value="">Select Type</option>
            @foreach($changeTypes as $type)
                <option value="{{ $type }}" @selected(old('change_type', $change->change_type ?? '') === $type)>{{ $type }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label">Package</label>
        <select name="design_package_id" class="form-select">
            <option value="">Select Package</option>
            @foreach($packages as $package)
                <option value="{{ $package->id }}" @selected((string) old('design_package_id', $change->design_package_id ?? '') === (string) $package->id)>{{ $package->package_code }} — {{ $package->package_name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label">Discipline</label>
        <select name="design_discipline_id" class="form-select">
            <option value="">Select Discipline</option>
            @foreach($disciplines as $discipline)
                <option value="{{ $discipline->id }}" @selected((string) old('design_discipline_id', $change->design_discipline_id ?? '') === (string) $discipline->id)>{{ $discipline->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        @if($isEdit)
            <label class="form-label">Status</label>
            <input type="text" class="form-control bg-light" value="{{ $change->workflowStatus() }}" readonly>
        @else
            <label class="form-label">Status</label>
            <input type="text" class="form-control bg-light" value="Draft" readonly>
            <input type="hidden" name="version_number" value="{{ old('version_number', $change->version_number ?? 1) }}">
        @endif
    </div>
    <div class="col-md-3">
        <label class="form-label">Requested By</label>
        <select name="requested_by" class="form-select">
            <option value="">Select User</option>
            @foreach($users as $user)
                <option value="{{ $user->id }}" @selected((string) old('requested_by', $change->requested_by ?? '') === (string) $user->id)>{{ $user->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <label class="form-label">Requested Date</label>
        <input type="date" name="requested_date" class="form-control" value="{{ old('requested_date', optional($change->requested_date ?? null)->format('Y-m-d')) }}">
    </div>
    <div class="col-md-3">
        <label class="form-label">Required Date</label>
        <input type="date" name="required_date" class="form-control" value="{{ old('required_date', optional($change->required_date ?? null)->format('Y-m-d')) }}">
    </div>
    <div class="col-md-3">
        <label class="form-label">Cost Impact</label>
        <input type="number" step="0.01" name="cost_impact" class="form-control" value="{{ old('cost_impact', $change->cost_impact ?? 0) }}">
    </div>
    <div class="col-md-3">
        <label class="form-label">Currency</label>
        <select name="currency" class="form-select">
            @foreach($currencies as $currency)
                <option value="{{ $currency }}" @selected(old('currency', $change->currency ?? 'INR') === $currency)>{{ $currency }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <label class="form-label">Time Impact (Days)</label>
        <input type="number" name="time_impact_days" class="form-control" value="{{ old('time_impact_days', $change->time_impact_days ?? 0) }}">
    </div>
    <div class="col-md-3">
        <label class="form-label">Implemented Date</label>
        <input type="date" name="implemented_date" class="form-control" value="{{ old('implemented_date', optional($change->implemented_date ?? null)->format('Y-m-d')) }}">
    </div>
    <div class="col-12">
        <label class="form-label">Reason</label>
        <textarea name="reason" class="form-control" rows="3">{{ old('reason', $change->reason ?? '') }}</textarea>
    </div>
    <div class="col-12">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="3">{{ old('description', $change->description ?? '') }}</textarea>
    </div>
    <div class="col-12">
        <label class="form-label">Remarks</label>
        <textarea name="remarks" class="form-control" rows="2">{{ old('remarks', $change->remarks ?? '') }}</textarea>
    </div>
</div>
