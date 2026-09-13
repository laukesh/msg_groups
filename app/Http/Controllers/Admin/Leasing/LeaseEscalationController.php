<?php

namespace App\Http\Controllers\Admin\Leasing;

use App\Http\Controllers\Controller;
use App\Models\LeaseAgreement;
use App\Models\LeaseEscalation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\LeaseHistoryService;

class LeaseEscalationController extends Controller
{
    public function index()
    {
        $escalations = LeaseEscalation::with([
            'agreement.tenant'
        ])
        ->latest('id')
        ->paginate(20);

        return view(
            'admin.leasing.escalations.index',
            compact('escalations')
        );
    }


    public function create()
    {
        $agreements = LeaseAgreement::with('tenant')
            ->where('agreement_status', 'Active')
            ->orderBy('agreement_no')
            ->get();

        return view(
            'admin.leasing.escalations.create',
            compact('agreements')
        );
    }


    public function store(Request $request)
    {
        $validated = $request->validate([

            'lease_agreement_id' =>
                'required|exists:lease_agreements,id',

            'effective_from' =>
                'required|date',

            'escalation_type' =>
                'required|in:Percentage,Fixed Amount',

            'escalation_value' =>
                'required|numeric|min:0',

            'remarks' =>
                'nullable|string',

        ]);


        $agreement = LeaseAgreement::findOrFail(
            $validated['lease_agreement_id']
        );


        if ($agreement->agreement_status !== 'Active') {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Escalation can only be created for an active agreement.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Get Next Escalation Number
        |--------------------------------------------------------------------------
        */

        $lastEscalation = LeaseEscalation::where(
            'lease_agreement_id',
            $agreement->id
        )
        ->orderByDesc('escalation_no')
        ->first();


        $escalationNo = $lastEscalation
            ? $lastEscalation->escalation_no + 1
            : 1;


        /*
        |--------------------------------------------------------------------------
        | Previous Rent
        |--------------------------------------------------------------------------
        */

        $previousRent = (float) (
            $agreement->monthly_rent ?? 0
        );


        /*
        |--------------------------------------------------------------------------
        | Calculate Revised Rent
        |--------------------------------------------------------------------------
        */

        $value = (float) $validated['escalation_value'];


        if (
            $validated['escalation_type']
            === 'Percentage'
        ) {

            $revisedRent =
                $previousRent
                + (
                    $previousRent
                    * $value
                    / 100
                );

        } else {

            $revisedRent =
                $previousRent + $value;
        }


        /*
        |--------------------------------------------------------------------------
        | Create Escalation
        |--------------------------------------------------------------------------
        */

        LeaseEscalation::create([

            'lease_agreement_id' =>
                $agreement->id,

            'escalation_no' =>
                $escalationNo,

            'effective_from' =>
                $validated['effective_from'],

            'previous_rent' =>
                $previousRent,

            'escalation_type' =>
                $validated['escalation_type'],

            'escalation_value' =>
                $value,

            'revised_rent' =>
                round($revisedRent, 2),

            'status' =>
                'Pending',

            'remarks' =>
                $validated['remarks'] ?? null,

            'created_by' =>
                auth()->id(),

            'updated_by' =>
                auth()->id(),

        ]);


        return redirect()
            ->route(
                'admin.leasing.escalations.index'
            )
            ->with(
                'success',
                'Lease escalation created successfully.'
            );
    }


    public function show(LeaseEscalation $escalation)
    {
        $escalation->load([
            'agreement.tenant'
        ]);

        return view(
            'admin.leasing.escalations.show',
            compact('escalation')
        );
    }


    public function approve(
        LeaseEscalation $escalation
    ) {

        if ($escalation->status !== 'Pending') {

            return back()->with(
                'error',
                'Only pending escalations can be applied.'
            );
        }


        DB::transaction(function () use (
            $escalation
        ) {

            $agreement =
                $escalation->agreement;


            if (!$agreement) {

                throw new \Exception(
                    'Lease agreement not found.'
                );
            }


            if (
                $agreement->agreement_status
                !== 'Active'
            ) {

                throw new \Exception(
                    'Only active agreements can have rent escalated.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Update Agreement Rent
            |--------------------------------------------------------------------------
            */

            $agreement->update([

                'monthly_rent' =>
                    $escalation->revised_rent,

                'updated_by' =>
                    auth()->id(),

            ]);

            LeaseHistoryService::log(

                $agreement->id,

                'Escalation',

                'Rent Escalation Applied',

                'Monthly rent was increased through lease escalation.',

                [
                    'monthly_rent' => $escalation->previous_rent
                ],

                [
                    'monthly_rent' => $escalation->revised_rent
                ],

                'LeaseEscalation',

                $escalation->id,

                $escalation->remarks
            );


            /*
            |--------------------------------------------------------------------------
            | Update Escalation
            |--------------------------------------------------------------------------
            */

            $escalation->update([

                'status' =>
                    'Applied',

                'approved_by' =>
                    auth()->id(),

                'approved_at' =>
                    now(),

                'updated_by' =>
                    auth()->id(),

            ]);
        });


        return redirect()
            ->route(
                'admin.leasing.escalations.show',
                $escalation->id
            )
            ->with(
                'success',
                'Rent escalation applied successfully.'
            );
    }


    public function cancel(
        LeaseEscalation $escalation
    ) {

        if ($escalation->status !== 'Pending') {

            return back()->with(
                'error',
                'Only pending escalations can be cancelled.'
            );
        }


        $escalation->update([

            'status' =>
                'Cancelled',

            'updated_by' =>
                auth()->id(),

        ]);


        return redirect()
            ->route(
                'admin.leasing.escalations.show',
                $escalation->id
            )
            ->with(
                'success',
                'Escalation cancelled successfully.'
            );
    }
}