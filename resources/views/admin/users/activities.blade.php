@extends('layouts.app')

@section('title', 'System Activity Logs')

@section('content')

<div class="container-fluid py-3">

    {{-- =========================================================
         HEADER
    ========================================================== --}}

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">

        <div>

            <h4 class="fw-bold mb-1">

                <i class="ri-shield-check-line text-primary me-1"></i>

                System Activity Logs

            </h4>

            <div class="text-muted small">

                Monitor activities across all system modules.

            </div>

        </div>

        <div>

            <span class="badge bg-primary-subtle text-primary px-3 py-2">

                <i class="ri-history-line me-1"></i>

                {{ number_format($activities->total()) }}
                Activities

            </span>

        </div>

    </div>


    {{-- =========================================================
         FILTER CARD
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white border-0 py-3">

            <div class="fw-semibold">

                <i class="ri-filter-3-line me-1"></i>

                Filter Activities

            </div>

        </div>

        <div class="card-body">

            <form method="GET"
                  action="{{ route('admin.activity-logs.index') }}">

                <div class="row g-3">

                    {{-- User --}}

                    <div class="col-xl-2 col-lg-3 col-md-6">

                        <label class="form-label small fw-semibold">

                            User

                        </label>

                        <select name="user_id"
                                class="form-select">

                            <option value="">
                                All Users
                            </option>

                            @foreach($users as $user)

                                <option value="{{ $user->id }}"
                                    @selected(request('user_id') == $user->id)>

                                    {{ $user->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Module --}}

                    <div class="col-xl-2 col-lg-3 col-md-6">

                        <label class="form-label small fw-semibold">

                            Module

                        </label>

                        <select name="module"
                                class="form-select">

                            <option value="">
                                All Modules
                            </option>

                            @foreach($modules as $module)

                                <option value="{{ $module }}"
                                    @selected(request('module') === $module)>

                                    {{ $module }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Action --}}

                    <div class="col-xl-2 col-lg-3 col-md-6">

                        <label class="form-label small fw-semibold">

                            Action

                        </label>

                        <select name="action"
                                class="form-select">

                            <option value="">
                                All Actions
                            </option>

                            @foreach($actions as $action)

                                <option value="{{ $action }}"
                                    @selected(request('action') === $action)>

                                    {{ ucwords(str_replace('_', ' ', $action)) }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Search --}}

                    <div class="col-xl-2 col-lg-3 col-md-6">

                        <label class="form-label small fw-semibold">

                            Search

                        </label>

                        <input type="text"
                               name="search"
                               value="{{ request('search') }}"
                               class="form-control"
                               placeholder="Description / IP / route">

                    </div>


                    {{-- From --}}

                    <div class="col-xl-1 col-lg-3 col-md-6">

                        <label class="form-label small fw-semibold">

                            From

                        </label>

                        <input type="date"
                               name="from"
                               value="{{ request('from') }}"
                               class="form-control">

                    </div>


                    {{-- To --}}

                    <div class="col-xl-1 col-lg-3 col-md-6">

                        <label class="form-label small fw-semibold">

                            To

                        </label>

                        <input type="date"
                               name="to"
                               value="{{ request('to') }}"
                               class="form-control">

                    </div>


                    {{-- Buttons --}}

                    <div class="col-xl-2 col-lg-3 col-md-6 d-flex align-items-end gap-2">

                        <button type="submit"
                                class="btn btn-primary flex-grow-1">

                            <i class="ri-search-line me-1"></i>

                            Search

                        </button>

                        <a href="{{ route('admin.activity-logs.index') }}"
                           class="btn btn-outline-secondary">

                            <i class="ri-refresh-line"></i>

                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- =========================================================
         TABLE
    ========================================================== --}}

    <div class="card border-0 shadow-sm">

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th class="px-3">
                                #
                            </th>

                            <th>
                                User
                            </th>

                            <th>
                                Module
                            </th>

                            <th>
                                Action
                            </th>

                            <th>
                                Description
                            </th>

                            <th>
                                IP Address
                            </th>

                            <th>
                                Date / Time
                            </th>

                            <th class="text-end px-3">
                                Action
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                    @forelse($activities as $activity)

                        <tr>

                            <td class="px-3">

                                <span class="text-muted">

                                    #{{ $activity->id }}

                                </span>

                            </td>


                            <td>

                                @if($activity->user)

                                    <div class="fw-semibold">

                                        {{ $activity->user->name }}

                                    </div>

                                    <small class="text-muted">

                                        User #{{ $activity->user_id }}

                                    </small>

                                @else

                                    <span class="badge bg-secondary">

                                        System

                                    </span>

                                @endif

                            </td>


                            <td>

                                <span class="badge bg-primary-subtle text-primary">

                                    {{ $activity->module ?: 'System' }}

                                </span>

                            </td>


                            <td>

                                @php

                                    $badge = match($activity->action) {

                                        'created' => 'success',

                                        'updated' => 'warning',

                                        'deleted' => 'danger',

                                        'restored' => 'info',

                                        'login' => 'success',

                                        'logout' => 'secondary',

                                        'failed_login' => 'danger',

                                        default => 'primary',

                                    };

                                @endphp

                                <span class="badge bg-{{ $badge }}">

                                    {{ ucwords(str_replace('_', ' ', $activity->action)) }}

                                </span>

                            </td>


                            <td>

                                <div class="fw-medium">

                                    {{ $activity->description }}

                                </div>

                                @if($activity->route)

                                    <small class="text-muted">

                                        {{ $activity->route }}

                                    </small>

                                @endif

                            </td>


                            <td>

                                <code>

                                    {{ $activity->ip_address ?? '-' }}

                                </code>

                            </td>


                            <td>

                                <div class="fw-medium">

                                    {{ $activity->created_at->format('d M Y') }}

                                </div>

                                <small class="text-muted">

                                    {{ $activity->created_at->format('h:i:s A') }}

                                </small>

                            </td>


                            <td class="text-end px-3">

                                <a href="{{ route('admin.activity-logs.show', $activity) }}"
                                   class="btn btn-sm btn-outline-primary"
                                   title="View details">

                                    <i class="ri-eye-line"></i>

                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="8"
                                class="text-center py-5">

                                <div class="text-muted">

                                    <i class="ri-file-search-line fs-1 d-block mb-2"></i>

                                    No activity records found.

                                </div>

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- Pagination --}}

        @if($activities->hasPages())

            <div class="card-footer bg-white border-0">

                {{ $activities->links() }}

            </div>

        @endif

    </div>

</div>

@endsection