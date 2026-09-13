@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                Contractor Details
            </h4>

            <p class="text-muted mb-0">
                View contractor registration and compliance information
            </p>
        </div>

        <div class="d-flex gap-2">

            <a href="{{ route('admin.fitout.contractors.edit', $contractor->id) }}"
               class="btn btn-primary">

                Edit Contractor

            </a>


            @if($contractor->status === 'Pending')

                <form method="POST"
                      action="{{ route('admin.fitout.contractors.approve', $contractor->id) }}"
                      onsubmit="return confirm('Are you sure you want to approve this contractor?');">

                    @csrf

                    <button type="submit"
                            class="btn btn-success">

                        Approve

                    </button>

                </form>


                <form method="POST"
                      action="{{ route('admin.fitout.contractors.reject', $contractor->id) }}"
                      onsubmit="return confirm('Are you sure you want to reject this contractor?');">

                    @csrf

                    <button type="submit"
                            class="btn btn-danger">

                        Reject

                    </button>

                </form>

            @elseif($contractor->status === 'Approved')

                <form method="POST"
                      action="{{ route('admin.fitout.contractors.suspend', $contractor->id) }}"
                      onsubmit="return confirm('Are you sure you want to suspend this contractor?');">

                    @csrf

                    <button type="submit"
                            class="btn btn-warning">

                        Suspend

                    </button>

                </form>

            @endif


            <a href="{{ route('admin.fitout.contractors.index') }}"
               class="btn btn-secondary">

                Back

            </a>

        </div>

    </div>


    {{-- Success --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- Error --}}
    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    <div class="row">

        {{-- ===================================================== --}}
        {{-- BASIC INFORMATION --}}
        {{-- ===================================================== --}}

        <div class="col-lg-8">

            <div class="card mb-4">

                <div class="card-header">

                    <strong>
                        Contractor Information
                    </strong>

                </div>

                <div class="card-body">

                    <div class="row g-4">

                        <div class="col-md-6">

                            <small class="text-muted">
                                Contractor Code
                            </small>

                            <div class="fw-bold">
                                {{ $contractor->contractor_code }}
                            </div>

                        </div>


                        <div class="col-md-6">

                            <small class="text-muted">
                                Status
                            </small>

                            <div>

                                @if($contractor->status === 'Approved')

                                    <span class="badge bg-success">
                                        Approved
                                    </span>

                                @elseif($contractor->status === 'Pending')

                                    <span class="badge bg-warning text-dark">
                                        Pending
                                    </span>

                                @elseif($contractor->status === 'Rejected')

                                    <span class="badge bg-danger">
                                        Rejected
                                    </span>

                                @elseif($contractor->status === 'Suspended')

                                    <span class="badge bg-dark">
                                        Suspended
                                    </span>

                                @elseif($contractor->status === 'Expired')

                                    <span class="badge bg-secondary">
                                        Expired
                                    </span>

                                @else

                                    <span class="badge bg-secondary">
                                        {{ $contractor->status }}
                                    </span>

                                @endif

                            </div>

                        </div>


                        <div class="col-md-12">

                            <small class="text-muted">
                                Contractor / Company Name
                            </small>

                            <div class="fs-5 fw-bold">
                                {{ $contractor->contractor_name }}
                            </div>

                        </div>


                        <div class="col-md-6">

                            <small class="text-muted">
                                Contact Person
                            </small>

                            <div>
                                {{ $contractor->contact_person ?? '-' }}
                            </div>

                        </div>


                        <div class="col-md-6">

                            <small class="text-muted">
                                Mobile
                            </small>

                            <div>
                                {{ $contractor->mobile ?? '-' }}
                            </div>

                        </div>


                        <div class="col-md-6">

                            <small class="text-muted">
                                Company Email
                            </small>

                            <div>
                                {{ $contractor->email ?? '-' }}
                            </div>

                        </div>


                        <div class="col-md-6">

                            <small class="text-muted">
                                Worker Count
                            </small>

                            <div>
                                {{ $contractor->worker_count ?? 0 }}
                            </div>

                        </div>


                        <div class="col-md-12">

                            <small class="text-muted">
                                Address
                            </small>

                            <div>
                                {{ $contractor->address ?? '-' }}
                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- LICENSE --}}
            {{-- ================================================= --}}

            <div class="card mb-4">

                <div class="card-header">

                    <strong>
                        License & Registration
                    </strong>

                </div>

                <div class="card-body">

                    <div class="row g-4">

                        <div class="col-md-4">

                            <small class="text-muted">
                                Trade License No.
                            </small>

                            <div>
                                {{ $contractor->trade_license_no ?? '-' }}
                            </div>

                        </div>


                        <div class="col-md-4">

                            <small class="text-muted">
                                Labour License No.
                            </small>

                            <div>
                                {{ $contractor->labour_license_no ?? '-' }}
                            </div>

                        </div>


                        <div class="col-md-4">

                            <small class="text-muted">
                                GST Number
                            </small>

                            <div>
                                {{ $contractor->gst_number ?? '-' }}
                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- INSURANCE --}}
            {{-- ================================================= --}}

            <div class="card mb-4">

                <div class="card-header">

                    <strong>
                        Insurance & Safety
                    </strong>

                </div>

                <div class="card-body">

                    <div class="row g-4">

                        <div class="col-md-6">

                            <small class="text-muted">
                                Insurance Policy No.
                            </small>

                            <div>
                                {{ $contractor->insurance_policy_no ?? '-' }}
                            </div>

                        </div>


                        <div class="col-md-6">

                            <small class="text-muted">
                                Insurance Expiry
                            </small>

                            <div>

                                @if($contractor->insurance_expiry)

                                    {{ $contractor->insurance_expiry->format('d M Y') }}

                                    @if($contractor->insurance_expiry->isPast())

                                        <span class="badge bg-danger ms-2">
                                            Expired
                                        </span>

                                    @elseif(
                                        $contractor->insurance_expiry->diffInDays(now()) <= 30
                                    )

                                        <span class="badge bg-warning text-dark ms-2">
                                            Expiring Soon
                                        </span>

                                    @else

                                        <span class="badge bg-success ms-2">
                                            Valid
                                        </span>

                                    @endif

                                @else

                                    -

                                @endif

                            </div>

                        </div>


                        <div class="col-md-6">

                            <small class="text-muted">
                                Safety Induction Date
                            </small>

                            <div>

                                @if($contractor->safety_induction_date)

                                    {{ $contractor->safety_induction_date->format('d M Y') }}

                                    <span class="badge bg-success ms-2">
                                        Completed
                                    </span>

                                @else

                                    <span class="badge bg-warning text-dark">
                                        Pending
                                    </span>

                                @endif

                            </div>

                        </div>


                        <div class="col-md-6">

                            <small class="text-muted">
                                Worker Count
                            </small>

                            <div>
                                {{ $contractor->worker_count ?? 0 }}
                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- FIT-OUT REQUESTS --}}
            {{-- ================================================= --}}

            <div class="card mb-4">

                <div class="card-header">

                    <strong>
                        Assigned Fit-Out Requests
                    </strong>

                </div>

                <div class="card-body p-0">

                    <div class="table-responsive">

                        <table class="table table-hover mb-0">

                            <thead class="table-light">

                                <tr>

                                    <th>
                                        Request No.
                                    </th>

                                    <th>
                                        Type
                                    </th>

                                    <th>
                                        Proposed Start
                                    </th>

                                    <th>
                                        Proposed End
                                    </th>

                                    <th>
                                        Status
                                    </th>

                                </tr>

                            </thead>

                            <tbody>

                                @forelse(
                                    $contractor->fitoutRequests
                                    as $request
                                )

                                    <tr>

                                        <td>
                                            {{ $request->request_no }}
                                        </td>

                                        <td>
                                            {{ $request->fitout_type }}
                                        </td>

                                        <td>
                                            {{ $request->proposed_start_date
                                                ? $request->proposed_start_date->format('d M Y')
                                                : '-'
                                            }}
                                        </td>

                                        <td>
                                            {{ $request->proposed_end_date
                                                ? $request->proposed_end_date->format('d M Y')
                                                : '-'
                                            }}
                                        </td>

                                        <td>

                                            <span class="badge bg-secondary">
                                                {{ $request->fitout_status }}
                                            </span>

                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td colspan="5"
                                            class="text-center py-4 text-muted">

                                            No fit-out requests assigned.

                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- RIGHT SIDEBAR --}}
        {{-- ===================================================== --}}

        <div class="col-lg-4">


            {{-- LOGIN ACCOUNT --}}
            <div class="card mb-4">

                <div class="card-header">

                    <strong>
                        Login Account
                    </strong>

                </div>

                <div class="card-body">

                    @if($contractor->user)

                        <div class="mb-3">

                            <small class="text-muted">
                                Name
                            </small>

                            <div>
                                {{ $contractor->user->name }}
                            </div>

                        </div>


                        <div class="mb-3">

                            <small class="text-muted">
                                Email
                            </small>

                            <div>
                                {{ $contractor->user->email }}
                            </div>

                        </div>


                        <div class="mb-3">

                            <small class="text-muted">
                                Phone
                            </small>

                            <div>
                                {{ $contractor->user->phone ?? '-' }}
                            </div>

                        </div>


                        <div>

                            <small class="text-muted">
                                Role
                            </small>

                            <div>
                                {{ $contractor->user->role ?? 'Contractor' }}
                            </div>

                        </div>

                    @else

                        <div class="alert alert-warning mb-0">

                            No login account linked.

                        </div>

                    @endif

                </div>

            </div>


            {{-- REMARKS --}}
            <div class="card mb-4">

                <div class="card-header">

                    <strong>
                        Remarks
                    </strong>

                </div>

                <div class="card-body">

                    {{ $contractor->remarks ?? '-' }}

                </div>

            </div>


            {{-- RECORD INFORMATION --}}
            <div class="card">

                <div class="card-header">

                    <strong>
                        Record Information
                    </strong>

                </div>

                <div class="card-body">

                    <div class="mb-3">

                        <small class="text-muted">
                            Created At
                        </small>

                        <div>
                            {{ $contractor->created_at
                                ? $contractor->created_at->format('d M Y H:i')
                                : '-'
                            }}
                        </div>

                    </div>


                    <div>

                        <small class="text-muted">
                            Last Updated
                        </small>

                        <div>
                            {{ $contractor->updated_at
                                ? $contractor->updated_at->format('d M Y H:i')
                                : '-'
                            }}
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection