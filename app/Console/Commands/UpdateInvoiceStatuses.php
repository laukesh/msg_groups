<?php

namespace App\Console\Commands;

use App\Models\Invoice;
use App\Models\RentSchedule;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateInvoiceStatuses extends Command
{
    protected $signature = 'invoices:update-statuses';

    protected $description = 'Update invoice and rent schedule payment statuses';

    public function handle()
    {
        DB::transaction(function () {

            $invoices = Invoice::whereNotIn(
                'invoice_status',
                ['Draft', 'Cancelled', 'Paid']
            )->get();

            foreach ($invoices as $invoice) {

                $paid = (float) $invoice->paid_amount;

                $balance = round(
                    (float) $invoice->total_amount - $paid,
                    2
                );

                if ($balance <= 0) {

                    $invoice->update([
                        'paid_amount' => $invoice->total_amount,
                        'balance_amount' => 0,
                        'invoice_status' => 'Paid',
                    ]);

                } elseif (
                    $invoice->due_date &&
                    Carbon::parse($invoice->due_date)->isPast()
                ) {

                    $invoice->update([
                        'balance_amount' => $balance,
                        'invoice_status' => 'Overdue',
                    ]);

                } elseif ($paid > 0) {

                    $invoice->update([
                        'balance_amount' => $balance,
                        'invoice_status' => 'Partially Paid',
                    ]);

                } else {

                    $invoice->update([
                        'balance_amount' => $balance,
                        'invoice_status' => 'Generated',
                    ]);
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Update Rent Schedules
            |--------------------------------------------------------------------------
            */

            $schedules = RentSchedule::whereNotNull(
                'invoice_id'
            )->get();

            foreach ($schedules as $schedule) {

                $invoice = Invoice::find(
                    $schedule->invoice_id
                );

                if (!$invoice) {
                    continue;
                }

                if ((float) $invoice->balance_amount <= 0) {

                    $status = 'Paid';

                } elseif (
                    $schedule->due_date &&
                    Carbon::parse($schedule->due_date)->isPast()
                ) {

                    $status = 'Overdue';

                } elseif (
                    (float) $invoice->paid_amount > 0
                ) {

                    $status = 'Partial';

                } else {

                    $status = 'Pending';
                }

                $schedule->update([
                    'payment_status' => $status,
                ]);
            }
        });

        $this->info(
            'Invoice and rent schedule statuses updated.'
        );

        return Command::SUCCESS;
    }
}