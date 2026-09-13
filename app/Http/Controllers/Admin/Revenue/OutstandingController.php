<?php

namespace App\Http\Controllers\Admin\Revenue;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;

class OutstandingController extends Controller
{
    public function index(Request $request)
    {
        $query = Invoice::with([
            'tenant'
        ])
        ->whereNotIn('invoice_status', [
            'Draft',
            'Cancelled',
            'Paid'
        ])
        ->where('balance_amount', '>', 0)
        ->orderByDesc('invoice_date');

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where(
                    'invoice_no',
                    'like',
                    "%{$search}%"
                )
                ->orWhereHas('tenant', function ($tenantQuery) use ($search) {

                    $tenantQuery
                        ->where('company_name', 'like', "%{$search}%")
                        ->orWhere(
                            'tenant_code',
                            'like',
                            "%{$search}%"
                        );

                });

            });
        }


        /*
        |--------------------------------------------------------------------------
        | Status Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {

            $query->where(
                'invoice_status',
                $request->status
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $invoices = $query
            ->paginate(20)
            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | Summary
        |--------------------------------------------------------------------------
        */

        $summaryQuery = Invoice::query()
            ->whereNotIn('invoice_status', [
                'Draft',
                'Cancelled',
                'Paid'
            ])
            ->where('balance_amount', '>', 0);

        $totalOutstanding = (clone $summaryQuery)
            ->sum('balance_amount');

        $totalInvoices = (clone $summaryQuery)
            ->count();

        $overdueInvoices = (clone $summaryQuery)
            ->whereDate(
                'due_date',
                '<',
                now()->toDateString()
            )
            ->count();

        $overdueAmount = (clone $summaryQuery)
            ->whereDate(
                'due_date',
                '<',
                now()->toDateString()
            )
            ->sum('balance_amount');


        return view(
            'admin.revenue.outstanding.index',
            compact(
                'invoices',
                'totalOutstanding',
                'totalInvoices',
                'overdueInvoices',
                'overdueAmount'
            )
        );
    }

    public function overdue(Request $request)
	{
	    $query = Invoice::with([
	        'tenant'
	    ])
	    ->whereNotIn('invoice_status', [
	        'Draft',
	        'Cancelled',
	        'Paid'
	    ])
	    ->where('balance_amount', '>', 0)
	    ->whereDate(
	        'due_date',
	        '<',
	        now()->toDateString()
	    )
	    ->orderBy('due_date', 'asc');


	    /*
	    |--------------------------------------------------------------------------
	    | Search
	    |--------------------------------------------------------------------------
	    */

	    if ($request->filled('search')) {

	        $search = $request->search;

	        $query->where(function ($q) use ($search) {

	            $q->where(
	                'invoice_no',
	                'like',
	                "%{$search}%"
	            )
	            ->orWhereHas('tenant', function ($tenantQuery) use ($search) {

	                $tenantQuery
	                    ->where(
	                        'company_name',
	                        'like',
	                        "%{$search}%"
	                    )
	                    ->orWhere(
	                        'tenant_code',
	                        'like',
	                        "%{$search}%"
	                    );

	            });

	        });
	    }


	    /*
	    |--------------------------------------------------------------------------
	    | Pagination
	    |--------------------------------------------------------------------------
	    */

	    $invoices = $query
	        ->paginate(20)
	        ->withQueryString();


	    /*
	    |--------------------------------------------------------------------------
	    | Summary
	    |--------------------------------------------------------------------------
	    */

	    $summaryQuery = Invoice::query()
	        ->whereNotIn('invoice_status', [
	            'Draft',
	            'Cancelled',
	            'Paid'
	        ])
	        ->where('balance_amount', '>', 0)
	        ->whereDate(
	            'due_date',
	            '<',
	            now()->toDateString()
	        );


	    $overdueAmount = (clone $summaryQuery)
	        ->sum('balance_amount');


	    $overdueInvoices = (clone $summaryQuery)
	        ->count();


	    return view(
	        'admin.revenue.outstanding.overdue',
	        compact(
	            'invoices',
	            'overdueAmount',
	            'overdueInvoices'
	        )
	    );
	}

	public function tenants(Request $request)
	{
	    $query = Invoice::query()
	        ->select(
	            'tenant_id',
	            \DB::raw('COUNT(*) as invoice_count'),
	            \DB::raw('SUM(total_amount) as total_invoiced'),
	            \DB::raw('SUM(paid_amount) as total_paid'),
	            \DB::raw('SUM(balance_amount) as total_outstanding')
	        )
	        ->with('tenant')
	        ->whereNotIn('invoice_status', [
	            'Draft',
	            'Cancelled',
	            'Paid'
	        ])
	        ->where('balance_amount', '>', 0);


	    /*
	    |--------------------------------------------------------------------------
	    | Tenant Search
	    |--------------------------------------------------------------------------
	    */

	    if ($request->filled('search')) {

	        $search = $request->search;

	        $query->whereHas('tenant', function ($q) use ($search) {

	            $q->where(
	                'company_name',
	                'like',
	                "%{$search}%"
	            )
	            ->orWhere(
	                'tenant_code',
	                'like',
	                "%{$search}%"
	            );

	        });
	    }


	    /*
	    |--------------------------------------------------------------------------
	    | Group By Tenant
	    |--------------------------------------------------------------------------
	    */

	    $query->groupBy('tenant_id');


	    /*
	    |--------------------------------------------------------------------------
	    | Highest Outstanding First
	    |--------------------------------------------------------------------------
	    */

	    $query->orderByDesc('total_outstanding');


	    /*
	    |--------------------------------------------------------------------------
	    | Pagination
	    |--------------------------------------------------------------------------
	    */

	    $tenants = $query
	        ->paginate(20)
	        ->withQueryString();


	    /*
	    |--------------------------------------------------------------------------
	    | Summary
	    |--------------------------------------------------------------------------
	    */

	    $summaryQuery = Invoice::query()
	        ->whereNotIn('invoice_status', [
	            'Draft',
	            'Cancelled',
	            'Paid'
	        ])
	        ->where('balance_amount', '>', 0);


	    $totalTenants = (clone $summaryQuery)
	        ->whereNotNull('tenant_id')
	        ->distinct('tenant_id')
	        ->count('tenant_id');


	    $totalOutstanding = (clone $summaryQuery)
	        ->sum('balance_amount');


	    return view(
	        'admin.revenue.outstanding.tenants',
	        compact(
	            'tenants',
	            'totalTenants',
	            'totalOutstanding'
	        )
	    );
	}
}