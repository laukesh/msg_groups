<?php

namespace App\Http\Controllers\Admin\Revenue;

use App\Http\Controllers\Controller;
use App\Models\TaxConfiguration;
use App\Models\ChargeType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TaxConfigurationController extends Controller
{
    /**
     * Display tax configurations.
     */
    public function index()
    {
        $taxConfigurations = TaxConfiguration::with('chargeType')
            ->orderByDesc('id')
            ->get();

        $chargeTypes = ChargeType::where('status', 1)
            ->orderBy('charge_name')
            ->get();

        return view(
            'admin.revenue.tax_configurations.index',
            compact(
                'taxConfigurations',
                'chargeTypes'
            )
        );
    }


    /**
     * Store tax configuration.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([

            'charge_type_id' => [
                'required',
                'exists:charge_types,id',
            ],

            'tax_name' => [
                'required',
                'string',
                'max:100',
            ],

            'tax_type' => [
                'required',
                'in:GST,CGST,SGST,IGST,VAT,Service Tax',
            ],

            'hsn_sac_code' => [
                'nullable',
                'string',
                'max:20',
            ],

            'tax_percentage' => [
                'required',
                'numeric',
                'min:0',
                'max:100',
            ],

            'effective_from' => [
                'required',
                'date',
            ],

            'effective_to' => [
                'nullable',
                'date',
                'after_or_equal:effective_from',
            ],

            'is_default' => [
                'required',
                'in:Yes,No',
            ],

            'status' => [
                'required',
                'in:Active,Inactive',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Check Charge Type
        |--------------------------------------------------------------------------
        */

        $chargeType = ChargeType::findOrFail(
            $validated['charge_type_id']
        );


        /*
        |--------------------------------------------------------------------------
        | Taxable Check
        |--------------------------------------------------------------------------
        |
        | Non-taxable charge types should not receive
        | a tax configuration.
        |
        */

        if (!$chargeType->taxable) {

            return back()
                ->withInput()
                ->withErrors([
                    'charge_type_id' =>
                        'This charge type is marked as non-taxable.'
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Default Tax Validation
        |--------------------------------------------------------------------------
        */

        if ($validated['is_default'] === 'Yes') {

            $this->removeExistingDefault(
                $validated['charge_type_id']
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Create Tax Configuration
        |--------------------------------------------------------------------------
        */

        TaxConfiguration::create([

            'uuid' =>
                (string) Str::uuid(),

            'charge_type_id' =>
                $validated['charge_type_id'],

            'tax_name' =>
                $validated['tax_name'],

            'tax_type' =>
                $validated['tax_type'],

            'hsn_sac_code' =>
                $validated['hsn_sac_code'] ?? null,

            'tax_percentage' =>
                $validated['tax_percentage'],

            'effective_from' =>
                $validated['effective_from'],

            'effective_to' =>
                $validated['effective_to'] ?? null,

            'is_default' =>
                $validated['is_default'],

            'status' =>
                $validated['status'],

            'remarks' =>
                $validated['remarks'] ?? null,

            'created_by' =>
                Auth::id(),

            'updated_by' =>
                Auth::id(),
        ]);


        return redirect()
            ->route('admin.revenue.tax-configurations.index')
            ->with(
                'success',
                'Tax configuration created successfully.'
            );
    }


    /**
     * Show edit form.
     */
    public function edit($id)
    {
        $taxConfiguration =
            TaxConfiguration::findOrFail($id);

        $chargeTypes = ChargeType::where('status', 1)
            ->orderBy('charge_name')
            ->get();

        return view(
            'admin.revenue.tax_configurations.edit',
            compact(
                'taxConfiguration',
                'chargeTypes'
            )
        );
    }


    /**
     * Update tax configuration.
     */
    public function update(
        Request $request,
        $id
    ) {

        $taxConfiguration =
            TaxConfiguration::findOrFail($id);


        $validated = $request->validate([

            'charge_type_id' => [
                'required',
                'exists:charge_types,id',
            ],

            'tax_name' => [
                'required',
                'string',
                'max:100',
            ],

            'tax_type' => [
                'required',
                'in:GST,CGST,SGST,IGST,VAT,Service Tax',
            ],

            'hsn_sac_code' => [
                'nullable',
                'string',
                'max:20',
            ],

            'tax_percentage' => [
                'required',
                'numeric',
                'min:0',
                'max:100',
            ],

            'effective_from' => [
                'required',
                'date',
            ],

            'effective_to' => [
                'nullable',
                'date',
                'after_or_equal:effective_from',
            ],

            'is_default' => [
                'required',
                'in:Yes,No',
            ],

            'status' => [
                'required',
                'in:Active,Inactive',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],
        ]);


        $chargeType = ChargeType::findOrFail(
            $validated['charge_type_id']
        );


        if (!$chargeType->taxable) {

            return back()
                ->withInput()
                ->withErrors([
                    'charge_type_id' =>
                        'This charge type is marked as non-taxable.'
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Remove Existing Default
        |--------------------------------------------------------------------------
        */

        if ($validated['is_default'] === 'Yes') {

            $this->removeExistingDefault(
                $validated['charge_type_id'],
                $taxConfiguration->id
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Update
        |--------------------------------------------------------------------------
        */

        $taxConfiguration->update([

            'charge_type_id' =>
                $validated['charge_type_id'],

            'tax_name' =>
                $validated['tax_name'],

            'tax_type' =>
                $validated['tax_type'],

            'hsn_sac_code' =>
                $validated['hsn_sac_code'] ?? null,

            'tax_percentage' =>
                $validated['tax_percentage'],

            'effective_from' =>
                $validated['effective_from'],

            'effective_to' =>
                $validated['effective_to'] ?? null,

            'is_default' =>
                $validated['is_default'],

            'status' =>
                $validated['status'],

            'remarks' =>
                $validated['remarks'] ?? null,

            'updated_by' =>
                Auth::id(),
        ]);


        return redirect()
            ->route('admin.revenue.tax-configurations.index')
            ->with(
                'success',
                'Tax configuration updated successfully.'
            );
    }


    /**
     * Delete tax configuration.
     */
    public function destroy($id)
    {
        $taxConfiguration =
            TaxConfiguration::findOrFail($id);

        $taxConfiguration->update([
            'updated_by' => Auth::id(),
        ]);

        $taxConfiguration->delete();

        return redirect()
            ->route('admin.revenue.tax-configurations.index')
            ->with(
                'success',
                'Tax configuration deleted successfully.'
            );
    }


    /**
     * Ensure only one default configuration
     * exists for a charge type.
     */
    private function removeExistingDefault(
        int $chargeTypeId,
        ?int $exceptId = null
    ): void {

        $query = TaxConfiguration::where(
            'charge_type_id',
            $chargeTypeId
        )
        ->where(
            'is_default',
            'Yes'
        );

        if ($exceptId) {

            $query->where(
                'id',
                '!=',
                $exceptId
            );
        }

        $query->update([
            'is_default' => 'No',
            'updated_by' => Auth::id(),
        ]);
    }
}