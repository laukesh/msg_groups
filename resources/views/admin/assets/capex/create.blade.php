@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                Add Asset CAPEX
            </h4>

            <p class="text-muted mb-0">
                Create a new capital expenditure record
            </p>
        </div>

        <a href="{{ route('admin.assets.capex.index') }}"
           class="btn btn-secondary">

            <i class="fas fa-arrow-left"></i>
            Back

        </a>

    </div>

    <div class="card">

        <div class="card-body">

            <form method="POST"
                  action="{{ route('admin.assets.capex.store') }}">

                @include(
                    'admin.assets.capex._form'
                )

                <div class="mt-4">

                    <button type="submit"
                            class="btn btn-primary">

                        <i class="fas fa-save"></i>
                        Save CAPEX

                    </button>

                    <a href="{{ route('admin.assets.capex.index') }}"
                       class="btn btn-light">

                        Cancel

                    </a>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection