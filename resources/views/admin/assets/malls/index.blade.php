@extends('layouts.app')

@section('title', 'Malls')

@section('content')

<div class="container-fluid">

   
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">Malls</h4>

            <div class="text-muted">
                Manage malls.
            </div>
        </div>

        <a href="{{ route('admin.assets.malls.create') }}" class="btn btn-primary">

            <i class="fas fa-plus me-1"></i>
            Add Mall

        </a>

    </div>


    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
                aria-label="Close">
            </button>
        </div>
    @endif


    {{-- Error Message --}}
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
                aria-label="Close">
            </button>
        </div>
    @endif


    {{-- Search --}}
  <div class="card mb-4">

        <div class="card-body">

            <form
                method="GET"
                action="{{ route('admin.assets.malls.index') }}"
            >
                <div class="row g-2">

                    <div class="col-md-8">
                        <label for="search" class="form-label">
                            Search Mall
                        </label>

                        <input
                            type="text"
                            name="search"
                            id="search"
                            class="form-control"
                            placeholder="Search by code, name, city, type..."
                            value="{{ request('search') }}"
                        >
                    </div>

                    <div class="col-md-4 d-flex align-items-end gap-2">

                        <button
                            type="submit"
                            class="btn btn-primary"
                        >
                            Search
                        </button>

                        @if(request('search'))
                            <a
                                href="{{ route('admin.assets.malls.index') }}"
                                class="btn btn-secondary"
                            >
                                Clear
                            </a>
                        @endif

                    </div>

                </div>
            </form>

        </div>
    </div>


    {{-- Mall Table --}}
  <div class="card">

        <div class="card-header">

            <div class="d-flex justify-content-between align-items-center">

                <h5 class="mb-0">

                    <i class="fas fa-file-contract me-1"></i>

                   Malls List

                </h5>


                <span class="badge bg-primary">

                    {{ $malls->total() }} Total

                </span>

            </div>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>
                            <th width="60">#</th>
                            <th>Code</th>
                            <th>Name</th>
                            <th>Type</th>
                            <th>City</th>
                            <th>Country</th>
                            <th>Contact</th>
                            <th>Status</th>
                            <th width="180">Actions</th>
                        </tr>

                    </thead>

                    <tbody>

                        @forelse($malls as $mall)

                            <tr>

                                {{-- ID --}}
                                <td>
                                    {{ $mall->id }}
                                </td>


                                {{-- Code --}}
                                <td>
                                    <strong>
                                        {{ $mall->mall_code }}
                                    </strong>
                                </td>


                                {{-- Name --}}
                                <td>
                                    <a
                                        href="{{ route('admin.assets.malls.show', $mall->id) }}"
                                        class="text-decoration-none"
                                    >
                                        {{ $mall->mall_name }}
                                    </a>
                                </td>


                                {{-- Type --}}
                                <td>
                                    {{ $mall->mall_type ?? '-' }}
                                </td>


                                {{-- City --}}
                                <td>
                                    {{ $mall->city ?? '-' }}
                                </td>


                                {{-- Country --}}
                                <td>
                                    {{ $mall->country ?? '-' }}
                                </td>


                                {{-- Contact --}}
                                <td>
                                    {{ $mall->contact_number ?? '-' }}
                                </td>


                                {{-- Status --}}
                               <td>
                                    <span class="badge {{ $mall->status ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $mall->status ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>


                                {{-- Actions --}}
                                <td>

                                    <div class="d-flex gap-1">

                                        {{-- View --}}
                                        <a
                                            href="{{ route('admin.assets.malls.show', $mall->id) }}"
                                            class="btn btn-sm btn-info"
                                        >
                                            <i class="fas fa-eye"></i>
                                        </a>


                                        {{-- Edit --}}
                                        <a
                                            href="{{ route('admin.assets.malls.edit', $mall->id) }}"
                                            class="btn btn-sm btn-primary"
                                        >
                                            <i class="fas fa-edit"></i>
                                        </a>


                                        {{-- Delete --}}
                                        <form
                                            method="POST"
                                            action="{{ route('admin.assets.malls.destroy', $mall->id) }}"
                                            class="d-inline"
                                        >

                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="btn btn-sm btn-danger"
                                                onclick="return confirm('Are you sure you want to delete this mall?')"
                                            >
                                                <i class="fas fa-trash"></i> 
                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td
                                    colspan="9"
                                    class="text-center py-4"
                                >
                                    <div class="text-muted">
                                        No malls found.
                                    </div>

                                    @if(request('search'))
                                        <a
                                            href="{{ route('admin.malls.index') }}"
                                            class="btn btn-sm btn-secondary mt-2"
                                        >
                                            Clear Search
                                        </a>
                                    @endif
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