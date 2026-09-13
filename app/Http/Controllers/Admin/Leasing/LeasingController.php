<?php

namespace App\Http\Controllers\Admin\Leasing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LeasingController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('lease_proposals as lp')

            ->leftJoin(
                'lease_agreements as la',
                'la.proposal_id',
                '=',
                'lp.id'
            )

            ->leftJoin(
                'tenants as t',
                't.id',
                '=',
                'lp.tenant_id'
            )

            ->whereNull('lp.deleted_at')

            ->select([
                'lp.id as proposal_id',
                'lp.proposal_no',
                'lp.proposal_title',
                'lp.proposal_date',
                'lp.proposal_status',

                'lp.tenant_id',

                't.company_name as tenant_name',
                't.brand_name',

                'la.id as agreement_id',
                'la.agreement_no',
                'la.agreement_date',
                'la.lease_start_date',
                'la.lease_end_date',
                'la.monthly_rent',
                'la.cam_amount',
                'la.billing_frequency',
                'la.agreement_status',
            ]);


        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->where(
                    'lp.proposal_no',
                    'like',
                    "%{$search}%"
                )

                ->orWhere(
                    'lp.proposal_title',
                    'like',
                    "%{$search}%"
                )

                ->orWhere(
                    'la.agreement_no',
                    'like',
                    "%{$search}%"
                )

                ->orWhere(
                    't.company_name',
                    'like',
                    "%{$search}%"
                )

                ->orWhere(
                    't.brand_name',
                    'like',
                    "%{$search}%"
                );

            });

        }


        /*
        |--------------------------------------------------------------------------
        | STATUS
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {

            $status = $request->status;

            $query->where(function ($q) use ($status) {

                $q->where(
                    'la.agreement_status',
                    $status
                )

                ->orWhere(
                    'lp.proposal_status',
                    $status
                );

            });

        }


        /*
        |--------------------------------------------------------------------------
        | ORDER
        |--------------------------------------------------------------------------
        */

        $leasing = $query

            ->orderByDesc('lp.id')

            ->paginate(20)

            ->withQueryString();


        return view(
            'admin.leasing.index',
            compact('leasing')
        );
    }

    public function show($id)
	{
	    /*
	    |--------------------------------------------------------------------------
	    | Agreement
	    |--------------------------------------------------------------------------
	    */

	    $agreement = DB::table('lease_agreements as la')

        ->leftJoin(
            'lease_proposals as lp',
            'lp.id',
            '=',
            'la.proposal_id'
        )

        ->leftJoin(
            'tenants as t',
            't.id',
            '=',
            'la.tenant_id'
        )

        ->where('la.id', $id)

        ->whereNull('la.deleted_at')

        ->select([
            'la.*',

            'lp.proposal_no',
            'lp.proposal_title',
            'lp.proposal_status',
            'lp.proposal_date',
            'lp.expected_start_date',
            'lp.expected_end_date',

            't.tenant_code',
            't.company_name as tenant_name',
            't.brand_name',
            't.email as tenant_email',
            't.phone as tenant_phone',
        ])

        ->first();


    /*
    |--------------------------------------------------------------------------
    | Agreement Not Found
    |--------------------------------------------------------------------------
    */

    if (!$agreement) {

        abort(
            404,
            'Lease agreement not found for ID: ' . $id
        );

    }


    /*
    |--------------------------------------------------------------------------
    | Units
    |--------------------------------------------------------------------------
    */

    $units = DB::table('proposal_units as pu')

        ->join(
            'units as u',
            'u.id',
            '=',
            'pu.unit_id'
        )

        ->where(
            'pu.proposal_id',
            $agreement->proposal_id
        )

        ->whereNull('pu.deleted_at')

        ->select([
            'pu.id as proposal_unit_id',
            'pu.unit_id',

            'pu.proposed_rent',
            'pu.proposed_cam_rate',
            'pu.proposed_security_deposit',
            'pu.rent_free_days',
            'pu.fitout_period_days',

            'u.unit_no',
            'u.shop_name',
            'u.carpet_area',
            'u.builtup_area',
            'u.monthly_rent',
        ])

        ->get();


	    /*
	    |--------------------------------------------------------------------------
	    | Agreement Not Found
	    |--------------------------------------------------------------------------
	    */

	    abort_if(
	        !$agreement,
	        404,
	        'Lease agreement not found.'
	    );


	    /*
	    |--------------------------------------------------------------------------
	    | Lease Terms
	    |--------------------------------------------------------------------------
	    */

	    $terms = DB::table('lease_terms')

	        ->where(
	            'lease_agreement_id',
	            $agreement->id
	        )

	        ->whereNull('deleted_at')

	        ->first();


	    /*
	    |--------------------------------------------------------------------------
	    | Documents
	    |--------------------------------------------------------------------------
	    */

	    $documents = DB::table('lease_documents')

	        ->where(
	            'lease_agreement_id',
	            $agreement->id
	        )

	        ->whereNull('deleted_at')

	        ->orderByDesc('id')

	        ->get();


	    /*
	    |--------------------------------------------------------------------------
	    | Escalations
	    |--------------------------------------------------------------------------
	    */

	    $escalations = DB::table('lease_escalations')

	        ->where(
	            'lease_agreement_id',
	            $agreement->id
	        )

	        ->whereNull('deleted_at')

	        ->orderBy('effective_from')

	        ->get();


	    /*
	    |--------------------------------------------------------------------------
	    | Renewals
	    |--------------------------------------------------------------------------
	    */

	    $renewals = DB::table('lease_renewals')

	        ->where(
	            'lease_agreement_id',
	            $agreement->id
	        )

	        ->whereNull('deleted_at')

	        ->orderByDesc('id')

	        ->get();


	    /*
	    |--------------------------------------------------------------------------
	    | Extensions
	    |--------------------------------------------------------------------------
	    */

	    $extensions = DB::table('lease_extensions')

	        ->where(
	            'lease_agreement_id',
	            $agreement->id
	        )

	        ->whereNull('deleted_at')

	        ->orderByDesc('id')

	        ->get();


	    /*
	    |--------------------------------------------------------------------------
	    | Terminations
	    |--------------------------------------------------------------------------
	    */

	    $terminations = DB::table('lease_terminations')

	        ->where(
	            'lease_agreement_id',
	            $agreement->id
	        )

	        ->whereNull('deleted_at')

	        ->orderByDesc('id')

	        ->get();

	    /*
		|--------------------------------------------------------------------------
		| History
		|--------------------------------------------------------------------------
		*/

		$history = DB::table('lease_history')
		    ->where(
		        'lease_agreement_id',
		        $agreement->id
		    )
		    ->orderByDesc('activity_date')
		    ->orderByDesc('id')
		    ->get();


	    return view(
	        'admin.leasing.show',
	        compact(
	            'agreement',
	            'terms',
	            'documents',
	            'escalations',
	            'renewals',
	            'extensions',
	            'terminations',
	            'history',
	            'units'
	        )
	    );
	}
}