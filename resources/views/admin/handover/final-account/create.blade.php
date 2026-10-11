@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <div class="d-flex align-items-center gap-2 mb-4">

        <a href="{{ route('admin.projects.handover.final-account.index', $project) }}"
           class="btn btn-light btn-sm">
            <i class="ri-arrow-left-line"></i>
        </a>

        <div>
            <h4 class="mb-0">Create Final Accounts</h4>

            <div class="text-muted small">
                {{ $project->project_name ?? 'Project' }}
                · {{ $handover->handover_no }}
            </div>
        </div>

    </div>


    @if($contracts->count())

        <div class="card border-0 shadow-sm">

            <div class="card-header bg-white">

                <div class="d-flex justify-content-between align-items-center">

                    <div>
                        <h5 class="mb-1">
                            Procurement Contracts
                        </h5>

                        <small class="text-muted">
                            A separate Final Account will be created
                            automatically for each contract.
                        </small>
                    </div>

                    <span class="badge bg-primary">
                        {{ $contracts->count() }} Contract(s)
                    </span>

                </div>

            </div>


            <div class="card-body">

                <div class="alert alert-info">

                    <i class="ri-information-line me-1"></i>

                    Final Accounts will automatically source:

                    <strong>
                        Contract Value, Approved Variations,
                        Approved Claims and Approved/Processed Payments
                    </strong>

                    for each procurement contract.

                </div>


                <div class="table-responsive">

                    <table class="table table-bordered align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th width="60">
                                    #
                                </th>

                                <th>
                                    Procurement Contract
                                </th>

                                <th>
                                    Contractor / Bidder
                                </th>

                                <th class="text-end">
                                    Contract Value
                                </th>

                                <th class="text-center">
                                    Final Account
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($contracts as $index => $contract)

                                @php

                                    $currency =
                                        $contract->currency
                                        ?? $contract->contract_currency
                                        ?? '';

                                    $value =
                                        $contract->contract_amount
                                        ?? $contract->contract_value
                                        ?? $contract->total_amount
                                        ?? $contract->amount
                                        ?? 0;

                                    $contractNo =
                                        $contract->contract_no
                                        ?? $contract->contract_number
                                        ?? ('Contract #'.$contract->id);

                                @endphp

                                <tr>

                                    <td>
                                        {{ $index + 1 }}
                                    </td>

                                    <td>

                                        <div class="fw-semibold">
                                            {{ $contractNo }}
                                        </div>

                                        @if($contract->contract_title)
                                            <div class="small text-muted">
                                                {{ $contract->contract_title }}
                                            </div>
                                        @endif

                                    </td>


                                    <td>

                                        @if($contract->bidder)

                                            {{ $contract->bidder->name
                                                ?? $contract->bidder->company_name
                                                ?? '-' }}

                                        @else

                                            <span class="text-muted">
                                                -
                                            </span>

                                        @endif

                                    </td>


                                    <td class="text-end">

                                        <strong>
                                            {{ $currency }}
                                            {{ number_format((float) $value, 2) }}
                                        </strong>

                                    </td>


                                    <td class="text-center">

                                        <span class="badge bg-warning-subtle text-warning">
                                            Will Be Created
                                        </span>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            </div>


            <div class="card-footer bg-white">

                <div class="d-flex justify-content-between align-items-center">

                    <a href="{{ route(
                        'admin.projects.handover.final-account.index',
                        $project
                    ) }}"
                       class="btn btn-light">

                        <i class="ri-arrow-left-line me-1"></i>

                        Cancel

                    </a>


                    <form method="POST"
                          action="{{ route(
                              'admin.projects.handover.final-account.store',
                              $project
                          ) }}">

                        @csrf

                        <button type="submit"
                                class="btn btn-primary"
                                onclick="return confirm(
                                    'Create Final Accounts for all {{ $contracts->count() }} procurement contracts?'
                                )">

                            <i class="ri-add-line me-1"></i>

                            Create All Final Accounts
                            ({{ $contracts->count() }})

                        </button>

                    </form>

                </div>

            </div>

        </div>

    @else

        <div class="card border-0 shadow-sm">

            <div class="card-body text-center py-5">

                <i class="ri-checkbox-circle-line fs-1 text-success"></i>

                <h5 class="mt-3">
                    All Contracts Already Have Final Accounts
                </h5>

                <p class="text-muted mb-3">
                    There are no remaining procurement contracts
                    requiring a Final Account for this handover.
                </p>

                <a href="{{ route(
                    'admin.projects.handover.final-account.index',
                    $project
                ) }}"
                   class="btn btn-primary">

                    Back to Final Accounts

                </a>

            </div>

        </div>

    @endif

</div>
@endsection