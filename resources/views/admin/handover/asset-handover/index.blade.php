@extends('layouts.app')

@section('title', 'Asset Handover')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                Asset Handover
            </h4>

            <div class="text-muted">
                {{ $project->project_code ?? '' }}

                @if($project->project_name)
                    - {{ $project->project_name }}
                @endif
            </div>
        </div>

        <div class="d-flex gap-2">

            <a href="{{ route(
                'admin.projects.handover.index',
                $project
            ) }}"
               class="btn btn-light border">

                <i class="ri-arrow-left-line me-1"></i>
                Back to Handover

            </a>


            @if(!$assetHandover && $certificateApproved)

                <button type="button"
                        class="btn btn-primary"
                        data-bs-toggle="modal"
                        data-bs-target="#createAssetHandoverModal">

                    <i class="ri-add-line me-1"></i>
                    Create Asset Handover

                </button>

            @endif

        </div>

    </div>


    {{-- Alerts --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            <i class="ri-checkbox-circle-line me-1"></i>

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


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


    {{-- Validation Errors --}}
    @if($errors->any())

        <div class="alert alert-danger">

            <strong>Please correct the following:</strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

    @endif


    {{-- Certificate Dependency --}}
    @if(!$certificateApproved)

        <div class="alert alert-warning border-warning mb-4">

            <div class="d-flex align-items-start">

                <i class="ri-lock-line fs-3 me-3"></i>

                <div>

                    <h6 class="mb-1">
                        Asset Handover is Locked
                    </h6>

                    <div>
                        An <strong>Approved Handover Certificate</strong>
                        is required before Asset Handover can be created.
                    </div>

                    <div class="mt-2">

                        @if($certificate)

                            Current Certificate Status:

                            <span class="badge bg-secondary ms-1">
                                {{ $certificate->status }}
                            </span>

                        @else

                            Handover Certificate has not been created.

                        @endif

                    </div>

                </div>

            </div>

        </div>

    @endif


    {{-- KPI Cards --}}
    <div class="row g-3 mb-4">

        {{-- Total --}}
        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <div class="text-muted small">
                                Total Assets
                            </div>

                            <h4 class="mb-0 mt-2">
                                {{ $totalItems }}
                            </h4>

                        </div>

                        <div class="avatar-sm bg-primary-subtle rounded">

                            <div class="avatar-title text-primary fs-4">

                                <i class="ri-building-4-line"></i>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Pending --}}
        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <div class="text-muted small">
                                Pending
                            </div>

                            <h4 class="mb-0 mt-2 text-warning">
                                {{ $pendingItems }}
                            </h4>

                        </div>

                        <div class="avatar-sm bg-warning-subtle rounded">

                            <div class="avatar-title text-warning fs-4">

                                <i class="ri-time-line"></i>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Verified --}}
        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <div class="text-muted small">
                                Verified
                            </div>

                            <h4 class="mb-0 mt-2 text-info">
                                {{ $verifiedItems }}
                            </h4>

                        </div>

                        <div class="avatar-sm bg-info-subtle rounded">

                            <div class="avatar-title text-info fs-4">

                                <i class="ri-search-eye-line"></i>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Accepted --}}
        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <div class="text-muted small">
                                Accepted
                            </div>

                            <h4 class="mb-0 mt-2 text-success">
                                {{ $acceptedItems }}
                            </h4>

                        </div>

                        <div class="avatar-sm bg-success-subtle rounded">

                            <div class="avatar-title text-success fs-4">

                                <i class="ri-checkbox-circle-line"></i>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <div class="row g-4">


        {{-- Main --}}
        <div class="col-xl-8">


            @if($assetHandover)

                {{-- Asset Handover Header --}}
                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white">

                        <div class="d-flex justify-content-between align-items-center">

                            <div>

                                <h5 class="card-title mb-1">
                                    {{ $assetHandover->title }}
                                </h5>

                                <div class="text-muted small">

                                    {{ $assetHandover->handover_no }}

                                </div>

                            </div>


                            @php

                                $statusClass = match(
                                    $assetHandover->status
                                ) {

                                    'Approved' =>
                                        'bg-success',

                                    'Rejected' =>
                                        'bg-danger',

                                    'Under Review' =>
                                        'bg-info',

                                    'Submitted' =>
                                        'bg-warning text-dark',

                                    'Prepared' =>
                                        'bg-primary',

                                    default =>
                                        'bg-secondary',

                                };

                            @endphp


                            <span class="badge {{ $statusClass }}">

                                {{ $assetHandover->status }}

                            </span>

                        </div>

                    </div>


                    <div class="card-body">

                        <div class="row g-4">

                            <div class="col-md-4">

                                <div class="text-muted small">
                                    Handover No.
                                </div>

                                <div class="fw-semibold">
                                    {{ $assetHandover->handover_no }}
                                </div>

                            </div>


                            <div class="col-md-4">

                                <div class="text-muted small">
                                    Handover Date
                                </div>

                                <div>

                                    {{ $assetHandover->handover_date
                                        ? $assetHandover->handover_date->format('d M Y')
                                        : '-' }}

                                </div>

                            </div>


                            <div class="col-md-4">

                                <div class="text-muted small">
                                    Asset Count
                                </div>

                                <div class="fw-bold">
                                    {{ $assetHandover->asset_count }}
                                </div>

                            </div>


                            @if($assetHandover->description)

                                <div class="col-12">

                                    <div class="text-muted small">
                                        Description
                                    </div>

                                    <div>

                                        {!! nl2br(
                                            e($assetHandover->description)
                                        ) !!}

                                    </div>

                                </div>

                            @endif


                            @if($assetHandover->handover_statement)

                                <div class="col-12">

                                    <div class="text-muted small">
                                        Handover Statement
                                    </div>

                                    <div class="bg-light border rounded p-3">

                                        {!! nl2br(
                                            e(
                                                $assetHandover
                                                    ->handover_statement
                                            )
                                        ) !!}

                                    </div>

                                </div>

                            @endif


                            @if($assetHandover->rejection_reason)

                                <div class="col-12">

                                    <div class="alert alert-danger mb-0">

                                        <strong>
                                            Rejection Reason
                                        </strong>

                                        <div class="mt-1">

                                            {!! nl2br(
                                                e(
                                                    $assetHandover
                                                        ->rejection_reason
                                                )
                                            ) !!}

                                        </div>

                                    </div>

                                </div>

                            @endif

                        </div>

                    </div>

                </div>


                {{-- Asset Items --}}
                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white">

                        <div class="d-flex justify-content-between align-items-center">

                            <div>

                                <h5 class="card-title mb-1">
                                    Asset Handover Items
                                </h5>

                                <div class="text-muted small">
                                    Assets being transferred to operations
                                </div>

                            </div>


                            @if(in_array(
                                $assetHandover->status,
                                ['Draft', 'Prepared', 'Rejected']
                            ))

                                <button type="button"
                                        class="btn btn-primary btn-sm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#addAssetItemModal">

                                    <i class="ri-add-line me-1"></i>
                                    Add Asset

                                </button>

                            @endif

                        </div>

                    </div>


                    <div class="card-body p-0">

                        @if($items->count())

                            <div class="table-responsive">

                                <table class="table table-hover align-middle mb-0">

                                    <thead class="table-light">

                                        <tr>

                                            <th class="ps-3">
                                                Asset
                                            </th>

                                            <th>
                                                Location
                                            </th>

                                            <th>
                                                Condition
                                            </th>

                                            <th>
                                                Commissioning
                                            </th>

                                            <th>
                                                Warranty
                                            </th>

                                            <th>
                                                Status
                                            </th>

                                            <th class="text-end pe-3">
                                                Action
                                            </th>

                                        </tr>

                                    </thead>


                                    <tbody>

                                        @foreach($items as $item)

                                            <tr>

                                                {{-- Asset --}}
                                                <td class="ps-3">

                                                    <div class="fw-semibold">

                                                        {{ $item->asset_name }}

                                                    </div>

                                                    @if($item->asset_code)

                                                        <div class="small text-muted">

                                                            {{ $item->asset_code }}

                                                        </div>

                                                    @endif

                                                    @if($item->asset_category)

                                                        <div class="small text-muted">

                                                            {{ $item->asset_category }}

                                                            @if($item->asset_type)
                                                                /
                                                                {{ $item->asset_type }}
                                                            @endif

                                                        </div>

                                                    @endif

                                                </td>


                                                {{-- Location --}}
                                                <td>

                                                    @if($item->location)
                                                        {{ $item->location }}
                                                    @elseif($item->building || $item->floor || $item->zone)
                                                        {{ collect([
                                                            $item->building,
                                                            $item->floor,
                                                            $item->zone
                                                        ])->filter()->implode(' / ') }}
                                                    @else
                                                        -
                                                    @endif

                                                </td>


                                                {{-- Condition --}}
                                                <td>

                                                    <span class="badge
                                                        @switch($item->condition_status)

                                                            @case('New')
                                                                bg-success
                                                                @break

                                                            @case('Good')
                                                                bg-success
                                                                @break

                                                            @case('Fair')
                                                                bg-warning text-dark
                                                                @break

                                                            @case('Damaged')
                                                                bg-danger
                                                                @break

                                                            @default
                                                                bg-secondary

                                                        @endswitch">

                                                        {{ $item->condition_status }}

                                                    </span>

                                                </td>


                                                {{-- Commissioning --}}
                                                <td>

                                                    @if(
                                                        $item->commissioning_status
                                                        === 'Completed'
                                                    )

                                                        <span class="badge bg-success">
                                                            Completed
                                                        </span>

                                                    @elseif(
                                                        $item->commissioning_status
                                                        === 'Pending'
                                                    )

                                                        <span class="badge bg-warning text-dark">
                                                            Pending
                                                        </span>

                                                    @else

                                                        <span class="badge bg-secondary">
                                                            N/A
                                                        </span>

                                                    @endif

                                                </td>


                                                {{-- Warranty --}}
                                                <td>

                                                    @if($item->warranty_available)

                                                        <span class="badge bg-success-subtle text-success">

                                                            <i class="ri-shield-check-line"></i>
                                                            Yes

                                                        </span>

                                                        @if($item->warranty_expiry_date)

                                                            <div class="small text-muted mt-1">

                                                                Till
                                                                {{ $item->warranty_expiry_date->format('d M Y') }}

                                                            </div>

                                                        @endif

                                                    @else

                                                        <span class="text-muted">
                                                            No
                                                        </span>

                                                    @endif

                                                </td>


                                                {{-- Status --}}
                                                <td>

                                                    @php

                                                        $itemStatusClass = match(
                                                            $item->status
                                                        ) {

                                                            'Accepted' =>
                                                                'bg-success',

                                                            'Verified' =>
                                                                'bg-info',

                                                            'Rejected' =>
                                                                'bg-danger',

                                                            default =>
                                                                'bg-warning text-dark',

                                                        };

                                                    @endphp


                                                    <span class="badge {{ $itemStatusClass }}">

                                                        {{ $item->status }}

                                                    </span>

                                                </td>


                                                {{-- Actions --}}
                                                <td class="text-end pe-3">

                                                    <div class="dropdown">

                                                        <button class="btn btn-sm btn-light border"
                                                                type="button"
                                                                data-bs-toggle="dropdown">

                                                            <i class="ri-more-2-fill"></i>

                                                        </button>


                                                        <ul class="dropdown-menu dropdown-menu-end">


                                                            {{-- Verify --}}
                                                            @if(
                                                                in_array(
                                                                    $item->status,
                                                                    ['Pending', 'Rejected']
                                                                )
                                                                &&
                                                                in_array(
                                                                    $assetHandover->status,
                                                                    ['Draft', 'Prepared', 'Rejected']
                                                                )
                                                            )

                                                                <li>

                                                                    <form method="POST"
                                                                          action="{{ route(
                                                                              'admin.projects.handover.asset-handover.items.verify',
                                                                              [$project, $assetHandover, $item]
                                                                          ) }}">

                                                                        @csrf

                                                                        <button type="submit"
                                                                                class="dropdown-item">

                                                                            <i class="ri-search-eye-line me-2"></i>
                                                                            Verify

                                                                        </button>

                                                                    </form>

                                                                </li>

                                                            @endif


                                                            {{-- Accept --}}
                                                            @if(
                                                                $item->status === 'Verified'
                                                                &&
                                                                in_array(
                                                                    $assetHandover->status,
                                                                    ['Draft', 'Prepared', 'Rejected']
                                                                )
                                                            )

                                                                <li>

                                                                    <form method="POST"
                                                                          action="{{ route(
                                                                              'admin.projects.handover.asset-handover.items.accept',
                                                                              [$project, $assetHandover, $item]
                                                                          ) }}">

                                                                        @csrf

                                                                        <button type="submit"
                                                                                class="dropdown-item text-success">

                                                                            <i class="ri-checkbox-circle-line me-2"></i>
                                                                            Accept

                                                                        </button>

                                                                    </form>

                                                                </li>


                                                                <li>
                                                                    <button type="button"
                                                                            class="dropdown-item text-danger"
                                                                            onclick="openRejectItemModal(
                                                                                '{{ $item->id }}'
                                                                            )">

                                                                        <i class="ri-close-circle-line me-2"></i>
                                                                        Reject

                                                                    </button>
                                                                </li>

                                                            @endif


                                                            {{-- Edit --}}
                                                            @if(
                                                                in_array(
                                                                    $assetHandover->status,
                                                                    ['Draft', 'Prepared', 'Rejected']
                                                                )
                                                                &&
                                                                $item->status !== 'Accepted'
                                                            )

                                                                <li>

                                                                    <button type="button"
                                                                            class="dropdown-item"
                                                                            onclick="openEditAssetItemModal(
                                                                                {{ $item->id }}
                                                                            )">

                                                                        <i class="ri-edit-line me-2"></i>
                                                                        Edit

                                                                    </button>

                                                                </li>

                                                            @endif


                                                            {{-- Delete --}}
                                                            @if(
                                                                in_array(
                                                                    $assetHandover->status,
                                                                    ['Draft', 'Prepared', 'Rejected']
                                                                )
                                                                &&
                                                                in_array(
                                                                    $item->status,
                                                                    ['Pending', 'Rejected']
                                                                )
                                                            )

                                                                <li>

                                                                    <form method="POST"
                                                                          action="{{ route(
                                                                              'admin.projects.handover.asset-handover.items.destroy',
                                                                              [$project, $assetHandover, $item]
                                                                          ) }}"
                                                                          onsubmit="return confirm(
                                                                              'Remove this asset item?'
                                                                          );">

                                                                        @csrf
                                                                        @method('DELETE')

                                                                        <button type="submit"
                                                                                class="dropdown-item text-danger">

                                                                            <i class="ri-delete-bin-line me-2"></i>
                                                                            Remove

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

                        @else

                            <div class="text-center py-5">

                                <i class="ri-building-4-line text-muted"
                                   style="font-size: 56px;">
                                </i>

                                <h6 class="mt-3">
                                    No Asset Items
                                </h6>

                                <p class="text-muted mb-3">
                                    Add assets that are being handed over
                                    to the operations team.
                                </p>


                                @if(in_array(
                                    $assetHandover->status,
                                    ['Draft', 'Prepared', 'Rejected']
                                ))

                                    <button type="button"
                                            class="btn btn-primary btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#addAssetItemModal">

                                        <i class="ri-add-line me-1"></i>
                                        Add First Asset

                                    </button>

                                @endif

                            </div>

                        @endif

                    </div>

                </div>


                {{-- Workflow --}}
                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white">

                        <h5 class="card-title mb-0">
                            Workflow Actions
                        </h5>

                    </div>


                    <div class="card-body">

                        <div class="d-flex flex-wrap gap-2">


                            {{-- Submit --}}
                            @if(in_array(
                                $assetHandover->status,
                                ['Draft', 'Prepared', 'Rejected']
                            ))

                                <form method="POST"
                                      action="{{ route(
                                          'admin.projects.handover.asset-handover.submit',
                                          [$project, $assetHandover]
                                      ) }}">

                                    @csrf

                                    <button type="submit"
                                            class="btn btn-primary">

                                        <i class="ri-send-plane-line me-1"></i>
                                        Submit for Review

                                    </button>

                                </form>

                            @endif


                            {{-- Review --}}
                            @if($assetHandover->status === 'Submitted')

                                <form method="POST"
                                      action="{{ route(
                                          'admin.projects.handover.asset-handover.review',
                                          [$project, $assetHandover]
                                      ) }}">

                                    @csrf

                                    <button type="submit"
                                            class="btn btn-info">

                                        <i class="ri-search-eye-line me-1"></i>
                                        Start Review

                                    </button>

                                </form>

                            @endif


                            {{-- Approve --}}
                            @if($assetHandover->status === 'Under Review')

                                <form method="POST"
                                      action="{{ route(
                                          'admin.projects.handover.asset-handover.approve',
                                          [$project, $assetHandover]
                                      ) }}">

                                    @csrf

                                    <button type="submit"
                                            class="btn btn-success"
                                            onclick="return confirm(
                                                'Approve Asset Handover?'
                                            );">

                                        <i class="ri-checkbox-circle-line me-1"></i>
                                        Approve Asset Handover

                                    </button>

                                </form>


                                <button type="button"
                                        class="btn btn-danger"
                                        data-bs-toggle="modal"
                                        data-bs-target="#rejectAssetHandoverModal">

                                    <i class="ri-close-circle-line me-1"></i>
                                    Reject

                                </button>

                            @endif


                            @if($assetHandover->status === 'Approved')

                                <span class="badge bg-success-subtle text-success p-2">

                                    <i class="ri-checkbox-circle-line me-1"></i>

                                    Asset Handover Approved

                                </span>

                            @endif

                        </div>

                    </div>

                </div>


                {{-- Timeline --}}
                <div class="card border-0 shadow-sm">

                    <div class="card-header bg-white">

                        <h5 class="card-title mb-0">
                            Asset Handover Timeline
                        </h5>

                    </div>


                    <div class="card-body">

                        <div class="timeline">


                            {{-- Created --}}
                            <div class="d-flex mb-4">

                                <div class="me-3">

                                    <span class="badge bg-secondary rounded-circle p-2">

                                        <i class="ri-add-line"></i>

                                    </span>

                                </div>

                                <div>

                                    <h6 class="mb-1">
                                        Asset Handover Created
                                    </h6>

                                    <small class="text-muted">

                                        {{ $assetHandover->created_at
                                            ? $assetHandover->created_at->format(
                                                'd M Y, h:i A'
                                            )
                                            : '-' }}

                                    </small>

                                </div>

                            </div>


                            @if($assetHandover->prepared_at)

                                <div class="d-flex mb-4">

                                    <div class="me-3">

                                        <span class="badge bg-primary rounded-circle p-2">

                                            <i class="ri-file-edit-line"></i>

                                        </span>

                                    </div>

                                    <div>

                                        <h6 class="mb-1">
                                            Prepared
                                        </h6>

                                        <small class="text-muted">

                                            {{ $assetHandover->prepared_at->format(
                                                'd M Y, h:i A'
                                            ) }}

                                            —

                                            {{ $assetHandover->preparedBy?->name
                                                ?? 'User' }}

                                        </small>

                                    </div>

                                </div>

                            @endif


                            @if($assetHandover->submitted_at)

                                <div class="d-flex mb-4">

                                    <div class="me-3">

                                        <span class="badge bg-info rounded-circle p-2">

                                            <i class="ri-send-plane-line"></i>

                                        </span>

                                    </div>

                                    <div>

                                        <h6 class="mb-1">
                                            Submitted
                                        </h6>

                                        <small class="text-muted">

                                            {{ $assetHandover->submitted_at->format(
                                                'd M Y, h:i A'
                                            ) }}

                                            —

                                            {{ $assetHandover->submittedBy?->name
                                                ?? 'User' }}

                                        </small>

                                    </div>

                                </div>

                            @endif


                            @if($assetHandover->reviewed_at)

                                <div class="d-flex mb-4">

                                    <div class="me-3">

                                        <span class="badge bg-warning text-dark rounded-circle p-2">

                                            <i class="ri-search-eye-line"></i>

                                        </span>

                                    </div>

                                    <div>

                                        <h6 class="mb-1">
                                            Under Review
                                        </h6>

                                        <small class="text-muted">

                                            {{ $assetHandover->reviewed_at->format(
                                                'd M Y, h:i A'
                                            ) }}

                                            —

                                            {{ $assetHandover->reviewedBy?->name
                                                ?? 'User' }}

                                        </small>

                                    </div>

                                </div>

                            @endif


                            @if($assetHandover->approved_at)

                                <div class="d-flex">

                                    <div class="me-3">

                                        <span class="badge bg-success rounded-circle p-2">

                                            <i class="ri-checkbox-circle-line"></i>

                                        </span>

                                    </div>

                                    <div>

                                        <h6 class="mb-1">
                                            Asset Handover Approved
                                        </h6>

                                        <small class="text-muted">

                                            {{ $assetHandover->approved_at->format(
                                                'd M Y, h:i A'
                                            ) }}

                                            —

                                            {{ $assetHandover->approvedBy?->name
                                                ?? 'User' }}

                                        </small>

                                    </div>

                                </div>

                            @endif


                        </div>

                    </div>

                </div>


            @else

                {{-- No Asset Handover --}}
                <div class="card border-0 shadow-sm">

                    <div class="card-body text-center py-5">

                        <i class="ri-building-4-line text-muted"
                           style="font-size: 64px;">
                        </i>

                        <h5 class="mt-3">
                            Asset Handover Not Created
                        </h5>

                        <p class="text-muted">

                            @if($certificateApproved)

                                The Handover Certificate has been approved.
                                You can now create Asset Handover.

                            @else

                                Asset Handover will become available
                                after the Handover Certificate is approved.

                            @endif

                        </p>


                        @if($certificateApproved)

                            <button type="button"
                                    class="btn btn-primary"
                                    data-bs-toggle="modal"
                                    data-bs-target="#createAssetHandoverModal">

                                <i class="ri-add-line me-1"></i>
                                Create Asset Handover

                            </button>

                        @endif

                    </div>

                </div>

            @endif

        </div>


        {{-- Sidebar --}}
        <div class="col-xl-4">


            {{-- Handover Certificate --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white">

                    <h5 class="card-title mb-0">
                        Handover Certificate
                    </h5>

                </div>


                <div class="card-body">

                    @if($certificate)

                        <div class="d-flex justify-content-between mb-3">

                            <span class="text-muted">
                                Certificate No.
                            </span>

                            <strong>
                                {{ $certificate->certificate_no }}
                            </strong>

                        </div>


                        <div class="d-flex justify-content-between mb-3">

                            <span class="text-muted">
                                Status
                            </span>

                            <span class="badge
                                {{ $certificate->status === 'Approved'
                                    ? 'bg-success'
                                    : 'bg-secondary' }}">

                                {{ $certificate->status }}

                            </span>

                        </div>


                        <div class="d-flex justify-content-between">

                            <span class="text-muted">
                                Approved Date
                            </span>

                            <strong>

                                {{ $certificate->approved_at
                                    ? $certificate->approved_at->format(
                                        'd M Y'
                                    )
                                    : '-' }}

                            </strong>

                        </div>

                    @else

                        <div class="text-center text-muted py-3">

                            Handover Certificate not available.

                        </div>

                    @endif

                </div>

            </div>


            {{-- Item Progress --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white">

                    <h5 class="card-title mb-0">
                        Asset Acceptance
                    </h5>

                </div>


                <div class="card-body">

                    @php

                        $acceptancePercentage =
                            $totalItems > 0
                                ? round(
                                    ($acceptedItems / $totalItems) * 100,
                                    2
                                )
                                : 0;

                    @endphp


                    <div class="d-flex justify-content-between mb-2">

                        <span class="text-muted">
                            Accepted
                        </span>

                        <strong>
                            {{ $acceptedItems }}/{{ $totalItems }}
                        </strong>

                    </div>


                    <div class="progress mb-3"
                         style="height: 8px;">

                        <div class="progress-bar bg-success"
                             style="width: {{ $acceptancePercentage }}%;">
                        </div>

                    </div>


                    @if($itemsReady)

                        <div class="alert alert-success mb-0">

                            <i class="ri-checkbox-circle-line me-1"></i>

                            All asset items have been accepted.

                        </div>

                    @else

                        <div class="text-muted small">

                            All assets must be accepted before
                            Asset Handover approval.

                        </div>

                    @endif

                </div>

            </div>


            {{-- Navigation --}}
            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white">

                    <h5 class="card-title mb-0">
                        Handover Modules
                    </h5>

                </div>


                <div class="card-body p-2">

                    <a href="{{ route(
                        'admin.projects.handover.certificates.index',
                        $project
                    ) }}"
                       class="btn btn-light w-100 text-start mb-2">

                        <i class="ri-file-shield-2-line me-2"></i>
                        Handover Certificate

                    </a>


                    <a href="{{ route(
                        'admin.projects.handover.final-completion.index',
                        $project
                    ) }}"
                       class="btn btn-light w-100 text-start mb-2">

                        <i class="ri-flag-line me-2"></i>
                        Final Completion

                    </a>


                    <a href="{{ route(
                        'admin.projects.handover.final-payment.index',
                        $project
                    ) }}"
                       class="btn btn-light w-100 text-start mb-2">

                        <i class="ri-money-rupee-circle-line me-2"></i>
                        Final Payment

                    </a>


                    <a href="{{ route(
                        'admin.projects.handover.index',
                        $project
                    ) }}"
                       class="btn btn-light w-100 text-start">

                        <i class="ri-dashboard-line me-2"></i>
                        Handover Dashboard

                    </a>

                </div>

            </div>

        </div>

    </div>

</div>


{{-- ========================================================= --}}
{{-- CREATE ASSET HANDOVER MODAL --}}
{{-- ========================================================= --}}

@if($certificateApproved && !$assetHandover)

<div class="modal fade"
     id="createAssetHandoverModal"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog modal-lg">

        <form method="POST"
              action="{{ route(
                  'admin.projects.handover.asset-handover.store',
                  $project
              ) }}">

            @csrf

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title">
                        Create Asset Handover
                    </h5>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal">
                    </button>

                </div>


                <div class="modal-body">

                    <div class="alert alert-success">

                        <i class="ri-checkbox-circle-line me-1"></i>

                        Handover Certificate is Approved.
                        Asset Handover can now be initiated.

                    </div>


                    <div class="row g-3">

                        <div class="col-md-6">

                            <label class="form-label">
                                Title
                                <span class="text-danger">*</span>
                            </label>

                            <input type="text"
                                   name="title"
                                   class="form-control"
                                   value="Operational Asset Handover"
                                   required>

                        </div>


                        <div class="col-md-6">

                            <label class="form-label">
                                Handover Date
                            </label>

                            <input type="date"
                                   name="handover_date"
                                   class="form-control"
                                   value="{{ now()->format('Y-m-d') }}">

                        </div>


                        <div class="col-12">

                            <label class="form-label">
                                Description
                            </label>

                            <textarea name="description"
                                      rows="3"
                                      class="form-control"
                                      placeholder="Describe the assets being transferred to operations..."></textarea>

                        </div>


                        <div class="col-12">

                            <label class="form-label">
                                Handover Statement
                            </label>

                            <textarea name="handover_statement"
                                      rows="4"
                                      class="form-control"
                                      placeholder="Enter formal asset handover statement..."></textarea>

                        </div>


                        <div class="col-12">

                            <label class="form-label">
                                Remarks
                            </label>

                            <textarea name="remarks"
                                      rows="3"
                                      class="form-control"></textarea>

                        </div>

                    </div>

                </div>


                <div class="modal-footer">

                    <button type="button"
                            class="btn btn-light"
                            data-bs-dismiss="modal">

                        Cancel

                    </button>

                    <button type="submit"
                            class="btn btn-primary">

                        <i class="ri-save-line me-1"></i>
                        Create Asset Handover

                    </button>

                </div>

            </div>

        </form>

    </div>

</div>

@endif


{{-- ========================================================= --}}
{{-- ADD ASSET ITEM MODAL --}}
{{-- ========================================================= --}}

@if($assetHandover)

<div class="modal fade"
     id="addAssetItemModal"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog modal-xl">

        <form method="POST"
              action="{{ route(
                  'admin.projects.handover.asset-handover.items.store',
                  [$project, $assetHandover]
              ) }}">

            @csrf

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title">
                        Add Asset
                    </h5>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal">
                    </button>

                </div>


                <div class="modal-body">

                    <div class="row g-3">

                        <div class="col-md-4">

                            <label class="form-label">
                                Existing Asset ID
                            </label>

                            <input type="number"
                                   name="asset_id"
                                   class="form-control"
                                   placeholder="Optional">

                            <div class="form-text">
                                Use this when the operational Asset
                                already exists.
                            </div>

                        </div>


                        <div class="col-md-4">

                            <label class="form-label">
                                Asset Code
                            </label>

                            <input type="text"
                                   name="asset_code"
                                   class="form-control"
                                   placeholder="e.g. HVAC-001">

                        </div>


                        <div class="col-md-4">

                            <label class="form-label">
                                Asset Name
                                <span class="text-danger">*</span>
                            </label>

                            <input type="text"
                                   name="asset_name"
                                   class="form-control"
                                   placeholder="e.g. Central HVAC System"
                                   required>

                        </div>


                        <div class="col-md-6">

                            <label class="form-label">
                                Asset Category
                            </label>

                            <input type="text"
                                   name="asset_category"
                                   class="form-control"
                                   placeholder="e.g. HVAC, Electrical, Fire Safety">

                        </div>


                        <div class="col-md-6">

                            <label class="form-label">
                                Asset Type
                            </label>

                            <input type="text"
                                   name="asset_type"
                                   class="form-control"
                                   placeholder="e.g. Chiller, Generator">

                        </div>


                        <div class="col-md-6">

                            <label class="form-label">
                                Location
                            </label>

                            <input type="text"
                                   name="location"
                                   class="form-control"
                                   placeholder="Asset location">

                        </div>


                        <div class="col-md-3">

                            <label class="form-label">
                                Building
                            </label>

                            <input type="text"
                                   name="building"
                                   class="form-control">

                        </div>


                        <div class="col-md-3">

                            <label class="form-label">
                                Floor
                            </label>

                            <input type="text"
                                   name="floor"
                                   class="form-control">

                        </div>


                        <div class="col-md-3">

                            <label class="form-label">
                                Zone
                            </label>

                            <input type="text"
                                   name="zone"
                                   class="form-control">

                        </div>


                        <div class="col-md-3">

                            <label class="form-label">
                                Unit
                            </label>

                            <input type="text"
                                   name="unit"
                                   class="form-control">

                        </div>


                        <div class="col-md-4">

                            <label class="form-label">
                                Quantity
                                <span class="text-danger">*</span>
                            </label>

                            <input type="number"
                                   name="quantity"
                                   class="form-control"
                                   value="1"
                                   min="0.001"
                                   step="0.001"
                                   required>

                        </div>


                        <div class="col-md-4">

                            <label class="form-label">
                                Condition
                                <span class="text-danger">*</span>
                            </label>

                            <select name="condition_status"
                                    class="form-select"
                                    required>

                                <option value="New">
                                    New
                                </option>

                                <option value="Good">
                                    Good
                                </option>

                                <option value="Fair">
                                    Fair
                                </option>

                                <option value="Requires Attention">
                                    Requires Attention
                                </option>

                                <option value="Damaged">
                                    Damaged
                                </option>

                            </select>

                        </div>


                        <div class="col-md-4">

                            <label class="form-label">
                                Commissioning Status
                                <span class="text-danger">*</span>
                            </label>

                            <select name="commissioning_status"
                                    class="form-select"
                                    required>

                                <option value="Not Applicable">
                                    Not Applicable
                                </option>

                                <option value="Completed">
                                    Completed
                                </option>

                                <option value="Pending">
                                    Pending
                                </option>

                            </select>

                        </div>


                        <div class="col-md-4">

                            <div class="form-check mt-4">

                                <input type="checkbox"
                                       name="warranty_available"
                                       value="1"
                                       class="form-check-input"
                                       id="warranty_available">

                                <label class="form-check-label"
                                       for="warranty_available">

                                    Warranty Available

                                </label>

                            </div>

                        </div>


                        <div class="col-md-4">

                            <label class="form-label">
                                Warranty Expiry
                            </label>

                            <input type="date"
                                   name="warranty_expiry_date"
                                   class="form-control">

                        </div>


                        <div class="col-md-4">

                            <div class="form-check mt-4">

                                <input type="checkbox"
                                       name="documents_available"
                                       value="1"
                                       class="form-check-input"
                                       id="documents_available">

                                <label class="form-check-label"
                                       for="documents_available">

                                    Documents Available

                                </label>

                            </div>

                        </div>


                        <div class="col-12">

                            <label class="form-label">
                                Remarks
                            </label>

                            <textarea name="remarks"
                                      rows="3"
                                      class="form-control"></textarea>

                        </div>

                    </div>

                </div>


                <div class="modal-footer">

                    <button type="button"
                            class="btn btn-light"
                            data-bs-dismiss="modal">

                        Cancel

                    </button>

                    <button type="submit"
                            class="btn btn-primary">

                        <i class="ri-add-line me-1"></i>
                        Add Asset

                    </button>

                </div>

            </div>

        </form>

    </div>

