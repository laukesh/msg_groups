<?php

namespace App\Http\Controllers\Admin\Revenue;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\RentPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RevenueReportController extends Controller
{
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Invoice Report
        |--------------------------------------------------------------------------
        */

        $query = Invoice::with('tenant')
            ->whereNotIn('invoice_status', [
                'Draft',
                'Cancelled'
            ])
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
        | Invoice Status
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
        | Date Range
        |--------------------------------------------------------------------------
        */

        if ($request->filled('from_date')) {

            $query->whereDate(
                'invoice_date',
                '>=',
                $request->from_date
            );
        }


        if ($request->filled('to_date')) {

            $query->whereDate(
                'invoice_date',
                '<=',
                $request->to_date
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
                'Cancelled'
            ]);


        if ($request->filled('from_date')) {

            $summaryQuery->whereDate(
                'invoice_date',
                '>=',
                $request->from_date
            );
        }


        if ($request->filled('to_date')) {

            $summaryQuery->whereDate(
                'invoice_date',
                '<=',
                $request->to_date
            );
        }


        if ($request->filled('status')) {

            $summaryQuery->where(
                'invoice_status',
                $request->status
            );
        }


        $totalInvoiced = (clone $summaryQuery)
            ->sum('total_amount');

        $totalCollected = (clone $summaryQuery)
            ->sum('paid_amount');

        $totalOutstanding = (clone $summaryQuery)
            ->sum('balance_amount');

        $invoiceCount = (clone $summaryQuery)
            ->count();


