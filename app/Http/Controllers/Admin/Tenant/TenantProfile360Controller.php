<?php

namespace App\Http\Controllers\Admin\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TenantProfile360Controller extends Controller
{
    /**
     * Tenant directory with 360-degree profile access.
     */
    public function index(Request $request)
    {
        $query = Tenant::query()
            ->whereNull('tenants.deleted_at');

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('company_name', 'like', "%{$search}%")
                    ->orWhere('brand_name', 'like', "%{$search}%")
                    ->orWhere('tenant_code', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $tenants = $query
            ->orderBy('company_name')
            ->paginate(15)
            ->withQueryString();

        return view('admin.tenants.profile360.index', compact('tenants'));
    }

    /**
     * Aggregate all available information for one tenant.
     */
    public function show(Tenant $tenant)
    {
        abort_if($tenant->deleted_at !== null, 404);

        $tenantId = $tenant->id;

        $contacts = $this->tenantRows(
            'tenant_contacts',
            $tenantId
        );

        $addresses = $this->tenantRows(
            'tenant_addresses',
            $tenantId
        );

        $documents = $this->tenantRows(
            'tenant_documents',
            $tenantId
        );

        $notes = $this->tenantRows(
            'tenant_notes',
            $tenantId
        );

        $history = $this->tenantRows(
            'tenant_history',
            $tenantId,
            'activity_date'
        );

        $leases = $this->tenantRows(
            'lease_agreements',
            $tenantId
        );

        $invoices = $this->tenantRows(
            'invoices',
            $tenantId
        );

        $payments = $this->tenantRows(
            'rent_payments',
            $tenantId
        );

        $fitoutRequests = $this->tenantRows(
            'fitout_requests',
            $tenantId
        );

        /*
         * Existing maintenance module.
         * Table names can vary by project; inspect yours before
         * enabling additional maintenance tables here.
         */
        $maintenanceRequests = $this->tenantRows(
            'maintenance_requests',
            $tenantId
        );

        $workOrders = $this->tenantRows(
            'work_orders',
            $tenantId
        );

        $sales = $this->tenantRows(
            'tenant_sales',
            $tenantId
        );

        /*
         * Renewal risk uses existing lease end dates, statuses,
         * outstanding invoice balances and unresolved requests.
         */
        $today = now()->startOfDay();

        $activeLeases = $leases->filter(function ($lease) use ($today) {
            $status = strtolower((string) ($lease->agreement_status ?? ''));

            return $status === 'active'
                && !empty($lease->lease_end_date);
        })->values();

        $expiringLeases = $activeLeases->filter(function ($lease) use ($today) {
            $endDate = \Illuminate\Support\Carbon::parse(
                $lease->lease_end_date
            );

            return $endDate->betweenIncluded(
                $today,
                $today->copy()->addDays(90)
            );
        })->values();

        $overdueBalance = $invoices->sum(function ($invoice) {
            return (float) (
                $invoice->balance_amount
                ?? $invoice->outstanding_amount
                ?? 0
            );
        });

        $openMaintenance = $maintenanceRequests->filter(function ($row) {
            return !in_array(
                strtolower((string) ($row->status ?? '')),
                ['closed', 'completed', 'cancelled', 'resolved'],
                true
            );
        })->count();

        $openWorkOrders = $workOrders->filter(function ($row) {
            return !in_array(
                strtolower((string) ($row->status ?? $row->work_order_status ?? '')),
                ['closed', 'completed', 'cancelled'],
                true
            );
        })->count();

        $expiredDocuments = $documents->filter(function ($document) use ($today) {
            return !empty($document->expiry_date)
                && \Illuminate\Support\Carbon::parse(
                    $document->expiry_date
                )->lt($today);
        })->count();

        $renewalRisk = 'Low';
        $riskReasons = [];

        if ($expiringLeases->isNotEmpty()) {
            $riskReasons[] = 'One or more active leases expire within 90 days.';
        }

        if ($overdueBalance > 0) {
            $riskReasons[] = 'Invoice balances require financial verification.';
        }

        if ($openMaintenance > 0) {
            $riskReasons[] = 'Open maintenance requests need review.';
        }

        if ($expiringLeases->isNotEmpty() || $overdueBalance > 0) {
            $renewalRisk = 'High';
        } elseif ($openMaintenance > 0 || $openWorkOrders > 0) {
            $renewalRisk = 'Medium';
        }

        $summary = [
            'active_leases'       => $activeLeases->count(),
            'expiring_leases'     => $expiringLeases->count(),
            'invoices'            => $invoices->count(),
            'outstanding_balance' => $overdueBalance,
            'payments'            => $payments->count(),
            'sales_records'       => $sales->count(),
            'open_requests'       => $openMaintenance,
            'open_work_orders'    => $openWorkOrders,
            'fitout_requests'     => $fitoutRequests->count(),
            'documents'           => $documents->count(),
            'expired_documents'   => $expiredDocuments,
        ];

        return view('admin.tenants.profile360.show', compact(
            'tenant',
            'contacts',
            'addresses',
            'documents',
            'notes',
            'history',
            'leases',
            'activeLeases',
            'expiringLeases',
            'invoices',
            'payments',
            'fitoutRequests',
            'maintenanceRequests',
            'workOrders',
            'sales',
            'summary',
            'renewalRisk',
            'riskReasons'
        ));
    }

    /**
     * Safely read an existing table by tenant_id.
     */
    private function tenantRows(
        string $table,
        int $tenantId,
        string $orderBy = 'created_at'
    ) {
        if (!Schema::hasTable($table)) {
            return collect();
        }

        if (!Schema::hasColumn($table, 'tenant_id')) {
            return collect();
        }

        $query = DB::table($table)
            ->where('tenant_id', $tenantId);

        if (Schema::hasColumn($table, 'deleted_at')) {
            $query->whereNull('deleted_at');
        }

        if (Schema::hasColumn($table, $orderBy)) {
            $query->orderByDesc($orderBy);
        } elseif (Schema::hasColumn($table, 'id')) {
            $query->orderByDesc('id');
        }

        return $query->limit(100)->get();
    }
}