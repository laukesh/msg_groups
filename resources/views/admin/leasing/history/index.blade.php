@extends('layouts.app')

@section('title', 'Lease History')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                Lease History
            </h4>

            <div class="text-muted">
                Complete activity history of lease agreements.
            </div>

        </div>

    </div>


    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


    <div class="card">

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover mb-0">

                    <thead>

                        <tr>

                            <th>Date</th>

                            <th>Agreement</th>

                            <th>Activity</th>

                            <th>Title</th>

                            <th>Performed By</th>

                            <th>Reference</th>

                        </tr>

                    </thead>

                    <tbody>

                    @forelse($histories as $history)

                        <tr>

                            <td>

                                {{ $history->activity_date
                                    ? $history->activity_date->format('d M Y H:i')
                                    : '-' }}

                            </td>


                            <td>

                                {{ $history->agreement?->agreement_no ?? '-' }}

                            </td>


                            <td>

                                @php

                                    $badge = match(
                                        $history->activity_type
                                    ) {

                                        'Escalation'
                                            => 'bg-warning text-dark',

                                        'Renewal'
                                            => 'bg-info',

                                        'Termination'
                                            => 'bg-danger',

                                        'Rent Update'
                                            => 'bg-primary',

                                        'Agreement'
                                            => 'bg-success',

                                        default
                                            => 'bg-secondary',

                                    };

                                @endphp

                                <span class="badge {{ $badge }}">

                                    {{ $history->activity_type }}

                                </span>

                            </td>


                            <td>

                                <div class="fw-semibold">

                                    {{ $history->activity_title }}

                                </div>

                                @if($history->activity_description)

                                    <small class="text-muted">

                                        {{ $history->activity_description }}

                                    </small>

                                @endif

                            </td>


                            <td>

                                {{ $history->performer?->name ?? 'System' }}

                            </td>


                            <td>

                                @if($history->reference_module)

                                    {{ $history->reference_module }}

                                    @if($history->reference_id)

                                        #{{ $history->reference_id }}

                                    @endif

                                @else

                                    -

                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="6"
                                class="text-center py-4 text-muted">

                                No lease history found.

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        @if($histories->hasPages())

            <div class="card-footer">

                {{ $histories->links() }}

            </div>

        @endif

    </div>

</div>

@endsection