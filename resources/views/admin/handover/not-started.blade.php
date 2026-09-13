@extends('layouts.app')

@section('title', 'Handover Not Started')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                Handover &amp; Closeout
            </h4>

            <div class="text-muted">
                {{ $project->project_name }}

                @if(!empty($project->project_code))
                    <span class="mx-1">•</span>
                    {{ $project->project_code }}
                @endif
            </div>
        </div>

        <div>

            <a
                href="{{ route('admin.handover.index') }}"
                class="btn btn-outline-secondary">

                <i class="ri-arrow-left-line me-1"></i>
                Back to Projects

            </a>

        </div>

    </div>


    {{-- Not Started --}}
    <div class="card border">

        <div class="card-body text-center py-5">

            <div
                class="mb-3 text-primary"
                style="font-size: 56px;">

                <i class="ri-checkbox-circle-line"></i>

            </div>


            <h4 class="mb-2">
                Handover Not Started
            </h4>


            <p class="text-muted mb-4">

                The Handover &amp; Closeout process has not yet
                been started for this project.

            </p>


            <form
                method="POST"
                action="{{ route(
                    'admin.handover.projects.start',
                    $project
                ) }}"
                class="d-inline">

                @csrf

                <button
                    type="submit"
                    class="btn btn-primary">

                    <i class="ri-play-line me-1"></i>
                    Start Handover

                </button>

            </form>


            <a
                href="{{ route('admin.handover.index') }}"
                class="btn btn-light ms-2">

                Cancel

            </a>

        </div>

    </div>

</div>

@endsection