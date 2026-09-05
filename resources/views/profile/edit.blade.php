@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')

<div class="container-fluid py-4">

    <div class="row justify-content-center">

        <div class="col-md-8 col-lg-7">

            <div class="card shadow-sm border-0">

                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-pencil-square me-2"></i>
                        Update Profile
                    </h5>
                </div>

                <div class="card-body">

                    <form method="POST"
                          action="{{ route('profile.update') }}">

                        @csrf
                        @method('PUT')

                        {{-- Name --}}
                        <div class="mb-3">

                            <label class="form-label">
                                Name
                            </label>

                            <input type="text"
                                   name="name"
                                   value="{{ old('name', auth()->user()->name) }}"
                                   class="form-control @error('name') is-invalid @enderror"
                                   required>

                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        {{-- Email --}}
                        <div class="mb-4">

                            <label class="form-label">
                                Email
                            </label>

                            <input type="email"
                                   name="email"
                                   value="{{ old('email', auth()->user()->email) }}"
                                   class="form-control @error('email') is-invalid @enderror"
                                   required>

                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        <div class="d-flex gap-2">

                            <button type="submit"
                                    class="btn btn-primary">

                                <i class="bi bi-save me-1"></i>
                                Update Profile

                            </button>

                            <a href="{{ route('profile.show') }}"
                               class="btn btn-secondary">

                                Cancel

                            </a>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection