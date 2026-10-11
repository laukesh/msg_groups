<?php

namespace App\Http\Controllers\Admin\Fitout;

use App\Http\Controllers\Controller;
use App\Models\FitoutRequest;
use App\Models\FitoutContractor;
use App\Models\LeaseAgreement;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Models\ProposalUnit;
use App\Models\Unit;
use App\Models\FitoutStage;
use App\Models\FitoutApproval;
class FitoutRequestController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $query = FitoutRequest::query()
            ->with([
                'leaseAgreement',
                'tenant',
                'unit',
                'contractor',
            ]);


        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->where(
                    'request_no',
                    'like',
                    "%{$search}%"
                )

                ->orWhere(
                    'work_description',
                    'like',
                    "%{$search}%"
                )

                ->orWhereHas(
                    'tenant',
                    function ($tenantQuery) use ($search) {

                        $tenantQuery
                            ->where(
                                'company_name',
                                'like',
                                "%{$search}%"
                            )
                            ->orWhere(
                                'tenant_code',
                                'like',
                                "%{$search}%"
                            );
                    }
                )

                ->orWhereHas(
                    'contractor',
                    function ($contractorQuery) use ($search) {

                        $contractorQuery
                            ->where(
                                'contractor_name',
                                'like',
                                "%{$search}%"
                            )
                            ->orWhere(
                                'contractor_code',
                                'like',
                                "%{$search}%"
                            );
                    }
                );

            });
        }


        /*
        |--------------------------------------------------------------------------
        | Fit-Out Type
        |--------------------------------------------------------------------------
        */

        if ($request->filled('fitout_type')) {

            $query->where(
                'fitout_type',
                $request->fitout_type
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Status
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {

            $query->where(
                'fitout_status',
                $request->status
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


        /*
        |--------------------------------------------------------------------------
        | From Date
        |--------------------------------------------------------------------------
        */

        if ($request->filled('from_date')) {

            $query->whereDate(
                'proposed_start_date',
                '>=',
                $request->from_date
            );
        }


        /*
        |--------------------------------------------------------------------------
        | To Date
        |--------------------------------------------------------------------------
        */

        if ($request->filled('to_date')) {

            $query->whereDate(
                'proposed_start_date',
                '<=',
                $request->to_date
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Requests
        |--------------------------------------------------------------------------
        */

        $requests = $query
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | Filter Data
        |--------------------------------------------------------------------------
        */

        $contractors = FitoutContractor::where(
                'status',
                'Approved'
            )
            ->orderBy('contractor_name')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Summary
        |--------------------------------------------------------------------------
        */

        $totalRequests = FitoutRequest::count();

        $draftRequests = FitoutRequest::where(
            'fitout_status',
            'Draft'
        )->count();

        $submittedRequests = FitoutRequest::where(
            'fitout_status',
            'Submitted'
        )->count();

        $underReviewRequests = FitoutRequest::where(
            'fitout_status',
            'Under Review'
        )->count();

        $approvedRequests = FitoutRequest::where(
            'fitout_status',
            'Approved'
        )->count();

        $inProgressRequests = FitoutRequest::where(
            'fitout_status',
            'In Progress'
        )->count();

        $completedRequests = FitoutRequest::where(
            'fitout_status',
            'Completed'
        )->count();


        return view(
            'admin.fitout.requests.index',
            compact(
                'requests',
                'contractors',
                'totalRequests',
                'draftRequests',
                'submittedRequests',
                'underReviewRequests',
                'approvedRequests',
                'inProgressRequests',
                'completedRequests'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    
    public function create()
    {
        /*
        |--------------------------------------------------------------------------
        | Active / Renewed Lease Agreements
        |--------------------------------------------------------------------------
        */

        $leaseAgreements = LeaseAgreement::with('tenant')
            ->whereIn(
                'agreement_status',
                ['Active', 'Renewed']
            )
            ->orderByDesc('id')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Approved Contractors
        |--------------------------------------------------------------------------
        */

        $contractors = FitoutContractor::where(
                'status',
                'Approved'
            )
            ->orderBy('contractor_name')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Prepare Lease Agreement Units
        |--------------------------------------------------------------------------
        |
        | Units are linked through:
        |
        | lease_agreements.proposal_id
        |          ↓
        | proposal_units.lease_proposal_id
        |          ↓
        | proposal_units.unit_id
        |
        */

        $leaseAgreementUnits = [];


        foreach ($leaseAgreements as $agreement) {

            $unitIds = ProposalUnit::where(
                    'lease_proposal_id',
                    $agreement->proposal_id
                )
                ->pluck('unit_id');


            $units = Unit::whereIn(
                    'id',
                    $unitIds
                )
                ->orderBy('unit_no')
                ->get();


            $leaseAgreementUnits[$agreement->id] = [

                'tenant_name' =>
                    $agreement->tenant->company_name ?? '',

                'lease_start_date' =>
                    optional(
                        $agreement->lease_start_date
                    )->format('d M Y'),

                'lease_end_date' =>
                    optional(
                        $agreement->lease_end_date
                    )->format('d M Y'),

                'units' =>
                    $units->map(function ($unit) {

                        return [

                            'id' =>
                                $unit->id,

                            'unit_no' =>
                                $unit->unit_no,

                        ];

                    })->values()->toArray(),
            ];
        }

        $contractorData = $contractors->map(function ($contractor) {

            return [
                'id' => $contractor->id,
                'name' => $contractor->contractor_name,
                'mobile' => $contractor->mobile,
                'status' => $contractor->status,
            ];

        })->values();

        return view(
            'admin.fitout.requests.create',
            compact(
                'leaseAgreements',
                'contractors',
                'leaseAgreementUnits',
                'contractorData'
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

            'lease_agreement_id' => [
                'required',
                'exists:lease_agreements,id',
            ],

            'unit_id' => [
                'required',
                'exists:units,id',
            ],

            'contractor_id' => [
                'required',
                'exists:fitout_contractors,id',
            ],

            'fitout_type' => [
                'required',
                'in:New,Renovation,Expansion,Modification',
            ],

            'work_description' => [
                'nullable',
                'string',
            ],

            'proposed_start_date' => [
                'required',
                'date',
            ],

            'proposed_end_date' => [
                'required',
                'date',
                'after_or_equal:proposed_start_date',
            ],

            'estimated_cost' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'safety_induction_completed' => [
                'nullable',
                'in:Yes,No',
            ],

            'insurance_verified' => [
                'nullable',
                'in:Yes,No',
            ],

            'work_permit_no' => [
                'nullable',
                'string',
                'max:50',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Load Lease Agreement
        |--------------------------------------------------------------------------
        */

        $leaseAgreement = LeaseAgreement::with('tenant')
            ->findOrFail(
                $validated['lease_agreement_id']
            );


        /*
        |--------------------------------------------------------------------------
        | Validate Lease Agreement Status
        |--------------------------------------------------------------------------
        */

        if (!in_array(
            $leaseAgreement->agreement_status,
            ['Active', 'Renewed']
        )) {

            return back()
                ->withInput()
                ->withErrors([
                    'lease_agreement_id' =>
                        'Fit-Out request can only be created for an Active or Renewed lease agreement.'
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Validate Unit Belongs To Lease Agreement
        |--------------------------------------------------------------------------
        |
        | lease_agreements.proposal_id
        |          ↓
        | proposal_units.lease_proposal_id
        |          ↓
        | proposal_units.unit_id
        |
        */

        $unitBelongsToAgreement = ProposalUnit::where(
                'lease_proposal_id',
                $leaseAgreement->proposal_id
            )
            ->where(
                'unit_id',
                $validated['unit_id']
            )
            ->exists();


        if (!$unitBelongsToAgreement) {

            return back()
                ->withInput()
                ->withErrors([
                    'unit_id' =>
                        'The selected unit does not belong to this lease agreement.'
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Load Contractor
        |--------------------------------------------------------------------------
        */

        $contractor = FitoutContractor::findOrFail(
            $validated['contractor_id']
        );


        /*
        |--------------------------------------------------------------------------
        | Contractor Must Be Approved
        |--------------------------------------------------------------------------
        */

        if ($contractor->status !== 'Approved') {

            return back()
                ->withInput()
                ->withErrors([
                    'contractor_id' =>
                        'Only approved contractors can be assigned to a fit-out request.'
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Generate Request Number
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use (
            $validated,
            $leaseAgreement,
            $contractor
        ) {

            $year = now()->format('Y');


            $lastRequest = FitoutRequest::where(
                    'request_no',
                    'like',
                    'FO-' . $year . '-%'
                )
                ->lockForUpdate()
                ->orderByDesc('id')
                ->first();


            $lastNumber = 0;


            if ($lastRequest) {

                $lastNumber = (int) substr(
                    $lastRequest->request_no,
                    -5
                );
            }


            $requestNo =
                'FO-' .
                $year .
                '-' .
                str_pad(
                    $lastNumber + 1,
                    5,
                    '0',
                    STR_PAD_LEFT
                );


            /*
            |--------------------------------------------------------------------------
            | Create Request
            |--------------------------------------------------------------------------
            */

            FitoutRequest::create([

                'uuid' =>
                    (string) Str::uuid(),

                'request_no' =>
                    $requestNo,

                'lease_agreement_id' =>
                    $leaseAgreement->id,

                /*
                |--------------------------------------------------------------------------
                | Tenant comes from Lease Agreement
                |--------------------------------------------------------------------------
                */

                'tenant_id' =>
                    $leaseAgreement->tenant_id,

                'unit_id' =>
                    $validated['unit_id'],

                /*
                |--------------------------------------------------------------------------
                | Contractor
                |--------------------------------------------------------------------------
                */

                'contractor_id' =>
                    $contractor->id,

                'contractor_name' =>
                    $contractor->contractor_name,

                'contractor_contact' =>
                    $contractor->mobile,

                /*
                |--------------------------------------------------------------------------
                | Fit-Out Details
                |--------------------------------------------------------------------------
                */

                'fitout_type' =>
                    $validated['fitout_type'],

                'work_description' =>
                    $validated['work_description'] ?? null,

                'proposed_start_date' =>
                    $validated['proposed_start_date'],

                'proposed_end_date' =>
                    $validated['proposed_end_date'],

                'estimated_cost' =>
                    $validated['estimated_cost'] ?? 0,

                'safety_induction_completed' =>
                    $validated['safety_induction_completed'] ?? 'No',

                'insurance_verified' =>
                    $validated['insurance_verified'] ?? 'No',

                'work_permit_no' =>
                    $validated['work_permit_no'] ?? null,

                /*
                |--------------------------------------------------------------------------
                | Initial Status
                |--------------------------------------------------------------------------
                */

                'fitout_status' =>
                    'Draft',

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
                'admin.fitout.requests.index'
            )
            ->with(
                'success',
                'Fit-Out request created successfully.'
            );
    }

    public function show($id)
    {
        $fitoutRequest = FitoutRequest::with([
            'leaseAgreement',
            'tenant',
            'unit',
            'contractor',
            'stages',
            'documents',
            'approvals',
            'inspections',
            'snags',
            'handovers',
        ])->findOrFail($id);

        return view(
            'admin.fitout.requests.show',
            compact('fitoutRequest')
        );
    }

    public function submit($id)
    {
        $fitoutRequest = FitoutRequest::findOrFail($id);

        if ($fitoutRequest->fitout_status !== 'Draft') {

            return back()->with(
                'error',
                'Only draft fit-out requests can be submitted.'
            );
        }

        $fitoutRequest->update([
            'fitout_status' => 'Submitted',
            'updated_by' => Auth::id(),
        ]);

        return back()->with(
            'success',
            'Fit-Out request submitted successfully.'
        );
    }

    public function startReview($id)
    {
        $fitoutRequest = FitoutRequest::findOrFail($id);

        if ($fitoutRequest->fitout_status !== 'Submitted') {

            return back()->with(
                'error',
                'Only submitted fit-out requests can be moved to review.'
            );
        }

        $fitoutRequest->update([
            'fitout_status' => 'Under Review',
            'updated_by' => Auth::id(),
        ]);

        return back()->with(
            'success',
            'Fit-Out request moved to review.'
        );
    }

    public function approve($id)
    {
        $fitoutRequest = FitoutRequest::with('contractor')
            ->findOrFail($id);


        if ($fitoutRequest->fitout_status !== 'Under Review') {

            return back()->with(
                'error',
                'Only requests under review can be approved.'
            );
        }


        if (!$fitoutRequest->contractor) {

            return back()->with(
                'error',
                'A contractor must be assigned before approval.'
            );
        }


        if ($fitoutRequest->contractor->status !== 'Approved') {

            return back()->with(
                'error',
                'The assigned contractor is not approved.'
            );
        }


        /*$fitoutRequest->update([

            'fitout_status' => 'Approved',

            'updated_by' => Auth::id(),

        ]);*/

        DB::transaction(function () use ($fitoutRequest) {

            $fitoutRequest->update([
                'fitout_status' => 'Approved',
                'updated_by' => auth()->id(),
            ]);

            $this->generateFitoutStages($fitoutRequest);
        });


        return back()->with(
            'success',
            'Fit-Out request approved successfully.'
        );
    }

    public function reject(Request $request, $id)
    {
        $validated = $request->validate([

            'rejection_reason' => [
                'required',
                'string',
                'max:1000',
            ],

        ]);


        $fitoutRequest = FitoutRequest::findOrFail($id);


        if (!in_array(
            $fitoutRequest->fitout_status,
            ['Submitted', 'Under Review']
        )) {

            return back()->with(
                'error',
                'This fit-out request cannot be rejected at its current stage.'
            );
        }


        $fitoutRequest->update([

            'fitout_status' => 'Rejected',

            'remarks' =>
                $validated['rejection_reason'],

            'updated_by' =>
                Auth::id(),

        ]);


        return back()->with(
            'success',
            'Fit-Out request rejected.'
        );
    }

    /*public function start($id)
    {
        $fitoutRequest = FitoutRequest::findOrFail($id);


        if ($fitoutRequest->fitout_status !== 'Approved') {

            return back()->with(
                'error',
                'Only approved fit-out requests can be started.'
            );
        }


        $fitoutRequest->update([

            'fitout_status' =>
                'In Progress',

            'actual_start_date' =>
                now()->toDateString(),

            'updated_by' =>
                Auth::id(),

        ]);


        return back()->with(
            'success',
            'Fit-Out work started successfully.'
        );
    }*/
    public function start($id)
    {
        DB::transaction(function () use ($id) {

            $fitoutRequest = FitoutRequest::with('contractor')
                ->lockForUpdate()
                ->findOrFail($id);


            /*
            |--------------------------------------------------------------------------
            | Validate Status
            |--------------------------------------------------------------------------
            */

            if ($fitoutRequest->fitout_status !== 'Approved') {

                throw \Illuminate\Validation\ValidationException::withMessages([
                    'fitout' =>
                        'Only approved fit-out requests can be started.'
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Validate Contractor
            |--------------------------------------------------------------------------
            */

            if (!$fitoutRequest->contractor) {

                throw \Illuminate\Validation\ValidationException::withMessages([
                    'contractor' =>
                        'A contractor must be assigned before starting the fit-out.'
                ]);
            }


            if ($fitoutRequest->contractor->status !== 'Approved') {

                throw \Illuminate\Validation\ValidationException::withMessages([
                    'contractor' =>
                        'The assigned contractor is not approved.'
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Prevent Duplicate Stages
            |--------------------------------------------------------------------------
            */

            if ($fitoutRequest->stages()->exists()) {

                throw \Illuminate\Validation\ValidationException::withMessages([
                    'fitout' =>
                        'Fit-Out stages have already been created.'
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Start Fit-Out
            |--------------------------------------------------------------------------
            */

            $fitoutRequest->update([

                'fitout_status' =>
                    'In Progress',

                'actual_start_date' =>
                    now()->toDateString(),

                'updated_by' =>
                    Auth::id(),

            ]);


            /*
            |--------------------------------------------------------------------------
            | Default Stages
            |--------------------------------------------------------------------------
            */

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


            /*
            |--------------------------------------------------------------------------
            | Create Stages
            |--------------------------------------------------------------------------
            */

            foreach ($stages as $index => $stageName) {

                FitoutStage::create([

                    'uuid' =>
                        (string) Str::uuid(),

                    'fitout_request_id' =>
                        $fitoutRequest->id,

                    'contractor_id' =>
                        $fitoutRequest->contractor_id,

                    'stage_name' =>
                        $stageName,

                    'stage_sequence' =>
                        $index + 1,

                    'planned_start_date' =>
                        null,

                    'planned_end_date' =>
                        null,

                    'actual_start_date' =>
                        null,

                    'actual_end_date' =>
                        null,

                    'completion_percentage' =>
                        0,

                    'engineer_id' =>
                        null,

                    'stage_status' =>
                        'Pending',

                    'remarks' =>
                        null,

                    'created_by' =>
                        Auth::id(),

                    'updated_by' =>
                        Auth::id(),

                ]);
            }

        });


        return back()->with(
            'success',
            'Fit-Out started and stages created successfully.'
        );
    }

    public function complete($id)
    {
        $fitoutRequest = FitoutRequest::with([
            'stages',
            'inspections',
            'snags',
        ])->findOrFail($id);


        if ($fitoutRequest->fitout_status !== 'In Progress') {

            return back()->with(
                'error',
                'Only fit-out requests in progress can be completed.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Check Open Snags
        |--------------------------------------------------------------------------
        */

        $openSnags = $fitoutRequest->snags()
            ->whereNotIn(
                'status',
                ['Closed']
            )
            ->count();


        if ($openSnags > 0) {

            return back()->with(
                'error',
                'Fit-Out cannot be completed while open snags exist.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Complete
        |--------------------------------------------------------------------------
        */

        $fitoutRequest->update([

            'fitout_status' =>
                'Completed',

            'actual_end_date' =>
                now()->toDateString(),

            'updated_by' =>
                Auth::id(),

        ]);


        return back()->with(
            'success',
            'Fit-Out request completed successfully.'
        );
    }

    public function close($id)
    {
        $fitoutRequest = FitoutRequest::findOrFail($id);


        if ($fitoutRequest->fitout_status !== 'Completed') {

            return back()->with(
                'error',
                'Only completed fit-out requests can be closed.'
            );
        }


        $fitoutRequest->update([

            'fitout_status' =>
                'Closed',

            'updated_by' =>
                Auth::id(),

        ]);


        return back()->with(
            'success',
            'Fit-Out request closed successfully.'
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

    public function generateApproval($id)
    {
        $fitoutRequest = FitoutRequest::findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | Prevent duplicate workflow
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
                'Engineering',
                'Electrical',
                'HVAC',
                'Fire & Safety',
                'Architecture',
                'Security',
                'Leasing',
                'Mall Management',
                'Final Approval',
            ];

            foreach ($levels as $index => $approvalType) {

                FitoutApproval::create([

                    'uuid' => (string) Str::uuid(),

                    'fitout_request_id' => $fitoutRequest->id,

                    'approval_level' => $index + 1,

                    'approval_type' => $approvalType,

                    'approval_status' => 'Pending',

                    'approver_id' => auth()->id(),

                    'created_by' => auth()->id(),

                    'updated_by' => auth()->id(),
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Move Fit-Out Request into approval workflow
            |--------------------------------------------------------------------------
            */

            $fitoutRequest->update([

                'fitout_status' => 'Under Review',

                'updated_by' => auth()->id(),

            ]);
        });


        return back()->with(
            'success',
            'Approval workflow generated successfully.'
        );
    }
}