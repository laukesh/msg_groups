@extends('layouts.app')

@section('title', 'Leads & Prospects')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                <i class="ri-user-search-line me-1"></i>
                Leads & Prospects
            </h4>

            <p class="text-muted mb-0">
                CRM for potential tenants and occupiers.
            </p>
        </div>

        @can('prospects.create')
            <a href="{{ route('admin.leasing.prospects.create') }}"
               class="btn btn-primary">

                <i class="ri-add-line me-1"></i>
                Add Lead

            </a>
        @endcan

    </div>


    {{-- KPI --}}
    <div class="row g-3 mb-4">

        <div class="col-xl-2 col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <small class="text-muted">Total</small>
                    <h3 class="mb-0">{{ $total }}</h3>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <small class="text-muted">Active</small>
                    <h3 class="mb-0">{{ $activeCount }}</h3>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <small class="text-muted">Leads</small>
                    <h3 class="mb-0">{{ $leadCount }}</h3>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <small class="text-muted">Prospects</small>
                    <h3 class="mb-0">{{ $prospectCount }}</h3>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <small class="text-muted">Converted</small>
                    <h3 class="mb-0">{{ $convertedCount }}</h3>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <small class="text-muted">Follow-ups Due</small>
                    <h3 class="mb-0">{{ $followUpsDue }}</h3>
                </div>
            </div>
        </div>

    </div>


    {{-- Filters --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <form method="GET"
                  action="{{ route('admin.leasing.prospects.index') }}">

                <div class="row g-2">

                    <div class="col-md-3">
                        <input type="text"
                               name="search"
                               class="form-control"
                               value="{{ request('search') }}"
                               placeholder="Company, contact, brand...">
                    </div>

                    <div class="col-md-2">

                        <select name="status"
                                class="form-select">

                            <option value="">
                                All Status
                            </option>

                            @foreach([
                                'lead',
                                'prospect',
                                'qualified',
                                'site_visit',
                                'negotiation',
                                'converted',
                                'lost',
                                'inactive'
                            ] as $status)

                                <option value="{{ $status }}"
                                    @selected(request('status') == $status)>
                                    {{ ucfirst(str_replace('_', ' ', $status)) }}
                                </option>

                            @endforeach

                        </select>

                    </div>

                    <div class="col-md-2">

                        <select name="owner_id"
                                class="form-select">

                            <option value="">
                                All Owners
                            </option>

                            @foreach($owners as $owner)

                                <option value="{{ $owner->id }}"
                                    @selected(request('owner_id') == $owner->id)>
                                    {{ $owner->name }}
                                </option>

                            @endforeach

                        </select>

                    </div>

                    <div class="col-md-2">

                        <select name="priority"
                                class="form-select">

                            <option value="">
                                All Priority
                            </option>

                            @foreach(['low','medium','high','urgent'] as $priority)

                                <option value="{{ $priority }}"
                                    @selected(request('priority') == $priority)>
                                    {{ ucfirst($priority) }}
                                </option>

                            @endforeach

                        </select>

                    </div>

                    <div class="col-md-3">

                        <div class="d-flex gap-2">

                            <button class="btn btn-primary">
                                <i class="ri-search-line"></i>
                                Filter
                            </button>

                            <a href="{{ route('admin.leasing.prospects.index') }}"
                               class="btn btn-light">
                                Reset
                            </a>

                        </div>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- Table --}}
    <div class="card border-0 shadow-sm">

        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">

                    <tr>

                        <th>#</th>
                        <th>Company / Contact</th>
                        <th>Brand</th>
                        <th>Category</th>
                        <th>Area Need</th>
                        <th>Budget</th>
                        <th>Location</th>
                        <th>Status</th>
                        <th>Owner</th>
                        <th>Follow-up</th>
                        <th>Activities</th>
                        <th class="text-end">Action</th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($prospects as $prospect)

                        <tr>

                            <td>
                                {{ $prospects->firstItem() + $loop->index }}
                            </td>

                            <td>

                                <div class="fw-semibold">
                                    {{ $prospect->company_name ?: '-' }}
                                </div>

                                <small class="text-muted">
                                    {{ $prospect->contact_name }}
                                </small>

                                @if($prospect->phone)
                                    <br>
                                    <small>
                                        {{ $prospect->phone }}
                                    </small>
                                @endif

                            </td>

                            <td>
                                {{ $prospect->brand_name ?: '-' }}
                            </td>

                            <td>
                                {{ $prospect->category ?: '-' }}
                            </td>

                            <td>
                                @if($prospect->area_required)
                                    {{ number_format($prospect->area_required, 2) }}
                                    {{ $prospect->area_unit }}
                                @else
                                    -
                                @endif
                            </td>

                            <td>
                                {{ $prospect->budget_range }}
                            </td>

                            <td>
                                {{ $prospect->preferred_location ?: '-' }}
                            </td>

                            <td>

                                <span class="badge bg-primary-subtle text-primary">
                                    {{ $prospect->status_label }}
                                </span>

                            </td>

                            <td>
                                {{ $prospect->owner?->name ?: '-' }}
                            </td>

                            <td>

                                @if($prospect->next_follow_up)

                                    <span class="
                                        {{ $prospect->next_follow_up->isPast()
                                            ? 'text-danger fw-semibold'
                                            : 'text-muted' }}
                                    ">
                                        {{ $prospect->next_follow_up->format('d M Y') }}
                                    </span>

                                @else
                                    -
                                @endif

                            </td>

                            <td>
                                <span class="badge bg-light text-dark">
                                    {{ $prospect->activities_count }}
                                </span>
                            </td>

                            <td class="text-end">

                                <div class="btn-group">

                                    @can('prospects.view')

                                        <a href="{{ route(
                                            'admin.leasing.prospects.show',
                                            $prospect
                                        ) }}"
                                           class="btn btn-sm btn-success"
                                           title="View">

                                            <i class="ri-eye-line"></i>

                                        </a>

                                    @endcan

                                    @can('prospects.edit')

                                        <a href="{{ route(
                                            'admin.leasing.prospects.edit',
                                            $prospect
                                        ) }}"
                                           class="btn btn-sm btn-primary"
                                           title="Edit">

                                            <i class="ri-edit-line"></i>

                                        </a>

                                    @endcan

                                    @can('prospects.delete')

                                        <form method="POST"
                                              action="{{ route(
                                                'admin.leasing.prospects.destroy',
                                                $prospect
                                              ) }}"
                                              onsubmit="return confirm(
                                                'Delete this lead/prospect?'
                                              );">

                                            @csrf
                                            @method('DELETE')

                                            <button class="btn btn-sm btn-danger">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>

                                        </form>

                                    @endcan

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="12"
                                class="text-center py-5 text-muted">

                                <i class="ri-user-search-line fs-1 d-block mb-2"></i>

                                No leads or prospects found.

                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        <div class="card-footer bg-white">
            {{ $prospects->links() }}
        </div>

    </div>

</div>

@endsection