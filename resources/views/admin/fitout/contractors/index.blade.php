@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">Contractors</h4>

            <p class="text-muted mb-0">
                Manage fit-out contractors
            </p>
        </div>

        <a href="{{ route('admin.fitout.contractors.create') }}"
           class="btn btn-primary">

            <i class="bi bi-plus-lg"></i>
            Add Contractor

        </a>

    </div>


    {{-- Success Message --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- Error Message --}}
    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- Validation Errors --}}
    @if($errors->any())

        <div class="alert alert-danger">

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- Search / Filter --}}
    <div class="card mb-4">

        <div class="card-body">

            <form method="GET"
                  action="{{ route('admin.fitout.contractors.index') }}">

                <div class="row g-3">

                    <div class="col-md-5">

                        <label class="form-label">
                            Search
                        </label>

                        <input type="text"
                               name="search"
                               class="form-control"
                               placeholder="Contractor code, name, contact, mobile..."
                               value="{{ request('search') }}">

                    </div>


                    <div class="col-md-3">

                        <label class="form-label">
                            Status
                        </label>

                        <select name="status"
                                class="form-select">

                            <option value="">
                                All Status
                            </option>

                            <option value="Pending"
                                {{ request('status') === 'Pending' ? 'selected' : '' }}>
                                Pending
                            </option>

                            <option value="Approved"
                                {{ request('status') === 'Approved' ? 'selected' : '' }}>
                                Approved
                            </option>

                            <option value="Rejected"
                                {{ request('status') === 'Rejected' ? 'selected' : '' }}>
                                Rejected
                            </option>

                            <option value="Suspended"
                                {{ request('status') === 'Suspended' ? 'selected' : '' }}>
                                Suspended
                            </option>

                            <option value="Expired"
                                {{ request('status') === 'Expired' ? 'selected' : '' }}>
                                Expired
                            </option>

                        </select>

                    </div>


                    <div class="col-md-4 d-flex align-items-end gap-2">

                        <button type="submit"
                                class="btn btn-primary">

                            Search

                        </button>

                        <a href="{{ route('admin.fitout.contractors.index') }}"
                           class="btn btn-secondary">

                            Reset

                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- Contractors Table --}}
    <div class="card">

        <div class="card-header">

            <div class="d-flex justify-content-between align-items-center">

                <strong>
                    All Contractors
                </strong>

                <span class="text-muted">

                    {{ $contractors->total() }} contractors

                </span>

            </div>

        </div>
        <style type="text/css">
.fitout-action-dropdown {
    position: static !important;
}

