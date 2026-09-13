<?php

namespace App\Http\Controllers\Admin\Handover;

use App\Http\Controllers\Controller;
use App\Models\HandoverDefect;
use App\Models\HandoverDocument;
use App\Models\HandoverFinalAccount;
use App\Models\HandoverFinalCompletion;
use App\Models\HandoverFinalPayment;
use App\Models\HandoverPracticalCompletion;
use App\Models\HandoverProject;
use App\Models\Project;
use App\Services\CommissioningAuditLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class HandoverFinalCompletionController extends Controller
{
    /**
     * Final Completion dashboard.
     */
    public function index(Project $project): View|RedirectResponse
    {
        $handover = $this->getHandover($project);

        if (!$handover) {
            return redirect()
                ->route(
                    'admin.projects.handover.index',
                    $project
                )
                ->with(
                    'error',
                    'Please start Handover & Closeout first.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Get / create Final Completion record
        |--------------------------------------------------------------------------
        */

        $completion = HandoverFinalCompletion::where(
            'handover_project_id',
            $handover->id
        )
            ->latest('id')
            ->first();

        /*
         * Create Final Completion record automatically
         * when the module is opened for the first time.
         */
        if (!$completion) {

            $completion = HandoverFinalCompletion::create([
                'project_id' => $project->id,
                'handover_project_id' => $handover->id,
                'completion_no' => $this->generateCompletionNo(
                    $project
                ),
                'status' => 'Not Ready',
                'readiness_percentage' => 0,
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
            ]);

            CommissioningAuditLogService::created(
                $completion,
                'Final Completion record created.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Always refresh system readiness
        |--------------------------------------------------------------------------
        */

        $this->evaluateReadiness($completion);

        $completion->refresh();

        /*
        |--------------------------------------------------------------------------
        | Load related records for dashboard
        |--------------------------------------------------------------------------
        */

        $practicalCompletion =
            HandoverPracticalCompletion::where(
                'handover_project_id',
                $handover->id
            )
                ->latest('id')
                ->first();

        $defects = HandoverDefect::where(
            'project_id',
            $project->id
        )
            ->where(
                'handover_project_id',
                $handover->id
            )
            ->get();

        $documents = HandoverDocument::where(
            'project_id',
            $project->id
        )
            ->where(
                'handover_project_id',
                $handover->id
            )
            ->get();

        $requirements = $handover->requirements()
            ->orderBy('id')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Dashboard counts
        |--------------------------------------------------------------------------
        */

        $closedDefects = $defects
            ->where('status', 'Closed')
            ->count();

        $totalDefects = $defects->count();

        $approvedDocuments = $documents
            ->where('status', 'Approved')
            ->count();

        $totalDocuments = $documents->count();

        /*
        |--------------------------------------------------------------------------
        | Final Account summary
        |--------------------------------------------------------------------------
        */

        $finalAccounts = HandoverFinalAccount::query()
            ->where(
                'project_id',
                $project->id
            )
            ->where(
                'handover_project_id',
                $handover->id
            )
            ->with([
                'procurementContract.bidder',
                'payment',
            ])
            ->get();

        $totalFinalAccounts = $finalAccounts->count();

        $approvedFinalAccounts = $finalAccounts
            ->where('status', 'Approved')
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Final Payment summary
        |--------------------------------------------------------------------------
        */

        $totalFinalPayments = HandoverFinalPayment::query()
            ->where(
                'project_id',
                $project->id
            )
            ->where(
                'handover_project_id',
                $handover->id
            )
            ->count();

        $paidFinalPayments = HandoverFinalPayment::query()
            ->where(
                'project_id',
                $project->id
            )
            ->where(
                'handover_project_id',
                $handover->id
            )
            ->where(
                'status',
                'Paid'
            )
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Handover Certificate
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        |
        | Handover Certificate is NOT a Final Completion readiness gate.
        |
        | It is displayed separately.
        |
        | Approved certificate = Complete.
        |
        | We intentionally query the certificate table directly so this
        | controller does not depend on a HandoverCertificate model class.
        |
        |--------------------------------------------------------------------------
        */

        $handoverCertificate = DB::table('handover_certificates')
            ->where(
                'project_id',
                $project->id
            )
            ->where(
                'handover_project_id',
                $handover->id
            )
            ->latest('id')
            ->first();

        $handoverCertificateComplete =
            $handoverCertificate
            &&
            $handoverCertificate->status === 'Approved';

        return view(
            'admin.handover.final-completion.index',
            compact(
                'project',
                'handover',
                'completion',
                'practicalCompletion',
                'defects',
                'documents',
                'requirements',
                'closedDefects',
                'totalDefects',
                'approvedDocuments',
                'totalDocuments',
                'finalAccounts',
                'totalFinalAccounts',
                'approvedFinalAccounts',
                'totalFinalPayments',
                'paidFinalPayments',
                'handoverCertificate',
                'handoverCertificateComplete'
            )
        );
    }


    /**
     * Update Final Completion details.
     */
    public function update(
        Request $request,
        Project $project,
        HandoverFinalCompletion $completion
    ): RedirectResponse {

        $this->validateCompletionProject(
            $project,
            $completion
        );

        if (!in_array(
            $completion->status,
            [
                'Not Ready',
                'Ready for Submission',
                'Rejected',
            ],
            true
        )) {
            return back()->with(
                'error',
                'Final Completion cannot be edited in its current status.'
            );
        }

        $validated = $request->validate([
            'planned_date' => [
                'nullable',
                'date',
            ],

            'completion_statement' => [
                'nullable',
                'string',
                'max:10000',
            ],

            'remarks' => [
                'nullable',
                'string',
                'max:5000',
            ],
        ]);

        $oldValues = $completion->getAttributes();

        $completion->update([
            'planned_date' =>
                $validated['planned_date'] ?? null,

            'completion_statement' =>
                $validated['completion_statement'] ?? null,

            'remarks' =>
                $validated['remarks'] ?? null,

            'rejection_reason' => null,

            'status' => 'Not Ready',

            'updated_by' => auth()->id(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | Recalculate after update
        |--------------------------------------------------------------------------
        */

        $this->evaluateReadiness($completion);

        $completion->refresh();

        CommissioningAuditLogService::updated(
            $completion,
            $oldValues,
            'Final Completion details updated.'
        );

        return back()->with(
            'success',
            'Final Completion details updated successfully.'
        );
    }


    /**
     * Submit Final Completion.
     */
    public function submit(
        Project $project,
        HandoverFinalCompletion $completion
    ): RedirectResponse {

        $this->validateCompletionProject(
            $project,
            $completion
        );

        /*
        |--------------------------------------------------------------------------
        | Recalculate immediately before submission
        |--------------------------------------------------------------------------
        */

        $this->evaluateReadiness($completion);

        $completion->refresh();

        if ($completion->status !== 'Ready for Submission') {

            return back()->with(
                'error',
                'Final Completion is not ready for submission. Please complete all required closeout activities.'
            );
        }

        DB::transaction(function () use ($completion) {

            $completion->update([
                'status' => 'Submitted',

                'submitted_date' =>
                    now()->toDateString(),

                'submitted_by' =>
                    auth()->id(),

                'submitted_at' =>
                    now(),

                'updated_by' =>
                    auth()->id(),
            ]);

            CommissioningAuditLogService::submitted(
                $completion,
                'Final Completion submitted for review.'
            );
        });

        return back()->with(
            'success',
            'Final Completion submitted successfully.'
        );
    }


    /**
     * Start Final Completion review.
     */
    public function review(
        Project $project,
        HandoverFinalCompletion $completion
    ): RedirectResponse {

        $this->validateCompletionProject(
            $project,
            $completion
        );

        if ($completion->status !== 'Submitted') {

            return back()->with(
                'error',
                'Only submitted Final Completion records can be reviewed.'
            );
        }

        DB::transaction(function () use ($completion) {

            $completion->update([
                'status' => 'Under Review',

                'reviewed_by' =>
                    auth()->id(),

                'reviewed_at' =>
                    now(),

                'updated_by' =>
                    auth()->id(),
            ]);

            CommissioningAuditLogService::action(
                'review_started',
                $completion,
                'Final Completion review started.'
            );
        });

        return back()->with(
            'success',
            'Final Completion is now under review.'
        );
    }


    /**
     * Approve Final Completion.
     */
    public function approve(
        Project $project,
        HandoverFinalCompletion $completion
    ): RedirectResponse {

        $this->validateCompletionProject(
            $project,
            $completion
        );

        if ($completion->status !== 'Under Review') {

            return back()->with(
                'error',
                'Only Final Completion records under review can be approved.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Critical:
        | Recalculate readiness immediately before approval.
        |--------------------------------------------------------------------------
        */

        $this->evaluateReadiness($completion);

        $completion->refresh();

        if (
            (float) $completion->readiness_percentage < 100
        ) {

            return back()->with(
                'error',
                'Final Completion cannot be approved because one or more mandatory closeout conditions are incomplete.'
            );
        }

        DB::transaction(function () use ($completion) {

            $completion->update([
                'status' => 'Approved',

                'approved_date' =>
                    now()->toDateString(),

                'approved_by' =>
                    auth()->id(),

                'approved_at' =>
                    now(),

                'updated_by' =>
                    auth()->id(),
            ]);

            /*
            |--------------------------------------------------------------------------
            | At Final Completion the Handover process
            | itself becomes completed.
            |--------------------------------------------------------------------------
            */

            $handover = $completion->handover;

            if ($handover) {

                $handover->update([
                    'status' => 'Completed',

                    'actual_handover_date' =>
                        $handover->actual_handover_date
                            ?: now()->toDateString(),

                    'updated_by' =>
                        auth()->id(),
                ]);
            }

            CommissioningAuditLogService::approved(
                $completion,
                'Final Completion approved and Handover Closeout completed.'
            );
        });

        return back()->with(
            'success',
            'Final Completion approved successfully. Handover Closeout is now completed.'
        );
    }


    /**
     * Reject Final Completion.
     */
    public function reject(
        Request $request,
        Project $project,
        HandoverFinalCompletion $completion
    ): RedirectResponse {

        $this->validateCompletionProject(
            $project,
            $completion
        );

        if ($completion->status !== 'Under Review') {

            return back()->with(
                'error',
                'Only Final Completion records under review can be rejected.'
            );
        }

        $validated = $request->validate([
            'rejection_reason' => [
                'required',
                'string',
                'max:5000',
            ],
        ]);

        DB::transaction(function () use (
            $completion,
            $validated
        ) {

            $completion->update([
                'status' => 'Rejected',

                'rejection_reason' =>
                    $validated['rejection_reason'],

                'updated_by' =>
                    auth()->id(),
            ]);

            CommissioningAuditLogService::rejected(
                $completion,
                'Final Completion rejected. Reason: ' .
                $validated['rejection_reason']
            );
        });

        return back()->with(
            'success',
            'Final Completion rejected and returned for rectification.'
        );
    }


    /**
     * Calculate Final Completion readiness.
     *
     * IMPORTANT:
     *
     * Handover Certificate is NOT included here.
     *
     * Final Completion has exactly 5 readiness gates:
     *
     * 1. Practical Completion
     * 2. ALL Final Accounts
     * 3. ALL Final Payments
     * 4. Defects
     * 5. Handover Documents
     *
     * Handover Certificate is a separate post-closeout record.
     */
    protected function evaluateReadiness(
        HandoverFinalCompletion $completion
    ): void {

        $handover = $completion->handover;

        if (!$handover) {
            return;
        }


        /*
        |--------------------------------------------------------------------------
        | 1. Practical Completion
        |--------------------------------------------------------------------------
        */

        $practicalCompletion =
            HandoverPracticalCompletion::query()
                ->where(
                    'project_id',
                    $handover->project_id
                )
                ->where(
                    'handover_project_id',
                    $handover->id
                )
                ->latest('id')
                ->first();

        $practicalComplete =
            $practicalCompletion
            &&
            $practicalCompletion->status === 'Approved';


        /*
        |--------------------------------------------------------------------------
        | 2. Final Accounts
        |--------------------------------------------------------------------------
        |
        | Multiple Procurement Contracts are supported.
        |
        | Contract 1 → Final Account 1
        | Contract 2 → Final Account 2
        | Contract 3 → Final Account 3
        |
        | ALL Final Accounts must be Approved.
        |--------------------------------------------------------------------------
        */

        $finalAccounts = HandoverFinalAccount::query()
            ->where(
                'project_id',
                $handover->project_id
            )
            ->where(
                'handover_project_id',
                $handover->id
            )
            ->get();

        $totalFinalAccounts =
            $finalAccounts->count();

        $approvedFinalAccounts =
            $finalAccounts
                ->where(
                    'status',
                    'Approved'
                )
                ->count();

        $finalAccountComplete =
            $totalFinalAccounts > 0
            &&
            $approvedFinalAccounts === $totalFinalAccounts;


        /*
        |--------------------------------------------------------------------------
        | 3. Final Payments
        |--------------------------------------------------------------------------
        |
        | Every approved Final Account is checked separately.
        |
        | If balance_payable = 0:
        |     No payment is required.
        |
        | If balance_payable > 0:
        |     A Final Payment must exist,
        |     be Paid,
        |     and balance_amount must be 0.
        |--------------------------------------------------------------------------
        */

        $finalPaymentComplete = false;

        if ($finalAccountComplete) {

            $finalPaymentComplete = true;

            foreach ($finalAccounts as $account) {

                $balancePayable =
                    (float) $account->balance_payable;

                /*
                 * No outstanding amount.
                 *
                 * Therefore no Final Payment is required.
                 */
                if ($balancePayable <= 0) {
                    continue;
                }

                /*
                 * Find payment for THIS Final Account.
                 */
                $payment =
                    HandoverFinalPayment::query()
                        ->where(
                            'project_id',
                            $handover->project_id
                        )
                        ->where(
                            'handover_project_id',
                            $handover->id
                        )
                        ->where(
                            'final_account_id',
                            $account->id
                        )
                        ->latest('id')
                        ->first();

                /*
                 * Payment is incomplete if:
                 *
                 * - no payment exists
                 * - payment is not Paid
                 * - balance still exists
                 */
                if (
                    !$payment
                    ||
                    $payment->status !== 'Paid'
                    ||
                    (float) $payment->balance_amount > 0
                ) {

                    $finalPaymentComplete = false;

                    break;
                }
            }
        }


        /*
        |--------------------------------------------------------------------------
        | 4. Defects
        |--------------------------------------------------------------------------
        */

        $defects = HandoverDefect::query()
            ->where(
                'project_id',
                $handover->project_id
            )
            ->where(
                'handover_project_id',
                $handover->id
            )
            ->get();

        /*
         * No defects = Complete.
         *
         * If defects exist, ALL must be Closed.
         */
        $defectsComplete =
            $defects->isEmpty()
            ||
            $defects->every(
                fn ($defect) =>
                    $defect->status === 'Closed'
            );


        /*
        |--------------------------------------------------------------------------
        | 5. Handover Documents
        |--------------------------------------------------------------------------
        |
        | Every uploaded Handover Document must be Approved.
        |--------------------------------------------------------------------------
        */

        $documents = HandoverDocument::query()
            ->where(
                'project_id',
                $handover->project_id
            )
            ->where(
                'handover_project_id',
                $handover->id
            )
            ->get();

        $totalDocuments =
            $documents->count();

        $approvedDocuments =
            $documents
                ->where(
                    'status',
                    'Approved'
                )
                ->count();

        $documentsComplete =
            $totalDocuments > 0
            &&
            $approvedDocuments === $totalDocuments;


        /*
        |--------------------------------------------------------------------------
        | Handover Certificate
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        |
        | Certificate is NOT part of Final Completion readiness.
        |
        | We only keep the existing database field synchronized so
        | older screens/data remain consistent.
        |
        | Approved certificate = true.
        |--------------------------------------------------------------------------
        */

        $handoverCertificate =
            DB::table('handover_certificates')
                ->where(
                    'project_id',
                    $handover->project_id
                )
                ->where(
                    'handover_project_id',
                    $handover->id
                )
                ->latest('id')
                ->first();

        $handoverCertificateComplete =
            $handoverCertificate
            &&
            $handoverCertificate->status === 'Approved';


        /*
        |--------------------------------------------------------------------------
        | Calculate readiness
        |--------------------------------------------------------------------------
        |
        | EXACTLY 5 readiness gates.
        |
        |--------------------------------------------------------------------------
        */

        $checks = [
            'practical_completion' =>
                $practicalComplete,

            'final_account' =>
                $finalAccountComplete,

            'final_payment' =>
                $finalPaymentComplete,

            'defects' =>
                $defectsComplete,

            'documents' =>
                $documentsComplete,
        ];

        $completedChecks =
            collect($checks)
                ->filter()
                ->count();

        $totalChecks =
            count($checks);

        $percentage =
            $totalChecks > 0
                ? round(
                    (
                        $completedChecks
                        /
                        $totalChecks
                    ) * 100,
                    2
                )
                : 0;

        $ready =
            $completedChecks === $totalChecks;


        /*
        |--------------------------------------------------------------------------
        | Data to save
        |--------------------------------------------------------------------------
        */

        $data = [

            'readiness_percentage' =>
                $percentage,

            'practical_completion_complete' =>
                (bool) $practicalComplete,

            'final_account_complete' =>
                (bool) $finalAccountComplete,

            'final_payment_complete' =>
                (bool) $finalPaymentComplete,

            'defects_complete' =>
                (bool) $defectsComplete,

            'documents_complete' =>
                (bool) $documentsComplete,

            /*
             * Separate information only.
             *
             * NOT included in readiness percentage.
             */
            'handover_certificate_complete' =>
                (bool) $handoverCertificateComplete,

            'updated_by' =>
                auth()->id(),
        ];


        /*
        |--------------------------------------------------------------------------
        | Do not overwrite active workflow statuses
        |--------------------------------------------------------------------------
        */

        if (in_array(
            $completion->status,
            [
                'Submitted',
                'Under Review',
                'Approved',
            ],
            true
        )) {

            $completion->update($data);

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Normal readiness state
        |--------------------------------------------------------------------------
        */

        $data['status'] =
            $ready
                ? 'Ready for Submission'
                : 'Not Ready';

        $completion->update($data);
    }


    /**
     * Get current project handover.
     */
    protected function getHandover(
        Project $project
    ): ?HandoverProject {

        return HandoverProject::where(
            'project_id',
            $project->id
        )
            ->latest('id')
            ->first();
    }


    /**
     * Validate Final Completion belongs to project.
     */
    protected function validateCompletionProject(
        Project $project,
        HandoverFinalCompletion $completion
    ): void {

        abort_unless(
            (int) $completion->project_id ===
            (int) $project->id,
            404
        );
    }


    /**
     * Generate Final Completion number.
     */
    protected function generateCompletionNo(
        Project $project
    ): string {

        $count =
            HandoverFinalCompletion::where(
                'project_id',
                $project->id
            )->count() + 1;

        return 'FC-' .
            str_pad(
                $project->id,
                4,
                '0',
                STR_PAD_LEFT
            ) .
            '-' .
            str_pad(
                $count,
                3,
                '0',
                STR_PAD_LEFT
            );
    }
}