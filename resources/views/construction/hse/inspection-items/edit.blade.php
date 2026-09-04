@extends('layouts.app')

@section('content')

<div class="container-fluid">


    {{-- Header --}}

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">

                Item:

                <strong>
                    {{ $item->item_number }}
                </strong>

            </div>


            <h3 class="mb-1">
                Edit Checklist Item
            </h3>


            <div class="text-muted">

                Inspection:

                {{ $inspection->inspection_number }}

            </div>

        </div>


        <a
            href="{{ route(
                'admin.projects.construction.hse.inspections.items.show',
                [
                    'project' => $project,
                    'inspection' => $inspection,
                    'item' => $item,
                ]
            ) }}"
            class="btn btn-outline-secondary"
        >

            <i class="bi bi-arrow-left me-1"></i>

            Back

        </a>

    </div>


    {{-- Errors --}}

    @if($errors->any())

        <div class="alert alert-danger">

            <strong>
                Please correct the following errors:
            </strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    @if($inspection->status === 'Closed')

        <div class="alert alert-warning">

            <i class="bi bi-exclamation-triangle me-1"></i>

            This inspection is

            <strong>
                Closed
            </strong>.

            Editing checklist items may affect the inspection record.

        </div>

    @endif


    @include(
        'construction.hse.inspection-items._form',
        [
            'action' => route(
                'admin.projects.construction.hse.inspections.items.update',
                [
                    'project' => $project,
                    'inspection' => $inspection,
                    'item' => $item,
                ]
            ),

            'method' => 'PUT',

            'item' => $item,

            'itemNumber' => $item->item_number,

            'project' => $project,

            'inspection' => $inspection,

            'cancelUrl' => route(
                'admin.projects.construction.hse.inspections.items.show',
                [
                    'project' => $project,
                    'inspection' => $inspection,
                    'item' => $item,
                ]
            ),

            'submitLabel' => 'Update Checklist Item',
        ]
    )

</div>

@endsection