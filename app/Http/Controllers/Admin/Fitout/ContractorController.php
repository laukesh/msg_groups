<?php

namespace App\Http\Controllers\Admin\Fitout;

use App\Http\Controllers\Controller;
use App\Models\FitoutContractor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ContractorController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $query = FitoutContractor::with('user');

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where(
                    'contractor_code',
                    'like',
                    "%{$search}%"
                )

                ->orWhere(
                    'contractor_name',
                    'like',
                    "%{$search}%"
                )

                ->orWhere(
                    'contact_person',
                    'like',
                    "%{$search}%"
                )

                ->orWhere(
                    'mobile',
                    'like',
                    "%{$search}%"
                )

                ->orWhere(
                    'email',
                    'like',
                    "%{$search}%"
                );
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Status
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {

            $query->where(
                'status',
                $request->status
            );
        }

        $contractors = $query
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        return view(
            'admin.fitout.contractors.index',
            compact('contractors')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        return view(
            'admin.fitout.contractors.create'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $validated = $request->validate([

            /*
            |--------------------------------------------------------------------------
            | User / Login
            |--------------------------------------------------------------------------
            */

            'name' => [
                'required',
                'string',
                'max:150',
            ],

            'email' => [
                'required',
                'email',
                'max:150',
                'unique:users,email',
            ],

            'phone' => [
                'required',
                'string',
                'max:20',
            ],

            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],

            /*
            |--------------------------------------------------------------------------
            | Contractor
            |--------------------------------------------------------------------------
            */

            /*'contractor_code' => [
                'required',
                'string',
                'max:30',
                'unique:fitout_contractors,contractor_code',
            ],*/

            'contractor_name' => [
                'required',
                'string',
                'max:200',
            ],

            'contact_person' => [
                'nullable',
                'string',
                'max:150',
            ],

            'mobile' => [
                'nullable',
                'string',
                'max:20',
            ],

            'contractor_email' => [
                'nullable',
                'email',
                'max:150',
            ],

            'address' => [
                'nullable',
                'string',
            ],

            'trade_license_no' => [
                'nullable',
                'string',
                'max:100',
            ],

            'labour_license_no' => [
                'nullable',
                'string',
                'max:100',
            ],

            'gst_number' => [
                'nullable',
                'string',
                'max:20',
            ],

            'insurance_policy_no' => [
                'nullable',
                'string',
                'max:100',
            ],

            'insurance_expiry' => [
                'nullable',
                'date',
            ],

            'safety_induction_date' => [
                'nullable',
                'date',
            ],

            'worker_count' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],
        ]);


        DB::transaction(function () use ($validated) {

            /*
            |--------------------------------------------------------------------------
            | Create Login User
            |--------------------------------------------------------------------------
            */

            $lastContractor = FitoutContractor::lockForUpdate()
		        ->orderByDesc('id')
		        ->first();

		    $nextNumber = $lastContractor
		        ? ((int) preg_replace(
		            '/[^0-9]/',
		            '',
		            $lastContractor->contractor_code
		        ) + 1)
		        : 1;

		    $contractorCode = 'CON-' .
		        str_pad(
		            $nextNumber,
		            5,
		            '0',
		            STR_PAD_LEFT
		        );


            $user = User::create([

                'name' =>
                    $validated['name'],

                'email' =>
                    $validated['email'],

                'phone' =>
                    $validated['phone'],

                'password' =>
                    Hash::make(
                        $validated['password']
                    ),

                'role' =>
                    'Contractor',
            ]);


            /*
            |--------------------------------------------------------------------------
            | Create Contractor
            |--------------------------------------------------------------------------
            */

            FitoutContractor::create([

                'user_id' =>
                    $user->id,

                'uuid' =>
                    (string) Str::uuid(),

                'contractor_code' => $contractorCode,

                'contractor_name' =>
                    $validated['contractor_name'],

                'contact_person' =>
                    $validated['contact_person'] ?? null,

                'mobile' =>
                    $validated['mobile'] ?? null,

                'email' =>
                    $validated['contractor_email'] ?? null,

                'address' =>
                    $validated['address'] ?? null,

                'trade_license_no' =>
                    $validated['trade_license_no'] ?? null,

                'labour_license_no' =>
                    $validated['labour_license_no'] ?? null,

                'gst_number' =>
                    $validated['gst_number'] ?? null,

                'insurance_policy_no' =>
                    $validated['insurance_policy_no'] ?? null,

                'insurance_expiry' =>
                    $validated['insurance_expiry'] ?? null,

                'safety_induction_date' =>
                    $validated['safety_induction_date'] ?? null,

                'worker_count' =>
                    $validated['worker_count'] ?? 0,

                'status' =>
                    'Pending',

                'remarks' =>
                    $validated['remarks'] ?? null,

                'created_by' =>
                    Auth::id(),

                'updated_by' =>
                    Auth::id(),
            ]);
        });


        return redirect()
            ->route(
                'admin.fitout.contractors.index'
            )
            ->with(
                'success',
                'Contractor created successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show($id)
	{
	    $contractor = FitoutContractor::with([
	        'user',
	        'fitoutRequests',
	    ])->findOrFail($id);

	    return view(
	        'admin.fitout.contractors.show',
	        compact('contractor')
	    );
	}


    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit($id)
    {
        $contractor = FitoutContractor::with('user')
            ->findOrFail($id);

        return view(
            'admin.fitout.contractors.edit',
            compact('contractor')
        );
    }

    public function update(Request $request, $id)
	{
	    $contractor = FitoutContractor::with('user')
	        ->findOrFail($id);

	    $validated = $request->validate([

	        /*
	        |--------------------------------------------------------------------------
	        | Login Account
	        |--------------------------------------------------------------------------
	        */

	        'name' => [
	            'required',
	            'string',
	            'max:150',
	        ],

	        'email' => [
	            'required',
	            'email',
	            'max:150',
	            'unique:users,email,' . optional($contractor->user)->id,
	        ],

	        'phone' => [
	            'required',
	            'string',
	            'max:20',
	        ],

	        /*
	        |--------------------------------------------------------------------------
	        | Contractor
	        |--------------------------------------------------------------------------
	        */

	        'contractor_name' => [
	            'required',
	            'string',
	            'max:200',
	        ],

	        'contact_person' => [
	            'nullable',
	            'string',
	            'max:150',
	        ],

	        'mobile' => [
	            'nullable',
	            'string',
	            'max:20',
	        ],

	        'contractor_email' => [
	            'nullable',
	            'email',
	            'max:150',
	        ],

	        'address' => [
	            'nullable',
	            'string',
	        ],

	        'trade_license_no' => [
	            'nullable',
	            'string',
	            'max:100',
	        ],

	        'labour_license_no' => [
	            'nullable',
	            'string',
	            'max:100',
	        ],

	        'gst_number' => [
	            'nullable',
	            'string',
	            'max:20',
	        ],

	        'insurance_policy_no' => [
	            'nullable',
	            'string',
	            'max:100',
	        ],

	        'insurance_expiry' => [
	            'nullable',
	            'date',
	        ],

	        'safety_induction_date' => [
	            'nullable',
	            'date',
	        ],

	        'worker_count' => [
	            'nullable',
	            'integer',
	            'min:0',
	        ],

	        'remarks' => [
	            'nullable',
	            'string',
	        ],
	    ]);


	    DB::transaction(function () use (
	        $contractor,
	        $validated
	    ) {

	        /*
	        |--------------------------------------------------------------------------
	        | Update Login User
	        |--------------------------------------------------------------------------
	        */

	        if ($contractor->user) {

	            $contractor->user->update([

	                'name' =>
	                    $validated['name'],

	                'email' =>
	                    $validated['email'],

	                'phone' =>
	                    $validated['phone'],

	            ]);
	        }


	        /*
	        |--------------------------------------------------------------------------
	        | Update Contractor
	        |--------------------------------------------------------------------------
	        */

	        $contractor->update([

	            'contractor_name' =>
	                $validated['contractor_name'],

	            'contact_person' =>
	                $validated['contact_person'] ?? null,

	            'mobile' =>
	                $validated['mobile'] ?? null,

	            'email' =>
	                $validated['contractor_email'] ?? null,

	            'address' =>
	                $validated['address'] ?? null,

	            'trade_license_no' =>
	                $validated['trade_license_no'] ?? null,

	            'labour_license_no' =>
	                $validated['labour_license_no'] ?? null,

	            'gst_number' =>
	                $validated['gst_number'] ?? null,

	            'insurance_policy_no' =>
	                $validated['insurance_policy_no'] ?? null,

	            'insurance_expiry' =>
	                $validated['insurance_expiry'] ?? null,

	            'safety_induction_date' =>
	                $validated['safety_induction_date'] ?? null,

	            'worker_count' =>
	                $validated['worker_count'] ?? 0,

	            'remarks' =>
	                $validated['remarks'] ?? null,

	            'updated_by' =>
	                Auth::id(),

	        ]);
	    });


	    return redirect()
	        ->route(
	            'admin.fitout.contractors.show',
	            $contractor->id
	        )
	        ->with(
	            'success',
	            'Contractor updated successfully.'
	        );
	}

	/*
	|--------------------------------------------------------------------------
	| APPROVE CONTRACTOR
	|--------------------------------------------------------------------------
	*/

	public function approve($id)
	{
	    DB::transaction(function () use ($id) {

	        $contractor = FitoutContractor::lockForUpdate()
	            ->findOrFail($id);

	        /*
	        |--------------------------------------------------------------------------
	        | Only Pending Contractors Can Be Approved
	        |--------------------------------------------------------------------------
	        */

	        if ($contractor->status !== 'Pending') {

	            throw ValidationException::withMessages([
	                'contractor' =>
	                    'Only pending contractors can be approved.'
	            ]);
	        }


	        /*
	        |--------------------------------------------------------------------------
	        | Approve
	        |--------------------------------------------------------------------------
	        */

	        $contractor->update([

	            'status' => 'Approved',

	            'updated_by' => Auth::id(),

	        ]);
	    });


	    return redirect()
	        ->back()
	        ->with(
	            'success',
	            'Contractor approved successfully.'
	        );
	}


	/*
	|--------------------------------------------------------------------------
	| REJECT CONTRACTOR
	|--------------------------------------------------------------------------
	*/

	public function reject($id)
	{
	    DB::transaction(function () use ($id) {

	        $contractor = FitoutContractor::lockForUpdate()
	            ->findOrFail($id);

	        /*
	        |--------------------------------------------------------------------------
	        | Only Pending Contractors Can Be Rejected
	        |--------------------------------------------------------------------------
	        */

	        if ($contractor->status !== 'Pending') {

	            throw ValidationException::withMessages([
	                'contractor' =>
	                    'Only pending contractors can be rejected.'
	            ]);
	        }


	        /*
	        |--------------------------------------------------------------------------
	        | Reject
	        |--------------------------------------------------------------------------
	        */

	        $contractor->update([

	            'status' => 'Rejected',

	            'updated_by' => Auth::id(),

	        ]);
	    });


	    return redirect()
	        ->back()
	        ->with(
	            'success',
	            'Contractor rejected successfully.'
	        );
	}


	/*
	|--------------------------------------------------------------------------
	| SUSPEND CONTRACTOR
	|--------------------------------------------------------------------------
	*/

	public function suspend($id)
	{
	    DB::transaction(function () use ($id) {

	        $contractor = FitoutContractor::lockForUpdate()
	            ->findOrFail($id);

	        /*
	        |--------------------------------------------------------------------------
	        | Only Approved Contractors Can Be Suspended
	        |--------------------------------------------------------------------------
	        */

	        if ($contractor->status !== 'Approved') {

	            throw ValidationException::withMessages([
	                'contractor' =>
	                    'Only approved contractors can be suspended.'
	            ]);
	        }


	        /*
	        |--------------------------------------------------------------------------
	        | Suspend
	        |--------------------------------------------------------------------------
	        */

	        $contractor->update([

	            'status' => 'Suspended',

	            'updated_by' => Auth::id(),

	        ]);
	    });


	    return redirect()
	        ->back()
	        ->with(
	            'success',
	            'Contractor suspended successfully.'
	        );
	}
}