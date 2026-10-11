@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="mb-4">

        <h3>
            Upload Land Document
        </h3>

        <p class="text-muted">

            {{ $land->land_code }}
            -
            {{ $land->land_name }}

        </p>

    </div>


    @if($errors->any())

        <div class="alert alert-danger">

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    <form
        method="POST"
        action="{{ route(
            'admin.land.lands.documents.store',
            $land
        ) }}"
        enctype="multipart/form-data"
    >

        @csrf


        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Document Information
                </strong>

            </div>


            <div class="card-body">

                <div class="row">


                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Document Type *
                        </label>

                        <select
                            name="document_type"
                            class="form-select"
                            required
                        >

                            <option value="">
                                Select Document Type
                            </option>

                            @foreach([
                                'Title Deed',
                                'Sale Deed',
                                'Ownership Proof',
                                'Survey Plan',
                                'Land Map',
                                'Legal Report',
                                'Technical Report',
                                'Environmental Report',
                                'Zoning Certificate',
                                'Development Permission',
                                'Acquisition Agreement',
                                'Government Approval',
                                'Other'
                            ] as $type)

                                <option
                                    value="{{ $type }}"
                                    @selected(
                                        old('document_type') === $type
                                    )
                                >
                                    {{ $type }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Document Number
                        </label>

                        <input
                            type="text"
                            name="document_number"
                            class="form-control"
                            value="{{ old('document_number') }}"
                        >

                    </div>


                    <div class="col-md-12 mb-3">

                        <label class="form-label">
                            Document Title *
                        </label>

                        <input
                            type="text"
                            name="title"
                            class="form-control"
                            value="{{ old('title') }}"
                            required
                        >

                    </div>


                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Document Date
                        </label>

                        <input
                            type="date"
                            name="document_date"
                            class="form-control"
                            value="{{ old('document_date') }}"
                        >

                    </div>


                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Expiry Date
                        </label>

                        <input
                            type="date"
                            name="expiry_date"
                            class="form-control"
                            value="{{ old('expiry_date') }}"
                        >

                    </div>


                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Approval Status *
                        </label>

                        <select
                            name="approval_status"
                            class="form-select"
                            required
                        >

                            @foreach([
                                'Pending',
                                'Under Review',
                                'Approved',
                                'Rejected'
                            ] as $status)

                                <option
                                    value="{{ $status }}"
                                    @selected(
                                        old(
                                            'approval_status',
                                            'Pending'
                                        ) === $status
                                    )
                                >
                                    {{ $status }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Document Owner
                        </label>

                        <select
                            name="owner_id"
                            class="form-select"
                        >

                            <option value="">
                                Select Owner
                            </option>

                            @foreach(
                                \App\Models\User::orderBy('name')->get()
                                as $user
                            )

                                <option
                                    value="{{ $user->id }}"
                                    @selected(
                                        old('owner_id') == $user->id
                                    )
                                >
                                    {{ $user->name }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-12 mb-3">

                        <label class="form-label">
                            Description
                        </label>

                        <textarea
                            name="description"
                            rows="4"
                            class="form-control"
                        >{{ old('description') }}</textarea>

                    </div>


                    <div class="col-md-12 mb-3">

                        <label class="form-label">
                            File *
                        </label>

                        <input
                            type="file"
                            name="file"
                            class="form-control"
                            required
                        >

                        <small class="text-muted">
                            Maximum file size: 50 MB
                        </small>

                    </div>


                    <div class="col-md-12 mb-3">

                        <label class="form-label">
                            Remarks
                        </label>

                        <textarea
                            name="remarks"
                            rows="3"
                            class="form-control"
                        >{{ old('remarks') }}</textarea>

                    </div>

                </div>

            </div>

        </div>


        <div class="d-flex justify-content-end">

            <a
                href="{{ route(
                    'admin.land.lands.documents.index',
                    $land
                ) }}"
                class="btn btn-secondary me-2"
            >
                Cancel
            </a>


            <button
                type="submit"
                class="btn btn-primary"
            >
                Upload Document
            </button>

        </div>

    </form>

</div>

@endsection