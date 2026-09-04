<?php

namespace App\Http\Controllers\Admin\Procurement;

use App\Http\Controllers\Controller;
use App\Models\ProcurementBidder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProcurementBidderController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Index
    |--------------------------------------------------------------------------
    */

    public function index(Request $request): View
    {
        $query = ProcurementBidder::query();

        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->where(
                    'bidder_code',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'company_name',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'gst_number',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'pan_number',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'email',
                    'like',
                    "%{$search}%"
                );

            });
        }

        if ($request->filled('status')) {

            $query->where(
                'status',
                $request->status
            );
        }

        $bidders = $query
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        return view(
            'procurement.bidders.index',
            compact('bidders')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Create
    |--------------------------------------------------------------------------
    */

    public function create(): View
    {
        return view(
            'procurement.bidders.create'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Store
    |--------------------------------------------------------------------------
    */

    public function store(
        Request $request
    ): RedirectResponse {

        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        |
        | bidder_code is intentionally NOT accepted from the form.
        |
        */

        $validated = $request->validate([

            'company_name' => [
                'required',
                'string',
                'max:255',
            ],

            'company_registration_no' => [
                'nullable',
                'string',
                'max:100',
            ],

            'gst_number' => [
                'nullable',
                'string',
                'max:50',
            ],

            'pan_number' => [
                'nullable',
                'string',
                'max:50',
            ],

            'contact_person' => [
                'nullable',
                'string',
                'max:255',
            ],

            'email' => [
                'nullable',
                'email',
                'max:255',
            ],

            'phone' => [
                'nullable',
                'string',
                'max:50',
            ],

            'address' => [
                'nullable',
                'string',
            ],

            'city' => [
                'nullable',
                'string',
                'max:100',
            ],

            'state' => [
                'nullable',
                'string',
                'max:100',
            ],

            'country' => [
                'nullable',
                'string',
                'max:100',
            ],

            'postal_code' => [
                'nullable',
                'string',
                'max:20',
            ],

            'status' => [
                'required',
                'string',
                'max:50',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Generate Bidder Code
        |--------------------------------------------------------------------------
        |
        | Example:
        |
        | BID-2026-0001
        | BID-2026-0002
        | BID-2026-0003
        |
        */

        $year = now()->format('Y');

        $lastBidder = ProcurementBidder::query()
            ->where(
                'bidder_code',
                'like',
                'BID-' . $year . '-%'
            )
            ->orderByDesc('id')
            ->first();


        if ($lastBidder) {

            $lastNumber = (int) str_replace(
                'BID-' . $year . '-',
                '',
                $lastBidder->bidder_code
            );

            $nextNumber = $lastNumber + 1;

        } else {

            $nextNumber = 1;
        }


        $bidderCode =
            'BID-' .
            $year .
            '-' .
            str_pad(
                $nextNumber,
                4,
                '0',
                STR_PAD_LEFT
            );


        /*
        |--------------------------------------------------------------------------
        | Safety Check
        |--------------------------------------------------------------------------
        |
        | Prevent accidental duplicate codes.
        |
        */

        while (
            ProcurementBidder::query()
                ->where(
                    'bidder_code',
                    $bidderCode
                )
                ->exists()
        ) {

            $nextNumber++;

            $bidderCode =
                'BID-' .
                $year .
                '-' .
                str_pad(
                    $nextNumber,
                    4,
                    '0',
                    STR_PAD_LEFT
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Create Bidder
        |--------------------------------------------------------------------------
        */

        $bidder = ProcurementBidder::create([

            'bidder_code' =>
                $bidderCode,

            'company_name' =>
                $validated['company_name'],

            'company_registration_no' =>
                $validated['company_registration_no']
                ?? null,

            'gst_number' =>
                $validated['gst_number']
                ?? null,

            'pan_number' =>
                $validated['pan_number']
                ?? null,

            'contact_person' =>
                $validated['contact_person']
                ?? null,

            'email' =>
                $validated['email']
                ?? null,

            'phone' =>
                $validated['phone']
                ?? null,

            'address' =>
                $validated['address']
                ?? null,

            'city' =>
                $validated['city']
                ?? null,

            'state' =>
                $validated['state']
                ?? null,

            'country' =>
                $validated['country']
                ?? 'India',

            'postal_code' =>
                $validated['postal_code']
                ?? null,

            'status' =>
                $validated['status'],

            'remarks' =>
                $validated['remarks']
                ?? null,

            'created_by' =>
                auth()->id(),
        ]);


        return redirect()
            ->route(
                'admin.procurement.bidders.show',
                $bidder
            )
            ->with(
                'success',
                'Bidder ' .
                $bidder->bidder_code .
                ' created successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Show
    |--------------------------------------------------------------------------
    */

    public function show(
        ProcurementBidder $procurementBidder
    ): View {

        $procurementBidder->load([
            'tenderBidders.tender.procurementPackage',
        ]);

        return view(
            'procurement.bidders.show',
            compact('procurementBidder')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Edit
    |--------------------------------------------------------------------------
    */

    public function edit(
        ProcurementBidder $procurementBidder
    ): View {

        return view(
            'procurement.bidders.edit',
            compact('procurementBidder')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Update
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        ProcurementBidder $procurementBidder
    ): RedirectResponse {

        $validated = $request->validate([

            /*
            |--------------------------------------------------------------------------
            | bidder_code intentionally removed
            |--------------------------------------------------------------------------
            |
            | Existing bidder code must never change.
            |
            */

            'company_name' => [
                'required',
                'string',
                'max:255',
            ],

            'company_registration_no' => [
                'nullable',
                'string',
                'max:100',
            ],

            'gst_number' => [
                'nullable',
                'string',
                'max:50',
            ],

            'pan_number' => [
                'nullable',
                'string',
                'max:50',
            ],

            'contact_person' => [
                'nullable',
                'string',
                'max:255',
            ],

            'email' => [
                'nullable',
                'email',
                'max:255',
            ],

            'phone' => [
                'nullable',
                'string',
                'max:50',
            ],

            'address' => [
                'nullable',
                'string',
            ],

            'city' => [
                'nullable',
                'string',
                'max:100',
            ],

            'state' => [
                'nullable',
                'string',
                'max:100',
            ],

            'country' => [
                'nullable',
                'string',
                'max:100',
            ],

            'postal_code' => [
                'nullable',
                'string',
                'max:20',
            ],

            'status' => [
                'required',
                'string',
                'max:50',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Update Bidder
        |--------------------------------------------------------------------------
        |
        | bidder_code is deliberately not included.
        |
        */

        $procurementBidder->update([

            'company_name' =>
                $validated['company_name'],

            'company_registration_no' =>
                $validated['company_registration_no']
                ?? null,

            'gst_number' =>
                $validated['gst_number']
                ?? null,

            'pan_number' =>
                $validated['pan_number']
                ?? null,

            'contact_person' =>
                $validated['contact_person']
                ?? null,

            'email' =>
                $validated['email']
                ?? null,

            'phone' =>
                $validated['phone']
                ?? null,

            'address' =>
                $validated['address']
                ?? null,

            'city' =>
                $validated['city']
                ?? null,

            'state' =>
                $validated['state']
                ?? null,

            'country' =>
                $validated['country']
                ?? 'India',

            'postal_code' =>
                $validated['postal_code']
                ?? null,

            'status' =>
                $validated['status'],

            'remarks' =>
                $validated['remarks']
                ?? null,

            'updated_by' =>
                auth()->id(),
        ]);


        return redirect()
            ->route(
                'admin.procurement.bidders.show',
                $procurementBidder
            )
            ->with(
                'success',
                'Bidder updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Destroy
    |--------------------------------------------------------------------------
    */

    public function destroy(
        ProcurementBidder $procurementBidder
    ): RedirectResponse {

        if (
            $procurementBidder
                ->tenderBidders()
                ->exists()
        ) {

            return back()->with(
                'error',
                'This bidder is already linked to a Tender and cannot be deleted.'
            );
        }


        $procurementBidder->delete();


        return redirect()
            ->route(
                'admin.procurement.bidders.index'
            )
            ->with(
                'success',
                'Bidder deleted successfully.'
            );
    }
}