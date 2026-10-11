@extends('layouts.app')

@section('content')

@php
    /*
    |--------------------------------------------------------------------------
    | Safe Defaults
    |--------------------------------------------------------------------------
    */

    $progress = $progress ?? null;

    $workOrders = $workOrders ?? collect();

    $users = $users ?? collect();
@endphp


<div class="container-fluid">

    {{-- ================================================================
         HEADER
    ================================================================= --}}

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">
                Construction Management
            </div>

            <h4 class="mb-1">
                Add Progress Update
            </h4>

            <div class="text-muted">

                {{ $project->project_name }}

                @if($project->project_code)

                    · {{ $project->project_code }}

                @endif

            </div>

        </div>


        <a
            href="{{ route(
                'admin.projects.construction.progress.index',
                [
                    'project' => $project,
                ]
            ) }}"
            class="btn btn-outline-secondary"
        >

            <i class="bi bi-arrow-left me-1"></i>

            Back

        </a>

    </div>


    {{-- ================================================================
         VALIDATION ERRORS
    ================================================================= --}}

    @if($errors->any())

        <div class="alert alert-danger">

            <div class="fw-semibold mb-2">
                Please correct the following errors:
            </div>

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- ================================================================
         NO WORK ORDERS
    ================================================================= --}}

    @if($workOrders->isEmpty())

        <div class="alert alert-warning">

            <div class="d-flex align-items-start">

                <i class="bi bi-exclamation-triangle me-2"></i>

                <div>

                    <h6 class="mb-1">
                        No Work Orders Available
                    </h6>

                    <p class="mb-0">
                        Create a Work Order for this project before
                        recording construction progress.
                    </p>

                </div>

            </div>

        </div>


        <div>

            <a
                href="{{ route(
                    'admin.projects.construction.progress.index',
                    [
                        'project' => $project,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >

                <i class="bi bi-arrow-left me-1"></i>

                Back to Progress Register

            </a>

        </div>


    @else


        {{-- ============================================================
             FORM
        ============================================================= --}}

        <form
            method="POST"
            action="{{ route(
                'admin.projects.construction.progress.store',
                [
                    'project' => $project,
                ]
            ) }}"
        >

            @csrf


            <div class="card">

                <div class="card-header">

                    <strong>
                        Progress Update
                    </strong>

                </div>


                <div class="card-body">


                    {{-- Progress Number Information --}}

                    <div class="alert alert-info mb-4">

                        <div class="fw-semibold">

                            <i class="bi bi-info-circle me-1"></i>

                            Progress Number

                        </div>

                        <div class="small mt-1">

                            The Progress Number will be generated
                            automatically when this update is saved.

                        </div>

                    </div>


                    {{-- =================================================
                         FORM FIELDS
                    ================================================== --}}

                    @include(
                        'construction.progress._form',
                        [
                            'project' => $project,
                            'progress' => $progress,
                            'workOrders' => $workOrders,
                            'users' => $users,
                        ]
                    )

                </div>

            </div>


            {{-- ========================================================
                 BUTTONS
            ========================================================= --}}

            <div class="d-flex justify-content-end gap-2 mt-4">

                <a
                    href="{{ route(
                        'admin.projects.construction.progress.index',
                        [
                            'project' => $project,
                        ]
                    ) }}"
                    class="btn btn-outline-secondary"
                >

                    Cancel

                </a>


                <button
                    type="submit"
                    class="btn btn-primary"
                >

                    <i class="bi bi-check-lg me-1"></i>

                    Save Progress

                </button>

            </div>

        </form>

    @endif

</div>

@endsection