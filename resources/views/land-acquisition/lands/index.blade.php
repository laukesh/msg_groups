@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="mb-1">
                Land Register
            </h3>

            <p class="text-muted mb-0">
                Manage land acquisition records
            </p>
        </div>
        <div class="d-flex gap-2">

            <a href="{{ route('admin.land.lands.create') }}"
               class="btn btn-primary">

                + Add Land

            </a>
            <a href="{{ route('admin.land.opportunities.index') }}"
               class="btn btn-outline-secondary">

                Land Opportunities

            </a>
            
        </div>

        

    </div>


    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


    {{-- Search --}}

    <form method="GET"
          action="{{ route('admin.land.lands.index') }}"
          class="card mb-4">

        <div class="card-body">

            <div class="row">

                <div class="col-md-5">

                    <label class="form-label">
                        Search
                    </label>

                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        class="form-control"
                        placeholder="Land code, name, city..."
                    >

                </div>


                <div class="col-md-3">

                    <label class="form-label">
                        Acquisition Status
                    </label>

                    <select name="status"
                            class="form-select">

                        <option value="">
                            All
                        </option>

                        <option value="Under Evaluation"
                            @selected(request('status') === 'Under Evaluation')>
                            Under Evaluation
                        </option>

                        <option value="Approved"
                            @selected(request('status') === 'Approved')>
                            Approved
                        </option>

                        <option value="Acquired"
                            @selected(request('status') === 'Acquired')>
                            Acquired
                        </option>

                        <option value="Rejected"
                            @selected(request('status') === 'Rejected')>
                            Rejected
                        </option>

                    </select>

                </div>


                <div class="col-md-4 d-flex align-items-end">

                    <button class="btn btn-dark me-2">
                        Search
                    </button>

                    <a href="{{ route('admin.land.lands.index') }}"
                       class="btn btn-outline-secondary">
                        Reset
                    </a>

                </div>

            </div>

        </div>

    </form>


    {{-- Land Table --}}

    <div class="card">

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-bordered table-hover mb-0 align-middle">

                    <thead class="table-light">

                        <tr>

                            <th>Land Code</th>

                            <th>Land Name</th>

                            <th>Area</th>

                            <th>Status</th>

                            <th>Acquisition Date</th>

                            <th width="150">
                                Action
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                    @forelse($lands as $land)

                        <tr>

                            <td>
                                <strong>
                                    {{ $land->land_code }}
                                </strong>
                            </td>

                            <td>
                                {{ $land->land_name }}
                            </td>

                            <td>

                                {{ $land->total_area ?? '-' }}

                                {{ $land->area_unit ?? '' }}

                            </td>

                            <td>

                                <span class="badge bg-secondary">

                                    {{ $land->acquisition_status }}

                                </span>

                            </td>

                            <td>

                                {{ $land->acquisition_date
                                    ? $land->acquisition_date->format('d-m-Y')
                                    : '-' }}

                            </td>

                            <td>
                                <div class="d-flex gap-1">
                                    <a
                                        href="{{ route(
                                            'admin.land.lands.feasibility-assessments.index',
                                            $land
                                        ) }}"
                                        class="btn btn-sm btn-outline-primary"
                                    >
                                        Feasibility
                                    </a>

                                    <a
                                        href="{{ route('admin.land.lands.show', $land) }}"
                                        class="btn btn-sm btn-outline-primary">
                                        View
                                    </a>

                                    <a
                                        href="{{ route('admin.land.lands.edit', $land) }}"
                                        class="btn btn-sm btn-outline-secondary">
                                        Edit
                                    </a>
                                    
                                </div>
                                

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="7"
                                class="text-center py-5">

                                No land records found.

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        <div class="card-footer">

            {{ $lands->links() }}

        </div>

    </div>

</div>

@endsection