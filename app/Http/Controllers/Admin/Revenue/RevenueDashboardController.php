<?php

namespace App\Http\Controllers\Admin\Revenue;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\RentPayment;
use App\Models\PaymentAllocation;
use App\Models\RentSchedule;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RevenueDashboardController extends Controller
{
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | Current Date
        |--------------------------------------------------------------------------
        */

        $today = Carbon::today();

        $monthStart = Carbon::now()->startOfMonth();

        $monthEnd = Carbon::now()->endOfMonth();


        /*
        |--------------------------------------------------------------------------
        | TOTAL INVOICED
        |--------------------------------------------------------------------------
        |
        | Exclude Draft and Cancelled invoices.
        |
        */

        $totalInvoiced = Invoice::whereNotIn(
            'invoice_status',
            ['Draft', 'Cancelled']
        )->sum('total_amount');


        /*
        |--------------------------------------------------------------------------
        | TOTAL COLLECTED
        |--------------------------------------------------------------------------
        |
        | Use active payment allocations.
        | This prevents reversed allocations from being counted.
        |
        */

        $totalCollected = PaymentAllocation::where(
                'allocation_status',
                'Allocated'
            )
            ->sum('allocated_amount');


        /*
        |--------------------------------------------------------------------------
        | OUTSTANDING
        |--------------------------------------------------------------------------
        */

        $outstandingAmount = max(
            0,
            (float) $totalInvoiced -
            (float) $totalCollected
        );


        /*
        |--------------------------------------------------------------------------
        | OVERDUE
        |--------------------------------------------------------------------------
        */

        $overdueAmount = Invoice::where(
                'due_date',
                '<',
                $today
            )
            ->whereNotIn(
                'invoice_status',
                ['Draft', 'Cancelled', 'Paid']
            )
            ->sum('balance_amount');


        /*
        |--------------------------------------------------------------------------
        | CURRENT MONTH INVOICED
        |--------------------------------------------------------------------------
        */

        $currentMonthInvoiced = Invoice::whereBetween(
                'invoice_date',
                [
                    $monthStart->toDateString(),
                    $monthEnd->toDateString()
                ]
            )
            ->whereNotIn(
                'invoice_status',
                ['Draft', 'Cancelled']
            )
            ->sum('total_amount');


        /*
        |--------------------------------------------------------------------------
        | CURRENT MONTH COLLECTION
        |--------------------------------------------------------------------------
        */

        $currentMonthCollected = PaymentAllocation::where(
                'allocation_status',
                'Allocated'
            )
            ->whereBetween(
                'allocation_date',
                [
                    $monthStart->toDateString(),
                    $monthEnd->toDateString()
                ]
            )
            ->sum('allocated_amount');


        /*
        |--------------------------------------------------------------------------
        | COLLECTION RATE
        |--------------------------------------------------------------------------
        */

        $collectionRate = 0;

        if ($totalInvoiced > 0) {

            $collectionRate =
                ($totalCollected / $totalInvoiced) * 100;
        }


        /*
        |--------------------------------------------------------------------------
        | PENDING RECONCILIATION
        |--------------------------------------------------------------------------
        */

        $pendingReconciliation = RentPayment::where(
                'payment_status',
                'Confirmed'
            )
            ->where(
                'reconciliation_status',
                'Pending'
            )
            ->sum('payment_amount');


        /*
        |--------------------------------------------------------------------------
        | PAYMENT COUNTS
        |--------------------------------------------------------------------------
        */

        $pendingPayments = RentPayment::where(
            'payment_status',
            'Pending'
        )->count();

        $confirmedPayments = RentPayment::where(
            'payment_status',
            'Confirmed'
        )->count();

        $reconciledPayments = RentPayment::where(
            'reconciliation_status',
            'Reconciled'
        )->count();


        /*
        |--------------------------------------------------------------------------
        | INVOICE COUNTS
        |--------------------------------------------------------------------------
        */

        $totalInvoices = Invoice::whereNotIn(
            'invoice_status',
            ['Draft', 'Cancelled']
        )->count();

        $paidInvoices = Invoice::where(
            'invoice_status',
            'Paid'
        )->count();

        $partialInvoices = Invoice::where(
            'invoice_status',
            'Partially Paid'
        )->count();

        $overdueInvoices = Invoice::where(
                'due_date',
                '<',
                $today
            )
            ->whereNotIn(
                'invoice_status',
                ['Draft', 'Cancelled', 'Paid']
            )
            ->count();


        /*
        |--------------------------------------------------------------------------
        | RECENT PAYMENTS
        |--------------------------------------------------------------------------
        */

        $recentPayments = RentPayment::with([
                'tenant',
                'invoice',
            ])
            ->orderByDesc('id')
            ->limit(10)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | OUTSTANDING INVOICES
        |--------------------------------------------------------------------------
        */

        $outstandingInvoices = Invoice::with([
                'tenant',
            ])
            ->where(
                'balance_amount',
                '>',
                0
            )
            ->whereNotIn(
                'invoice_status',
                ['Draft', 'Cancelled']
            )
            ->orderByDesc('due_date')
            ->limit(10)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | MONTHLY REVENUE
        |--------------------------------------------------------------------------
        |
        | Last 6 months.
        |
        */

        $monthlyRevenue = PaymentAllocation::select(
                DB::raw(
                    "DATE_FORMAT(allocation_date, '%Y-%m') as month"
                ),
                DB::raw(
                    "SUM(allocated_amount) as total"
                )
            )
            ->where(
                'allocation_status',
                'Allocated'
            )
            ->where(
                'allocation_date',
                '>=',
                Carbon::now()
                    ->subMonths(5)
                    ->startOfMonth()
                    ->toDateString()
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Monthly chart data
        |--------------------------------------------------------------------------
        */

        $chartLabels = [];
        $chartValues = [];

        for ($i = 5; $i >= 0; $i--) {

            $date = Carbon::now()
                ->subMonths($i);

            $monthKey = $date->format('Y-m');

            $chartLabels[] = $date->format('M Y');

            $record = $monthlyRevenue->firstWhere(
                'month',
                $monthKey
            );

            $chartValues[] = $record
                ? (float) $record->total
                : 0;
        }

        $chargeTypeRevenue = DB::table('invoice_items as ii')
    ->join('charge_types as ct', 'ct.id', '=', 'ii.charge_type_id')
    ->join('invoices as i', 'i.id', '=', 'ii.invoice_id')

    ->leftJoinSub(
        DB::table('invoice_item_allocations')
            ->select(
                'invoice_item_id',
                DB::raw('SUM(allocated_amount) as collected_amount')
            )
            ->where(
                'allocation_status',
                'Allocated'
            )
            ->groupBy('invoice_item_id'),
        'allocations',
        function ($join) {
            $join->on(
                'allocations.invoice_item_id',
                '=',
                'ii.id'
            );
        }
    )

    ->whereNotIn('i.invoice_status', [
        'Draft',
        'Cancelled'
    ])

    ->select(
        'ct.id as charge_type_id',
        'ct.charge_name',
        'ct.charge_code',

        DB::raw('SUM(ii.total_amount) as invoiced_amount'),

        DB::raw('
            SUM(
                COALESCE(
                    allocations.collected_amount,
                    0
                )
            ) as collected_amount
        '),

        DB::raw('
            SUM(ii.total_amount)
            -
            SUM(
                COALESCE(
                    allocations.collected_amount,
                    0
                )
            ) as outstanding_amount
        ')
    )

    ->groupBy(
        'ct.id',
        'ct.charge_name',
        'ct.charge_code'
    )

    ->orderByDesc('invoiced_amount')

    ->get();


        return view(
            'admin.revenue.dashboard',
            compact(
                'totalInvoiced',
                'totalCollected',
                'outstandingAmount',
                'overdueAmount',
                'currentMonthInvoiced',
                'currentMonthCollected',
                'collectionRate',
                'pendingReconciliation',
                'pendingPayments',
                'confirmedPayments',
                'reconciledPayments',
                'totalInvoices',
                'paidInvoices',
                'partialInvoices',
                'overdueInvoices',
                'recentPayments',
                'outstandingInvoices',
                'chartLabels',
                'chartValues',
                'chargeTypeRevenue'
            )
        );
    }
}