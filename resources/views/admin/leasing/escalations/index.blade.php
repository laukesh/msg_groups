@extends('layouts.app')

@section('title', 'Lease Escalations')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">Lease Escalations</h4>
            <div class="text-muted">
                Manage rent escalation records.
            </div>
        </div>

        <a href="{{ route('admin.leasing.escalations.create') }}"
           class="btn btn-primary">

            <i class="fas fa-plus me-1"></i>
            Create Escalation

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

        <div class="card-header">

            <h5 class="mb-0">
                Escalation List
            </h5>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover mb-0">

                    <thead>

                        <tr>

                            <th>Escalation No.</th>

                            <th>Agreement</th>

                            <th>Tenant</th>

                            <th>Effective From</th>

                            <th>Previous Rent</th>

                            <th>Escalation</th>

                            <th>Revised Rent</th>

                            <th>Status</th>

                            <th class="text-end">Action</th>

                        </tr>

                    </thead>


                    <tbody>

                    @forelse($escalations as $escalation)

                        <tr>

                            <td>

                                #{{ $escalation->escalation_no }}

                            </td>


                            <td>

                                {{ $escalation->agreement?->agreement_no ?? '-' }}

                            </td>


                            <td>

                                {{ $escalation->agreement?->tenant?->company_name ?? '-' }}

                            </td>


                            <td>

                                {{ $escalation->effective_from
                                    ? $escalation->effective_from->format('d M Y')
                                    : '-' }}

                            </td>


                            <td>

                                ${{ number_format(
                                    $escalation->previous_rent ?? 0,
                                    2
                                ) }}

                            </td>


                            <td>

                                @if($escalation->escalation_type === 'Percentage')

                                    {{ number_format(
                                        $escalation->escalation_value,
                                        2
                                    ) }}%

                                @else

                                    ${{ number_format(
                                        $escalation->escalation_value,
                                        2
                                    ) }}

                                @endif

                            </td>


                            <td class="fw-semibold">

                                ${{ number_format(
                                    $escalation->revised_rent ?? 0,
                                    2
                                ) }}

                            </td>


                            <td>

                                @if($escalation->status === 'Pending')

                                    <span class="badge bg-warning text-dark">
                                        Pending
                                    </span>

                                @elseif($escalation->status === 'Applied')

                                    <span class="badge bg-success">
                                        Applied
                                    </span>

                                @else

                                    <span class="badge bg-secondary">
                                        Cancelled
                                    </span>

                                @endif

                            </td>


                            <td class="text-end">

                                <a href="{{ route(
                                    'admin.leasing.escalations.show',
                                    $escalation->id
                                ) }}"
                                   class="btn btn-sm btn-outline-primary">

                                    <i class="fas fa-eye"></i>

                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="9"
                                class="text-center py-4 text-muted">

                                No lease escalations found.

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        @if($escalations->hasPages())

            <div class="card-footer">

                {{ $escalations->links() }}

            </div>

        @endif

    </div>

</div>

@endsection