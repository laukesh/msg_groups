<?php

namespace App\Http\Controllers\Admin\Leasing;

use App\Http\Controllers\Controller;
use App\Models\LeaseAgreement;
use App\Models\LeaseTermination;
use App\Services\LeaseHistoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\LeaseHistory;
class LeaseTerminationController extends Controller
{
    public function index()
    {
        $terminations = LeaseTermination::with([
            'agreement.tenant'
        ])
        ->latest('id')
        ->paginate(20);

        return view(
            'admin.leasing.terminations.index',
            compact('terminations')
        );
    }


    public function create()
    {
        $agreements = LeaseAgreement::with('tenant')
            ->where('agreement_status', 'Active')
            ->orderBy('agreement_no')
            ->get();

        return view(
            'admin.leasing.terminations.create',
            compact('agreements')
        );
    }


    public function store(Request $request)
    {
        $validated = $request->validate([

            'lease_agreement_id' => [
                'required',
                'exists:lease_agreements,id'
            ],

            'termination_type' => [
                'required',
                'in:Lease Expiry,Tenant Request,Mall Request,Mutual Agreement,Legal,Default'
            ],

            'request_date' => [
                'required',
                'date'
            ],

            'notice_date' => [
                'nullable',
                'date'
            ],

            'effective_date' => [
                'required',
                'date'
            ],

            'reason' => [
                'nullable',
                'string'
            ],

            'outstanding_amount' => [
                'nullable',
                'numeric',
                'min:0'
            ],

            'penalty_amount' => [
                'nullable',
                'numeric',
                'min:0'
            ],

            'damage_charges' => [
                'nullable',
                'numeric',
                'min:0'
            ],

            'refundable_deposit' => [
                'nullable',
                'numeric',
                'min:0'
            ],

            'final_settlement_amount' => [
                'nullable',
                'numeric'
            ],

            'remarks' => [
                'nullable',
                'string'
            ],
        ]);


        $agreement = LeaseAgreement::findOrFail(
            $validated['lease_agreement_id']
        );


        if ($agreement->agreement_status !== 'Active') {

            return back()
                ->withInput()
                ->with('error',
                    'Only active lease agreements can be terminated.'
                );
        }


        $lastId = LeaseTermination::max('id') ?? 0;

        $terminationNo =
            'TRM-' .
            date('Y') .
            '-' .
            str_pad(
                $lastId + 1,
                5,
                '0',
                STR_PAD_LEFT
            );


        $termination = LeaseTermination::create([

            'lease_agreement_id' =>
                $agreement->id,

            'termination_no' =>
                $terminationNo,

            'termination_type' =>
                $validated['termination_type'],

            'request_date' =>
                $validated['request_date'],

            'notice_date' =>
                $validated['notice_date'] ?? null,

            'effective_date' =>
                $validated['effective_date'],

            'reason' =>
                $validated['reason'] ?? null,

            'outstanding_amount' =>
                $validated['outstanding_amount'] ?? 0,

            'penalty_amount' =>
                $validated['penalty_amount'] ?? 0,

            'damage_charges' =>
                $validated['damage_charges'] ?? 0,

            'refundable_deposit' =>
                $validated['refundable_deposit'] ?? 0,

            'final_settlement_amount' =>
                $validated['final_settlement_amount'] ?? 0,

            'inspection_status' =>
                'Pending',

            'handover_status' =>
                'Pending',

            'termination_status' =>
                'Draft',

            'remarks' =>
                $validated['remarks'] ?? null,

            'created_by' =>
                auth()->id(),

            'updated_by' =>
                auth()->id(),
        ]);


        LeaseHistoryService::log(

            $agreement->id,

            'Termination',

            'Termination Request Created',

            'A lease termination request was created.',

            null,

            [
                'termination_no' =>
                    $termination->termination_no,

                'termination_type' =>
                    $termination->termination_type,

                'effective_date' =>
                    $termination->effective_date?->format('Y-m-d'),
            ],

            'LeaseTermination',

            $termination->id

        );


        return redirect()
            ->route(
                'admin.leasing.terminations.show',
                $termination->id
            )
            ->with(
                'success',
                'Lease termination request created successfully.'
            );
    }


    public function show($id)
    {
        $termination = LeaseTermination::with([
            'agreement.tenant',
            'approver'
        ])->findOrFail($id);

        return view(
            'admin.leasing.terminations.show',
            compact('termination')
        );
    }


