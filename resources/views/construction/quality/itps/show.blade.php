@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ================================================================ --}}
    {{-- HEADER --}}
    {{-- ================================================================ --}}

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="d-flex align-items-center gap-2 mb-2">

                <h4 class="mb-0">
                    {{ $itp->itp_number }}
                </h4>

                @switch($itp->status)

                    @case('Draft')
                        <span class="badge bg-secondary">
                            Draft
                        </span>
                        @break

                    @case('Submitted')
                        <span class="badge bg-primary">
                            Submitted
                        </span>
                        @break

                    @case('Under Review')
                        <span class="badge bg-warning text-dark">
                            Under Review
                        </span>
                        @break

                    @case('Approved')
                        <span class="badge bg-success">
                            Approved
                        </span>
                        @break

                    @case('Rejected')
                        <span class="badge bg-danger">
                            Rejected
                        </span>
                        @break

                    @default
                        <span class="badge bg-light text-dark">
                            {{ $itp->status }}
                        </span>

                @endswitch

            </div>


            <h5 class="mb-1">
                {{ $itp->title }}
            </h5>


            <div class="text-muted">

                Project:
                {{ $project->name ?? $project->project_name ?? 'Project' }}

            </div>

        </div>


        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.projects.construction.quality.itps.index',
                    $project
                ) }}"
                class="btn btn-outline-secondary"
            >
                ← Back
            </a>


            @if(
                in_array(
                    $itp->status,
                    [
                        'Draft',
                        'Rejected',
                    ],
                    true
                )
            )

                <a
                    href="{{ route(
                        'admin.projects.construction.quality.itps.edit',
                        [
                            'project' =>
                                $project,

                            'itp' =>
                                $itp,
                        ]
                    ) }}"
                    class="btn btn-outline-primary"
                >
                    Edit
                </a>

            @endif

        </div>

    </div>


    {{-- ================================================================ --}}
    {{-- FLASH MESSAGES --}}
    {{-- ================================================================ --}}

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    @if($errors->any())

        <div class="alert alert-danger alert-dismissible fade show">

            @foreach($errors->all() as $error)

                <div>
                    {{ $error }}
                </div>

            @endforeach

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    {{-- ================================================================ --}}
    {{-- ITP INFORMATION --}}
    {{-- ================================================================ --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0">
                ITP Information
            </h5>

        </div>


        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-3">

                    <div class="text-muted small">
                        ITP Number
                    </div>

                    <div class="fw-semibold">
                        {{ $itp->itp_number }}
                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        ITP Type
                    </div>

                    <div>
                        {{ $itp->itp_type ?: '—' }}
                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Prepared By
                    </div>

                    <div>
                        {{ $itp->preparer?->name ?? '—' }}
                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Prepared Date
                    </div>

                    <div>
                        {{ $itp->prepared_date?->format('d M Y') ?? '—' }}
                    </div>

                </div>


                <div class="col-md-6">

                    <div class="text-muted small">
                        Contract
                    </div>

                    @if($itp->contract)

                        <div class="fw-semibold">

                            {{ $itp->contract->contract_number }}

                        </div>

                        <div class="text-muted small">

                            {{
                                $itp->contract->bidder?->company_name
                                ??
                                $itp->contract->bidder_name
                                ??
                                '—'
                            }}

                        </div>

                    @else

                        <span class="text-muted">
                            —
                        </span>

                    @endif

                </div>


                <div class="col-md-6">

                    <div class="text-muted small">
                        Work Order
                    </div>

                    @if($itp->workOrder)

                        <div class="fw-semibold">

                            {{ $itp->workOrder->work_order_number }}

                        </div>

                        <div class="text-muted small">

                            {{ $itp->workOrder->work_order_title }}

                        </div>

                    @else

                        <span class="text-muted">
                            —
                        </span>

                    @endif

                </div>


                @if($itp->description)

                    <div class="col-md-12">

                        <div class="text-muted small mb-1">
                            Description
                        </div>

                        <div>
                            {!! nl2br(e($itp->description)) !!}
                        </div>

                    </div>

                @endif


                @if($itp->remarks)

                    <div class="col-md-12">

                        <div class="text-muted small mb-1">
                            Remarks
                        </div>

                        <div>
                            {!! nl2br(e($itp->remarks)) !!}
                        </div>

                    </div>

                @endif

            </div>

        </div>

    </div>


    {{-- ================================================================ --}}
    {{-- WORKFLOW --}}
    {{-- ================================================================ --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0">
                Workflow
            </h5>

        </div>


        <div class="card-body">

            <div class="d-flex flex-wrap gap-2">

                {{-- Draft --}}
                @if($itp->status === 'Draft')

                    <form
                        method="POST"
                        action="{{ route(
                            'admin.projects.construction.quality.itps.submit',
                            [
                                'project' =>
                                    $project,

                                'itp' =>
                                    $itp,
                            ]
                        ) }}"
                        onsubmit="return confirm(
                            'Submit this ITP for review?'
                        );"
                    >

                        @csrf

                        <button
                            type="submit"
                            class="btn btn-primary"
                        >
                            Submit for Review
                        </button>

                    </form>

                @endif


                {{-- Submitted --}}
                @if($itp->status === 'Submitted')

                    <form
                        method="POST"
                        action="{{ route(
                            'admin.projects.construction.quality.itps.start-review',
                            [
                                'project' =>
                                    $project,

                                'itp' =>
                                    $itp,
                            ]
                        ) }}"
                        onsubmit="return confirm(
                            'Start review of this ITP?'
                        );"
                    >

                        @csrf

                        <button
                            type="submit"
                            class="btn btn-warning"
                        >
                            Start Review
                        </button>

                    </form>

                @endif


                {{-- Under Review --}}
                @if($itp->status === 'Under Review')

                    <form
                        method="POST"
                        action="{{ route(
                            'admin.projects.construction.quality.itps.approve',
                            [
                                'project' =>
                                    $project,

                                'itp' =>
                                    $itp,
                            ]
                        ) }}"
                        onsubmit="return confirm(
                            'Approve this ITP?'
                        );"
                    >

                        @csrf

                        <button
                            type="submit"
                            class="btn btn-success"
                        >
                            ✓ Approve ITP
                        </button>

                    </form>


                    <button
                        type="button"
                        class="btn btn-danger"
                        data-bs-toggle="modal"
                        data-bs-target="#rejectItpModal"
                    >
                        Reject ITP
                    </button>

                @endif


                {{-- Approved --}}
                @if($itp->status === 'Approved')

                    <span class="badge bg-success fs-6 px-3 py-2">

                        ✓ Approved

                    </span>

                @endif


                {{-- Rejected --}}
                @if($itp->status === 'Rejected')

                    <div class="w-100">

                        <div class="alert alert-danger mb-0">

                            <div class="fw-semibold">
                                ITP Rejected
                            </div>

                            @if($itp->approval_remarks)

                                <div class="mt-1">

                                    {{ $itp->approval_remarks }}

                                </div>

                            @endif

                        </div>

                    </div>

                @endif

            </div>


            @if($itp->approved_by)

                <div class="mt-3 text-muted small">

                    Approved by
                    <strong>
                        {{ $itp->approver?->name ?? '—' }}
                    </strong>

                    @if($itp->approved_date)

                        on
                        {{ $itp->approved_date->format('d M Y') }}

                    @endif

                </div>

            @endif

        </div>

    </div>


    {{-- ================================================================ --}}
    {{-- ITP ITEMS --}}
    {{-- ================================================================ --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <h5 class="mb-0">
                        Inspection & Test Items
                    </h5>

                    <div class="text-muted small mt-1">

                        {{ $itp->items->count() }}
                        item(s)

                    </div>

                </div>


                @if(
                    in_array(
                        $itp->status,
                        [
                            'Draft',
                            'Rejected',
                        ],
                        true
                    )
                )

                    <button
                        type="button"
                        class="btn btn-primary btn-sm"
                        data-bs-toggle="modal"
                        data-bs-target="#addItemModal"
                    >
                        + Add Item
                    </button>

                @endif

            </div>

        </div>


        <div class="card-body p-0">

            @if($itp->items->count())

                <div class="table-responsive">

                    <table class="table table-bordered align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th style="width: 60px;">
                                    #
                                </th>

                                <th>
                                    Activity
                                </th>

                                <th>
                                    Inspection / Test
                                </th>

                                <th>
                                    Stage
                                </th>

                                <th>
                                    Acceptance Criteria
                                </th>

                                <th>
                                    Reference Standard
                                </th>

                                <th>
                                    Type
                                </th>

                                <th>
                                    Responsible
                                </th>

                                <th class="text-center">
                                    HP
                                </th>

                                <th class="text-center">
                                    WP
                                </th>

                                <th class="text-center">
                                    Required
                                </th>

                                @if(
                                    in_array(
                                        $itp->status,
                                        [
                                            'Draft',
                                            'Rejected',
                                        ],
                                        true
                                    )
                                )

                                    <th
                                        class="text-end"
                                        style="width: 100px;"
                                    >
                                        Action
                                    </th>

                                @endif

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($itp->items as $item)

                                <tr>

                                    <td class="fw-semibold">

                                        {{ $item->item_number }}

                                    </td>


                                    <td>

                                        {{ $item->activity }}

                                    </td>


                                    <td>

                                        <div class="fw-semibold">

                                            {{ $item->inspection_test }}

                                        </div>

                                    </td>


                                    <td>

                                        {{ $item->stage ?: '—' }}

                                    </td>


                                    <td>

                                        {{ $item->acceptance_criteria ?: '—' }}

                                    </td>


                                    <td>

                                        {{ $item->reference_standard ?: '—' }}

                                    </td>


                                    <td>

                                        {{ $item->inspection_type ?: '—' }}

                                    </td>


                                    <td>

                                        {{ $item->responsible_party ?: '—' }}

                                    </td>


                                    <td class="text-center">

                                        @if($item->hold_point)

                                            <span class="badge bg-danger">
                                                HP
                                            </span>

                                        @else

                                            <span class="text-muted">
                                                —
                                            </span>

                                        @endif

                                    </td>


                                    <td class="text-center">

                                        @if($item->witness_point)

                                            <span class="badge bg-warning text-dark">
                                                WP
                                            </span>

                                        @else

                                            <span class="text-muted">
                                                —
                                            </span>

                                        @endif

                                    </td>


                                    <td class="text-center">

                                        @if($item->required)

                                            <span class="badge bg-success">
                                                Yes
                                            </span>

                                        @else

                                            <span class="text-muted">
                                                No
                                            </span>

                                        @endif

                                    </td>


                                    @if(
                                        in_array(
                                            $itp->status,
                                            [
                                                'Draft',
                                                'Rejected',
                                            ],
                                            true
                                        )
                                    )

                                        <td class="text-end">

                                            <div class="dropdown">

                                                <button
                                                    class="btn btn-sm btn-outline-secondary dropdown-toggle"
                                                    type="button"
                                                    data-bs-toggle="dropdown"
                                                >
                                                    Action
                                                </button>


                                                <ul class="dropdown-menu dropdown-menu-end">

                                                    <li>

                                                        <button
                                                            type="button"
                                                            class="dropdown-item"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#editItemModal{{ $item->id }}"
                                                        >
                                                            Edit
                                                        </button>

                                                    </li>


                                                    <li>

                                                        <form
                                                            method="POST"
                                                            action="{{ route(
                                                                'admin.projects.construction.quality.itps.items.destroy',
                                                                [
                                                                    'project' =>
                                                                        $project,

                                                                    'itp' =>
                                                                        $itp,

                                                                    'item' =>
                                                                        $item,
                                                                ]
                                                            ) }}"
                                                            onsubmit="return confirm(
                                                                'Delete this ITP item?'
                                                            );"
                                                        >

                                                            @csrf

                                                            @method('DELETE')

                                                            <button
                                                                type="submit"
                                                                class="dropdown-item text-danger"
                                                            >
                                                                Delete
                                                            </button>

                                                        </form>

                                                    </li>

                                                </ul>

                                            </div>

                                        </td>

                                    @endif

                                </tr>


                                {{-- ================================================= --}}
                                {{-- EDIT ITEM MODAL --}}
                                {{-- ================================================= --}}

                                @if(
                                    in_array(
                                        $itp->status,
                                        [
                                            'Draft',
                                            'Rejected',
                                        ],
                                        true
                                    )
                                )

                                    <div
                                        class="modal fade"
                                        id="editItemModal{{ $item->id }}"
                                        tabindex="-1"
                                        aria-hidden="true"
                                    >

                                        <div class="modal-dialog modal-lg">

                                            <div class="modal-content">

                                                <div class="modal-header">

                                                    <h5 class="modal-title">
                                                        Edit ITP Item
                                                    </h5>

                                                    <button
                                                        type="button"
                                                        class="btn-close"
                                                        data-bs-dismiss="modal"
                                                    ></button>

                                                </div>


                                                <form
                                                    method="POST"
                                                    action="{{ route(
                                                        'admin.projects.construction.quality.itps.items.update',
                                                        [
                                                            'project' =>
                                                                $project,

                                                            'itp' =>
                                                                $itp,

                                                            'item' =>
                                                                $item,
                                                        ]
                                                    ) }}"
                                                >

                                                    @csrf

                                                    @method('PUT')


                                                    <div class="modal-body">

                                                        @include(
                                                            'construction.quality.itps._item-form',
                                                            [
                                                                'item' =>
                                                                    $item,
                                                            ]
                                                        )

                                                    </div>


                                                    <div class="modal-footer">

                                                        <button
                                                            type="button"
                                                            class="btn btn-light"
                                                            data-bs-dismiss="modal"
                                                        >
                                                            Cancel
                                                        </button>

                                                        <button
                                                            type="submit"
                                                            class="btn btn-primary"
                                                        >
                                                            Update Item
                                                        </button>

                                                    </div>

                                                </form>

                                            </div>

                                        </div>

                                    </div>

                                @endif

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="text-center py-5">

                    <div class="fw-semibold mb-2">
                        No ITP items added
                    </div>

                    <div class="text-muted mb-3">
                        Add inspection and test checkpoints to this ITP.
                    </div>


                    @if(
                        in_array(
                            $itp->status,
                            [
                                'Draft',
                                'Rejected',
                            ],
                            true
                        )
                    )

                        <button
                            type="button"
                            class="btn btn-primary"
                            data-bs-toggle="modal"
                            data-bs-target="#addItemModal"
                        >
                            + Add First Item
                        </button>

                    @endif

                </div>

            @endif

        </div>

    </div>


    {{-- ================================================================ --}}
    {{-- AUDIT INFORMATION --}}
    {{-- ================================================================ --}}

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white">

            <h5 class="mb-0">
                Record Information
            </h5>

        </div>


        <div class="card-body">

            <div class="row g-3">

                <div class="col-md-3">

                    <div class="text-muted small">
                        Created By
                    </div>

                    <div>
                        {{ $itp->creator?->name ?? '—' }}
                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Created At
                    </div>

                    <div>
                        {{ $itp->created_at?->format('d M Y H:i') ?? '—' }}
                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Last Updated By
                    </div>

                    <div>
                        {{ $itp->updater?->name ?? '—' }}
                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Last Updated
                    </div>

                    <div>
                        {{ $itp->updated_at?->format('d M Y H:i') ?? '—' }}
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


