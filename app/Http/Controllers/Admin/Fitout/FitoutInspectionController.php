<?php

namespace App\Http\Controllers\Admin\Fitout;

use App\Http\Controllers\Controller;
use App\Models\Inspection;
use App\Models\FitoutRequest;
use App\Models\FitoutStage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FitoutInspectionController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $inspections = Inspection::with([
            'fitoutRequest.tenant',
            'fitoutRequest.unit',
            'fitoutRequest.contractor',
            'fitoutStage',
            'inspector',
        ])
        ->latest('id')
        ->paginate(20);

        return view(
            'admin.fitout.inspections.index',
            compact('inspections')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

	public function create()
	{
	    $fitoutRequests = FitoutRequest::with([
	        'tenant',
	        'unit',
	        'contractor',
	        'stages',
	    ])
	    ->whereNotIn('fitout_status', [
	        'Draft',
	        'Rejected',
	    ])
	    ->latest('id')
	    ->get();

	    $users = User::where('is_active', 1)
	        ->where('status', 'Active')
	        ->orderBy('name')
	        ->get();

	    $fitoutRequestData = $fitoutRequests->map(function ($request) {

	        return [
	            'id' => $request->id,

	            'request_no' => $request->request_no,

	            'tenant' => $request->tenant
	                ? (
	                    $request->tenant->company_name
	                    ?? $request->tenant->company_name
	                    ?? '-'
	                )
	                : '-',

	            'unit' => $request->unit
	                ? (
	                    $request->unit->unit_no
	                    ?? $request->unit->name
	                    ?? '-'
	                )
	                : '-',

	            'contractor' => $request->contractor
	                ? (
	                    $request->contractor->contractor_name
	                    ?? '-'
	                )
	                : '-',

	            'stages' => $request->stages->map(function ($stage) {

	                return [
	                    'id' => $stage->id,
	                    'stage_name' => $stage->stage_name,
	                    'stage_sequence' => $stage->stage_sequence,
	                ];

	            })->values()->all(),
	        ];

	    })->values()->all();

	    return view(
	        'admin.fitout.inspections.create',
	        compact(
	            'fitoutRequests',
	            'fitoutRequestData',
	            'users'
	        )
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

            'fitout_request_id' => [
                'required',
                'exists:fitout_requests,id',
            ],

            'fitout_stage_id' => [
                'nullable',
                'exists:fitout_stages,id',
            ],

            'inspection_type' => [
                'required',
                'in:Initial Site Inspection,Civil Inspection,Electrical Inspection,Plumbing Inspection,HVAC Inspection,Fire & Safety Inspection,Shop Front Inspection,Signage Inspection,Final Inspection,Re-Inspection',
            ],

            'scheduled_date' => [
                'required',
                'date',
            ],

            'scheduled_time' => [
                'nullable',
                'date_format:H:i',
            ],

            'inspector_id' => [
                'nullable',
                'exists:users,id',
            ],

            'observations' => [
                'nullable',
                'string',
            ],

            'recommendations' => [
                'nullable',
                'string',
            ],
        ]);


        DB::transaction(function () use ($validated, &$inspection) {

            $inspection = Inspection::create([

                'uuid' => (string) Str::uuid(),

                'fitout_request_id' =>
                    $validated['fitout_request_id'],

                'fitout_stage_id' =>
                    $validated['fitout_stage_id'] ?? null,

                'inspection_type' =>
                    $validated['inspection_type'],

                'inspection_number' =>
                    $this->generateInspectionNumber(),

                'scheduled_date' =>
                    $validated['scheduled_date'],

                'scheduled_time' =>
                    $validated['scheduled_time'] ?? null,

                'inspector_id' =>
                    $validated['inspector_id'] ?? null,

                'result' =>
                    'Pending',

                'status' =>
                    'Scheduled',

                'observations' =>
                    $validated['observations'] ?? null,

                'recommendations' =>
                    $validated['recommendations'] ?? null,

                'reinspection_required' =>
                    0,

                'created_by' =>
                    auth()->id(),

                'updated_by' =>
                    auth()->id(),
            ]);
        });


        return redirect()
            ->route(
                'admin.fitout.inspections.show',
                $inspection->id
            )
            ->with(
                'success',
                'Inspection scheduled successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show($id)
    {
        $inspection = Inspection::with([
            'fitoutRequest.tenant',
            'fitoutRequest.unit',
            'fitoutRequest.contractor',
            'fitoutRequest.stages',
            'fitoutStage',
            'inspector',
            'parentInspection',
            'reinspections',
        ])
        ->findOrFail($id);


        return view(
            'admin.fitout.inspections.show',
            compact('inspection')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit($id)
	{
	    $inspection = Inspection::with([
	        'fitoutRequest.tenant',
	        'fitoutRequest.unit',
	        'fitoutRequest.contractor',
	        'fitoutStage',
	    ])->findOrFail($id);

	    $stages = FitoutStage::where(
	        'fitout_request_id',
	        $inspection->fitout_request_id
	    )
	    ->orderBy('stage_sequence')
	    ->get();

	    $inspectors = User::where('is_active', 1)
	        ->where('status', 'Active')
	        ->orderBy('name')
	        ->get();

	    return view(
	        'admin.fitout.inspections.edit',
	        compact(
	            'inspection',
	            'stages',
	            'inspectors'
	        )
	    );
	}


    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(Request $request, $id)
	{
	    $inspection = Inspection::findOrFail($id);

	    $validated = $request->validate([
	        'inspection_type' => [
	            'required',
	            'in:Initial Site Inspection,Civil Inspection,Electrical Inspection,Plumbing Inspection,HVAC Inspection,Fire & Safety Inspection,Shop Front Inspection,Signage Inspection,Final Inspection,Re-Inspection',
	        ],

	        'fitout_stage_id' => [
	            'nullable',
	            'exists:fitout_stages,id',
	        ],

	        'scheduled_date' => [
	            'required',
	            'date',
	        ],

	        'scheduled_time' => [
	            'nullable',
	            'date_format:H:i',
	        ],

	        'inspection_date' => [
	            'nullable',
	            'date',
	        ],

	        'inspector_id' => [
	            'nullable',
	            'exists:users,id',
	        ],

	        'result' => [
	            'nullable',
	            'in:Pending,Passed,Failed,Conditional Pass',
	        ],

	        'status' => [
	            'required',
	            'in:Scheduled,In Progress,Completed,Cancelled,Rescheduled',
	        ],

	        'observations' => [
	            'nullable',
	            'string',
	        ],

	        'recommendations' => [
	            'nullable',
	            'string',
	        ],

	        'reinspection_required' => [
	            'nullable',
	            'boolean',
	        ],
	    ]);

	    $validated['reinspection_required'] =
	        $request->boolean('reinspection_required');

	    $validated['updated_by'] =
	        auth()->id();

	    $inspection->update($validated);

	    return redirect()
	        ->route(
	            'admin.fitout.inspections.show',
	            $inspection->id
	        )
	        ->with(
	            'success',
	            'Inspection updated successfully.'
	        );
	}


    /*
    |--------------------------------------------------------------------------
    | START INSPECTION
    |--------------------------------------------------------------------------
    */

    public function start($id)
    {
        $inspection = Inspection::findOrFail($id);

        if ($inspection->status !== 'Scheduled') {
            return back()->with(
                'error',
                'Only scheduled inspections can be started.'
            );
        }

        $inspection->update([

            'status' => 'In Progress',

            'inspection_date' => now()->toDateString(),

            'updated_by' => auth()->id(),
        ]);


        return back()->with(
            'success',
            'Inspection started successfully.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | COMPLETE INSPECTION
    |--------------------------------------------------------------------------
    */

    public function complete(Request $request, $id)
	{
	    $inspection = Inspection::findOrFail($id);

	    if ($inspection->status !== 'In Progress') {
	        return redirect()
	            ->back()
	            ->with(
	                'error',
	                'Only an inspection in progress can be completed.'
	            );
	    }

	    $validated = $request->validate([

	        'result' => [
	            'required',
	            'in:Passed,Failed,Conditional Pass',
	        ],

	        'observations' => [
	            'nullable',
	            'string',
	        ],

	        'recommendations' => [
	            'nullable',
	            'string',
	        ],

	        'reinspection_required' => [
	            'nullable',
	            'boolean',
	        ],

	        'report_file' => [
	            'nullable',
	            'file',
	            'mimes:pdf,doc,docx,jpg,jpeg,png',
	            'max:10240',
	        ],

	    ]);


	    $data = [

	        'result' =>
	            $validated['result'],

	        'observations' =>
	            $validated['observations'] ?? null,

	        'recommendations' =>
	            $validated['recommendations'] ?? null,

	        'reinspection_required' =>
	            $request->boolean(
	                'reinspection_required'
	            ),

	        'status' =>
	            'Completed',

	        'inspection_date' =>
	            $inspection->inspection_date
	                ?? now()->toDateString(),

	        'completed_at' =>
	            now(),

	        'updated_by' =>
	            auth()->id(),

	    ];


	    /*
	    |--------------------------------------------------------------------------
	    | Upload Inspection Report
	    |--------------------------------------------------------------------------
	    */

	    if ($request->hasFile('report_file')) {

	        $file = $request->file('report_file');

	        $filename =
	            'inspection_' .
	            $inspection->id .
	            '_' .
	            time() .
	            '.' .
	            $file->getClientOriginalExtension();

	        $path = $file->storeAs(
	            'fitout/inspection-reports',
	            $filename,
	            'public'
	        );

	        $data['report_file_path'] = $path;
	    }


	    $inspection->update($data);


	    return redirect()
	        ->route(
	            'admin.fitout.inspections.show',
	            $inspection->id
	        )
	        ->with(
	            'success',
	            'Inspection completed successfully.'
	        );
	}


    /*
    |--------------------------------------------------------------------------
    | CANCEL
    |--------------------------------------------------------------------------
    */

    public function cancel($id)
    {
        $inspection = Inspection::findOrFail($id);

        if (
            in_array(
                $inspection->status,
                ['Completed', 'Cancelled']
            )
        ) {
            return back()->with(
                'error',
                'This inspection cannot be cancelled.'
            );
        }


        $inspection->update([

            'status' => 'Cancelled',

            'updated_by' => auth()->id(),
        ]);


        return back()->with(
            'success',
            'Inspection cancelled successfully.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | RESCHEDULE
    |--------------------------------------------------------------------------
    */

    public function reschedule(Request $request, $id)
    {
        $inspection = Inspection::findOrFail($id);

        $validated = $request->validate([

            'scheduled_date' => [
                'required',
                'date',
            ],

            'scheduled_time' => [
                'nullable',
                'date_format:H:i',
            ],
        ]);


        $inspection->update([

            'scheduled_date' =>
                $validated['scheduled_date'],

            'scheduled_time' =>
                $validated['scheduled_time'] ?? null,

            'status' =>
                'Rescheduled',

            'updated_by' =>
                auth()->id(),
        ]);


        return back()->with(
            'success',
            'Inspection rescheduled successfully.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE RE-INSPECTION
    |--------------------------------------------------------------------------
    */

    public function createReinspection($id)
	{
	    $inspection = Inspection::with([
	        'fitoutRequest.tenant',
	        'fitoutRequest.unit',
	        'fitoutRequest.contractor',
	        'fitoutStage',
	    ])->findOrFail($id);

	    if (
	        $inspection->status !== 'Completed' ||
	        !$inspection->reinspection_required
	    ) {
	        return redirect()
	            ->route(
	                'admin.fitout.inspections.show',
	                $inspection->id
	            )
	            ->with(
	                'error',
	                'Re-inspection is not required for this inspection.'
	            );
	    }

	    $inspectors = User::where('is_active', 1)
	        ->where('status', 'Active')
	        ->orderBy('name')
	        ->get();

	    return view(
	        'admin.fitout.inspections.reinspection-create',
	        compact(
	            'inspection',
	            'inspectors'
	        )
	    );
	}


    /*
    |--------------------------------------------------------------------------
    | STORE RE-INSPECTION
    |--------------------------------------------------------------------------
    */

    public function storeReinspection(Request $request, $id)
	{
	    $parentInspection = Inspection::findOrFail($id);

	    if (
	        $parentInspection->status !== 'Completed' ||
	        !$parentInspection->reinspection_required
	    ) {
	        return redirect()
	            ->route(
	                'admin.fitout.inspections.show',
	                $parentInspection->id
	            )
	            ->with(
	                'error',
	                'Re-inspection cannot be created for this inspection.'
	            );
	    }

	    $validated = $request->validate([

	        'scheduled_date' => [
	            'required',
	            'date',
	        ],

	        'scheduled_time' => [
	            'nullable',
	            'date_format:H:i',
	        ],

	        'inspector_id' => [
	            'nullable',
	            'exists:users,id',
	        ],

	        'observations' => [
	            'nullable',
	            'string',
	        ],

	        'recommendations' => [
	            'nullable',
	            'string',
	        ],

	    ]);

	    $nextNumber = Inspection::where(
	        'fitout_request_id',
	        $parentInspection->fitout_request_id
	    )->count() + 1;

	    $inspectionNumber =
	        'INS-' .
	        date('Y') .
	        '-' .
	        str_pad(
	            $nextNumber,
	            5,
	            '0',
	            STR_PAD_LEFT
	        );

	    $reinspection = Inspection::create([

	        'uuid' => (string) \Illuminate\Support\Str::uuid(),

	        'fitout_request_id' =>
	            $parentInspection->fitout_request_id,

	        'fitout_stage_id' =>
	            $parentInspection->fitout_stage_id,

	        'inspection_type' =>
	            'Re-Inspection',

	        'inspection_number' =>
	            $inspectionNumber,

	        'scheduled_date' =>
	            $validated['scheduled_date'],

	        'scheduled_time' =>
	            $validated['scheduled_time'] ?? null,

	        'inspector_id' =>
	            $validated['inspector_id'] ?? null,

	        'result' =>
	            'Pending',

	        'status' =>
	            'Scheduled',

	        'observations' =>
	            $validated['observations'] ?? null,

	        'recommendations' =>
	            $validated['recommendations'] ?? null,

	        'reinspection_required' =>
	            false,

	        'parent_inspection_id' =>
	            $parentInspection->id,

	        'created_by' =>
	            auth()->id(),

	        'updated_by' =>
	            auth()->id(),

	    ]);

	    return redirect()
	        ->route(
	            'admin.fitout.inspections.show',
	            $reinspection->id
	        )
	        ->with(
	            'success',
	            'Re-inspection scheduled successfully.'
	        );
	}


    /*
    |--------------------------------------------------------------------------
    | GENERATE INSPECTION NUMBER
    |--------------------------------------------------------------------------
    */

    private function generateInspectionNumber()
    {
        $year = now()->year;

        $lastInspection = Inspection::withTrashed()
            ->where(
                'inspection_number',
                'like',
                "INS-{$year}-%"
            )
            ->orderByDesc('id')
            ->first();


        if (!$lastInspection) {
            $sequence = 1;
        } else {

            $lastNumber = (int) substr(
                $lastInspection->inspection_number,
                -5
            );

            $sequence = $lastNumber + 1;
        }


        return sprintf(
            'INS-%d-%05d',
            $year,
            $sequence
        );
    }
}