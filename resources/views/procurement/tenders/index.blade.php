@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Tender Management</h4>
            <div class="text-muted">
                Manage tenders issued against procurement packages.
            </div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.procurement.packages.index') }}" class="btn btn-outline-secondary">Packages</a>
            <a href="{{ route('admin.procurement.tenders.create') }}"
               class="btn btn-primary">
                + New Tender
            </a>
        </div>
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
            <strong>Filters</strong>
        </div>

        <div class="card-body">

            <form method="GET"
                  action="{{ route('admin.procurement.tenders.index') }}">

                <div class="row g-3">

                    <div class="col-md-6">

                        <label class="form-label">
                            Procurement Package
                        </label>

                        <select name="procurement_package_id"
                                class="form-select">

                            <option value="">
                                All Packages
                            </option>

                            @foreach($packages as $package)

                                <option value="{{ $package->id }}"
                                    @selected(
                                        request('procurement_package_id')
                                        == $package->id
                                    )>

                                    {{ $package->package_number }}
                                    -
                                    {{ $package->package_title }}

                                </option>

                            @endforeach

                        </select>

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
                                'Draft',
                                'Published',
                                'Open',
                                'Closed',
                                'Under Evaluation',
                                'Awarded',
                                'Cancelled',
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
                                Filter
                            </button>

                            <a href="{{ route(
                                'admin.procurement.tenders.index'
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
            <strong>Tenders</strong>
        </div>

        <div class="card-body p-0">

            @if($tenders->count())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                        <tr>
                            <th>#</th>
                            <th>Tender No.</th>
                            <th>Tender Title</th>
                            <th>Package</th>
                            <th>Project</th>
                            <th>Estimated Value</th>
                            <th>Submission Deadline</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>

                        </thead>

                        <tbody>

                        @foreach($tenders as $tender)

                            @php
                                $statusClass = match(
                                    $tender->status
                                ) {
                                    'Published' => 'bg-primary',
                                    'Open' => 'bg-success',
                                    'Under Evaluation'
                                        => 'bg-warning text-dark',
                                    'Awarded' => 'bg-info',
                                    'Cancelled' => 'bg-danger',
                                    'Closed' => 'bg-secondary',
                                    default => 'bg-secondary',
                                };
                            @endphp

                            <tr>

                                <td>
                                    {{
                                        $tenders->firstItem()
                                        + $loop->index
                                    }}
                                </td>

                                <td>
                                    <a href="{{ route(
                                        'admin.procurement.tenders.show',
                                        $tender
                                    ) }}"
                                       class="fw-semibold text-decoration-none">
                                        {{ $tender->tender_number }}
                                    </a>
                                </td>

                                <td>
                                    {{ $tender->tender_title }}
                                </td>

                                <td>
                                    {{
                                        $tender->procurementPackage
                                            ->package_number
                                        ?? '—'
                                    }}
                                </td>

                                <td>
                                    {{
                                        $tender
                                            ->procurementPackage
                                            ->procurementPlan
                                            ->project
                                            ->project_name
                                        ?? '—'
                                    }}
                                </td>

                                <td>
                                    {{ $tender->currency }}
                                    {{ number_format(
                                        (float) $tender->estimated_value,
                                        2
                                    ) }}
                                </td>

                                <td>
                                    {{
                                        $tender->submission_deadline
                                            ? $tender->submission_deadline
                                                ->format('d-m-Y')
                                            : '—'
                                    }}
                                </td>

                                <td>
                                    <span class="badge {{ $statusClass }}">
                                        {{ $tender->status }}
                                    </span>
                                </td>

                                <td class="text-end">

                                    <div class="d-inline-flex gap-1">

                                        <a href="{{ route(
                                            'admin.procurement.tenders.show',
                                            $tender
                                        ) }}"
                                           class="btn btn-sm btn-outline-primary">
                                            View
                                        </a>

                                        @if($tender->status === 'Draft')

                                            <a href="{{ route(
                                                'admin.procurement.tenders.edit',
                                                $tender
                                            ) }}"
                                               class="btn btn-sm btn-outline-secondary">
                                                Edit
                                            </a>

                                            <form method="POST"
                                                  action="{{ route(
                                                      'admin.procurement.tenders.destroy',
                                                      $tender
                                                  ) }}">

                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        class="btn btn-sm btn-outline-danger"
                                                        onclick="return confirm('Delete this Tender?')">
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
                        No tenders found.
                    </div>

                    <a href="{{ route(
                        'admin.procurement.tenders.create'
                    ) }}"
                       class="btn btn-primary">
                        Create Tender
                    </a>

                </div>

            @endif

        </div>

        @if($tenders->hasPages())

            <div class="card-footer">
                {{ $tenders->links() }}
            </div>

        @endif

    </div>

</div>

@endsection