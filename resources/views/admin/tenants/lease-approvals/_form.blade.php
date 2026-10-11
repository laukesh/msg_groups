
<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Tenant *</label>
        <select name="tenant_id" class="form-select" required>
            <option value="">Select tenant</option>
            @foreach($tenants as $tenant)
                <option value="{{ $tenant->id }}"
                    @selected(old('tenant_id', $approval->tenant_id ?? '') == $tenant->id)>
                    {{ $tenant->company_name ?? $tenant->tenant_name ?? $tenant->name ?? ('Tenant #' . $tenant->id) }}
                </option>
            @endforeach
        </select>
        @error('tenant_id') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">Unit *</label>
        <select name="unit_id" class="form-select" required>
            <option value="">Select unit</option>
            @foreach($units as $unit)
                <option value="{{ $unit->id }}"
                    @selected(old('unit_id', $approval->unit_id ?? '') == $unit->id)>
                    {{ $unit->unit_no ?? ('Unit #' . $unit->id) }}
                    {{ !empty($unit->shop_name) ? ' - ' . $unit->shop_name : '' }}
                </option>
            @endforeach
        </select>
        @error('unit_id') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">Related LOI</label>
        <select name="loi_id" class="form-select">
            <option value="">None</option>
            @foreach($lois as $loi)
                <option value="{{ $loi->id }}"
                    @selected(old('loi_id', $approval->loi_id ?? '') == $loi->id)>
                    {{ $loi->loi_number }} — {{ $loi->title }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-6">
        <label class="form-label">Lease Term (months)</label>
        <input type="number" min="1" name="lease_term_months"
               class="form-control"
               value="{{ old('lease_term_months', $approval->lease_term_months ?? '') }}">
    </div>

    <div class="col-12">
        <label class="form-label">Lease Summary *</label>
        <textarea name="lease_summary" class="form-control" rows="3" required>{{ old('lease_summary', $approval->lease_summary ?? '') }}</textarea>
        @error('lease_summary') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-3">
        <label class="form-label">Proposed Rent</label>
        <input type="number" min="0" step="0.01" name="proposed_rent"
               class="form-control"
               value="{{ old('proposed_rent', $approval->proposed_rent ?? '') }}">
    </div>

    <div class="col-md-3">
        <label class="form-label">Agreed Rent</label>
        <input type="number" min="0" step="0.01" name="agreed_rent"
               class="form-control"
               value="{{ old('agreed_rent', $approval->agreed_rent ?? '') }}">
    </div>

    <div class="col-md-3">
        <label class="form-label">Service Charge</label>
        <input type="number" min="0" step="0.01" name="service_charge"
               class="form-control"
               value="{{ old('service_charge', $approval->service_charge ?? '') }}">
    </div>

    <div class="col-md-3">
        <label class="form-label">Security Deposit</label>
        <input type="number" min="0" step="0.01" name="security_deposit"
               class="form-control"
               value="{{ old('security_deposit', $approval->security_deposit ?? '') }}">
    </div>

    <div class="col-md-6">
        <label class="form-label">Proposed Start Date</label>
        <input type="date" name="proposed_start_date" class="form-control"
               value="{{ old('proposed_start_date', isset($approval->proposed_start_date) ? $approval->proposed_start_date->format('Y-m-d') : '') }}">
    </div>

    <div class="col-md-6">
        <label class="form-label">Proposed End Date</label>
        <input type="date" name="proposed_end_date" class="form-control"
               value="{{ old('proposed_end_date', isset($approval->proposed_end_date) ? $approval->proposed_end_date->format('Y-m-d') : '') }}">
    </div>

    <div class="col-12">
        <label class="form-label">Commercial / Legal Deviations</label>
        <textarea name="deviations" class="form-control" rows="3">{{ old('deviations', $approval->deviations ?? '') }}</textarea>
    </div>

    <div class="col-12">
        <label class="form-label">Exceptions</label>
        <textarea name="exceptions" class="form-control" rows="2">{{ old('exceptions', $approval->exceptions ?? '') }}</textarea>
    </div>

    <div class="col-md-4">
        <label class="form-label">Credit Check</label>
        <select name="credit_check_status" class="form-select" required>
            @foreach(['PENDING', 'PASSED', 'FAILED', 'CONDITIONAL'] as $value)
                <option value="{{ $value }}"
                    @selected(old('credit_check_status', $approval->credit_check_status ?? 'PENDING') === $value)>
                    {{ $value }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-4">
        <label class="form-label">Credit Risk</label>
        <select name="credit_risk_rating" class="form-select">
            <option value="">Not rated</option>
            @foreach(['LOW', 'MEDIUM', 'HIGH', 'CRITICAL'] as $value)
                <option value="{{ $value }}"
                    @selected(old('credit_risk_rating', $approval->credit_risk_rating ?? '') === $value)>
                    {{ $value }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-4">
        <label class="form-label">SLA (days)</label>
        <input type="number" min="1" max="90" name="sla_days"
               class="form-control" required
               value="{{ old('sla_days', $approval->sla_days ?? 5) }}">
    </div>

    <div class="col-12">
        <label class="form-label">Credit Check Notes</label>
        <textarea name="credit_check_notes" class="form-control" rows="2">{{ old('credit_check_notes', $approval->credit_check_notes ?? '') }}</textarea>
    </div>

    <div class="col-12">
        <label class="form-label">Conditions Before Execution</label>
        <textarea name="conditions" class="form-control" rows="3">{{ old('conditions', $approval->conditions ?? '') }}</textarea>
    </div>

    <div class="col-12">
        <h6 class="mt-3">Authority Matrix — Assigned Approvers</h6>
    </div>

    @foreach([
        'commercial_approver_id' => 'Commercial Approver',
        'legal_approver_id' => 'Legal Approver',
        'finance_approver_id' => 'Finance Approver',
    ] as $field => $label)
        @php
            $role = str_replace('_approver_id', '', $field);
            $matrixKey = strtoupper($role);
            $savedApprover = $approval->authority_matrix[$matrixKey] ?? '';
        @endphp
        <div class="col-md-4">
            <label class="form-label">{{ $label }}</label>
            <select name="{{ $field }}" class="form-select">
                <option value="">Unassigned</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}"
                        @selected((string) old($field, $savedApprover) === (string) $user->id)>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
        </div>
    @endforeach

    <div class="col-12">
        <label class="form-label">Supporting Attachment</label>
        <input type="file" name="attachment" class="form-control"
               accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
        @if(!empty($approval?->attachment_path))
            <a href="{{ asset('storage/' . $approval->attachment_path) }}"
               target="_blank" rel="noopener">View existing attachment</a>
        @endif
        @error('attachment') <div class="text-danger">{{ $message }}</div> @enderror
    </div>
</div>
