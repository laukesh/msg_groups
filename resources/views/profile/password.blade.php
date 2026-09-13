@extends('layouts.app')

@section('title', 'Change Password')

@section('content')

<div class="container-fluid py-4">

    <div class="row justify-content-center">

        <div class="col-md-8 col-lg-7">

            <div class="card shadow-sm border-0">

                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-shield-lock me-2"></i>
                        Change Password
                    </h5>
                </div>

                <div class="card-body">

                    <form method="POST"
                          action="{{ route('profile.change-password') }}">

                        @csrf
                        @method('PUT')

                        {{-- Current Password --}}
                        <div class="mb-3">

                            <label class="form-label">
                                Current Password
                            </label>

                            <input type="password"
                                   name="current_password"
                                   class="form-control @error('current_password') is-invalid @enderror"
                                   placeholder="Enter current password"
                                   required>

                            @error('current_password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        {{-- New Password --}}
                        <div class="mb-3">

                            <label class="form-label">
                                New Password
                            </label>

                            <input type="password"
                                   name="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   placeholder="Enter new password"
                                   required>

                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        {{-- Confirm Password --}}
                        <div class="mb-4">

                            <label class="form-label">
                                Confirm New Password
                            </label>

                            <input type="password"
                                   name="password_confirmation"
                                   class="form-control"
                                   placeholder="Confirm new password"
                                   required>

                        </div>

                        <div class="d-flex gap-2">

                            <button type="submit"
                                    class="btn btn-primary">

                                <i class="bi bi-key me-1"></i>
                                Update Password

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