@extends('layouts.app')

@section('title', 'Tenant Notes')

@section('content')

<div class="container-fluid py-3">

    {{-- =========================================================
         HEADER
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1 fw-bold">
                Tenant Notes
            </h4>

            <p class="text-muted mb-0">

                {{ $tenant->company_name }}

                <span class="mx-1">•</span>

                {{ $tenant->tenant_code }}

            </p>

        </div>

        <a href="{{ route(
            'admin.tenants.show',
            $tenant->id
        ) }}"
           class="btn btn-outline-secondary">

            <i class="fas fa-arrow-left me-1"></i>

            Tenant Details

        </a>

    </div>


    {{-- =========================================================
         SUCCESS MESSAGE
    ========================================================== --}}

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


    <div class="row g-4">


        {{-- =====================================================
             ADD NOTE
        ====================================================== --}}

        <div class="col-xl-4">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white py-3">

                    <h5 class="mb-1">

                        <i class="fas fa-sticky-note
                                  text-primary
                                  me-2"></i>

                        Add Note

                    </h5>

                    <small class="text-muted">

                        Add an internal note for this tenant.

                    </small>

                </div>


                <div class="card-body">

                    <form method="POST"
                          action="{{ route(
                              'admin.tenants.notes.store',
                              $tenant->id
                          ) }}">

                        @csrf


                        {{-- NOTE TITLE --}}

                        <div class="mb-3">

                            <label class="form-label">

                                Note Title

                            </label>

                            <input type="text"
                                   name="note_title"
                                   value="{{ old(
                                       'note_title'
                                   ) }}"
                                   class="form-control"
                                   maxlength="200"
                                   placeholder="Enter note title">

                        </div>


                        {{-- NOTE --}}

                        <div class="mb-3">

                            <label class="form-label">

                                Note
                                <span class="text-danger">*</span>

                            </label>

                            <textarea name="note"
                                      rows="6"
                                      class="form-control"
                                      required
                                      placeholder="Enter your note...">{{ old('note') }}</textarea>

                        </div>


                        {{-- VISIBILITY --}}

                        <div class="mb-3">

                            <label class="form-label">

                                Visibility
                                <span class="text-danger">*</span>

                            </label>

                            <select name="visibility"
                                    class="form-select"
                                    required>

                                <option value="">
                                    Select Visibility
                                </option>

                                <option value="Internal"
                                    @selected(
                                        old('visibility')
                                        === 'Internal'
                                    )>

                                    Internal

                                </option>

                                <option value="Management"
                                    @selected(
                                        old('visibility')
                                        === 'Management'
                                    )>

                                    Management

                                </option>

                            </select>

                            <small class="text-muted">

                                Notes are not visible to tenants.

                            </small>

                        </div>


                        {{-- SUBMIT --}}

                        <button type="submit"
                                class="btn btn-primary w-100">

                            <i class="fas fa-save me-1"></i>

                            Add Note

                        </button>

                    </form>

                </div>

            </div>

        </div>


        {{-- =====================================================
             NOTE LIST
        ====================================================== --}}

        <div class="col-xl-8">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white py-3">

                    <div class="d-flex
                                justify-content-between
                                align-items-center">

                        <div>

                            <h5 class="mb-1">

                                Notes

                            </h5>

                            <small class="text-muted">

                                {{ $notes->count() }}
                                note(s)

                            </small>

                        </div>

                    </div>

                </div>


                <div class="card-body">

                    @forelse($notes as $note)

                        <div class="border rounded p-3 mb-3">

                            <div class="d-flex
                                        justify-content-between
                                        align-items-start">

                                <div>

                                    <h6 class="mb-1 fw-bold">

                                        {{ $note->note_title
                                            ?: 'Untitled Note' }}

                                    </h6>

                                    <small class="text-muted">

                                        <i class="fas fa-calendar-alt
                                                  me-1"></i>

                                        {{ $note->created_at
                                            ? $note->created_at
                                                ->format(
                                                    'd M Y, h:i A'
                                                )
                                            : '-' }}

                                    </small>

                                </div>


                                {{-- VISIBILITY --}}

                                @if(
                                    $note->visibility === 'Management'
                                )

                                    <span class="badge bg-warning
                                                 text-dark">

                                        <i class="fas fa-users-cog
                                                  me-1"></i>

                                        Management

                                    </span>

                                @else

                                    <span class="badge bg-secondary">

                                        <i class="fas fa-lock me-1"></i>

                                        Internal

                                    </span>

                                @endif

                            </div>


                            <hr>


                            {{-- NOTE CONTENT --}}

                            <div class="mb-3"
                                 style="white-space: pre-line;">

                                {{ $note->note }}

                            </div>


                            {{-- ACTIONS --}}

                            <div class="d-flex
                                        justify-content-end
                                        gap-2">

                                <a href="{{ route(
                                    'admin.tenants.notes.edit',
                                    [
                                        'tenant' =>
                                            $tenant->id,
                                        'note' =>
                                            $note->id,
                                    ]
                                ) }}"
                                   class="btn btn-sm
                                          btn-outline-warning">

                                    <i class="fas fa-edit me-1"></i>

                                    Edit

                                </a>


                                <form method="POST"
                                      action="{{ route(
                                          'admin.tenants.notes.destroy',
                                          [
                                              'tenant' =>
                                                  $tenant->id,
                                              'note' =>
                                                  $note->id,
                                          ]
                                      ) }}"
                                      onsubmit="return confirm(
                                          'Are you sure you want to delete this note?'
                                      );">

                                    @csrf

                                    @method('DELETE')

                                    <button type="submit"
                                            class="btn btn-sm
                                                   btn-outline-danger">

                                        <i class="fas fa-trash me-1"></i>

                                        Delete

                                    </button>

                                </form>

                            </div>

                        </div>

                    @empty

                        <div class="text-center
                                    text-muted
                                    py-5">

                            <i class="fas fa-sticky-note
                                      fa-3x
                                      d-block
                                      mb-3">
                            </i>

                            <h6>
                                No notes found
                            </h6>

                            <p class="mb-0">

                                Add the first note using
                                the form.

                            </p>

                        </div>

                    @endforelse

                </div>

            </div>

        </div>

    </div>

</div>

@endsection