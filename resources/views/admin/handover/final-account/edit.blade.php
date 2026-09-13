@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <div class="d-flex align-items-center gap-2 mb-4">
        <a href="{{ route('admin.projects.handover.final-account.show', [$project, $account]) }}"
           class="btn btn-light btn-sm">
            <i class="ri-arrow-left-line"></i>
        </a>
        <div>
            <h4 class="mb-0">Edit Final Account</h4>
            <div class="text-muted small">
                {{ $account->final_account_no }}
                · {{ $contract?->contract_no ?? $contract?->contract_number ?? 'Procurement Contract' }}
            </div>
        </div>
    </div>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form method="POST"
          action="{{ route('admin.projects.handover.final-account.update', [$project, $account]) }}">
        @csrf
        @method('PUT')

        <div class="row g-4">

            <div class="col-xl-8">

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-1">System Financial Values</h5>
                        <small class="text-muted">
                            These values are read-only and are recalculated from source modules.
                        </small>
                    </div>

                    <div class="card-body">
                        <div class="row g-3">

                            @foreach([
                                ['Contract Value', 'contract_value'],
                                ['Amount Paid', 'amount_paid'],
                                ['Approved Variations', 'approved_variations'],
                                ['Approved Claims', 'approved_claims'],
                                ['Gross Final Amount', 'gross_final_amount'],
                                ['Balance Payable', 'balance_payable'],
                            ] as [$label, $field])
                                <div class="col-md-6">
                                    <label class="form-label">{{ $label }}</label>
                                    <div class="input-group">
                                        <span class="input-group-text">{{ $currency }}</span>
                                        <input type="text"
                                               class="form-control"
                                               value="{{ number_format($account->{$field}, 2, '.', '') }}"
                                               readonly>
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    </div>
                </div>


                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Finance Adjustments</h5>
                    </div>

                    <div class="card-body">
                        <div class="row g-3">

                            <div class="col-md-6">
                                <label class="form-label">Advance Payment</label>
                                <div class="input-group">
                                    <span class="input-group-text">{{ $currency }}</span>
                                    <input type="number" step="0.01" min="0"
                                           name="advance_payment"
                                           class="form-control @error('advance_payment') is-invalid @enderror"
                                           value="{{ old('advance_payment', $account->advance_payment) }}">
                                </div>
                                @error('advance_payment')<div class="text-danger small">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Advance Recovery</label>
                                <div class="input-group">
                                    <span class="input-group-text">{{ $currency }}</span>
                                    <input type="number" step="0.01" min="0"
                                           name="advance_recovery"
                                           class="form-control"
                                           value="{{ old('advance_recovery', $account->advance_recovery) }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Retention Amount</label>
                                <div class="input-group">
                                    <span class="input-group-text">{{ $currency }}</span>
                                    <input type="number" step="0.01" min="0"
                                           name="retention_amount"
                                           class="form-control"
                                           value="{{ old('retention_amount', $account->retention_amount) }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Deductions</label>
                                <div class="input-group">
                                    <span class="input-group-text">{{ $currency }}</span>
                                    <input type="number" step="0.01" min="0"
                                           name="deductions"
                                           class="form-control"
                                           value="{{ old('deductions', $account->deductions) }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Other Adjustments</label>
                                <div class="input-group">
                                    <span class="input-group-text">{{ $currency }}</span>
                                    <input type="number" step="0.01"
                                           name="other_adjustments"
                                           class="form-control"
                                           value="{{ old('other_adjustments', $account->other_adjustments) }}">
                                </div>
                                <small class="text-muted">Positive or negative.</small>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Certified Amount</label>
                                <div class="input-group">
                                    <span class="input-group-text">{{ $currency }}</span>
                                    <input type="number" step="0.01" min="0"
                                           name="certified_amount"
                                           class="form-control"
                                           value="{{ old('certified_amount', $account->certified_amount) }}">
                                </div>
                            </div>

                        </div>
                    </div>
                </div>


                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Statements & Remarks</h5>
                    </div>

                    <div class="card-body">

                        <div class="mb-3">
                            <label class="form-label">Contractor Statement</label>
                            <textarea name="contractor_statement"
                                      rows="4"
                                      class="form-control">{{ old('contractor_statement', $account->contractor_statement) }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Finance Remarks</label>
                            <textarea name="finance_remarks"
                                      rows="4"
                                      class="form-control">{{ old('finance_remarks', $account->finance_remarks) }}</textarea>
                        </div>

                        <div>
                            <label class="form-label">Remarks</label>
                            <textarea name="remarks"
                                      rows="4"
                                      class="form-control">{{ old('remarks', $account->remarks) }}</textarea>
                        </div>

                    </div>
                </div>

            </div>


            <div class="col-xl-4">

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h6 class="mb-0">Account Summary</h6>
                    </div>
                    <div class="card-body">

                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Contract</span>
                            <strong>
                                {{ $contract?->contract_no ?? $contract?->contract_number ?? '-' }}
                            </strong>
                        </div>

                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Status</span>
                            <span class="badge bg-secondary">{{ $account->status }}</span>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between mb-2">
                            <span>Gross Final</span>
                            <strong>{{ $currency }} {{ number_format($account->gross_final_amount, 2) }}</strong>
                        </div>

                        <div class="d-flex justify-content-between mb-2">
                            <span>Paid</span>
                            <strong class="text-primary">{{ $currency }} {{ number_format($account->amount_paid, 2) }}</strong>
                        </div>

                        <div class="d-flex justify-content-between">
                            <span>Balance</span>
                            <strong class="{{ $account->balance_payable > 0 ? 'text-danger' : 'text-success' }}">
                                {{ $currency }} {{ number_format($account->balance_payable, 2) }}
                            </strong>
                        </div>

                    </div>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-body">

                        <button type="submit" class="btn btn-primary w-100 mb-2">
                            <i class="ri-save-line me-1"></i>
                            Save Final Account
                        </button>

                        <a href="{{ route('admin.projects.handover.final-account.show', [$project, $account]) }}"
                           class="btn btn-light w-100">
                            Cancel
                        </a>

                    </div>
                </div>

            </div>

        </div>
    </form>
</div>
@endsection
