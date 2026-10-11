@extends('layouts.app')

@section('title', 'Leads & Prospects')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                <i class="ri-user-search-line me-1"></i>
                Leads & Prospects
            </h4>

            <p class="text-muted mb-0">
                Manage potential tenants and occupiers.
            </p>
        </div>

        <a href="{{ route('admin.leasing.leads.create') }}"
           class="btn btn-primary">

            <i class="ri-add-line me-1"></i>
            Add Lead

        </a>

    </div>


    {{-- Success Message --}}
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


    {{-- Validation Errors --}}
    @if($errors->any())

        <div class="alert alert-danger">

            <strong>Please correct the following:</strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- Search / Filter --}}
    <div class="card shadow-sm border-0 mb-4">

        <div class="card-body">

            <form method="GET"
                  action="{{ route('admin.leasing.leads.index') }}">

                <div class="row g-3">

                    <div class="col-md-5">

                        <label class="form-label">
                            Search
                        </label>

                        <input type="text"
                               name="search"
                               class="form-control"
                               value="{{ request('search') }}"
                               placeholder="Company, contact, brand, email or phone">

                    </div>


                    <div class="col-md-3">

                        <label class="form-label">
                            Status
                        </label>

                        <select name="status"
                                class="form-select">

                            <option value="">
                                All Statuses
                            </option>

                            @foreach([
                                'NEW',
                                'CONTACTED',
                                'QUALIFIED',
                                'PROPOSAL',
                                'NEGOTIATION',
                                'CONVERTED',
                                'LOST',
                                'ON_HOLD'
                            ] as $status)

                                <option value="{{ $status }}"
                                    {{ request('status') == $status ? 'selected' : '' }}>

                                    {{ str_replace('_', ' ', $status) }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-4 d-flex align-items-end gap-2">

                        <button class="btn btn-primary">

                            <i class="ri-search-line me-1"></i>
                            Search

                        </button>

                        <a href="{{ route('admin.leasing.leads.index') }}"
                           class="btn btn-light border">

                            <i class="ri-refresh-line me-1"></i>
                            Reset

                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- Lead Table --}}
    <div class="card shadow-sm border-0">

        <div class="card-header bg-white py-3">

            <div class="d-flex justify-content-between">

                <strong>
                    Lead Register
                </strong>

                <span class="text-muted">
                    {{ $leads->total() }} records
                </span>

            </div>

        </div>


        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">

                    <tr>

                        <th>#</th>

                        <th>Company</th>

                        <th>Contact</th>

                        <th>Brand</th>

                        <th>Category</th>

                        <th>Area Need</th>

                        <th>Budget</th>

                        <th>Status</th>

                        <th>Source</th>

                        <th class="text-end">
                            Actions
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($leads as $lead)

                        <tr>

                            <td>
                                {{ $loop->iteration + (($leads->currentPage() - 1) * $leads->perPage()) }}
                            </td>


                            <td>

                                <div class="fw-semibold">

                                    {{ $lead->company_name }}

                                </div>

                                @if($lead->email)

                                    <small class="text-muted">

                                        {{ $lead->email }}

                                    </small>

                                @endif

                            </td>


                            <td>

                                {{ $lead->contact_name ?? '-' }}

                                @if($lead->phone)

                                    <br>

                                    <small class="text-muted">

                                        {{ $lead->phone }}

                                    </small>

                                @endif

                            </td>


                            <td>
                                {{ $lead->brand_name ?? '-' }}
                            </td>


                            <td>
                                {{ $lead->category ?? '-' }}
                            </td>


                            <td>

                                @if($lead->area_need !== null)

                                    {{ number_format((float) $lead->area_need, 2) }}

                                @else

                                    -

                                @endif

                            </td>


                            <td>

                                @if($lead->budget !== null)

                                    {{ number_format((float) $lead->budget, 2) }}

                                @else

                                    -

                                @endif

                            </td>


                            <td>

                                @php

                                    $statusClass = match($lead->status) {

                                        'NEW' => 'bg-primary',
                                        'CONTACTED' => 'bg-info',
                                        'QUALIFIED' => 'bg-success',
                                        'PROPOSAL' => 'bg-warning text-dark',
                                        'NEGOTIATION' => 'bg-warning text-dark',
                                        'CONVERTED' => 'bg-success',
                                        'LOST' => 'bg-danger',
                                        'ON_HOLD' => 'bg-secondary',

                                        default => 'bg-secondary',

                                    };

                                @endphp

                                <span class="badge {{ $statusClass }}">

                                    {{ str_replace('_', ' ', $lead->status) }}

                                </span>

                            </td>


                            <td>
                                {{ $lead->source ?? '-' }}
                            </td>


                            <td class="text-end">

                                <div class="btn-group">

                                    <a href="{{ route('admin.leasing.leads.show', $lead) }}"
                                       class="btn btn-sm btn-outline-primary"
                                       title="View">

                                        <i class="ri-eye-line"></i>

                                    </a>


                                    <a href="{{ route('admin.leasing.leads.edit', $lead) }}"
                                       class="btn btn-sm btn-outline-warning"
                                       title="Edit">

                                        <i class="ri-edit-line"></i>

                                    </a>


                                    <form method="POST"
                                          action="{{ route('admin.leasing.leads.destroy', $lead) }}"
                                          onsubmit="return confirm('Are you sure you want to delete this lead?');">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn btn-sm btn-outline-danger"
                                                title="Delete">

                                            <i class="ri-delete-bin-line"></i>

                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="10"
                                class="text-center py-5">

                                <i class="ri-user-search-line fs-1 text-muted"></i>

                                <h6 class="mt-3">
                                    No leads found
                                </h6>

                                <p class="text-muted">
                                    Create your first leasing lead.
                                </p>

                                <a href="{{ route('admin.leasing.leads.create') }}"
                                   class="btn btn-primary">

                                    Add Lead

                                </a>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        @if($leads->hasPages())

            <div class="card-footer bg-white">

                {{ $leads->links() }}

            </div>

        @endif

    </div>

</div>

@endsection