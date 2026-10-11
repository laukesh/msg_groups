@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted">
                Contract Management
            </div>

            <h4 class="mb-1">
                Add Contract Correspondence
            </h4>

            <div class="text-muted">

                {{ $contract->contract_code }}

                <span class="mx-1">|</span>

                {{ $contract->contract_title }}

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
              'admin.projects.contract-management.contracts.correspondence.store',
              [$project, $contract]
          ) }}"
          enctype="multipart/form-data">

        @csrf


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
                            Correspondence Date
                            <span class="text-danger">*</span>
                        </label>

                        <input type="date"
                               name="correspondence_date"
                               value="{{ old(
                                   'correspondence_date',
                                   now()->format('Y-m-d')
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
                                    old('direction') === 'Incoming'
                                )>
                                Incoming
                            </option>

                            <option value="Outgoing"
                                @selected(
                                    old(
                                        'direction',
                                        'Outgoing'
                                    ) === 'Outgoing'
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

                            <option value="">
                                Select Type
                            </option>

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
                                        old('communication_type')
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
                               value="{{ old('subject') }}"
                               class="form-control"
                               placeholder="Enter correspondence subject"
                               required>

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Reference Number
                        </label>

                        <input type="text"
                               name="reference_number"
                               value="{{ old(
                                   'reference_number'
                               ) }}"
                               class="form-control"
                               placeholder="External reference">

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">
                            From Party
                        </label>

                        <input type="text"
                               name="from_party"
                               value="{{ old('from_party') }}"
                               class="form-control"
                               placeholder="Sender">

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">
                            To Party
                        </label>

                        <input type="text"
                               name="to_party"
                               value="{{ old('to_party') }}"
                               class="form-control"
                               placeholder="Recipient">

                    </div>


                    <div class="col-md-12">

                        <label class="form-label">
                            CC
                        </label>

                        <textarea name="cc_party"
                                  rows="2"
                                  class="form-control"
                                  placeholder="CC parties">{{ old('cc_party') }}</textarea>

                    </div>

                </div>

            </div>

        </div>


        {{-- Tracking --}}

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
                            <span class="text-danger">*</span>
                        </label>

                        <select name="priority"
                                class="form-select"
                                required>

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
                                            'Normal'
                                        ) === $priority
                                    )>

                                    {{ $priority }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Status
                            <span class="text-danger">*</span>
                        </label>

                        <select name="status"
                                class="form-select"
                                required>

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
                                            'Open'
                                        ) === $status
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
                                           'response_required'
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
                                   'response_due_date'
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
                                   'response_date'
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
                                            'related_correspondence_id'
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
                              class="form-control"
                              placeholder="Enter correspondence details...">{{ old('description') }}</textarea>

                </div>


                <div class="mb-3">

                    <label class="form-label">
                        Remarks
                    </label>

                    <textarea name="remarks"
                              rows="4"
                              class="form-control"
                              placeholder="Internal remarks...">{{ old('remarks') }}</textarea>

                </div>

            </div>

        </div>


        {{-- Attachment --}}

        <div class="card shadow-sm mb-4">

            <div class="card-header bg-white">

                <h5 class="mb-0">
                    Attachment
                </h5>

            </div>


            <div class="card-body">

                <input type="file"
                       name="correspondence_file"
                       class="form-control"
                       accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png">


                <div class="form-text">

                    Allowed:
                    PDF, DOC, DOCX, XLS, XLSX, JPG, JPEG, PNG.

                    Maximum size: 50 MB.

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

                Save Correspondence

            </button>

        </div>

    </form>

</div>

@endsection