        return view(
            'admin.revenue.reports.revenue',
            compact(
                'invoices',
                'totalInvoiced',
                'totalCollected',
                'totalOutstanding',
                'invoiceCount'
            )
        );
    }

    public function collections(Request $request)
	{
	    $query = RentPayment::with([
	        'tenant',
	        'invoice'
	    ])
	    ->where('payment_status', 'Confirmed')
	    ->orderByDesc('payment_date')
	    ->orderByDesc('id');


	    /*
	    |--------------------------------------------------------------------------
	    | Search
	    |--------------------------------------------------------------------------
	    */

	    if ($request->filled('search')) {

	        $search = $request->search;

	        $query->where(function ($q) use ($search) {

	            $q->where(
	                'payment_no',
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

	            })
	            ->orWhereHas('invoice', function ($invoiceQuery) use ($search) {

	                $invoiceQuery->where(
	                    'invoice_no',
	                    'like',
	                    "%{$search}%"
	                );

	            });

	        });
	    }


	    /*
	    |--------------------------------------------------------------------------
	    | Payment Date
	    |--------------------------------------------------------------------------
	    */

	    if ($request->filled('from_date')) {

	        $query->whereDate(
	            'payment_date',
	            '>=',
	            $request->from_date
	        );
	    }


	    if ($request->filled('to_date')) {

	        $query->whereDate(
	            'payment_date',
	            '<=',
	            $request->to_date
	        );
	    }


	    /*
	    |--------------------------------------------------------------------------
	    | Pagination
	    |--------------------------------------------------------------------------
	    */

	    $payments = $query
	        ->paginate(20)
	        ->withQueryString();


	    /*
	    |--------------------------------------------------------------------------
	    | Summary
	    |--------------------------------------------------------------------------
	    */

	    $summaryQuery = RentPayment::query()
	        ->where('payment_status', 'Confirmed');


	    if ($request->filled('from_date')) {

	        $summaryQuery->whereDate(
	            'payment_date',
	            '>=',
	            $request->from_date
	        );
	    }


	    if ($request->filled('to_date')) {

	        $summaryQuery->whereDate(
	            'payment_date',
	            '<=',
	            $request->to_date
	        );
	    }


	    $totalCollected = (clone $summaryQuery)
	        ->sum('payment_amount');

	    $paymentCount = (clone $summaryQuery)
	        ->count();


	    return view(
	        'admin.revenue.reports.collections',
	        compact(
	            'payments',
	            'totalCollected',
	            'paymentCount'
	        )
	    );
	}

	public function chargeWise(Request $request)
	{
	    $query = DB::table('invoice_items as ii')
	        ->join(
	            'invoices as i',
	            'i.id',
	            '=',
	            'ii.invoice_id'
	        )
	        ->join(
	            'charge_types as ct',
	            'ct.id',
	            '=',
	            'ii.charge_type_id'
	        )
	        ->select(
	            'ct.id',
	            'ct.charge_name',
	            'ct.charge_code',

	            DB::raw('COUNT(DISTINCT ii.invoice_id) as invoice_count'),

	            DB::raw('SUM(ii.total_amount) as total_revenue'),

	            DB::raw('SUM(
	                CASE
	                    WHEN i.invoice_status != "Draft"
	                    AND i.invoice_status != "Cancelled"
	                    THEN ii.total_amount
	                    ELSE 0
	                END
	            ) as valid_revenue')
	        )
	        ->whereNotIn('i.invoice_status', [
	            'Draft',
	            'Cancelled'
	        ]);


	    /*
	    |--------------------------------------------------------------------------
	    | Date Filter
	    |--------------------------------------------------------------------------
	    */

	    if ($request->filled('from_date')) {

	        $query->whereDate(
	            'i.invoice_date',
	            '>=',
	            $request->from_date
	        );
	    }


	    if ($request->filled('to_date')) {

	        $query->whereDate(
	            'i.invoice_date',
	            '<=',
	            $request->to_date
	        );
	    }


	    /*
	    |--------------------------------------------------------------------------
	    | Charge Type Filter
	    |--------------------------------------------------------------------------
	    */

	    if ($request->filled('charge_type_id')) {

	        $query->where(
	            'ii.charge_type_id',
	            $request->charge_type_id
	        );
	    }


	    /*
	    |--------------------------------------------------------------------------
	    | Group
	    |--------------------------------------------------------------------------
	    */

	    $query->groupBy(
	        'ct.id',
	        'ct.charge_name',
	        'ct.charge_code'
	    );


	    $query->orderByDesc('total_revenue');


	    $chargeWise = $query->get();


	    /*
	    |--------------------------------------------------------------------------
	    | Charge Types
	    |--------------------------------------------------------------------------
	    */

	    $chargeTypes = DB::table('charge_types')
	        ->select(
	            'id',
	            'charge_name',
	            'charge_code'
	        )
	        ->orderBy('charge_name')
	        ->get();


	    /*
	    |--------------------------------------------------------------------------
	    | Summary
	    |--------------------------------------------------------------------------
	    */

	    $totalRevenue = $chargeWise->sum(
	        'total_revenue'
	    );

	    $chargeCount = $chargeWise->count();


	    return view(
	        'admin.revenue.reports.charge-wise',
	        compact(
	            'chargeWise',
	            'chargeTypes',
	            'totalRevenue',
	            'chargeCount'
	        )
	    );
	}

	public function tenantWise(Request $request)
	{
	    $query = DB::table('invoices as i')
	        ->join(
	            'tenants as t',
	            't.id',
	            '=',
	            'i.tenant_id'
	        )
	        ->select(
	            't.id as tenant_id',
	            't.company_name',
	            't.tenant_code',

	            DB::raw('COUNT(i.id) as invoice_count'),

	            DB::raw('SUM(i.total_amount) as total_invoiced'),

	            DB::raw('SUM(i.paid_amount) as total_collected'),

	            DB::raw('SUM(i.balance_amount) as total_outstanding')
	        )
	        ->whereNotIn('i.invoice_status', [
	            'Draft',
	            'Cancelled'
	        ]);


	    /*
	    |--------------------------------------------------------------------------
	    | Search Tenant
	    |--------------------------------------------------------------------------
	    */

	    if ($request->filled('search')) {

	        $search = $request->search;

	        $query->where(function ($q) use ($search) {

	            $q->where(
	                't.company_name',
	                'like',
	                "%{$search}%"
	            )
	            ->orWhere(
	                't.tenant_code',
	                'like',
	                "%{$search}%"
	            );

	        });
	    }


	    /*
	    |--------------------------------------------------------------------------
	    | Date Filter
	    |--------------------------------------------------------------------------
	    */

	    if ($request->filled('from_date')) {

	        $query->whereDate(
	            'i.invoice_date',
	            '>=',
	            $request->from_date
	        );
	    }


	    if ($request->filled('to_date')) {

	        $query->whereDate(
	            'i.invoice_date',
	            '<=',
	            $request->to_date
	        );
	    }


	    /*
	    |--------------------------------------------------------------------------
	    | Group By Tenant
	    |--------------------------------------------------------------------------
	    */

	    $query->groupBy(
	        't.id',
	        't.company_name',
	        't.tenant_code'
	    );


	    /*
	    |--------------------------------------------------------------------------
	    | Highest Revenue First
	    |--------------------------------------------------------------------------
	    */

	    $query->orderByDesc(
	        'total_invoiced'
	    );


	    $tenantWise = $query->get();


	    /*
	    |--------------------------------------------------------------------------
	    | Summary
	    |--------------------------------------------------------------------------
	    */

	    $totalRevenue = $tenantWise->sum(
	        'total_invoiced'
	    );

	    $totalCollected = $tenantWise->sum(
	        'total_collected'
	    );

	    $totalOutstanding = $tenantWise->sum(
	        'total_outstanding'
	    );

	    $tenantCount = $tenantWise->count();


	    return view(
	        'admin.revenue.reports.tenant-wise',
	        compact(
	            'tenantWise',
	            'totalRevenue',
	            'totalCollected',
	            'totalOutstanding',
	            'tenantCount'
	        )
	    );
	}

	public function aging(Request $request)
	{
	    $query = Invoice::with('tenant')
	        ->whereNotIn('invoice_status', [
	            'Draft',
	            'Cancelled',
	            'Paid'
	        ])
	        ->where('balance_amount', '>', 0)
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
	    | Date Filter
	    |--------------------------------------------------------------------------
	    */

	    if ($request->filled('from_date')) {

	        $query->whereDate(
	            'invoice_date',
	            '>=',
	            $request->from_date
	        );
	    }


	    if ($request->filled('to_date')) {

	        $query->whereDate(
	            'invoice_date',
	            '<=',
	            $request->to_date
	        );
	    }


	    /*
	    |--------------------------------------------------------------------------
	    | Get Invoices
	    |--------------------------------------------------------------------------
	    */

	    $invoices = $query
	        ->paginate(20)
	        ->withQueryString();


	    /*
	    |--------------------------------------------------------------------------
	    | Aging Summary
	    |--------------------------------------------------------------------------
	    */

	    $summaryInvoices = Invoice::query()
	        ->whereNotIn('invoice_status', [
	            'Draft',
	            'Cancelled',
	            'Paid'
	        ])
	        ->where('balance_amount', '>', 0)
	        ->get([
	            'id',
	            'due_date',
	            'balance_amount'
	        ]);


	    $aging = [
	        'current' => [
	            'count' => 0,
	            'amount' => 0,
	        ],

	        '1_30' => [
	            'count' => 0,
	            'amount' => 0,
	        ],

	        '31_60' => [
	            'count' => 0,
	            'amount' => 0,
	        ],

	        '61_90' => [
	            'count' => 0,
	            'amount' => 0,
	        ],

	        '90_plus' => [
	            'count' => 0,
	            'amount' => 0,
	        ],
	    ];


	    foreach ($summaryInvoices as $invoice) {

	        if (!$invoice->due_date) {

	            continue;
	        }


	        $dueDate = \Carbon\Carbon::parse(
	            $invoice->due_date
	        );


	        /*
	        |--------------------------------------------------------------------------
	        | Not Yet Due
	        |--------------------------------------------------------------------------
	        */

	        if ($dueDate->isFuture()) {

	            $aging['current']['count']++;

	            $aging['current']['amount'] +=
	                (float) $invoice->balance_amount;

	            continue;
	        }


	        /*
	        |--------------------------------------------------------------------------
	        | Overdue Days
	        |--------------------------------------------------------------------------
	        */

	        $days = $dueDate->diffInDays(
	            now()
	        );


	        if ($days <= 30) {

	            $bucket = '1_30';

	        } elseif ($days <= 60) {

	            $bucket = '31_60';

	        } elseif ($days <= 90) {

	            $bucket = '61_90';

	        } else {

	            $bucket = '90_plus';
	        }


	        $aging[$bucket]['count']++;

	        $aging[$bucket]['amount'] +=
	            (float) $invoice->balance_amount;
	    }


	    /*
	    |--------------------------------------------------------------------------
	    | Total Outstanding
	    |--------------------------------------------------------------------------
	    */

	    $totalOutstanding = $summaryInvoices->sum(
	        'balance_amount'
	    );


	    return view(
	        'admin.revenue.reports.aging',
	        compact(
	            'invoices',
	            'aging',
	            'totalOutstanding'
	        )
	    );
	}

}