.fitout-action-menu {
    z-index: 99999 !important;
}
        </style>

        <div class="card-body p-0">

            <div class="table-responsive fitout-table-wrapper">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th>
                                #
                            </th>

                            <th>
                                Contractor
                            </th>

                            <th>
                                Contact
                            </th>

                            <th>
                                Email
                            </th>

                            <th>
                                Insurance
                            </th>

                            <th>
                                Safety
                            </th>

                            <th>
                                Status
                            </th>

                            <th class="text-end">
                                Actions
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($contractors as $contractor)

                            <tr>

                                {{-- Serial --}}
                                <td>

                                    {{ $contractors->firstItem() + $loop->index }}

                                </td>


                                {{-- Contractor --}}
                                <td>

                                    <div>

                                        <strong>
                                            {{ $contractor->contractor_name }}
                                        </strong>

                                    </div>

                                    <small class="text-muted">

                                        {{ $contractor->contractor_code }}

                                    </small>

                                </td>


                                {{-- Contact --}}
                                <td>

                                    {{ $contractor->contact_person ?? '-' }}

                                    @if($contractor->mobile)

                                        <br>

                                        <small class="text-muted">

                                            {{ $contractor->mobile }}

                                        </small>

                                    @endif

                                </td>


                                {{-- Email --}}
                                <td>

                                    {{ $contractor->email ?? '-' }}

                                </td>


                                {{-- Insurance --}}
                                <td>

                                    @if($contractor->insurance_expiry)

                                        @if($contractor->insurance_expiry->isPast())

                                            <span class="badge bg-danger">
                                                Expired
                                            </span>

                                        @elseif(
                                            $contractor->insurance_expiry->diffInDays(now()) <= 30
                                        )

                                            <span class="badge bg-warning text-dark">
                                                Expiring Soon
                                            </span>

                                        @else

                                            <span class="badge bg-success">
                                                Valid
                                            </span>

                                        @endif

                                        <br>

                                        <small class="text-muted">

                                            {{ $contractor->insurance_expiry->format('d M Y') }}

                                        </small>

                                    @else

                                        <span class="text-muted">
                                            Not Provided
                                        </span>

                                    @endif

                                </td>


                                {{-- Safety --}}
                                <td>

                                    @if($contractor->safety_induction_date)

                                        <span class="badge bg-success">
                                            Completed
                                        </span>

                                        <br>

                                        <small class="text-muted">

                                            {{ $contractor->safety_induction_date->format('d M Y') }}

                                        </small>

                                    @else

                                        <span class="badge bg-warning text-dark">
                                            Pending
                                        </span>

                                    @endif

                                </td>


                                {{-- Status --}}
                                <td>

                                    @if($contractor->status === 'Approved')

                                        <span class="badge bg-success">
                                            Approved
                                        </span>

                                    @elseif($contractor->status === 'Pending')

                                        <span class="badge bg-warning text-dark">
                                            Pending
                                        </span>

                                    @elseif($contractor->status === 'Rejected')

                                        <span class="badge bg-danger">
                                            Rejected
                                        </span>

                                    @elseif($contractor->status === 'Suspended')

                                        <span class="badge bg-dark">
                                            Suspended
                                        </span>

                                    @elseif($contractor->status === 'Expired')

                                        <span class="badge bg-secondary">
                                            Expired
                                        </span>

                                    @else

                                        <span class="badge bg-secondary">
                                            {{ $contractor->status }}
                                        </span>

                                    @endif

                                </td>


                                {{-- Actions --}}
                                <td class="text-end">

                         <div class="dropdown fitout-action-dropdown">

                            <button
                                type="button"
                                class="btn btn-sm btn-secondary dropdown-toggle"
                                data-bs-toggle="dropdown"
                                aria-expanded="false">

                                Actions

                            </button>

                            <ul class="dropdown-menu dropdown-menu-end">

                                <li>
                                    <a class="dropdown-item"
                                       href="{{ route('admin.fitout.contractors.show', $contractor->id) }}">

                                        View

                                    </a>
                                </li>

                                <li>
                                    <a class="dropdown-item"
                                       href="{{ route('admin.fitout.contractors.edit', $contractor->id) }}">

                                        Edit

                                    </a>
                                </li>


                                @if($contractor->status === 'Pending')

                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>

                                    <li>

                                        <form method="POST"
                                              action="{{ route('admin.fitout.contractors.approve', $contractor->id) }}"
                                              onsubmit="return confirm('Are you sure you want to approve this contractor?');">

                                            @csrf

                                            <button type="submit"
                                                    class="dropdown-item text-success">

                                                Approve

                                            </button>

                                        </form>

                                    </li>

                                    <li>

                                        <form method="POST"
                                              action="{{ route('admin.fitout.contractors.reject', $contractor->id) }}"
                                              onsubmit="return confirm('Are you sure you want to reject this contractor?');">

                                            @csrf

                                            <button type="submit"
                                                    class="dropdown-item text-danger">

                                                Reject

                                            </button>

                                        </form>

                                    </li>

                                @elseif($contractor->status === 'Approved')

                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>

                                    <li>

                                        <form method="POST"
                                              action="{{ route('admin.fitout.contractors.suspend', $contractor->id) }}"
                                              onsubmit="return confirm('Are you sure you want to suspend this contractor?');">

                                            @csrf

                                            <button type="submit"
                                                    class="dropdown-item text-warning">

                                                Suspend

                                            </button>

                                        </form>

                                    </li>

                                @endif

                            </ul>

                        </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="8"
                                    class="text-center py-5">

                                    <div class="text-muted">

                                        No contractors found.

                                    </div>

                                    <a href="{{ route('admin.fitout.contractors.create') }}"
                                       class="btn btn-primary mt-3">

                                        Add Contractor

                                    </a>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- Pagination --}}
        @if($contractors->hasPages())

            <div class="card-footer">

                {{ $contractors->links() }}

            </div>

        @endif

    </div>

