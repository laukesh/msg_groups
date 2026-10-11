@extends('layouts.app')

@section('title', 'Area Revisions')

@section('content')

<div class="container-fluid">

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                <i class="ri-history-line me-1"></i>
                Area Revisions
            </h4>

            <div class="text-muted">
                {{ $area->area_code ?? 'Area' }}
                -
                {{ $area->name ?? $area->area_name ?? 'Property Area' }}
            </div>
        </div>

        <a href="{{ route(
            'admin.assets.revisions.create',
            ['area' => $area->id]
        ) }}"
           class="btn btn-primary">

            <i class="ri-add-line me-1"></i>
            Create Revision

        </a>

    </div>


    {{-- =========================================================
        SUCCESS MESSAGE
    ========================================================== --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            <i class="ri-check-line me-1"></i>

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- =========================================================
        ERROR MESSAGE
    ========================================================== --}}
    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            <i class="ri-error-warning-line me-1"></i>

            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- =========================================================
        VALIDATION ERRORS
    ========================================================== --}}
    @if($errors->any())

        <div class="alert alert-danger">

            <strong>
                Please correct the following errors:
            </strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- =========================================================
        AREA SUMMARY
    ========================================================== --}}
    <div class="row g-3 mb-4">

        {{-- GROSS AREA --}}
        <div class="col-md-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <div class="text-muted small">
                                Gross Area
                            </div>

                            <h4 class="mb-0 mt-1">
                                {{ number_format($area->gross_area ?? 0, 2) }}
                            </h4>

                        </div>

                        <div class="text-primary fs-3">
                            <i class="ri-building-line"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- NET AREA --}}
        <div class="col-md-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <div class="text-muted small">
                                Net Area
                            </div>

                            <h4 class="mb-0 mt-1">
                                {{ number_format($area->net_area ?? 0, 2) }}
                            </h4>

                        </div>

                        <div class="text-success fs-3">
                            <i class="ri-layout-grid-line"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- RENTABLE AREA --}}
        <div class="col-md-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <div class="text-muted small">
                                Rentable Area
                            </div>

                            <h4 class="mb-0 mt-1">
                                {{ number_format($area->rentable_area ?? 0, 2) }}
                            </h4>

                        </div>

                        <div class="text-warning fs-3">
                            <i class="ri-ruler-line"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        REVISION HISTORY
    ========================================================== --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white py-3">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <h5 class="mb-0">
                        <i class="ri-history-line me-1"></i>
                        Revision History
                    </h5>

                    <small class="text-muted">
                        Area change and approval history
                    </small>

                </div>

                <span class="badge bg-secondary">

                    {{ $revisions->total() }}

                    {{ $revisions->total() == 1
                        ? 'Revision'
                        : 'Revisions'
                    }}

                </span>

            </div>

        </div>


        <div class="card-body p-0">

            @if($revisions->count() > 0)

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th class="ps-3">
                                    #
                                </th>

                                <th>
                                    Revision
                                </th>

                                <th>
                                    Area Changes
                                </th>

                                <th>
                                    Reason
                                </th>

                                <th>
                                    Revision Date
                                </th>

                                <th>
                                    Effective From
                                </th>

                                <th>
                                    Status
                                </th>

                                <th class="text-end pe-3">
                                    Actions
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                        @foreach($revisions as $revision)

                            @php

                                $status = strtoupper(
                                    (string) ($revision->status ?? 'UNKNOWN')
                                );

                                $statusClass = match($status) {

                                    'DRAFT' =>
                                        'bg-secondary',

                                    'SUBMITTED' =>
                                        'bg-warning text-dark',

                                    'APPROVED' =>
                                        'bg-success',

                                    'REJECTED' =>
                                        'bg-danger',

                                    default =>
                                        'bg-dark',

                                };

                            @endphp


                            <tr>

                                {{-- =================================================
                                    ID
                                ================================================== --}}
                                <td class="ps-3">

                                    {{ $revision->id }}

                                </td>


                                {{-- =================================================
                                    REVISION NUMBER
                                ================================================== --}}
                                <td>

                                    <span class="fw-semibold">

                                        REV-{{
                                            str_pad(
                                                $revision->revision_number ?? 0,
                                                3,
                                                '0',
                                                STR_PAD_LEFT
                                            )
                                        }}

                                    </span>

                                </td>


                                {{-- =================================================
                                    AREA CHANGES
                                ================================================== --}}
                                <td>

                                    <div class="small">

                                        {{-- GROSS --}}
                                        <div class="mb-1">

                                            <span class="fw-semibold">
                                                Gross:
                                            </span>

                                            <span class="text-muted">

                                                {{
                                                    number_format(
                                                        $revision->previous_gross_area ?? 0,
                                                        2
                                                    )
                                                }}

                                            </span>

                                            <i class="ri-arrow-right-line mx-1"></i>

                                            <span class="text-primary fw-semibold">

                                                {{
                                                    number_format(
                                                        $revision->new_gross_area ?? 0,
                                                        2
                                                    )
                                                }}

                                            </span>

                                        </div>


                                        {{-- NET --}}
                                        <div class="mb-1">

                                            <span class="fw-semibold">
                                                Net:
                                            </span>

                                            <span class="text-muted">

                                                {{
                                                    number_format(
                                                        $revision->previous_net_area ?? 0,
                                                        2
                                                    )
                                                }}

                                            </span>

                                            <i class="ri-arrow-right-line mx-1"></i>

                                            <span class="text-primary fw-semibold">

                                                {{
                                                    number_format(
                                                        $revision->new_net_area ?? 0,
                                                        2
                                                    )
                                                }}

                                            </span>

                                        </div>


                                        {{-- RENTABLE --}}
                                        <div>

                                            <span class="fw-semibold">
                                                Rentable:
                                            </span>

                                            <span class="text-muted">

                                                {{
                                                    number_format(
                                                        $revision->previous_rentable_area ?? 0,
                                                        2
                                                    )
                                                }}

                                            </span>

                                            <i class="ri-arrow-right-line mx-1"></i>

                                            <span class="text-primary fw-semibold">

                                                {{
                                                    number_format(
                                                        $revision->new_rentable_area ?? 0,
                                                        2
                                                    )
                                                }}

                                            </span>

                                        </div>

                                    </div>

                                </td>


                                {{-- =================================================
                                    REASON
                                ================================================== --}}
                                <td>

                                    @if($revision->reason)

                                        <span
                                            title="{{ $revision->reason }}">

                                            {{
                                                \Illuminate\Support\Str::limit(
                                                    $revision->reason,
                                                    50
                                                )
                                            }}

                                        </span>

                                    @else

                                        <span class="text-muted">
                                            -
                                        </span>

                                    @endif

                                </td>


                                {{-- =================================================
                                    REVISION DATE
                                ================================================== --}}
                                <td>

                                    @if($revision->revision_date)

                                        {{
                                            \Carbon\Carbon::parse(
                                                $revision->revision_date
                                            )->format('d M Y')
                                        }}

                                    @else

                                        <span class="text-muted">
                                            -
                                        </span>

                                    @endif

                                </td>


                                {{-- =================================================
                                    EFFECTIVE DATE
                                ================================================== --}}
                                <td>

                                    @if($revision->effective_from)

                                        {{
                                            \Carbon\Carbon::parse(
                                                $revision->effective_from
                                            )->format('d M Y')
                                        }}

                                    @else

                                        <span class="text-muted">
                                            -
                                        </span>

                                    @endif

                                </td>


                                {{-- =================================================
                                    STATUS
                                ================================================== --}}
                                <td>

                                    <span class="badge {{ $statusClass }}">

                                        {{ $status }}

                                    </span>

                                </td>


                                {{-- =================================================
                                    ACTIONS
                                ================================================== --}}
                                <td class="text-end pe-3">

                                    <div class="btn-group"
                                         role="group">

                                        {{-- =================================================
                                            VIEW
                                        ================================================== --}}
                                        <a href="{{ route(
                                            'admin.assets.revisions.show',
                                            [
                                                'area' => $area->id,
                                                'revision' => $revision->id,
                                            ]
                                        ) }}"
                                           class="btn btn-sm btn-outline-primary"
                                           title="View Revision">

                                            <i class="ri-eye-line"></i>

                                        </a>


                                        {{-- =================================================
                                            EDIT
                                            DRAFT / REJECTED
                                        ================================================== --}}
                                        @if(
                                            $status === 'DRAFT' ||
                                            $status === 'REJECTED'
                                        )

                                            <a href="{{ route(
                                                'admin.assets.revisions.edit',
                                                [
                                                    'area' => $area->id,
                                                    'revision' => $revision->id,
                                                ]
                                            ) }}"
                                               class="btn btn-sm btn-outline-secondary"
                                               title="Edit Revision">

                                                <i class="ri-edit-line"></i>

                                            </a>

                                        @endif


                                        {{-- =================================================
                                            SUBMIT
                                            DRAFT
                                        ================================================== --}}
                                        @if($status === 'DRAFT')

                                            <form method="POST"
                                                  action="{{ route(
                                                      'admin.assets.revisions.submit', [
                                                        'area' => $area->id,
                                                        'revision' => $revision->id,
                                                    ]
                                                  ) }}"
                                                  class="d-inline">

                                                @csrf

                                                <button type="submit"
                                                        class="btn btn-sm btn-outline-warning"
                                                        title="Submit for Approval"
                                                        onclick="return confirm('Submit this revision for approval?')">

                                                    <i class="ri-send-plane-line"></i>

                                                </button>

                                            </form>

                                        @endif


                                        {{-- =================================================
                                            APPROVE
                                            SUBMITTED
                                        ================================================== --}}
                                        @if($status === 'SUBMITTED')

                                            <form method="POST"
                                                  action="{{ route(
                                                      'admin.assets.revisions.revisions.approve',
                                                      [
                                                          'area' => $area->id,
                                                          'revision' => $revision->id,
                                                      ]
                                                  ) }}"
                                                  class="d-inline">

                                                @csrf

                                                <button type="submit"
                                                        class="btn btn-sm btn-outline-success"
                                                        title="Approve Revision"
                                                        onclick="return confirm('Approve this revision and update the current area values?')">

                                                    <i class="ri-check-line"></i>

                                                </button>

                                            </form>


                                            {{-- REJECT --}}
                                            <button type="button"
                                                    class="btn btn-sm btn-outline-danger"
                                                    title="Reject Revision"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#rejectModal{{ $revision->id }}">

                                                <i class="ri-close-line"></i>

                                            </button>

                                        @endif


                                        {{-- =================================================
                                            DELETE
                                            DRAFT / REJECTED
                                        ================================================== --}}
                                        @if(
                                            $status === 'DRAFT' ||
                                            $status === 'REJECTED'
                                        )

                                            <form method="POST"
                                                  action="{{ route(
                                                      'admin.assets.revisions.destroy',
                                                      [
                                                          'area' => $area->id,
                                                          'revision' => $revision->id,
                                                      ]
                                                  ) }}"
                                                  class="d-inline">

                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        class="btn btn-sm btn-outline-danger"
                                                        title="Delete Revision"
                                                        onclick="return confirm('Are you sure you want to delete this revision?')">

                                                    <i class="ri-delete-bin-line"></i>

                                                </button>

                                            </form>

                                        @endif

                                    </div>


                                    {{-- =================================================
                                        REJECT MODAL
                                    ================================================== --}}
                                    @if($status === 'SUBMITTED')

                                        <div class="modal fade"
                                             id="rejectModal{{ $revision->id }}"
                                             tabindex="-1"
                                             aria-hidden="true">

                                            <div class="modal-dialog">

                                                <form method="POST"
                                                      action="{{ route(
                                                          'admin.assets.revisions.revisions.reject',
                                                          [
                                                              'area' => $area->id,
                                                              'revision' => $revision->id,
                                                          ]
                                                      ) }}">

                                                    @csrf

                                                    <div class="modal-content">

                                                        <div class="modal-header">

                                                            <h5 class="modal-title">

                                                                <i class="ri-close-circle-line me-1"></i>

                                                                Reject Area Revision

                                                            </h5>

                                                            <button type="button"
                                                                    class="btn-close"
                                                                    data-bs-dismiss="modal">
                                                            </button>

                                                        </div>


                                                        <div class="modal-body">

                                                            <div class="mb-3">

                                                                <label class="form-label">

                                                                    Rejection Remarks

                                                                </label>

                                                                <textarea
                                                                    name="remarks"
                                                                    class="form-control"
                                                                    rows="4"
                                                                    placeholder="Enter rejection reason...">{{ old('remarks') }}</textarea>

                                                            </div>

                                                        </div>


                                                        <div class="modal-footer">

                                                            <button type="button"
                                                                    class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">

                                                                Cancel

                                                            </button>


                                                            <button type="submit"
                                                                    class="btn btn-danger">

                                                                <i class="ri-close-line me-1"></i>

                                                                Reject Revision

                                                            </button>

                                                        </div>

                                                    </div>

                                                </form>

                                            </div>

                                        </div>

                                    @endif

                                </td>

                            </tr>

                        @endforeach

                        </tbody>

                    </table>

                </div>


                {{-- =================================================
                    PAGINATION
                ================================================== --}}
                @if($revisions->hasPages())

                    <div class="p-3 border-top">

                        {{ $revisions->links() }}

                    </div>

                @endif


            @else

                {{-- =================================================
                    EMPTY STATE
                ================================================== --}}
                <div class="text-center py-5">

                    <div class="mb-3">

                        <i class="ri-file-history-line fs-1 text-muted"></i>

                    </div>

                    <h5 class="mb-2">
                        No Area Revisions
                    </h5>

                    <p class="text-muted mb-4">

                        No revisions have been created for this
                        property area.

                    </p>


                    <a href="{{ route(
                        'admin.assets.revisions.create',
                        [
                            'area' => $area->id,
                        ]
                    ) }}"
                       class="btn btn-primary">

                        <i class="ri-add-line me-1"></i>

                        Create First Revision

                    </a>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection