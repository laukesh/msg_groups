@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">
                Construction Management
            </div>

            <h4 class="mb-1">
                Construction Variation
            </h4>

            <div class="text-muted">
                {{ $variation->variation_number }}
            </div>

        </div>


        <div class="d-flex gap-2">

            @if(
                in_array(
                    $variation->status,
                    [
                        'Draft',
                        'Rejected',
                    ],
                    true
                )
            )

                <a
                    href="{{ route(
                        'admin.projects.construction.variations.edit',
                        [
                            'project' => $project,
                            'variation' => $variation,
                        ]
                    ) }}"
                    class="btn btn-primary"
                >
                    Edit
                </a>

            @endif

            @if($variation->status === 'Draft')

                <form
                    method="POST"
                    action="{{ route(
                        'admin.projects.construction.variations.submit',
                        [
                            'project' => $project,
                            'variation' => $variation,
                        ]
                    ) }}"
                    class="d-inline"
                >

                    @csrf

                    <button
                        type="submit"
                        class="btn btn-primary"
                        onclick="
                            return confirm(
                                'Submit this variation for approval?'
                            );
                        "
                    >
                        Submit for Approval
                    </button>

                </form>

            @endif

            @if($variation->status === 'Submitted')

                <form
                    method="POST"
                    action="{{ route(
                        'admin.projects.construction.variations.approve',
                        [
                            'project' => $project,
                            'variation' => $variation,
                        ]
                    ) }}"
                    class="d-inline"
                >

                    @csrf

                    <button
                        type="submit"
                        class="btn btn-success"
                        onclick="
                            return confirm(
                                'Approve this variation?'
                            );
                        "
                    >
                        Approve
                    </button>

                </form>

            @endif

            @if($variation->status === 'Submitted')

                <button
                    type="button"
                    class="btn btn-outline-danger"
                    data-bs-toggle="modal"
                    data-bs-target="#rejectVariationModal"
                >
                    Reject
                </button>

            @endif


            <a
                href="{{ route(
                    'admin.projects.construction.variations.index',
                    $project
                ) }}"
                class="btn btn-outline-secondary"
            >
                Back
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


    @php

        $statusClass =
            match($variation->status) {

                'Approved' =>
                    'bg-success',

                'Submitted' =>
                    'bg-primary',

                'Rejected' =>
                    'bg-danger',

                default =>
                    'bg-secondary',

            };

    @endphp


    <div class="row g-4">

        <div class="col-lg-8">

            <div class="card mb-4">

                <div class="card-header">

                    <strong>
                        Variation Details
                    </strong>

                </div>


                <div class="card-body">

                    <div class="row g-4">

                        <div class="col-md-6">

                            <div class="text-muted small">
                                Variation Number
                            </div>

                            <div class="fw-semibold">
                                {{ $variation->variation_number }}
                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small">
                                Date
                            </div>

                            <div class="fw-semibold">

                                {{
                                    $variation->variation_date
                                        ?->format('d-m-Y')
                                    ?? '—'
                                }}

                            </div>

                        </div>


                        <div class="col-md-12">

                            <div class="text-muted small">
                                Title
                            </div>

                            <div class="fw-semibold">
                                {{ $variation->title }}
                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small">
                                Variation Type
                            </div>

                            <div class="fw-semibold">
                                {{ $variation->variation_type }}
                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small">
                                Status
                            </div>

                            <div class="mt-1">

                                <span
                                    class="badge {{ $statusClass }}"
                                >
                                    {{ $variation->status }}
                                </span>

                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small">
                                Contract
                            </div>

                            <div class="fw-semibold">

                                {{
                                    $variation->contract
                                        ?->contract_number
                                    ?? '—'
                                }}

                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small">
                                Work Order
                            </div>

                            <div class="fw-semibold">

                                {{
                                    $variation->workOrder
                                        ?->work_order_number
                                    ?? '—'
                                }}

                            </div>

                        </div>


                        <div class="col-md-12">

                            <div class="text-muted small">
                                Amount
                            </div>

                            <div class="fs-3 fw-semibold">

                                {{
                                    number_format(
                                        (float)
                                        $variation->amount,
                                        2
                                    )
                                }}

                                {{ $variation->currency }}

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            <div class="card mb-4">

                <div class="card-header">
                    <strong>Description</strong>
                </div>

                <div class="card-body">

                    {!! nl2br(
                        e(
                            $variation->description
                            ?? 'No description.'
                        )
                    ) !!}

                </div>

            </div>


            <div class="card mb-4">

                <div class="card-header">
                    <strong>Reason</strong>
                </div>

                <div class="card-body">

                    {!! nl2br(
                        e(
                            $variation->reason
                            ?? 'No reason provided.'
                        )
                    ) !!}

                </div>

            </div>


            <div class="card">

                <div class="card-header">
                    <strong>Remarks</strong>
                </div>

                <div class="card-body">

                    {!! nl2br(
                        e(
                            $variation->remarks
                            ?? 'No remarks.'
                        )
                    ) !!}

                </div>

            </div>

        </div>

        @if(
            $variation->status === 'Rejected'
            &&
            $variation->rejection_remarks
        )

            <div class="card border-danger mt-4">

                <div class="card-header text-danger">

                    <strong>
                        Rejection Reason
                    </strong>

                </div>

                <div class="card-body">

                    {!! nl2br(
                        e(
                            $variation->rejection_remarks
                        )
                    ) !!}

                </div>

            </div>

        @endif


        <div class="col-lg-4">

            <div class="card mb-4">

                <div class="card-header">
                    <strong>Workflow</strong>
                </div>

                <div class="card-body">

                    <div class="text-muted small">
                        Submitted At
                    </div>

                    <div class="fw-semibold mb-3">

                        {{
                            $variation->submitted_at
                                ?->format('d-m-Y H:i')
                            ?? '—'
                        }}

                    </div>


                    <div class="text-muted small">
                        Approved At
                    </div>

                    <div class="fw-semibold mb-3">

                        {{
                            $variation->approved_at
                                ?->format('d-m-Y H:i')
                            ?? '—'
                        }}

                    </div>


                    <div class="text-muted small">
                        Rejected At
                    </div>

                    <div class="fw-semibold">

                        {{
                            $variation->rejected_at
                                ?->format('d-m-Y H:i')
                            ?? '—'
                        }}

                    </div>

                </div>

            </div>


            @if(
                in_array(
                    $variation->status,
                    [
                        'Draft',
                        'Rejected',
                    ],
                    true
                )
            )

                <div class="card border-danger">

                    <div class="card-header text-danger">

                        <strong>
                            Danger Zone
                        </strong>

                    </div>


                    <div class="card-body">

                        <p class="small text-muted">
                            Only Draft or Rejected variations
                            can be deleted.
                        </p>


                        <form
                            method="POST"
                            action="{{ route(
                                'admin.projects.construction.variations.destroy',
                                [
                                    'project' => $project,
                                    'variation' => $variation,
                                ]
                            ) }}"
                            onsubmit="
                                return confirm(
                                    'Are you sure you want to delete this variation?'
                                );
                            "
                        >

                            @csrf

                            @method('DELETE')


                            <button
                                type="submit"
                                class="btn btn-outline-danger w-100"
                            >
                                Delete Variation
                            </button>

                        </form>

                    </div>

                </div>

            @endif

        </div>

    </div>

</div>
@if($variation->status === 'Submitted')

    <div
        class="modal fade"
        id="rejectVariationModal"
        tabindex="-1"
        aria-hidden="true"
    >

        <div class="modal-dialog">

            <div class="modal-content">

                <form
                    method="POST"
                    action="{{ route(
                        'admin.projects.construction.variations.reject',
                        [
                            'project' => $project,
                            'variation' => $variation,
                        ]
                    ) }}"
                >

                    @csrf


                    <div class="modal-header">

                        <h5 class="modal-title">
                            Reject Variation
                        </h5>

                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                        ></button>

                    </div>


                    <div class="modal-body">

                        <div class="mb-3">

                            <label class="form-label">

                                Rejection Remarks

                                <span class="text-danger">
                                    *
                                </span>

                            </label>

                            <textarea
                                name="rejection_remarks"
                                class="form-control"
                                rows="5"
                                required
                                placeholder="Please provide the reason for rejection..."
                            ></textarea>

                        </div>

                    </div>


                    <div class="modal-footer">

                        <button
                            type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal"
                        >
                            Cancel
                        </button>


                        <button
                            type="submit"
                            class="btn btn-danger"
                        >
                            Reject Variation
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

@endif
@endsection