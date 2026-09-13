@extends('layouts.app')

@section('title', 'Proposal Units')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h1 class="h3 mb-1">
                Proposal Units
            </h1>

            <p class="text-muted mb-0">
                Manage units associated with proposals.
            </p>
        </div>

        <a
            href="{{ route('admin.assets.proposal_units.create') }}"
            class="btn btn-primary"
        >
            + Add Proposal Unit
        </a>

    </div>


    {{-- Success Message --}}
    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


    {{-- Validation / Error Messages --}}
    @if($errors->any())

        <div class="alert alert-danger">

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- Proposal Units Table --}}
    <div class="card">

        <div class="card-header">

            <h5 class="mb-0">
                Proposal Unit List
            </h5>

        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-bordered table-hover mb-0">

                    <thead class="table-light">

                        <tr>
                            <th>ID</th>
                            <th>Proposal</th>
                            <th>Unit</th>
                            <th>Proposed Rent</th>
                            <th>CAM Rate</th>
                            <th>Security Deposit</th>
                            <th>Rent Free Days</th>
                            <th>Fitout Period</th>
                            <th>Actions</th>
                        </tr>

                    </thead>

                    <tbody>

                    @forelse($items as $item)

                        <tr>

                            {{-- ID --}}
                            <td>
                                {{ $item->id }}
                            </td>


                            {{-- Proposal --}}
                            <td>

                                @if($item->proposal)

                                    {{ $item->proposal->title }}

                                @else

                                    {{ $item->proposal_id }}

                                @endif

                            </td>


                            {{-- Unit --}}
                            <td>

                                @if($item->unit)

                                    {{ $item->unit->unit_no }}

                                    @if($item->unit->shop_name)

                                        <small class="text-muted d-block">
                                            {{ $item->unit->shop_name }}
                                        </small>

                                    @endif

                                @else

                                    {{ $item->unit_id }}

                                @endif

                            </td>


                            {{-- Proposed Rent --}}
                            <td>
                                {{ number_format((float) $item->proposed_rent, 2) }}
                            </td>


                            {{-- CAM Rate --}}
                            <td>
                                {{ number_format((float) $item->proposed_cam_rate, 2) }}
                            </td>


                            {{-- Security Deposit --}}
                            <td>
                                {{ number_format((float) $item->proposed_security_deposit, 2) }}
                            </td>


                            {{-- Rent Free Days --}}
                            <td>
                                {{ $item->rent_free_days ?? 0 }}
                            </td>


                            {{-- Fitout Period --}}
                            <td>
                                {{ $item->fitout_period_days ?? 0 }} days
                            </td>


                            {{-- Actions --}}
                            <td class="text-nowrap">

                                <a
                                    href="{{ route(
                                        'admin.assets.proposal_units.show',
                                        $item->id
                                    ) }}"
                                    class="btn btn-sm btn-info"
                                >
                                    View
                                </a>


                                <a
                                    href="{{ route(
                                        'admin.assets.proposal_units.edit',
                                        $item->id
                                    ) }}"
                                    class="btn btn-sm btn-primary"
                                >
                                    Edit
                                </a>


                                <form
                                    action="{{ route(
                                        'admin.assets.proposal_units.destroy',
                                        $item->id
                                    ) }}"
                                    method="POST"
                                    class="d-inline"
                                >

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="btn btn-sm btn-danger"
                                        onclick="return confirm('Are you sure you want to delete this proposal unit?')"
                                    >
                                        Delete
                                    </button>

                                </form>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="9"
                                class="text-center py-4"
                            >
                                No proposal units found.
                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    {{-- Pagination --}}
    @if($items->hasPages())

        <div class="mt-4">
            {{ $items->links() }}
        </div>

    @endif

</div>

@endsection