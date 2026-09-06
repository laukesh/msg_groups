@extends('layouts.app')

@section('title', 'Delay Approval')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                Delay Approval
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


    <form method="POST"
          action="{{ route(
              'admin.projects.construction.delays.approve',
              [$project, $delay]
          ) }}">

        @csrf


        <div class="card shadow-sm mb-4">

            <div class="card-header bg-white">

                <h5 class="mb-0">
                    Assessed Values
                </h5>

            </div>

            <div class="card-body">

                <div class="row g-4">

                    <div class="col-md-3">

                        <small class="text-muted d-block">
                            Assessed Days
                        </small>

                        <strong>
                            {{ $delay->assessed_days }}
                        </strong>

                    </div>


                    <div class="col-md-3">

                        <small class="text-muted d-block">
                            Assessed Cost
                        </small>

                        <strong>
                            ${{ number_format(
                                $delay->assessed_cost_impact ?? 0,
                                2
                            ) }}
                        </strong>

                    </div>


                    <div class="col-md-3">

                        <small class="text-muted d-block">
                            EOT Assessed
                        </small>

                        <strong>
                            {{ $delay->eot_assessed_days ?? 0 }} days
                        </strong>

                    </div>


                    <div class="col-md-3">

                        <small class="text-muted d-block">
                            Excusable
                        </small>

                        <strong>
                            {{ $delay->excusable_days ?? 0 }} days
                        </strong>

                    </div>

                </div>

            </div>

        </div>


        <div class="card shadow-sm mb-4">

            <div class="card-header bg-white">
                <h5 class="mb-0">Approval Decision</h5>
            </div>

            <div class="card-body">

                <div class="row g-3">

                    <div class="col-md-4">

                        <label class="form-label">
                            Approved Days <span class="text-danger">*</span>
                        </label>

                        <input type="number"
                               name="approved_days"
                               class="form-control"
                               min="0"
                               max="{{ $delay->assessed_days }}"
                               value="{{ old(
                                   'approved_days',
                                   $delay->approved_days
                               ) }}"
                               required>

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Approved Cost Impact ($)
                        </label>

                        <input type="number"
                               name="approved_cost_impact"
                               class="form-control"
                               min="0"
                               step="0.01"
                               value="{{ old(
                                   'approved_cost_impact',
                                   $delay->approved_cost_impact
                               ) }}">

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            EOT Approved Days
                        </label>

                        <input type="number"
                               name="eot_approved_days"
                               class="form-control"
                               min="0"
                               value="{{ old(
                                   'eot_approved_days',
                                   $delay->eot_approved_days
                               ) }}">

                    </div>


                    <div class="col-12">

                        <label class="form-label">
                            Approval Remarks
                        </label>

                        <textarea name="approval_remarks"
                                  rows="5"
                                  class="form-control">{{ old(
                                      'approval_remarks',
                                      $delay->approval_remarks
                                  ) }}</textarea>

                    </div>

                </div>

            </div>

        </div>


        <div class="card shadow-sm">

            <div class="card-body d-flex justify-content-end gap-2">

                <a href="{{ route(
                    'admin.projects.construction.delays.show',
                    [$project, $delay]
                ) }}"
                   class="btn btn-outline-secondary">

                    Cancel

                </a>

                <button type="submit"
                        class="btn btn-success">

                    <i class="bi bi-check-circle"></i>
                    Approve Delay

                </button>

            </div>

        </div>

    </form>

</div>

@endsection