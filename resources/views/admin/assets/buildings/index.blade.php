@extends('layouts.app')

@section('title', 'Buildings')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">



        <div>
            <h4 class="mb-1"> <i class="fas fa-building me-1"></i> Buildings</h4>

            <div class="text-muted">
                Manage malls.
            </div>
        </div>

      
        @can('buildings.create')

            <a
                href="{{ route('admin.assets.buildings.create') }}"
                class="btn btn-primary"
            >
               <i class="fas fa-plus me-1"></i> Add Building
            </a>

        @endcan

   

    </div>


    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


    @if($errors->any())

        <div class="alert alert-danger">

            <ul class="mb-0">

                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

    @endif


    {{-- Search --}}

    <div class="card mb-4">

        <div class="card-body">

            <form
                method="GET"
                action="{{ route('admin.assets.buildings.index') }}"
            >

                <div class="row g-2">

                    <div class="col-md-5">

                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            placeholder="Search building..."
                            value="{{ request('search') }}"
                        >

                    </div>


                    <div class="col-md-3">

                        <select
                            name="mall_id"
                            class="form-select"
                        >

                            <option value="">
                                All Malls
                            </option>

                            @foreach($malls as $id => $name)

                                <option
                                    value="{{ $id }}"
                                    {{ request('mall_id') == $id
                                        ? 'selected'
                                        : '' }}
                                >
                                    {{ $name }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-2">

                        <select
                            name="status"
                            class="form-select"
                        >

                            <option value="">
                                All Status
                            </option>

                            <option
                                value="1"
                                {{ request('status') === '1'
                                    ? 'selected'
                                    : '' }}
                            >
                                Active
                            </option>

                            <option
                                value="0"
                                {{ request('status') === '0'
                                    ? 'selected'
                                    : '' }}
                            >
                                Inactive
                            </option>

                        </select>

                    </div>


                    <div class="col-md-2">

                        <button
                            type="submit"
                            class="btn btn-primary"
                        >
                            Search
                        </button>

                        <a
                            href="{{ route('admin.assets.buildings.index') }}"
                            class="btn btn-secondary"
                        >
                            Clear
                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- Building Table --}}

    <div class="card">

        <div class="card-header">

            <h5 class="mb-0">
              <i class="fas fa-building me-1"></i>  Building List
            </h5>

        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-bordered table-hover mb-0">

                    <thead class="table-light">

                        <tr>
                            <th>#</th>
                            <th>Mall</th>
                            <th>Code</th>
                            <th>Building Name</th>
                            <th>Floors</th>
                            <th>Units</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>

                    </thead>

                    <tbody>

                    @forelse($buildings as $building)

                        <tr>

                            <td>
                                {{ $building->id }}
                            </td>

                            <td>
                                {{ $building->mall->mall_name ?? '-' }}
                            </td>

                            <td>
                                {{ $building->building_code }}
                            </td>

                            <td>

                                <a
                                    href="{{ route(
                                        'admin.assets.buildings.show',
                                        $building->id
                                    ) }}"
                                >
                                    <strong>
                                        {{ $building->building_name }}
                                    </strong>
                                </a>

                            </td>

                            <td>
                                {{ $building->total_floors ?? 0 }}
                            </td>

                            <td>
                                {{ $building->total_units ?? 0 }}
                            </td>

                            <td>

                                @if($building->status == 1)

                                    <span class="badge bg-success">
                                        Active
                                    </span>

                                @else

                                    <span class="badge bg-secondary">
                                        Inactive
                                    </span>

                                @endif

                            </td>

                            <td>

                                <a
                                    href="{{ route(
                                        'admin.assets.buildings.show',
                                        $building->id
                                    ) }}"
                                    class="btn btn-sm btn-info"
                                >
                                    <i class="fas fa-eye me-1"></i>
                                </a>


                                @can('buildings.edit')

                                    <a
                                        href="{{ route(
                                            'admin.assets.buildings.edit',
                                            $building->id
                                        ) }}"
                                        class="btn btn-sm btn-primary"
                                    >
                                        <i class="fas fa-edit me-1"></i>
                                    </a>

                                @endcan


                                @can('buildings.delete')

                                    <form
                                        method="POST"
                                        action="{{ route(
                                            'admin.assets.buildings.destroy',
                                            $building->id
                                        ) }}"
                                        class="d-inline"
                                    >

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="btn btn-sm btn-danger"
                                            onclick="return confirm(
                                                'Are you sure you want to delete this building?'
                                            )"
                                        >
                                            <i class="fas fa-trash-alt me-1"></i> 
                                        </button>

                                    </form>

                                @endcan

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="8"
                                class="text-center py-4"
                            >
                                No buildings found.
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