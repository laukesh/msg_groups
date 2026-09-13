<?php

namespace App\Http\Controllers\Admin\ContractManagement;

use App\Http\Controllers\Controller;
use App\Models\ContractExtensionOfTime;
use App\Models\ContractManagementContract;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ContractExtensionOfTimeController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Index
    |--------------------------------------------------------------------------
    */

    public function index(
        Project $project,
        ContractManagementContract $contract
    ): View {

        $this->validateContract(
            $project,
            $contract
        );


        $eots = ContractExtensionOfTime::query()

            ->where(
                'contract_management_contract_id',
                $contract->id
            )

            ->orderByDesc(
                'request_date'
            )

            ->orderByDesc(
                'id'
            )

            ->get();


        /*
        |--------------------------------------------------------------------------
        | Summary
        |--------------------------------------------------------------------------
        */

        $summary = [

            'total' =>
                $eots->count(),

            'pending' =>
                $eots->whereIn(
                    'status',
                    [
                        'Submitted',
                        'Under Review',
                        'Under Negotiation',
                    ]
                )->count(),

            'approved' =>
                $eots->whereIn(
                    'status',
                    [
                        'Approved',
                        'Partially Approved',
                    ]
                )->count(),

            'requested_days' =>
                (int) $eots->sum(
                    'requested_days'
                ),

            'approved_days' =>
                (int) $eots->sum(
                    'approved_days'
                ),
        ];


        /*
        |--------------------------------------------------------------------------
        | Current Completion Date
        |--------------------------------------------------------------------------
        */

        $currentCompletionDate =
            $this->getCurrentCompletionDate(
                $contract,
                $eots
            );


        return view(
            'contract-management.eot.index',
            compact(
                'project',
                'contract',
                'eots',
                'summary',
                'currentCompletionDate'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Create
    |--------------------------------------------------------------------------
    */

    public function create(
        Project $project,
        ContractManagementContract $contract
    ): View {

        $this->validateContract(
            $project,
            $contract
        );


        return view(
            'contract-management.eot.create',
            compact(
                'project',
                'contract'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Store
    |--------------------------------------------------------------------------
    */

    public function store(
        Request $request,
        Project $project,
        ContractManagementContract $contract
    ): RedirectResponse {

        $this->validateContract(
            $project,
            $contract
        );


        $validated = $request->validate([

            'request_date' =>
                'required|date',

            'reason_type' =>
                'required|string|max:100',

            'title' =>
                'required|string|max:255',

            'description' =>
                'nullable|string',

            'delay_start_date' =>
                'nullable|date',

            'delay_end_date' =>
                'nullable|date|after_or_equal:delay_start_date',

            'requested_days' =>
                'required|integer|min:0',

            'status' =>
                'required|string|max:50',

            'submitted_by_party' =>
                'nullable|string|max:255',

            'submission_date' =>
                'nullable|date',

            'response_due_date' =>
                'nullable|date',

            'review_remarks' =>
                'nullable|string',

            'decision_remarks' =>
                'nullable|string',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Generate EOT Number
        |--------------------------------------------------------------------------
        */

        $eotNumber =
            $this->generateEotNumber();


        /*
        |--------------------------------------------------------------------------
        | Original Completion Date
        |--------------------------------------------------------------------------
        */

        $originalCompletionDate =
            $contract->completion_date;


        /*
        |--------------------------------------------------------------------------
        | Defaults
        |--------------------------------------------------------------------------
        */

        $validated['contract_management_contract_id'] =
            $contract->id;

        $validated['eot_number'] =
            $eotNumber;

        $validated['original_completion_date'] =
            $originalCompletionDate;

        $validated['approved_days'] =
            0;

        $validated['revised_completion_date'] =
            null;

        $validated['created_by'] =
            Auth::id();

        $validated['updated_by'] =
            Auth::id();


        /*
        |--------------------------------------------------------------------------
        | Create EOT
        |--------------------------------------------------------------------------
        */

        ContractExtensionOfTime::create(
            $validated
        );


        return redirect()
            ->route(
                'admin.projects.contract-management.contracts.eot.index',
                [
                    $project,
                    $contract,
                ]
            )
            ->with(
                'success',
                'Extension of Time request created successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Edit
    |--------------------------------------------------------------------------
    */

    public function edit(
        Project $project,
        ContractManagementContract $contract,
        ContractExtensionOfTime $eot
    ): View {

        $this->validateContract(
            $project,
            $contract
        );

        $this->validateEot(
            $contract,
            $eot
        );


        return view(
            'contract-management.eot.edit',
            compact(
                'project',
                'contract',
                'eot'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Update
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        Project $project,
        ContractManagementContract $contract,
        ContractExtensionOfTime $eot
    ): RedirectResponse {

        $this->validateContract(
            $project,
            $contract
        );

        $this->validateEot(
            $contract,
            $eot
        );


        $validated = $request->validate([

            'request_date' =>
                'required|date',

            'reason_type' =>
                'required|string|max:100',

            'title' =>
                'required|string|max:255',

            'description' =>
                'nullable|string',

            'delay_start_date' =>
                'nullable|date',

            'delay_end_date' =>
                'nullable|date|after_or_equal:delay_start_date',

            'requested_days' =>
                'required|integer|min:0',

            'approved_days' =>
                'nullable|integer|min:0',

            'status' =>
                'required|string|max:50',

            'submitted_by_party' =>
                'nullable|string|max:255',

            'submission_date' =>
                'nullable|date',

            'response_due_date' =>
                'nullable|date',

            'decision_date' =>
                'nullable|date',

            'review_remarks' =>
                'nullable|string',

            'decision_remarks' =>
                'nullable|string',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Approved Days
        |--------------------------------------------------------------------------
        */

        $approvedDays =
            (int) (
                $validated['approved_days']
                ??
                0
            );


        /*
        |--------------------------------------------------------------------------
        | Business Rule
        |--------------------------------------------------------------------------
        |
        | Approved days cannot exceed requested days.
        |
        */

        if (
            $approvedDays >
            (int) $validated['requested_days']
        ) {

            return back()
                ->withInput()
                ->withErrors([
                    'approved_days' =>
                        'Approved days cannot exceed requested days.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Calculate Revised Completion Date
        |--------------------------------------------------------------------------
        */

        $revisedCompletionDate = null;


        if (
            in_array(
                $validated['status'],
                [
                    'Approved',
                    'Partially Approved',
                ],
                true
            )
            &&
            $approvedDays > 0
        ) {

            /*
            |--------------------------------------------------------------
            | IMPORTANT
            |--------------------------------------------------------------
            |
            | Each approved EOT is calculated from the previous
            | approved completion date, not always from the
            | original contract completion date.
            |
            */

            $baseDate =
                $this->getPreviousApprovedCompletionDate(
                    $contract,
                    $eot
                );


            $revisedCompletionDate =
                $baseDate->copy()
                    ->addDays(
                        $approvedDays
                    );
        }


        /*
        |--------------------------------------------------------------------------
        | Set Decision Date
        |--------------------------------------------------------------------------
        */

        if (
            in_array(
                $validated['status'],
                [
                    'Approved',
                    'Partially Approved',
                    'Rejected',
                ],
                true
            )
            &&
            empty(
                $validated['decision_date']
            )
        ) {

            $validated['decision_date'] =
                now()->format('Y-m-d');
        }


        $validated['approved_days'] =
            $approvedDays;

        $validated['revised_completion_date'] =
            $revisedCompletionDate;

        $validated['updated_by'] =
            Auth::id();


        /*
        |--------------------------------------------------------------------------
        | Update EOT
        |--------------------------------------------------------------------------
        */

        $eot->update(
            $validated
        );


        /*
        |--------------------------------------------------------------------------
        | Update Contract Completion Date
        |--------------------------------------------------------------------------
        |
        | Only approved EOT affects the contract's completion date.
        |
        */

        $this->updateContractCompletionDate(
            $contract
        );


        return redirect()
            ->route(
                'admin.projects.contract-management.contracts.eot.index',
                [
                    $project,
                    $contract,
                ]
            )
            ->with(
                'success',
                'Extension of Time updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Destroy
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Project $project,
        ContractManagementContract $contract,
        ContractExtensionOfTime $eot
    ): RedirectResponse {

        $this->validateContract(
            $project,
            $contract
        );

        $this->validateEot(
            $contract,
            $eot
        );


        $eot->delete();


        /*
        |--------------------------------------------------------------------------
        | Recalculate Contract Completion Date
        |--------------------------------------------------------------------------
        */

        $this->updateContractCompletionDate(
            $contract
        );


        return redirect()
            ->route(
                'admin.projects.contract-management.contracts.eot.index',
                [
                    $project,
                    $contract,
                ]
            )
            ->with(
                'success',
                'Extension of Time deleted successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Generate EOT Number
    |--------------------------------------------------------------------------
    */

    protected function generateEotNumber(): string
    {
        $lastId =
            ContractExtensionOfTime::max('id')
            ??
            0;

        return 'EOT-' .
            str_pad(
                $lastId + 1,
                6,
                '0',
                STR_PAD_LEFT
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Get Current Completion Date
    |--------------------------------------------------------------------------
    */

    protected function getCurrentCompletionDate(
        ContractManagementContract $contract,
        $eots
    ) {

        $currentDate =
            $contract->completion_date;


        /*
        |--------------------------------------------------------------------------
        | Recalculate from original date
        |--------------------------------------------------------------------------
        */

        if (!$currentDate) {

            return null;
        }


        /*
        |--------------------------------------------------------------------------
        | Important
        |--------------------------------------------------------------------------
        |
        | The stored contract completion date may already have been
        | updated. Therefore use the earliest original date from
        | approved EOT records when available.
        |
        */

        $approvedEots =
            $eots
                ->whereIn(
                    'status',
                    [
                        'Approved',
                        'Partially Approved',
                    ]
                )
                ->sortBy(
                    'id'
                );


        if (
            $approvedEots->count() === 0
        ) {

            return $currentDate;
        }


        $firstEot =
            $approvedEots->first();


        $baseDate =
            $firstEot->original_completion_date
            ??
            $currentDate;


        foreach (
            $approvedEots
            as $approvedEot
        ) {

            $baseDate =
                $baseDate->copy()
                    ->addDays(
                        (int)
                        $approvedEot->approved_days
                    );
        }


        return $baseDate;
    }


    /*
    |--------------------------------------------------------------------------
    | Get Previous Approved Completion Date
    |--------------------------------------------------------------------------
    */

    protected function getPreviousApprovedCompletionDate(
        ContractManagementContract $contract,
        ContractExtensionOfTime $currentEot
    ) {

        $previousEots =
            ContractExtensionOfTime::query()

                ->where(
                    'contract_management_contract_id',
                    $contract->id
                )

                ->where(
                    'id',
                    '<>',
                    $currentEot->id
                )

                ->whereIn(
                    'status',
                    [
                        'Approved',
                        'Partially Approved',
                    ]
                )

                ->orderBy(
                    'id'
                )

                ->get();


        /*
        |--------------------------------------------------------------------------
        | Base Completion Date
        |--------------------------------------------------------------------------
        */

        $baseDate =
            $currentEot->original_completion_date
            ??
            $contract->completion_date;


        if (!$baseDate) {

            abort(
                422,
                'Contract completion date is required before approving EOT.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Apply Previous EOTs
        |--------------------------------------------------------------------------
        */

        foreach (
            $previousEots
            as $previousEot
        ) {

            $baseDate =
                $baseDate->copy()
                    ->addDays(
                        (int)
                        $previousEot->approved_days
                    );
        }


        return $baseDate;
    }


    /*
    |--------------------------------------------------------------------------
    | Update Contract Completion Date
    |--------------------------------------------------------------------------
    */

    protected function updateContractCompletionDate(
        ContractManagementContract $contract
    ): void {

        $approvedEots =
            ContractExtensionOfTime::query()

                ->where(
                    'contract_management_contract_id',
                    $contract->id
                )

                ->whereIn(
                    'status',
                    [
                        'Approved',
                        'Partially Approved',
                    ]
                )

                ->orderBy(
                    'id'
                )

                ->get();


        /*
        |--------------------------------------------------------------------------
        | No Approved EOT
        |--------------------------------------------------------------------------
        */

        if (
            $approvedEots->isEmpty()
        ) {

            /*
            |--------------------------------------------------------------
            | Restore original completion date if available.
            |--------------------------------------------------------------
            */

            $firstEot =
                ContractExtensionOfTime::query()
                    ->where(
                        'contract_management_contract_id',
                        $contract->id
                    )
                    ->orderBy(
                        'id'
                    )
                    ->first();


            if (
                $firstEot &&
                $firstEot->original_completion_date
            ) {

                $contract->update([

                    'completion_date' =>
                        $firstEot
                            ->original_completion_date,

                ]);
            }


            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Original Completion Date
        |--------------------------------------------------------------------------
        */

        $firstEot =
            $approvedEots->first();


        $completionDate =
            $firstEot->original_completion_date
            ??
            $contract->completion_date;


        if (!$completionDate) {

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Apply All Approved EOTs
        |--------------------------------------------------------------------------
        */

        foreach (
            $approvedEots
            as $approvedEot
        ) {

            $completionDate =
                $completionDate->copy()
                    ->addDays(
                        (int)
                        $approvedEot->approved_days
                    );
        }


        /*
        |--------------------------------------------------------------------------
        | Update Contract
        |--------------------------------------------------------------------------
        */

        $contract->update([

            'completion_date' =>
                $completionDate,

        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Validate Contract
    |--------------------------------------------------------------------------
    */

    protected function validateContract(
        Project $project,
        ContractManagementContract $contract
    ): void {

        if (
            (int) $contract->project_id !==
            (int) $project->id
        ) {

            abort(
                404,
                'Contract does not belong to this project.'
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Validate EOT
    |--------------------------------------------------------------------------
    */

    protected function validateEot(
        ContractManagementContract $contract,
        ContractExtensionOfTime $eot
    ): void {

        if (
            (int)
            $eot->contract_management_contract_id
            !==
            (int) $contract->id
        ) {

            abort(
                404,
                'EOT does not belong to this contract.'
            );
        }
    }
}