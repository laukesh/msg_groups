<?php

namespace App\Http\Controllers\Admin\Fitout;

use App\Http\Controllers\Controller;
use App\Models\FitoutApproval;
use App\Models\FitoutRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class FitoutApprovalController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | All Approvals
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $approvals = FitoutApproval::with([
            'fitoutRequest.tenant',
            'fitoutRequest.unit',
            'fitoutRequest.contractor',
            'approver',
        ])
        ->latest('id')
        ->paginate(20);

        return view(
            'admin.fitout.approvals.index',
            compact('approvals')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Pending Approvals
    |--------------------------------------------------------------------------
    */

    public function pending()
	{
	    $approvals = FitoutApproval::with([
	        'fitoutRequest.tenant',
	        'fitoutRequest.unit',
	        'fitoutRequest.contractor',
	        'approver',
	    ])
	    ->where('approval_status', 'Pending')
	    ->whereNotExists(function ($query) {
	        $query->select(DB::raw(1))
	            ->from('fitout_approvals as previous')
	            ->whereColumn(
	                'previous.fitout_request_id',
	                'fitout_approvals.fitout_request_id'
	            )
	            ->whereColumn(
	                'previous.approval_level',
	                '<',
	                'fitout_approvals.approval_level'
	            )
	            ->where(
	                'previous.approval_status',
	                '!=',
	                'Approved'
	            );
	    })
	    ->orderBy('approval_level')
	    ->latest('id')
	    ->paginate(20);

	    return view(
	        'admin.fitout.approvals.pending',
	        compact('approvals')
	    );
	}


    /*
    |--------------------------------------------------------------------------
    | Generate Approval Chain
    |--------------------------------------------------------------------------
    */

    public function generate($fitoutRequestId)
    {
        $fitoutRequest = FitoutRequest::findOrFail(
            $fitoutRequestId
        );

        /*
        |--------------------------------------------------------------------------
        | Prevent duplicate approval chain
        |--------------------------------------------------------------------------
        */

        if ($fitoutRequest->approvals()->exists()) {

            return back()->with(
                'error',
                'Approval workflow has already been generated for this Fit-Out Request.'
            );
        }


        DB::transaction(function () use ($fitoutRequest) {

            $levels = [
                [
                    'level' => 1,
                    'type'  => 'Technical Approval',
                ],
                [
                    'level' => 2,
                    'type'  => 'Commercial Approval',
                ],
                [
                    'level' => 3,
                    'type'  => 'Final Approval',
                ],
            ];


            foreach ($levels as $index => $level) {

                FitoutApproval::create([

                    'uuid' =>
                        (string) Str::uuid(),

                    'fitout_request_id' =>
                        $fitoutRequest->id,

                    'approval_level' =>
                        $level['level'],

                    'approval_type' =>
                        $level['type'],

                    /*
                    |--------------------------------------------------------------------------
                    | Only first level is active initially
                    |--------------------------------------------------------------------------
                    */

                    'approval_status' =>
                        $level['level'] === 1
                            ? 'Pending'
                            : 'Pending',

                    'created_by' =>
                        auth()->id(),

                    'updated_by' =>
                        auth()->id(),

                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Move request to Under Review
            |--------------------------------------------------------------------------
            */

            $fitoutRequest->update([
                'fitout_status' => 'Under Review',
                'updated_by' => auth()->id(),
            ]);
        });


        return back()->with(
            'success',
            'Fit-Out approval workflow generated successfully.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Approval Details
    |--------------------------------------------------------------------------
    */

    public function show($id)
	{
	    $approval = FitoutApproval::with([
	        'fitoutRequest.tenant',
	        'fitoutRequest.unit',
	        'fitoutRequest.contractor',
	        'fitoutRequest.documents',
	        'fitoutRequest.stages',
	        'fitoutRequest.approvals.approver',
	        'approver',
	    ])->findOrFail($id);

	    return view(
	        'admin.fitout.approvals.show',
	        compact('approval')
	    );
	}


    /*
    |--------------------------------------------------------------------------
    | Approve
    |--------------------------------------------------------------------------
    */

    public function approve($id)
    {
        $approval = FitoutApproval::with(
            'fitoutRequest'
        )->findOrFail($id);


        /*
        |--------------------------------------------------------------------------
        | Only Pending approval can be approved
        |--------------------------------------------------------------------------
        */

        if ($approval->approval_status !== 'Pending') {

            return back()->with(
                'error',
                'This approval has already been processed.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Check previous approval level
        |--------------------------------------------------------------------------
        */

        if ($approval->approval_level > 1) {

            $previousApproval = FitoutApproval::where(
                'fitout_request_id',
                $approval->fitout_request_id
            )
            ->where(
                'approval_level',
                $approval->approval_level - 1
            )
            ->first();


            if (
                !$previousApproval ||
                $previousApproval->approval_status !== 'Approved'
            ) {

                return back()->with(
                    'error',
                    'Previous approval level must be approved first.'
                );
            }
        }


        DB::transaction(function () use ($approval) {

            /*
            |--------------------------------------------------------------------------
            | Approve current level
            |--------------------------------------------------------------------------
            */

            $approval->update([

                'approval_status' =>
                    'Approved',

                'approved_at' =>
                    now(),

                'approver_id' =>
                    auth()->id(),

                'updated_by' =>
                    auth()->id(),

            ]);


            /*
            |--------------------------------------------------------------------------
            | Check whether this was final approval
            |--------------------------------------------------------------------------
            */

            $totalLevels = FitoutApproval::where(
                'fitout_request_id',
                $approval->fitout_request_id
            )->count();


            $approvedLevels = FitoutApproval::where(
                'fitout_request_id',
                $approval->fitout_request_id
            )
            ->where(
                'approval_status',
                'Approved'
            )
            ->count();


            if ($totalLevels === $approvedLevels) {

                /*
                |--------------------------------------------------------------------------
                | Entire Fit-Out approved
                |--------------------------------------------------------------------------
                */

                $approval->fitoutRequest->update([

                    'fitout_status' =>
                        'Approved',

                    'updated_by' =>
                        auth()->id(),

                ]);

            }
        });


        return redirect()
            ->route(
                'admin.fitout.approvals.show',
                $approval->id
            )
            ->with(
                'success',
                'Approval processed successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Reject
    |--------------------------------------------------------------------------
    */

    public function reject(
        Request $request,
        $id
    ) {

        $request->validate([

            'rejection_reason' => [
                'required',
                'string',
                'max:1000',
            ],

        ]);


        $approval = FitoutApproval::with(
            'fitoutRequest'
        )->findOrFail($id);


        if ($approval->approval_status !== 'Pending') {

            return back()->with(
                'error',
                'This approval has already been processed.'
            );
        }


        DB::transaction(function () use (
            $approval,
            $request
        ) {

            $approval->update([

                'approval_status' =>
                    'Rejected',

                'rejection_reason' =>
                    $request->rejection_reason,

                'approver_id' =>
                    auth()->id(),

                'approved_at' =>
                    null,

                'updated_by' =>
                    auth()->id(),

            ]);


            /*
            |--------------------------------------------------------------------------
            | Entire Fit-Out Request becomes Rejected
            |--------------------------------------------------------------------------
            */

            $approval->fitoutRequest->update([

                'fitout_status' =>
                    'Rejected',

                'rejection_reason' =>
                    $request->rejection_reason,

                'updated_by' =>
                    auth()->id(),

            ]);
        });


        return redirect()
            ->route(
                'admin.fitout.approvals.show',
                $approval->id
            )
            ->with(
                'success',
                'Fit-Out Request rejected successfully.'
            );
    }


}