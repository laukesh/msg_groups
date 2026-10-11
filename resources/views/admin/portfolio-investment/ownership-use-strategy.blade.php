@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Ownership / Use Strategy</h4>
            <p class="text-muted mb-0">
                Land ownership structure and ownership details
            </p>
        </div>

        <button type="button"
                class="btn btn-outline-secondary"
                onclick="window.print()">
            <i class="ri-printer-line me-1"></i>
            Print Report
        </button>
    </div>

    {{-- Summary Cards --}}
    <div class="row g-3 mb-4">

        <div class="col-xl-4 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Total Ownership Records</p>
                            <h3 class="mb-0">
                                {{ number_format($totalOwners) }}
                            </h3>
                        </div>

                        <div class="avatar-sm bg-soft-primary rounded">
                            <i class="ri-user-settings-line fs-4 text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Lands with Ownership Records</p>
                            <h3 class="mb-0">
                                {{ number_format($totalOwnedLands) }}
                            </h3>
                        </div>

                        <div class="avatar-sm bg-soft-success rounded">
                            <i class="ri-landscape-line fs-4 text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Owner Types</p>
                            <h3 class="mb-0">
                                {{ number_format($ownerTypeSummary->count()) }}
                            </h3>
                        </div>

                        <div class="avatar-sm bg-soft-warning rounded">
                            <i class="ri-group-line fs-4 text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Owner Type Summary --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white">
            <h5 class="mb-0">Ownership Type Summary</h5>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Owner Type</th>
                            <th>Total Owners</th>
                            <th>Total Lands</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($ownerTypeSummary as $index => $summary)
                            <tr>
                                <td>{{ $index + 1 }}</td>

                                <td>
                                    {{ $summary->owner_type ?: 'Not Specified' }}
                                </td>

                                <td>
                                    {{ number_format($summary->total_owners) }}
                                </td>

                                <td>
                                    {{ number_format($summary->total_lands) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">
                                    No ownership type data available.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Ownership Details --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0">Ownership Details</h5>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Land</th>
                            <th>Owner Type</th>
                            <th>Owner Name</th>
                            <th>Ownership %</th>
                            <th>Ownership Period</th>
                            <th>Title Reference</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($ownershipRecords as $index => $owner)
                            <tr>
                                <td>{{ $index + 1 }}</td>

                                <td>
                                    <strong>
                                        {{ $owner->land_code ?: '—' }}
                                    </strong>
                                    <br>

                                    <small class="text-muted">
                                        {{ $owner->land_name ?: '—' }}
                                    </small>
                                </td>

                                <td>
                                    {{ $owner->owner_type ?: '—' }}
                                </td>

                                <td>
                                    {{ $owner->owner_name ?: '—' }}
                                </td>

                                <td>
                                    {{ $owner->ownership_percentage !== null
                                        ? number_format($owner->ownership_percentage, 4) . '%'
                                        : '—'
                                    }}
                                </td>

                                <td>
                                    @if($owner->ownership_start_date || $owner->ownership_end_date)
                                        {{ $owner->ownership_start_date
                                            ? \Carbon\Carbon::parse($owner->ownership_start_date)->format('d-m-Y')
                                            : '—'
                                        }}

                                        <br>

                                        <small class="text-muted">to</small>

                                        <br>

                                        {{ $owner->ownership_end_date
                                            ? \Carbon\Carbon::parse($owner->ownership_end_date)->format('d-m-Y')
                                            : 'Present'
                                        }}
                                    @else
                                        —
                                    @endif
                                </td>

                                <td>
                                    {{ $owner->title_reference ?: '—' }}
                                </td>

                                <td>
                                    <a href="{{ url('admin/land/lands/' . $owner->land_id) }}"
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="ri-eye-line"></i>
                                        Land
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted">
                                    No ownership records available.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

@endsection