<?php

namespace App\Http\Controllers\Admin\Revenue;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\LeaseAgreement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DepositController extends Controller
{
    /**
     * Display deposits.
     */
    public function index()
    {
        $deposits = Deposit::with('leaseAgreement')
            ->orderByDesc('id')
            ->get();

        $leaseAgreements = LeaseAgreement::whereIn(
                'agreement_status',
                ['Active', 'Renewed']
            )
            ->orderBy('agreement_no')
            ->get();

        return view(
            'admin.revenue.deposits.index',
            compact(
                'deposits',
                'leaseAgreements'
            )
        );
    }


    /**
     * Store deposit.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([

            'lease_agreement_id' => [
                'required',
                'exists:lease_agreements,id',
            ],

            'deposit_type' => [
                'required',
                'in:Security Deposit,Additional Deposit,Utility Deposit',
            ],

            'deposit_amount' => [
                'required',
                'numeric',
                'min:0.01',
            ],

            'due_date' => [
                'nullable',
                'date',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Validate Lease Agreement
        |--------------------------------------------------------------------------
        */

        $leaseAgreement = LeaseAgreement::findOrFail(
            $validated['lease_agreement_id']
        );


        if (!in_array(
            $leaseAgreement->agreement_status,
            ['Active', 'Renewed']
        )) {

            return back()
                ->withInput()
                ->withErrors([
                    'lease_agreement_id' =>
                        'Deposit can only be created for an Active or Renewed lease agreement.'
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Create Deposit
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use (
            $validated,
            $leaseAgreement
        ) {

            Deposit::create([

                'uuid' =>
                    (string) Str::uuid(),

                'lease_agreement_id' =>
                    $leaseAgreement->id,

                'deposit_type' =>
                    $validated['deposit_type'],

                'deposit_amount' =>
                    $validated['deposit_amount'],

                'received_amount' =>
                    0,

                'balance_amount' =>
                    $validated['deposit_amount'],

                'due_date' =>
                    $validated['due_date'] ?? null,

                'payment_status' =>
                    'Pending',

                'refundable_amount' =>
                    0,

                'remarks' =>
                    $validated['remarks'] ?? null,

                'created_by' =>
                    Auth::id(),

                'updated_by' =>
                    Auth::id(),
            ]);
        });


        return redirect()
            ->route(
                'admin.revenue.deposits.index'
            )
            ->with(
                'success',
                'Deposit created successfully.'
            );
    }


    /**
     * Show edit form.
     */
    public function edit($id)
    {
        $deposit = Deposit::findOrFail($id);

        $leaseAgreements = LeaseAgreement::whereIn(
                'agreement_status',
                ['Active', 'Renewed']
            )
            ->orderBy('agreement_no')
            ->get();

        return view(
            'admin.revenue.deposits.edit',
            compact(
                'deposit',
                'leaseAgreements'
            )
        );
    }


    /**
     * Update deposit.
     */
    public function update(
        Request $request,
        $id
    ) {

        $deposit = Deposit::findOrFail($id);


        $validated = $request->validate([

            'lease_agreement_id' => [
                'required',
                'exists:lease_agreements,id',
            ],

            'deposit_type' => [
                'required',
                'in:Security Deposit,Additional Deposit,Utility Deposit',
            ],

            'deposit_amount' => [
                'required',
                'numeric',
                'min:0.01',
            ],

            'due_date' => [
                'nullable',
                'date',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],
        ]);


        $leaseAgreement = LeaseAgreement::findOrFail(
            $validated['lease_agreement_id']
        );


        if (!in_array(
            $leaseAgreement->agreement_status,
            ['Active', 'Renewed']
        )) {

            return back()
                ->withInput()
                ->withErrors([
                    'lease_agreement_id' =>
                        'Deposit can only be assigned to an Active or Renewed lease agreement.'
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Do Not Allow Amount Changes After Receipt
        |--------------------------------------------------------------------------
        |
        | Once money has been received, changing the required
        | deposit amount can make the financial history inconsistent.
        |
        */

        if (
            (float) $deposit->received_amount > 0 &&
            (float) $validated['deposit_amount']
                != (float) $deposit->deposit_amount
        ) {

            return back()
                ->withInput()
                ->withErrors([
                    'deposit_amount' =>
                        'Deposit amount cannot be changed after a payment has been received.'
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Update
        |--------------------------------------------------------------------------
        */

        $deposit->update([

            'lease_agreement_id' =>
                $validated['lease_agreement_id'],

            'deposit_type' =>
                $validated['deposit_type'],

            'deposit_amount' =>
                $validated['deposit_amount'],

            'due_date' =>
                $validated['due_date'] ?? null,

            'remarks' =>
                $validated['remarks'] ?? null,

            'updated_by' =>
                Auth::id(),
        ]);


        /*
        |--------------------------------------------------------------------------
        | Recalculate Balance
        |--------------------------------------------------------------------------
        */

        $deposit->balance_amount =
            max(
                0,
                (float) $deposit->deposit_amount
                - (float) $deposit->received_amount
            );


        if (
            (float) $deposit->received_amount <= 0
        ) {

            $deposit->payment_status = 'Pending';

        } elseif (
            (float) $deposit->received_amount
            < (float) $deposit->deposit_amount
        ) {

            $deposit->payment_status = 'Partial';

        } else {

            $deposit->payment_status = 'Paid';
        }


        $deposit->save();


        return redirect()
            ->route(
                'admin.revenue.deposits.index'
            )
            ->with(
                'success',
                'Deposit updated successfully.'
            );
    }


    /**
     * Delete deposit.
     */
    public function destroy($id)
    {
        $deposit = Deposit::findOrFail($id);


        /*
        |--------------------------------------------------------------------------
        | Prevent Delete After Payment
        |--------------------------------------------------------------------------
        */

        if (
            (float) $deposit->received_amount > 0
        ) {

            return back()
                ->with(
                    'error',
                    'A deposit with received payment cannot be deleted.'
                );
        }


        $deposit->updated_by = Auth::id();

        $deposit->save();

        $deposit->delete();


        return redirect()
            ->route(
                'admin.revenue.deposits.index'
            )
            ->with(
                'success',
                'Deposit deleted successfully.'
            );
    }
}
