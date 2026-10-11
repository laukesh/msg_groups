<?php

namespace App\Http\Controllers\Admin\Leasing;


use App\Http\Controllers\Controller;
use App\Models\LeasingPipeline;
use App\Models\LeasingProspect;
use App\Models\LeasingProspectActivity;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Carbon\Carbon;

class LeasingPipelineController extends Controller
{
    /**
     * Leasing Pipeline.
     *
     * Live pipeline generated from existing:
     *
     * lease_proposals
     * proposal_units
     * units
     * tenants
     * users
     * lease_agreements
     * fitout_requests
     */
    public function index(Request $request)
{
    $query = LeasingProspect::query()
            ->with(['owner'])
            ->withCount('activities');

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where('company_name', 'like', "%{$search}%")
                    ->orWhere(
                        'contact_name',
                        'like',
                        "%{$search}%"
                    )
                    ->orWhere(
                        'brand_name',
                        'like',
                        "%{$search}%"
                    )
                    ->orWhere(
                        'email',
                        'like',
                        "%{$search}%"
                    )
                    ->orWhere(
                        'phone',
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

        if ($request->filled('category')) {
            $query->where(
                'category',
                $request->category
            );
        }

        if ($request->filled('source')) {
            $query->where(
                'source',
                $request->source
            );
        }

        if ($request->filled('owner_id')) {
            $query->where(
                'owner_id',
                $request->owner_id
            );
        }

        if ($request->filled('priority')) {
            $query->where(
                'priority',
                $request->priority
            );
        }

        $prospects = $query
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $total = LeasingProspect::count();

        $activeCount = LeasingProspect::active()->count();

        $leadCount = LeasingProspect::leads()->count();

        $prospectCount = LeasingProspect::prospects()->count();

        $convertedCount = LeasingProspect::converted()->count();

        $followUpsDue = LeasingProspect::followUpsDue()->count();

        $conversionRate = $total > 0
            ? round(
                ($convertedCount / $total) * 100,
                1
            )
            : 0;

        $statusCounts = LeasingProspect::query()
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $owners = User::query()
            ->orderBy('name')
            ->get();

        $categories = LeasingProspect::query()
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        $sources = LeasingProspect::query()
            ->whereNotNull('source')
            ->where('source', '!=', '')
            ->distinct()
            ->orderBy('source')
            ->pluck('source');

        return view(
            'admin.leasing.prospects.index',
            compact(
                'prospects',
                'total',
                'activeCount',
                'leadCount',
                'prospectCount',
                'convertedCount',
                'followUpsDue',
                'conversionRate',
                'statusCounts',
                'owners',
                'categories',
                'sources'
            )
        );
}



    /**
     * Apply stage filter.
     */
    protected function applyStageFilter(
        $query,
        string $stage
    ): void {

        switch ($stage) {

            case 'Prospect':

                $query->where(
                    'lp.proposal_status',
                    'Draft'
                );

                break;


            case 'Negotiation':

                $query->where(
                    'lp.proposal_status',
                    'Pending Approval'
                );

                break;


            case 'Approval':

                $query->where(
                    'lp.proposal_status',
                    'Approved'
                );

                break;


            case 'Lease Signed':

                $query->where(
                    'lp.proposal_status',
                    'Converted'
                );

                break;


            case 'Lost':

                $query->where(
                    'lp.proposal_status',
                    'Rejected'
                );

                break;


            case 'Opening':

                $query->where(
                    'lp.proposal_status',
                    'Converted'
                );

                $query->whereNotNull(
                    'la.handover_date'
                );

                break;
        }
    }


    /**
     * Resolve current pipeline stage.
     */
    protected function resolveStage($item): string
    {
        /*
        |--------------------------------------------------------------------------
        | Lost
        |--------------------------------------------------------------------------
        */

        if (
            $item->proposal_status ===
            'Rejected'
        ) {

            return 'Lost';
        }


        /*
        |--------------------------------------------------------------------------
        | Converted + Handover = Opening
        |--------------------------------------------------------------------------
        */

        if (
            $item->proposal_status ===
                'Converted'
            &&
            !empty(
                $item->handover_date
            )
        ) {

            return 'Opening';
        }


        /*
        |--------------------------------------------------------------------------
        | Converted = Lease Signed
        |--------------------------------------------------------------------------
        */

        if (
            $item->proposal_status ===
            'Converted'
        ) {

            return 'Lease Signed';
        }


        /*
        |--------------------------------------------------------------------------
        | Approved
        |--------------------------------------------------------------------------
        */

        if (
            $item->proposal_status ===
            'Approved'
        ) {

            return 'Approval';
        }


        /*
        |--------------------------------------------------------------------------
        | Pending Approval
        |--------------------------------------------------------------------------
        */

        if (
            $item->proposal_status ===
            'Pending Approval'
        ) {

            return 'Negotiation';
        }


        /*
        |--------------------------------------------------------------------------
        | Draft
        |--------------------------------------------------------------------------
        */

        return 'Prospect';
    }


    /**
     * Resolve probability.
     */
    protected function resolveProbability($item): int
    {
        return match (
            $item->stage
        ) {

            'Prospect'
                => 20,

            'Negotiation'
                => 60,

            'Approval'
                => 85,

            'Lease Signed'
                => 100,

            'Opening'
                => 100,

            'Lost'
                => 0,

            default
                => 0,
        };
    }


    /**
     * Resolve next action.
     */
    protected function resolveNextAction($item): string
    {
        return match (
            $item->stage
        ) {

            'Prospect'
                => 'Qualify prospect / arrange site visit',

            'Negotiation'
                => 'Follow up proposal and negotiate terms',

            'Approval'
                => 'Complete lease approval',

            'Lease Signed'
                => 'Complete handover / start fit-out',

            'Opening'
                => 'Complete opening readiness',

            'Lost'
                => 'Closed / no further action',

            default
                => 'Review opportunity',
        };
    }


    /**
     * Calculate opportunity aging.
     */
    protected function calculateAging(
        $proposalDate
    ): int {

        if (!$proposalDate) {
            return 0;
        }

        try {

            return Carbon::parse(
                $proposalDate
            )->diffInDays(
                now()
            );

        } catch (\Throwable $e) {

            return 0;
        }
    }


    /**
     * Calculate forecast opening.
     */
    protected function forecastOpening($item): ?string
    {
        /*
        |--------------------------------------------------------------------------
        | Existing lease agreement fit-out end date
        |--------------------------------------------------------------------------
        */

        if (
            !empty(
                $item->fitout_end_date
            )
        ) {

            return Carbon::parse(
                $item->fitout_end_date
            )->format('Y-m-d');
        }


        /*
        |--------------------------------------------------------------------------
        | Expected start + fit-out period
        |--------------------------------------------------------------------------
        */

        if (
            !empty(
                $item->expected_start_date
            )
        ) {

            $date = Carbon::parse(
                $item->expected_start_date
            );


            $days =
                (int) (
                    $item->fitout_period_days
                    ?? 0
                );


            if ($days > 0) {

                $date->addDays(
                    $days
                );
            }


            return $date->format(
                'Y-m-d'
            );
        }


        return null;
    }


    /**
     * Show one pipeline opportunity.
     */
    public function show(
        int $id
    ): View {

        $item = DB::table(
            'lease_proposals as lp'
        )

            ->leftJoin(
                'proposal_units as pu',
                function ($join) {

                    $join->on(
                        'pu.lease_proposal_id',
                        '=',
                        'lp.id'
                    )

                    ->whereNull(
                        'pu.deleted_at'
                    );
                }
            )

            ->leftJoin(
                'units as u',
                function ($join) {

                    $join->on(
                        'u.id',
                        '=',
                        'pu.unit_id'
                    )

                    ->whereNull(
                        'u.deleted_at'
                    );
                }
            )

            ->leftJoin(
                'tenants as t',
                function ($join) {

                    $join->on(
                        't.id',
                        '=',
                        'lp.tenant_id'
                    )

                    ->whereNull(
                        't.deleted_at'
                    );
                }
            )

            ->leftJoin(
                'users as owner',
                'owner.id',
                '=',
                'lp.created_by'
            )

            ->leftJoin(
                'unit_types as ut',
                'ut.id',
                '=',
                'u.unit_type_id'
            )

            ->leftJoin(
                'malls as m',
                'm.id',
                '=',
                'u.mall_id'
            )

            ->leftJoin(
                'buildings as b',
                'b.id',
                '=',
                'u.building_id'
            )

            ->leftJoin(
                'floors as f',
                'f.id',
                '=',
                'u.floor_id'
            )

            ->where(
                'lp.id',
                $id
            )

            ->whereNull(
                'lp.deleted_at'
            )

            ->select([
                'lp.*',

                'pu.unit_id',
                'pu.proposed_rent',
                'pu.proposed_cam_rate',

                'u.unit_no',
                'u.carpet_area',
                'u.builtup_area',

                't.company_name',
                't.brand_name',

                'ut.type_name',

                'm.mall_name',
                'b.building_name',
                'f.floor_name',

                'owner.name as owner_name',
            ])

            ->first();


        abort_if(
            !$item,
            404
        );


        /*
        |--------------------------------------------------------------------------
        | Agreement
        |--------------------------------------------------------------------------
        */

        $agreement = DB::table(
            'lease_agreements'
        )
            ->where(
                'proposal_id',
                $item->id
            )
            ->whereNull(
                'deleted_at'
            )
            ->latest('id')
            ->first();


        /*
        |--------------------------------------------------------------------------
        | Resolve
        |--------------------------------------------------------------------------
        */

        $item->agreement_status =
            $agreement->agreement_status
            ?? null;

        $item->handover_date =
            $agreement->handover_date
            ?? null;

        $item->fitout_end_date =
            $agreement->fitout_end_date
            ?? null;

        $item->stage =
            $this->resolveStage(
                $item
            );

        $item->probability =
            $this->resolveProbability(
                $item
            );

        $item->next_action =
            $this->resolveNextAction(
                $item
            );

        $item->aging =
            $this->calculateAging(
                $item->proposal_date
            );

        $item->forecast_opening =
            $this->forecastOpening(
                $item
            );

        $item->expected_rent =
            (float) (
                $item->proposed_rent
                ??
                $item->monthly_rent
                ??
                0
            );
 $prospect->load([
            'owner',
            'creator',
            'updater',
            'activities.creator',
            'pipelines.unit',
            'pipelines.owner',
        ]);

        return view(
            'admin.leasing.pipeline.show',
            compact(
                'item','prospect',
                'agreement'
            )
        );
    }


    /**
     * Redirect to existing Lease Proposal creation.
     *
     * No duplicate pipeline record is created.
     */
   public function create()
{
    $units = Unit::query()
        ->orderBy('id')
        ->get();

    $owners = User::query()
        ->orderBy('name')
        ->get();

    return view('admin.leasing.pipeline.create', compact(
        'units',
        'owners'
    ));
}


    /**
     * Redirect pipeline edit to existing proposal.
     */
   public function edit(LeasingPipeline $pipeline)
{
    $pipeline->load([
        'unit',
        'owner',
        // 'prospect',
    ]);

    $units = Unit::query()
        ->orderBy('id')
        ->get();

    $owners = User::query()
        ->orderBy('name')
        ->get();

    return view('admin.leasing.pipeline.edit', compact(
        'pipeline',
        'units',
        'owners'
    ));
}
   /**
     * Store a new leasing pipeline opportunity.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'prospect_name' => [
                'required',
                'string',
                'max:255',
            ],

            'company_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'unit_id' => [
                'nullable',
                'integer',
                'exists:units,id',
            ],

            'area' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'expected_rent' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'rent_period' => [
                'nullable',
                'in:monthly,quarterly,yearly',
            ],

            'stage' => [
                'required',
                'in:lead,prospect,negotiation,agreement,opening,won,lost',
            ],

            'probability' => [
                'required',
                'integer',
                'min:0',
                'max:100',
            ],

            'next_action' => [
                'nullable',
                'string',
                'max:500',
            ],

            'owner_id' => [
                'nullable',
                'integer',
                'exists:users,id',
            ],
            'prospect_id' => [
                'nullable',
                'integer',
                'exists:leasing_prospects,id',
            ],
            'forecast_opening' => [
                'nullable',
                'date',
            ],

            'expected_opening_date' => [
                'nullable',
                'date',
                'after_or_equal:today',
            ],
            'subject' => [
                'nullable',
                'string',
                'max:255',
            ],
            'description' => [
                'nullable',
                'string',
            ],
            'notes' => [
                'nullable',
                'string',
            ],
        ]);

        DB::beginTransaction();

        try {

            $pipeline = new LeasingPipeline();

            $pipeline->prospect_name = $validated['prospect_name'];
            $pipeline->prospect_id = $validated['prospect_id'];
            $pipeline->company_name = $validated['company_name'] ?? null;
            $pipeline->unit_id = $validated['unit_id'] ?? null;
            $pipeline->area = $validated['area'] ?? null;
            $pipeline->subject = $validated['subject'] ?? 'Lead created';
            $pipeline->description = $validated['description'] ?? 'New leasing lead/prospect created.';
            $pipeline->expected_rent = $validated['expected_rent'] ?? null;
            $pipeline->rent_period = $validated['rent_period'] ?? null;
            $pipeline->stage = $validated['stage'];
            $pipeline->probability = $validated['probability'];
            $pipeline->next_action = $validated['next_action'] ?? null;
            $pipeline->owner_id = $validated['owner_id'] ?? null;
            $pipeline->forecast_opening = $validated['forecast_opening'] ?? null;
            $pipeline->expected_opening_date = $validated['expected_opening_date'] ?? null;
            $pipeline->notes = $validated['notes'] ?? null;

            // Audit user
            $pipeline->created_by = Auth::id();
            $pipeline->updated_by = Auth::id();

            $pipeline->save();

            DB::commit();

            return redirect()
                ->route('admin.leasing.pipeline.index')
                ->with('success', 'Leasing pipeline opportunity created successfully.');

        } catch (\Throwable $e) {

            DB::rollBack();

            report($e);

            return back()
                ->withInput()
                ->with('error', 'Unable to create leasing pipeline opportunity. Please try again.');
        }
    }


    /**
     * Update an existing leasing pipeline opportunity.
     */
    public function update(Request $request, LeasingPipeline $pipeline)
    {
        $validated = $request->validate([
            'prospect_name' => [
                'required',
                'string',
                'max:255',
            ],

            'company_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'unit_id' => [
                'nullable',
                'integer',
                'exists:units,id',
            ],

            'area' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'expected_rent' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'rent_period' => [
                'nullable',
                'in:monthly,quarterly,yearly',
            ],

            'stage' => [
                'required',
                'in:lead,prospect,negotiation,agreement,opening,won,lost',
            ],

            'probability' => [
                'required',
                'integer',
                'min:0',
                'max:100',
            ],

            'next_action' => [
                'nullable',
                'string',
                'max:500',
            ],

            'owner_id' => [
                'nullable',
                'integer',
                'exists:users,id',
            ],

            'forecast_opening' => [
                'nullable',
                'date',
            ],

            'expected_opening_date' => [
                'nullable',
                'date',
                'after_or_equal:today',
            ],

            'notes' => [
                'nullable',
                'string',
            ],
               'prospect_id' => [
                'nullable',
                'integer',
                'exists:leasing_prospects,id',
            ],
            'subject' => [
                'nullable',
                'string',
                'max:255',
            ],
            'description' => [
                'nullable',
                'string',
            ],
        ]);

        DB::beginTransaction();

        try {
            $pipeline->prospect_id = $validated['prospect_id'] ?? null;
           
            $pipeline->subject = $validated['subject'] ?? 'Lead created';
            $pipeline->description = $validated['description'] ?? 'New leasing lead/prospect created.';

            $pipeline->prospect_name = $validated['prospect_name'];
            $pipeline->company_name = $validated['company_name'] ?? null;
            $pipeline->unit_id = $validated['unit_id'] ?? null;
            $pipeline->area = $validated['area'] ?? null;
            $pipeline->expected_rent = $validated['expected_rent'] ?? null;
            $pipeline->rent_period = $validated['rent_period'] ?? null;
            $pipeline->stage = $validated['stage'];
            $pipeline->probability = $validated['probability'];
            $pipeline->next_action = $validated['next_action'] ?? null;
            $pipeline->owner_id = $validated['owner_id'] ?? null;
            $pipeline->forecast_opening = $validated['forecast_opening'] ?? null;
            $pipeline->expected_opening_date = $validated['expected_opening_date'] ?? null;
            $pipeline->notes = $validated['notes'] ?? null;

            // Audit user
            $pipeline->updated_by = Auth::id();

            $pipeline->save();

            DB::commit();

            return redirect()
                ->route('admin.leasing.pipeline.show', $pipeline)
                ->with('success', 'Leasing pipeline opportunity updated successfully.');

        } catch (\Throwable $e) {

            DB::rollBack();

            report($e);

            return back()
                ->withInput()
                ->with('error', 'Unable to update leasing pipeline opportunity. Please try again.');
        }
    }
    /**
 * Delete a leasing pipeline opportunity.
 */
public function destroy(LeasingPipeline $pipeline)
{
    try {

        // Store ID/name for optional logging
        $pipelineId = $pipeline->id;
        $prospectName = $pipeline->prospect_name;

        // Audit user
        $pipeline->updated_by = Auth::id();
        $pipeline->save();

        // Soft delete
        $pipeline->delete();

        return redirect()
            ->route('admin.leasing.pipeline.index')
            ->with(
                'success',
                "Leasing pipeline opportunity #{$pipelineId} ({$prospectName}) deleted successfully."
            );

    } catch (\Throwable $e) {

        report($e);

        return back()
            ->with(
                'error',
                'Unable to delete the leasing pipeline opportunity. Please try again.'
            );
    }
}
}