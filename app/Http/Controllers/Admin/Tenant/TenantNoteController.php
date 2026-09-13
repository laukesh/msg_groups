<?php

namespace App\Http\Controllers\Admin\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\TenantNote;
use Illuminate\Http\Request;

class TenantNoteController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Note Index
    |--------------------------------------------------------------------------
    */

    public function index($tenantId)
    {
        $tenant = Tenant::findOrFail($tenantId);

        $notes = TenantNote::where(
            'tenant_id',
            $tenant->id
        )
            ->with('tenant')
            ->latest('created_at')
            ->get();

        return view(
            'admin.tenants.notes.index',
            compact(
                'tenant',
                'notes'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Store Note
    |--------------------------------------------------------------------------
    */

    public function store(
        Request $request,
        $tenantId
    ) {
        $tenant = Tenant::findOrFail($tenantId);

        $validated = $request->validate([

            'note_title' => [
                'nullable',
                'string',
                'max:200',
            ],

            'note' => [
                'required',
                'string',
            ],

            'visibility' => [
                'required',
                'in:Internal,Management',
            ],
        ]);


        TenantNote::create([

            'tenant_id' =>
                $tenant->id,

            'note_title' =>
                $validated['note_title'] ?? null,

            'note' =>
                $validated['note'],

            'visibility' =>
                $validated['visibility'],

            'created_by' =>
                auth()->id(),
        ]);


        return redirect()
            ->route(
                'admin.tenants.notes.index',
                $tenant->id
            )
            ->with(
                'success',
                'Tenant note added successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Edit Note
    |--------------------------------------------------------------------------
    */

    public function edit(
        $tenantId,
        $noteId
    ) {
        $tenant = Tenant::findOrFail($tenantId);

        $note = TenantNote::where(
            'tenant_id',
            $tenant->id
        )
            ->findOrFail($noteId);

        return view(
            'admin.tenants.notes.edit',
            compact(
                'tenant',
                'note'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Update Note
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        $tenantId,
        $noteId
    ) {
        $tenant = Tenant::findOrFail($tenantId);

        $note = TenantNote::where(
            'tenant_id',
            $tenant->id
        )
            ->findOrFail($noteId);

        $validated = $request->validate([

            'note_title' => [
                'nullable',
                'string',
                'max:200',
            ],

            'note' => [
                'required',
                'string',
            ],

            'visibility' => [
                'required',
                'in:Internal,Management',
            ],
        ]);


        $note->update([

            'note_title' =>
                $validated['note_title'] ?? null,

            'note' =>
                $validated['note'],

            'visibility' =>
                $validated['visibility'],
        ]);


        return redirect()
            ->route(
                'admin.tenants.notes.index',
                $tenant->id
            )
            ->with(
                'success',
                'Tenant note updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Delete Note
    |--------------------------------------------------------------------------
    */

    public function destroy(
        $tenantId,
        $noteId
    ) {
        $tenant = Tenant::findOrFail($tenantId);

        $note = TenantNote::where(
            'tenant_id',
            $tenant->id
        )
            ->findOrFail($noteId);

        $note->delete();


        return redirect()
            ->route(
                'admin.tenants.notes.index',
                $tenant->id
            )
            ->with(
                'success',
                'Tenant note deleted successfully.'
            );
    }
}