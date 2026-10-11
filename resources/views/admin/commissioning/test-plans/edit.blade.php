@extends('layouts.app')

@section('title', 'Edit Commissioning Test Plan')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                <i class="bi bi-pencil-square me-2"></i>
                Edit Commissioning Test Plan
            </h4>

            <div class="text-muted">

                {{ $project->project_code ?? '' }}

                @if(
                    !empty($project->project_code) &&
                    !empty($project->project_name)
                )
                    -
                @endif

                {{ $project->project_name ?? '' }}

            </div>

        </div>


        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.projects.commissioning.test-plans.show',
                    [$project, $plan]
                ) }}"
                class="btn btn-outline-secondary"
            >
                <i class="bi bi-arrow-left me-1"></i>
                Back
            </a>

        </div>

    </div>


    {{-- Errors --}}
    @if($errors->any())

        <div class="alert alert-danger">

            <div class="fw-semibold mb-2">

                <i class="bi bi-exclamation-triangle me-2"></i>

                Please correct the following errors:

            </div>

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- Rejection Notice --}}
    @if($plan->status === 'Rejected')

        <div class="alert alert-danger">

            <div class="fw-semibold">

                <i class="bi bi-x-circle me-2"></i>
                Test Plan Rejected

            </div>

            @if($plan->rejection_reason)

                <div class="mt-2">

                    <strong>
                        Reason:
                    </strong>

                    {{ $plan->rejection_reason }}

                </div>

            @endif

            <div class="small mt-2">

                Update the Test Plan and submit it again
                from the detail page.

            </div>

        </div>

    @endif


    {{-- On Hold Notice --}}
    @if($plan->status === 'On Hold')

        <div class="alert alert-warning">

            <i class="bi bi-pause-circle me-2"></i>

            This Test Plan is currently
            <strong>On Hold</strong>.

            You can update the information and resume the workflow
            from the detail page.

        </div>

    @endif


    {{-- Form --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <strong>
                        {{ $plan->test_plan_no }}
                    </strong>

                    <div class="text-muted small">
                        Update Test Plan Details
                    </div>

                </div>


                @php

                    $statusClass = match($plan->status) {

                        'Draft'
                            => 'bg-secondary',

                        'Submitted'
                            => 'bg-warning text-dark',

                        'Approved'
                            => 'bg-success',

                        'In Progress'
                            => 'bg-info text-dark',

                        'Completed'
                            => 'bg-primary',

                        'Rejected'
                            => 'bg-danger',

                        'On Hold'
                            => 'bg-dark',

                        default
                            => 'bg-secondary',

                    };

                @endphp

                <span class="badge {{ $statusClass }}">
                    {{ $plan->status }}
                </span>

            </div>

        </div>


        <div class="card-body">

            <form
                method="POST"
                action="{{ route(
                    'admin.projects.commissioning.test-plans.update',
                    [$project, $plan]
                ) }}"
            >

                @method('PUT')

                @include(
                    'admin.commissioning.test-plans._form'
                )


                {{-- Buttons --}}
                <div class="border-top mt-4 pt-4">

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        <i class="bi bi-check-lg me-1"></i>
                        Update Test Plan
                    </button>


                    <a
                        href="{{ route(
                            'admin.projects.commissioning.test-plans.show',
                            [$project, $plan]
                        ) }}"
                        class="btn btn-outline-secondary ms-2"
                    >
                        Cancel
                    </a>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection