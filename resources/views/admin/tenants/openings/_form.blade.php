@php
    $currentChecklist = old('checklist', $checklist ?? []);
@endphp

<div class="row g-3">
    <div class="col-md-4">
        <label class="form-label">Tenant <span class="text-danger">*</span></label>
        <select name="tenant_id" class="form-select" required>
            <option value="">Select tenant</option>
            @foreach($tenants as $tenant)
                <option value="{{ $tenant->id }}"
                    @selected((string) old('tenant_id', $handover->tenant_id ?? '') === (string) $tenant->id)>
                    {{ $tenant->company_name }}
                    {{ $tenant->brand_name ? ' — '.$tenant->brand_name : '' }}
                </option>
            @endforeach
        </select>
        @error('tenant_id') <div class="text-danger small">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-4">
        <label class="form-label">Unit <span class="text-danger">*</span></label>
        <select name="unit_id" class="form-select" required>
            <option value="">Select unit</option>
            @foreach($units as $unit)
                <option value="{{ $unit->id }}"
                    @selected((string) old('unit_id', $handover->unit_id ?? '') === (string) $unit->id)>
                    {{ $unit->unit_no }}
                </option>
            @endforeach
        </select>
        @error('unit_id') <div class="text-danger small">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-4">
        <label class="form-label">Fit-out request <span class="text-danger">*</span></label>
        <select name="fitout_request_id" class="form-select" required>
            <option value="">Select fit-out request</option>
            @foreach($fitoutRequests as $fitout)
                <option value="{{ $fitout->id }}"
                    @selected((string) old('fitout_request_id', $handover->fitout_request_id ?? '') === (string) $fitout->id)>
                    {{ $fitout->request_no }} — {{ $fitout->fitout_status }}
                </option>
            @endforeach
        </select>
        @error('fitout_request_id') <div class="text-danger small">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-4">
        <label class="form-label">Opening / handover date</label>
        <input type="date" name="handover_date" class="form-control"
               value="{{ old('handover_date', isset($handover->handover_date) ? substr($handover->handover_date, 0, 10) : '') }}">
        @error('handover_date') <div class="text-danger small">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-4">
        <label class="form-label">Handover type <span class="text-danger">*</span></label>
        <select name="handover_type" class="form-select" required>
            @foreach(['Final Handover', 'Fit-Out Handover', 'Partial Handover'] as $type)
                <option value="{{ $type }}"
                    @selected(old('handover_type', $handover->handover_type ?? 'Final Handover') === $type)>
                    {{ $type }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-4">
        <label class="form-label">Unit condition</label>
        <select name="unit_condition" class="form-select">
            <option value="">Select condition</option>
            @foreach(['Good', 'Minor Issues', 'Major Issues', 'Not Ready'] as $condition)
                <option value="{{ $condition }}"
                    @selected(old('unit_condition', $handover->unit_condition ?? '') === $condition)>
                    {{ $condition }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-3">
        <label class="form-label">Keys handed over</label>
        <input type="number" min="0" name="key_count" class="form-control"
               value="{{ old('key_count', $handover->key_count ?? 0) }}">
    </div>

    <div class="col-md-3">
        <label class="form-label">Access cards issued</label>
        <input type="number" min="0" name="access_card_count" class="form-control"
               value="{{ old('access_card_count', $handover->access_card_count ?? 0) }}">
    </div>

    <div class="col-md-3">
        <label class="form-label">Electricity meter number</label>
        <input name="electricity_meter_no" class="form-control"
               value="{{ old('electricity_meter_no', $handover->electricity_meter_no ?? '') }}">
    </div>

    <div class="col-md-3">
        <label class="form-label">Electricity reading</label>
        <input type="number" min="0" step="0.01" name="electricity_meter_reading"
               class="form-control"
               value="{{ old('electricity_meter_reading', $handover->electricity_meter_reading ?? '') }}">
    </div>

    <div class="col-md-6">
        <label class="form-label">Water meter number</label>
        <input name="water_meter_no" class="form-control"
               value="{{ old('water_meter_no', $handover->water_meter_no ?? '') }}">
    </div>

    <div class="col-md-6">
        <label class="form-label">Water reading</label>
        <input type="number" min="0" step="0.01" name="water_meter_reading"
               class="form-control"
               value="{{ old('water_meter_reading', $handover->water_meter_reading ?? '') }}">
    </div>
</div>

<hr class="my-4">

<h5>Handover checklist</h5>
<p class="text-muted small">
    Mark each item as completed or not applicable. Unchecked items remain outstanding.
</p>

<div class="row g-3">
    @foreach($checklistItems as $key => $label)
        <div class="col-md-6">
            <div class="border rounded p-3 h-100">
                <label class="form-label">{{ $label }}</label>
                <select name="checklist[{{ $key }}]" class="form-select">
                    <option value="pending"
                        @selected(($currentChecklist[$key] ?? 'pending') === 'pending')>
                        Pending
                    </option>
                    <option value="done"
                        @selected(($currentChecklist[$key] ?? '') === 'done')>
                        Completed
                    </option>
                    <option value="not_applicable"
                        @selected(($currentChecklist[$key] ?? '') === 'not_applicable')>
                        Not applicable
                    </option>
                </select>
            </div>
        </div>
    @endforeach
</div>

<div class="mt-4">
    <label class="form-label">Outstanding items / snags</label>
    <textarea name="outstanding_items" rows="4" class="form-control"
              placeholder="Record incomplete work, responsible person and target completion date">{{ old('outstanding_items', $handover->outstanding_items ?? '') }}</textarea>
    @error('outstanding_items') <div class="text-danger small">{{ $message }}</div> @enderror
</div>

<div class="mt-3">
    <label class="form-label">Remarks</label>
    <textarea name="remarks" rows="3" class="form-control">{{ old('remarks', $handover->remarks ?? '') }}</textarea>
    @error('remarks') <div class="text-danger small">{{ $message }}</div> @enderror
</div>