@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">Bidder Management</h4>
            <div class="text-muted">
                Manage procurement bidders and vendor profiles.
            </div>
        </div>

        <a href="{{ route('admin.procurement.bidders.create') }}"
           class="btn btn-primary">
            + Add Bidder
        </a>

    </div>


    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif


    <div class="card mb-4">

        <div class="card-header">
            <strong>Search & Filter</strong>
        </div>

        <div class="card-body">

            <form method="GET"
                  action="{{ route('admin.procurement.bidders.index') }}">

                <div class="row g-3">

                    <div class="col-md-6">

                        <label class="form-label">
                            Search
                        </label>

                        <input type="text"
                               name="search"
                               class="form-control"
                               value="{{ request('search') }}"
                               placeholder="Bidder code, company, GST, PAN or email">

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Status
                        </label>

                        <select name="status"
                                class="form-select">

                            <option value="">
                                All Statuses
                            </option>

                            @foreach([
                                'Active',
                                'Inactive',
                                'Blacklisted',
                            ] as $status)

                                <option value="{{ $status }}"
                                    @selected(
                                        request('status') === $status
                                    )>
                                    {{ $status }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-2 d-flex align-items-end">

                        <div class="d-flex gap-2 w-100">

                            <button type="submit"
                                    class="btn btn-primary flex-grow-1">
                                Search
                            </button>

                            <a href="{{ route(
                                'admin.procurement.bidders.index'
                            ) }}"
                               class="btn btn-outline-secondary">
                                Reset
                            </a>

                        </div>

                    </div>

                </div>

            </form>

        </div>

    </div>


    <div class="card">

        <div class="card-header">
            <strong>Bidders</strong>
        </div>

        <div class="card-body p-0">

            @if($bidders->count())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                        <tr>
                            <th>#</th>
                            <th>Bidder Code</th>
                            <th>Company Name</th>
                            <th>Contact Person</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>

                        </thead>

                        <tbody>

                        @foreach($bidders as $bidder)

                            @php
                                $statusClass = match(
                                    $bidder->status
                                ) {
                                    'Active' => 'bg-success',
                                    'Inactive' => 'bg-secondary',
                                    'Blacklisted' => 'bg-danger',
                                    default => 'bg-secondary',
                                };
                            @endphp

                            <tr>

                                <td>
                                    {{
                                        $bidders->firstItem()
                                        + $loop->index
                                    }}
                                </td>

                                <td>
                                    <a href="{{ route(
                                        'admin.procurement.bidders.show',
                                        $bidder
                                    ) }}"
                                       class="fw-semibold text-decoration-none">
                                        {{ $bidder->bidder_code }}
                                    </a>
                                </td>

                                <td>
                                    {{ $bidder->company_name }}
                                </td>

                                <td>
                                    {{ $bidder->contact_person ?: '—' }}
                                </td>

                                <td>
                                    {{ $bidder->email ?: '—' }}
                                </td>

                                <td>
                                    {{ $bidder->phone ?: '—' }}
                                </td>

                                <td>
                                    <span class="badge {{ $statusClass }}">
                                        {{ $bidder->status }}
                                    </span>
                                </td>

                                <td class="text-end">

                                    <div class="d-inline-flex gap-1">

                                        <a href="{{ route(
                                            'admin.procurement.bidders.show',
                                            $bidder
                                        ) }}"
                                           class="btn btn-sm btn-outline-primary">
                                            View
                                        </a>

                                        <a href="{{ route(
                                            'admin.procurement.bidders.edit',
                                            $bidder
                                        ) }}"
                                           class="btn btn-sm btn-outline-secondary">
                                            Edit
                                        </a>

                                        @if(!$bidder->tenderBidders()->exists())

                                            <form method="POST"
                                                  action="{{ route(
                                                      'admin.procurement.bidders.destroy',
                                                      $bidder
                                                  ) }}">

                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        class="btn btn-sm btn-outline-danger"
                                                        onclick="return confirm('Delete this bidder?')">
                                                    Delete
                                                </button>

                                            </form>

                                        @endif

                                    </div>

                                </td>

                            </tr>

                        @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="text-center py-5">

                    <div class="text-muted mb-3">
                        No bidders found.
                    </div>

                    <a href="{{ route(
                        'admin.procurement.bidders.create'
                    ) }}"
                       class="btn btn-primary">
                        Add First Bidder
                    </a>

                </div>

            @endif

        </div>


        @if($bidders->hasPages())

            <div class="card-footer">
                {{ $bidders->links() }}
            </div>

        @endif

    </div>

</div>

@endsection