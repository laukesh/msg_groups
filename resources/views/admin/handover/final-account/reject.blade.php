@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <div class="d-flex align-items-center gap-2 mb-4">
        <a href="{{ route('admin.projects.handover.final-account.show', [$project, $account]) }}"
           class="btn btn-light btn-sm">
            <i class="ri-arrow-left-line"></i>
        </a>
        <div>
            <h4 class="mb-0">Reject Final Account</h4>
            <div class="text-muted small">{{ $account->final_account_no }}</div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-xl-7 col-lg-9">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white">
                    <h5 class="mb-1 text-danger">Reject Final Account</h5>
                    <small class="text-muted">
                        The account will return to Rejected and can be corrected and resubmitted.
                    </small>
                </div>

                <form method="POST"
                      action="{{ route('admin.projects.handover.final-account.reject', [$project, $account]) }}">
                    @csrf

                    <div class="card-body">

                        <div class="alert alert-warning">
                            <i class="ri-alert-line me-1"></i>
                            Please provide a clear reason for rejection.
                        </div>

                        <label class="form-label">
                            Rejection Reason <span class="text-danger">*</span>
                        </label>

                        <textarea name="rejection_reason"
                                  rows="6"
                                  required
                                  class="form-control @error('rejection_reason') is-invalid @enderror"
                                  placeholder="Enter rejection reason...">{{ old('rejection_reason') }}</textarea>

                        @error('rejection_reason')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                    </div>

                    <div class="card-footer bg-white d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.projects.handover.final-account.show', [$project, $account]) }}"
                           class="btn btn-light">
                            Cancel
                        </a>

                        <button type="submit"
                                class="btn btn-danger"
                                onclick="return confirm('Reject this Final Account?');">
                            <i class="ri-close-circle-line me-1"></i>
                            Reject Final Account
                        </button>
                    </div>

                </form>

            </div>

        </div>
    </div>
</div>
@endsection
