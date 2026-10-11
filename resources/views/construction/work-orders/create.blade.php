@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">
                Construction Management
            </div>

            <h4>
                Create Work Order
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
                'admin.projects.construction.work-orders.index',
                $project
            ) }}"
            class="btn btn-outline-secondary"
        >
            Back
        </a>

    </div>


    @if($errors->any())

        <div class="alert alert-danger">

            <h6 class="mb-2">
                Please correct the following errors:
            </h6>

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    @if($contracts->isEmpty())

        <div class="alert alert-warning">

            <h6>
                No eligible contracts found
            </h6>

            <p class="mb-0">

                A Work Order can only be created against
                an approved or active procurement contract
                belonging to this project.

            </p>

        </div>

    @else

        <form
            method="POST"
            action="{{ route(
                'admin.projects.construction.work-orders.store',
                $project
            ) }}"
        >

            @csrf


            <div class="card">

                <div class="card-header">

                    <strong>
                        Work Order Details
                    </strong>

                </div>


                <div class="card-body">

                    <div class="alert alert-info">

                        <strong>
                            Work Order Number
                        </strong>

                        <div class="small mt-1">

                            The Work Order Number will be
                            generated automatically when the
                            Work Order is created.

                        </div>

                    </div>


                    @include(
                        'construction.work-orders._form'
                    )

                </div>

            </div>


            <div class="d-flex justify-content-end gap-2 mt-4">

                <a
                    href="{{ route(
                        'admin.projects.construction.work-orders.index',
                        $project
                    ) }}"
                    class="btn btn-outline-secondary"
                >
                    Cancel
                </a>


                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    Create Work Order
                </button>

            </div>

        </form>

    @endif

</div>

@endsection