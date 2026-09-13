<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="invoice_no">Invoice No</label>
            <input type="text" name="invoice_no" id="invoice_no" class="form-control" value="{{ old('invoice_no', optional($invoice)->invoice_no) }}">
        </div>

        <div class="form-group">
            <label for="lease_agreement_id">Lease Agreement ID</label>
            <input type="number" name="lease_agreement_id" id="lease_agreement_id" class="form-control" value="{{ old('lease_agreement_id', optional($invoice)->lease_agreement_id) }}">
        </div>

        <div class="form-group">
            <label for="tenant_id">Tenant ID</label>
            <input type="number" name="tenant_id" id="tenant_id" class="form-control" value="{{ old('tenant_id', optional($invoice)->tenant_id) }}">
        </div>

        <div class="form-group">
            <label for="invoice_date">Invoice Date</label>
            <input type="date" name="invoice_date" id="invoice_date" class="form-control" value="{{ old('invoice_date', optional($invoice)->invoice_date ? optional($invoice)->invoice_date->format('Y-m-d') : null) }}">
        </div>

        <div class="form-group">
            <label for="billing_period_from">Billing Period From</label>
            <input type="date" name="billing_period_from" id="billing_period_from" class="form-control" value="{{ old('billing_period_from', optional($invoice)->billing_period_from ? optional($invoice)->billing_period_from->format('Y-m-d') : null) }}">
        </div>

        <div class="form-group">
            <label for="billing_period_to">Billing Period To</label>
            <input type="date" name="billing_period_to" id="billing_period_to" class="form-control" value="{{ old('billing_period_to', optional($invoice)->billing_period_to ? optional($invoice)->billing_period_to->format('Y-m-d') : null) }}">
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="due_date">Due Date</label>
            <input type="date" name="due_date" id="due_date" class="form-control" value="{{ old('due_date', optional($invoice)->due_date ? optional($invoice)->due_date->format('Y-m-d') : null) }}">
        </div>

        <div class="form-group">
            <label for="subtotal">Subtotal</label>
            <input type="text" name="subtotal" id="subtotal" class="form-control" value="{{ old('subtotal', optional($invoice)->subtotal) }}">
        </div>

        <div class="form-group">
            <label for="tax_amount">Tax Amount</label>
            <input type="text" name="tax_amount" id="tax_amount" class="form-control" value="{{ old('tax_amount', optional($invoice)->tax_amount) }}">
        </div>

        <div class="form-group">
            <label for="total_amount">Total Amount</label>
            <input type="text" name="total_amount" id="total_amount" class="form-control" value="{{ old('total_amount', optional($invoice)->total_amount) }}">
        </div>

        <div class="form-group">
            <label for="invoice_status">Status</label>
            <input type="text" name="invoice_status" id="invoice_status" class="form-control" value="{{ old('invoice_status', optional($invoice)->invoice_status) }}">
        </div>

        <div class="form-group">
            <label for="remarks">Remarks</label>
            <textarea name="remarks" id="remarks" class="form-control">{{ old('remarks', optional($invoice)->remarks) }}</textarea>
        </div>
    </div>
</div>
