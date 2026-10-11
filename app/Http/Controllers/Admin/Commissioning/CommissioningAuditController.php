<?php

namespace App\Http\Controllers\Admin\Commissioning;

use App\Http\Controllers\Controller;
use App\Models\CommissioningAuditLog;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CommissioningAuditController extends Controller
{
    public function index(
        Request $request,
        Project $project
    ): View {

        $query = CommissioningAuditLog::query()
            ->with([
                'user',
                'project',
            ])
            ->where('project_id', $project->id);

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->where('action', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('ip_address', 'like', "%{$search}%")
                    ->orWhere('auditable_type', 'like', "%{$search}%");

                $q->orWhereHas('user', function ($userQuery) use ($search) {

                    $userQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");

                });
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Action Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('action')) {

            $query->where(
                'action',
                $request->action
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Date Filters
        |--------------------------------------------------------------------------
        */

        if ($request->filled('from_date')) {

            $query->whereDate(
                'created_at',
                '>=',
                $request->from_date
            );
        }

        if ($request->filled('to_date')) {

            $query->whereDate(
                'created_at',
                '<=',
                $request->to_date
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Statistics
        |--------------------------------------------------------------------------
        */

        $totalLogs = CommissioningAuditLog::where(
            'project_id',
            $project->id
        )->count();

        $todayLogs = CommissioningAuditLog::where(
            'project_id',
            $project->id
        )
            ->whereDate('created_at', today())
            ->count();

        $createdLogs = CommissioningAuditLog::where(
            'project_id',
            $project->id
        )
            ->where('action', 'created')
            ->count();

        $updatedLogs = CommissioningAuditLog::where(
            'project_id',
            $project->id
        )
            ->where('action', 'updated')
            ->count();

        $workflowLogs = CommissioningAuditLog::where(
            'project_id',
            $project->id
        )
            ->whereNotIn('action', [
                'created',
                'updated',
                'deleted',
            ])
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Actions
        |--------------------------------------------------------------------------
        */

        $actions = CommissioningAuditLog::where(
            'project_id',
            $project->id
        )
            ->whereNotNull('action')
            ->distinct()
            ->orderBy('action')
            ->pluck('action');

        /*
        |--------------------------------------------------------------------------
        | Logs
        |--------------------------------------------------------------------------
        */

        $logs = $query
            ->latest('created_at')
            ->paginate(20)
            ->withQueryString();

        return view(
            'admin.commissioning.audit.index',
            compact(
                'project',
                'logs',
                'actions',
                'totalLogs',
                'todayLogs',
                'createdLogs',
                'updatedLogs',
                'workflowLogs'
            )
        );
    }
}