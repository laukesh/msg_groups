@extends('layouts.app')

@section('title', 'Status Audits')

@section('content')

<div class="container-fluid">

    {{-- ========================================================= --}}
    {{-- PAGE HEADER --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">

                <i class="fas fa-history me-2"></i>

                Status Audits

            </h4>

            <div class="text-muted">

                View status change history for
                <strong>{{ $user->name }}</strong>

            </div>
        </div>


        <a href="{{ route('admin.users.show', $user->id) }}"
           class="btn btn-secondary">

            <i class="fas fa-arrow-left me-1"></i>

            Back to User

        </a>

    </div>


    {{-- ========================================================= --}}
    {{-- SUCCESS MESSAGE --}}
    {{-- ========================================================= --}}

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            <i class="fas fa-check-circle me-1"></i>

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- VALIDATION / ERROR MESSAGE --}}
    {{-- ========================================================= --}}

    @if($errors->any())

        <div class="alert alert-danger alert-dismissible fade show">

            <i class="fas fa-exclamation-circle me-1"></i>

            <strong>Please fix the following errors:</strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- AUDIT CARD --}}
    {{-- ========================================================= --}}

    <div class="card">

        <div class="card-header">

            <div class="d-flex justify-content-between align-items-center">

                <h5 class="mb-0">

                    <i class="fas fa-exchange-alt me-1"></i>

                    Status Change History

                </h5>

                <span class="badge bg-primary">

                    {{ $audits->total() }} Records

                </span>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- AUDIT TABLE --}}
        {{-- ===================================================== --}}

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th width="70">
                                #
                            </th>

                            <th>
                                Field
                            </th>

                            <th>
                                Old Value
                            </th>

                            <th>
                                New Value
                            </th>

                            <th>
                                Changed By
                            </th>

                            <th>
                                Changed At
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($audits as $audit)

                            <tr>

                                {{-- ID --}}
                                <td>

                                    {{ $audit->id }}

                                </td>


                                {{-- Field --}}
                                <td>

                                    <span class="badge bg-info text-dark">

                                        {{ $audit->field }}

                                    </span>

                                </td>


                                {{-- Old Value --}}
                                <td>

                                    @if(
                                        $audit->old_value !== null &&
                                        $audit->old_value !== ''
                                    )

                                        <span class="badge bg-danger">

                                            {{ $audit->old_value }}

                                        </span>

                                    @else

                                        <span class="text-muted">
                                            —
                                        </span>

                                    @endif

                                </td>


                                {{-- New Value --}}
                                <td>

                                    @if(
                                        $audit->new_value !== null &&
                                        $audit->new_value !== ''
                                    )

                                        <span class="badge bg-success">

                                            {{ $audit->new_value }}

                                        </span>

                                    @else

                                        <span class="text-muted">
                                            —
                                        </span>

                                    @endif

                                </td>


                                {{-- Changed By --}}
                                <td>

                                    <div class="d-flex align-items-center">

                                        <i class="fas fa-user-circle fa-lg text-muted me-2"></i>

                                        <span class="fw-semibold">

                                            {{ $audit->changed_by ?? 'System' }}

                                        </span>

                                    </div>

                                </td>


                                {{-- Changed At --}}
                                <td>

                                    @if($audit->created_at)

                                        <div class="fw-semibold">

                                            {{ $audit->created_at->format('d M Y') }}

                                        </div>

                                        <small class="text-muted">

                                            {{ $audit->created_at->format('h:i A') }}

                                        </small>

                                    @else

                                        <span class="text-muted">
                                            —
                                        </span>

                                    @endif

                                </td>

                            </tr>


                        @empty

                            <tr>

                                <td colspan="6"
                                    class="text-center py-5">

                                    <div class="text-muted">

                                        <i class="fas fa-history fa-3x mb-3"></i>

                                        <h5>
                                            No Status Audits Found
                                        </h5>

                                        <p class="mb-0">

                                            No status changes have been
                                            recorded for this user.

                                        </p>

                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- PAGINATION --}}
        {{-- ===================================================== --}}

        @if($audits->hasPages())

            <div class="card-footer">

                <div class="d-flex justify-content-between align-items-center">

                    <div class="text-muted small">

                        Showing
                        <strong>{{ $audits->firstItem() }}</strong>
                        to
                        <strong>{{ $audits->lastItem() }}</strong>
                        of
                        <strong>{{ $audits->total() }}</strong>
                        records

                    </div>

                    <div>

                        {{ $audits->links() }}

                    </div>

                </div>

            </div>

        @endif

    </div>

</div>

@endsection