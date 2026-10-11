<?php

namespace App\Http\Controllers\Admin\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\TenantContact;
use Illuminate\Http\Request;

class TenantContactController extends Controller
{
	/*
	|--------------------------------------------------------------------------
	| Contact Index
	|--------------------------------------------------------------------------
	*/

	public function index($tenantId)
	{
	    $tenant = Tenant::findOrFail($tenantId);

	    $contacts = TenantContact::where(
	        'tenant_id',
	        $tenant->id
	    )
	        ->latest('id')
	        ->get();

	    return view(
	        'admin.tenants.contacts.index',
	        compact(
	            'tenant',
	            'contacts'
	        )
	    );
	}
    /*
    |--------------------------------------------------------------------------
    | Store Contact
    |--------------------------------------------------------------------------
    */

    public function store(
        Request $request,
        $tenantId
    ) {
        $tenant = Tenant::findOrFail($tenantId);

        $validated = $request->validate([

            'contact_name' => [
                'required',
                'string',
                'max:150',
            ],

            'designation' => [
                'nullable',
                'string',
                'max:100',
            ],

            'mobile' => [
                'nullable',
                'string',
                'max:20',
            ],

            'email' => [
                'nullable',
                'email',
                'max:150',
            ],

            'is_primary' => [
                'nullable',
                'boolean',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Primary Contact
        |--------------------------------------------------------------------------
        */

        if (
            !empty(
                $validated['is_primary']
            )
        ) {

            TenantContact::where(
                'tenant_id',
                $tenant->id
            )->update([
                'is_primary' => 0,
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Create Contact
        |--------------------------------------------------------------------------
        */

        TenantContact::create([

            'tenant_id' =>
                $tenant->id,

            'contact_name' =>
                $validated['contact_name'],

            'designation' =>
                $validated['designation'] ?? null,

            'mobile' =>
                $validated['mobile'] ?? null,

            'email' =>
                $validated['email'] ?? null,

            'is_primary' =>
                $validated['is_primary'] ?? false,

            'remarks' =>
                $validated['remarks'] ?? null,

            'created_by' =>
                auth()->id(),

            'updated_by' =>
                auth()->id(),
        ]);


        return redirect()
            ->route(
                'admin.tenants.contacts.index',
                $tenant->id
            )
            ->with(
                'success',
                'Tenant contact added successfully.'
            );
    }

    /*
	|--------------------------------------------------------------------------
	| Edit Contact
	|--------------------------------------------------------------------------
	*/

	public function edit(
	    $tenantId,
	    $contactId
	) {
	    $tenant = Tenant::findOrFail($tenantId);

	    $contact = TenantContact::where(
	        'tenant_id',
	        $tenant->id
	    )->findOrFail($contactId);

	    return view(
	        'admin.tenants.contacts.edit',
	        compact(
	            'tenant',
	            'contact'
	        )
	    );
	}


    /*
    |--------------------------------------------------------------------------
    | Update Contact
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        $tenantId,
        $contactId
    ) {
        $tenant = Tenant::findOrFail($tenantId);

        $contact = TenantContact::where(
            'tenant_id',
            $tenant->id
        )->findOrFail($contactId);

        $validated = $request->validate([

            'contact_name' => [
                'required',
                'string',
                'max:150',
            ],

            'designation' => [
                'nullable',
                'string',
                'max:100',
            ],

            'mobile' => [
                'nullable',
                'string',
                'max:20',
            ],

            'email' => [
                'nullable',
                'email',
                'max:150',
            ],

            'is_primary' => [
                'nullable',
                'boolean',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],
        ]);


        if (
            !empty(
                $validated['is_primary']
            )
        ) {

            TenantContact::where(
                'tenant_id',
                $tenant->id
            )
            ->where(
                'id',
                '!=',
                $contact->id
            )
            ->update([
                'is_primary' => 0,
            ]);
        }


        $contact->update([

            'contact_name' =>
                $validated['contact_name'],

            'designation' =>
                $validated['designation'] ?? null,

            'mobile' =>
                $validated['mobile'] ?? null,

            'email' =>
                $validated['email'] ?? null,

            'is_primary' =>
                $validated['is_primary'] ?? false,

            'remarks' =>
                $validated['remarks'] ?? null,

            'updated_by' =>
                auth()->id(),
        ]);


        return redirect()
            ->route(
                'admin.tenants.contacts.index',
                $tenant->id
            )
            ->with(
                'success',
                'Tenant contact updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Delete Contact
    |--------------------------------------------------------------------------
    */

    public function destroy(
        $tenantId,
        $contactId
    ) {
        $tenant = Tenant::findOrFail($tenantId);

        $contact = TenantContact::where(
            'tenant_id',
            $tenant->id
        )->findOrFail($contactId);

        $contact->delete();

        return redirect()
            ->route(
                'admin.tenants.contacts.index',
                $tenant->id
            )
            ->with(
                'success',
                'Tenant contact deleted successfully.'
            );
    }
}