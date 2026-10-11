@extends('layouts.app')

@section('title', 'Site Visits')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                <i class="ri-map-pin-user-line me-1"></i>
                Site Visits
            </h4>

            <p class="text-muted mb-0">
                Schedule and manage prospect property and unit visits.
            </p>

        </div>

        <a href="{{ route('admin.leasing.site-visits.create') }}"
           class="btn btn-primary">

            <i class="ri-add-line me-1"></i>
            Schedule Visit

        </a>

    </div>


    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            <i class="ri-checkbox-circle-line me-1"></i>

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- Filters --}}
    <div class="card shadow-sm border-0 mb-4">

        <div class="card-body">

            <form method="GET"
                  action="{{ route('admin.leasing.site-visits.index') }}">

                <div class="row g-3">

                    <div class="col-md-4">

                        <label class="form-label">
                            Search Prospect
                        </label>

                        <input type="text"
                               name="search"
                               class="form-control"
                               value="{{ request('search') }}"
                               placeholder="Company, contact or brand">

                    </div>


                    <div class="col-md-2">

                        <label class="form-label">
                            Status
                        </label>

                        <select name="status"
                                class="form-select">

                            <option value="">
                                All
                            </option>

                            @foreach([
                                'SCHEDULED',
                                'COMPLETED',
                                'CANCELLED',
                                'NO_SHOW',
                                'RESCHEDULED'
                            ] as $status)

                                <option value="{{ $status }}"
                                    @selected(request('status') === $status)>

                                    {{ str_replace('_', ' ', $status) }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-2">

                        <label class="form-label">
                            Interest
                        </label>

                        <select name="interest"
                                class="form-select">

                            <option value="">
                                All
                            </option>

                            @foreach([
                                'HIGH',
                                'MEDIUM',
                                'LOW',
                                'NOT_INTERESTED'
                            ] as $interest)

                                <option value="{{ $interest }}"
                                    @selected(request('interest') === $interest)>

                                    {{ str_replace('_', ' ', $interest) }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-2">

                        <label class="form-label">
                            Visit Date
                        </label>

                        <input type="date"
                               name="date"
                               class="form-control"
                               value="{{ request('date') }}">

                    </div>


                    <div class="col-md-2 d-flex align-items-end gap-2">

                        <button class="btn btn-primary">

                            <i class="ri-search-line"></i>

                        </button>

                        <a href="{{ route('admin.leasing.site-visits.index') }}"
                           class="btn btn-light border">

                            <i class="ri-refresh-line"></i>

                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- Register --}}
    <div class="card shadow-sm border-0">

        <div class="card-header bg-white py-3">

            <div class="d-flex justify-content-between">

                <strong>
                    Site Visit Register
                </strong>

                <span class="text-muted">
                    {{ $visits->total() }} visits
                </span>

            </div>

        </div>


        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">

                    <tr>

                        <th>#</th>

                        <th>Prospect</th>

                        <th>Unit</th>

                        <th>Visit Date</th>

                        <th>Interest</th>

                        <th>Follow-up</th>

                        <th>Officer</th>

                        <th>Status</th>

                        <th class="text-end">
                            Actions
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($visits as $visit)

                        <tr>

                            <td>
                                {{ $loop->iteration + (($visits->currentPage() - 1) * $visits->perPage()) }}
                            </td>


                            <td>

                                @if($visit->prospect)

                                    <div class="fw-semibold">

                                        {{ $visit->prospect->company_name }}

                                    </div>

                                    <small class="text-muted">

                                        {{ $visit->prospect->contact_name ?? '' }}

                                    </small>

                                @else

                                    -

                                @endif

                            </td>


                            <td>

                                @if($visit->unit)

                                    <strong>
                                        {{ $visit->unit->unit_no }}
                                    </strong>

                                    @if(isset($visit->unit->shop_name))

                                        <br>

                                        <small class="text-muted">
                                            {{ $visit->unit->shop_name }}
                                        </small>

                                    @endif

                                @else

                                    -

                                @endif

                            </td>


                            <td>

                                {{ optional($visit->visit_date)->format('d M Y, h:i A') }}

                            </td>


                            <td>

                                @php

                                    $interestClass = match($visit->interest) {

                                        'HIGH' => 'bg-success',
                                        'MEDIUM' => 'bg-warning text-dark',
                                        'LOW' => 'bg-secondary',
                                        'NOT_INTERESTED' => 'bg-danger',

                                        default => 'bg-light text-dark',

                                    };

                                @endphp

                                <span class="badge {{ $interestClass }}">

                                    {{ $visit->interest
                                        ? str_replace('_', ' ', $visit->interest)
                                        : 'Not Recorded' }}

                                </span>

                            </td>


                            <td>

                                {{ optional($visit->follow_up_date)
                                    ->format('d M Y, h:i A') ?? '-' }}

                            </td>


                            <td>

                                {{ optional($visit->assignedOfficer)->name ?? '-' }}

                            </td>


                            <td>

                                @php

                                    $statusClass = match($visit->status) {

                                        'SCHEDULED' => 'bg-primary',
                                        'COMPLETED' => 'bg-success',
                                        'CANCELLED' => 'bg-danger',
                                        'NO_SHOW' => 'bg-warning text-dark',
                                        'RESCHEDULED' => 'bg-info',

                                        default => 'bg-secondary',

                                    };

                                @endphp

                                <span class="badge {{ $statusClass }}">

                                    {{ str_replace('_', ' ', $visit->status) }}

                                </span>

                            </td>


                            <td class="text-end">

                                <div class="btn-group">

                                    <a href="{{ route('admin.leasing.site-visits.show', $visit) }}"
                                       class="btn btn-sm btn-outline-primary">

                                        <i class="ri-eye-line"></i>

                                    </a>


                                    <a href="{{ route('admin.leasing.site-visits.edit', $visit) }}"
                                       class="btn btn-sm btn-outline-warning">

                                        <i class="ri-edit-line"></i>

                                    </a>


                                    <form method="POST"
                                          action="{{ route('admin.leasing.site-visits.destroy', $visit) }}"
                                          onsubmit="return confirm('Delete this site visit?');">

                                        @csrf
                                        @method('DELETE')

                                        <button class="btn btn-sm btn-outline-danger">

                                            <i class="ri-delete-bin-line"></i>

                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="9"
                                class="text-center py-5">

                                <i class="ri-map-pin-user-line fs-1 text-muted"></i>

                                <h6 class="mt-3">
                                    No site visits found
                                </h6>

                                <a href="{{ route('admin.leasing.site-visits.create') }}"
                                   class="btn btn-primary">

                                    Schedule First Visit

                                </a>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        @if($visits->hasPages())

            <div class="card-footer bg-white">

                {{ $visits->links() }}

            </div>

        @endif

    </div>

</div>

@endsection