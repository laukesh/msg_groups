<?php

namespace App\Http\Controllers\Admin\Leasing;

use App\Http\Controllers\Controller;
use App\Models\SiteVisit;
use App\Models\LeasingProspect;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Http\Request;

class SiteVisitController extends Controller
{
    /**
     * Display site visits.
     */
    public function index(Request $request)
    {
        $query = SiteVisit::with([
            'prospect',
            'unit',
            'assignedOfficer',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->whereHas('prospect', function ($prospect) use ($search) {

                    $prospect->where('company_name', 'like', "%{$search}%")
                        ->orWhere('contact_name', 'like', "%{$search}%")
                        ->orWhere('brand_name', 'like', "%{$search}%");

                });

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
        | Interest
        |--------------------------------------------------------------------------
        */

        if ($request->filled('interest')) {

            $query->where(
                'interest',
                $request->interest
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Date
        |--------------------------------------------------------------------------
        */

        if ($request->filled('date')) {

            $query->whereDate(
                'visit_date',
                $request->date
            );
        }

        $visits = $query
            ->orderByDesc('visit_date')
            ->paginate(20)
            ->withQueryString();

        return view(
            'admin.leasing.site-visits.index',
            compact('visits')
        );
    }


    /**
     * Show create form.
     */
   public function create(Request $request)
{
    $prospects = LeasingProspect::query()
        ->orderBy('company_name')
        ->get();

    $units = Unit::query()
        ->orderBy('unit_no')
        ->get();

    $officers = User::query()
        ->orderBy('name')
        ->get();

    $selectedProspect = $request->prospect_id;
//dd($prospects, $units, $officers, $selectedProspect);
    return view(
        'admin.leasing.site-visits.create',
        compact(
            'prospects',
            'units',
            'officers',
            'selectedProspect'
        )
    );
}
    /**
     * Store site visit.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([

            'prospect_id' => [
                'required',
                'integer',
                'exists:leasing_prospects,id',
            ],

            'unit_id' => [
                'required',
                'integer',
                'exists:units,id',
            ],

            'visit_date' => [
                'required',
                'date',
            ],

            'attendees' => [
                'nullable',
                'string',
            ],

            'feedback' => [
                'nullable',
                'string',
            ],

            'interest' => [
                'nullable',
                'string',
                'max:50',
            ],

            'follow_up_date' => [
                'nullable',
                'date',
            ],

            'assigned_to' => [
                'nullable',
                'integer',
                'exists:users,id',
            ],

            'status' => [
                'required',
                'string',
                'max:50',
            ],

            'outcome' => [
                'nullable',
                'string',
                'max:255',
            ],

            'notes' => [
                'nullable',
                'string',
            ],
        ]);

        $validated['created_by'] = auth()->id();

        $visit = SiteVisit::create($validated);

        return redirect()
            ->route(
                'admin.leasing.site-visits.show',
                $visit
            )
            ->with(
                'success',
                'Site visit scheduled successfully.'
            );
    }


    /**
     * Display site visit.
     */
    public function show(SiteVisit $siteVisit)
    {
        $siteVisit->load([
            'prospect',
            'unit',
            'assignedOfficer',
            'createdBy',
            'updatedBy',
        ]);

        return view(
            'admin.leasing.site-visits.show',
            compact('siteVisit')
        );
    }


    /**
     * Show edit form.
     */
    public function edit(SiteVisit $siteVisit)
    {
        $prospects = LeasingProspect::query()
            ->orderBy('company_name')
            ->get();

        $units = Unit::query()
            ->orderBy('unit_no')
            ->get();

        $officers = User::query()
            ->orderBy('name')
            ->get();

        return view(
            'admin.leasing.site-visits.edit',
            compact(
                'siteVisit',
                'prospects',
                'units',
                'officers'
            )
        );
    }


    /**
     * Update site visit.
     */
    public function update(
        Request $request,
        SiteVisit $siteVisit
    ) {
        $validated = $request->validate([

            'prospect_id' => [
                'required',
                'integer',
                'exists:leasing_prospects,id',
            ],

            'unit_id' => [
                'required',
                'integer',
                'exists:units,id',
            ],

            'visit_date' => [
                'required',
                'date',
            ],

            'attendees' => [
                'nullable',
                'string',
            ],

            'feedback' => [
                'nullable',
                'string',
            ],

            'interest' => [
                'nullable',
                'string',
                'max:50',
            ],

            'follow_up_date' => [
                'nullable',
                'date',
            ],

            'assigned_to' => [
                'nullable',
                'integer',
                'exists:users,id',
            ],

            'status' => [
                'required',
                'string',
                'max:50',
            ],

            'outcome' => [
                'nullable',
                'string',
                'max:255',
            ],

            'notes' => [
                'nullable',
                'string',
            ],
        ]);

        $validated['updated_by'] = auth()->id();

        $siteVisit->update($validated);

        return redirect()
            ->route(
                'admin.leasing.site-visits.show',
                $siteVisit
            )
            ->with(
                'success',
                'Site visit updated successfully.'
            );
    }


    /**
     * Delete site visit.
     */
    public function destroy(SiteVisit $siteVisit)
    {
        $siteVisit->delete();

        return redirect()
            ->route(
                'admin.leasing.site-visits.index'
            )
            ->with(
                'success',
                'Site visit deleted successfully.'
            );
    }


    /**
     * Record visit outcome.
     */
    public function outcome(
        Request $request,
        SiteVisit $siteVisit
    ) {
        $validated = $request->validate([

            'status' => [
                'required',
                'string',
                'max:50',
            ],

            'interest' => [
                'nullable',
                'string',
                'max:50',
            ],

            'feedback' => [
                'nullable',
                'string',
            ],

            'outcome' => [
                'nullable',
                'string',
                'max:255',
            ],

            'follow_up_date' => [
                'nullable',
                'date',
            ],
        ]);

        $siteVisit->update([
            ...$validated,
            'updated_by' => auth()->id(),
        ]);

        return back()->with(
            'success',
            'Site visit outcome recorded successfully.'
        );
    }
}