</div>

@endif


{{-- ========================================================= --}}
{{-- EDIT ASSET ITEM MODALS --}}
{{-- ========================================================= --}}

@if($assetHandover)

    @foreach($items as $item)

        @if(
            $item->status !== 'Accepted' &&
            in_array(
                $assetHandover->status,
                ['Draft', 'Prepared', 'Rejected']
            )
        )

            <div class="modal fade"
                 id="editAssetItemModal{{ $item->id }}"
                 tabindex="-1"
                 aria-hidden="true">

                <div class="modal-dialog modal-xl">

                    <form method="POST"
                          action="{{ route(
                              'admin.projects.handover.asset-handover.items.update',
                              [$project, $assetHandover, $item]
                          ) }}">

                        @csrf
                        @method('PUT')

                        <div class="modal-content">

                            <div class="modal-header">

                                <h5 class="modal-title">
                                    Edit Asset
                                </h5>

                                <button type="button"
                                        class="btn-close"
                                        data-bs-dismiss="modal">
                                </button>

                            </div>


                            <div class="modal-body">

                                <div class="row g-3">

                                    <div class="col-md-4">

                                        <label class="form-label">
                                            Existing Asset ID
                                        </label>

                                        <input type="number"
                                               name="asset_id"
                                               class="form-control"
                                               value="{{ $item->asset_id }}">

                                    </div>


                                    <div class="col-md-4">

                                        <label class="form-label">
                                            Asset Code
                                        </label>

                                        <input type="text"
                                               name="asset_code"
                                               class="form-control"
                                               value="{{ $item->asset_code }}">

                                    </div>


                                    <div class="col-md-4">

                                        <label class="form-label">
                                            Asset Name
                                            <span class="text-danger">*</span>
                                        </label>

                                        <input type="text"
                                               name="asset_name"
                                               class="form-control"
                                               value="{{ $item->asset_name }}"
                                               required>

                                    </div>


                                    <div class="col-md-6">

                                        <label class="form-label">
                                            Asset Category
                                        </label>

                                        <input type="text"
                                               name="asset_category"
                                               class="form-control"
                                               value="{{ $item->asset_category }}">

                                    </div>


                                    <div class="col-md-6">

                                        <label class="form-label">
                                            Asset Type
                                        </label>

                                        <input type="text"
                                               name="asset_type"
                                               class="form-control"
                                               value="{{ $item->asset_type }}">

                                    </div>


                                    <div class="col-md-6">

                                        <label class="form-label">
                                            Location
                                        </label>

                                        <input type="text"
                                               name="location"
                                               class="form-control"
                                               value="{{ $item->location }}">

                                    </div>


                                    <div class="col-md-3">

                                        <label class="form-label">
                                            Building
                                        </label>

                                        <input type="text"
                                               name="building"
                                               class="form-control"
                                               value="{{ $item->building }}">

                                    </div>


                                    <div class="col-md-3">

                                        <label class="form-label">
                                            Floor
                                        </label>

                                        <input type="text"
                                               name="floor"
                                               class="form-control"
                                               value="{{ $item->floor }}">

                                    </div>


                                    <div class="col-md-3">

                                        <label class="form-label">
                                            Zone
                                        </label>

                                        <input type="text"
                                               name="zone"
                                               class="form-control"
                                               value="{{ $item->zone }}">

                                    </div>


                                    <div class="col-md-3">

                                        <label class="form-label">
                                            Unit
                                        </label>

                                        <input type="text"
                                               name="unit"
                                               class="form-control"
                                               value="{{ $item->unit }}">

                                    </div>


                                    <div class="col-md-4">

                                        <label class="form-label">
                                            Quantity
                                        </label>

                                        <input type="number"
                                               name="quantity"
                                               class="form-control"
                                               value="{{ $item->quantity }}"
                                               min="0.001"
                                               step="0.001"
                                               required>

                                    </div>


                                    <div class="col-md-4">

                                        <label class="form-label">
                                            Condition
                                        </label>

                                        <select name="condition_status"
                                                class="form-select"
                                                required>

                                            @foreach([
                                                'New',
                                                'Good',
                                                'Fair',
                                                'Damaged',
                                                'Requires Attention'
                                            ] as $condition)

                                                <option value="{{ $condition }}"
                                                    @selected(
                                                        $item->condition_status
                                                        === $condition
                                                    )>

                                                    {{ $condition }}

                                                </option>

                                            @endforeach

                                        </select>

                                    </div>


                                    <div class="col-md-4">

                                        <label class="form-label">
                                            Commissioning
                                        </label>

                                        <select name="commissioning_status"
                                                class="form-select"
                                                required>

                                            @foreach([
                                                'Not Applicable',
                                                'Pending',
                                                'Completed'
                                            ] as $commissioning)

                                                <option value="{{ $commissioning }}"
                                                    @selected(
                                                        $item->commissioning_status
                                                        === $commissioning
                                                    )>

                                                    {{ $commissioning }}

                                                </option>

                                            @endforeach

                                        </select>

                                    </div>


                                    <div class="col-md-4">

                                        <div class="form-check mt-4">

                                            <input type="checkbox"
                                                   name="warranty_available"
                                                   value="1"
                                                   class="form-check-input"
                                                   id="edit_warranty_{{ $item->id }}"
                                                   @checked($item->warranty_available)>

                                            <label class="form-check-label"
                                                   for="edit_warranty_{{ $item->id }}">

                                                Warranty Available

                                            </label>

                                        </div>

                                    </div>


                                    <div class="col-md-4">

                                        <label class="form-label">
                                            Warranty Expiry
                                        </label>

                                        <input type="date"
                                               name="warranty_expiry_date"
                                               class="form-control"
                                               value="{{ $item->warranty_expiry_date?->format('Y-m-d') }}">

                                    </div>


                                    <div class="col-md-4">

                                        <div class="form-check mt-4">

                                            <input type="checkbox"
                                                   name="documents_available"
                                                   value="1"
                                                   class="form-check-input"
                                                   id="edit_documents_{{ $item->id }}"
                                                   @checked($item->documents_available)>

                                            <label class="form-check-label"
                                                   for="edit_documents_{{ $item->id }}">

                                                Documents Available

                                            </label>

                                        </div>

                                    </div>


                                    <div class="col-12">

                                        <label class="form-label">
                                            Remarks
                                        </label>

                                        <textarea name="remarks"
                                                  rows="3"
                                                  class="form-control">{{ $item->remarks }}</textarea>

                                    </div>

                                </div>

                            </div>


                            <div class="modal-footer">

                                <button type="button"
                                        class="btn btn-light"
                                        data-bs-dismiss="modal">

                                    Cancel

                                </button>

                                <button type="submit"
                                        class="btn btn-primary">

                                    <i class="ri-save-line me-1"></i>
                                    Update Asset

                                </button>

                            </div>

                        </div>

                    </form>

                </div>

            </div>

        @endif

    @endforeach

