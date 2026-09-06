@extends('layouts.app')

@section('title', 'Delay Assessment')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                Delay Assessment
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
              'admin.projects.construction.delays.assess',
              [$project, $delay]
          ) }}">

        @csrf


        <div class="card shadow-sm mb-4">

            <div class="card-header bg-white">
                <h5 class="mb-0">Assessment</h5>
            </div>

            <div class="card-body">

                <div class="row g-3">

                    <div class="col-md-4">

                        <label class="form-label">
                            Reported Days
                        </label>

                        <input type="text"
                               class="form-control"
                               value="{{ $delay->reported_days }}"
                               readonly>

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Assessed Days <span class="text-danger">*</span>
                        </label>

                        <input type="number"
                               name="assessed_days"
                               class="form-control"
                               min="0"
                               max="{{ $delay->reported_days }}"
                               value="{{ old(
                                   'assessed_days',
                                   $delay->assessed_days
                               ) }}"
                               required>

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Assessed Cost Impact ($)
                        </label>

                        <input type="number"
                               name="assessed_cost_impact"
                               class="form-control"
                               min="0"
                               step="0.01"
                               value="{{ old(
                                   'assessed_cost_impact',
                                   $delay->assessed_cost_impact
                               ) }}">

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Excusable Days
                        </label>

                        <input type="number"
                               name="excusable_days"
                               class="form-control"
                               min="0"
                               value="{{ old(
                                   'excusable_days',
                                   $delay->excusable_days
                               ) }}">

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Compensable Days
                        </label>

                        <input type="number"
                               name="compensable_days"
                               class="form-control"
                               min="0"
                               value="{{ old(
                                   'compensable_days',
                                   $delay->compensable_days
                               ) }}">

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            EOT Assessed Days
                        </label>

                        <input type="number"
                               name="eot_assessed_days"
                               class="form-control"
                               min="0"
                               value="{{ old(
                                   'eot_assessed_days',
                                   $delay->eot_assessed_days
                               ) }}">

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">
                            Is Excusable?
                        </label>

                        <select name="is_excusable"
                                class="form-select">

                            <option value="0"
                                @selected(!old(
                                    'is_excusable',
                                    $delay->is_excusable
                                ))>
                                No
                            </option>

                            <option value="1"
                                @selected(old(
                                    'is_excusable',
                                    $delay->is_excusable
                                ))>
                                Yes
                            </option>

                        </select>

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">
                            Is Compensable?
                        </label>

                        <select name="is_compensable"
                                class="form-select">

                            <option value="0"
                                @selected(!old(
                                    'is_compensable',
                                    $delay->is_compensable
                                ))>
                                No
                            </option>

                            <option value="1"
                                @selected(old(
                                    'is_compensable',
                                    $delay->is_compensable
                                ))>
                                Yes
                            </option>

                        </select>

                    </div>


                    <div class="col-12">

                        <label class="form-label">
                            Assessment Remarks
                        </label>

                        <textarea name="assessment_remarks"
                                  rows="5"
                                  class="form-control">{{ old(
                                      'assessment_remarks',
                                      $delay->assessment_remarks
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
                        class="btn btn-primary">

                    Save Assessment

                </button>

            </div>

        </div>

    </form>

</div>

@endsection