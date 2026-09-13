@extends('layouts.app')

@section('title', 'Tenant History')

@section('content')

<div class="container-fluid py-3">

    {{-- =========================================================
         HEADER
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1 fw-bold">
                Tenant History
            </h4>

            <p class="text-muted mb-0">

                {{ $tenant->company_name }}

                <span class="mx-1">•</span>

                {{ $tenant->tenant_code }}

            </p>

        </div>

        <a href="{{ route(
            'admin.tenants.show',
            $tenant->id
        ) }}"
           class="btn btn-outline-secondary">

            <i class="fas fa-arrow-left me-1"></i>

            Tenant Details

        </a>

    </div>


    {{-- =========================================================
         HISTORY
    ========================================================== --}}

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white py-3">

            <div class="d-flex
                        justify-content-between
                        align-items-center">

                <div>

                    <h5 class="mb-1">

                        <i class="fas fa-history
                                  text-primary
                                  me-2"></i>

                        Activity History

                    </h5>

                    <small class="text-muted">

                        {{ $history->count() }}
                        activity record(s)

                    </small>

                </div>

            </div>

        </div>


        <div class="card-body">

            @forelse($history as $activity)

                <div class="d-flex mb-4">

                    {{-- =================================================
                         TIMELINE ICON
                    ================================================== --}}

                    <div class="me-3">

                        @php

                            $icon = match(
                                strtolower(
                                    $activity->activity_type
                                )
                            ) {

                                'tenant created'
                                    => 'fa-user-plus',

                                'tenant updated'
                                    => 'fa-user-edit',

                                'document uploaded'
                                    => 'fa-file-upload',

                                'document updated'
                                    => 'fa-file-alt',

                                'emergency contact added'
                                    => 'fa-phone',

                                'emergency contact updated'
                                    => 'fa-phone-alt',

                                'note added'
                                    => 'fa-sticky-note',

                                'note updated'
                                    => 'fa-edit',

                                'address added'
                                    => 'fa-map-marker-alt',

                                'address updated'
                                    => 'fa-map-marker',

                                'bank account added'
                                    => 'fa-university',

                                default
                                    => 'fa-history',
                            };

                        @endphp

                        <div class="rounded-circle
                                    bg-primary
                                    text-white
                                    d-flex
                                    align-items-center
                                    justify-content-center"
                             style="
                                width:45px;
                                height:45px;
                             ">

                            <i class="fas {{ $icon }}"></i>

                        </div>

                    </div>


                    {{-- =================================================
                         ACTIVITY CONTENT
                    ================================================== --}}

                    <div class="flex-grow-1">

                        <div class="d-flex
                                    justify-content-between
                                    align-items-start">

                            <div>

                                <h6 class="mb-1 fw-bold">

                                    {{ $activity->activity_type }}

                                </h6>

                                @if(
                                    $activity->description
                                )

                                    <p class="mb-1 text-muted">

                                        {{ $activity->description }}

                                    </p>

                                @endif

                            </div>


                            {{-- DATE --}}

                            <small class="text-muted ms-3">

                                @if(
                                    $activity->activity_date
                                )

                                    {{ $activity
                                        ->activity_date
                                        ->format(
                                            'd M Y, h:i A'
                                        ) }}

                                @else

                                    -

                                @endif

                            </small>

                        </div>


                        {{-- =================================================
                             REFERENCE
                        ================================================== --}}

                        @if(
                            $activity->reference_module
                        )

                            <div class="small text-muted mt-2">

                                <i class="fas fa-link me-1"></i>

                                {{ $activity->reference_module }}

                                @if(
                                    $activity->reference_id
                                )

                                    <span class="mx-1">
                                        #
                                    </span>

                                    {{ $activity->reference_id }}

                                @endif

                            </div>

                        @endif


                        {{-- =================================================
                             PERFORMED BY
                        ================================================== --}}

                        @if(
                            $activity->performer
                        )

                            <div class="small text-muted mt-1">

                                <i class="fas fa-user me-1"></i>

                                By:

                                <strong>

                                    {{ $activity
                                        ->performer
                                        ->name }}

                                </strong>

                            </div>

                        @endif

                    </div>

                </div>


                @if(!$loop->last)

                    <hr class="my-3">

                @endif

            @empty

                <div class="text-center
                            text-muted
                            py-5">

                    <i class="fas fa-history
                              fa-3x
                              d-block
                              mb-3">
                    </i>

                    <h6>
                        No history found
                    </h6>

                    <p class="mb-0">

                        Tenant activities will appear here
                        as actions are performed.

                    </p>

                </div>

            @endforelse

        </div>

    </div>

</div>

@endsection