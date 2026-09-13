<?php

namespace App\Http\Controllers\Admin\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\TenantHistory;

class TenantHistoryController extends Controller
{
    public function index($tenantId)
    {
        $tenant = Tenant::findOrFail($tenantId);

        $history = TenantHistory::with('performer')
            ->where(
                'tenant_id',
                $tenant->id
            )
            ->orderByDesc('activity_date')
            ->get();

        return view(
            'admin.tenants.history.index',
            compact(
                'tenant',
                'history'
            )
        );
    }
}