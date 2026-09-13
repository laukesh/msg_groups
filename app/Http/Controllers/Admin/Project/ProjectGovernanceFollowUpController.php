<?php

namespace App\Http\Controllers\Admin\Project;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectGovernanceMeetingActionItem;
use App\Models\ProjectGovernanceMeetingDecision;
use Illuminate\View\View;

class ProjectGovernanceFollowUpController extends Controller
{
    /**
     * Project-level Governance Follow-up Dashboard.
     */
    public function test(){
    	echo 1;die();
    }
    public function index(Project $project): View
    {

    	//dd('CONTROLLER REACHED', $project->id);

        /*
         * ---------------------------------------------------------
         * ACTION ITEMS
         * ---------------------------------------------------------
         *
         * Action items do not have project_id directly.
         * They belong to a Governance Meeting, which belongs
         * to the Project.
         */

        $actionItems = ProjectGovernanceMeetingActionItem::query()
            ->with([
                'meeting',
                'responsibleUser',
            ])
            ->whereHas('meeting', function ($query) use ($project) {

                $query->where(
                    'project_id',
                    $project->id
                );

            })
            ->orderByRaw("
                CASE
                    WHEN status = 'Overdue' THEN 1
                    WHEN status = 'Open' THEN 2
                    WHEN status = 'In Progress' THEN 3
                    WHEN status = 'Completed' THEN 4
                    ELSE 5
                END
            ")
            ->orderBy('due_date')
            ->get();


        /*
         * ---------------------------------------------------------
         * DECISIONS
         * ---------------------------------------------------------
         */

        $decisions = ProjectGovernanceMeetingDecision::query()
            ->with([
                'meeting',
                'approver',
            ])
            ->whereHas('meeting', function ($query) use ($project) {

                $query->where(
                    'project_id',
                    $project->id
                );

            })
            ->orderByRaw("
                CASE
                    WHEN decision_status = 'Draft' THEN 1
                    WHEN decision_status = 'Deferred' THEN 2
                    WHEN decision_status = 'Approved' THEN 3
                    WHEN decision_status = 'Rejected' THEN 4
                    ELSE 5
                END
            ")
            ->orderByDesc('created_at')
            ->get();


        /*
         * ---------------------------------------------------------
         * ACTION COUNTS
         * ---------------------------------------------------------
         */

        $actionCounts = [

            'total' =>
                $actionItems->count(),

            'open' =>
                $actionItems
                    ->where('status', 'Open')
                    ->count(),

            'in_progress' =>
                $actionItems
                    ->where('status', 'In Progress')
                    ->count(),

            'completed' =>
                $actionItems
                    ->where('status', 'Completed')
                    ->count(),

            'overdue' =>
                $actionItems
                    ->where('status', 'Overdue')
                    ->count(),

            'cancelled' =>
                $actionItems
                    ->where('status', 'Cancelled')
                    ->count(),

        ];


        /*
         * ---------------------------------------------------------
         * DECISION COUNTS
         * ---------------------------------------------------------
         */

        $decisionCounts = [

            'total' =>
                $decisions->count(),

            'draft' =>
                $decisions
                    ->where('decision_status', 'Draft')
                    ->count(),

            'approved' =>
                $decisions
                    ->where('decision_status', 'Approved')
                    ->count(),

            'rejected' =>
                $decisions
                    ->where('decision_status', 'Rejected')
                    ->count(),

            'deferred' =>
                $decisions
                    ->where('decision_status', 'Deferred')
                    ->count(),

            'superseded' =>
                $decisions
                    ->where('decision_status', 'Superseded')
                    ->count(),

        ];


        /*
         * ---------------------------------------------------------
         * OVERDUE ACTIONS
         * ---------------------------------------------------------
         *
         * Include both explicitly marked Overdue records and
         * Open/In Progress records whose due date has passed.
         */

        $overdueActions = $actionItems
            ->filter(function ($action) {

                if (
                    $action->status === 'Overdue'
                ) {
                    return true;
                }

                if (
                    in_array(
                        $action->status,
                        [
                            'Open',
                            'In Progress',
                        ],
                        true
                    )
                    && $action->due_date
                    && $action->due_date->isPast()
                ) {
                    return true;
                }

                return false;
            })
            ->values();


        /*
         * ---------------------------------------------------------
         * OPEN ACTIONS
         * ---------------------------------------------------------
         */

        $openActions = $actionItems
            ->filter(function ($action) {

                return in_array(
                    $action->status,
                    [
                        'Open',
                        'In Progress',
                        'Overdue',
                    ],
                    true
                );

            })
            ->values();


        /*
         * ---------------------------------------------------------
         * PENDING DECISIONS
         * ---------------------------------------------------------
         */

        $pendingDecisions = $decisions
            ->filter(function ($decision) {

                return in_array(
                    $decision->decision_status,
                    [
                        'Draft',
                        'Deferred',
                    ],
                    true
                );

            })
            ->values();


        return view(
            'projects.governance.follow-up.index',
            compact(
                'project',
                'actionItems',
                'decisions',
                'actionCounts',
                'decisionCounts',
                'overdueActions',
                'openActions',
                'pendingDecisions'
            )
        );
    }
}