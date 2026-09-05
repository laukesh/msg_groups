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
                Claims
            </h4>

            <div class="text-muted">

                {{ $contract->contract_code }}

                <span class="mx-1">
                    |
                </span>

                {{ $contract->contract_title }}

            </div>

        </div>


        <div class="d-flex gap-2">

            <a href="{{ route(
                'admin.projects.contract-management.contracts.show',
                [$project, $contract]
            ) }}"
               class="btn btn-outline-secondary">

                <i class="fa fa-arrow-left me-1"></i>

                Contract

            </a>


            <a href="{{ route(
                'admin.projects.contract-management.contracts.claims.create',
                [$project, $contract]
            ) }}"
               class="btn btn-primary">

                <i class="fa fa-plus-lg me-1"></i>

                Add Claim

            </a>

        </div>

    </div>


    {{-- Summary --}}

    <div class="row g-3 mb-4">

        <div class="col-xl-3 col-md-6">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Total Claims
                    </div>

                    <h3 class="mb-0">
                        {{ $summary['total'] }}
                    </h3>

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Under Review
                    </div>

                    <h3 class="mb-0">
                        {{ $summary['under_review'] }}
                    </h3>

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Claimed Amount
                    </div>

                    <h5 class="mb-0">

                        {{ $contract->currency ?? 'USD' }}

                        {{ number_format(
                            $summary['claimed_amount'],
                            2
                        ) }}

                    </h5>

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Approved Amount
                    </div>

                    <h5 class="mb-0">

                        {{ $contract->currency ?? 'USD' }}

                        {{ number_format(
                            $summary['approved_amount'],
                            2
                        ) }}

                    </h5>

                </div>

            </div>

        </div>

    </div>


    {{-- Claims Table --}}

    <div class="card shadow-sm">

        <div class="card-header bg-white">

            <h5 class="mb-0">
                Claim Register
            </h5>

        </div>


        <div class="card-body p-0">

            @if($claims->count())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th class="px-3">
                                    Claim No.
                                </th>

                                <th>
                                    Date
                                </th>

                                <th>
                                    Type
                                </th>

                                <th>
                                    Title
                                </th>

                                <th>
                                    Submitted By
                                </th>

                                <th class="text-end">
                                    Claimed
                                </th>

                                <th class="text-end">
                                    Approved
                                </th>

                                <th>
                                    Status
                                </th>

                                <th class="text-end">
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($claims as $claim)

                                @php

                                    $statusClass = match(
                                        $claim->status
                                    ) {

                                        'Approved',
                                        'Partially Approved'
                                            => 'success',

                                        'Rejected',
                                        'Withdrawn'
                                            => 'danger',

                                        'Under Review',
                                        'Under Negotiation'
                                            => 'warning',

                                        'Closed'
                                            => 'secondary',

                                        default
                                            => 'primary',

                                    };

                                @endphp


                                <tr>

                                    <td class="px-3">

                                        <strong>
                                            {{ $claim->claim_number }}
                                        </strong>

                                    </td>


                                    <td>

                                        {{ $claim->claim_date
                                            ? $claim->claim_date
                                                ->format('d M Y')
                                            : '—'
                                        }}

                                    </td>


                                    <td>

                                        {{ $claim->claim_type }}

                                    </td>


                                    <td>

                                        <div class="fw-semibold">

                                            {{ $claim->title }}

                                        </div>

                                    </td>


                                    <td>

                                        {{ $claim->submitted_by_party
                                            ?? '—'
                                        }}

                                    </td>


                                    <td class="text-end">

                                        {{ $claim->currency ?? 'USD' }}

                                        {{ number_format(
                                            (float)
                                            $claim->claimed_amount,
                                            2
                                        ) }}

                                    </td>


                                    <td class="text-end">

                                        {{ $claim->currency ?? 'USD' }}

                                        {{ number_format(
                                            (float)
                                            $claim->approved_amount,
                                            2
                                        ) }}

                                    </td>


                                    <td>

                                        <span class="badge bg-{{ $statusClass }}">

                                            {{ $claim->status }}

                                        </span>

                                    </td>


                                    <td class="text-end">

                                        <a href="{{ route(
                                            'admin.projects.contract-management.contracts.claims.edit',
                                            [$project, $contract, $claim]
                                        ) }}"
                                           class="btn btn-sm btn-outline-primary">

                                            <i class="fa fa-edit"></i>

                                        </a>


                                        <form method="POST"
                                              action="{{ route(
                                                  'admin.projects.contract-management.contracts.claims.destroy',
                                                  [$project, $contract, $claim]
                                              ) }}"
                                              class="d-inline">

                                            @csrf

                                            @method('DELETE')

                                            <button type="submit"
                                                    class="btn btn-sm btn-outline-danger"
                                                    onclick="return confirm('Delete this claim?');">

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

                    <h5>
                        No Claims Found
                    </h5>

                    <p class="text-muted mb-3">
                        No claims have been registered against this contract.
                    </p>

                    <a href="{{ route(
                        'admin.projects.contract-management.contracts.claims.create',
                        [$project, $contract]
                    ) }}"
                       class="btn btn-primary">

                        <i class="bi bi-plus-lg me-1"></i>

                        Add First Claim

                    </a>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection