@extends('layouts.app')

@section('title', 'Area Revision Details')

@section('content')

<div class="container-fluid">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                <i class="ri-history-line me-1"></i>
                Area Revision Details
            </h4>

            <div class="text-muted">

                REV-{{ str_pad(
                    $revision->revision_number,
                    3,
                    '0',
                    STR_PAD_LEFT
                ) }}

            </div>

        </div>


        <a href="{{ route(
            'admin.assets.revisions.index',
            $revision->propertyArea
        ) }}"
           class="btn btn-outline-secondary">

            <i class="ri-arrow-left-line me-1"></i>

            Back

        </a>

    </div>


    {{-- STATUS --}}
    @php

        $statusClass = match($revision->status) {

            'DRAFT'     => 'bg-secondary',

            'SUBMITTED' => 'bg-warning text-dark',

            'APPROVED'  => 'bg-success',

            'REJECTED'  => 'bg-danger',

            default     => 'bg-dark',

        };

    @endphp


    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <div class="row align-items-center">

                <div class="col-md-8">

                    <h5 class="mb-1">

                        Revision
                        #{{ $revision->revision_number }}

                    </h5>

                    <div class="text-muted">

                        {{ $revision->propertyArea->name
                            ?? $revision->propertyArea->area_name
                            ?? 'Property Area' }}

                    </div>

                </div>


                <div class="col-md-4 text-md-end">

                    <span class="badge {{ $statusClass }} fs-6">

                        {{ $revision->status }}

                    </span>

                </div>

            </div>

        </div>

    </div>


    {{-- AREA COMPARISON --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0">
                Area Comparison
            </h5>

        </div>


        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th>Area Type</th>

                            <th>Previous Area</th>

                            <th></th>

                            <th>New Area</th>

                            <th>Difference</th>

                        </tr>

                    </thead>


                    <tbody>

                        {{-- GROSS --}}
                        <tr>

                            <td>
                                <strong>Gross Area</strong>
                            </td>

                            <td>
                                {{ number_format(
                                    $revision->previous_gross_area ?? 0,
                                    2
                                ) }}
                            </td>

                            <td class="text-center">
                                <i class="ri-arrow-right-line"></i>
                            </td>

                            <td class="fw-semibold text-primary">

                                {{ number_format(
                                    $revision->new_gross_area ?? 0,
                                    2
                                ) }}

                            </td>

                            <td>

                                @php
                                    $difference =
                                        ($revision->new_gross_area ?? 0)
                                        -
                                        ($revision->previous_gross_area ?? 0);
                                @endphp

                                {{ number_format($difference, 2) }}

                            </td>

                        </tr>


                        {{-- NET --}}
                        <tr>

                            <td>
                                <strong>Net Area</strong>
                            </td>

                            <td>
                                {{ number_format(
                                    $revision->previous_net_area ?? 0,
                                    2
                                ) }}
                            </td>

                            <td class="text-center">
                                <i class="ri-arrow-right-line"></i>
                            </td>

                            <td class="fw-semibold text-primary">

                                {{ number_format(
                                    $revision->new_net_area ?? 0,
                                    2
                                ) }}

                            </td>

                            <td>

                                @php
                                    $difference =
                                        ($revision->new_net_area ?? 0)
                                        -
                                        ($revision->previous_net_area ?? 0);
                                @endphp

                                {{ number_format($difference, 2) }}

                            </td>

                        </tr>


                        {{-- RENTABLE --}}
                        <tr>

                            <td>
                                <strong>Rentable Area</strong>
                            </td>

                            <td>
                                {{ number_format(
                                    $revision->previous_rentable_area ?? 0,
                                    2
                                ) }}
                            </td>

                            <td class="text-center">
                                <i class="ri-arrow-right-line"></i>
                            </td>

                            <td class="fw-semibold text-primary">

                                {{ number_format(
                                    $revision->new_rentable_area ?? 0,
                                    2
                                ) }}

                            </td>

                            <td>

                                @php
                                    $difference =
                                        ($revision->new_rentable_area ?? 0)
                                        -
                                        ($revision->previous_rentable_area ?? 0);
                                @endphp

                                {{ number_format($difference, 2) }}

                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    {{-- REVISION INFORMATION --}}
    <div class="row g-4">

        <div class="col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white">

                    <h5 class="mb-0">
                        Revision Information
                    </h5>

                </div>


                <div class="card-body">

                    <dl class="row mb-0">

                        <dt class="col-sm-5">
                            Reason
                        </dt>

                        <dd class="col-sm-7">
                            {{ $revision->reason }}
                        </dd>


                        <dt class="col-sm-5">
                            Revision Date
                        </dt>

                        <dd class="col-sm-7">

                            {{ $revision->revision_date
                                ? \Carbon\Carbon::parse(
                                    $revision->revision_date
                                )->format('d M Y')
                                : '-' }}

                        </dd>


                        <dt class="col-sm-5">
                            Effective From
                        </dt>

                        <dd class="col-sm-7">

                            {{ $revision->effective_from
                                ? \Carbon\Carbon::parse(
                                    $revision->effective_from
                                )->format('d M Y')
                                : '-' }}

                        </dd>


                        <dt class="col-sm-5">
                            Submitted By
                        </dt>

                        <dd class="col-sm-7">

                            {{ $revision->submittedBy->name
                                ?? $revision->submitted_by
                                ?? '-' }}

                        </dd>


                        <dt class="col-sm-5">
                            Approved By
                        </dt>

                        <dd class="col-sm-7">

                            {{ $revision->approvedBy->name
                                ?? $revision->approved_by
                                ?? '-' }}

                        </dd>


                        <dt class="col-sm-5">
                            Approved At
                        </dt>

                        <dd class="col-sm-7">

                            {{ $revision->approved_at
                                ? \Carbon\Carbon::parse(
                                    $revision->approved_at
                                )->format('d M Y H:i')
                                : '-' }}

                        </dd>

                    </dl>

                </div>

            </div>

        </div>


        {{-- REMARKS --}}
        <div class="col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white">

                    <h5 class="mb-0">
                        Remarks
                    </h5>

                </div>


                <div class="card-body">

                    @if($revision->remarks)

                        <p class="mb-0">
                            {{ $revision->remarks }}
                        </p>

                    @else

                        <span class="text-muted">
                            No remarks available.
                        </span>

                    @endif

                </div>

            </div>

        </div>

    </div>


    {{-- ACTIONS --}}
    @if($revision->status === 'DRAFT')

        <div class="card border-0 shadow-sm mt-4">

            <div class="card-body">

                <form method="POST"
                      action="{{ route(
                          'admin.assets.revisions.submit',
                           [
                                                        'area' => $area->id,
                                                        'revision' => $revision->id,
                                                    ]
                      ) }}">

                    @csrf

                    <button type="submit"
                            class="btn btn-warning"
                            onclick="return confirm('Submit this revision for approval?')">

                        <i class="ri-send-plane-line me-1"></i>

                        Submit for Approval

                    </button>

                </form>

            </div>

        </div>

    @endif


    @if($revision->status === 'SUBMITTED')

        <div class="card border-0 shadow-sm mt-4">

            <div class="card-body d-flex gap-2">

                <form method="POST"
                      action="{{ route(
                          'admin.assets.revisions.approve',
                          $revision
                      ) }}">

                    @csrf

                    <button type="submit"
                            class="btn btn-success"
                            onclick="return confirm('Approve this revision? The current property area will be updated.')">

                        <i class="ri-check-line me-1"></i>

                        Approve Revision

                    </button>

                </form>


                <button type="button"
                        class="btn btn-danger"
                        data-bs-toggle="modal"
                        data-bs-target="#rejectRevisionModal">

                    <i class="ri-close-line me-1"></i>

                    Reject Revision

                </button>

            </div>

        </div>


        {{-- REJECT MODAL --}}
        <div class="modal fade"
             id="rejectRevisionModal"
             tabindex="-1">

            <div class="modal-dialog">

                <form method="POST"
                      action="{{ route(
                          'admin.assets.revisions.reject',
                          $revision
                      ) }}">

                    @csrf

                    <div class="modal-content">

                        <div class="modal-header">

                            <h5 class="modal-title">
                                Reject Revision
                            </h5>

                            <button type="button"
                                    class="btn-close"
                                    data-bs-dismiss="modal">
                            </button>

                        </div>


                        <div class="modal-body">

                            <label class="form-label">
                                Rejection Remarks
                            </label>

                            <textarea
                                name="remarks"
                                rows="4"
                                class="form-control"
                                placeholder="Enter rejection reason..."></textarea>

                        </div>


                        <div class="modal-footer">

                            <button type="button"
                                    class="btn btn-secondary"
                                    data-bs-dismiss="modal">

                                Cancel

                            </button>

                            <button type="submit"
                                    class="btn btn-danger">

                                Reject Revision

                            </button>

                        </div>

                    </div>

                </form>

            </div>

        </div>

    @endif

</div>

@endsection