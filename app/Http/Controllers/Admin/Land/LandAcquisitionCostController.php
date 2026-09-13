<?php

namespace App\Http\Controllers\Admin\Land;

use App\Http\Controllers\Controller;
use App\Models\Land;
use App\Models\LandAcquisitionCost;
use Illuminate\Http\Request;

class LandAcquisitionCostController extends Controller
{
    /**
     * Display acquisition costs.
     */
    public function index(Land $land)
    {
        $acquisitionCosts = $land->acquisitionCosts()
            ->latest('cost_date')
            ->latest('id')
            ->paginate(15);

        $totalAmount = $land->acquisitionCosts()
            ->sum('total_amount');

        $paidAmount = $land->acquisitionCosts()
            ->where('payment_status', 'Paid')
            ->sum('total_amount');

        $pendingAmount = $land->acquisitionCosts()
            ->whereIn(
                'payment_status',
                [
                    'Pending',
                    'Partially Paid'
                ]
            )
            ->sum('total_amount');


        return view(
            'land-acquisition.acquisition-costs.index',
            compact(
                'land',
                'acquisitionCosts',
                'totalAmount',
                'paidAmount',
                'pendingAmount'
            )
        );
    }


    /**
     * Show create form.
     */
    public function create(Land $land)
    {
        return view(
            'land-acquisition.acquisition-costs.create',
            compact('land')
        );
    }


    /**
     * Store acquisition cost.
     */
    public function store(
        Request $request,
        Land $land
    ) {

        $validated = $request->validate([

            'cost_category' => [
                'required',
                'string',
                'max:100'
            ],

            'cost_description' => [
                'nullable',
                'string',
                'max:255'
            ],

            'amount' => [
                'required',
                'numeric',
                'min:0'
            ],

            'tax_amount' => [
                'nullable',
                'numeric',
                'min:0'
            ],

            'currency' => [
                'required',
                'string',
                'max:10'
            ],

            'cost_date' => [
                'nullable',
                'date'
            ],

            'payment_status' => [
                'required',
                'in:Pending,Partially Paid,Paid'
            ],

            'paid_date' => [
                'nullable',
                'date'
            ],

            'reference_number' => [
                'nullable',
                'string',
                'max:150'
            ],

            'remarks' => [
                'nullable',
                'string'
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Calculate Total
        |--------------------------------------------------------------------------
        */

        $amount = (float) $validated['amount'];

        $taxAmount = (float) (
            $validated['tax_amount'] ?? 0
        );

        $validated['total_amount'] =
            $amount + $taxAmount;


        /*
        |--------------------------------------------------------------------------
        | Relationship
        |--------------------------------------------------------------------------
        */

        $validated['land_id'] = $land->id;

        $validated['created_by'] = auth()->id();


        /*
        |--------------------------------------------------------------------------
        | Create
        |--------------------------------------------------------------------------
        */

        LandAcquisitionCost::create(
            $validated
        );


        return redirect()
            ->route(
                'admin.land.lands.show',
                $land
            )
            ->with(
                'success',
                'Acquisition cost added successfully.'
            );
    }


    /**
     * Display acquisition cost.
     */
    public function show(
        Land $land,
        LandAcquisitionCost $acquisitionCost
    ) {

        $this->validateCostBelongsToLand(
            $land,
            $acquisitionCost
        );


        return view(
            'land-acquisition.acquisition-costs.show',
            compact(
                'land',
                'acquisitionCost'
            )
        );
    }


    /**
     * Show edit form.
     */
    public function edit(
        Land $land,
        LandAcquisitionCost $acquisitionCost
    ) {

        $this->validateCostBelongsToLand(
            $land,
            $acquisitionCost
        );


        return view(
            'land-acquisition.acquisition-costs.edit',
            compact(
                'land',
                'acquisitionCost'
            )
        );
    }


    /**
     * Update acquisition cost.
     */
    public function update(
        Request $request,
        Land $land,
        LandAcquisitionCost $acquisitionCost
    ) {

        $this->validateCostBelongsToLand(
            $land,
            $acquisitionCost
        );


        $validated = $request->validate([

            'cost_category' => [
                'required',
                'string',
                'max:100'
            ],

            'cost_description' => [
                'nullable',
                'string',
                'max:255'
            ],

            'amount' => [
                'required',
                'numeric',
                'min:0'
            ],

            'tax_amount' => [
                'nullable',
                'numeric',
                'min:0'
            ],

            'currency' => [
                'required',
                'string',
                'max:10'
            ],

            'cost_date' => [
                'nullable',
                'date'
            ],

            'payment_status' => [
                'required',
                'in:Pending,Partially Paid,Paid'
            ],

            'paid_date' => [
                'nullable',
                'date'
            ],

            'reference_number' => [
                'nullable',
                'string',
                'max:150'
            ],

            'remarks' => [
                'nullable',
                'string'
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Calculate Total
        |--------------------------------------------------------------------------
        */

        $amount = (float) $validated['amount'];

        $taxAmount = (float) (
            $validated['tax_amount'] ?? 0
        );

        $validated['total_amount'] =
            $amount + $taxAmount;


        $validated['updated_by'] = auth()->id();


        $acquisitionCost->update(
            $validated
        );


        return redirect()
            ->route(
                'admin.land.lands.show',
                $land
            )
            ->with(
                'success',
                'Acquisition cost updated successfully.'
            );
    }


    /**
     * Delete acquisition cost.
     */
    public function destroy(
        Land $land,
        LandAcquisitionCost $acquisitionCost
    ) {

        $this->validateCostBelongsToLand(
            $land,
            $acquisitionCost
        );


        $acquisitionCost->delete();


        return redirect()
            ->route(
                'admin.land.lands.show',
                $land
            )
            ->with(
                'success',
                'Acquisition cost deleted successfully.'
            );
    }


    /**
     * Validate relationship.
     */
    private function validateCostBelongsToLand(
        Land $land,
        LandAcquisitionCost $acquisitionCost
    ): void {

        abort_unless(
            $acquisitionCost->land_id === $land->id,
            404
        );
    }
}
