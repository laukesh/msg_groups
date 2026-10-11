<?php

namespace App\Http\Controllers\Admin\Leasing;

use App\Http\Controllers\Controller;
use App\Models\LeaseProposal;
use App\Models\Tenant;
use App\Models\Unit;
use App\Models\ProposalUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class LeaseProposalController extends Controller
{
    /**
     * Display lease proposals.
     */
    public function index(Request $request)
    {
        $query = LeaseProposal::with([
            'tenant',
            'units.unit'
        ]);

        // Search
        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('proposal_no', 'LIKE', "%{$search}%")
                    ->orWhere('proposal_title', 'LIKE', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('proposal_status', $request->status);
        }

        // Tenant filter
        if ($request->filled('tenant_id')) {
            $query->where('tenant_id', $request->tenant_id);
        }

        // Date filter
        if ($request->filled('from_date')) {
            $query->whereDate(
                'proposal_date',
                '>=',
                $request->from_date
            );
        }

        if ($request->filled('to_date')) {
            $query->whereDate(
                'proposal_date',
                '<=',
                $request->to_date
            );
        }

        $proposals = $query
            ->latest('id')
            ->paginate(20)
            ->withQueryString();

        $tenants = Tenant::where('status', 'Active')
            ->orderBy('company_name')
            ->get();

        return view('admin.leasing.proposals.index', compact(
            'proposals',
            'tenants'
        ));
    }

    /**
     * Show create form.
     */
    public function create()
    {
        $tenants = Tenant::where('status', 'Active')
            ->orderBy('company_name')
            ->get();

        $units = Unit::where('current_status', 'Vacant')
            ->orderBy('unit_no')
            ->get();

        return view(
            'admin.leasing.proposals.create',
            compact('tenants', 'units')
        );
    }

    /**
     * Store lease proposal.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tenant_id' => [
                'required',
                'integer',
                'exists:tenants,id'
            ],

            'proposal_title' => [
                'required',
                'string',
                'max:200'
            ],

            'proposal_date' => [
                'required',
                'date'
            ],

            'valid_until' => [
                'required',
                'date'
            ],
            'lease_start_date' => [
                'required',
                'date'
            ],

            'lease_end_date' => [
                'required',
                'date',
                'after:expected_start_date'
            ],

            'unit_ids' => [
                'required',
                'array',
                'min:1'
            ],

            'unit_ids.*' => [
                'required',
                'integer',
                'exists:units,id'
            ],

            'remarks' => [
                'nullable',
                'string'
            ],
            'escalation_percentage' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100'
            ],
            'rent_free_days' => [
                'nullable',
                'integer',
                'min:0'
            ],
            'monthly_rent' => [
                'nullable',
                'numeric',
                'min:0'
            ],

            'cam_amount' => [
                'nullable',
                'numeric',
                'min:0'
            ],

            'security_deposit' => [
                'nullable',
                'numeric',
                'min:0'
            ],

            'fitout_period_days' => [
                'nullable',
                'integer',
                'min:0'
            ],
        ]);

        

        try {


            DB::transaction(function () use ($validated) {

            	$start = Carbon::parse(
		            $validated['lease_start_date']
		        );

		        $end = Carbon::parse(
		            $validated['lease_end_date']
		        );

		        $leasePeriodMonths =
		            $start->diffInMonths($end);

		        if ($end->day >= $start->day) {
		            $leasePeriodMonths++;
		        }
		        //echo $leasePeriodMonths;die();

                /*
                 * Lock selected units while checking them.
                 */
                $units = Unit::whereIn(
                    'id',
                    $validated['unit_ids']
                )
                ->lockForUpdate()
                ->get();

                if (
                    $units->count() !==
                    count($validated['unit_ids'])
                ) {
                    throw new \Exception(
                        'One or more selected units were not found.'
                    );
                }

                /*
                 * Make sure all units are vacant.
                 */
                foreach ($units as $unit) {

                    if ($unit->current_status !== 'Vacant') {
                        throw new \Exception(
                            "Unit {$unit->unit_no} is no longer available."
                        );
                    }
                }

                /*
                 * Generate proposal number.
                 */
                $proposalNumber =
                    $this->generateProposalNumber();

                /*
                 * Create proposal.
                 */
                $proposal = LeaseProposal::create([
                    'uuid' => (string) Str::uuid(),

                    'proposal_no' => $proposalNumber,

                    'tenant_id' => $validated['tenant_id'],

                    'proposal_title' => $validated['proposal_title'],

                    'proposal_date' => $validated['proposal_date'],

                    //'expected_start_date' => $validated['expected_start_date'],

                   // 'expected_end_date' => $validated['expected_end_date'],

                    'proposal_status' => 'Draft',

                    'remarks' =>  $validated['remarks'] ?? null,

                    'created_by' => auth()->id(),

                    'updated_by' => auth()->id(),
                    'valid_until' => $validated['valid_until'] ?? null,

					'lease_start_date' =>  $validated['lease_start_date'],

					'lease_end_date' => $validated['lease_end_date'],

					'lease_period_months' => $leasePeriodMonths,

					'security_deposit' =>  $validated['security_deposit'] ?? 0,

					'monthly_rent' => $validated['monthly_rent'] ?? 0,

					'cam_amount' => $validated['cam_amount'] ?? 0,

					'fitout_period_days' => $validated['fitout_period_days'] ?? 0,

					'rent_free_days' => $validated['rent_free_days'] ?? 0,


					'escalation_percentage' => $validated['escalation_percentage'] ?? null,
                ]);

                /*
                 * Attach units.
                 */
                /*foreach ($validated['unit_ids'] as $unitId) {

                    $proposal->units()->create([
                        'unit_id' => $unitId,
                    ]);
                }*/

                foreach ($validated['unit_ids'] as $unitId) {

					    ProposalUnit::create([

					        'proposal_id' => $proposal->id,

					        'lease_proposal_id' => $proposal->id,

					        'unit_id' => $unitId,

					        'proposed_rent' => 0,

					        'proposed_cam_rate' => 0,

					        'proposed_security_deposit' => 0,

					        'rent_free_days' => 0,

					        'fitout_period_days' => 0,

					        'created_by' => auth()->id(),

					        'updated_by' => auth()->id(),

					    ]);
					}
            });

            return redirect()
                ->route('admin.leasing.proposals.index')
                ->with(
                    'success',
                    'Lease proposal created successfully.'
                );

        } catch (\Throwable $e) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    $e->getMessage()
                );
        }
    }

    /**
     * Display proposal.
     */
    public function show($id)
    {
        $proposal = LeaseProposal::with([
	        'tenant',
	        'proposalUnits.unit'
	    ])->findOrFail($id);

        return view(
            'admin.leasing.proposals.show',
            compact('proposal')
        );
    }

    /**
     * Show edit form.
     */
    public function edit($id)
    {
        $proposal = LeaseProposal::with([
            'units'
        ])->findOrFail($id);

        if ($proposal->proposal_status !== 'Draft') {
            return redirect()
                ->route(
                    'admin.leasing.proposals.index'
                )
                ->with(
                    'error',
                    'Only draft proposals can be edited.'
                );
        }

        $tenants = Tenant::where('status', 'Active')
            ->orderBy('company_name')
            ->get();

        /*
         * Include currently selected units as well.
         */
        $selectedUnitIds = $proposal
            ->units
            ->pluck('unit_id')
            ->toArray();

        $units = Unit::where(function ($query) use (
            $selectedUnitIds
        ) {
            $query->where(
                'current_status',
                'Vacant'
            )
            ->orWhereIn(
                'id',
                $selectedUnitIds
            );
        })
        ->orderBy('unit_no')
        ->get();

        return view(
            'admin.leasing.proposals.edit',
            compact(
                'proposal',
                'tenants',
                'units',
                'selectedUnitIds'
            )
        );
    }

    /**
     * Update proposal.
     */
    public function update(
        Request $request,
        $id
    ) {
        $proposal = LeaseProposal::findOrFail($id);

        if ($proposal->proposal_status !== 'Draft') {
            return redirect()
                ->route(
                    'admin.leasing.proposals.index'
                )
                ->with(
                    'error',
                    'Only draft proposals can be updated.'
                );
        }

        /*$validated = $request->validate([
            'tenant_id' => [
                'required',
                'integer',
                'exists:tenants,id'
            ],

            'proposal_title' => [
                'required',
                'string',
                'max:200'
            ],

            'proposal_date' => [
                'required',
                'date'
            ],

            'expected_start_date' => [
                'required',
                'date'
            ],

            'expected_end_date' => [
                'required',
                'date',
                'after:expected_start_date'
            ],

            'unit_ids' => [
                'required',
                'array',
                'min:1'
            ],

            'unit_ids.*' => [
                'required',
                'integer',
                'exists:units,id'
            ],

            'remarks' => [
                'nullable',
                'string'
            ],
        ]);*/

        $validated = $request->validate([
            'tenant_id' => [
                'required',
                'integer',
                'exists:tenants,id'
            ],

            'proposal_title' => [
                'required',
                'string',
                'max:200'
            ],

            'proposal_date' => [
                'required',
                'date'
            ],

            'valid_until' => [
                'required',
                'date'
            ],
            'lease_start_date' => [
                'required',
                'date'
            ],

            'lease_end_date' => [
                'required',
                'date',
                'after:expected_start_date'
            ],

            'unit_ids' => [
                'required',
                'array',
                'min:1'
            ],

            'unit_ids.*' => [
                'required',
                'integer',
                'exists:units,id'
            ],

            'remarks' => [
                'nullable',
                'string'
            ],
            'escalation_percentage' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100'
            ],
            'rent_free_days' => [
                'nullable',
                'integer',
                'min:0'
            ],
            'monthly_rent' => [
                'nullable',
                'numeric',
                'min:0'
            ],

            'cam_amount' => [
                'nullable',
                'numeric',
                'min:0'
            ],

            'security_deposit' => [
                'nullable',
                'numeric',
                'min:0'
            ],

            'fitout_period_days' => [
                'nullable',
                'integer',
                'min:0'
            ],
        ]);

        try {

            DB::transaction(function () use (
                $proposal,
                $validated
            ) {

            	$start = Carbon::parse(
		            $validated['lease_start_date']
		        );

		        $end = Carbon::parse(
		            $validated['lease_end_date']
		        );

		        $leasePeriodMonths =
		            $start->diffInMonths($end);

		        if ($end->day >= $start->day) {
		            $leasePeriodMonths++;
		        }

                $units = Unit::whereIn(
                    'id',
                    $validated['unit_ids']
                )
                ->lockForUpdate()
                ->get();

                foreach ($units as $unit) {

                    /*
                     * Existing proposal units are allowed.
                     * New units must still be vacant.
                     */
                    $alreadySelected =
                        $proposal->units()
                            ->where(
                                'unit_id',
                                $unit->id
                            )
                            ->exists();

                    if (
                        !$alreadySelected &&
                        $unit->current_status !== 'Vacant'
                    ) {
                        throw new \Exception(
                            "Unit {$unit->unit_no} is no longer available."
                        );
                    }
                }

                $proposal->update([
                    'tenant_id' => $validated['tenant_id'],

                    'proposal_title' => $validated['proposal_title'],

                    'proposal_date' => $validated['proposal_date'],

                    //'expected_start_date' => $validated['expected_start_date'],

                   // 'expected_end_date' => $validated['expected_end_date'],

                    //'proposal_status' => 'Draft',

                    'remarks' =>  $validated['remarks'] ?? null,

                    'created_by' => auth()->id(),

                    'updated_by' => auth()->id(),
                    'valid_until' => $validated['valid_until'] ?? null,

					'lease_start_date' =>  $validated['lease_start_date'],

					'lease_end_date' => $validated['lease_end_date'],

					'lease_period_months' => $leasePeriodMonths,

					'security_deposit' =>  $validated['security_deposit'] ?? 0,

					'monthly_rent' => $validated['monthly_rent'] ?? 0,

					'cam_amount' => $validated['cam_amount'] ?? 0,

					'fitout_period_days' => $validated['fitout_period_days'] ?? 0,

					'rent_free_days' => $validated['rent_free_days'] ?? 0,


					'escalation_percentage' => $validated['escalation_percentage'] ?? null,

                    'updated_by' =>
                        auth()->id(),
                ]);

                /*
                 * Replace proposal units.
                 */
                /*$proposal->units()->delete();

                foreach (
                    $validated['unit_ids']
                    as $unitId
                ) {

                    $proposal->units()->create([
                        'unit_id' => $unitId,
                    ]);
                }*/

                $proposal->units()->delete();

				/*foreach ($validated['unit_ids'] as $unitId) {

				    ProposalUnit::updateOrCreate(
				        [
				            'proposal_id' => $proposal->id,
				            'unit_id' => $unitId,
				        ],
				        [
				            'lease_proposal_id' => $proposal->id,
				            'proposed_rent' => 0,
				            'proposed_cam_rate' => 0,
				            'proposed_security_deposit' => 0,
				            'rent_free_days' => 0,
				            'fitout_period_days' => 0,
				            'updated_by' => auth()->id(),
				        ]
				    );
				}*/

				// Remove units that are no longer selected
ProposalUnit::where('proposal_id', $proposal->id)
    ->whereNotIn('unit_id', $validated['unit_ids'])
    ->delete();

// Add new units / update existing units
foreach ($validated['unit_ids'] as $unitId) {

    ProposalUnit::updateOrCreate(
        [
            'proposal_id' => $proposal->id,
            'unit_id'     => $unitId,
        ],
        [
            'lease_proposal_id'          => $proposal->id,
            'proposed_rent'              => 0,
            'proposed_cam_rate'          => 0,
            'proposed_security_deposit'  => 0,
            'rent_free_days'             => 0,
            'fitout_period_days'         => 0,
            'updated_by'                 => auth()->id(),
        ]
    );
}

            });

            return redirect()
                ->route(
                    'admin.leasing.proposals.show',
                    $proposal->id
                )
                ->with(
                    'success',
                    'Lease proposal updated successfully.'
                );

        } catch (\Throwable $e) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    $e->getMessage()
                );
        }
    }

    /**
     * Delete proposal.
     */
    public function destroy($id)
    {
        $proposal = LeaseProposal::findOrFail($id);

        if ($proposal->proposal_status !== 'Draft') {
            return back()->with(
                'error',
                'Only draft proposals can be deleted.'
            );
        }

        $proposal->delete();

        return redirect()
            ->route(
                'admin.leasing.proposals.index'
            )
            ->with(
                'success',
                'Lease proposal deleted successfully.'
            );
    }

    /**
     * Submit proposal for approval.
     */
    public function submit($id)
    {
        $proposal = LeaseProposal::with('units')
            ->findOrFail($id);

        if ($proposal->proposal_status !== 'Draft') {
            return back()->with(
                'error',
                'Only draft proposals can be submitted.'
            );
        }

        if ($proposal->units->isEmpty()) {
            return back()->with(
                'error',
                'At least one unit is required.'
            );
        }

        $proposal->update([
            'proposal_status' => 'Pending Approval',
            'updated_by' => auth()->id(),
        ]);

        return back()->with(
            'success',
            'Lease proposal submitted for approval.'
        );
    }

    /**
     * Approve proposal.
     */
    public function approve($id)
    {
        $proposal = LeaseProposal::findOrFail($id);

        if ($proposal->proposal_status !== 'Pending Approval') {
            return back()->with(
                'error',
                'Only pending proposals can be approved.'
            );
        }

        $proposal->update([
            'proposal_status' => 'Approved',
            'updated_by' => auth()->id(),
        ]);

        return back()->with(
            'success',
            'Lease proposal approved successfully.'
        );
    }

    /**
     * Reject proposal.
     */
    public function reject(
        Request $request,
        $id
    ) {
        $request->validate([
            'rejection_reason' => [
                'required',
                'string',
                'max:1000'
            ]
        ]);

        $proposal = LeaseProposal::findOrFail($id);

        if ($proposal->proposal_status !== 'Pending Approval') {
            return back()->with(
                'error',
                'Only pending proposals can be rejected.'
            );
        }

        $proposal->update([
            'proposal_status' => 'Rejected',
            'rejection_reason' =>
                $request->rejection_reason,
            'updated_by' => auth()->id(),
        ]);

        return back()->with(
            'success',
            'Lease proposal rejected successfully.'
        );
    }

    /**
     * Generate proposal number.
     */
    private function generateProposalNumber()
    {
        $year = date('Y');

        $lastProposal = LeaseProposal::where(
            'proposal_no',
            'LIKE',
            "LP-{$year}-%"
        )
        ->orderByDesc('id')
        ->first();

        if ($lastProposal) {

            $lastNumber = (int) substr(
                $lastProposal->proposal_no,
                -5
            );

            $nextNumber = $lastNumber + 1;

        } else {

            $nextNumber = 1;
        }

        return 'LP-' .
            $year .
            '-' .
            str_pad(
                $nextNumber,
                5,
                '0',
                STR_PAD_LEFT
            );
    }
}