{{-- ================================================================== --}}
{{-- ADD ITEM MODAL --}}
{{-- ================================================================== --}}

<div
    class="modal fade"
    id="addItemModal"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog modal-lg">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">
                    Add ITP Item
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                ></button>

            </div>


            <form
                method="POST"
                action="{{ route(
                    'admin.projects.construction.quality.itps.items.store',
                    [
                        'project' =>
                            $project,

                        'itp' =>
                            $itp,
                    ]
                ) }}"
            >

                @csrf


                <div class="modal-body">

                    @include(
                        'construction.quality.itps._item-form'
                    )

                </div>


                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-light"
                        data-bs-dismiss="modal"
                    >
                        Cancel
                    </button>

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        Add Item
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


{{-- ================================================================== --}}
{{-- REJECT MODAL --}}
{{-- ================================================================== --}}

<div
    class="modal fade"
    id="rejectItpModal"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title text-danger">
                    Reject ITP
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                ></button>

            </div>


            <form
                method="POST"
                action="{{ route(
                    'admin.projects.construction.quality.itps.reject',
                    [
                        'project' =>
                            $project,

                        'itp' =>
                            $itp,
                    ]
                ) }}"
            >

                @csrf


                <div class="modal-body">

                    <label class="form-label">
                        Rejection Remarks
                        <span class="text-danger">*</span>
                    </label>

                    <textarea
                        name="approval_remarks"
                        rows="5"
                        class="form-control"
                        placeholder="Enter reason for rejection..."
                        required
                    ></textarea>

                </div>


                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-light"
                        data-bs-dismiss="modal"
                    >
                        Cancel
                    </button>

                    <button
                        type="submit"
                        class="btn btn-danger"
                    >
                        Reject ITP
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection