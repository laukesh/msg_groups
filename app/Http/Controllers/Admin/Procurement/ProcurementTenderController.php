<?php

namespace App\Http\Controllers\Admin\Procurement;

use App\Http\Controllers\Controller;
use App\Models\ProcurementPackage;
use App\Models\ProcurementTender;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\ProcurementAward;
use App\Models\ProcurementContract;
use App\Models\ProcurementPurchaseOrder;
use App\Models\ProcurementDelivery;
use App\Models\ProcurementMaterialTracking;
use App\Models\ProcurementContractInvoice;
use App\Models\ProcurementContractPayment;

class ProcurementTenderController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Tender List
    |--------------------------------------------------------------------------
    */

    public function index(
        Project $project,
        Request $request
    ): View {

        $query = ProcurementTender::query()
            ->where('project_id', $project->id)
            ->with([
                'project',
                'procurementPackage.procurementPlan',
                'responsibleUser',
            ]);

        /*
        |--------------------------------------------------------------------------
        | Package Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('procurement_package_id')) {

            $query->where(
                'procurement_package_id',
                $request->procurement_package_id
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Status Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {

            $query->where(
                'status',
                $request->status
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = trim(
                $request->search
            );

            $query->where(function ($q) use ($search) {

                $q->where(
                    'tender_number',
                    'like',
                    '%' . $search . '%'
                )
                ->orWhere(
                    'tender_title',
                    'like',
                    '%' . $search . '%'
                );
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Tenders
        |--------------------------------------------------------------------------
        */

        $tenders = $query
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Only Packages Of This Project
        |--------------------------------------------------------------------------
        */

        $packages = ProcurementPackage::query()
            ->whereHas(
                'procurementPlan',
                function ($query) use ($project) {

                    $query->where(
                        'project_id',
                        $project->id
                    );
                }
            )
            ->with([
                'procurementPlan',
            ])
            ->orderBy('package_number')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Statistics
        |--------------------------------------------------------------------------
        */

        $baseQuery = ProcurementTender::query()
            ->where(
                'project_id',
                $project->id
            );

        $totalTenders = (clone $baseQuery)->count();

        $draftTenders = (clone $baseQuery)
            ->where('status', 'Draft')
            ->count();

        $publishedTenders = (clone $baseQuery)
            ->where('status', 'Published')
            ->count();

        $openTenders = (clone $baseQuery)
            ->where('status', 'Open')
            ->count();

        $evaluationTenders = (clone $baseQuery)
            ->where('status', 'Under Evaluation')
            ->count();

        $awardedTenders = (clone $baseQuery)
            ->where('status', 'Awarded')
            ->count();

        return view(
            'procurement.tenders.index',
            compact(
                'project',
                'tenders',
                'packages',
                'totalTenders',
                'draftTenders',
                'publishedTenders',
                'openTenders',
                'evaluationTenders',
                'awardedTenders'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Create Tender
    |--------------------------------------------------------------------------
    */

    public function create(
        Project $project,
        Request $request
    ): View {

        /*
        |--------------------------------------------------------------------------
        | Only Packages Belonging To This Project
        |--------------------------------------------------------------------------
        */

        $packages = ProcurementPackage::query()
            ->whereHas(
                'procurementPlan',
                function ($query) use ($project) {

                    $query->where(
                        'project_id',
                        $project->id
                    );
                }
            )
            ->whereIn(
                'status',
                [
                    'Draft',
                    'Planned',
                    'Tendering',
                ]
            )
            ->with([
                'procurementPlan',
            ])
            ->orderBy('package_number')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Selected Package
        |--------------------------------------------------------------------------
        */

        $selectedPackageId = $request->integer(
            'procurement_package_id'
        );

        /*
        |--------------------------------------------------------------------------
        | Validate Selected Package
        |--------------------------------------------------------------------------
        */

        if (
            $selectedPackageId &&
            !$packages->contains(
                'id',
                $selectedPackageId
            )
        ) {

            $selectedPackageId = null;
        }

        /*
        |--------------------------------------------------------------------------
        | Users
        |--------------------------------------------------------------------------
        */

        $users = User::query()
            ->orderBy('name')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Next Tender Number
        |--------------------------------------------------------------------------
        */

        $year = now()->format('Y');

        $lastTender = ProcurementTender::query()
            ->where(
                'project_id',
                $project->id
            )
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

        return view(
            'procurement.tenders.create',
            compact(
                'project',
                'packages',
                'selectedPackageId',
                'users',
                'tenderNumber'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Store Tender
    |--------------------------------------------------------------------------
    */

    public function store(
        Project $project,
        Request $request
    ): RedirectResponse {

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
        | STRICT PROJECT → PACKAGE VALIDATION
        |--------------------------------------------------------------------------
        */

        $package = ProcurementPackage::query()
            ->where('id', $validated['procurement_package_id'])
            ->whereHas(
                'procurementPlan',
                function ($query) use ($project) {

                    $query->where(
                        'project_id',
                        $project->id
                    );
                }
            )
            ->first();

        if (!$package) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'The selected Procurement Package does not belong to this Project.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Generate Tender Number
        |--------------------------------------------------------------------------
        */

        $year = now()->format('Y');

        $lastTender = ProcurementTender::query()
            ->where(
                'project_id',
                $project->id
            )
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

        do {

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

            $exists = ProcurementTender::query()
                ->where(
                    'project_id',
                    $project->id
                )
                ->where(
                    'tender_number',
                    $tenderNumber
                )
                ->exists();

            $nextNumber++;

        } while ($exists);

        /*
        |--------------------------------------------------------------------------
        | Create Tender
        |--------------------------------------------------------------------------
        */

        $tender = ProcurementTender::create([

            /*
            | IMPORTANT:
            | Project ID comes from route.
            | Never trust project_id from form.
            */
            'project_id' => $project->id,

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
                'admin.projects.procurement.tenders.show',
                [
                    'project' => $project,
                    'procurementTender' => $tender,
                ]
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
        Project $project,
        ProcurementTender $procurementTender
    ): View {

        $this->validateTenderProject(
            $project,
            $procurementTender
        );

        $procurementTender->load([
            'project',

            'package.procurementPlan',

            'submissions',

            'technicalEvaluations',

            'commercialEvaluations',

            'bidComparisons',

            'responsibleUser',
        ]);


        /*
        |--------------------------------------------------------------------------
        | AWARD
        |--------------------------------------------------------------------------
        */

        $award = ProcurementAward::query()
            ->where('procurement_tender_id', $procurementTender->id)
            ->latest('id')
            ->first();

        $awardValue = (float) (
            $award?->awarded_amount ?? 0
        );


        /*
        |--------------------------------------------------------------------------
        | CONTRACTS
        |--------------------------------------------------------------------------
        */

        $contractQuery = ProcurementContract::query()
            ->where('project_id', $project->id)
            ->where(
                'procurement_tender_id',
                $procurementTender->id
            );

        $contractCount = (clone $contractQuery)->count();

        $contractValue = (float) (clone $contractQuery)
            ->sum('contract_amount');


        /*
        |--------------------------------------------------------------------------
        | PURCHASE ORDERS
        |--------------------------------------------------------------------------
        */

        $purchaseOrderQuery = ProcurementPurchaseOrder::query()
            ->where('project_id', $project->id)
            ->where(
                'procurement_tender_id',
                $procurementTender->id
            );

        $purchaseOrderCount = (clone $purchaseOrderQuery)->count();

        $purchaseOrderValue = (float) (clone $purchaseOrderQuery)
            ->sum('total_amount');


        /*
        |--------------------------------------------------------------------------
        | DELIVERIES
        |--------------------------------------------------------------------------
        |
        | Deliveries belong to Purchase Orders.
        |
        */

        $deliveryCount = ProcurementDelivery::query()
            ->where('project_id', $project->id)
            ->whereHas('purchaseOrder', function ($query) use (
                $project,
                $procurementTender
            ) {
                $query
                    ->where('project_id', $project->id)
                    ->where(
                        'procurement_tender_id',
                        $procurementTender->id
                    );
            })
            ->count();


        /*
        |--------------------------------------------------------------------------
        | MATERIAL TRACKING
        |--------------------------------------------------------------------------
        */

        $materialTrackingCount =
            ProcurementMaterialTracking::query()
                ->where('project_id', $project->id)
                ->where(
                    'procurement_tender_id',
                    $procurementTender->id
                )
                ->count();


        /*
        |--------------------------------------------------------------------------
        | INVOICES
        |--------------------------------------------------------------------------
        */

        $invoiceCount = ProcurementContractInvoice::query()
            ->where('project_id', $project->id)
            ->whereHas('contract', function ($query) use (
                $project,
                $procurementTender
            ) {
                $query
                    ->where('project_id', $project->id)
                    ->where(
                        'procurement_tender_id',
                        $procurementTender->id
                    );
            })
            ->count();

        $totalInvoiceValue = ProcurementContractInvoice::query()
        ->where('project_id', $project->id)
        ->whereHas('contract', function ($query) use (
            $project,
            $procurementTender
        ) {
            $query
                ->where('project_id', $project->id)
                ->where(
                    'procurement_tender_id',
                    $procurementTender->id
                );
        })
        ->sum('net_amount');


        /*
        |--------------------------------------------------------------------------
        | PAYMENTS
        |--------------------------------------------------------------------------
        */

        $paymentQuery = ProcurementContractPayment::query()
            ->where('project_id', $project->id)
            ->whereHas('contract', function ($query) use (
                $project,
                $procurementTender
            ) {
                $query
                    ->where('project_id', $project->id)
                    ->where(
                        'procurement_tender_id',
                        $procurementTender->id
                    );
            });

        $paymentCount = (clone $paymentQuery)->count();

        $paidAmount = (float) (clone $paymentQuery)
            ->whereIn('status', [
                'Approved',
                'Processed',
                'Paid',
            ])
            ->sum('amount');

        $totalInvoiceValue = (float) $totalInvoiceValue;

        $remainingToPay = max(
            0,
            $totalInvoiceValue - $paidAmount
        );

        $paymentPercentage = $totalInvoiceValue > 0
            ? min(
                100,
                ($paidAmount / $totalInvoiceValue) * 100
            )
            : 0;


        /*
        |--------------------------------------------------------------------------
        | OUTSTANDING
        |--------------------------------------------------------------------------
        */

        $baseValue = $contractValue > 0
            ? $contractValue
            : $purchaseOrderValue;

        $outstandingAmount = max(
            0,
            $baseValue - $paidAmount
        );


        return view(
            'procurement.tenders.show',
            compact(
                'project',
                'procurementTender',

                // Commercial Summary
                'awardValue',
                'contractValue',
                'purchaseOrderValue',
                'paidAmount',
                'outstandingAmount',

                // Execution Summary
                'purchaseOrderCount',
                'deliveryCount',
                'materialTrackingCount',
                'contractCount',
                'invoiceCount',
                'paymentCount',
                'totalInvoiceValue',
                'remainingToPay',
                'paymentPercentage'
            )
        );
    }
        


    /*
    |--------------------------------------------------------------------------
    | Edit Tender
    |--------------------------------------------------------------------------
    */

    public function edit(
        Project $project,
        ProcurementTender $procurementTender
    ): View {

        $this->validateTenderProject(
            $project,
            $procurementTender
        );

        if (
            $procurementTender->status !== 'Draft'
        ) {

            abort(
                403,
                'This Tender can no longer be edited directly.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Only Project Packages
        |--------------------------------------------------------------------------
        */

        $packages = ProcurementPackage::query()
            ->whereHas(
                'procurementPlan',
                function ($query) use ($project) {

                    $query->where(
                        'project_id',
                        $project->id
                    );
                }
            )
            ->whereIn(
                'status',
                [
                    'Draft',
                    'Planned',
                    'Tendering',
                ]
            )
            ->with([
                'procurementPlan',
            ])
            ->orderBy('package_number')
            ->get();

        $users = User::query()
            ->orderBy('name')
            ->get();

        return view(
            'procurement.tenders.edit',
            compact(
                'project',
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
        Project $project,
        Request $request,
        ProcurementTender $procurementTender
    ): RedirectResponse {

        $this->validateTenderProject(
            $project,
            $procurementTender
        );

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
        | STRICT PROJECT → PACKAGE VALIDATION
        |--------------------------------------------------------------------------
        */

        $packageBelongsToProject =
            ProcurementPackage::query()
                ->where(
                    'id',
                    $validated['procurement_package_id']
                )
                ->whereHas(
                    'procurementPlan',
                    function ($query) use ($project) {

                        $query->where(
                            'project_id',
                            $project->id
                        );
                    }
                )
                ->exists();

        if (!$packageBelongsToProject) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'The selected Procurement Package does not belong to this Project.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Update
        |--------------------------------------------------------------------------
        |
        | project_id is intentionally NOT changed.
        | tender_number is intentionally NOT changed.
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
                'admin.projects.procurement.tenders.show',
                [
                    'project' => $project,
                    'procurementTender' => $procurementTender,
                ]
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
        Project $project,
        ProcurementTender $procurementTender
    ): RedirectResponse {

        $this->validateTenderProject(
            $project,
            $procurementTender
        );

        if (
            $procurementTender->status !== 'Draft'
        ) {

            return back()
                ->with(
                    'error',
                    'Only Draft Tenders can be deleted.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Protect Downstream Data
        |--------------------------------------------------------------------------
        */

        if (
            $procurementTender->submissions()->exists() ||
            $procurementTender->technicalEvaluations()->exists() ||
            $procurementTender->commercialEvaluations()->exists() ||
            $procurementTender->bidComparisons()->exists() ||
            $procurementTender->negotiations()->exists() ||
            $procurementTender->awards()->exists()
        ) {

            return back()
                ->with(
                    'error',
                    'This Tender cannot be deleted because downstream procurement records already exist.'
                );
        }

        $procurementTender->delete();

        return redirect()
            ->route(
                'admin.projects.procurement.tenders.index',
                $project
            )
            ->with(
                'success',
                'Tender deleted successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Validate Tender Belongs To Project
    |--------------------------------------------------------------------------
    */

    private function validateTenderProject(
        Project $project,
        ProcurementTender $procurementTender
    ): void {

        if (
            (int) $procurementTender->project_id !==
            (int) $project->id
        ) {

            abort(
                404,
                'Tender does not belong to this Project.'
            );
        }
    }
}