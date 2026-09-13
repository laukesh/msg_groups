@extends('layouts.app')

@section('title', 'Lease Terminations')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">Lease Terminations</h4>
            <div class="text-muted">
                Manage lease termination requests and settlements.
            </div>
        </div>

        <a href="{{ route('admin.leasing.terminations.create') }}"
           class="btn btn-primary">

            <i class="fas fa-plus me-1"></i>
            New Termination

        </a>

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


    <div class="card">

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead>

                        <tr>

                            <th>Termination No.</th>

                            <th>Agreement</th>

                            <th>Termination Type</th>

                            <th>Request Date</th>

                            <th>Effective Date</th>

                            <th>Settlement</th>

                            <th>Status</th>

                            <th width="150">Action</th>

                        </tr>

                    </thead>


                    <tbody>

                    @forelse($terminations as $termination)

                        <tr>

                            <td>

                                <strong>
                                    {{ $termination->termination_no }}
                                </strong>

                            </td>


                            <td>

                                {{ $termination->agreement?->agreement_no ?? '-' }}

                                @if($termination->agreement?->tenant)

                                    <div class="small text-muted">

                                        {{ $termination->agreement->tenant->company_name
                                            ?? $termination->agreement->tenant->name
                                            ?? '-' }}

                                    </div>

                                @endif

                            </td>


                            <td>

                                {{ $termination->termination_type }}

                            </td>


                            <td>

                                {{ $termination->request_date
                                    ? $termination->request_date->format('d M Y')
                                    : '-' }}

                            </td>


                            <td>

                                {{ $termination->effective_date
                                    ? $termination->effective_date->format('d M Y')
                                    : '-' }}

                            </td>


                            <td>

                                ${{ number_format(
                                    $termination->final_settlement_amount ?? 0,
                                    2
                                ) }}

                            </td>


                            <td>

                                @php

                                    $statusClass = match(
                                        $termination->termination_status
                                    ) {

                                        'Draft'
                                            => 'bg-secondary',

                                        'Pending Approval'
                                            => 'bg-warning text-dark',

                                        'Approved'
                                            => 'bg-primary',

                                        'Completed'
                                            => 'bg-success',

                                        'Cancelled'
                                            => 'bg-danger',

                                        default
                                            => 'bg-secondary',

                                    };

                                @endphp

                                <span class="badge {{ $statusClass }}">

                                    {{ $termination->termination_status }}

                                </span>

                            </td>


                            <td>

                                <div class="d-flex gap-1">

                                    <a href="{{ route(
                                        'admin.leasing.terminations.show',
                                        $termination->id
                                    ) }}"
                                       class="btn btn-sm btn-info">

                                        <i class="fas fa-eye"></i>

                                    </a>


                                    @if(
                                        in_array(
                                            $termination->termination_status,
                                            ['Draft', 'Pending Approval']
                                        )
                                    )

                                        <a href="{{ route(
                                            'admin.leasing.terminations.edit',
                                            $termination->id
                                        ) }}"
                                           class="btn btn-sm btn-warning">

                                            <i class="fas fa-edit"></i>

                                        </a>

                                    @endif

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="8"
                                class="text-center py-5 text-muted">

                                No termination requests found.

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        @if($terminations->hasPages())

            <div class="card-footer">

                {{ $terminations->links() }}

            </div>

        @endif

    </div>

</div>

@endsection