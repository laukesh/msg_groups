<?php

namespace App\Http\Controllers\Admin\Fitout;

use App\Http\Controllers\Controller;
use App\Models\Handover;
use App\Models\FitoutRequest;
use App\Models\FitoutContractor;
use App\Models\Inspection;
use App\Models\Unit;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class HandoverController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Index
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $query = Handover::with([
            'fitoutRequest',
            'unit',
            'tenant',
            'contractor',
            'finalInspection',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where(
                    'handover_number',
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


        /*
        |--------------------------------------------------------------------------
        | Handover Type
        |--------------------------------------------------------------------------
        */

        if ($request->filled('handover_type')) {

            $query->where(
                'handover_type',
                $request->handover_type
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Unit
        |--------------------------------------------------------------------------
        */

        if ($request->filled('unit_id')) {

            $query->where(
                'unit_id',
                $request->unit_id
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Contractor
        |--------------------------------------------------------------------------
        */

        if ($request->filled('contractor_id')) {

            $query->where(
                'contractor_id',
                $request->contractor_id
            );
        }


        $handovers = $query
            ->latest('id')
            ->paginate(20);


        /*
        |--------------------------------------------------------------------------
        | Statistics
        |--------------------------------------------------------------------------
        */

        $stats = [

            'total' => Handover::count(),

            'pending' => Handover::where(
                'status',
                'Pending'
            )->count(),

            'in_progress' => Handover::where(
                'status',
                'In Progress'
            )->count(),

            'completed' => Handover::where(
                'status',
                'Completed'
            )->count(),

        ];


        $contractors = FitoutContractor::orderBy('id')
            ->get();

        $units = Unit::orderBy('unit_no')
            ->get();


        return view(
            'admin.fitout.handovers.index',
            compact(
                'handovers',
                'contractors',
                'units',
                'stats'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Create
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        /*
        |--------------------------------------------------------------------------
        | Fit-Out Requests
        |--------------------------------------------------------------------------
        */

        $fitoutRequests = FitoutRequest::with([
            'unit',
            'tenant',
            'contractor',
            'stages',
        ])
        ->whereIn(
            'fitout_status',
            [
                'Approved',
                'In Progress',
                'Completed',
                'Under Handover',
            ]
        )
        ->orderByDesc('id')
        ->get();


        /*
        |--------------------------------------------------------------------------
        | Final Inspections
        |--------------------------------------------------------------------------
        */

        $inspections = Inspection::with([
            'fitoutRequest',
            'fitoutStage',
        ])
        ->where(
            'inspection_type',
            'Final Inspection'
        )
        ->where(
            'result',
            'Passed'
        )
        ->orderByDesc('id')
        ->get();


        /*
        |--------------------------------------------------------------------------
        | Contractors
        |--------------------------------------------------------------------------
        */

        $contractors = FitoutContractor::orderBy('id')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Units
        |--------------------------------------------------------------------------
        */

        $units = Unit::orderBy('unit_no')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Tenants
        |--------------------------------------------------------------------------
        */

        $tenants = Tenant::orderBy('id')
            ->get();


        return view(
            'admin.fitout.handovers.create',
            compact(
                'fitoutRequests',
                'inspections',
                'contractors',
                'units',
                'tenants'
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

            'fitout_request_id' => [
                'required',
                'exists:fitout_requests,id',
            ],

            'unit_id' => [
                'required',
                'exists:units,id',
            ],

            'tenant_id' => [
                'required',
                'exists:tenants,id',
            ],

            'contractor_id' => [
                'nullable',
                'exists:fitout_contractors,id',
            ],

            'final_inspection_id' => [
                'nullable',
                'exists:inspections,id',
            ],

            'handover_date' => [
                'nullable',
                'date',
            ],

            'handover_type' => [
                'required',
                'in:Fit-Out Handover,Final Handover,Partial Handover',
            ],

            'status' => [
                'nullable',
                'in:Pending,Scheduled,In Progress,Accepted,Rejected,Completed,Cancelled',
            ],

            'unit_condition' => [
                'nullable',
                'in:Good,Minor Issues,Major Issues,Not Ready',
            ],

            'key_count' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'access_card_count' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'electricity_meter_no' => [
                'nullable',
                'string',
                'max:100',
            ],

            'electricity_meter_reading' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'water_meter_no' => [
                'nullable',
                'string',
                'max:100',
            ],

            'water_meter_reading' => [
                'nullable',
                'numeric',
                'min:0',
            ],
            'handover_document' => [
                'nullable',
                'file',
                'mimes:pdf,jpg,jpeg,png',
                'max:10240',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Verify Inspection
        |--------------------------------------------------------------------------
        */

        if (!empty($validated['final_inspection_id'])) {

            $inspection = Inspection::findOrFail(
                $validated['final_inspection_id']
            );

            if (
                (int) $inspection->fitout_request_id !==
                (int) $validated['fitout_request_id']
            ) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'final_inspection_id' =>
                            'The selected final inspection does not belong to the selected fit-out request.',
                    ]);
            }

            if (
                $inspection->inspection_type !==
                'Final Inspection'
            ) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'final_inspection_id' =>
                            'The selected inspection is not a final inspection.',
                    ]);
            }

            if ($inspection->result !== 'Passed') {
                return back()
                    ->withInput()
                    ->withErrors([
                        'final_inspection_id' =>
                            'The final inspection must be passed before creating a handover.',
                    ]);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Generate Handover Number
        |--------------------------------------------------------------------------
        */

        $year = now()->year;


        $lastHandover = Handover::where(
            'handover_number',
            'like',
            "HO-{$year}-%"
        )
        ->orderByDesc('id')
        ->first();


        if ($lastHandover) {

            $lastNumber = (int) Str::after(
                $lastHandover->handover_number,
                "HO-{$year}-"
            );

            $nextNumber = $lastNumber + 1;

        } else {

            $nextNumber = 1;

        }


        $handoverNumber = sprintf(
            'HO-%d-%05d',
            $year,
            $nextNumber
        );


        /*
        |--------------------------------------------------------------------------
        | Create
        |--------------------------------------------------------------------------
        */

        $validated['uuid'] = (string) Str::uuid();

        $validated['handover_number'] =
            $handoverNumber;

        $validated['status'] =
            $validated['status'] ?? 'Pending';

        $validated['key_count'] =
            $validated['key_count'] ?? 0;

        $validated['access_card_count'] =
            $validated['access_card_count'] ?? 0;

        $validated['created_by'] =
            auth()->id();

        $validated['updated_by'] =
            auth()->id();

        if ($request->hasFile('handover_document')) {

            $path = $request->file('handover_document')
                ->store(
                    'fitout/handovers',
                    'public'
                );

            $validated['handover_document_path'] = $path;
        }

        $handover = Handover::create(
            $validated
        );


        return redirect()
            ->route(
                'admin.fitout.handovers.show',
                $handover->id
            )
            ->with(
                'success',
                'Handover created successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Show
    |--------------------------------------------------------------------------
    */

    public function show($id)
    {
        $handover = Handover::with([
		    'fitoutRequest',
		    'unit',
		    'tenant',
		    'contractor',
		    'finalInspection',
		    'tenantAcceptedBy',
		    'contractorAcceptedBy',
		    'mallApprovedBy',
		    'createdBy',
		    'updatedBy',
		])->findOrFail($id);


        return view(
            'admin.fitout.handovers.show',
            compact('handover')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Edit
    |--------------------------------------------------------------------------
    */

    public function edit($id)
    {
        $handover = Handover::findOrFail($id);


        $fitoutRequests = FitoutRequest::with([
            'unit',
            'tenant',
            'contractor',
        ])
        ->orderByDesc('id')
        ->get();


        $inspections = Inspection::with([
            'fitoutRequest',
        ])
        ->where(
            'inspection_type',
            'Final Inspection'
        )
        ->orderByDesc('id')
        ->get();


        $contractors = FitoutContractor::orderBy('id')
            ->get();

        $units = Unit::orderBy('unit_no')
            ->get();

        $tenants = Tenant::orderBy('id')
            ->get();


        return view(
            'admin.fitout.handovers.edit',
            compact(
                'handover',
                'fitoutRequests',
                'inspections',
                'contractors',
                'units',
                'tenants'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Update
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        $id
    ) {
        $handover = Handover::findOrFail($id);


        $validated = $request->validate([

            'fitout_request_id' => [
                'required',
                'exists:fitout_requests,id',
            ],

            'unit_id' => [
                'required',
                'exists:units,id',
            ],

            'tenant_id' => [
                'required',
                'exists:tenants,id',
            ],

            'contractor_id' => [
                'nullable',
                'exists:fitout_contractors,id',
            ],

            'final_inspection_id' => [
                'nullable',
                'exists:inspections,id',
            ],

            'handover_date' => [
                'nullable',
                'date',
            ],

            'handover_type' => [
                'required',
                'in:Fit-Out Handover,Final Handover,Partial Handover',
            ],

            'status' => [
                'required',
                'in:Pending,Scheduled,In Progress,Accepted,Rejected,Completed,Cancelled',
            ],

            'unit_condition' => [
                'nullable',
                'in:Good,Minor Issues,Major Issues,Not Ready',
            ],

            'key_count' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'access_card_count' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'electricity_meter_no' => [
                'nullable',
                'string',
                'max:100',
            ],

            'electricity_meter_reading' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'water_meter_no' => [
                'nullable',
                'string',
                'max:100',
            ],

            'water_meter_reading' => [
                'nullable',
                'numeric',
                'min:0',
            ],
            'handover_document' => [
                'nullable',
                'file',
                'mimes:pdf,jpg,jpeg,png',
                'max:10240',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],

        ]);

        if ($request->hasFile('handover_document')) {

            /*
            |--------------------------------------------------------------------------
            | Delete Previous Document
            |--------------------------------------------------------------------------
            */

            if ($handover->handover_document_path) {

                Storage::disk('public')->delete(
                    $handover->handover_document_path
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Store New Document
            |--------------------------------------------------------------------------
            */

            $path = $request->file('handover_document')
                ->store(
                    'fitout/handovers',
                    'public'
                );

            $validated['handover_document_path'] = $path;
        }


        $validated['updated_by'] =
            auth()->id();


        $handover->update(
            $validated
        );


        return redirect()
            ->route(
                'admin.fitout.handovers.show',
                $handover->id
            )
            ->with(
                'success',
                'Handover updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Delete
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {
        $handover = Handover::findOrFail($id);

        $handover->delete();

        return redirect()
            ->route(
                'admin.fitout.handovers.index'
            )
            ->with(
                'success',
                'Handover deleted successfully.'
            );
    }

    public function schedule(Request $request, $id)
	{
	    $handover = Handover::findOrFail($id);

	    if ($handover->status !== 'Pending') {
	        return back()->with(
	            'error',
	            'Only pending handovers can be scheduled.'
	        );
	    }

	    $validated = $request->validate([
	        'handover_date' => [
	            'required',
	            'date',
	        ],
	    ]);

	    $handover->update([
	        'handover_date' => $validated['handover_date'],
	        'status' => 'Scheduled',
	        'updated_by' => auth()->id(),
	    ]);

	    return back()->with(
	        'success',
	        'Handover scheduled successfully.'
	    );
	}

	public function start($id)
	{
	    $handover = Handover::findOrFail($id);

	    if ($handover->status !== 'Scheduled') {
	        return back()->with(
	            'error',
	            'Only scheduled handovers can be started.'
	        );
	    }

	    $handover->update([
	        'status' => 'In Progress',
	        'updated_by' => auth()->id(),
	    ]);

	    return back()->with(
	        'success',
	        'Handover has been started.'
	    );
	}

	public function tenantAccept($id)
	{
	    $handover = Handover::findOrFail($id);

	    if ($handover->status !== 'In Progress') {
	        return back()->with(
	            'error',
	            'Handover must be in progress before tenant acceptance.'
	        );
	    }

	    $handover->update([
	        'tenant_accepted_by' => auth()->id(),
	        'tenant_accepted_at' => now(),
	        'updated_by' => auth()->id(),
	    ]);

	    return back()->with(
	        'success',
	        'Tenant acceptance recorded successfully.'
	    );
	}

	public function contractorAccept($id)
	{
	    $handover = Handover::findOrFail($id);

	    if ($handover->status !== 'In Progress') {
	        return back()->with(
	            'error',
	            'Handover must be in progress before contractor acceptance.'
	        );
	    }

	    $handover->update([
	        'contractor_accepted_by' => auth()->id(),
	        'contractor_accepted_at' => now(),
	        'updated_by' => auth()->id(),
	    ]);

	    return back()->with(
	        'success',
	        'Contractor acceptance recorded successfully.'
	    );
	}

	public function approve($id)
	{
	    $handover = Handover::findOrFail($id);

	    if ($handover->status !== 'In Progress') {
	        return back()->with(
	            'error',
	            'Handover must be in progress before mall approval.'
	        );
	    }

	    if (!$handover->tenant_accepted_at) {
	        return back()->with(
	            'error',
	            'Tenant acceptance is required before mall approval.'
	        );
	    }

	    if (!$handover->contractor_accepted_at) {
	        return back()->with(
	            'error',
	            'Contractor acceptance is required before mall approval.'
	        );
	    }

	    $handover->update([
	        'mall_approved_by' => auth()->id(),
	        'mall_approved_at' => now(),
	        'status' => 'Accepted',
	        'updated_by' => auth()->id(),
	    ]);

	    return back()->with(
	        'success',
	        'Handover approved by mall management.'
	    );
	}

	public function complete($id)
	{
	    $handover = Handover::findOrFail($id);

	    if ($handover->status !== 'Accepted') {
	        return back()->with(
	            'error',
	            'Only accepted handovers can be completed.'
	        );
	    }

	    if (!$handover->mall_approved_at) {
	        return back()->with(
	            'error',
	            'Mall approval is required before completion.'
	        );
	    }

	    $handover->update([
	        'status' => 'Completed',
	        'updated_by' => auth()->id(),
	    ]);

	    return back()->with(
	        'success',
	        'Handover completed successfully.'
	    );
	}

    public function certificate($id)
    {
        $handover = Handover::with([
            'fitoutRequest',
            'unit',
            'tenant',
            'contractor',
            'finalInspection',
            'tenantAcceptedBy',
            'contractorAcceptedBy',
            'mallApprovedBy',
        ])->findOrFail($id);

       // echo "<pre>";print_r($handover);die();

        if ($handover->status !== 'Completed') {
            return back()->with(
                'error',
                'Handover certificate is available only after completion.'
            );
        }

        return view(
            'admin.fitout.handovers.certificate',
            compact('handover')
        );
    }


}