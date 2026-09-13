<?php

namespace App\Http\Controllers\Admin\Fitout;

use App\Http\Controllers\Controller;
use App\Models\FitoutContractor;
use App\Models\FitoutRequest;
use App\Models\FitoutStage;
use App\Models\Inspection;
use App\Models\SnagList;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SnagListController extends Controller
{
    public function index(Request $request)
	{
	    $query = SnagList::with([
	        'fitoutRequest',
	        'inspection',
	        'fitoutStage',
	        'contractor',
	        'assignedTo',
	    ]);

	    if ($request->filled('search')) {

	        $search = $request->search;

	        $query->where(function ($q) use ($search) {

	            $q->where(
	                'snag_number',
	                'like',
	                "%{$search}%"
	            )
	            ->orWhere(
	                'title',
	                'like',
	                "%{$search}%"
	            );

	        });
	    }

	    if ($request->filled('status')) {

	        $query->where(
	            'status',
	            $request->status
	        );
	    }

	    if ($request->filled('priority')) {

	        $query->where(
	            'priority',
	            $request->priority
	        );
	    }

	    if ($request->filled('contractor_id')) {

	        $query->where(
	            'contractor_id',
	            $request->contractor_id
	        );
	    }

	    if ($request->filled('due_date')) {

	        $query->whereDate(
	            'due_date',
	            $request->due_date
	        );
	    }

	    $snags = $query
	        ->latest('id')
	        ->paginate(20);


	    /*
	    |--------------------------------------------------------------------------
	    | Statistics
	    |--------------------------------------------------------------------------
	    */

	    $stats = [

	        'total' => SnagList::count(),

	        'critical' => SnagList::where(
	            'priority',
	            'Critical'
	        )->count(),

	        'open' => SnagList::whereIn(
	            'status',
	            [
	                'Open',
	                'Assigned',
	                'In Progress',
	                'Reopened',
	            ]
	        )->count(),

	        'closed' => SnagList::where(
	            'status',
	            'Closed'
	        )->count(),

	    ];


	    $contractors = FitoutContractor::query()
	        ->orderBy('id')
	        ->get();


	    return view(
	        'admin.fitout.snags.index',
	        compact(
	            'snags',
	            'contractors',
	            'stats'
	        )
	    );
	}


    public function create()
    {
        $inspections = Inspection::with([
            'fitoutRequest',
        ])
        ->whereIn(
            'status',
            ['Completed', 'In Progress']
        )
        ->latest('id')
        ->get();

        $stages = FitoutStage::orderBy(
            'stage_sequence'
        )->get();

        $contractors = FitoutContractor::orderBy(
            'id'
        )->get();

        $users = User::orderBy('name')->get();

        return view(
            'admin.fitout.snags.create',
            compact(
                'inspections',
                'stages',
                'contractors',
                'users'
            )
        );
    }


    public function store(Request $request)
    {
        $validated = $request->validate([

            'fitout_request_id' => [
                'required',
                'exists:fitout_requests,id',
            ],

            'inspection_id' => [
                'required',
                'exists:inspections,id',
            ],

            'fitout_stage_id' => [
                'nullable',
                'exists:fitout_stages,id',
            ],

            'contractor_id' => [
                'nullable',
                'exists:fitout_contractors,id',
            ],

            'title' => [
                'required',
                'string',
                'max:200',
            ],

            'description' => [
                'required',
                'string',
            ],

            'priority' => [
                'required',
                'in:Low,Medium,High,Critical',
            ],

            'category' => [
                'nullable',
                'string',
                'max:100',
            ],

            'location' => [
                'nullable',
                'string',
                'max:200',
            ],

            'reported_date' => [
                'required',
                'date',
            ],

            'due_date' => [
                'nullable',
                'date',
                'after_or_equal:reported_date',
            ],

            'assigned_to' => [
                'nullable',
                'exists:users,id',
            ],

        ]);

        $inspection = Inspection::findOrFail(
		    $validated['inspection_id']
		);

		if (
		    (int) $inspection->fitout_request_id !==
		    (int) $validated['fitout_request_id']
		) {
		    return back()
		        ->withInput()
		        ->withErrors([
		            'inspection_id' =>
		                'The selected inspection does not belong to the selected fit-out request.',
		        ]);
		}


        $snag = SnagList::create([

            'uuid' => Str::uuid(),

            'fitout_request_id' =>
                $validated['fitout_request_id'],

            'inspection_id' =>
                $validated['inspection_id'],

            'fitout_stage_id' =>
                $validated['fitout_stage_id'] ?? null,

            'contractor_id' =>
                $validated['contractor_id'] ?? null,

            'snag_number' =>
                $this->generateSnagNumber(),

            'title' =>
                $validated['title'],

            'description' =>
                $validated['description'],

            'priority' =>
                $validated['priority'],

            'category' =>
                $validated['category'] ?? null,

            'location' =>
                $validated['location'] ?? null,

            'reported_date' =>
                $validated['reported_date'],

            'due_date' =>
                $validated['due_date'] ?? null,

            'assigned_to' =>
                $validated['assigned_to'] ?? null,

            'status' =>
                $validated['assigned_to']
                    ? 'Assigned'
                    : 'Open',

            'created_by' =>
                auth()->id(),

            'updated_by' =>
                auth()->id(),

        ]);


        return redirect()
            ->route(
                'admin.fitout.snags.show',
                $snag->id
            )
            ->with(
                'success',
                'Snag created successfully.'
            );
    }


    public function show($id)
    {
        $snag = SnagList::with([
            'fitoutRequest.tenant',
            'fitoutRequest.unit',
            'inspection',
            'fitoutStage',
            'contractor',
            'assignedTo',
            'resolvedBy',
            'verifiedBy',
        ])->findOrFail($id);

        return view(
            'admin.fitout.snags.show',
            compact('snag')
        );
    }


    public function edit($id)
    {
        $snag = SnagList::with([
            'fitoutRequest',
            'inspection',
            'fitoutStage',
            'contractor',
            'assignedTo',
        ])->findOrFail($id);

        $inspections = Inspection::with('fitoutRequest')
            ->orderByDesc('id')
            ->get();

        $fitoutRequests = FitoutRequest::orderByDesc('id')
            ->get();

        $stages = FitoutStage::orderBy('stage_sequence')
            ->get();

        $contractors = FitoutContractor::orderBy('id')
            ->get();

        $users = User::orderBy('name')
            ->get();

        return view(
            'admin.fitout.snags.edit',
            compact(
                'snag',
                'inspections',
                'fitoutRequests',
                'stages',
                'contractors',
                'users'
            )
        );
    }


    public function update(Request $request, $id)
    {
        $snag = SnagList::findOrFail($id);

        $validated = $request->validate([
            'fitout_request_id' => [
                'required',
                'exists:fitout_requests,id',
            ],

            'inspection_id' => [
                'required',
                'exists:inspections,id',
            ],

            'fitout_stage_id' => [
                'nullable',
                'exists:fitout_stages,id',
            ],

            'contractor_id' => [
                'nullable',
                'exists:fitout_contractors,id',
            ],

            'title' => [
                'required',
                'string',
                'max:200',
            ],

            'description' => [
                'required',
                'string',
            ],

            'priority' => [
                'required',
                'in:Low,Medium,High,Critical',
            ],

            'category' => [
                'nullable',
                'string',
                'max:100',
            ],

            'location' => [
                'nullable',
                'string',
                'max:200',
            ],

            'reported_date' => [
                'required',
                'date',
            ],

            'due_date' => [
                'nullable',
                'date',
            ],

            'assigned_to' => [
                'nullable',
                'exists:users,id',
            ],

            'status' => [
                'required',
                'in:Open,Assigned,In Progress,Resolved,Under Verification,Closed,Rejected,Reopened',
            ],

            'corrective_action' => [
                'nullable',
                'string',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Verify inspection belongs to request
        |--------------------------------------------------------------------------
        */

        $inspection = Inspection::findOrFail(
            $validated['inspection_id']
        );

        if (
            (int) $inspection->fitout_request_id !==
            (int) $validated['fitout_request_id']
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'inspection_id' =>
                        'The selected inspection does not belong to the selected fit-out request.',
                ]);
        }


        $validated['updated_by'] = auth()->id();


        $snag->update($validated);


        return redirect()
            ->route(
                'admin.fitout.snags.show',
                $snag->id
            )
            ->with(
                'success',
                'Snag updated successfully.'
            );
    }

    public function destroy($id)
	{
	    $snag = SnagList::findOrFail($id);

	    $snag->update([
	        'updated_by' => auth()->id(),
	    ]);

	    $snag->delete();

	    return redirect()
	        ->route('admin.fitout.snags.index')
	        ->with(
	            'success',
	            'Snag deleted successfully.'
	        );
	}


    private function generateSnagNumber()
    {
        $lastId = SnagList::withTrashed()
            ->max('id') ?? 0;

        return 'SNAG-' .
            date('Y') .
            '-' .
            str_pad(
                $lastId + 1,
                5,
                '0',
                STR_PAD_LEFT
            );
    }

    public function resolve(Request $request, $id)
	{
	    $snag = SnagList::findOrFail($id);

	    if (!in_array($snag->status, [
	        'Open',
	        'Assigned',
	        'In Progress',
	        'Reopened',
	    ])) {
	        return back()->with(
	            'error',
	            'This snag cannot be resolved in its current status.'
	        );
	    }

	    $validated = $request->validate([
	        'corrective_action' => [
	            'required',
	            'string',
	        ],
	    ]);

	    $snag->update([
	        'corrective_action' => $validated['corrective_action'],
	        'resolved_date' => now()->toDateString(),
	        'resolved_by' => auth()->id(),
	        'status' => 'Resolved',
	        'updated_by' => auth()->id(),
	    ]);

	    return redirect()
	        ->route(
	            'admin.fitout.snags.show',
	            $snag->id
	        )
	        ->with(
	            'success',
	            'Snag marked as resolved.'
	        );
	}

	public function verify(Request $request, $id)
	{
	    $snag = SnagList::findOrFail($id);

	    if ($snag->status !== 'Under Verification') {
	        return back()->with(
	            'error',
	            'Only snags under verification can be verified.'
	        );
	    }

	    $validated = $request->validate([
	        'verification_status' => [
	            'required',
	            'in:Closed,Reopened',
	        ],

	        'verification_comments' => [
	            'nullable',
	            'string',
	        ],
	    ]);

	    $snag->update([
	        'status' => $validated['verification_status'],

	        'verification_comments' =>
	            $validated['verification_comments'] ?? null,

	        'verified_by' => auth()->id(),

	        'verified_at' => now(),

	        'updated_by' => auth()->id(),
	    ]);

	    return redirect()
	        ->route(
	            'admin.fitout.snags.show',
	            $snag->id
	        )
	        ->with(
	            'success',
	            $validated['verification_status'] === 'Closed'
	                ? 'Snag verified and closed successfully.'
	                : 'Snag rejected and reopened.'
	        );
	}

	public function startVerification($id)
	{
	    $snag = SnagList::findOrFail($id);

	    if ($snag->status !== 'Resolved') {
	        return back()->with(
	            'error',
	            'Only resolved snags can be sent for verification.'
	        );
	    }

	    $snag->update([
	        'status' => 'Under Verification',
	        'updated_by' => auth()->id(),
	    ]);

	    return redirect()
	        ->route('admin.fitout.snags.show', $snag->id)
	        ->with(
	            'success',
	            'Snag moved to verification.'
	        );
	}
}