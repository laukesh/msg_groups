
@php
    $editing = isset($loi);
    $tenantLabel = fn ($tenant) =>
        $tenant->company_name ?? $tenant->tenant_name ?? $tenant->name ?? ('Tenant #' . $tenant->id);
@endphp

<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Tenant <span class="text-danger">*</span></label>
        <select name="tenant_id" class="form-select" required>
            <option value="">Select tenant</option>
            @foreach($tenants as $tenant)
                <option value="{{ $tenant->id }}"
                    @selected(old('tenant_id', $loi->tenant_id ?? '') == $tenant->id)>
                    {{ $tenantLabel($tenant) }}
                </option>
            @endforeach
        </select>
        @error('tenant_id') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">Unit <span class="text-danger">*</span></label>
        <select name="unit_id" class="form-select" required>
            <option value="">Select unit</option>
            @foreach($units as $unit)
                <option value="{{ $unit->id }}"
                    @selected(old('unit_id', $loi->unit_id ?? '') == $unit->id)>
                    {{ $unit->unit_no ?? ('Unit #' . $unit->id) }}
                    {{ !empty($unit->shop_name) ? ' - ' . $unit->shop_name : '' }}
                </option>
            @endforeach
        </select>
        @error('unit_id') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-8">
        <label class="form-label">LOI Title</label>
        <input type="text" name="title" class="form-control"
               value="{{ old('title', $loi->title ?? '') }}">
    </div>

    <div class="col-md-4">
        <label class="form-label">Lease Term (Months)</label>
        <input type="number" min="1" name="lease_term_months" class="form-control"
               value="{{ old('lease_term_months', $loi->lease_term_months ?? '') }}">
    </div>

    <div class="col-md-4">
        <label class="form-label">Proposed Rent</label>
        <input type="number" min="0" step="0.01" name="proposed_rent" class="form-control"
               value="{{ old('proposed_rent', $loi->proposed_rent ?? '') }}">
    </div>

    <div class="col-md-4">
        <label class="form-label">Service Charge</label>
        <input type="number" min="0" step="0.01" name="service_charge" class="form-control"
               value="{{ old('service_charge', $loi->service_charge ?? '') }}">
    </div>

    <div class="col-md-4">
        <label class="form-label">Security Deposit</label>
        <input type="number" min="0" step="0.01" name="security_deposit" class="form-control"
               value="{{ old('security_deposit', $loi->security_deposit ?? '') }}">
    </div>

    <div class="col-md-4">
        <label class="form-label">Proposed Area</label>
        <input type="number" min="0" step="0.01" name="proposed_area" class="form-control"
               value="{{ old('proposed_area', $loi->proposed_area ?? '') }}">
    </div>

    <div class="col-md-4">
        <label class="form-label">Issue Date</label>
        <input type="date" name="issue_date" class="form-control"
               value="{{ old('issue_date', isset($loi->issue_date) ? $loi->issue_date->format('Y-m-d') : '') }}">
        @error('issue_date') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-4">
        <label class="form-label">Expiry Date</label>
        <input type="date" name="expiry_date" class="form-control"
               value="{{ old('expiry_date', isset($loi->expiry_date) ? $loi->expiry_date->format('Y-m-d') : '') }}">
        @error('expiry_date') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

    <div class="col-12">
        <label class="form-label">Key Commercial Terms</label>
        <textarea name="key_terms" rows="4" class="form-control"
        >{{ old('key_terms', $loi->key_terms ?? '') }}</textarea>
    </div>

    <div class="col-12">
        <label class="form-label">Conditions</label>
        <textarea name="conditions" rows="4" class="form-control"
        >{{ old('conditions', $loi->conditions ?? '') }}</textarea>
    </div>

    <div class="col-12">
        <label class="form-label">Remarks</label>
        <textarea name="remarks" rows="3" class="form-control"
        >{{ old('remarks', $loi->remarks ?? '') }}</textarea>
    </div>

    <div class="col-12">
        <label class="form-label">Attachment (PDF, DOC, DOCX, JPG, PNG; max 10 MB)</label>
        <input type="file" name="attachment" class="form-control"
               accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
        @error('attachment') <div class="text-danger">{{ $message }}</div> @enderror

        @if($editing && $loi->attachment_path)
            <a class="d-inline-block mt-2"
               href="{{ asset('storage/' . $loi->attachment_path) }}"
               target="_blank" rel="noopener">View current attachment</a>
        @endif
    </div>
</div>