    public function edit($id)
    {
        $termination =
            LeaseTermination::findOrFail($id);

        $agreements = LeaseAgreement::with('tenant')
            ->where('agreement_status', 'Active')
            ->orderBy('agreement_no')
            ->get();

        return view(
            'admin.leasing.terminations.edit',
            compact(
                'termination',
                'agreements'
            )
        );
    }


    public function update(
        Request $request,
        $id
    ) {

        $termination =
            LeaseTermination::findOrFail($id);


        if (
            !in_array(
                $termination->termination_status,
                ['Draft', 'Pending Approval']
            )
        ) {

            return back()->with(
                'error',
                'This termination cannot be edited.'
            );
        }


        $validated = $request->validate([

            'termination_type' => [
                'required',
                'in:Lease Expiry,Tenant Request,Mall Request,Mutual Agreement,Legal,Default'
            ],

            'request_date' => [
                'required',
                'date'
            ],

            'notice_date' => [
                'nullable',
                'date'
            ],

            'effective_date' => [
                'required',
                'date'
            ],

            'reason' => [
                'nullable',
                'string'
            ],

            'outstanding_amount' => [
                'nullable',
                'numeric',
                'min:0'
            ],

            'penalty_amount' => [
                'nullable',
                'numeric',
                'min:0'
            ],

            'damage_charges' => [
                'nullable',
                'numeric',
                'min:0'
            ],

            'refundable_deposit' => [
                'nullable',
                'numeric',
                'min:0'
            ],

            'final_settlement_amount' => [
                'nullable',
                'numeric'
            ],

            'remarks' => [
                'nullable',
                'string'
            ],
        ]);


        $termination->update([

            ...$validated,

            'updated_by' =>
                auth()->id(),
        ]);


        return redirect()
            ->route(
                'admin.leasing.terminations.show',
                $termination->id
            )
            ->with(
                'success',
                'Termination updated successfully.'
            );
    }


    public function submit($id)
    {
        $termination =
            LeaseTermination::findOrFail($id);


        if (
            $termination->termination_status !== 'Draft'
        ) {

            return back()->with(
                'error',
                'Only draft termination requests can be submitted.'
            );
        }


        $termination->update([

            'termination_status' =>
                'Pending Approval',

            'updated_by' =>
                auth()->id(),
        ]);


        LeaseHistoryService::log(

            $termination->lease_agreement_id,

            'Termination',

            'Termination Submitted',

            'Termination request submitted for approval.',

            [
                'status' => 'Draft'
            ],

            [
                'status' => 'Pending Approval'
            ],

            'LeaseTermination',

            $termination->id
        );


        return back()->with(
            'success',
            'Termination submitted for approval.'
        );
    }


    public function approve($id)
    {
        $termination =
            LeaseTermination::with('agreement')
                ->findOrFail($id);


        if (
            $termination->termination_status !==
            'Pending Approval'
        ) {

            return back()->with(
                'error',
                'Only pending termination requests can be approved.'
            );
        }


        DB::beginTransaction();

        try {

            $agreement =
                $termination->agreement;


            $oldStatus =
                $agreement->agreement_status;


            $oldStatus = $agreement->agreement_status;
            $termination->update([
                'termination_status' => 'Approved',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
                'updated_by' => auth()->id(),
            ]);


            LeaseHistoryService::log(

                $agreement->id,

                'Termination',

                'Lease Termination Approved',

                'Lease termination request was approved. Agreement remains active until the termination process is completed.',

                [
                    'agreement_status' =>
                        $oldStatus
                ],

                [
                    'agreement_status' =>
                        'Terminated',

                    'termination_no' =>
                        $termination->termination_no,

                    'effective_date' =>
                        $termination->effective_date?->format('Y-m-d'),
                ],

                'LeaseTermination',

                $termination->id,

                $termination->reason
            );


            DB::commit();


            return back()->with(
                'success',
                'Lease agreement terminated successfully.'
            );

        } catch (\Throwable $e) {

            DB::rollBack();

            return back()->with(
                'error',
                'Unable to terminate lease: ' .
                $e->getMessage()
            );
        }
    }


    public function cancel($id)
    {
        $termination =
            LeaseTermination::findOrFail($id);


        if (
            in_array(
                $termination->termination_status,
                ['Approved', 'Completed']
            )
        ) {

            return back()->with(
                'error',
                'This termination cannot be cancelled.'
            );
        }


        $oldStatus =
            $termination->termination_status;


        $termination->update([

            'termination_status' =>
                'Cancelled',

            'updated_by' =>
                auth()->id(),
        ]);


        LeaseHistoryService::log(

            $termination->lease_agreement_id,

            'Termination',

            'Termination Request Cancelled',

            'Lease termination request was cancelled.',

            [
                'status' => $oldStatus
            ],

            [
                'status' => 'Cancelled'
            ],

            'LeaseTermination',

            $termination->id
        );


        return back()->with(
            'success',
            'Termination request cancelled.'
        );
    }

    public function completeInspection($id)
    {
        $termination = LeaseTermination::findOrFail($id);

        if ($termination->termination_status !== 'Approved') {
            return back()->with(
                'error',
                'Only approved termination can proceed with inspection.'
            );
        }

        $termination->update([
            'inspection_status' => 'Completed',
            'updated_by' => auth()->id(),
        ]);

        LeaseHistory::create([
            'lease_agreement_id' => $termination->lease_agreement_id,
            'activity_type' => 'Inspection',
            'reference_module' => 'lease_terminations',
            'reference_id' => $termination->id,
            'activity_title' => 'Termination Inspection Completed',
            'activity_description' =>
                'Exit inspection completed for lease termination.',
            'performed_by' => auth()->id(),
            'ip_address' => request()->ip(),
            'device_info' => request()->userAgent(),
        ]);

        return back()->with(
            'success',
            'Inspection marked as completed.'
        );
    }

    public function completeHandover($id)
    {
        $termination = LeaseTermination::findOrFail($id);

        if ($termination->termination_status !== 'Approved') {
            return back()->with(
                'error',
                'Only approved termination can proceed with handover.'
            );
        }

        if ($termination->inspection_status !== 'Completed') {
            return back()->with(
                'error',
                'Please complete the inspection first.'
            );
        }

        $termination->update([
            'handover_status' => 'Completed',
            'updated_by' => auth()->id(),
        ]);

        LeaseHistory::create([
            'lease_agreement_id' => $termination->lease_agreement_id,
            'activity_type' => 'Handover',
            'reference_module' => 'lease_terminations',
            'reference_id' => $termination->id,
            'activity_title' => 'Premises Handover Completed',
            'activity_description' =>
                'Tenant premises handover completed.',
            'performed_by' => auth()->id(),
            'ip_address' => request()->ip(),
            'device_info' => request()->userAgent(),
        ]);

        return back()->with(
            'success',
            'Handover marked as completed.'
        );
    }

    public function complete($id)
    {
        $termination = LeaseTermination::findOrFail($id);

        if ($termination->termination_status !== 'Approved') {
            return back()->with(
                'error',
                'Only approved termination can be completed.'
            );
        }

        if ($termination->inspection_status !== 'Completed') {
            return back()->with(
                'error',
                'Please complete the inspection first.'
            );
        }

        if ($termination->handover_status !== 'Completed') {
            return back()->with(
                'error',
                'Please complete the handover first.'
            );
        }

        DB::transaction(function () use ($termination) {

            $oldStatus = $termination->termination_status;

            $termination->update([
                'termination_status' => 'Completed',
                'updated_by' => auth()->id(),
            ]);

            $agreement = LeaseAgreement::findOrFail(
                $termination->lease_agreement_id
            );

            $oldAgreementStatus = $agreement->agreement_status;

            $agreement->update([
                'agreement_status' => 'Terminated',
                'updated_by' => auth()->id(),
            ]);

            LeaseHistory::create([
                'lease_agreement_id' =>
                    $termination->lease_agreement_id,

                'activity_type' => 'Termination',

                'reference_module' =>
                    'lease_terminations',

                'reference_id' =>
                    $termination->id,

                'activity_title' =>
                    'Lease Termination Completed',

                'activity_description' =>
                    'Lease termination process completed successfully.',

                'old_value' => json_encode([
                    'termination_status' => $oldStatus,
                    'agreement_status' => $oldAgreementStatus,
                ]),

                'new_value' => json_encode([
                    'termination_status' => 'Completed',
                    'agreement_status' => 'Terminated',
                ]),

                'performed_by' => auth()->id(),

                'ip_address' => request()->ip(),

                'device_info' => request()->userAgent(),
            ]);
        });

        return redirect()
            ->route(
                'admin.leasing.terminations.show',
                $termination->id
            )
            ->with(
                'success',
                'Lease termination completed successfully.'
            );
    }

}