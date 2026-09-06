@extends('layouts.app')

@section('title', 'Edit Correspondence')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                Edit Correspondence
            </h4>

            <div class="text-muted">
                {{ $project->project_number ?? $project->project_code }}
                -
                {{ $project->project_name }}
            </div>

            <div class="small text-muted mt-1">
                {{ $correspondence->correspondence_number }}
            </div>
        </div>

        <div class="d-flex gap-2">

            <a href="{{ route(
                'admin.projects.construction.correspondence.show',
                [$project, $correspondence]
            ) }}"
               class="btn btn-outline-secondary">

                <i class="bi bi-arrow-left me-1"></i>
                Back to Correspondence

            </a>

        </div>

    </div>


    {{-- Validation Errors --}}
    @if($errors->any())

        <div class="alert alert-danger">

            <div class="fw-semibold mb-2">
                Please correct the following errors:
            </div>

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    <form method="POST"
          action="{{ route(
              'admin.projects.construction.correspondence.update',
              [$project, $correspondence]
          ) }}">

        @csrf
        @method('PUT')


        {{-- Basic Information --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white">

                <h6 class="mb-0">
                    <i class="bi bi-envelope me-1"></i>
                    Correspondence Information
                </h6>

            </div>


            <div class="card-body">

                <div class="row g-3">

                    {{-- Correspondence Number --}}
                    <div class="col-lg-4 col-md-6">

                        <label class="form-label">
                            Correspondence Number
                        </label>

                        <input type="text"
                               class="form-control bg-light"
                               value="{{ $correspondence->correspondence_number }}"
                               readonly>

                        <div class="form-text">
                            System generated number.
                        </div>

                    </div>


                    {{-- Reference Number --}}
                    <div class="col-lg-4 col-md-6">

                        <label class="form-label">
                            Reference Number
                        </label>

                        <input type="text"
                               name="reference_number"
                               value="{{ old(
                                   'reference_number',
                                   $correspondence->reference_number
                               ) }}"
                               class="form-control"
                               maxlength="150"
                               placeholder="External reference number">

                    </div>


                    {{-- Correspondence Type --}}
                    <div class="col-lg-4 col-md-6">

                        <label class="form-label">
                            Correspondence Type
                            <span class="text-danger">*</span>
                        </label>

                        <select name="correspondence_type"
                                class="form-select"
                                required>

                            <option value="">
                                Select Type
                            </option>

                            @foreach([
                                'Incoming',
                                'Outgoing',
                                'Internal',
                                'Notice',
                                'Instruction',
                                'Letter',
                                'Email',
                                'Memo',
                                'Other'
                            ] as $type)

                                <option value="{{ $type }}"
                                    {{ old(
                                        'correspondence_type',
                                        $correspondence->correspondence_type
                                    ) == $type ? 'selected' : '' }}>

                                    {{ $type }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Communication Method --}}
                    <div class="col-lg-4 col-md-6">

                        <label class="form-label">
                            Communication Method
                            <span class="text-danger">*</span>
                        </label>

                        <select name="communication_method"
                                class="form-select"
                                required>

                            @foreach([
                                'Email',
                                'Letter',
                                'Meeting',
                                'Phone',
                                'Portal',
                                'Hand Delivery',
                                'Other'
                            ] as $method)

                                <option value="{{ $method }}"
                                    {{ old(
                                        'communication_method',
                                        $correspondence->communication_method
                                    ) == $method ? 'selected' : '' }}>

                                    {{ $method }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Correspondence Date --}}
                    <div class="col-lg-4 col-md-6">

                        <label class="form-label">
                            Correspondence Date
                            <span class="text-danger">*</span>
                        </label>

                        <input type="date"
                               name="correspondence_date"
                               value="{{ old(
                                   'correspondence_date',
                                   optional($correspondence->correspondence_date)->format('Y-m-d')
                               ) }}"
                               class="form-control"
                               required>

                    </div>


                    {{-- Received Date --}}
                    <div class="col-lg-4 col-md-6">

                        <label class="form-label">
                            Received Date
                        </label>

                        <input type="date"
                               name="received_date"
                               value="{{ old(
                                   'received_date',
                                   optional($correspondence->received_date)->format('Y-m-d')
                               ) }}"
                               class="form-control">

                    </div>


                    {{-- Sent Date --}}
                    <div class="col-lg-4 col-md-6">

                        <label class="form-label">
                            Sent Date
                        </label>

                        <input type="date"
                               name="sent_date"
                               value="{{ old(
                                   'sent_date',
                                   optional($correspondence->sent_date)->format('Y-m-d')
                               ) }}"
                               class="form-control">

                    </div>


                    {{-- Subject --}}
                    <div class="col-12">

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
                               maxlength="255"
                               required>

                    </div>

                </div>

            </div>

        </div>


        {{-- Project References --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white">

                <h6 class="mb-0">
                    <i class="bi bi-link-45deg me-1"></i>
                    Project References
                </h6>

            </div>


            <div class="card-body">

                <div class="row g-3">

                    {{-- Work Order --}}
                    <div class="col-lg-4 col-md-6">

                        <label class="form-label">
                            Construction Work Order
                        </label>

                        <select name="construction_work_order_id"
                                class="form-select">

                            <option value="">
                                Select Work Order
                            </option>

                            @foreach($workOrders as $workOrder)

                                <option value="{{ $workOrder->id }}"
                                    {{ old(
                                        'construction_work_order_id',
                                        $correspondence->construction_work_order_id
                                    ) == $workOrder->id ? 'selected' : '' }}>

                                    {{ $workOrder->work_order_number }}

                                    @if($workOrder->work_order_title)
                                        - {{ $workOrder->work_order_title }}
                                    @endif

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Procurement Contract --}}
                    <div class="col-lg-4 col-md-6">

                        <label class="form-label">
                            Procurement Contract
                        </label>

                        <select name="procurement_contract_id"
                                class="form-select">

                            <option value="">
                                Select Contract
                            </option>

                            @foreach($contracts as $contract)

                                <option value="{{ $contract->id }}"
                                    {{ old(
                                        'procurement_contract_id',
                                        $correspondence->procurement_contract_id
                                    ) == $contract->id ? 'selected' : '' }}>

                                    {{ $contract->contract_number ?? 'Contract #'.$contract->id }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Claim --}}
                    <div class="col-lg-4 col-md-6">

                        <label class="form-label">
                            Construction Claim
                        </label>

                        <select name="construction_claim_id"
                                class="form-select">

                            <option value="">
                                Select Claim
                            </option>

                            @foreach($claims as $claim)

                                <option value="{{ $claim->id }}"
                                    {{ old(
                                        'construction_claim_id',
                                        $correspondence->construction_claim_id
                                    ) == $claim->id ? 'selected' : '' }}>

                                    {{ $claim->claim_number }}

                                    @if($claim->subject)
                                        - {{ $claim->subject }}
                                    @endif

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Delay --}}
                    <div class="col-lg-4 col-md-6">

                        <label class="form-label">
                            Construction Delay
                        </label>

                        <select name="construction_delay_id"
                                class="form-select">

                            <option value="">
                                Select Delay
                            </option>

                            @foreach($delays as $delay)

                                <option value="{{ $delay->id }}"
                                    {{ old(
                                        'construction_delay_id',
                                        $correspondence->construction_delay_id
                                    ) == $delay->id ? 'selected' : '' }}>

                                    {{ $delay->delay_number }}

                                    @if($delay->delay_title)
                                        - {{ $delay->delay_title }}
                                    @endif

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Risk --}}
                    <div class="col-lg-4 col-md-6">

                        <label class="form-label">
                            Construction Risk
                        </label>

                        <select name="construction_risk_id"
                                class="form-select">

                            <option value="">
                                Select Risk
                            </option>

                            @foreach($risks as $risk)

                                <option value="{{ $risk->id }}"
                                    {{ old(
                                        'construction_risk_id',
                                        $correspondence->construction_risk_id
                                    ) == $risk->id ? 'selected' : '' }}>

                                    {{ $risk->risk_number }}

                                    @if($risk->risk_title)
                                        - {{ $risk->risk_title }}
                                    @endif

                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>

            </div>

        </div>


        {{-- Sender / Receiver --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white">

                <h6 class="mb-0">
                    <i class="bi bi-people me-1"></i>
                    Sender & Receiver
                </h6>

            </div>


            <div class="card-body">

                <div class="row g-4">

                    {{-- Sender --}}
                    <div class="col-lg-6">

                        <div class="border rounded p-3 h-100">

                            <h6 class="mb-3">
                                Sender
                            </h6>

                            <div class="row g-3">

                                <div class="col-md-6">

                                    <label class="form-label">
                                        Sender Type
                                    </label>

                                    <select name="sender_type"
                                            class="form-select">

                                        <option value="">
                                            Select
                                        </option>

                                        @foreach([
                                            'Client',
                                            'Consultant',
                                            'Contractor',
                                            'Supplier',
                                            'Authority',
                                            'Project Team',
                                            'Other'
                                        ] as $type)

                                            <option value="{{ $type }}"
                                                {{ old(
                                                    'sender_type',
                                                    $correspondence->sender_type
                                                ) == $type ? 'selected' : '' }}>

                                                {{ $type }}

                                            </option>

                                        @endforeach

                                    </select>

                                </div>


                                <div class="col-md-6">

                                    <label class="form-label">
                                        Sender Name
                                    </label>

                                    <input type="text"
                                           name="sender_name"
                                           value="{{ old(
                                               'sender_name',
                                               $correspondence->sender_name
                                           ) }}"
                                           class="form-control"
                                           maxlength="255">

                                </div>


                                <div class="col-12">

                                    <label class="form-label">
                                        Sender Organization
                                    </label>

                                    <input type="text"
                                           name="sender_organization"
                                           value="{{ old(
                                               'sender_organization',
                                               $correspondence->sender_organization
                                           ) }}"
                                           class="form-control"
                                           maxlength="255">

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- Receiver --}}
                    <div class="col-lg-6">

                        <div class="border rounded p-3 h-100">

                            <h6 class="mb-3">
                                Receiver
                            </h6>

                            <div class="row g-3">

                                <div class="col-md-6">

                                    <label class="form-label">
                                        Receiver Type
                                    </label>

                                    <select name="receiver_type"
                                            class="form-select">

                                        <option value="">
                                            Select
                                        </option>

                                        @foreach([
                                            'Client',
                                            'Consultant',
                                            'Contractor',
                                            'Supplier',
                                            'Authority',
                                            'Project Team',
                                            'Other'
                                        ] as $type)

                                            <option value="{{ $type }}"
                                                {{ old(
                                                    'receiver_type',
                                                    $correspondence->receiver_type
                                                ) == $type ? 'selected' : '' }}>

                                                {{ $type }}

                                            </option>

                                        @endforeach

                                    </select>

                                </div>


                                <div class="col-md-6">

                                    <label class="form-label">
                                        Receiver Name
                                    </label>

                                    <input type="text"
                                           name="receiver_name"
                                           value="{{ old(
                                               'receiver_name',
                                               $correspondence->receiver_name
                                           ) }}"
                                           class="form-control"
                                           maxlength="255">

                                </div>


                                <div class="col-12">

                                    <label class="form-label">
                                        Receiver Organization
                                    </label>

                                    <input type="text"
                                           name="receiver_organization"
                                           value="{{ old(
                                               'receiver_organization',
                                               $correspondence->receiver_organization
                                           ) }}"
                                           class="form-control"
                                           maxlength="255">

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Action & Response --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white">

                <h6 class="mb-0">
                    <i class="bi bi-check2-square me-1"></i>
                    Action & Response Tracking
                </h6>

            </div>


            <div class="card-body">

                <div class="row g-3">

                    {{-- Priority --}}
                    <div class="col-lg-3 col-md-6">

                        <label class="form-label">
                            Priority
                            <span class="text-danger">*</span>
                        </label>

                        <select name="priority"
                                class="form-select"
                                required>

                            @foreach([
                                'Low',
                                'Medium',
                                'High',
                                'Critical'
                            ] as $priority)

                                <option value="{{ $priority }}"
                                    {{ old(
                                        'priority',
                                        $correspondence->priority ?? 'Medium'
                                    ) == $priority ? 'selected' : '' }}>

                                    {{ $priority }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Response Required --}}
                    <div class="col-lg-3 col-md-6">

                        <label class="form-label">
                            Response Required
                        </label>

                        <div class="form-check form-switch mt-2">

                            <input class="form-check-input"
                                   type="checkbox"
                                   name="response_required"
                                   value="1"
                                   id="response_required"
                                   {{ old(
                                       'response_required',
                                       $correspondence->response_required
                                   ) ? 'checked' : '' }}>

                            <label class="form-check-label"
                                   for="response_required">

                                Yes

                            </label>

                        </div>

                    </div>


                    {{-- Response Due Date --}}
                    <div class="col-lg-3 col-md-6">

                        <label class="form-label">
                            Response Due Date
                        </label>

                        <input type="date"
                               name="response_due_date"
                               value="{{ old(
                                   'response_due_date',
                                   optional($correspondence->response_due_date)->format('Y-m-d')
                               ) }}"
                               class="form-control">

                    </div>


                    {{-- Assigned To --}}
                    <div class="col-lg-3 col-md-6">

                        <label class="form-label">
                            Assigned To
                        </label>

                        <select name="assigned_to"
                                class="form-select">

                            <option value="">
                                Select User
                            </option>

                            @foreach($users as $user)

                                <option value="{{ $user->id }}"
                                    {{ old(
                                        'assigned_to',
                                        $correspondence->assigned_to
                                    ) == $user->id ? 'selected' : '' }}>

                                    {{ $user->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Action Required --}}
                    <div class="col-lg-3 col-md-6">

                        <label class="form-label">
                            Action Required
                        </label>

                        <div class="form-check form-switch mt-2">

                            <input class="form-check-input"
                                   type="checkbox"
                                   name="action_required"
                                   value="1"
                                   id="action_required"
                                   {{ old(
                                       'action_required',
                                       $correspondence->action_required
                                   ) ? 'checked' : '' }}>

                            <label class="form-check-label"
                                   for="action_required">

                                Yes

                            </label>

                        </div>

                    </div>


                    {{-- Responsible Party Type --}}
                    <div class="col-lg-3 col-md-6">

                        <label class="form-label">
                            Responsible Party Type
                        </label>

                        <select name="responsible_party_type"
                                class="form-select">

                            <option value="">
                                Select
                            </option>

                            @foreach([
                                'Client',
                                'Consultant',
                                'Contractor',
                                'Supplier',
                                'Project Team',
                                'Other'
                            ] as $type)

                                <option value="{{ $type }}"
                                    {{ old(
                                        'responsible_party_type',
                                        $correspondence->responsible_party_type
                                    ) == $type ? 'selected' : '' }}>

                                    {{ $type }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Responsible Party Name --}}
                    <div class="col-lg-6 col-md-6">

                        <label class="form-label">
                            Responsible Party Name
                        </label>

                        <input type="text"
                               name="responsible_party_name"
                               value="{{ old(
                                   'responsible_party_name',
                                   $correspondence->responsible_party_name
                               ) }}"
                               class="form-control"
                               maxlength="255">

                    </div>


                    {{-- Action Description --}}
                    <div class="col-12">

                        <label class="form-label">
                            Action Description
                        </label>

                        <textarea name="action_description"
                                  class="form-control"
                                  rows="3"
                                  placeholder="Describe the action required...">{{ old(
                                      'action_description',
                                      $correspondence->action_description
                                  ) }}</textarea>

                    </div>

                </div>

            </div>

        </div>


        {{-- Description & Remarks --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white">

                <h6 class="mb-0">
                    <i class="bi bi-file-text me-1"></i>
                    Description & Remarks
                </h6>

            </div>


            <div class="card-body">

                <div class="mb-3">

                    <label class="form-label">
                        Description
                    </label>

                    <textarea name="description"
                              class="form-control"
                              rows="5"
                              placeholder="Enter detailed correspondence description...">{{ old(
                                  'description',
                                  $correspondence->description
                              ) }}</textarea>

                </div>


                <div>

                    <label class="form-label">
                        Remarks
                    </label>

                    <textarea name="remarks"
                              class="form-control"
                              rows="3"
                              placeholder="Additional remarks...">{{ old(
                                  'remarks',
                                  $correspondence->remarks
                              ) }}</textarea>

                </div>

            </div>

        </div>


        {{-- Current Status --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white">

                <h6 class="mb-0">
                    <i class="bi bi-info-circle me-1"></i>
                    Current Status
                </h6>

            </div>


            <div class="card-body">

                <div class="d-flex align-items-center gap-3">

                    @php

                        $statusClass = match($correspondence->status) {
                            'Draft' => 'bg-secondary-subtle text-secondary',
                            'Registered' => 'bg-info-subtle text-info',
                            'Under Review' => 'bg-primary-subtle text-primary',
                            'Action Required' => 'bg-warning-subtle text-warning',
                            'Responded' => 'bg-success-subtle text-success',
                            'Closed' => 'bg-dark-subtle text-dark',
                            'Archived' => 'bg-light text-dark',
                            default => 'bg-light text-dark',
                        };

                    @endphp

                    <span class="badge {{ $statusClass }} fs-6">
                        {{ $correspondence->status }}
                    </span>

                    <span class="text-muted small">
                        Status is controlled through the correspondence workflow.
                    </span>

                </div>

            </div>

        </div>


        {{-- Form Actions --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-body">

                <div class="d-flex justify-content-end gap-2">

                    <a href="{{ route(
                        'admin.projects.construction.correspondence.show',
                        [$project, $correspondence]
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

            </div>

        </div>

    </form>

</div>

@endsection