{{-- resources/views/admin/assets/areas/types/create.blade.php --}}

@extends('layouts.admin')

@section('title', 'Create Area Type')

@section('content')
<div class="container-fluid">

    <div class="mb-4">
        <h1 class="h3">Create Area Type</h1>
        <p class="text-muted">
            Create a new area classification.
        </p>
    </div>

    @include('admin.assets.areas.partials.errors')

    @include('admin.assets.areas.partials.type-form', [
        'action' => route('admin.assets.areas.types.store'),
        'method' => 'POST',
        'areaType' => null,
    ])

</div>
@endsection


{{-- resources/views/admin/assets/areas/types/edit.blade.php --}}

@extends('layouts.admin')

@section('title', 'Edit Area Type')

@section('content')
<div class="container-fluid">

    <div class="mb-4">
        <h1 class="h3">Edit Area Type</h1>
        <p class="text-muted">
            Update area classification.
        </p>
    </div>

    @include('admin.assets.areas.partials.errors')

    @include('admin.assets.areas.partials.type-form', [
        'action' => route(
            'admin.assets.areas.types.update',
            $areaType
        ),
        'method' => 'PUT',
        'areaType' => $areaType,
    ])

</div>
@endsection
