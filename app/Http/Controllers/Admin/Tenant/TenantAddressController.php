<?php

namespace App\Http\Controllers\Admin\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\TenantAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TenantAddressController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Address Index
    |--------------------------------------------------------------------------
    */

    public function index($tenantId)
    {
        $tenant = Tenant::findOrFail($tenantId);

        $addresses = TenantAddress::where(
            'tenant_id',
            $tenant->id
        )
            ->latest('id')
            ->get();

        return view(
            'admin.tenants.addresses.index',
            compact(
                'tenant',
                'addresses'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Store Address
    |--------------------------------------------------------------------------
    */

    public function store(
        Request $request,
        $tenantId
    ) {
        $tenant = Tenant::findOrFail($tenantId);

        $validated = $request->validate([

            'address_type' => [
                'required',
                'in:Registered,Corporate,Billing,Communication',
            ],

            'address_line1' => [
                'required',
                'string',
                'max:255',
            ],

            'address_line2' => [
                'nullable',
                'string',
                'max:255',
            ],

            'city' => [
                'nullable',
                'string',
                'max:80',
            ],

            'state' => [
                'nullable',
                'string',
                'max:80',
            ],

            'country' => [
                'nullable',
                'string',
                'max:80',
            ],

            'pincode' => [
                'nullable',
                'string',
                'max:10',
            ],

            'is_default' => [
                'nullable',
                'boolean',
            ],
        ]);


        DB::transaction(function () use (
            $tenant,
            $validated
        ) {

            /*
            |--------------------------------------------------------------------------
            | Make this address default
            |--------------------------------------------------------------------------
            */

            if (
                !empty(
                    $validated['is_default']
                )
            ) {

                TenantAddress::where(
                    'tenant_id',
                    $tenant->id
                )->update([
                    'is_default' => 0,
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Create Address
            |--------------------------------------------------------------------------
            */

            TenantAddress::create([

                'tenant_id' =>
                    $tenant->id,

                'address_type' =>
                    $validated['address_type'],

                'address_line1' =>
                    $validated['address_line1'],

                'address_line2' =>
                    $validated['address_line2'] ?? null,

                'city' =>
                    $validated['city'] ?? null,

                'state' =>
                    $validated['state'] ?? null,

                'country' =>
                    $validated['country'] ?? null,

                'pincode' =>
                    $validated['pincode'] ?? null,

                'is_default' =>
                    $validated['is_default'] ?? false,

                'created_by' =>
                    auth()->id(),

                'updated_by' =>
                    auth()->id(),
            ]);
        });


        return redirect()
            ->route(
                'admin.tenants.addresses.index',
                $tenant->id
            )
            ->with(
                'success',
                'Tenant address added successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Edit Address
    |--------------------------------------------------------------------------
    */

    public function edit(
        $tenantId,
        $addressId
    ) {
        $tenant = Tenant::findOrFail($tenantId);

        $address = TenantAddress::where(
            'tenant_id',
            $tenant->id
        )->findOrFail($addressId);

        return view(
            'admin.tenants.addresses.edit',
            compact(
                'tenant',
                'address'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Update Address
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        $tenantId,
        $addressId
    ) {
        $tenant = Tenant::findOrFail($tenantId);

        $address = TenantAddress::where(
            'tenant_id',
            $tenant->id
        )->findOrFail($addressId);

        $validated = $request->validate([

            'address_type' => [
                'required',
                'in:Registered,Corporate,Billing,Communication',
            ],

            'address_line1' => [
                'required',
                'string',
                'max:255',
            ],

            'address_line2' => [
                'nullable',
                'string',
                'max:255',
            ],

            'city' => [
                'nullable',
                'string',
                'max:80',
            ],

            'state' => [
                'nullable',
                'string',
                'max:80',
            ],

            'country' => [
                'nullable',
                'string',
                'max:80',
            ],

            'pincode' => [
                'nullable',
                'string',
                'max:10',
            ],

            'is_default' => [
                'nullable',
                'boolean',
            ],
        ]);


        DB::transaction(function () use (
            $tenant,
            $address,
            $validated
        ) {

            /*
            |--------------------------------------------------------------------------
            | Remove previous default
            |--------------------------------------------------------------------------
            */

            if (
                !empty(
                    $validated['is_default']
                )
            ) {

                TenantAddress::where(
                    'tenant_id',
                    $tenant->id
                )
                ->where(
                    'id',
                    '!=',
                    $address->id
                )
                ->update([
                    'is_default' => 0,
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Update Address
            |--------------------------------------------------------------------------
            */

            $address->update([

                'address_type' =>
                    $validated['address_type'],

                'address_line1' =>
                    $validated['address_line1'],

                'address_line2' =>
                    $validated['address_line2'] ?? null,

                'city' =>
                    $validated['city'] ?? null,

                'state' =>
                    $validated['state'] ?? null,

                'country' =>
                    $validated['country'] ?? null,

                'pincode' =>
                    $validated['pincode'] ?? null,

                'is_default' =>
                    $validated['is_default'] ?? false,

                'updated_by' =>
                    auth()->id(),
            ]);
        });


        return redirect()
            ->route(
                'admin.tenants.addresses.index',
                $tenant->id
            )
            ->with(
                'success',
                'Tenant address updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Delete Address
    |--------------------------------------------------------------------------
    */

    public function destroy(
        $tenantId,
        $addressId
    ) {
        $tenant = Tenant::findOrFail($tenantId);

        $address = TenantAddress::where(
            'tenant_id',
            $tenant->id
        )->findOrFail($addressId);

        $address->delete();

        return redirect()
            ->route(
                'admin.tenants.addresses.index',
                $tenant->id
            )
            ->with(
                'success',
                'Tenant address deleted successfully.'
            );
    }
}