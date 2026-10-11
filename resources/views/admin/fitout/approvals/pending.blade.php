@extends('layouts.app')

@section('title', 'Pending Fit-Out Approvals')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                Pending Approvals
            </h4>

            <p class="text-muted mb-0">
                Review and process pending fit-out approvals.
            </p>
        </div>

        <a
            href="{{ route('admin.fitout.approvals.index') }}"
            class="btn btn-outline-secondary"
        >
            <i class="bi bi-list me-1"></i>
            All Approvals
        </a>

    </div>


    {{-- Messages --}}
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


    {{-- Pending Approval Table --}}
    <div class="card">

        <div class="card-header">

            <div class="d-flex justify-content-between align-items-center">

                <h5 class="mb-0">
                    Pending Approval Queue
                </h5>

                <span class="badge bg-warning text-dark">

                    {{ $approvals->total() }} Pending

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
                                Approval Type
                            </th>

                            <th>
                                Level
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


                                {{-- Request Number --}}
                                <td>

                                    @if($approval->fitoutRequest)

                                        <a
                                            href="{{ route('admin.fitout.requests.show', $approval->fitoutRequest->id) }}"
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

                                    @if($approval->fitoutRequest?->tenant)

                                        {{
                                            $approval->fitoutRequest->tenant->company_name
                                            ?? $approval->fitoutRequest->tenant->company_name
                                            ?? 'N/A'
                                        }}

                                    @else

                                        N/A

                                    @endif

                                </td>


                                {{-- Unit --}}
                                <td>

                                    {{ $approval->fitoutRequest?->unit?->unit_no ?? 'N/A' }}

                                </td>


                                {{-- Contractor --}}
                                <td>

                                    {{ $approval->fitoutRequest?->contractor?->contractor_name ?? 'N/A' }}

                                </td>


                                {{-- Approval Type --}}
                                <td>

                                    <strong>
                                        {{ $approval->approval_type }}
                                    </strong>

                                </td>


                                {{-- Level --}}
                                <td>

                                    <span class="badge bg-secondary">

                                        Level {{ $approval->approval_level }}

                                    </span>

                                </td>


                                {{-- Status --}}
                                <td>

                                    <span class="badge bg-warning text-dark">

                                        Pending

                                    </span>

                                </td>


                                {{-- Action --}}
                                <td class="text-end">

                                    <a
                                        href="{{ route('admin.fitout.approvals.show', $approval->id) }}"
                                        class="btn btn-sm btn-primary"
                                    >

                                        <i class="bi bi-eye me-1"></i>

                                        Review

                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="9"
                                    class="text-center py-5"
                                >

                                    <div class="mb-2">

                                        <i
                                            class="bi bi-check-circle"
                                            style="font-size:45px;"
                                        ></i>

                                    </div>

                                    <h6>
                                        No Pending Approvals
                                    </h6>

                                    <p class="text-muted mb-0">

                                        There are currently no approvals
                                        waiting for review.

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