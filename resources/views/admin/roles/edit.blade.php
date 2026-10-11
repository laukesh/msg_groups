@extends('layouts.app')

@section('title', 'Edit Role')

@section('content')

<div class="container-fluid">

    {{-- ========================================================= --}}
    {{-- HEADER --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                Edit Role
            </h4>

            <div class="text-muted">
                Update role details and manage permissions
            </div>
        </div>

        <a href="{{ route('admin.roles.index') }}"
           class="btn btn-secondary">

            <i class="fas fa-arrow-left"></i>
            Back to Roles

        </a>

    </div>


    {{-- ========================================================= --}}
    {{-- SUCCESS MESSAGE --}}
    {{-- ========================================================= --}}

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            <i class="fas fa-check-circle me-1"></i>

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- VALIDATION ERRORS --}}
    {{-- ========================================================= --}}

    @if($errors->any())

        <div class="alert alert-danger alert-dismissible fade show">

            <i class="fas fa-exclamation-circle me-1"></i>

            <strong>Please fix the following errors:</strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- ROLE FORM --}}
    {{-- ========================================================= --}}

    <div class="card">

        <div class="card-header">

            <h5 class="mb-0">

                <i class="fas fa-user-shield me-1"></i>

                Edit Role: {{ $role->name }}

            </h5>

        </div>


        <div class="card-body">

            <form method="POST"
                  action="{{ route(
                      'admin.roles.update',
                      $role->id
                  ) }}">

                @csrf

                @method('PUT')


                {{-- ================================================= --}}
                {{-- ROLE NAME --}}
                {{-- ================================================= --}}

                <div class="mb-4">

                    <label for="name"
                           class="form-label">

                        Role Name
                        <span class="text-danger">*</span>

                    </label>

                    <input type="text"
                           id="name"
                           name="name"
                           class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name', $role->name) }}"
                           placeholder="Enter role name"
                           required>

                    @error('name')

                        <div class="invalid-feedback">

                            {{ $message }}

                        </div>

                    @enderror

                </div>


                {{-- ================================================= --}}
                {{-- PERMISSIONS --}}
                {{-- ================================================= --}}

                <div class="mb-4">

                    <label class="form-label">

                        Permissions

                    </label>

                    <div class="card border">

                        <div class="card-body">

                            @if($permissions->count())

                                <div class="row">

                                    @foreach($permissions as $p)

                                        <div class="col-md-4 col-lg-3 mb-3">

                                            <div class="form-check">

                                                <input type="checkbox"
                                                       class="form-check-input"
                                                       id="permission_{{ $p->id }}"
                                                       name="permissions[]"
                                                       value="{{ $p->name }}"
                                                       {{ $role->hasPermissionTo($p->name) ? 'checked' : '' }}>

                                                <label class="form-check-label"
                                                       for="permission_{{ $p->id }}">

                                                    {{ $p->name }}

                                                </label>

                                            </div>

                                        </div>

                                    @endforeach

                                </div>

                            @else

                                <div class="text-muted text-center py-3">

                                    <i class="fas fa-lock fa-2x mb-2"></i>

                                    <div>
                                        No permissions available.
                                    </div>

                                </div>

                            @endif

                        </div>

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- BUTTONS --}}
                {{-- ================================================= --}}

                <div class="d-flex justify-content-end gap-2">

                    <a href="{{ route('admin.roles.index') }}"
                       class="btn btn-secondary">

                        <i class="fas fa-times"></i>
                        Cancel

                    </a>

                    <button type="submit"
                            class="btn btn-primary">

                        <i class="fas fa-save"></i>
                        Update Role

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection