@extends('layouts.app')

@section('title', 'Add Asset Income')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4>Add Asset Income</h4>

            <div class="text-muted">
                {{ $asset->asset_code }} -
                {{ $asset->asset_name }}
            </div>

        </div>

        <a href="{{ route(
            'admin.assets.incomes.index',
            $asset->id
        ) }}"
           class="btn btn-secondary">

            <i class="fas fa-arrow-left"></i>
            Back

        </a>

    </div>


    @include('admin.assets.incomes._form', [
        'income' => null,
        'action' => route(
            'admin.assets.incomes.store',
            $asset->id
        ),
        'method' => 'POST'
    ])

</div>

@endsection