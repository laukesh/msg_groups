<?php

namespace App\Http\Controllers\Admin\Fitout;

use App\Http\Controllers\Controller;
use App\Models\FitoutStage;
use App\Models\FitoutRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FitoutStageController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index($fitoutRequestId)
    {
        $fitoutRequest = FitoutRequest::findOrFail(
            $fitoutRequestId
        );


        $stages = FitoutStage::with([
                'contractor',
            ])
            ->where(
                'fitout_request_id',
                $fitoutRequest->id
            )
            ->orderBy(
                'stage_sequence'
            )
            ->get();


        return view(
            'admin.fitout.stages.index',
            compact(
                'fitoutRequest',
                'stages'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show($id)
	{
	    $stage = FitoutStage::with([
	        'fitoutRequest.tenant',
	        'fitoutRequest.unit',
	        'contractor',
	    ])->findOrFail($id);

	    return view(
	        'admin.fitout.stages.show',
	        compact('stage')
	    );
	}
	public function edit($id)
    {
        $stage = FitoutStage::with([
            'fitoutRequest',
            'contractor',
        ])->findOrFail($id);

        return view(
            'admin.fitout.stages.edit',
            compact('stage')
        );
    }

    /*public function update(Request $request, $id)
    {
        $stage = FitoutStage::findOrFail($id);

        $validated = $request->validate([
            'planned_start_date' => 'nullable|date',
            'planned_end_date' => 'nullable|date|after_or_equal:planned_start_date',

            'actual_start_date' => 'nullable|date',
            'actual_end_date' => 'nullable|date|after_or_equal:actual_start_date',

            'completion_percentage' => [
                'required',
                'numeric',
                'min:0',
                'max:100',
            ],

            'engineer_id' => 'nullable|integer',

            'stage_status' => [
                'required',
                'in:Pending,In Progress,Completed,On Hold,Cancelled',
            ],

            'remarks' => 'nullable|string',
        ]);

        $stage->update([
            'planned_start_date' => $validated['planned_start_date'] ?? null,
            'planned_end_date' => $validated['planned_end_date'] ?? null,
            'actual_start_date' => $validated['actual_start_date'] ?? null,
            'actual_end_date' => $validated['actual_end_date'] ?? null,
            'completion_percentage' => $validated['completion_percentage'],
            'engineer_id' => $validated['engineer_id'] ?? null,
            'stage_status' => $validated['stage_status'],
            'remarks' => $validated['remarks'] ?? null,
            'updated_by' => auth()->id(),
        ]);

        return redirect()
            ->route('admin.fitout.stages.show', $stage->id)
            ->with('success', 'Fit-out stage updated successfully.');
    }*/

    public function update(Request $request, $id)
	{
	    $stage = FitoutStage::findOrFail($id);

	    $validated = $request->validate([
	        'planned_start_date' => 'nullable|date',
	        'planned_end_date' => 'nullable|date|after_or_equal:planned_start_date',
	        'actual_start_date' => 'nullable|date',
	        'actual_end_date' => 'nullable|date|after_or_equal:actual_start_date',
	        'completion_percentage' => [
	            'required',
	            'numeric',
	            'min:0',
	            'max:100',
	        ],
	        'engineer_id' => 'nullable|exists:users,id',
	        'stage_status' => [
	            'required',
	            'in:Pending,In Progress,Completed,On Hold,Cancelled',
	        ],
	        'remarks' => 'nullable|string',
	    ]);

	    $completion = (float) $validated['completion_percentage'];
	    $status = $validated['stage_status'];

	    /*
	    |--------------------------------------------------------------------------
	    | Completed validation
	    |--------------------------------------------------------------------------
	    */

	    if ($status === 'Completed' && $completion < 100) {
	        return back()
	            ->withInput()
	            ->withErrors([
	                'completion_percentage' => 'Completion must be 100% before marking the stage as Completed.',
	            ]);
	    }

	    if ($status === 'Completed' && empty($validated['actual_end_date'])) {
	        return back()
	            ->withInput()
	            ->withErrors([
	                'actual_end_date' => 'Actual End Date is required when the stage is Completed.',
	            ]);
	    }

	    /*
	    |--------------------------------------------------------------------------
	    | 100% automatically means Completed
	    |--------------------------------------------------------------------------
	    */

	    if ($completion >= 100) {
	        $completion = 100;
	        $status = 'Completed';

	        if (empty($validated['actual_end_date'])) {
	            $validated['actual_end_date'] = now()->format('Y-m-d');
	        }
	    }

	    /*
	    |--------------------------------------------------------------------------
	    | In Progress automatically gets Actual Start Date
	    |--------------------------------------------------------------------------
	    */

	    if (  $status === 'In Progress' && empty($validated['actual_start_date'])
	    ) {
	        $validated['actual_start_date'] = now()->format('Y-m-d');
	    }

	    /*
	    |--------------------------------------------------------------------------
	    | Update
	    |--------------------------------------------------------------------------
	    */

	    if ($status === 'In Progress') {

		    $previousStage = FitoutStage::where('fitout_request_id', $stage->fitout_request_id)
		        ->where('stage_sequence', '<', $stage->stage_sequence)
		        ->orderByDesc('stage_sequence')
		        ->first();

		    if ($previousStage && $previousStage->stage_status !== 'Completed') {

		        return back()
		            ->withInput()
		            ->withErrors([
		                'stage_status' => 'The previous stage must be completed before starting this stage.'
		            ]);
		    }
		}

	    $stage->update([
	        'planned_start_date' => $validated['planned_start_date'] ?? null,
	        'planned_end_date' => $validated['planned_end_date'] ?? null,
	        'actual_start_date' => $validated['actual_start_date'] ?? null,
	        'actual_end_date' => $validated['actual_end_date'] ?? null,
	        'completion_percentage' => $completion,
	        'engineer_id' => $validated['engineer_id'] ?? null,
	        'stage_status' => $status,
	        'remarks' => $validated['remarks'] ?? null,
	        'updated_by' => auth()->id(),
	    ]);

	    if ($status === 'Completed') {

		    $nextStage = FitoutStage::where(
		        'fitout_request_id',
		        $stage->fitout_request_id
		    )
		    ->where(
		        'stage_sequence',
		        $stage->stage_sequence + 1
		    )
		    ->first();

		    if ($nextStage && $nextStage->stage_status === 'Pending') {

		        $nextStage->update([
		            'stage_status' => 'In Progress',
		            'actual_start_date' => now()->format('Y-m-d'),
		            'updated_by' => auth()->id(),
		        ]);
		    }
		}

	    return redirect()
	    	->route('admin.fitout.stages.show', $stage->id)
	        ->with('success', 'Fit-out stage updated successfully.');
	}

    /*
    |--------------------------------------------------------------------------
    | START STAGE
    |--------------------------------------------------------------------------
    */

    public function start($id)
    {
        DB::transaction(function () use ($id) {

            $stage = FitoutStage::lockForUpdate()
                ->findOrFail($id);


            if ($stage->stage_status !== 'Pending') {

                throw \Illuminate\Validation\ValidationException::withMessages([
                    'stage' =>
                        'Only pending stages can be started.'
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Previous Stage Must Be Completed
            |--------------------------------------------------------------------------
            */

            if ($stage->stage_sequence > 1) {

                $previousStage =
                    FitoutStage::where(
                        'fitout_request_id',
                        $stage->fitout_request_id
                    )
                    ->where(
                        'stage_sequence',
                        $stage->stage_sequence - 1
                    )
                    ->first();


                if (
                    $previousStage &&
                    $previousStage->stage_status !== 'Completed'
                ) {

                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'stage' =>
                            'The previous stage must be completed before starting this stage.'
                    ]);
                }
            }


            $stage->update([

                'stage_status' =>
                    'In Progress',

                'actual_start_date' =>
                    now()->toDateString(),

                'completion_percentage' =>
                    max(
                        1,
                        (float) $stage->completion_percentage
                    ),

                'updated_by' =>
                    Auth::id(),

            ]);

        });


        return back()->with(
            'success',
            'Stage started successfully.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE PROGRESS
    |--------------------------------------------------------------------------
    */

    public function updateProgress(
        Request $request,
        $id
    ) {

        $validated = $request->validate([

            'completion_percentage' => [
                'required',
                'numeric',
                'min:0',
                'max:100',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],

        ]);


        $stage = FitoutStage::findOrFail($id);


        if ($stage->stage_status !== 'In Progress') {

            return back()->with(
                'error',
                'Only stages in progress can be updated.'
            );
        }


        $stage->update([

            'completion_percentage' =>
                $validated['completion_percentage'],

            'remarks' =>
                $validated['remarks'] ?? null,

            'updated_by' =>
                Auth::id(),

        ]);


        return back()->with(
            'success',
            'Stage progress updated successfully.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | COMPLETE STAGE
    |--------------------------------------------------------------------------
    */

    public function complete($id)
    {
        DB::transaction(function () use ($id) {

            $stage = FitoutStage::lockForUpdate()
                ->findOrFail($id);


            if ($stage->stage_status !== 'In Progress') {

                throw \Illuminate\Validation\ValidationException::withMessages([
                    'stage' =>
                        'Only stages in progress can be completed.'
                ]);
            }


            $stage->update([

                'stage_status' =>
                    'Completed',

                'completion_percentage' =>
                    100,

                'actual_end_date' =>
                    now()->toDateString(),

                'updated_by' =>
                    Auth::id(),

            ]);

        });


        return back()->with(
            'success',
            'Stage completed successfully.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | HOLD STAGE
    |--------------------------------------------------------------------------
    */

    public function hold($id)
    {
        $stage = FitoutStage::findOrFail($id);


        if (!in_array(
            $stage->stage_status,
            ['Pending', 'In Progress']
        )) {

            return back()->with(
                'error',
                'This stage cannot be put on hold.'
            );
        }


        $stage->update([

            'stage_status' =>
                'On Hold',

            'updated_by' =>
                Auth::id(),

        ]);


        return back()->with(
            'success',
            'Stage put on hold.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | RESUME STAGE
    |--------------------------------------------------------------------------
    */

    public function resume($id)
    {
        $stage = FitoutStage::findOrFail($id);


        if ($stage->stage_status !== 'On Hold') {

            return back()->with(
                'error',
                'Only stages on hold can be resumed.'
            );
        }


        $stage->update([

            'stage_status' =>
                'In Progress',

            'updated_by' =>
                Auth::id(),

        ]);


        return back()->with(
            'success',
            'Stage resumed successfully.'
        );
    }

    private function generateFitoutStages($fitoutRequest)
	{
	    $stages = [
	        'Site Handover',
	        'Civil Work',
	        'Masonry',
	        'Plumbing',
	        'Electrical',
	        'HVAC',
	        'Fire Fighting',
	        'False Ceiling',
	        'Flooring',
	        'Painting',
	        'Glass Installation',
	        'Signage',
	        'Furniture Installation',
	        'Cleaning',
	        'Final Inspection',
	    ];

	    foreach ($stages as $index => $stageName) {

	        FitoutStage::firstOrCreate(
	            [
	                'fitout_request_id' => $fitoutRequest->id,
	                'stage_sequence' => $index + 1,
	            ],
	            [
	                'uuid' => (string) Str::uuid(),
	                'contractor_id' => $fitoutRequest->contractor_id,
	                'stage_name' => $stageName,
	                'stage_status' => 'Pending',
	                'completion_percentage' => 0,
	                'created_by' => auth()->id(),
	                'updated_by' => auth()->id(),
	            ]
	        );
	    }
	}
}