@endif


{{-- ========================================================= --}}
{{-- REJECT ITEM MODAL --}}
{{-- ========================================================= --}}

@if($assetHandover)

<div class="modal fade"
     id="rejectAssetItemModal"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog">

        <form method="POST"
              id="rejectAssetItemForm">

            @csrf

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title">
                        Reject Asset Item
                    </h5>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal">
                    </button>

                </div>


                <div class="modal-body">

                    <label class="form-label">

                        Rejection Reason

                        <span class="text-danger">*</span>

                    </label>

                    <textarea name="rejection_reason"
                              class="form-control"
                              rows="5"
                              required></textarea>

                </div>


                <div class="modal-footer">

                    <button type="button"
                            class="btn btn-light"
                            data-bs-dismiss="modal">

                        Cancel

                    </button>

                    <button type="submit"
                            class="btn btn-danger">

                        <i class="ri-close-circle-line me-1"></i>
                        Reject Item

                    </button>

                </div>

            </div>

        </form>

    </div>

</div>

@endif


{{-- ========================================================= --}}
{{-- REJECT ASSET HANDOVER MODAL --}}
{{-- ========================================================= --}}

@if($assetHandover)

<div class="modal fade"
     id="rejectAssetHandoverModal"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog">

        <form method="POST"
              action="{{ route(
                  'admin.projects.handover.asset-handover.reject',
                  [$project, $assetHandover]
              ) }}">

            @csrf

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title">
                        Reject Asset Handover
                    </h5>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal">
                    </button>

                </div>


                <div class="modal-body">

                    <div class="alert alert-warning">

                        The Asset Handover will be returned for
                        correction and resubmission.

                    </div>


                    <label class="form-label">

                        Rejection Reason

                        <span class="text-danger">*</span>

                    </label>

                    <textarea name="rejection_reason"
                              class="form-control"
                              rows="5"
                              required></textarea>

                </div>


                <div class="modal-footer">

                    <button type="button"
                            class="btn btn-light"
                            data-bs-dismiss="modal">

                        Cancel

                    </button>

                    <button type="submit"
                            class="btn btn-danger">

                        Reject Asset Handover

                    </button>

                </div>

            </div>

        </form>

    </div>

</div>

@endif


@push('scripts')

<script>

function openEditAssetItemModal(itemId)
{
    const modalElement =
        document.getElementById(
            'editAssetItemModal' + itemId
        );

    if (!modalElement) {
        return;
    }

    const modal =
        bootstrap.Modal.getOrCreateInstance(
            modalElement
        );

    modal.show();
}


function openRejectItemModal(itemId)
{
    const form =
        document.getElementById(
            'rejectAssetItemForm'
        );

    if (!form) {
        return;
    }

    form.action =
        "{{ url(
            'admin/projects/' .
            $project->id .
            '/handover/asset-handover'
        ) }}"
        + "/{{ $assetHandover?->id }}"
        + "/items/"
        + itemId
        + "/reject";


    const modalElement =
        document.getElementById(
            'rejectAssetItemModal'
        );

    if (!modalElement) {
        return;
    }

    const modal =
        bootstrap.Modal.getOrCreateInstance(
            modalElement
        );

    modal.show();
}

</script>

@endpush

@endsection