@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                Revenue Audit Log
            </h4>

            <p class="text-muted mb-0">
                Track important financial activities and changes.
            </p>

        </div>

    </div>


    {{-- FILTERS --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <form method="GET">

                <div class="row g-3 align-items-end">

                    <div class="col-lg-3">

                        <label class="form-label">
                            Search
                        </label>

                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            class="form-control"
                            placeholder="Description / Action / ID"
                        >

                    </div>


                    <div class="col-lg-2">

                        <label class="form-label">
                            Module
                        </label>

                        <select
                            name="module"
                            class="form-select"
                        >

                            <option value="">
                                All Modules
                            </option>

                            @foreach($modules as $module)

                                <option
                                    value="{{ $module }}"
                                    {{ request('module') === $module
                                        ? 'selected'
                                        : ''
                                    }}
                                >
                                    {{ $module }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-lg-2">

                        <label class="form-label">
                            Action
                        </label>

                        <select
                            name="action"
                            class="form-select"
                        >

                            <option value="">
                                All Actions
                            </option>

                            @foreach($actions as $action)

                                <option
                                    value="{{ $action }}"
                                    {{ request('action') === $action
                                        ? 'selected'
                                        : ''
                                    }}
                                >
                                    {{ $action }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-lg-2">

                        <label class="form-label">
                            From
                        </label>

                        <input
                            type="date"
                            name="from_date"
                            value="{{ request('from_date') }}"
                            class="form-control"
                        >

                    </div>


                    <div class="col-lg-1">

                        <button
                            type="submit"
                            class="btn btn-primary w-100"
                        >
                            Go
                        </button>

                    </div>


                    <div class="col-lg-2">

                        <a
                            href="{{ route(
                                'admin.revenue.audit.index'
                            ) }}"
                            class="btn btn-light border w-100"
                        >
                            Reset
                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- TABLE --}}

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white py-3">

            <h5 class="mb-0">
                Activity History
            </h5>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th>
                                Date & Time
                            </th>

                            <th>
                                User
                            </th>

                            <th>
                                Module
                            </th>

                            <th>
                                Action
                            </th>

                            <th>
                                Reference
                            </th>

                            <th>
                                Description
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($logs as $log)

                            <tr>

                                <td>

                                    {{ $log->created_at
                                        ? $log->created_at->format(
                                            'd M Y h:i A'
                                        )
                                        : '—'
                                    }}

                                </td>


                                <td>

                                    @if($log->user)

                                        {{ $log->user->name }}

                                    @else

                                        System

                                    @endif

                                </td>


                                <td>

                                    <span class="badge bg-light text-dark">

                                        {{ $log->module }}

                                    </span>

                                </td>


                                <td>

                                    <span class="badge bg-primary">

                                        {{ $log->action }}

                                    </span>

                                </td>


                                <td>

                                    @if($log->reference_type)

                                        {{ $log->reference_type }}

                                        #{{ $log->reference_id }}

                                    @else

                                        —

                                    @endif

                                </td>


                                <td>

                                    {{ $log->description ?? '—' }}

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="6"
                                    class="text-center py-5"
                                >

                                    <div class="text-muted">
                                        No audit records found.
                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        @if($logs->hasPages())

            <div class="card-footer bg-white">

                {{ $logs->links() }}

            </div>

        @endif

    </div>

</div>

@endsection