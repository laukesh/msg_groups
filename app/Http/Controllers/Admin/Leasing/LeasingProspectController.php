<?php

namespace App\Http\Controllers\Admin\Leasing;

use App\Http\Controllers\Controller;
use App\Models\LeasingProspect;
use App\Models\User;
use Illuminate\Http\Request;

class LeasingProspectController extends Controller
{
    /**
     * Display leads & prospects.
     */
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Base Query
        |--------------------------------------------------------------------------
        */
        $query = LeasingProspect::query()
            ->with([
                'assignedUser',
            ]);

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */
        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->where('company_name', 'like', "%{$search}%")
                    ->orWhere('contact_name', 'like', "%{$search}%")
                    ->orWhere('brand_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Status Filter
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
        | Owner Filter
        |
        | Blade uses owner_id.
        | Existing database uses assigned_to.
        |--------------------------------------------------------------------------
        */
        if ($request->filled('owner_id')) {

            $query->where(
                'assigned_to',
                $request->owner_id
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Priority Filter
        |
        | Only apply if your leasing_prospects table has a priority column.
        |--------------------------------------------------------------------------
        */
        if (
            $request->filled('priority') &&
            $this->prospectHasColumn('priority')
        ) {

            $query->where(
                'priority',
                $request->priority
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Activities Count
        |
        | This will work if LeasingProspect has an activities()
        | relationship.
        |--------------------------------------------------------------------------
        */
        if (
            method_exists(LeasingProspect::class, 'activities')
        ) {
            $query->withCount('activities');
        }

        /*
        |--------------------------------------------------------------------------
        | Prospect Listing
        |--------------------------------------------------------------------------
        */
        $prospects = $query
            ->latest('id')
            ->paginate(20)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | KPI - Total
        |--------------------------------------------------------------------------
        */
        $total = LeasingProspect::count();

        /*
        |--------------------------------------------------------------------------
        | KPI - Active
        |
        | Active = everything except converted/lost/inactive.
        |--------------------------------------------------------------------------
        */
        $activeCount = LeasingProspect::query()
            ->whereNotIn('status', [
                'converted',
                'lost',
                'inactive',
            ])
            ->count();

        /*
        |--------------------------------------------------------------------------
        | KPI - Leads
        |--------------------------------------------------------------------------
        */
        $leadCount = LeasingProspect::query()
            ->where('status', 'lead')
            ->count();

        /*
        |--------------------------------------------------------------------------
        | KPI - Prospects
        |--------------------------------------------------------------------------
        */
        $prospectCount = LeasingProspect::query()
            ->whereIn('status', [
                'prospect',
                'qualified',
                'site_visit',
                'negotiation',
            ])
            ->count();

        /*
        |--------------------------------------------------------------------------
        | KPI - Converted
        |--------------------------------------------------------------------------
        */
        $convertedCount = LeasingProspect::query()
            ->where('status', 'converted')
            ->count();

        /*
        |--------------------------------------------------------------------------
        | KPI - Follow-ups Due
        |--------------------------------------------------------------------------
        |
        | Existing DB field:
        | next_follow_up_at
        |--------------------------------------------------------------------------
        */
        $followUpsDue = 0;

        if ($this->prospectHasColumn('next_follow_up_at')) {

            $followUpsDue = LeasingProspect::query()
                ->whereNotNull('next_follow_up_at')
                ->where('next_follow_up_at', '<=', now())
                ->whereNotIn('status', [
                    'converted',
                    'lost',
                    'inactive',
                ])
                ->count();
        }

        /*
        |--------------------------------------------------------------------------
        | Owners
        |--------------------------------------------------------------------------
        */
        $owners = User::query()
            ->orderBy('name')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Categories
        |--------------------------------------------------------------------------
        */
        $categories = LeasingProspect::query()
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        /*
        |--------------------------------------------------------------------------
        | Sources
        |--------------------------------------------------------------------------
        */
        $sources = LeasingProspect::query()
            ->whereNotNull('source')
            ->where('source', '!=', '')
            ->distinct()
            ->orderBy('source')
            ->pluck('source');

        /*
        |--------------------------------------------------------------------------
        | Return View
        |--------------------------------------------------------------------------
        */
        return view(
            'admin.leasing.prospects.index',
            compact(
                'prospects',
                'owners',
                'categories',
                'sources',

                // KPI
                'total',
                'activeCount',
                'leadCount',
                'prospectCount',
                'convertedCount',
                'followUpsDue'
            )
        );
    }


    /**
     * Show create form.
     */
    public function create()
    {
        $owners = User::query()
            ->orderBy('name')
            ->get();

        return view(
            'admin.leasing.prospects.create',
            compact('owners')
        );
    }


    /**
     * Store new prospect.
     */
    public function store(Request $request)
    {
        $validated = $this->validateProspect($request);

        /*
        |--------------------------------------------------------------------------
        | Created By
        |--------------------------------------------------------------------------
        */
        $validated['created_by'] = auth()->id();

        /*
        |--------------------------------------------------------------------------
        | Create
        |--------------------------------------------------------------------------
        */
        $prospect = LeasingProspect::create($validated);

        return redirect()
            ->route(
                'admin.leasing.prospects.show',
                $prospect
            )
            ->with(
                'success',
                'Lead / prospect created successfully.'
            );
    }


    /**
     * Display prospect.
     */
    public function show(LeasingProspect $prospect)
    {
        /*
        |--------------------------------------------------------------------------
        | Load Relationships
        |--------------------------------------------------------------------------
        */
        $prospect->load([
            'assignedUser',
            'siteVisits' => function ($query) {
                $query->latest('visit_date');
            },
            'siteVisits.unit',
            'siteVisits.assignedOfficer',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Activities
        |--------------------------------------------------------------------------
        */
        if (
            method_exists($prospect, 'activities')
        ) {
            $prospect->load([
                'activities' => function ($query) {
                    $query->latest();
                },
            ]);
        }

        return view(
            'admin.leasing.prospects.show',
            compact('prospect')
        );
    }


    /**
     * Show edit form.
     */
    public function edit1(LeasingProspect $prospect)
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
public function edit(LeasingProspect $prospect)
{
    $owners = User::query()
        ->orderBy('name')
        ->get();

    return view(
        'admin.leasing.prospects.edit',
        compact('prospect', 'owners')
    );
}

    /**
     * Update prospect.
     */
    public function update(
        Request $request,
        LeasingProspect $prospect
    ) {
        $validated = $this->validateProspect(
            $request,
            $prospect
        );

        /*
        |--------------------------------------------------------------------------
        | Updated By
        |--------------------------------------------------------------------------
        */
        $validated['updated_by'] = auth()->id();

        /*
        |--------------------------------------------------------------------------
        | Update
        |--------------------------------------------------------------------------
        */
        //dd($validated);
        $prospect->update($validated);

        return redirect()
            ->route(
                'admin.leasing.prospects.show',
                $prospect
            )
            ->with(
                'success',
                'Lead / prospect updated successfully.'
            );
    }


    /**
     * Delete prospect.
     */
    public function destroy(LeasingProspect $prospect)
    {
        $prospect->delete();

        return redirect()
            ->route(
                'admin.leasing.prospects.index'
            )
            ->with(
                'success',
                'Lead / prospect deleted successfully.'
            );
    }


    /**
     * Update status.
     */
    public function updateStatus(
        Request $request,
        LeasingProspect $prospect
    ) {
        $validated = $request->validate([
            'status' => [
                'required',
                'string',
                'max:50',
            ],
        ]);

        $prospect->update([
            'status' => $validated['status'],
            'updated_by' => auth()->id(),
        ]);

        return back()->with(
            'success',
            'Prospect status updated successfully.'
        );
    }


    /**
     * Assign prospect to owner.
     */
    public function assign(
        Request $request,
        LeasingProspect $prospect
    ) {
        $validated = $request->validate([
            'assigned_to' => [
                'required',
                'integer',
                'exists:users,id',
            ],
        ]);

        $prospect->update([
            'assigned_to' => $validated['assigned_to'],
            'updated_by' => auth()->id(),
        ]);

        return back()->with(
            'success',
            'Prospect assigned successfully.'
        );
    }


    /**
     * Validate prospect.
     */
    private function validateProspect(
        Request $request,
        ?LeasingProspect $prospect = null
    ): array {

        $rules = [

            /*
            |--------------------------------------------------------------------------
            | Company
            |--------------------------------------------------------------------------
            */
            'company_name' => [
                'required',
                'string',
                'max:255',
            ],

            /*
            |--------------------------------------------------------------------------
            | Contact
            |--------------------------------------------------------------------------
            */
            'contact_name' => [
                'nullable',
                'string',
                'max:255',
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

            /*
            |--------------------------------------------------------------------------
            | Brand
            |--------------------------------------------------------------------------
            */
            'brand_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            /*
            |--------------------------------------------------------------------------
            | Category
            |--------------------------------------------------------------------------
            */
            'category' => [
                'nullable',
                'string',
                'max:255',
            ],

            /*
            |--------------------------------------------------------------------------
            | Area Requirement
            |--------------------------------------------------------------------------
            */
            'area_need' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            /*
            |--------------------------------------------------------------------------
            | Budget
            |--------------------------------------------------------------------------
            */
            'budget' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            /*
            |--------------------------------------------------------------------------
            | Preferred Location
            |--------------------------------------------------------------------------
            */
            'preferred_location' => [
                'nullable',
                'string',
                'max:255',
            ],

            /*
            |--------------------------------------------------------------------------
            | Source
            |--------------------------------------------------------------------------
            */
            'source' => [
                'nullable',
                'string',
                'max:100',
            ],

            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */
            'status' => [
                'required',
                'string',
                'max:50',
            ],

            /*
            |--------------------------------------------------------------------------
            | Notes
            |--------------------------------------------------------------------------
            */
            'notes' => [
                'nullable',
                'string',
            ],

            /*
            |--------------------------------------------------------------------------
            | Assigned Owner
            |--------------------------------------------------------------------------
            */
            'assigned_to' => [
                'nullable',
                'integer',
                'exists:users,id',
            ],

            /*
            |--------------------------------------------------------------------------
            | Next Follow-up
            |--------------------------------------------------------------------------
            */
            'next_follow_up_at' => [
                'nullable',
                'date',
            ],
        ];

        /*
        |--------------------------------------------------------------------------
        | Optional Priority
        |--------------------------------------------------------------------------
        */
        if ($this->prospectHasColumn('priority')) {

            $rules['priority'] = [
                'nullable',
                'string',
                'max:50',
            ];
        }

        return $request->validate($rules);
    }


    /**
     * Check whether a column exists.
     *
     * This prevents the controller from breaking when optional
     * fields such as priority are not yet present in the database.
     */
    private function prospectHasColumn(string $column): bool
    {
        static $columns = null;

        if ($columns === null) {

            $columns = \Illuminate\Support\Facades\Schema::getColumnListing(
                (new LeasingProspect)->getTable()
            );
        }

        return in_array(
            $column,
            $columns,
            true
        );
    }
}