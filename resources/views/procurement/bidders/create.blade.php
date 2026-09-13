@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">Add Bidder</h4>

            <div class="text-muted">
                Create a reusable procurement bidder profile.
            </div>
        </div>

        <a href="{{ route('admin.procurement.bidders.index') }}"
           class="btn btn-outline-secondary">
            Back
        </a>

    </div>


    @if($errors->any())

        <div class="alert alert-danger">

            <strong>Please correct the following:</strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

    @endif


    <form method="POST"
          action="{{ route('admin.procurement.bidders.store') }}">

        @csrf

        <div class="card">

            <div class="card-header">
                <strong>Bidder Information</strong>
            </div>

            <div class="card-body">

                @include(
                    'procurement.bidders._form',
                    [
                        'procurementBidder' => null
                    ]
                )

            </div>

            <div class="card-footer d-flex justify-content-end gap-2">

                <a href="{{ route('admin.procurement.bidders.index') }}"
                   class="btn btn-outline-secondary">
                    Cancel
                </a>

                <button type="submit"
                        class="btn btn-primary">
                    Save Bidder
                </button>

            </div>

        </div>

    </form>

</div>

@endsection