@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted">
                Contract Management
            </div>

            <h4 class="mb-1">
                Edit Contract Correspondence
            </h4>

            <div class="text-muted">

                {{ $correspondence->correspondence_number }}

                <span class="mx-1">|</span>

                {{ $contract->contract_code }}

            </div>

        </div>


        <a href="{{ route(
            'admin.projects.contract-management.contracts.correspondence.index',
            [$project, $contract]
        ) }}"
           class="btn btn-outline-secondary">

            <i class="bi bi-arrow-left me-1"></i>

            Back

        </a>

    </div>


    @if($errors->any())

        <div class="alert alert-danger">

            <strong>
                Please correct the following errors:
            </strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    <form method="POST"
          action="{{ route(
              'admin.projects.contract-management.contracts.correspondence.update',
              [
                  $project,
                  $contract,
                  $correspondence
              ]
          ) }}"
          enctype="multipart/form-data">

        @csrf

        @method('PUT')


        {{-- Basic Information --}}

        <div class="card shadow-sm mb-4">

            <div class="card-header bg-white">

                <h5 class="mb-0">
                    Correspondence Information
                </h5>

            </div>


            <div class="card-body">

                <div class="row g-3">


                    <div class="col-md-4">

                        <label class="form-label">
                            Correspondence Number
                        </label>

                        <input type="text"
                               value="{{ $correspondence->correspondence_number }}"
                               class="form-control"
                               readonly>

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Correspondence Date
                            <span class="text-danger">*</span>
                        </label>

                        <input type="date"
                               name="correspondence_date"
                               value="{{ old(
                                   'correspondence_date',
                                   $correspondence
                                       ->correspondence_date
                                       ->format('Y-m-d')
                               ) }}"
                               class="form-control"
                               required>

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Direction
                            <span class="text-danger">*</span>
                        </label>

                        <select name="direction"
                                class="form-select"
                                required>

                            <option value="Incoming"
                                @selected(
                                    old(
                                        'direction',
                                        $correspondence->direction
                                    )
                                    === 'Incoming'
                                )>
                                Incoming
                            </option>

                            <option value="Outgoing"
                                @selected(
                                    old(
                                        'direction',
                                        $correspondence->direction
                                    )
                                    === 'Outgoing'
                                )>
                                Outgoing
                            </option>

                        </select>

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Communication Type
                            <span class="text-danger">*</span>
                        </label>

                        <select name="communication_type"
                                class="form-select"
                                required>

                            @foreach([
                                'Letter',
                                'Email',
                                'Notice',
                                'Instruction',
                                'Site Instruction',
                                'Contractor Submission',
                                'Consultant Submission',
                                'Meeting Minutes',
                                'Warning',
                                'Approval',
                                'Rejection',
                                'Request',
                                'Response',
                                'Other'
                            ] as $type)

                                <option value="{{ $type }}"
                                    @selected(
                                        old(
                                            'communication_type',
                                            $correspondence->communication_type
                                        )
                                        === $type
                                    )>

                                    {{ $type }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-8">

                        <label class="form-label">
                            Subject
                            <span class="text-danger">*</span>
                        </label>

                        <input type="text"
                               name="subject"
                               value="{{ old(
                                   'subject',
                                   $correspondence->subject
                               ) }}"
                               class="form-control"
                               required>

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Reference Number
                        </label>

                        <input type="text"
                               name="reference_number"
                               value="{{ old(
                                   'reference_number',
                                   $correspondence->reference_number
                               ) }}"
                               class="form-control">

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">
                            From Party
                        </label>

                        <input type="text"
                               name="from_party"
                               value="{{ old(
                                   'from_party',
                                   $correspondence->from_party
                               ) }}"
                               class="form-control">

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">
                            To Party
                        </label>

                        <input type="text"
                               name="to_party"
                               value="{{ old(
                                   'to_party',
                                   $correspondence->to_party
                               ) }}"
                               class="form-control">

                    </div>


                    <div class="col-md-12">

                        <label class="form-label">
                            CC
                        </label>

                        <textarea name="cc_party"
                                  rows="2"
                                  class="form-control">{{ old(
                                      'cc_party',
                                      $correspondence->cc_party
                                  ) }}</textarea>

                    </div>

                </div>

            </div>

        </div>


        {{-- Response Tracking --}}

        <div class="card shadow-sm mb-4">

            <div class="card-header bg-white">

                <h5 class="mb-0">
                    Response Tracking
                </h5>

            </div>


            <div class="card-body">

                <div class="row g-3">


                    <div class="col-md-4">

                        <label class="form-label">
                            Priority
                        </label>

                        <select name="priority"
                                class="form-select">

                            @foreach([
                                'Low',
                                'Normal',
                                'High',
                                'Urgent'
                            ] as $priority)

                                <option value="{{ $priority }}"
                                    @selected(
                                        old(
                                            'priority',
                                            $correspondence->priority
                                        )
                                        === $priority
                                    )>

                                    {{ $priority }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Status
                        </label>

                        <select name="status"
                                class="form-select">

                            @foreach([
                                'Open',
                                'Pending Response',
                                'Responded',
                                'Closed',
                                'For Information',
                                'Archived'
                            ] as $status)

                                <option value="{{ $status }}"
                                    @selected(
                                        old(
                                            'status',
                                            $correspondence->status
                                        )
                                        === $status
                                    )>

                                    {{ $status }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-4 d-flex align-items-end">

                        <div class="form-check mb-2">

                            <input type="checkbox"
                                   name="response_required"
                                   value="1"
                                   id="response_required"
                                   class="form-check-input"
                                   @checked(
                                       old(
                                           'response_required',
                                           $correspondence->response_required
                                       )
                                   )>

                            <label class="form-check-label"
                                   for="response_required">

                                Response Required

                            </label>

                        </div>

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Response Due Date
                        </label>

                        <input type="date"
                               name="response_due_date"
                               value="{{ old(
                                   'response_due_date',
                                   $correspondence
                                       ->response_due_date
                                       ?->format('Y-m-d')
                               ) }}"
                               class="form-control">

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Response Date
                        </label>

                        <input type="date"
                               name="response_date"
                               value="{{ old(
                                   'response_date',
                                   $correspondence
                                       ->response_date
                                       ?->format('Y-m-d')
                               ) }}"
                               class="form-control">

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Related Correspondence
                        </label>

                        <select name="related_correspondence_id"
                                class="form-select">

                            <option value="">
                                None
                            </option>

                            @foreach(
                                $previousCorrespondence
                                as $previous
                            )

                                <option value="{{ $previous->id }}"
                                    @selected(
                                        old(
                                            'related_correspondence_id',
                                            $correspondence
                                                ->related_correspondence_id
                                        )
                                        ==
                                        $previous->id
                                    )>

                                    {{
                                        $previous
                                            ->correspondence_number
                                    }}

                                    -
                                    {{
                                        \Illuminate\Support\Str::limit(
                                            $previous->subject,
                                            60
                                        )
                                    }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>

            </div>

        </div>


        {{-- Details --}}

        <div class="card shadow-sm mb-4">

            <div class="card-header bg-white">

                <h5 class="mb-0">
                    Details
                </h5>

            </div>


            <div class="card-body">

                <div class="mb-3">

                    <label class="form-label">
                        Description
                    </label>

                    <textarea name="description"
                              rows="6"
                              class="form-control">{{ old(
                                  'description',
                                  $correspondence->description
                              ) }}</textarea>

                </div>


                <div class="mb-3">

                    <label class="form-label">
                        Remarks
                    </label>

                    <textarea name="remarks"
                              rows="4"
                              class="form-control">{{ old(
                                  'remarks',
                                  $correspondence->remarks
                              ) }}</textarea>

                </div>

            </div>

        </div>


        {{-- Current Attachment --}}

        <div class="card shadow-sm mb-4">

            <div class="card-header bg-white">

                <h5 class="mb-0">
                    Attachment
                </h5>

            </div>


            <div class="card-body">

                @if($correspondence->file_name)

                    <div class="border rounded p-3 mb-3">

                        <div class="d-flex justify-content-between align-items-center">

                            <div>

                                <div class="fw-semibold">

                                    <i class="bi bi-file-earmark-text me-2"></i>

                                    {{ $correspondence->file_name }}

                                </div>

                                <div class="text-muted small">

                                    {{
                                        $correspondence
                                            ->formatted_file_size
                                    }}

                                </div>

                            </div>


                            <a href="{{ route(
                                'admin.projects.contract-management.contracts.correspondence.download',
                                [
                                    $project,
                                    $contract,
                                    $correspondence
                                ]
                            ) }}"
                               class="btn btn-outline-success">

                                <i class="bi bi-download me-1"></i>

                                Download

                            </a>

                        </div>

                    </div>

                @endif


                <label class="form-label">
                    Replace File
                </label>

                <input type="file"
                       name="correspondence_file"
                       class="form-control"
                       accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png">


                <div class="form-text">

                    Leave empty to keep the existing file.

                    Maximum size: 50 MB.

                </div>

            </div>

        </div>


        {{-- Audit --}}

        <div class="card shadow-sm mb-4">

            <div class="card-header bg-white">

                <h5 class="mb-0">
                    Record Information
                </h5>

            </div>


            <div class="card-body">

                <div class="row">

                    <div class="col-md-4">

                        <div class="text-muted small">
                            Created
                        </div>

                        {{
                            $correspondence
                                ->created_at
                                ?->format('d M Y H:i')
                            ?? '—'
                        }}

                    </div>


                    <div class="col-md-4">

                        <div class="text-muted small">
                            Last Updated
                        </div>

                        {{
                            $correspondence
                                ->updated_at
                                ?->format('d M Y H:i')
                            ?? '—'
                        }}

                    </div>

                </div>

            </div>

        </div>


        <div class="d-flex justify-content-end gap-2 mb-5">

            <a href="{{ route(
                'admin.projects.contract-management.contracts.correspondence.index',
                [$project, $contract]
            ) }}"
               class="btn btn-outline-secondary">

                Cancel

            </a>


            <button type="submit"
                    class="btn btn-primary">

                <i class="bi bi-check-lg me-1"></i>

                Update Correspondence

            </button>

        </div>

    </form>

</div>

@endsection