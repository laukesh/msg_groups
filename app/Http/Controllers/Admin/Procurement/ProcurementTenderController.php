<?php

namespace App\Http\Controllers\Admin\Procurement;

use App\Http\Controllers\Controller;
use App\Models\ProcurementPackage;
use App\Models\ProcurementTender;
use App\Models\ProcurementPlan;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProcurementTenderController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Tender List
    |--------------------------------------------------------------------------
    */

    public function index(Request $request): View
    {
        $query = ProcurementTender::query()
            ->with([
                'procurementPackage.procurementPlan.project',
                'responsibleUser',
            ]);

        if ($request->filled('procurement_package_id')) {

            $query->where(
                'procurement_package_id',
                $request->procurement_package_id
            );
        }

        if ($request->filled('status')) {

            $query->where(
                'status',
                $request->status
            );
        }

        $tenders = $query
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        $packages = ProcurementPackage::query()
            ->with('procurementPlan.project')
            ->orderBy('package_number')
            ->get();

        return view(
            'procurement.tenders.index',
            compact(
                'tenders',
                'packages'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Create Tender
    |--------------------------------------------------------------------------
    */

    public function create(Request $request): View
    {
        $packages = ProcurementPackage::query()
            ->with('procurementPlan.project')
            ->whereIn(
                'status',
                [
                    'Draft',
                    'Planned',
                    'Tendering',
                ]
            )
            ->orderBy('package_number')
            ->get();

        $selectedPackageId = $request->integer(
            'procurement_package_id'
        );

        $users = User::query()
            ->orderBy('name')
            ->get();

        return view(
            'procurement.tenders.create',
            compact(
                'packages',
                'selectedPackageId',
                'users'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Store Tender
    |--------------------------------------------------------------------------
    */

    public function store(
        Request $request
    ): RedirectResponse {

        /*
        |--------------------------------------------------------------------------
        | Validate Form
        |--------------------------------------------------------------------------
        |
        | tender_number is intentionally NOT accepted from the user.
        |
        */

        $validated = $request->validate([

            'procurement_package_id' => [
                'required',
                'integer',
                'exists:procurement_packages,id',
            ],

            'tender_title' => [
                'required',
                'string',
                'max:255',
            ],

            'tender_type' => [
                'nullable',
                'string',
                'max:100',
            ],

            'procurement_method' => [
                'nullable',
                'string',
                'max:100',
            ],

            'estimated_value' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'currency' => [
                'required',
                'string',
                'max:10',
            ],

            'tender_fee' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'emd_amount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'issue_date' => [
                'nullable',
                'date',
            ],

            'submission_start_date' => [
                'nullable',
                'date',
            ],

            'submission_deadline' => [
                'nullable',
                'date',
                'after_or_equal:submission_start_date',
            ],

            'opening_date' => [
                'nullable',
                'date',
                'after_or_equal:submission_deadline',
            ],

            'technical_evaluation_date' => [
                'nullable',
                'date',
                'after_or_equal:opening_date',
            ],

            'commercial_evaluation_date' => [
                'nullable',
                'date',
                'after_or_equal:technical_evaluation_date',
            ],

            'planned_award_date' => [
                'nullable',
                'date',
                'after_or_equal:commercial_evaluation_date',
            ],

            'prequalification_required' => [
                'nullable',
                'boolean',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'scope_of_work' => [
                'nullable',
                'string',
            ],

            'terms_and_conditions' => [
                'nullable',
                'string',
            ],

            'responsible_user_id' => [
                'nullable',
                'integer',
                'exists:users,id',
            ],

            'responsible_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Generate Tender Number
        |--------------------------------------------------------------------------
        |
        | Example:
        |
        | TND-2026-0001
        | TND-2026-0002
        | TND-2026-0003
        |
        */

        $year = now()->format('Y');

        $lastTender = ProcurementTender::query()
            ->where(
                'tender_number',
                'like',
                'TND-' . $year . '-%'
            )
            ->orderByDesc('id')
            ->first();


        if ($lastTender) {

            $lastNumber = (int) str_replace(
                'TND-' . $year . '-',
                '',
                $lastTender->tender_number
            );

            $nextNumber = $lastNumber + 1;

        } else {

            $nextNumber = 1;
        }


        $tenderNumber =
            'TND-' .
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
        */

        while (
            ProcurementTender::query()
                ->where(
                    'tender_number',
                    $tenderNumber
                )
                ->exists()
        ) {

            $nextNumber++;

            $tenderNumber =
                'TND-' .
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
        | Create Tender
        |--------------------------------------------------------------------------
        */

        $tender = ProcurementTender::create([

            'procurement_package_id' =>
                $validated['procurement_package_id'],

            'tender_number' =>
                $tenderNumber,

            'tender_title' =>
                $validated['tender_title'],

            'tender_type' =>
                $validated['tender_type'] ?? null,

            'procurement_method' =>
                $validated['procurement_method'] ?? null,

            'estimated_value' =>
                $validated['estimated_value'] ?? 0,

            'currency' =>
                $validated['currency'],

            'tender_fee' =>
                $validated['tender_fee'] ?? 0,

            'emd_amount' =>
                $validated['emd_amount'] ?? 0,

            'issue_date' =>
                $validated['issue_date'] ?? null,

            'submission_start_date' =>
                $validated['submission_start_date'] ?? null,

            'submission_deadline' =>
                $validated['submission_deadline'] ?? null,

            'opening_date' =>
                $validated['opening_date'] ?? null,

            'technical_evaluation_date' =>
                $validated['technical_evaluation_date'] ?? null,

            'commercial_evaluation_date' =>
                $validated['commercial_evaluation_date'] ?? null,

            'planned_award_date' =>
                $validated['planned_award_date'] ?? null,

            'prequalification_required' =>
                $request->boolean(
                    'prequalification_required'
                ),

            'description' =>
                $validated['description'] ?? null,

            'scope_of_work' =>
                $validated['scope_of_work'] ?? null,

            'terms_and_conditions' =>
                $validated['terms_and_conditions'] ?? null,

            'status' =>
                'Draft',

            'responsible_user_id' =>
                $validated['responsible_user_id'] ?? null,

            'responsible_name' =>
                $validated['responsible_name'] ?? null,

            'remarks' =>
                $validated['remarks'] ?? null,

            'created_by' =>
                auth()->id(),
        ]);


        return redirect()
            ->route(
                'admin.procurement.tenders.show',
                $tender
            )
            ->with(
                'success',
                'Tender ' .
                $tender->tender_number .
                ' created successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Show Tender
    |--------------------------------------------------------------------------
    */

    public function show(
        ProcurementTender $procurementTender
    ): View {

        $procurementTender->load([
            'package.procurementPlan',
            'submissions',
            'technicalEvaluations',
            'commercialEvaluations',
            'bidComparisons',
        ]);

        return view(
            'procurement.tenders.show',
            compact(
                'procurementTender'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Edit Tender
    |--------------------------------------------------------------------------
    */

    public function edit(
        ProcurementTender $procurementTender
    ): View {

        if (
            !in_array(
                $procurementTender->status,
                [
                    'Draft',
                ],
                true
            )
        ) {

            abort(
                403,
                'This Tender can no longer be edited directly.'
            );
        }


        $packages = ProcurementPackage::query()
            ->with('procurementPlan.project')
            ->whereIn(
                'status',
                [
                    'Draft',
                    'Planned',
                    'Tendering',
                ]
            )
            ->orderBy('package_number')
            ->get();


        $users = User::query()
            ->orderBy('name')
            ->get();


        return view(
            'procurement.tenders.edit',
            compact(
                'procurementTender',
                'packages',
                'users'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Update Tender
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        ProcurementTender $procurementTender
    ): RedirectResponse {

        if (
            $procurementTender->status !== 'Draft'
        ) {

            return back()
                ->with(
                    'error',
                    'Only Draft Tenders can be edited.'
                );
        }


        $validated = $request->validate([

            'procurement_package_id' => [
                'required',
                'integer',
                'exists:procurement_packages,id',
            ],

            'tender_title' => [
                'required',
                'string',
                'max:255',
            ],

            'tender_type' => [
                'nullable',
                'string',
                'max:100',
            ],

            'procurement_method' => [
                'nullable',
                'string',
                'max:100',
            ],

            'estimated_value' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'currency' => [
                'required',
                'string',
                'max:10',
            ],

            'tender_fee' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'emd_amount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'issue_date' => [
                'nullable',
                'date',
            ],

            'submission_start_date' => [
                'nullable',
                'date',
            ],

            'submission_deadline' => [
                'nullable',
                'date',
                'after_or_equal:submission_start_date',
            ],

            'opening_date' => [
                'nullable',
                'date',
                'after_or_equal:submission_deadline',
            ],

            'technical_evaluation_date' => [
                'nullable',
                'date',
                'after_or_equal:opening_date',
            ],

            'commercial_evaluation_date' => [
                'nullable',
                'date',
                'after_or_equal:technical_evaluation_date',
            ],

            'planned_award_date' => [
                'nullable',
                'date',
                'after_or_equal:commercial_evaluation_date',
            ],

            'prequalification_required' => [
                'nullable',
                'boolean',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'scope_of_work' => [
                'nullable',
                'string',
            ],

            'terms_and_conditions' => [
                'nullable',
                'string',
            ],

            'responsible_user_id' => [
                'nullable',
                'integer',
                'exists:users,id',
            ],

            'responsible_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Update
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        | Tender number is NOT changed.
        |
        */

        $procurementTender->update([

            'procurement_package_id' =>
                $validated['procurement_package_id'],

            'tender_title' =>
                $validated['tender_title'],

            'tender_type' =>
                $validated['tender_type'] ?? null,

            'procurement_method' =>
                $validated['procurement_method'] ?? null,

            'estimated_value' =>
                $validated['estimated_value'] ?? 0,

            'currency' =>
                $validated['currency'],

            'tender_fee' =>
                $validated['tender_fee'] ?? 0,

            'emd_amount' =>
                $validated['emd_amount'] ?? 0,

            'issue_date' =>
                $validated['issue_date'] ?? null,

            'submission_start_date' =>
                $validated['submission_start_date'] ?? null,

            'submission_deadline' =>
                $validated['submission_deadline'] ?? null,

            'opening_date' =>
                $validated['opening_date'] ?? null,

            'technical_evaluation_date' =>
                $validated['technical_evaluation_date'] ?? null,

            'commercial_evaluation_date' =>
                $validated['commercial_evaluation_date'] ?? null,

            'planned_award_date' =>
                $validated['planned_award_date'] ?? null,

            'prequalification_required' =>
                $request->boolean(
                    'prequalification_required'
                ),

            'description' =>
                $validated['description'] ?? null,

            'scope_of_work' =>
                $validated['scope_of_work'] ?? null,

            'terms_and_conditions' =>
                $validated['terms_and_conditions'] ?? null,

            'responsible_user_id' =>
                $validated['responsible_user_id'] ?? null,

            'responsible_name' =>
                $validated['responsible_name'] ?? null,

            'remarks' =>
                $validated['remarks'] ?? null,

            'updated_by' =>
                auth()->id(),
        ]);


        return redirect()
            ->route(
                'admin.procurement.tenders.show',
                $procurementTender
            )
            ->with(
                'success',
                'Tender updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Delete Tender
    |--------------------------------------------------------------------------
    */

    public function destroy(
        ProcurementTender $procurementTender
    ): RedirectResponse {

        if (
            $procurementTender->status !== 'Draft'
        ) {

            return back()
                ->with(
                    'error',
                    'Only Draft Tenders can be deleted.'
                );
        }


        $procurementTender->delete();


        return redirect()
            ->route(
                'admin.procurement.tenders.index'
            )
            ->with(
                'success',
                'Tender deleted successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Package Tenders
    |--------------------------------------------------------------------------
    */

    public function packageTenders(
        ProcurementPackage $procurementPackage
    ): View {

        $procurementPackage->load([
            'procurementPlan.project',
        ]);


        $tenders = $procurementPackage
            ->tenders()
            ->latest('id')
            ->paginate(15);


        $packages = ProcurementPackage::query()
            ->with('procurementPlan.project')
            ->orderBy('package_number')
            ->get();


        return view(
            'procurement.tenders.index',
            compact(
                'procurementPackage',
                'tenders',
                'packages'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Plan Tenders
    |--------------------------------------------------------------------------
    */

    public function planTenders(
        ProcurementPlan $procurementPlan
    ): View {

        $procurementPlan->load([
            'project',
        ]);


        $tenders = $procurementPlan
            ->tenders()
            ->latest('id')
            ->paginate(15);


        $packages = ProcurementPackage::query()
            ->with('procurementPlan.project')
            ->orderBy('package_number')
            ->get();


        return view(
            'procurement.tenders.index',
            compact(
                'procurementPlan',
                'tenders',
                'packages'
            )
        );
    }
}