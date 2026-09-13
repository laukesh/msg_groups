@extends('layouts.app')

@section('title', 'Commissioning Certificates')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                Commissioning Certificates
            </h4>

            <div class="text-muted">
                {{ $project->project_code ?? '' }}
                -
                {{ $project->project_name ?? '' }}
            </div>

        </div>

        <div class="d-flex gap-2">

            <a
                href="{{ route('admin.projects.commissioning.index', $project) }}"
                class="btn btn-outline-secondary"
            >
                <i class="fas fa-arrow-left me-1"></i>
                Commissioning
            </a>

            <a
                href="{{ route('admin.projects.commissioning.certificates.create', $project) }}"
                class="btn btn-primary"
            >
                <i class="fas fa-plus me-1"></i>
                New Certificate
            </a>

        </div>

    </div>


    @if(session('success'))

        <div class="alert alert-success">
            <i class="bi bi-check-circle me-1"></i>
            {{ session('success') }}
        </div>

    @endif


    @if(session('error'))

        <div class="alert alert-danger">
            {{ session('error') }}
        </div>

    @endif


    {{-- KPI --}}

    <div class="row g-3 mb-4">

        @foreach([
            ['Total', $totalCertificates, ''],
            ['Draft', $draftCertificates, 'secondary'],
            ['Submitted', $submittedCertificates, 'warning'],
            ['Approved', $approvedCertificates, 'success'],
            ['Rejected', $rejectedCertificates, 'danger'],
            ['Expired', $expiredCertificates, 'dark'],
        ] as [$label, $value, $color])

            <div class="col-xl-2 col-md-4 col-6">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body">

                        <div class="text-muted small">
                            {{ $label }}
                        </div>

                        <div class="fs-3 fw-bold {{ $color ? 'text-'.$color : '' }}">
                            {{ $value }}
                        </div>

                    </div>

                </div>

            </div>

        @endforeach

    </div>


    @if($expiringCertificates > 0)

        <div class="alert alert-warning">

            <i class="bi bi-exclamation-triangle me-1"></i>

            <strong>{{ $expiringCertificates }}</strong>
            approved certificate(s) will expire within the next 30 days.

        </div>

    @endif


    {{-- FILTER --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <form method="GET" class="row g-3">

                <div class="col-md-4">

                    <label class="form-label small">
                        Search
                    </label>

                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        class="form-control"
                        placeholder="Certificate No, type, scope..."
                    >

                </div>


                <div class="col-md-3">

                    <label class="form-label small">
                        Status
                    </label>

                    <select name="status" class="form-select">

                        <option value="">
                            All Status
                        </option>

                        @foreach([
                            'Draft',
                            'Submitted',
                            'Approved',
                            'Rejected',
                            'Expired'
                        ] as $status)

                            <option
                                value="{{ $status }}"
                                @selected(request('status') === $status)
                            >
                                {{ $status }}
                            </option>

                        @endforeach

                    </select>

                </div>


                <div class="col-md-3">

                    <label class="form-label small">
                        Certificate Type
                    </label>

                    <input
                        type="text"
                        name="certificate_type"
                        value="{{ request('certificate_type') }}"
                        class="form-control"
                    >

                </div>


                <div class="col-md-2 d-flex align-items-end gap-2">

                    <button class="btn btn-primary">
                        <i class="fas fa-search me-1"></i>
                        Filter
                    </button>

                    <a
                        href="{{ route('admin.projects.commissioning.certificates.index', $project) }}"
                        class="btn btn-light"
                    >
                        Reset
                    </a>

                </div>

            </form>

        </div>

    </div>


    {{-- TABLE --}}

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white d-flex justify-content-between">

            <h6 class="mb-0 fw-semibold">
                Certificate Register
            </h6>

            <span class="text-muted small">
                {{ $certificates->total() }} records
            </span>

        </div>


        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">

                    <tr>

                        <th>#</th>
                        <th>Certificate</th>
                        <th>Scope</th>
                        <th>Type</th>
                        <th>Issue Date</th>
                        <th>Valid Until</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>

                    </tr>

                </thead>

                <tbody>

                @forelse($certificates as $certificate)

                    @php

                        $statusClass = match($certificate->status) {
                            'Approved' => 'success',
                            'Submitted' => 'warning',
                            'Rejected' => 'danger',
                            'Expired' => 'dark',
                            default => 'secondary',
                        };

                    @endphp

                    <tr>

                        <td>
                            {{ $certificates->firstItem() + $loop->index }}
                        </td>


                        <td>

                            <a
                                href="{{ route('admin.projects.commissioning.certificates.show', [$project, $certificate]) }}"
                                class="fw-semibold text-decoration-none"
                            >
                                {{ $certificate->certificate_no }}
                            </a>

                        </td>


                        <td>

                            @if($certificate->scope)

                                <div class="fw-semibold">
                                    {{ $certificate->scope->scope_code }}
                                </div>

                                <div class="small text-muted">
                                    {{ $certificate->scope->scope_name }}
                                </div>

                            @else

                                <span class="text-muted">
                                    Project Level
                                </span>

                            @endif

                        </td>


                        <td>
                            {{ $certificate->certificate_type }}
                        </td>


                        <td>
                            {{ $certificate->issue_date?->format('d M Y') ?? '—' }}
                        </td>


                        <td>

                            @if($certificate->valid_until)

                                {{ $certificate->valid_until->format('d M Y') }}

                                @if(
                                    $certificate->status === 'Approved'
                                    && $certificate->valid_until->isPast()
                                )

                                    <span class="badge bg-danger ms-1">
                                        Expired
                                    </span>

                                @elseif(
                                    $certificate->status === 'Approved'
                                    && $certificate->valid_until->lte(now()->addDays(30))
                                )

                                    <span class="badge bg-warning text-dark ms-1">
                                        Expiring
                                    </span>

                                @endif

                            @else
                                —
                            @endif

                        </td>


                        <td>

                            <span class="badge bg-{{ $statusClass }}">
                                {{ $certificate->status }}
                            </span>

                        </td>


                        <td class="text-end">

                            <div class="d-flex gap-1">

                                <a
                                    href="{{ route('admin.projects.commissioning.certificates.show', [$project, $certificate]) }}"
                                    class="btn btn-sm btn-outline-primary"
                                >
                                    <i class="fas fa-eye"></i>
                                </a>

                                @if(in_array($certificate->status, ['Draft', 'Rejected']))

                                    <a
                                        href="{{ route('admin.projects.commissioning.certificates.edit', [$project, $certificate]) }}"
                                        class="btn btn-sm btn-outline-secondary"
                                    >
                                        <i class="fas fa-edit"></i>
                                    </a>

                                @endif

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="8"
                            class="text-center py-5 text-muted"
                        >

                            <i class="bi bi-award fs-2 d-block mb-2"></i>

                            No commissioning certificates found.

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>


        @if($certificates->hasPages())

            <div class="card-footer bg-white">
                {{ $certificates->links() }}
            </div>

        @endif

    </div>

</div>

@endsection