<?php

namespace App\Http\Controllers\Admin\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\TenantEmergencyContact;
use App\Models\TenantHistory;
use Illuminate\Http\Request;

class TenantEmergencyContactController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Emergency Contact Index
    |--------------------------------------------------------------------------
    */

    public function index($tenantId)
    {
        $tenant = Tenant::findOrFail($tenantId);

        $emergencyContacts = TenantEmergencyContact::where(
            'tenant_id',
            $tenant->id
        )
            ->latest('id')
            ->get();

        return view(
            'admin.tenants.emergency_contacts.index',
            compact(
                'tenant',
                'emergencyContacts'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Store Emergency Contact
    |--------------------------------------------------------------------------
    */

    public function store(
        Request $request,
        $tenantId
    ) {
        $tenant = Tenant::findOrFail($tenantId);

        $validated = $request->validate([

            'person_name' => [
                'required',
                'string',
                'max:150',
            ],

            'relation' => [
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

            'remarks' => [
                'nullable',
                'string',
            ],
        ]);


        $emergencyContact = TenantEmergencyContact::create([

            'tenant_id' =>
                $tenant->id,

            'person_name' =>
                $validated['person_name'],

            'relation' =>
                $validated['relation'] ?? null,

            'mobile' =>
                $validated['mobile'] ?? null,

            'email' =>
                $validated['email'] ?? null,

            'remarks' =>
                $validated['remarks'] ?? null,

            'created_by' =>
                auth()->id(),

            'updated_by' =>
                auth()->id(),
        ]);

        TenantHistory::create([
		    'tenant_id' => $tenant->id,
		    'activity_type' => 'Emergency Contact Added',
		    'reference_module' => 'Emergency Contact',
		    'reference_id' => $emergencyContact->id,
		    'description' => 'Emergency contact was added.',
		    'activity_date' => now(),
		    'performed_by' => auth()->id(),
		]);


        return redirect()
            ->route(
                'admin.tenants.emergency-contacts.index',
                $tenant->id
            )
            ->with(
                'success',
                'Emergency contact added successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Edit Emergency Contact
    |--------------------------------------------------------------------------
    */

    public function edit(
        $tenantId,
        $contactId
    ) {
        $tenant = Tenant::findOrFail($tenantId);

        $emergencyContact =
            TenantEmergencyContact::where(
                'tenant_id',
                $tenant->id
            )
            ->findOrFail($contactId);

        return view(
            'admin.tenants.emergency_contacts.edit',
            compact(
                'tenant',
                'emergencyContact'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Update Emergency Contact
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        $tenantId,
        $contactId
    ) {
        $tenant = Tenant::findOrFail($tenantId);

        $emergencyContact =
            TenantEmergencyContact::where(
                'tenant_id',
                $tenant->id
            )
            ->findOrFail($contactId);

        $validated = $request->validate([

            'person_name' => [
                'required',
                'string',
                'max:150',
            ],

            'relation' => [
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

            'remarks' => [
                'nullable',
                'string',
            ],
        ]);


        $emergencyContact->update([

            'person_name' =>
                $validated['person_name'],

            'relation' =>
                $validated['relation'] ?? null,

            'mobile' =>
                $validated['mobile'] ?? null,

            'email' =>
                $validated['email'] ?? null,

            'remarks' =>
                $validated['remarks'] ?? null,

            'updated_by' =>
                auth()->id(),
        ]);


        return redirect()
            ->route(
                'admin.tenants.emergency-contacts.index',
                $tenant->id
            )
            ->with(
                'success',
                'Emergency contact updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Delete Emergency Contact
    |--------------------------------------------------------------------------
    */

    public function destroy(
        $tenantId,
        $contactId
    ) {
        $tenant = Tenant::findOrFail($tenantId);

        $emergencyContact =
            TenantEmergencyContact::where(
                'tenant_id',
                $tenant->id
            )
            ->findOrFail($contactId);

        $emergencyContact->delete();


        return redirect()
            ->route(
                'admin.tenants.emergency-contacts.index',
                $tenant->id
            )
            ->with(
                'success',
                'Emergency contact deleted successfully.'
            );
    }
}