</div>
<script>

document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('.fitout-action-dropdown').forEach(function (dropdown) {

        const button = dropdown.querySelector('[data-bs-toggle="dropdown"]');
        const menu = dropdown.querySelector('.dropdown-menu');

        if (!button || !menu) {
            return;
        }

        let placeholder = document.createElement('div');

        placeholder.style.display = 'none';

        menu.parentNode.insertBefore(
            placeholder,
            menu
        );


        dropdown.addEventListener(
            'show.bs.dropdown',
            function () {

                /*
                |--------------------------------------------------------------------------
                | Save original position
                |--------------------------------------------------------------------------
                */

                menu._originalParent = menu.parentNode;


                /*
                |--------------------------------------------------------------------------
                | Move dropdown to body
                |--------------------------------------------------------------------------
                */

                document.body.appendChild(menu);


                /*
                |--------------------------------------------------------------------------
                | Position dropdown
                |--------------------------------------------------------------------------
                */

                const buttonRect =
                    button.getBoundingClientRect();


                menu.style.position = 'fixed';

                menu.style.zIndex = '99999';

                menu.style.display = 'block';

                menu.style.top =
                    buttonRect.bottom + 4 + 'px';


                /*
                |--------------------------------------------------------------------------
                | Right align with button
                |--------------------------------------------------------------------------
                */

                const menuWidth =
                    menu.offsetWidth;


                let left =
                    buttonRect.right - menuWidth;


                /*
                |--------------------------------------------------------------------------
                | Prevent going outside viewport
                |--------------------------------------------------------------------------
                */

                if (left < 5) {
                    left = 5;
                }


                if (
                    left + menuWidth >
                    window.innerWidth - 5
                ) {

                    left =
                        window.innerWidth -
                        menuWidth -
                        5;
                }


                menu.style.left =
                    left + 'px';

            }
        );


        dropdown.addEventListener(
            'shown.bs.dropdown',
            function () {

                /*
                |--------------------------------------------------------------------------
                | Recalculate after Bootstrap finishes positioning
                |--------------------------------------------------------------------------
                */

                const buttonRect =
                    button.getBoundingClientRect();


                const menuWidth =
                    menu.offsetWidth;


                let left =
                    buttonRect.right - menuWidth;


                if (left < 5) {
                    left = 5;
                }


                if (
                    left + menuWidth >
                    window.innerWidth - 5
                ) {

                    left =
                        window.innerWidth -
                        menuWidth -
                        5;
                }


                menu.style.position = 'fixed';

                menu.style.top =
                    buttonRect.bottom + 4 + 'px';

                menu.style.left =
                    left + 'px';

            }
        );


        dropdown.addEventListener(
            'hide.bs.dropdown',
            function () {

                /*
                |--------------------------------------------------------------------------
                | Return menu to original location
                |--------------------------------------------------------------------------
                */

                if (menu._originalParent) {

                    menu._originalParent.appendChild(
                        menu
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | Clear inline positioning
                |--------------------------------------------------------------------------
                */

                menu.style.position = '';

                menu.style.top = '';

                menu.style.left = '';

                menu.style.zIndex = '';

                menu.style.display = '';

            }
        );

    });

});

</script>
@endsection
