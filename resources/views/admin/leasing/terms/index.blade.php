@extends('layouts.app')

@section('title', 'Lease Terms')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">Lease Terms</h4>
            <div class="text-muted">
                Manage commercial and operational terms of active lease agreements.
            </div>
        </div>

        <a href="{{ route('admin.leasing.terms.create') }}"
           class="btn btn-primary">

            <i class="fas fa-plus me-1"></i>
            Add Lease Terms

        </a>

    </div>


    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            <i class="fas fa-check-circle me-1"></i>

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            <i class="fas fa-exclamation-circle me-1"></i>

            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    <div class="card">

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover table-bordered mb-0">

                    <thead class="table-light">

                        <tr>

                            <th width="60">#</th>

                            <th>Agreement No.</th>

                            <th>Tenant</th>

                            <th>Lock-in</th>

                            <th>Notice</th>

                            <th>Escalation</th>

                            <th>Billing</th>

                            <th>Maintenance</th>

                            <th width="170">Action</th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($terms as $term)

                            <tr>

                                <td>
                                    {{ $terms->firstItem() + $loop->index }}
                                </td>


                                <td>

                                    @if($term->agreement)

                                        <a href="{{ route(
                                            'admin.leasing.agreements.show',
                                            $term->agreement->id
                                        ) }}"
                                           class="fw-semibold text-decoration-none">

                                            {{ $term->agreement->agreement_no }}

                                        </a>

                                    @else

                                        -

                                    @endif

                                </td>


                                <td>

                                    {{ $term->agreement?->tenant?->company_name ?? '-' }}

                                </td>


                                <td>

                                    {{ $term->lock_in_period_months ?? 0 }}
                                    Months

                                </td>


                                <td>

                                    {{ $term->notice_period_days ?? 0 }}
                                    Days

                                </td>


                                <td>

                                    {{ $term->escalation_frequency ?? '-' }}

                                    @if(
                                        $term->escalation_percentage !== null
                                    )

                                        <br>

                                        <small class="text-muted">

                                            {{ number_format(
                                                $term->escalation_percentage,
                                                2
                                            ) }}%

                                        </small>

                                    @endif

                                </td>


                                <td>

                                    {{ $term->billing_cycle ?? '-' }}

                                </td>


                                <td>

                                    <span class="badge
                                        @if($term->maintenance_responsibility === 'Mall')
                                            bg-primary
                                        @elseif($term->maintenance_responsibility === 'Tenant')
                                            bg-warning text-dark
                                        @else
                                            bg-secondary
                                        @endif">

                                        {{ $term->maintenance_responsibility ?? '-' }}

                                    </span>

                                </td>


                                <td>

                                    <div class="d-flex gap-1">

                                        <a href="{{ route(
                                            'admin.leasing.terms.show',
                                            $term->id
                                        ) }}"
                                           class="btn btn-sm btn-info"
                                           title="View">

                                            <i class="fas fa-eye"></i>

                                        </a>


                                        <a href="{{ route(
                                            'admin.leasing.terms.edit',
                                            $term->id
                                        ) }}"
                                           class="btn btn-sm btn-primary"
                                           title="Edit">

                                            <i class="fas fa-edit"></i>

                                        </a>


                                        <form method="POST"
                                              action="{{ route(
                                                  'admin.leasing.terms.destroy',
                                                  $term->id
                                              ) }}"
                                              onsubmit="return confirm(
                                                  'Are you sure you want to delete these lease terms?'
                                              );">

                                            @csrf

                                            @method('DELETE')

                                            <button type="submit"
                                                    class="btn btn-sm btn-danger"
                                                    title="Delete">

                                                <i class="fas fa-trash"></i>

                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="9"
                                    class="text-center py-5">

                                    <i class="fas fa-file-contract fa-2x text-muted mb-2"></i>

                                    <div class="text-muted">
                                        No lease terms found.
                                    </div>

                                    <a href="{{ route(
                                        'admin.leasing.terms.create'
                                    ) }}"
                                       class="btn btn-primary btn-sm mt-3">

                                        Add Lease Terms

                                    </a>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        @if($terms->hasPages())

            <div class="card-footer">

                {{ $terms->links() }}

            </div>

        @endif

    </div>

</div>

@endsection