@extends('layouts.app')

@section('title', 'Reject Delay')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                Reject Delay
            </h4>

            <div class="text-muted">
                {{ $delay->delay_number }} -
                {{ $delay->delay_title }}
            </div>

        </div>

        <a href="{{ route(
            'admin.projects.construction.delays.show',
            [$project, $delay]
        ) }}"
           class="btn btn-outline-secondary">

            Back

        </a>

    </div>


    @if ($errors->any())

        <div class="alert alert-danger">

            <ul class="mb-0">

                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

    @endif


    <div class="card shadow-sm mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0">
                Rejection Decision
            </h5>

        </div>

        <div class="card-body">

            <div class="alert alert-warning">

                <i class="bi bi-exclamation-triangle"></i>

                You are about to reject this delay record.
                Please provide a clear reason.

            </div>


            <form method="POST"
                  action="{{ route(
                      'admin.projects.construction.delays.reject',
                      [$project, $delay]
                  ) }}">

                @csrf


                <div class="mb-3">

                    <label class="form-label">
                        Rejection Remarks
                        <span class="text-danger">*</span>
                    </label>

                    <textarea name="rejection_remarks"
                              rows="6"
                              class="form-control"
                              required>{{ old(
                                  'rejection_remarks',
                                  $delay->rejection_remarks
                              ) }}</textarea>

                </div>


                <div class="d-flex justify-content-end gap-2">

                    <a href="{{ route(
                        'admin.projects.construction.delays.show',
                        [$project, $delay]
                    ) }}"
                       class="btn btn-outline-secondary">

                        Cancel

                    </a>

                    <button type="submit"
                            class="btn btn-danger">

                        <i class="bi bi-x-circle"></i>
                        Reject Delay

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection