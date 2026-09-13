@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- Header --}}

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted">
                Contract Management
            </div>

            <h4 class="mb-1">
                Contract Correspondence
            </h4>

            <div class="text-muted">

                {{ $contract->contract_code }}

                <span class="mx-1">|</span>

                {{ $contract->contract_title }}

            </div>

        </div>


        <div class="d-flex gap-2">

            <a href="{{ route(
                'admin.projects.contract-management.contracts.show',
                [$project, $contract]
            ) }}"
               class="btn btn-outline-secondary">

                <i class="bi bi-arrow-left me-1"></i>

                Contract

            </a>


            <a href="{{ route(
                'admin.projects.contract-management.contracts.correspondence.create',
                [$project, $contract]
            ) }}"
               class="btn btn-primary">

                <i class="bi bi-plus-lg me-1"></i>

                Add Correspondence

            </a>

        </div>

    </div>


    {{-- Success --}}

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- Summary --}}

    <div class="row g-3 mb-4">


        <div class="col-xl-3 col-md-6">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Total
                    </div>

                    <div class="fs-4 fw-semibold">
                        {{ $summary['total'] }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Incoming
                    </div>

                    <div class="fs-4 fw-semibold text-info">
                        {{ $summary['incoming'] }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Outgoing
                    </div>

                    <div class="fs-4 fw-semibold text-primary">
                        {{ $summary['outgoing'] }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Pending Response
                    </div>

                    <div class="fs-4 fw-semibold text-warning">
                        {{ $summary['pending_response'] }}
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Action Alerts --}}

    @if($summary['overdue'] > 0)

        <div class="alert alert-danger">

            <i class="bi bi-exclamation-triangle me-2"></i>

            <strong>
                {{ $summary['overdue'] }}
            </strong>

            correspondence item(s) have overdue responses.

        </div>

    @endif


    {{-- Type Summary --}}

    @if($typeSummary->count())

        <div class="card shadow-sm mb-4">

            <div class="card-header bg-white">

                <h5 class="mb-0">
                    Communication Types
                </h5>

            </div>


            <div class="card-body">

                <div class="d-flex flex-wrap gap-2">

                    @foreach($typeSummary as $type => $count)

                        <span class="badge bg-light text-dark border p-2">

                            {{ $type }}

                            <span class="badge bg-primary ms-1">
                                {{ $count }}
                            </span>

                        </span>

                    @endforeach

                </div>

            </div>

        </div>

    @endif


    {{-- Register --}}

    <div class="card shadow-sm">

        <div class="card-header bg-white">

            <div class="d-flex justify-content-between align-items-center">

                <h5 class="mb-0">
                    Correspondence Register
                </h5>

                <span class="text-muted small">

                    {{ $summary['total'] }}
                    record(s)

                </span>

            </div>

        </div>


        <div class="card-body p-0">

            @if($correspondence->count())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th class="px-3">
                                    No.
                                </th>

                                <th>
                                    Date
                                </th>

                                <th>
                                    Direction
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
                                    Response
                                </th>

                                <th>
                                    Status
                                </th>

                                <th class="text-end">
                                    Actions
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($correspondence as $item)

                                <tr>

                                    <td class="px-3">

                                        <strong>
                                            {{ $item->correspondence_number }}
                                        </strong>

                                        @if($item->reference_number)

                                            <div class="small text-muted">

                                                Ref:
                                                {{ $item->reference_number }}

                                            </div>

                                        @endif

                                    </td>


                                    <td>

                                        {{
                                            $item->correspondence_date
                                                ->format('d M Y')
                                        }}

                                    </td>


                                    <td>

                                        @if($item->direction === 'Incoming')

                                            <span class="badge bg-info">
                                                <i class="bi bi-arrow-down me-1"></i>
                                                Incoming
                                            </span>

                                        @else

                                            <span class="badge bg-primary">
                                                <i class="bi bi-arrow-up me-1"></i>
                                                Outgoing
                                            </span>

                                        @endif

                                    </td>


                                    <td>

                                        <span class="badge bg-light text-dark border">

                                            {{ $item->communication_type }}

                                        </span>

                                    </td>


                                    <td>

                                        <div class="fw-semibold">

                                            {{ $item->subject }}

                                        </div>


                                        @if($item->relatedCorrespondence)

                                            <div class="small text-muted">

                                                <i class="bi bi-link-45deg"></i>

                                                {{
                                                    $item
                                                        ->relatedCorrespondence
                                                        ->correspondence_number
                                                }}

                                            </div>

                                        @endif

                                    </td>


                                    <td>

                                        @if($item->direction === 'Incoming')

                                            <div class="small">
                                                From:
                                                {{ $item->from_party ?? '—' }}
                                            </div>

                                            <div class="small text-muted">
                                                To:
                                                {{ $item->to_party ?? '—' }}
                                            </div>

                                        @else

                                            <div class="small">
                                                To:
                                                {{ $item->to_party ?? '—' }}
                                            </div>

                                            <div class="small text-muted">
                                                From:
                                                {{ $item->from_party ?? '—' }}
                                            </div>

                                        @endif

                                    </td>


                                    <td>

                                        @if($item->priority === 'Urgent')

                                            <span class="badge bg-danger">
                                                Urgent
                                            </span>

                                        @elseif($item->priority === 'High')

                                            <span class="badge bg-warning text-dark">
                                                High
                                            </span>

                                        @elseif($item->priority === 'Low')

                                            <span class="badge bg-secondary">
                                                Low
                                            </span>

                                        @else

                                            <span class="badge bg-light text-dark border">
                                                Normal
                                            </span>

                                        @endif

                                    </td>


                                    <td>

                                        @if(!$item->response_required)

                                            <span class="text-muted">
                                                Not Required
                                            </span>

                                        @elseif($item->response_date)

                                            <span class="text-success">
                                                <i class="bi bi-check-circle me-1"></i>
                                                Responded
                                            </span>

                                            <div class="small text-muted">

                                                {{
                                                    $item
                                                        ->response_date
                                                        ->format('d M Y')
                                                }}

                                            </div>

                                        @elseif($item->isResponseOverdue())

                                            <span class="text-danger fw-semibold">

                                                <i class="bi bi-exclamation-circle me-1"></i>

                                                Overdue

                                            </span>

                                            <div class="small">

                                                Due:
                                                {{
                                                    $item
                                                        ->response_due_date
                                                        ->format('d M Y')
                                                }}

                                            </div>

                                        @else

                                            <span class="text-warning">

                                                Pending

                                            </span>

                                            @if($item->response_due_date)

                                                <div class="small text-muted">

                                                    Due:
                                                    {{
                                                        $item
                                                            ->response_due_date
                                                            ->format('d M Y')
                                                    }}

                                                </div>

                                            @endif

                                        @endif

                                    </td>


                                    <td>

                                        @if($item->status === 'Closed')

                                            <span class="badge bg-success">
                                                Closed
                                            </span>

                                        @elseif($item->status === 'Pending Response')

                                            <span class="badge bg-warning text-dark">
                                                Pending Response
                                            </span>

                                        @elseif($item->status === 'Responded')

                                            <span class="badge bg-info">
                                                Responded
                                            </span>

                                        @elseif($item->status === 'Archived')

                                            <span class="badge bg-secondary">
                                                Archived
                                            </span>

                                        @else

                                            <span class="badge bg-primary">
                                                {{ $item->status }}
                                            </span>

                                        @endif

                                    </td>


                                    <td class="text-end">

                                        @if($item->file_path)

                                            <a href="{{ route(
                                                'admin.projects.contract-management.contracts.correspondence.download',
                                                [
                                                    $project,
                                                    $contract,
                                                    $item
                                                ]
                                            ) }}"
                                               class="btn btn-sm btn-outline-success"
                                               title="Download">

                                                <i class="fa fa-download"></i>

                                            </a>

                                        @endif


                                        <a href="{{ route(
                                            'admin.projects.contract-management.contracts.correspondence.edit',
                                            [
                                                $project,
                                                $contract,
                                                $item
                                            ]
                                        ) }}"
                                           class="btn btn-sm btn-outline-primary"
                                           title="Edit">

                                            <i class="fa fa-edit"></i>

                                        </a>


                                        <form method="POST"
                                              action="{{ route(
                                                  'admin.projects.contract-management.contracts.correspondence.destroy',
                                                  [
                                                      $project,
                                                      $contract,
                                                      $item
                                                  ]
                                              ) }}"
                                              class="d-inline">

                                            @csrf

                                            @method('DELETE')

                                            <button type="submit"
                                                    class="btn btn-sm btn-outline-danger"
                                                    title="Delete"
                                                    onclick="return confirm('Delete this correspondence and its file?');">

                                                <i class="fa fa-trash"></i>

                                            </button>

                                        </form>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="text-center py-5">

                    <i class="bi bi-envelope-paper fs-1 text-muted"></i>

                    <h5 class="mt-3">
                        No Correspondence
                    </h5>

                    <p class="text-muted">
                        No contract correspondence has been recorded yet.
                    </p>


                    <a href="{{ route(
                        'admin.projects.contract-management.contracts.correspondence.create',
                        [$project, $contract]
                    ) }}"
                       class="btn btn-primary">

                        <i class="bi bi-plus-lg me-1"></i>

                        Add First Correspondence

                    </a>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection