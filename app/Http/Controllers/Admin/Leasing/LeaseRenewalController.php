<?php

namespace App\Http\Controllers\Admin\Leasing;

use App\Http\Controllers\Controller;
use App\Models\LeaseAgreement;
use App\Models\LeaseRenewal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\LeaseTerm;
class LeaseRenewalController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Index
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $renewals = LeaseRenewal::with([
						    'agreement.tenant',
						    'approvedBy',
						])
						->latest('id')
						->paginate(15);

        return view(
            'admin.leasing.renewals.index',
            compact('renewals')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Create
    |--------------------------------------------------------------------------
    */

    public function create(Request $request)
    {
        $agreement = null;

        if ($request->filled('lease_agreement_id')) {

            $agreement = LeaseAgreement::with([
                'tenant',
                'terms',
            ])->findOrFail(
                $request->lease_agreement_id
            );
        }

        $agreements = LeaseAgreement::with('tenant')
            ->where('agreement_status', 'Active')
            ->orderBy('agreement_no')
            ->get();

        return view(
            'admin.leasing.renewals.create',
            compact(
                'agreement',
                'agreements'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Store
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $validated = $request->validate([

            'lease_agreement_id' =>
                'required|exists:lease_agreements,id',

            'request_date' =>
                'required|date',

            'proposed_start_date' =>
                'required|date',

            'proposed_end_date' =>
                'required|date|after:proposed_start_date',

            'renewal_period_months' =>
                'nullable|integer|min:1',

            'proposed_rent' =>
                'required|numeric|min:0',

            'proposed_security_deposit' =>
                'nullable|numeric|min:0',

            'escalation_percentage' =>
                'nullable|numeric|min:0|max:100',

            'negotiation_notes' =>
                'nullable|string',

            'remarks' =>
                'nullable|string',
        ]);


        $agreement = LeaseAgreement::findOrFail(
            $validated['lease_agreement_id']
        );


        /*
        |--------------------------------------------------------------------------
        | Generate Renewal Number
        |--------------------------------------------------------------------------
        */

        $year = now()->format('Y');

        $lastRenewal = LeaseRenewal::where(
            'renewal_no',
            'LIKE',
            "RN-{$year}-%"
        )
        ->orderByDesc('id')
        ->first();


        if ($lastRenewal) {

            $lastNumber = (int) substr(
                $lastRenewal->renewal_no,
                -4
            );

            $nextNumber = $lastNumber + 1;

        } else {

            $nextNumber = 1;
        }


        $renewalNo = 'RN-' .
            $year .
            '-' .
            str_pad(
                $nextNumber,
                4,
                '0',
                STR_PAD_LEFT
            );


        /*
        |--------------------------------------------------------------------------
        | Create Renewal
        |--------------------------------------------------------------------------
        */

        $renewal = LeaseRenewal::create([

            'lease_agreement_id' =>
                $agreement->id,

            'renewal_no' =>
                $renewalNo,

            'request_date' =>
                $validated['request_date'],

            'current_expiry_date' =>
                $agreement->lease_end_date,

            'proposed_start_date' =>
                $validated['proposed_start_date'],

            'proposed_end_date' =>
                $validated['proposed_end_date'],

            'renewal_period_months' =>
                $validated['renewal_period_months'] ?? null,

            'current_rent' =>
                $agreement->monthly_rent ?? 0,

            'proposed_rent' =>
                $validated['proposed_rent'],

            'proposed_security_deposit' =>
                $validated['proposed_security_deposit'] ?? 0,

            'escalation_percentage' =>
                $validated['escalation_percentage'] ?? 0,

            'negotiation_notes' =>
                $validated['negotiation_notes'] ?? null,

            'approval_status' =>
                'Draft',

            'remarks' =>
                $validated['remarks'] ?? null,

            'created_by' =>
                Auth::id(),

            'updated_by' =>
                Auth::id(),
        ]);


        return redirect()
            ->route(
                'admin.leasing.renewals.show',
                $renewal->id
            )
            ->with(
                'success',
                'Lease renewal created successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Show
    |--------------------------------------------------------------------------
    */

    public function show(LeaseRenewal $renewal)
    {
        $renewal->load([
            'agreement.tenant',
            'agreement.terms',
            'approvedBy',
            'createdBy',
            'updatedBy',
        ]);

        return view(
            'admin.leasing.renewals.show',
            compact('renewal')
        );
    }

    /*
	|--------------------------------------------------------------------------
	| Submit for Approval
	|--------------------------------------------------------------------------
	*/

	public function submit(LeaseRenewal $renewal)
	{
	    if ($renewal->approval_status !== 'Draft') {
	        return back()->with(
	            'error',
	            'Only draft renewals can be submitted.'
	        );
	    }

	    $renewal->update([
	        'approval_status' => 'Pending',
	        'updated_by' => auth()->id(),
	    ]);

	    return redirect()
	        ->route(
	            'admin.leasing.renewals.show',
	            $renewal->id
	        )
	        ->with(
	            'success',
	            'Lease renewal submitted for approval.'
	        );
	}


	/*
	|--------------------------------------------------------------------------
	| Approve
	|--------------------------------------------------------------------------
	*/

	public function approve(LeaseRenewal $renewal)
	{
	    if ($renewal->approval_status !== 'Pending') {
	        return back()->with(
	            'error',
	            'Only pending renewals can be approved.'
	        );
	    }

	    $renewal->update([
	        'approval_status' => 'Approved',
	        'approved_by' => auth()->id(),
	        'approved_at' => now(),
	        'updated_by' => auth()->id(),
	    ]);

	    return redirect()
	        ->route(
	            'admin.leasing.renewals.show',
	            $renewal->id
	        )
	        ->with(
	            'success',
	            'Lease renewal approved successfully.'
	        );
	}


	/*
	|--------------------------------------------------------------------------
	| Reject
	|--------------------------------------------------------------------------
	*/

	public function reject(Request $request, LeaseRenewal $renewal)
	{
	    if ($renewal->approval_status !== 'Pending') {
	        return back()->with(
	            'error',
	            'Only pending renewals can be rejected.'
	        );
	    }

	    $renewal->update([
	        'approval_status' => 'Rejected',
	        'updated_by' => auth()->id(),
	    ]);

	    return redirect()
	        ->route(
	            'admin.leasing.renewals.show',
	            $renewal->id
	        )
	        ->with(
	            'success',
	            'Lease renewal rejected.'
	        );
	}


	/*
	|--------------------------------------------------------------------------
	| Cancel
	|--------------------------------------------------------------------------
	*/

	public function cancel(LeaseRenewal $renewal)
	{
	    if (!in_array(
	        $renewal->approval_status,
	        ['Draft', 'Pending']
	    )) {
	        return back()->with(
	            'error',
	            'This renewal cannot be cancelled.'
	        );
	    }

	    $renewal->update([
	        'approval_status' => 'Cancelled',
	        'updated_by' => auth()->id(),
	    ]);

	    return redirect()
	        ->route(
	            'admin.leasing.renewals.show',
	            $renewal->id
	        )
	        ->with(
	            'success',
	            'Lease renewal cancelled.'
	        );
	}

	public function edit(LeaseRenewal $renewal)
	{
	    if ($renewal->approval_status !== 'Draft') {
	        return redirect()
	            ->route(
	                'admin.leasing.renewals.show',
	                $renewal->id
	            )
	            ->with(
	                'error',
	                'Only draft renewals can be edited.'
	            );
	    }

	    $agreements = LeaseAgreement::with('tenant')
	        ->where('agreement_status', 'Active')
	        ->orderBy('agreement_no')
	        ->get();

	    return view(
	        'admin.leasing.renewals.edit',
	        compact(
	            'renewal',
	            'agreements'
	        )
	    );
	}


	public function update( Request $request, LeaseRenewal $renewal ) {
	    if ($renewal->approval_status !== 'Draft') {
	        return redirect()
	            ->route(
	                'admin.leasing.renewals.show',
	                $renewal->id
	            )
	            ->with(
	                'error',
	                'Only draft renewals can be edited.'
	            );
	    }

	    $validated = $request->validate([

	        'lease_agreement_id' => [
	            'required',
	            'exists:lease_agreements,id'
	        ],

	        'request_date' => [
	            'required',
	            'date'
	        ],

	        'proposed_start_date' => [
	            'required',
	            'date'
	        ],

	        'proposed_end_date' => [
	            'required',
	            'date',
	            'after_or_equal:proposed_start_date'
	        ],

	        'renewal_period_months' => [
	            'nullable',
	            'integer',
	            'min:1'
	        ],

	        'proposed_rent' => [
	            'nullable',
	            'numeric',
	            'min:0'
	        ],

	        'proposed_security_deposit' => [
	            'nullable',
	            'numeric',
	            'min:0'
	        ],

	        'escalation_percentage' => [
	            'nullable',
	            'numeric',
	            'min:0',
	            'max:100'
	        ],

	        'negotiation_notes' => [
	            'nullable',
	            'string'
	        ],

	        'remarks' => [
	            'nullable',
	            'string'
	        ],

	    ]);

	    $agreement = LeaseAgreement::findOrFail(
	        $validated['lease_agreement_id']
	    );

	    $renewal->update([

	        'lease_agreement_id' =>
	            $agreement->id,

	        'request_date' =>
	            $validated['request_date'],

	        'current_expiry_date' =>
	            $agreement->lease_end_date,

	        'proposed_start_date' =>
	            $validated['proposed_start_date'],

	        'proposed_end_date' =>
	            $validated['proposed_end_date'],

	        'renewal_period_months' =>
	            $validated['renewal_period_months'] ?? null,

	        'current_rent' =>
	            $agreement->monthly_rent ?? 0,

	        'proposed_rent' =>
	            $validated['proposed_rent'] ?? 0,

	        'proposed_security_deposit' =>
	            $validated['proposed_security_deposit'] ?? 0,

	        'escalation_percentage' =>
	            $validated['escalation_percentage'] ?? 0,

	        'negotiation_notes' =>
	            $validated['negotiation_notes'] ?? null,

	        'remarks' =>
	            $validated['remarks'] ?? null,

	        'updated_by' =>
	            auth()->id(),

	    ]);

	    return redirect()
	        ->route(
	            'admin.leasing.renewals.show',
	            $renewal->id
	        )
	        ->with(
	            'success',
	            'Lease renewal updated successfully.'
	        );
	}

	public function convert(LeaseRenewal $renewal)
	{
	    if ($renewal->approval_status !== 'Approved') {

	        return redirect()
	            ->route(
	                'admin.leasing.renewals.show',
	                $renewal->id
	            )
	            ->with(
	                'error',
	                'Only approved renewals can be converted into a new lease agreement.'
	            );
	    }

	    $renewal->load([
	        'agreement.tenant',
	    ]);

	    $agreement = $renewal->agreement;

	    if (!$agreement) {

	        return back()->with(
	            'error',
	            'Original lease agreement not found.'
	        );
	    }

	    return view(
	        'admin.leasing.renewals.convert',
	        compact(
	            'renewal',
	            'agreement'
	        )
	    );
	}

	public function convertStore(LeaseRenewal $renewal)
	{
	    /*
	    |--------------------------------------------------------------------------
	    | Only Approved Renewal Can Be Converted
	    |--------------------------------------------------------------------------
	    */

	    if ($renewal->approval_status !== 'Approved') {

	        return redirect()
	            ->route(
	                'admin.leasing.renewals.show',
	                $renewal->id
	            )
	            ->with(
	                'error',
	                'Only approved renewals can be converted into a new agreement.'
	            );
	    }


	    /*
	    |--------------------------------------------------------------------------
	    | Load Renewal + Existing Agreement
	    |--------------------------------------------------------------------------
	    */

	    $renewal->load([
	        'agreement.tenant',
	        'agreement.terms',
	    ]);


	    $oldAgreement = $renewal->agreement;


	    if (!$oldAgreement) {

	        return back()->with(
	            'error',
	            'Original lease agreement not found.'
	        );
	    }


	    /*
	    |--------------------------------------------------------------------------
	    | Prevent Duplicate Conversion
	    |--------------------------------------------------------------------------
	    */

	    $alreadyRenewed = LeaseAgreement::where(
	        'remarks',
	        'like',
	        '%Renewal ID: ' . $renewal->id . '%'
	    )->exists();


	    if ($alreadyRenewed) {

	        return redirect()
	            ->route(
	                'admin.leasing.renewals.show',
	                $renewal->id
	            )
	            ->with(
	                'error',
	                'This renewal has already been converted into an agreement.'
	            );
	    }


	    /*
	    |--------------------------------------------------------------------------
	    | Generate Agreement Number
	    |--------------------------------------------------------------------------
	    */

	    $year = now()->format('Y');

	    $lastAgreement = LeaseAgreement::withTrashed()
	        ->where(
	            'agreement_no',
	            'like',
	            'LA-' . $year . '-%'
	        )
	        ->orderByDesc('id')
	        ->first();


	    if ($lastAgreement) {

	        $lastNumber = (int) substr(
	            $lastAgreement->agreement_no,
	            -4
	        );

	        $nextNumber = $lastNumber + 1;

	    } else {

	        $nextNumber = 1;
	    }


	    $agreementNo =
	        'LA-' .
	        $year .
	        '-' .
	        str_pad(
	            $nextNumber,
	            4,
	            '0',
	            STR_PAD_LEFT
	        );


	    /*
	    |--------------------------------------------------------------------------
	    | Create New Agreement
	    |--------------------------------------------------------------------------
	    */

	    try {

	        $newAgreement = DB::transaction(function () use (
	            $renewal,
	            $oldAgreement,
	            $agreementNo
	        ) {

	            /*
	            |--------------------------------------------------------------------------
	            | Create New Agreement
	            |--------------------------------------------------------------------------
	            */

	            $agreement = LeaseAgreement::create([

	                'uuid' =>
	                    (string) Str::uuid(),

	                'agreement_no' =>
	                    $agreementNo,

	                'proposal_id' =>
	                    $oldAgreement->proposal_id,

	                'tenant_id' =>
	                    $oldAgreement->tenant_id,

	                'agreement_date' =>
	                    now()->toDateString(),

	                'lease_start_date' =>
	                    $renewal->proposed_start_date,

	                'lease_end_date' =>
	                    $renewal->proposed_end_date,

	                'lease_period_months' =>
	                    $renewal->renewal_period_months,

	                'rent_start_date' =>
	                    $renewal->proposed_start_date,

	                'handover_date' =>
	                    $oldAgreement->handover_date,

	                'fitout_start_date' =>
	                    $oldAgreement->fitout_start_date,

	                'fitout_end_date' =>
	                    $oldAgreement->fitout_end_date,

	                'rent_free_days' =>
	                    $oldAgreement->rent_free_days ?? 0,

	                'security_deposit' =>
	                    $renewal->proposed_security_deposit ?? 0,

	                'monthly_rent' =>
	                    $renewal->proposed_rent ?? 0,

	                'cam_amount' =>
	                    $oldAgreement->cam_amount ?? 0,

	                'utility_deposit' =>
	                    $oldAgreement->utility_deposit ?? 0,

	                'billing_frequency' =>
	                    $oldAgreement->billing_frequency ?? 'Monthly',

	                'payment_due_day' =>
	                    $oldAgreement->payment_due_day ?? 5,

	                'agreement_status' =>
	                    'Active',

	                'remarks' =>
	                    'Renewed from Agreement '
	                    . $oldAgreement->agreement_no
	                    . ' | Renewal ID: '
	                    . $renewal->id,

	                'created_by' =>
	                    auth()->id(),

	                'updated_by' =>
	                    auth()->id(),

	            ]);


	            /*
	            |--------------------------------------------------------------------------
	            | Copy Lease Terms
	            |--------------------------------------------------------------------------
	            */

	            $oldTerms = $oldAgreement->terms;


	            if ($oldTerms) {

	                LeaseTerm::create([

	                    'lease_agreement_id' =>
	                        $agreement->id,

	                    'lock_in_period_months' =>
	                        $oldTerms->lock_in_period_months ?? 0,

	                    'notice_period_days' =>
	                        $oldTerms->notice_period_days ?? 90,

	                    'escalation_frequency' =>
	                        $oldTerms->escalation_frequency
	                        ?? 'Yearly',

	                    'escalation_percentage' =>
	                        $renewal->escalation_percentage
	                        ?? $oldTerms->escalation_percentage
	                        ?? 0,

	                    'billing_cycle' =>
	                        $oldTerms->billing_cycle
	                        ?? 'Monthly',

	                    'payment_due_days' =>
	                        $oldTerms->payment_due_days
	                        ?? 5,

	                    'grace_period_days' =>
	                        $oldTerms->grace_period_days
	                        ?? 0,

	                    'late_fee_type' =>
	                        $oldTerms->late_fee_type
	                        ?? 'Percentage',

	                    'late_fee_value' =>
	                        $oldTerms->late_fee_value
	                        ?? 0,

	                    'cam_calculation_method' =>
	                        $oldTerms->cam_calculation_method
	                        ?? 'Fixed',

	                    'utility_billing_method' =>
	                        $oldTerms->utility_billing_method
	                        ?? 'Meter Reading',

	                    'maintenance_responsibility' =>
	                        $oldTerms->maintenance_responsibility
	                        ?? 'Shared',

	                    'insurance_required' =>
	                        $oldTerms->insurance_required
	                        ?? 'Yes',

	                    'subletting_allowed' =>
	                        $oldTerms->subletting_allowed
	                        ?? 'No',

	                    'termination_clause' =>
	                        $oldTerms->termination_clause,

	                    'special_terms' =>
	                        $oldTerms->special_terms,

	                    'remarks' =>
	                        $oldTerms->remarks,

	                    'created_by' =>
	                        auth()->id(),

	                    'updated_by' =>
	                        auth()->id(),

	                ]);
	            }


	            /*
	            |--------------------------------------------------------------------------
	            | Mark Old Agreement as Renewed
	            |--------------------------------------------------------------------------
	            */

	            $oldAgreement->update([

	                'agreement_status' =>
	                    'Renewed',

	                'updated_by' =>
	                    auth()->id(),

	            ]);


	            return $agreement;
	        });


	        /*
	        |--------------------------------------------------------------------------
	        | Success
	        |--------------------------------------------------------------------------
	        */

	        return redirect()
	            ->route(
	                'admin.leasing.agreements.show',
	                $newAgreement->id
	            )
	            ->with(
	                'success',
	                'Lease renewal successfully converted into a new agreement.'
	            );


	    } catch (\Throwable $e) {

	        report($e);

	        return back()
	            ->with(
	                'error',
	                'Unable to create renewed agreement: '
	                . $e->getMessage()
	            );
	    }
	}


}