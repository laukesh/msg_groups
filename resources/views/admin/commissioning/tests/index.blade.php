@extends('layouts.app')

@section('title', 'Commissioning Tests')

@section('content')

<div class="container-fluid">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                Commissioning Test Executions
            </h4>

            <div class="text-muted">
                {{ $project->project_code ?? '' }}
                -
                {{ $project->project_name ?? '' }}
            </div>

        </div>

        <div class="d-flex gap-2">

            <a
                href="{{ route('admin.projects.commissioning.index', $project) }}"
                class="btn btn-outline-secondary"
            >
                <i class="bi bi-arrow-left me-1"></i>
                Commissioning
            </a>

            <a
                href="{{ route('admin.projects.commissioning.tests.create', $project) }}"
                class="btn btn-primary"
            >
                <i class="bi bi-plus-lg me-1"></i>
                Record Test
            </a>

        </div>

    </div>


    {{-- FLASH --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            <i class="bi bi-check-circle me-1"></i>

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            <i class="bi bi-exclamation-triangle me-1"></i>

            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    {{-- KPI --}}
    <div class="row g-3 mb-4">

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Total Tests
                    </div>

                    <div class="fs-3 fw-bold">
                        {{ $totalTests }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Completed
                    </div>

                    <div class="fs-3 fw-bold text-success">
                        {{ $completedTests }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Passed
                    </div>

                    <div class="fs-3 fw-bold text-success">
                        {{ $passedTests }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Pass Rate
                    </div>

                    <div class="fs-3 fw-bold">
                        {{ $passRate }}%
                    </div>

                </div>

            </div>

        </div>

    </div>


    <div class="row g-3 mb-4">

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <div class="text-muted small">
                        In Progress
                    </div>

                    <div class="fs-4 fw-bold">
                        {{ $inProgressTests }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <div class="text-muted small">
                        Failed
                    </div>

                    <div class="fs-4 fw-bold text-danger">
                        {{ $failedTests }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <div class="text-muted small">
                        Retest Required
                    </div>

                    <div class="fs-4 fw-bold text-warning">
                        {{ $retestRequired }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <div class="text-muted small">
                        Retest Executions
                    </div>

                    <div class="fs-4 fw-bold">
                        {{ $retestExecutions }}
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- FILTERS --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <form
                method="GET"
                class="row g-3"
            >

                <div class="col-md-4">

                    <label class="form-label small">
                        Search
                    </label>

                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        class="form-control"
                        placeholder="Test No, scope, plan, location..."
                    >

                </div>


                <div class="col-md-2">

                    <label class="form-label small">
                        Result
                    </label>

                    <select
                        name="result"
                        class="form-select"
                    >

                        <option value="">
                            All Results
                        </option>

                        @foreach([
                            'Pass',
                            'Fail',
                            'Conditional Pass',
                            'Not Tested'
                        ] as $result)

                            <option
                                value="{{ $result }}"
                                @selected(request('result') === $result)
                            >
                                {{ $result }}
                            </option>

                        @endforeach

                    </select>

                </div>


                <div class="col-md-2">

                    <label class="form-label small">
                        Status
                    </label>

                    <select
                        name="status"
                        class="form-select"
                    >

                        <option value="">
                            All Status
                        </option>

                        @foreach([
                            'Planned',
                            'Scheduled',
                            'In Progress',
                            'Completed',
                            'Retest Required',
                            'Cancelled'
                        ] as $status)

                            <option
                                value="{{ $status }}"
                                @selected(request('status') === $status)
                            >
                                {{ $status }}
                            </option>

                        @endforeach

                    </select>

                </div>


                <div class="col-md-2">

                    <label class="form-label small">
                        Retest
                    </label>

                    <select
                        name="retest"
                        class="form-select"
                    >

                        <option value="">
                            All
                        </option>

                        <option
                            value="yes"
                            @selected(request('retest') === 'yes')
                        >
                            Retests
                        </option>

                        <option
                            value="no"
                            @selected(request('retest') === 'no')
                        >
                            Original Tests
                        </option>

                    </select>

                </div>


                <div class="col-md-2 d-flex align-items-end gap-2">

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        <i class="bi bi-search me-1"></i>
                        Filter
                    </button>

                    <a
                        href="{{ route('admin.projects.commissioning.tests.index', $project) }}"
                        class="btn btn-light"
                    >
                        Reset
                    </a>

                </div>

            </form>

        </div>

    </div>


    {{-- TABLE --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white d-flex justify-content-between">

            <h6 class="mb-0 fw-semibold">
                Test Execution Register
            </h6>

            <span class="text-muted small">
                {{ $tests->total() }} records
            </span>

        </div>


        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">

                    <tr>

                        <th>#</th>
                        <th>Test</th>
                        <th>Scope</th>
                        <th>Test Plan</th>
                        <th>Date</th>
                        <th>Result</th>
                        <th>Status</th>
                        <th>Retest</th>
                        <th class="text-end">Actions</th>

                    </tr>

                </thead>

                <tbody>

                @forelse($tests as $test)

                    <tr>

                        <td>
                            {{ $tests->firstItem() + $loop->index }}
                        </td>


                        <td>

                            <a
                                href="{{ route('admin.projects.commissioning.tests.show', [$project, $test]) }}"
                                class="fw-semibold text-decoration-none"
                            >
                                {{ $test->test_no }}
                            </a>

                            @if($test->test_type)

                                <div class="small text-muted">
                                    {{ $test->test_type }}
                                </div>

                            @endif

                        </td>


                        <td>

                            @if($test->scope)

                                <div class="fw-semibold">
                                    {{ $test->scope->scope_code }}
                                </div>

                                <div class="small text-muted">
                                    {{ $test->scope->scope_name }}
                                </div>

                            @else
                                —
                            @endif

                        </td>


                        <td>

                            @if($test->plan)

                                <div class="fw-semibold">
                                    {{ $test->plan->test_plan_no }}
                                </div>

                                <div class="small text-muted">
                                    {{ $test->plan->title }}
                                </div>

                            @else
                                —
                            @endif

                        </td>


                        <td>
                            {{ $test->test_date?->format('d M Y') ?? '—' }}
                        </td>


                        <td>

                            @php

                                $resultClass = match($test->result) {
                                    'Pass' => 'success',
                                    'Fail' => 'danger',
                                    'Conditional Pass' => 'warning',
                                    default => 'secondary',
                                };

                            @endphp

                            <span class="badge bg-{{ $resultClass }}">
                                {{ $test->result }}
                            </span>

                        </td>


                        <td>

                            @php

                                $statusClass = match($test->status) {
                                    'Completed' => 'success',
                                    'In Progress' => 'primary',
                                    'Retest Required' => 'warning',
                                    'Cancelled' => 'dark',
                                    default => 'secondary',
                                };

                            @endphp

                            <span class="badge bg-{{ $statusClass }}">
                                {{ $test->status }}
                            </span>

                        </td>


                        <td>

                            @if($test->parent_test_id)

                                <span class="badge bg-warning text-dark">
                                    Retest
                                </span>

                            @elseif($test->retest_required)

                                <span class="badge bg-danger">
                                    Required
                                </span>

                            @else

                                <span class="text-muted">
                                    —
                                </span>

                            @endif

                        </td>


                        <td class="text-end">

                            <div class="d-flex gap-1">

                                <a
                                    href="{{ route('admin.projects.commissioning.tests.show', [$project, $test]) }}"
                                    class="btn btn-sm btn-outline-primary"
                                    title="View"
                                >
                                    <i class="fas fa-eye"></i>
                                </a>

                                @if($test->status !== 'Completed')

                                    <a
                                        href="{{ route('admin.projects.commissioning.tests.edit', [$project, $test]) }}"
                                        class="btn btn-sm btn-outline-secondary"
                                        title="Edit"
                                    >
                                        <i class="fas fa-edit"></i>
                                    </a>

                                @endif

                                @if(
                                    $test->result === 'Fail'
                                    || $test->retest_required
                                    || $test->status === 'Retest Required'
                                )

                                    <a
                                        href="{{ route('admin.projects.commissioning.tests.retest.create', [$project, $test]) }}"
                                        class="btn btn-sm btn-outline-warning"
                                        title="Create Retest"
                                    >
                                        <i class="fas fa-arrow-right"></i>
                                    </a>

                                @endif

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="9"
                            class="text-center py-5 text-muted"
                        >

                            <i class="bi bi-clipboard-x fs-2 d-block mb-2"></i>

                            No commissioning tests found.

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>


        @if($tests->hasPages())

            <div class="card-footer bg-white">

                {{ $tests->links() }}

            </div>

        @endif

    </div>

</div>

@endsection