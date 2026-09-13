<?php

namespace App\Http\Controllers\Admin\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\User;
use App\Models\TenantHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TenantController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Tenant Index
    |--------------------------------------------------------------------------
    */

    /*public function index()
    {
        $tenants = Tenant::with('user')
            ->latest('id')
            ->paginate(15);

        return view(
            'admin.tenants.index',
            compact('tenants')
        );
    }*/

    public function index(Request $request)
    {
        $query = Tenant::query()
            ->with('user');

        // Search
        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->where('tenant_code', 'like', "%{$search}%")
                    ->orWhere('company_name', 'like', "%{$search}%")
                    ->orWhere('brand_name', 'like', "%{$search}%")
                    ->orWhere('gst_number', 'like', "%{$search}%")
                    ->orWhere('pan_number', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");

            });
        }


        // Tenant Status
        if ($request->filled('status')) {

            $query->where(
                'status',
                $request->status
            );

        }


        // Login Status
        if ($request->login_status === 'active') {

            $query->whereHas('user', function ($q) {

                $q->where('is_active', 1);

            });

        }

        if ($request->login_status === 'inactive') {

            $query->whereHas('user', function ($q) {

                $q->where('is_active', 0);

            });

        }

        if ($request->login_status === 'none') {

            $query->whereDoesntHave('user');

        }


        $tenants = $query
            ->latest('id')
            ->paginate(20)
            ->withQueryString();


        return view(
            'admin.tenants.index',
            compact('tenants')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Create Tenant
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        return view(
            'admin.tenants.create'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Store Tenant
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $validated = $request->validate([

            /*
            |--------------------------------------------------------------------------
            | Login Information
            |--------------------------------------------------------------------------
            */

            'user_name' => [
                'required',
                'string',
                'max:255',
            ],

            'user_email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email',
            ],

            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],


            /*
            |--------------------------------------------------------------------------
            | Tenant Information
            |--------------------------------------------------------------------------
            */

            'tenant_code' => [
                'required',
                'string',
                'max:30',
                'unique:tenants,tenant_code',
            ],

            'company_name' => [
                'required',
                'string',
                'max:200',
            ],

            'brand_name' => [
                'nullable',
                'string',
                'max:150',
            ],

            'business_category_id' => [
                'nullable',
                'integer',
            ],

            'gst_number' => [
                'nullable',
                'string',
                'max:20',
            ],

            'pan_number' => [
                'nullable',
                'string',
                'max:20',
            ],

            'company_registration_no' => [
                'nullable',
                'string',
                'max:100',
            ],

            'website' => [
                'nullable',
                'string',
                'max:200',
            ],

            'email' => [
                'nullable',
                'email',
                'max:150',
            ],

            'phone' => [
                'nullable',
                'string',
                'max:20',
            ],

            'status' => [
                'required',
                'in:Active,Inactive',
            ],
        ]);


        DB::transaction(function () use ($validated) {

            /*
            |--------------------------------------------------------------------------
            | 1. Create User Login
            |--------------------------------------------------------------------------
            */

            $user = User::create([

                'name' =>
                    $validated['user_name'],

                'email' =>
                    $validated['user_email'],

                'password' =>
                    Hash::make(
                        $validated['password']
                    ),

                'is_active' => 1,

                'is_super_admin' => 0,

                'status' => 'Active',
            ]);


            /*
            |--------------------------------------------------------------------------
            | 2. Create Tenant
            |--------------------------------------------------------------------------
            */

         $tenant = Tenant::create([

                'user_id' =>
                    $user->id,

                'uuid' =>
                    (string) Str::uuid(),

                'tenant_code' =>
                    $validated['tenant_code'],

                'company_name' =>
                    $validated['company_name'],

                'brand_name' =>
                    $validated['brand_name'] ?? null,

                'business_category_id' =>
                    $validated['business_category_id'] ?? null,

                'gst_number' =>
                    $validated['gst_number'] ?? null,

                'pan_number' =>
                    $validated['pan_number'] ?? null,

                'company_registration_no' =>
                    $validated['company_registration_no'] ?? null,

                'website' =>
                    $validated['website'] ?? null,

                'email' =>
                    $validated['email'] ?? null,

                'phone' =>
                    $validated['phone'] ?? null,

                'status' =>
                    $validated['status'],

                'created_by' =>
                    auth()->id(),

                'updated_by' =>
                    auth()->id(),
            ]);

            TenantHistory::create([
                'tenant_id' => $tenant->id,
                'activity_type' => 'Tenant Created',
                'reference_module' => 'Tenant',
                'reference_id' => $tenant->id,
                'description' => 'Tenant profile was created.',
                'activity_date' => now(),
                'performed_by' => auth()->id(),
            ]);


        });

        


        return redirect()
            ->route('admin.tenants.index')
            ->with(
                'success',
                'Tenant created successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Show
    |--------------------------------------------------------------------------
    */

    public function show($id)
    {
        $tenant = Tenant::with([
            'user',
            'contacts',
            'addresses',
            'bankAccounts',
            'documents.documentType',
            'emergencyContacts',
            'notes',
            'history.performer',
            'leaseAgreements.rentSchedules',
            'invoices',
            'rentPayments'
        ])->findOrFail($id);

        return view(
            'admin.tenants.show',
            compact('tenant')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Edit Tenant
    |--------------------------------------------------------------------------
    */

    public function edit($id)
    {
        $tenant = Tenant::with('user')
            ->findOrFail($id);

        return view(
            'admin.tenants.edit',
            compact('tenant')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Update Tenant
    |--------------------------------------------------------------------------
    */

    public function update(Request $request, $id)
    {
        $tenant = Tenant::with('user')
            ->findOrFail($id);

        $validated = $request->validate([

            /*
            |--------------------------------------------------------------------------
            | Login Account
            |--------------------------------------------------------------------------
            */

            'user_name' => [
                'required',
                'string',
                'max:255',
            ],

            'user_email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email,' . ($tenant->user_id ?? 'NULL'),
            ],

            'password' => [
                'nullable',
                'string',
                'min:8',
                'confirmed',
            ],


            /*
            |--------------------------------------------------------------------------
            | Tenant
            |--------------------------------------------------------------------------
            */

            'tenant_code' => [
                'required',
                'string',
                'max:30',
                'unique:tenants,tenant_code,' . $tenant->id,
            ],

            'company_name' => [
                'required',
                'string',
                'max:200',
            ],

            'brand_name' => [
                'nullable',
                'string',
                'max:150',
            ],

            'business_category_id' => [
                'nullable',
                'integer',
            ],

            'gst_number' => [
                'nullable',
                'string',
                'max:20',
            ],

            'pan_number' => [
                'nullable',
                'string',
                'max:20',
            ],

            'company_registration_no' => [
                'nullable',
                'string',
                'max:100',
            ],

            'website' => [
                'nullable',
                'string',
                'max:200',
            ],

            'email' => [
                'nullable',
                'email',
                'max:150',
            ],

            'phone' => [
                'nullable',
                'string',
                'max:20',
            ],

            'status' => [
                'required',
                'in:Active,Inactive',
            ],
        ]);


        DB::transaction(function () use (
            $tenant,
            $validated
        ) {

            /*
            |--------------------------------------------------------------------------
            | Update User
            |--------------------------------------------------------------------------
            */

            if ($tenant->user) {

                $userData = [

                    'name' =>
                        $validated['user_name'],

                    'email' =>
                        $validated['user_email'],

                    'is_active' =>
                        $validated['status'] === 'Active'
                            ? 1
                            : 0,

                    'status' =>
                        $validated['status'],
                ];


                /*
                | Only change password if entered
                */

                if (
                    !empty(
                        $validated['password']
                    )
                ) {

                    $userData['password'] =
                        Hash::make(
                            $validated['password']
                        );
                }


                $tenant->user->update(
                    $userData
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Update Tenant
            |--------------------------------------------------------------------------
            */

            $tenant->update([

                'tenant_code' =>
                    $validated['tenant_code'],

                'company_name' =>
                    $validated['company_name'],

                'brand_name' =>
                    $validated['brand_name'] ?? null,

                'business_category_id' =>
                    $validated['business_category_id'] ?? null,

                'gst_number' =>
                    $validated['gst_number'] ?? null,

                'pan_number' =>
                    $validated['pan_number'] ?? null,

                'company_registration_no' =>
                    $validated['company_registration_no'] ?? null,

                'website' =>
                    $validated['website'] ?? null,

                'email' =>
                    $validated['email'] ?? null,

                'phone' =>
                    $validated['phone'] ?? null,

                'status' =>
                    $validated['status'],

                'updated_by' =>
                    auth()->id(),
            ]);
        });


        return redirect()
            ->route(
                'admin.tenants.show',
                $tenant->id
            )
            ->with(
                'success',
                'Tenant updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Delete
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {
        $tenant = Tenant::with('user')
            ->findOrFail($id);

        DB::transaction(function () use ($tenant) {

            /*
            |--------------------------------------------------------------------------
            | Deactivate Tenant
            |--------------------------------------------------------------------------
            */

            $tenant->update([
                'status' => 'Inactive',
                'updated_by' => auth()->id(),
            ]);


            /*
            |--------------------------------------------------------------------------
            | Deactivate Login Account
            |--------------------------------------------------------------------------
            */

            if ($tenant->user) {

                $tenant->user->update([
                    'is_active' => 0,
                    'status' => 'Inactive',
                ]);
            }
        });

        return redirect()
            ->route('admin.tenants.index')
            ->with(
                'success',
                'Tenant has been deactivated successfully.'
            );
    }
}