@extends('layouts.app')

@section('title', 'My Profile')

@section('content')

<div class="container-fluid py-4">

    <div class="row justify-content-center">

        <div class="col-md-8 col-lg-7">

            <div class="card shadow-sm border-0">

                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-person-circle me-2"></i>
                        My Profile
                    </h5>
                </div>

                <div class="card-body">

                    <div class="text-center mb-4">

                        <div class="rounded-circle bg-primary text-white
                                    d-inline-flex align-items-center
                                    justify-content-center"
                             style="width:90px;height:90px;font-size:35px;">

                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}

                        </div>

                        <h5 class="mt-3 mb-1">
                            {{ auth()->user()->name }}
                        </h5>

                        <small class="text-muted">
                            {{ auth()->user()->email }}
                        </small>

                    </div>

                    <hr>

                    <div class="row mb-3">

                        <div class="col-sm-4 fw-semibold">
                            Name
                        </div>

                        <div class="col-sm-8">
                            {{ auth()->user()->name }}
                        </div>

                    </div>

                    <div class="row mb-3">

                        <div class="col-sm-4 fw-semibold">
                            Email
                        </div>

                        <div class="col-sm-8">
                            {{ auth()->user()->email }}
                        </div>

                    </div>

                    <div class="row mb-3">

                        <div class="col-sm-4 fw-semibold">
                            Status
                        </div>

                        <div class="col-sm-8">

                            @if(auth()->user()->is_active)
                                <span class="badge bg-success">
                                    Active
                                </span>
                            @else
                                <span class="badge bg-danger">
                                    Inactive
                                </span>
                            @endif

                        </div>

                    </div>

                    <div class="row mb-3">

                        <div class="col-sm-4 fw-semibold">
                            Member Since
                        </div>

                        <div class="col-sm-8">
                            {{ auth()->user()->created_at?->format('d M Y') }}
                        </div>

                    </div>

                    <div class="mt-4">

                        <a href="{{ route('profile.edit') }}"
                           class="btn btn-primary">

                            <i class="bi bi-pencil me-1"></i>
                            Edit Profile

                        </a>

                        <a href="{{ route('password.edit') }}"
                           class="btn btn-outline-secondary">

                            <i class="bi bi-key me-1"></i>
                            Change Password

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection