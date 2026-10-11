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

class LeasingController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Index
    |--------------------------------------------------------------------------
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

    /*
    |--------------------------------------------------------------------------
    | Create
    |--------------------------------------------------------------------------
    */

   public function create()
{
    $units = Unit::query()
        ->orderBy('name')
        ->get();

    $owners = User::query()
        ->orderBy('name')
        ->get();

    $prospects = LeasingProspect::query()
        ->active()
        ->orderBy('company_name')
        ->orderBy('contact_name')
        ->get();

    return view(
        'admin.leasing.pipeline.create',
        compact(
            'units',
            'owners',
            'prospects'
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
        $validated = $this->validateProspect($request);

        DB::beginTransaction();

        try {

            $prospect = new LeasingProspect();

            $prospect->fill($validated);

            $prospect->created_by = Auth::id();
            $prospect->updated_by = Auth::id();

            $prospect->save();

            /*
             * Automatically create initial activity.
             */
            LeasingProspectActivity::create([
                'prospect_id' => $prospect->id,
                'activity_type' => 'note',
                'subject' => 'Lead created',
                'description' =>
                    'New leasing lead/prospect created.',
                'activity_date' => now(),
                'created_by' => Auth::id(),
            ]);

            DB::commit();

            return redirect()
                ->route(
                    'admin.leasing.prospects.show',
                    $prospect
                )
                ->with(
                    'success',
                    'Lead / prospect created successfully.'
                );

        } catch (\Throwable $e) {

            DB::rollBack();

            report($e);

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Unable to create lead / prospect.'
                );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Show
    |--------------------------------------------------------------------------
    */

    public function show(LeasingProspect $prospect)
    {
        $prospect->load([
            'owner',
            'creator',
            'updater',
            'activities.creator',
            'pipelines.unit',
            'pipelines.owner',
        ]);

        return view(
            'admin.leasing.prospects.show',
            compact('prospect')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Edit
    |--------------------------------------------------------------------------
    */

    public function edit(LeasingProspect $prospect)
    {
        $owners = User::query()
            ->orderBy('name')
            ->get();

        return view(
            'admin.leasing.prospects.edit',
            compact(
                'prospect',
                'owners'
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
        LeasingProspect $prospect
    ) {
        $validated = $this->validateProspect($request);

        DB::beginTransaction();

        try {

            $oldStatus = $prospect->status;

            $prospect->fill($validated);

            $prospect->updated_by = Auth::id();

            $prospect->save();

            /*
             * Record status change.
             */
            if ($oldStatus !== $prospect->status) {

                LeasingProspectActivity::create([
                    'prospect_id' => $prospect->id,
                    'activity_type' => 'note',
                    'subject' => 'Status changed',
                    'description' =>
                        'Status changed from '
                        . str_replace('_', ' ', $oldStatus)
                        . ' to '
                        . str_replace(
                            '_',
                            ' ',
                            $prospect->status
                        ),
                    'activity_date' => now(),
                    'created_by' => Auth::id(),
                ]);
            }

            DB::commit();

            return redirect()
                ->route(
                    'admin.leasing.prospects.show',
                    $prospect
                )
                ->with(
                    'success',
                    'Lead / prospect updated successfully.'
                );

        } catch (\Throwable $e) {

            DB::rollBack();

            report($e);

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Unable to update lead / prospect.'
                );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Destroy
    |--------------------------------------------------------------------------
    */

    public function destroy(LeasingProspect $prospect)
    {
        DB::beginTransaction();

        try {

            $prospect->updated_by = Auth::id();
            $prospect->save();

            $prospect->delete();

            DB::commit();

            return redirect()
                ->route(
                    'admin.leasing.prospects.index'
                )
                ->with(
                    'success',
                    'Lead / prospect deleted successfully.'
                );

        } catch (\Throwable $e) {

            DB::rollBack();

            report($e);

            return back()
                ->with(
                    'error',
                    'Unable to delete lead / prospect.'
                );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Add Activity
    |--------------------------------------------------------------------------
    */

    public function storeActivity(
        Request $request,
        LeasingProspect $prospect
    ) {
        $validated = $request->validate([

            'activity_type' => [
                'required',
                'in:call,email,meeting,site_visit,follow_up,note,whatsapp,other',
            ],

            'subject' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'activity_date' => [
                'required',
                'date',
            ],

            'next_follow_up' => [
                'nullable',
                'date',
            ],

            'outcome' => [
                'nullable',
                'string',
                'max:500',
            ],
        ]);

        LeasingProspectActivity::create([
            'prospect_id' => $prospect->id,
            'activity_type' =>
                $validated['activity_type'],
            'subject' =>
                $validated['subject'],
            'description' =>
                $validated['description'] ?? null,
            'activity_date' =>
                $validated['activity_date'],
            'next_follow_up' =>
                $validated['next_follow_up'] ?? null,
            'outcome' =>
                $validated['outcome'] ?? null,
            'created_by' => Auth::id(),
        ]);

        /*
         * Keep the prospect follow-up date synchronized.
         */
        if (!empty($validated['next_follow_up'])) {

            $prospect->update([
                'next_follow_up' =>
                    $validated['next_follow_up'],
                'updated_by' => Auth::id(),
            ]);
        }

        return back()->with(
            'success',
            'Activity added successfully.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Convert to Pipeline
    |--------------------------------------------------------------------------
    */

    public function convertToPipeline(
        Request $request,
        LeasingProspect $prospect
    ) {
        $validated = $request->validate([

            'stage' => [
                'required',
                'in:lead,prospect,negotiation,agreement,opening,won,lost',
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

            'forecast_opening' => [
                'nullable',
                'date',
            ],
        ]);

        DB::beginTransaction();

        try {

            $pipeline = new LeasingPipeline();

            $pipeline->prospect_id = $prospect->id;
            $pipeline->prospect_name =
                $prospect->contact_name;

            $pipeline->company_name =
                $prospect->company_name;

            $pipeline->unit_id =
                $validated['unit_id'] ?? null;

            $pipeline->area =
                $validated['area']
                ?? $prospect->area_required;

            $pipeline->expected_rent =
                $validated['expected_rent']
                ?? $prospect->budget_max;

            $pipeline->rent_period =
                $validated['rent_period']
                ?? $prospect->budget_period;

            $pipeline->stage =
                $validated['stage'];

            $pipeline->probability =
                $validated['probability'];

            $pipeline->next_action =
                $validated['next_action'] ?? null;

            $pipeline->owner_id =
                $prospect->owner_id;

            $pipeline->forecast_opening =
                $validated['forecast_opening']
                ?? null;

            $pipeline->notes =
                $prospect->notes;

            $pipeline->created_by =
                Auth::id();

            $pipeline->updated_by =
                Auth::id();

            $pipeline->save();

            $prospect->status = 'converted';
            $prospect->updated_by = Auth::id();
            $prospect->save();

            LeasingProspectActivity::create([
                'prospect_id' => $prospect->id,
                'activity_type' => 'note',
                'subject' => 'Converted to leasing pipeline',
                'description' =>
                    'CRM lead converted to leasing pipeline opportunity #'
                    . $pipeline->id,
                'activity_date' => now(),
                'created_by' => Auth::id(),
            ]);

            DB::commit();

            return redirect()
                ->route(
                    'admin.leasing.pipeline.show',
                    $pipeline
                )
                ->with(
                    'success',
                    'Lead converted to leasing pipeline successfully.'
                );

        } catch (\Throwable $e) {

            DB::rollBack();

            report($e);

            return back()
                ->with(
                    'error',
                    'Unable to convert lead to pipeline.'
                );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Validation
    |--------------------------------------------------------------------------
    */

    private function validateProspect(
        Request $request
    ) {
        return $request->validate([

            'company_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'contact_name' => [
                'required',
                'string',
                'max:255',
            ],

            'designation' => [
                'nullable',
                'string',
                'max:150',
            ],

            'email' => [
                'nullable',
                'email',
                'max:255',
            ],

            'phone' => [
                'nullable',
                'string',
                'max:50',
            ],

            'brand_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'category' => [
                'nullable',
                'string',
                'max:150',
            ],

            'area_required' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'area_unit' => [
                'nullable',
                'string',
                'max:50',
            ],

            'budget_min' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'budget_max' => [
                'nullable',
                'numeric',
                'gte:budget_min',
            ],

            'budget_period' => [
                'nullable',
                'in:monthly,quarterly,yearly',
            ],

            'preferred_location' => [
                'nullable',
                'string',
                'max:255',
            ],

            'source' => [
                'nullable',
                'string',
                'max:100',
            ],

            'status' => [
                'required',
                'in:lead,prospect,qualified,site_visit,negotiation,converted,lost,inactive',
            ],

            'priority' => [
                'required',
                'in:low,medium,high,urgent',
            ],

            'owner_id' => [
                'nullable',
                'integer',
                'exists:users,id',
            ],

            'next_follow_up' => [
                'nullable',
                'date',
            ],

            'notes' => [
                'nullable',
                'string',
            ],
        ]);
    }
}