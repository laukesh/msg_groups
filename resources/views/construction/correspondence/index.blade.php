@extends('layouts.app')

@section('title', 'Construction Correspondence')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                Construction Correspondence
            </h4>

            <div class="text-muted">
                {{ $project->project_number ?? $project->project_code }}
                -
                {{ $project->project_name }}
            </div>
        </div>

        <div class="d-flex gap-2">

            <a href="{{ route('admin.projects.construction.dashboard', $project) }}"
               class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i>
                Construction Dashboard
            </a>

            <a href="{{ route('admin.projects.construction.correspondence.create', $project) }}"
               class="btn btn-primary">
                <i class="bi bi-plus-lg"></i>
                Add Correspondence
            </a>

        </div>

    </div>


    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle me-1"></i>
            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>
        </div>
    @endif


    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-triangle me-1"></i>
            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>
        </div>
    @endif


    {{-- Summary Cards --}}
    <div class="row g-3 mb-4">

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>
                            <div class="text-muted small">
                                Total Correspondence
                            </div>

                            <h3 class="mb-0 mt-1">
                                {{ $correspondences->total() }}
                            </h3>
                        </div>

                        <div class="bg-primary-subtle text-primary rounded p-3">
                            <i class="bi bi-envelope fs-4"></i>
                        </div>

                    </div>

                </div>
            </div>
        </div>


        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>
                            <div class="text-muted small">
                                Action Required
                            </div>

                            <h3 class="mb-0 mt-1">
                                {{ $correspondences->where('status', 'Action Required')->count() }}
                            </h3>
                        </div>

                        <div class="bg-warning-subtle text-warning rounded p-3">
                            <i class="bi bi-exclamation-circle fs-4"></i>
                        </div>

                    </div>

                </div>
            </div>
        </div>


        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>
                            <div class="text-muted small">
                                High / Critical
                            </div>

                            <h3 class="mb-0 mt-1">
                                {{ $correspondences->whereIn('priority', ['High', 'Critical'])->count() }}
                            </h3>
                        </div>

                        <div class="bg-danger-subtle text-danger rounded p-3">
                            <i class="bi bi-flag fs-4"></i>
                        </div>

                    </div>

                </div>
            </div>
        </div>


        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>
                            <div class="text-muted small">
                                Response Pending
                            </div>

                            <h3 class="mb-0 mt-1">
                                {{ $correspondences->where('response_required', true)->whereNull('response_date')->count() }}
                            </h3>
                        </div>

                        <div class="bg-info-subtle text-info rounded p-3">
                            <i class="bi bi-reply fs-4"></i>
                        </div>

                    </div>

                </div>
            </div>
        </div>

    </div>


    {{-- Filters --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <div class="d-flex justify-content-between align-items-center">

                <h6 class="mb-0">
                    <i class="bi bi-funnel me-1"></i>
                    Filters
                </h6>

                <a href="{{ route('admin.projects.construction.correspondence.index', $project) }}"
                   class="btn btn-sm btn-outline-secondary">
                    Reset
                </a>

            </div>

        </div>


        <div class="card-body">

            <form method="GET"
                  action="{{ route('admin.projects.construction.correspondence.index', $project) }}">

                <div class="row g-3">

                    <div class="col-lg-3 col-md-6">

                        <label class="form-label">
                            Search
                        </label>

                        <input type="text"
                               name="search"
                               value="{{ request('search') }}"
                               class="form-control"
                               placeholder="Number, subject, reference...">

                    </div>


                    <div class="col-lg-2 col-md-6">

                        <label class="form-label">
                            Type
                        </label>

                        <select name="correspondence_type"
                                class="form-select">

                            <option value="">
                                All Types
                            </option>

                            @foreach([
                                'Incoming',
                                'Outgoing',
                                'Internal',
                                'Notice',
                                'Instruction',
                                'Letter',
                                'Email',
                                'Memo',
                                'Other'
                            ] as $type)

                                <option value="{{ $type }}"
                                    {{ request('correspondence_type') == $type ? 'selected' : '' }}>
                                    {{ $type }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-lg-2 col-md-6">

                        <label class="form-label">
                            Status
                        </label>

                        <select name="status"
                                class="form-select">

                            <option value="">
                                All Statuses
                            </option>

                            @foreach([
                                'Draft',
                                'Registered',
                                'Under Review',
                                'Action Required',
                                'Responded',
                                'Closed',
                                'Archived'
                            ] as $status)

                                <option value="{{ $status }}"
                                    {{ request('status') == $status ? 'selected' : '' }}>
                                    {{ $status }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-lg-2 col-md-6">

                        <label class="form-label">
                            Priority
                        </label>

                        <select name="priority"
                                class="form-select">

                            <option value="">
                                All Priorities
                            </option>

                            @foreach([
                                'Low',
                                'Medium',
                                'High',
                                'Critical'
                            ] as $priority)

                                <option value="{{ $priority }}"
                                    {{ request('priority') == $priority ? 'selected' : '' }}>
                                    {{ $priority }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-lg-3 col-md-6 d-flex align-items-end">

                        <button type="submit"
                                class="btn btn-primary w-100">
                            <i class="bi bi-search me-1"></i>
                            Apply Filters
                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- Correspondence Table --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white">

            <div class="d-flex justify-content-between align-items-center">

                <div>
                    <h6 class="mb-0">
                        Correspondence Register
                    </h6>

                    <small class="text-muted">
                        Project communication and correspondence records
                    </small>
                </div>

                <span class="badge bg-light text-dark">
                    {{ $correspondences->total() }} Records
                </span>

            </div>

        </div>


        <div class="card-body p-0">

            @if($correspondences->count())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th class="ps-3">
                                    Correspondence
                                </th>

                                <th>
                                    Date
                                </th>

                                <th>
                                    Type
                                </th>

                                <th>
                                    Subject
                                </th>

                                <th>
                                    From / To
                                </th>

                                <th>
                                    Priority
                                </th>

                                <th>
                                    Status
                                </th>

                                <th>
                                    Response
                                </th>

                                <th class="text-end pe-3">
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($correspondences as $correspondence)

                                <tr>

                                    {{-- Number --}}
                                    <td class="ps-3">

                                        <a href="{{ route(
                                            'admin.projects.construction.correspondence.show',
                                            [$project, $correspondence]
                                        ) }}"
                                           class="fw-semibold text-decoration-none">

                                            {{ $correspondence->correspondence_number }}

                                        </a>

                                        @if($correspondence->reference_number)

                                            <div class="small text-muted">
                                                Ref:
                                                {{ $correspondence->reference_number }}
                                            </div>

                                        @endif

                                    </td>


                                    {{-- Date --}}
                                    <td>

                                        <div>
                                            {{ optional($correspondence->correspondence_date)->format('d M Y') }}
                                        </div>

                                        @if($correspondence->received_date)

                                            <div class="small text-muted">
                                                Received:
                                                {{ optional($correspondence->received_date)->format('d M Y') }}
                                            </div>

                                        @elseif($correspondence->sent_date)

                                            <div class="small text-muted">
                                                Sent:
                                                {{ optional($correspondence->sent_date)->format('d M Y') }}
                                            </div>

                                        @endif

                                    </td>


                                    {{-- Type --}}
                                    <td>

                                        @php

                                            $typeClass = match($correspondence->correspondence_type) {
                                                'Incoming' => 'bg-info-subtle text-info',
                                                'Outgoing' => 'bg-primary-subtle text-primary',
                                                'Internal' => 'bg-secondary-subtle text-secondary',
                                                'Notice' => 'bg-danger-subtle text-danger',
                                                'Instruction' => 'bg-warning-subtle text-warning',
                                                'Letter' => 'bg-light text-dark',
                                                'Email' => 'bg-success-subtle text-success',
                                                'Memo' => 'bg-dark-subtle text-dark',
                                                default => 'bg-light text-dark',
                                            };

                                        @endphp

                                        <span class="badge {{ $typeClass }}">
                                            {{ $correspondence->correspondence_type }}
                                        </span>

                                    </td>


                                    {{-- Subject --}}
                                    <td>

                                        <div class="fw-semibold">
                                            {{ \Illuminate\Support\Str::limit(
                                                $correspondence->subject,
                                                55
                                            ) }}
                                        </div>

                                        @if($correspondence->communication_method)

                                            <div class="small text-muted">

                                                <i class="bi bi-chat-left-text me-1"></i>

                                                {{ $correspondence->communication_method }}

                                            </div>

                                        @endif

                                    </td>


                                    {{-- From / To --}}
                                    <td>

                                        @if($correspondence->sender_name)

                                            <div>
                                                <span class="text-muted small">
                                                    From:
                                                </span>

                                                {{ $correspondence->sender_name }}
                                            </div>

                                        @endif


                                        @if($correspondence->receiver_name)

                                            <div>
                                                <span class="text-muted small">
                                                    To:
                                                </span>

                                                {{ $correspondence->receiver_name }}
                                            </div>

                                        @endif

                                    </td>


                                    {{-- Priority --}}
                                    <td>

                                        @php

                                            $priorityClass = match($correspondence->priority) {
                                                'Low' => 'bg-secondary-subtle text-secondary',
                                                'Medium' => 'bg-info-subtle text-info',
                                                'High' => 'bg-warning-subtle text-warning',
                                                'Critical' => 'bg-danger-subtle text-danger',
                                                default => 'bg-light text-dark',
                                            };

                                        @endphp

                                        <span class="badge {{ $priorityClass }}">
                                            {{ $correspondence->priority }}
                                        </span>

                                    </td>


                                    {{-- Status --}}
                                    <td>

                                        @php

                                            $statusClass = match($correspondence->status) {
                                                'Draft' => 'bg-secondary-subtle text-secondary',
                                                'Registered' => 'bg-info-subtle text-info',
                                                'Under Review' => 'bg-primary-subtle text-primary',
                                                'Action Required' => 'bg-warning-subtle text-warning',
                                                'Responded' => 'bg-success-subtle text-success',
                                                'Closed' => 'bg-dark-subtle text-dark',
                                                'Archived' => 'bg-light text-dark',
                                                default => 'bg-light text-dark',
                                            };

                                        @endphp

                                        <span class="badge {{ $statusClass }}">
                                            {{ $correspondence->status }}
                                        </span>

                                    </td>


                                    {{-- Response --}}
                                    <td>

                                        @if($correspondence->response_required)

                                            @if($correspondence->response_date)

                                                <span class="badge bg-success-subtle text-success">
                                                    <i class="bi bi-check-circle me-1"></i>
                                                    Responded
                                                </span>

                                                <div class="small text-muted mt-1">
                                                    {{ optional($correspondence->response_date)->format('d M Y') }}
                                                </div>

                                            @elseif(
                                                $correspondence->response_due_date &&
                                                $correspondence->response_due_date->isPast()
                                            )

                                                <span class="badge bg-danger-subtle text-danger">
                                                    <i class="bi bi-exclamation-circle me-1"></i>
                                                    Overdue
                                                </span>

                                                <div class="small text-danger mt-1">
                                                    Due:
                                                    {{ optional($correspondence->response_due_date)->format('d M Y') }}
                                                </div>

                                            @else

                                                <span class="badge bg-warning-subtle text-warning">
                                                    Pending
                                                </span>

                                                @if($correspondence->response_due_date)

                                                    <div class="small text-muted mt-1">
                                                        Due:
                                                        {{ optional($correspondence->response_due_date)->format('d M Y') }}
                                                    </div>

                                                @endif

                                            @endif

                                        @else

                                            <span class="text-muted">
                                                —
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Action --}}
                                    <td class="text-end pe-3">

                                        <div class="dropdown">

                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle"
                                                    type="button"
                                                    data-bs-toggle="dropdown">

                                                Actions

                                            </button>


                                            <ul class="dropdown-menu dropdown-menu-end">

                                                <li>

                                                    <a class="dropdown-item"
                                                       href="{{ route(
                                                           'admin.projects.construction.correspondence.show',
                                                           [$project, $correspondence]
                                                       ) }}">

                                                        <i class="bi bi-eye me-2"></i>
                                                        View

                                                    </a>

                                                </li>


                                                @if(in_array($correspondence->status, ['Draft', 'Registered']))

                                                    <li>

                                                        <a class="dropdown-item"
                                                           href="{{ route(
                                                               'admin.projects.construction.correspondence.edit',
                                                               [$project, $correspondence]
                                                           ) }}">

                                                            <i class="bi bi-pencil me-2"></i>
                                                            Edit

                                                        </a>

                                                    </li>

                                                @endif


                                                <li>

                                                    <a class="dropdown-item"
                                                       href="{{ route(
                                                           'admin.projects.construction.correspondence.documents.index',
                                                           [$project, $correspondence]
                                                       ) }}">

                                                        <i class="bi bi-paperclip me-2"></i>
                                                        Documents

                                                    </a>

                                                </li>


                                                @if($correspondence->status === 'Draft')

                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>

                                                    <li>

                                                        <form method="POST"
                                                              action="{{ route(
                                                                  'admin.projects.construction.correspondence.destroy',
                                                                  [$project, $correspondence]
                                                              ) }}"
                                                              onsubmit="return confirm('Are you sure you want to delete this correspondence?');">

                                                            @csrf
                                                            @method('DELETE')

                                                            <button type="submit"
                                                                    class="dropdown-item text-danger">

                                                                <i class="bi bi-trash me-2"></i>
                                                                Delete

                                                            </button>

                                                        </form>

                                                    </li>

                                                @endif

                                            </ul>

                                        </div>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>


                {{-- Pagination --}}
                <div class="card-footer bg-white">

                    <div class="d-flex justify-content-between align-items-center">

                        <div class="text-muted small">

                            Showing
                            {{ $correspondences->firstItem() ?? 0 }}
                            to
                            {{ $correspondences->lastItem() ?? 0 }}
                            of
                            {{ $correspondences->total() }}
                            records

                        </div>

                        <div>

                            {{ $correspondences->withQueryString()->links() }}

                        </div>

                    </div>

                </div>

            @else

                <div class="text-center py-5">

                    <div class="mb-3">

                        <i class="bi bi-envelope-open fs-1 text-muted"></i>

                    </div>

                    <h6>
                        No Correspondence Found
                    </h6>

                    <p class="text-muted mb-3">
                        No correspondence records are available for this project.
                    </p>

                    <a href="{{ route(
                        'admin.projects.construction.correspondence.create',
                        $project
                    ) }}"
                       class="btn btn-primary">

                        <i class="bi bi-plus-lg me-1"></i>
                        Add Correspondence

                    </a>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection