@extends('layouts.app')

@section('title', 'Edit Tenant Note')

@section('content')

<div class="container-fluid py-3">

    {{-- =========================================================
         HEADER
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1 fw-bold">
                Edit Tenant Note
            </h4>

            <p class="text-muted mb-0">

                {{ $tenant->company_name }}

                <span class="mx-1">•</span>

                {{ $tenant->tenant_code }}

            </p>

        </div>

        <a href="{{ route(
            'admin.tenants.notes.index',
            $tenant->id
        ) }}"
           class="btn btn-outline-secondary">

            <i class="fas fa-arrow-left me-1"></i>

            Back to Notes

        </a>

    </div>


    {{-- =========================================================
         VALIDATION ERRORS
    ========================================================== --}}

    @if($errors->any())

        <div class="alert alert-danger">

            <strong>
                Please fix the following errors:
            </strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- =========================================================
         EDIT FORM
    ========================================================== --}}

    <div class="row justify-content-center">

        <div class="col-xl-7 col-lg-9">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white py-3">

                    <h5 class="mb-1">

                        <i class="fas fa-sticky-note
                                  text-primary
                                  me-2"></i>

                        Note Information

                    </h5>

                    <small class="text-muted">

                        Update the tenant note.

                    </small>

                </div>


                <div class="card-body">

                    <form method="POST"
                          action="{{ route(
                              'admin.tenants.notes.update',
                              [
                                  'tenant' =>
                                      $tenant->id,
                                  'note' =>
                                      $note->id,
                              ]
                          ) }}">

                        @csrf

                        @method('PUT')


                        {{-- =================================================
                             NOTE TITLE
                        ================================================== --}}

                        <div class="mb-3">

                            <label class="form-label">

                                Note Title

                            </label>

                            <input type="text"
                                   name="note_title"
                                   value="{{ old(
                                       'note_title',
                                       $note->note_title
                                   ) }}"
                                   class="form-control"
                                   maxlength="200"
                                   placeholder="Enter note title">

                        </div>


                        {{-- =================================================
                             NOTE
                        ================================================== --}}

                        <div class="mb-3">

                            <label class="form-label">

                                Note
                                <span class="text-danger">*</span>

                            </label>

                            <textarea name="note"
                                      rows="8"
                                      class="form-control"
                                      required
                                      placeholder="Enter your note...">{{ old(
                                          'note',
                                          $note->note
                                      ) }}</textarea>

                        </div>


                        {{-- =================================================
                             VISIBILITY
                        ================================================== --}}

                        <div class="mb-4">

                            <label class="form-label">

                                Visibility
                                <span class="text-danger">*</span>

                            </label>

                            <select name="visibility"
                                    class="form-select"
                                    required>

                                <option value="Internal"
                                    @selected(
                                        old(
                                            'visibility',
                                            $note->visibility
                                        ) === 'Internal'
                                    )>

                                    Internal

                                </option>

                                <option value="Management"
                                    @selected(
                                        old(
                                            'visibility',
                                            $note->visibility
                                        ) === 'Management'
                                    )>

                                    Management

                                </option>

                            </select>

                            <small class="text-muted">

                                Notes are currently for internal
                                or management use only.

                            </small>

                        </div>


                        {{-- =================================================
                             ACTIONS
                        ================================================== --}}

                        <div class="d-flex
                                    justify-content-end
                                    gap-2">

                            <a href="{{ route(
                                'admin.tenants.notes.index',
                                $tenant->id
                            ) }}"
                               class="btn btn-secondary">

                                Cancel

                            </a>


                            <button type="submit"
                                    class="btn btn-primary">

                                <i class="fas fa-save me-1"></i>

                                Update Note

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection