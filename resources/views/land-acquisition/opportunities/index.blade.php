@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="mb-1">
                Land Opportunities
            </h3>

            <p class="text-muted mb-0">
                Manage potential land acquisition opportunities
            </p>
        </div>

        <div class="d-flex gap-2">

            <a href="{{ route('admin.land.lands.index') }}"
               class="btn btn-secondary">

                Back to Land Acquisition

            </a>

            <a href="{{ route('admin.land.opportunities.create') }}"
               class="btn btn-primary">

                + Add Opportunity

            </a>
            
        </div>

        

    </div>


    {{-- Success Message --}}

    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


    {{-- Error Message --}}

    @if(session('error'))

        <div class="alert alert-danger">
            {{ session('error') }}
        </div>

    @endif


    {{-- Search --}}

    <form method="GET"
          action="{{ route('admin.land.opportunities.index') }}"
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
                        placeholder="Opportunity no, name, location..."
                    >

                </div>


                <div class="col-md-3">

                    <label class="form-label">
                        Status
                    </label>

                    <select
                        name="status"
                        class="form-select">

                        <option value="">
                            All Status
                        </option>

                        <option value="New"
                            @selected(request('status') === 'New')>
                            New
                        </option>

                        <option value="Under Evaluation"
                            @selected(request('status') === 'Under Evaluation')>
                            Under Evaluation
                        </option>

                        <option value="Approved"
                            @selected(request('status') === 'Approved')>
                            Approved
                        </option>

                        <option value="Rejected"
                            @selected(request('status') === 'Rejected')>
                            Rejected
                        </option>

                        <option value="On Hold"
                            @selected(request('status') === 'On Hold')>
                            On Hold
                        </option>

                    </select>

                </div>


                <div class="col-md-4 d-flex align-items-end">

                    <button
                        type="submit"
                        class="btn btn-dark me-2">

                        Search

                    </button>

                    <a
                        href="{{ route('admin.land.opportunities.index') }}"
                        class="btn btn-outline-secondary">

                        Reset

                    </a>

                </div>

            </div>

        </div>

    </form>


    {{-- Table --}}

    <div class="card">

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover mb-0">

                    <thead>

                        <tr>

                            <th>
                                Opportunity No.
                            </th>

                            <th>
                                Opportunity Name
                            </th>

                            <th>
                                Location
                            </th>

                            <th>
                                Estimated Area
                            </th>

                            <th>
                                Estimated Cost
                            </th>

                            <th>
                                Status
                            </th>

                            <th width="160">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                    @forelse($opportunities as $opportunity)

                        <tr>

                            <td>

                                <strong>
                                    {{ $opportunity->opportunity_no }}
                                </strong>

                            </td>


                            <td>

                                {{ $opportunity->opportunity_name }}

                            </td>


                            <td>

                                {{ $opportunity->location_text ?? '-' }}

                            </td>


                            <td>

                                {{ $opportunity->estimated_area ?? '-' }}

                                {{ $opportunity->area_unit ?? '' }}

                            </td>


                            <td>

                                @if($opportunity->estimated_acquisition_cost)

                                    {{ $opportunity->currency ?? 'USD' }}
                                    {{ number_format(
                                        $opportunity->estimated_acquisition_cost,
                                        2
                                    ) }}

                                @else

                                    -

                                @endif

                            </td>


                            <td>

                                <span class="badge bg-secondary">

                                    {{ $opportunity->status }}

                                </span>

                            </td>


                            <td>

                                <a
                                    href="{{ route(
                                        'admin.land.opportunities.show',
                                        $opportunity
                                    ) }}"
                                    class="btn btn-sm btn-outline-primary">

                                    View

                                </a>


                                <a
                                    href="{{ route(
                                        'admin.land.opportunities.edit',
                                        $opportunity
                                    ) }}"
                                    class="btn btn-sm btn-outline-secondary">

                                    Edit

                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="7"
                                class="text-center py-5">

                                No land opportunities found.

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        <div class="card-footer">

            {{ $opportunities->links() }}

        </div>

    </div>

</div>

@endsection