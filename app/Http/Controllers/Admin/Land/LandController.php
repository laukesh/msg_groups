<?php

namespace App\Http\Controllers\Admin\Land;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Land;
use App\Models\LandOpportunity;
use App\Models\LandAcquisitionStatusHistory;

class LandController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Index
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $query = Land::with('opportunity');

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where('land_code', 'like', "%{$search}%")
                    ->orWhere('land_name', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%")
                    ->orWhere('state', 'like', "%{$search}%");

            });
        }

        if ($request->filled('status')) {

            $query->where(
                'acquisition_status',
                $request->status
            );
        }

        $lands = $query
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        return view(
            'land-acquisition.lands.index',
            compact('lands')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Create
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $opportunities = LandOpportunity::orderBy(
            'opportunity_name'
        )->get();

        return view(
            'land-acquisition.lands.create',
            compact('opportunities')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Generate Land Code
    |--------------------------------------------------------------------------
    */

    private function generateLandCode(): string
    {
        /*
        |--------------------------------------------------------------------------
        | Find Latest Land
        |--------------------------------------------------------------------------
        */

        $latestLand = Land::withTrashed()
            ->latest('id')
            ->first();


        /*
        |--------------------------------------------------------------------------
        | First Land
        |--------------------------------------------------------------------------
        */

        if (!$latestLand) {

            return 'LAND-000001';
        }


        /*
        |--------------------------------------------------------------------------
        | Generate From ID
        |--------------------------------------------------------------------------
        |
        | Since id is AUTO_INCREMENT, this gives us a predictable
        | unique land code.
        |
        */

        $nextId = $latestLand->id + 1;

        $code = 'LAND-' . str_pad(
            $nextId,
            6,
            '0',
            STR_PAD_LEFT
        );


        /*
        |--------------------------------------------------------------------------
        | Safety Check
        |--------------------------------------------------------------------------
        */

        while (
            Land::withTrashed()
                ->where('land_code', $code)
                ->exists()
        ) {

            $nextId++;

            $code = 'LAND-' . str_pad(
                $nextId,
                6,
                '0',
                STR_PAD_LEFT
            );
        }


        return $code;
    }


    /*
    |--------------------------------------------------------------------------
    | Store
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $validated = $request->validate([

            /*
            |--------------------------------------------------------------------------
            | Basic Information
            |--------------------------------------------------------------------------
            */

            'land_name' => [
                'required',
                'string',
                'max:255'
            ],

            'opportunity_id' => [
                'nullable',
                'exists:land_opportunities,id'
            ],

            'acquisition_status' => [
                'required',
                'string',
                'max:50'
            ],

            'land_type' => [
                'nullable',
                'string',
                'max:100'
            ],

            'description' => [
                'nullable',
                'string'
            ],


            /*
            |--------------------------------------------------------------------------
            | Location
            |--------------------------------------------------------------------------
            */

            'address_line1' => [
                'nullable',
                'string',
                'max:255'
            ],

            'address_line2' => [
                'nullable',
                'string',
                'max:255'
            ],

            'locality' => [
                'nullable',
                'string',
                'max:150'
            ],

            'city' => [
                'nullable',
                'string',
                'max:100'
            ],

            'state' => [
                'nullable',
                'string',
                'max:100'
            ],

            'country' => [
                'nullable',
                'string',
                'max:100'
            ],

            'postal_code' => [
                'nullable',
                'string',
                'max:20'
            ],

            'latitude' => [
                'nullable',
                'numeric',
                'between:-90,90'
            ],

            'longitude' => [
                'nullable',
                'numeric',
                'between:-180,180'
            ],


            /*
            |--------------------------------------------------------------------------
            | Area
            |--------------------------------------------------------------------------
            */

            'total_area' => [
                'nullable',
                'numeric',
                'min:0'
            ],

            'area_unit' => [
                'nullable',
                'string',
                'max:20'
            ],


            /*
            |--------------------------------------------------------------------------
            | Acquisition
            |--------------------------------------------------------------------------
            */

            'acquisition_date' => [
                'nullable',
                'date'
            ],


            /*
            |--------------------------------------------------------------------------
            | Remarks
            |--------------------------------------------------------------------------
            */

            'remarks' => [
                'nullable',
                'string'
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Auto Generate Land Code
        |--------------------------------------------------------------------------
        */

        $validated['land_code'] =
            $this->generateLandCode();


        /*
        |--------------------------------------------------------------------------
        | Created By
        |--------------------------------------------------------------------------
        */

        $validated['created_by'] =
            auth()->id();


        /*
        |--------------------------------------------------------------------------
        | Initial Status
        |--------------------------------------------------------------------------
        |
        | New land enters the acquisition pipeline as Opportunity.
        |
        */

        $validated['acquisition_status'] =
            'Opportunity';


        /*
        |--------------------------------------------------------------------------
        | Create Land
        |--------------------------------------------------------------------------
        */

        $land = Land::create(
            $validated
        );


        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'admin.land.lands.show',
                $land
            )
            ->with(
                'success',
                'Land created successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Show
    |--------------------------------------------------------------------------
    */

    public function show(Land $land)
    {
        $land->load([
            'opportunity',
            'owners',
            'plots',
            'zonings',
            'developmentRights',
            'dueDiligences',
            'acquisitionCosts',
        ]);

        return view(
            'land-acquisition.lands.show',
            compact('land')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Edit
    |--------------------------------------------------------------------------
    */

    public function edit(Land $land)
    {
        $opportunities = LandOpportunity::orderBy(
            'opportunity_name'
        )->get();

        return view(
            'land-acquisition.lands.edit',
            compact(
                'land',
                'opportunities'
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
        Land $land
    ) {

        $validated = $request->validate([

            /*
            |--------------------------------------------------------------------------
            | Basic Information
            |--------------------------------------------------------------------------
            */

            'land_name' => [
                'required',
                'string',
                'max:255'
            ],

            'opportunity_id' => [
                'nullable',
                'exists:land_opportunities,id'
            ],

            'acquisition_status' => [
                'required',
                'string',
                'max:50'
            ],

            'land_type' => [
                'nullable',
                'string',
                'max:100'
            ],

            'description' => [
                'nullable',
                'string'
            ],


            /*
            |--------------------------------------------------------------------------
            | Location
            |--------------------------------------------------------------------------
            */

            'address_line1' => [
                'nullable',
                'string',
                'max:255'
            ],

            'address_line2' => [
                'nullable',
                'string',
                'max:255'
            ],

            'locality' => [
                'nullable',
                'string',
                'max:150'
            ],

            'city' => [
                'nullable',
                'string',
                'max:100'
            ],

            'state' => [
                'nullable',
                'string',
                'max:100'
            ],

            'country' => [
                'nullable',
                'string',
                'max:100'
            ],

            'postal_code' => [
                'nullable',
                'string',
                'max:20'
            ],

            'latitude' => [
                'nullable',
                'numeric',
                'between:-90,90'
            ],

            'longitude' => [
                'nullable',
                'numeric',
                'between:-180,180'
            ],


            /*
            |--------------------------------------------------------------------------
            | Area
            |--------------------------------------------------------------------------
            */

            'total_area' => [
                'nullable',
                'numeric',
                'min:0'
            ],

            'area_unit' => [
                'nullable',
                'string',
                'max:20'
            ],


            /*
            |--------------------------------------------------------------------------
            | Acquisition
            |--------------------------------------------------------------------------
            */

            'acquisition_date' => [
                'nullable',
                'date'
            ],


            /*
            |--------------------------------------------------------------------------
            | Remarks
            |--------------------------------------------------------------------------
            */

            'remarks' => [
                'nullable',
                'string'
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Status History
        |--------------------------------------------------------------------------
        */

        $oldStatus =
            $land->acquisition_status;

        $newStatus =
            $validated['acquisition_status'];


        if ($oldStatus !== $newStatus) {

            LandAcquisitionStatusHistory::create([

                'land_id' => $land->id,

                'from_status' => $oldStatus,

                'to_status' => $newStatus,

                'changed_by' => auth()->id(),

                'changed_at' => now(),

            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Updated By
        |--------------------------------------------------------------------------
        */

        $validated['updated_by'] =
            auth()->id();


        /*
        |--------------------------------------------------------------------------
        | Never Change Land Code
        |--------------------------------------------------------------------------
        */

        unset(
            $validated['land_code']
        );


        /*
        |--------------------------------------------------------------------------
        | Update
        |--------------------------------------------------------------------------
        */

        $land->update(
            $validated
        );


        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'admin.land.lands.show',
                $land
            )
            ->with(
                'success',
                'Land updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Destroy
    |--------------------------------------------------------------------------
    */

    public function destroy(Land $land)
    {
        $land->delete();

        return redirect()
            ->route(
                'admin.land.lands.index'
            )
            ->with(
                'success',
                'Land deleted successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Acquisition Review
    |--------------------------------------------------------------------------
    */

    public function acquisitionReview(
        Land $land
    ) {

        $land->load([
            'dueDiligences',
            'acquisitionStatusHistories',
            'documents',
        ]);


        $legalDueDiligence =
            $land->dueDiligences
                ->where('type', 'Legal')
                ->sortByDesc('id')
                ->first();


        $technicalDueDiligence =
            $land->dueDiligences
                ->where('type', 'Technical')
                ->sortByDesc('id')
                ->first();


        $environmentalAssessment =
            $land->dueDiligences
                ->where('type', 'Environmental')
                ->sortByDesc('id')
                ->first();


        $legalCompleted =
            $legalDueDiligence &&
            in_array(
                $legalDueDiligence->status,
                [
                    'Completed',
                    'Approved'
                ],
                true
            );


        $technicalCompleted =
            $technicalDueDiligence &&
            in_array(
                $technicalDueDiligence->status,
                [
                    'Completed',
                    'Approved'
                ],
                true
            );


        $environmentalCompleted =
            $environmentalAssessment &&
            in_array(
                $environmentalAssessment->status,
                [
                    'Completed',
                    'Approved'
                ],
                true
            );


        $acquisitionApproved =
            in_array(
                $land->acquisition_status,
                [
                    'Approved',
                    'Acquisition in Progress',
                    'Acquired'
                ],
                true
            );


        $readyForFeasibility =
            $legalCompleted &&
            $technicalCompleted &&
            $environmentalCompleted &&
            $acquisitionApproved;


        $totalAcquisitionCost =
            $land->acquisitionCosts()
                ->sum('amount');


        return view(
            'land-acquisition.lands.acquisition-review',
            compact(
                'land',
                'legalDueDiligence',
                'technicalDueDiligence',
                'environmentalAssessment',
                'legalCompleted',
                'technicalCompleted',
                'environmentalCompleted',
                'acquisitionApproved',
                'readyForFeasibility',
                'totalAcquisitionCost'
            )
        );
    }
}