<?php

namespace App\Http\Controllers\Admin\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\TenantBankAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TenantBankAccountController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Bank Account Index
    |--------------------------------------------------------------------------
    */

    public function index($tenantId)
    {
        $tenant = Tenant::findOrFail($tenantId);

        $bankAccounts = TenantBankAccount::where(
            'tenant_id',
            $tenant->id
        )
            ->latest('id')
            ->get();

        return view(
            'admin.tenants.bank_accounts.index',
            compact(
                'tenant',
                'bankAccounts'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Store Bank Account
    |--------------------------------------------------------------------------
    */

    public function store(
        Request $request,
        $tenantId
    ) {
        $tenant = Tenant::findOrFail($tenantId);

        $validated = $request->validate([

            'account_holder' => [
                'required',
                'string',
                'max:150',
            ],

            'bank_name' => [
                'required',
                'string',
                'max:150',
            ],

            'branch_name' => [
                'nullable',
                'string',
                'max:150',
            ],

            'account_number' => [
                'required',
                'string',
                'max:50',
            ],

            'ifsc_code' => [
                'nullable',
                'string',
                'max:20',
            ],

            'swift_code' => [
                'nullable',
                'string',
                'max:20',
            ],

            'account_type' => [
                'required',
                'in:Current,Savings',
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
            | Remove Existing Default
            |--------------------------------------------------------------------------
            */

            if (
                !empty(
                    $validated['is_default']
                )
            ) {

                TenantBankAccount::where(
                    'tenant_id',
                    $tenant->id
                )->update([
                    'is_default' => 0,
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Create Bank Account
            |--------------------------------------------------------------------------
            */

            TenantBankAccount::create([

                'tenant_id' =>
                    $tenant->id,

                'account_holder' =>
                    $validated['account_holder'],

                'bank_name' =>
                    $validated['bank_name'],

                'branch_name' =>
                    $validated['branch_name'] ?? null,

                'account_number' =>
                    $validated['account_number'],

                'ifsc_code' =>
                    $validated['ifsc_code'] ?? null,

                'swift_code' =>
                    $validated['swift_code'] ?? null,

                'account_type' =>
                    $validated['account_type'],

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
                'admin.tenants.bank-accounts.index',
                $tenant->id
            )
            ->with(
                'success',
                'Tenant bank account added successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Edit Bank Account
    |--------------------------------------------------------------------------
    */

    public function edit(
        $tenantId,
        $accountId
    ) {
        $tenant = Tenant::findOrFail($tenantId);

        $bankAccount = TenantBankAccount::where(
            'tenant_id',
            $tenant->id
        )->findOrFail($accountId);

        return view(
            'admin.tenants.bank_accounts.edit',
            compact(
                'tenant',
                'bankAccount'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Update Bank Account
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        $tenantId,
        $accountId
    ) {
        $tenant = Tenant::findOrFail($tenantId);

        $bankAccount = TenantBankAccount::where(
            'tenant_id',
            $tenant->id
        )->findOrFail($accountId);

        $validated = $request->validate([

            'account_holder' => [
                'required',
                'string',
                'max:150',
            ],

            'bank_name' => [
                'required',
                'string',
                'max:150',
            ],

            'branch_name' => [
                'nullable',
                'string',
                'max:150',
            ],

            'account_number' => [
                'required',
                'string',
                'max:50',
            ],

            'ifsc_code' => [
                'nullable',
                'string',
                'max:20',
            ],

            'swift_code' => [
                'nullable',
                'string',
                'max:20',
            ],

            'account_type' => [
                'required',
                'in:Current,Savings',
            ],

            'is_default' => [
                'nullable',
                'boolean',
            ],
        ]);


        DB::transaction(function () use (
            $tenant,
            $bankAccount,
            $validated
        ) {

            /*
            |--------------------------------------------------------------------------
            | Remove Existing Default
            |--------------------------------------------------------------------------
            */

            if (
                !empty(
                    $validated['is_default']
                )
            ) {

                TenantBankAccount::where(
                    'tenant_id',
                    $tenant->id
                )
                ->where(
                    'id',
                    '!=',
                    $bankAccount->id
                )
                ->update([
                    'is_default' => 0,
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Update Account
            |--------------------------------------------------------------------------
            */

            $bankAccount->update([

                'account_holder' =>
                    $validated['account_holder'],

                'bank_name' =>
                    $validated['bank_name'],

                'branch_name' =>
                    $validated['branch_name'] ?? null,

                'account_number' =>
                    $validated['account_number'],

                'ifsc_code' =>
                    $validated['ifsc_code'] ?? null,

                'swift_code' =>
                    $validated['swift_code'] ?? null,

                'account_type' =>
                    $validated['account_type'],

                'is_default' =>
                    $validated['is_default'] ?? false,

                'updated_by' =>
                    auth()->id(),
            ]);
        });


        return redirect()
            ->route(
                'admin.tenants.bank-accounts.index',
                $tenant->id
            )
            ->with(
                'success',
                'Tenant bank account updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Delete Bank Account
    |--------------------------------------------------------------------------
    */

    public function destroy(
        $tenantId,
        $accountId
    ) {
        $tenant = Tenant::findOrFail($tenantId);

        $bankAccount = TenantBankAccount::where(
            'tenant_id',
            $tenant->id
        )->findOrFail($accountId);

        $bankAccount->delete();

        return redirect()
            ->route(
                'admin.tenants.bank-accounts.index',
                $tenant->id
            )
            ->with(
                'success',
                'Tenant bank account deleted successfully.'
            );
    }
}