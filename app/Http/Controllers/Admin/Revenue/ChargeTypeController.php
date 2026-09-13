<?php

namespace App\Http\Controllers\Admin\Revenue;

use App\Http\Controllers\Controller;
use App\Models\ChargeType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ChargeTypeController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $query = ChargeType::query()
            ->orderBy('id', 'asc');


        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where(
                    'charge_name',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'charge_code',
                    'like',
                    "%{$search}%"
                );

            });
        }


        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $chargeTypes = $query
            ->paginate(20)
            ->withQueryString();


        return view(
            'admin.revenue.settings.charge-types.index',
            compact('chargeTypes')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        return view(
            'admin.revenue.settings.charge-types.create'
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

            'charge_name' => [
                'required',
                'string',
                'max:100',
            ],

            'charge_code' => [
                'required',
                'string',
                'max:30',
                'alpha_dash',
                'unique:charge_types,charge_code',
            ],

        ]);


        ChargeType::create([

            'charge_name' => $validated['charge_name'],

            'charge_code' => strtoupper(
                $validated['charge_code']
            ),

        ]);


        return redirect()
            ->route('admin.revenue.settings.charge-types.index')
            ->with(
                'success',
                'Charge type created successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit($id)
    {
        $chargeType = ChargeType::findOrFail($id);

        return view(
            'admin.revenue.settings.charge-types.edit',
            compact('chargeType')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        $id
    ) {

        $chargeType = ChargeType::findOrFail($id);


        $validated = $request->validate([

            'charge_name' => [
                'required',
                'string',
                'max:100',
            ],

            'charge_code' => [
                'required',
                'string',
                'max:30',
                'alpha_dash',

                Rule::unique(
                    'charge_types',
                    'charge_code'
                )->ignore($chargeType->id),

            ],

        ]);


        $chargeType->update([

            'charge_name' =>
                $validated['charge_name'],

            'charge_code' =>
                strtoupper(
                    $validated['charge_code']
                ),

        ]);


        return redirect()
            ->route('admin.revenue.settings.charge-types.index')
            ->with(
                'success',
                'Charge type updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {
        $chargeType = ChargeType::findOrFail($id);


        /*
        |--------------------------------------------------------------------------
        | Prevent Delete If Used
        |--------------------------------------------------------------------------
        */

        $used = \DB::table('invoice_items')
            ->where(
                'charge_type_id',
                $chargeType->id
            )
            ->exists();


        if ($used) {

            return back()->with(
                'error',
                'This charge type cannot be deleted because it is already used in invoice items.'
            );
        }


        $chargeType->delete();


        return redirect()
            ->route('admin.revenue.settings.charge-types.index')
            ->with(
                'success',
                'Charge type deleted successfully.'
            );
    }
}