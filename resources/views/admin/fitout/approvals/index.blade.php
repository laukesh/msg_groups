@extends('layouts.app')

@section('title', 'Fit-Out Approvals')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                Fit-Out Approvals
            </h4>

            <p class="text-muted mb-0">
                Manage approval workflows for fit-out requests.
            </p>
        </div>

        <div>

            <a
                href="{{ route('admin.fitout.approvals.pending') }}"
                class="btn btn-warning"
            >
                <i class="bi bi-clock-history"></i>
                Pending Approvals
            </a>

        </div>

    </div>


    {{-- Success Message --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    {{-- Error Message --}}
    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    {{-- Approval Table --}}
    <div class="card">

        <div class="card-header">

            <div class="d-flex justify-content-between align-items-center">

                <h5 class="mb-0">
                    All Approvals
                </h5>

                <span class="text-muted">
                    Total: {{ $approvals->total() }}
                </span>

            </div>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th>#</th>

                            <th>
                                Request No.
                            </th>

                            <th>
                                Tenant
                            </th>

                            <th>
                                Unit
                            </th>

                            <th>
                                Contractor
                            </th>

                            <th>
                                Approval
                            </th>

                            <th>
                                Level
                            </th>

                            <th>
                                Approver
                            </th>

                            <th>
                                Status
                            </th>

                            <th class="text-end">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($approvals as $approval)

                            <tr>

                                {{-- Number --}}
                                <td>

                                    {{ $approvals->firstItem() + $loop->index }}

                                </td>


                                {{-- Request --}}
                                <td>

                                    @if($approval->fitoutRequest)

                                        <a
                                            href="#"
                                            class="fw-semibold text-decoration-none"
                                        >

                                            {{ $approval->fitoutRequest->request_no }}

                                        </a>

                                    @else

                                        <span class="text-muted">
                                            N/A
                                        </span>

                                    @endif

                                </td>


                                {{-- Tenant --}}
                                <td>

                                    {{ $approval->fitoutRequest->tenant->company_name
                                        ?? $approval->fitoutRequest->tenant->company_name
                                        ?? 'N/A' }}

                                </td>


                                {{-- Unit --}}
                                <td>

                                    {{ $approval->fitoutRequest->unit->unit_no ?? 'N/A' }}

                                </td>


                                {{-- Contractor --}}
                                <td>

                                    {{ $approval->fitoutRequest->contractor->contractor_name ?? 'N/A' }}

                                </td>


                                {{-- Approval Type --}}
                                <td>

                                    <div class="fw-semibold">

                                        {{ $approval->approval_type }}

                                    </div>

                                </td>


                                {{-- Level --}}
                                <td>

                                    <span class="badge bg-secondary">

                                        Level {{ $approval->approval_level }}

                                    </span>

                                </td>


                                {{-- Approver --}}
                                <td>

                                    @if($approval->approver)

                                        {{ $approval->approver->name }}

                                    @else

                                        <span class="text-muted">
                                            Not Assigned
                                        </span>

                                    @endif

                                </td>


                                {{-- Status --}}
                                <td>

                                    @php

                                        $statusClass = match(
                                            $approval->approval_status
                                        ) {

                                            'Approved' =>
                                                'bg-success',

                                            'Rejected' =>
                                                'bg-danger',

                                            'Pending' =>
                                                'bg-warning text-dark',

                                            default =>
                                                'bg-secondary',

                                        };

                                    @endphp


                                    <span class="badge {{ $statusClass }}">

                                        {{ $approval->approval_status }}

                                    </span>

                                </td>


                                {{-- Actions --}}
                                <td class="text-end">

                                    <div class="dropdown">

                                        <button
                                            class="btn btn-sm btn-outline-secondary dropdown-toggle"
                                            type="button"
                                            data-bs-toggle="dropdown"
                                            aria-expanded="false"
                                        >

                                            Actions

                                        </button>


                                        <ul class="dropdown-menu dropdown-menu-end">

                                            <li>

                                                <a
                                                    class="dropdown-item"
                                                    href="{{ route('admin.fitout.approvals.show', $approval->id) }}"
                                                >

                                                    <i class="bi bi-eye me-2"></i>

                                                    View Details

                                                </a>

                                            </li>


                                            @if($approval->approval_status === 'Pending')

                                                <li>
                                                    <hr class="dropdown-divider">
                                                </li>


                                                <li>

                                                    <form
                                                        action="{{ route('admin.fitout.approvals.approve', $approval->id) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('Are you sure you want to approve this approval?');"
                                                    >

                                                        @csrf

                                                        <button
                                                            type="submit"
                                                            class="dropdown-item text-success"
                                                        >

                                                            <i class="bi bi-check-circle me-2"></i>

                                                            Approve

                                                        </button>

                                                    </form>

                                                </li>


                                                <li>

                                                    <a
                                                        class="dropdown-item text-danger"
                                                        href="{{ route('admin.fitout.approvals.show', $approval->id) }}"
                                                    >

                                                        <i class="bi bi-x-circle me-2"></i>

                                                        Review / Reject

                                                    </a>

                                                </li>

                                            @endif

                                        </ul>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="10"
                                    class="text-center py-5"
                                >

                                    <div class="mb-2">

                                        <i
                                            class="bi bi-check2-square"
                                            style="font-size:40px;"
                                        ></i>

                                    </div>


                                    <h6>
                                        No Approval Records Found
                                    </h6>


                                    <p class="text-muted mb-0">

                                        Approval workflows have not been
                                        generated yet.

                                    </p>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- Pagination --}}
        @if($approvals->hasPages())

            <div class="card-footer">

                {{ $approvals->links() }}

            </div>

        @endif

    </div>

</div>

@endsection