<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    /**
     * Activity log listing.
     */
    public function index(Request $request)
    {
        $query = ActivityLog::query()
            ->with('user')
            ->latest();

        /*
        |--------------------------------------------------------------------------
        | User filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('user_id')) {

            $query->where(
                'user_id',
                $request->user_id
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Module filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('module')) {

            $query->where(
                'module',
                $request->module
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Action filter
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
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where(
                    'description',
                    'like',
                    "%{$search}%"
                )

                ->orWhere(
                    'ip_address',
                    'like',
                    "%{$search}%"
                )

                ->orWhere(
                    'route',
                    'like',
                    "%{$search}%"
                );
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Date
        |--------------------------------------------------------------------------
        */

        if ($request->filled('from')) {

            $query->whereDate(
                'created_at',
                '>=',
                $request->from
            );
        }

        if ($request->filled('to')) {

            $query->whereDate(
                'created_at',
                '<=',
                $request->to
            );
        }

        $activities = $query
            ->paginate(25)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Filters
        |--------------------------------------------------------------------------
        */

        $users = \App\Models\User::query()
            ->orderBy('name')
            ->get([
                'id',
                'name',
            ]);

        $modules = ActivityLog::query()
            ->whereNotNull('module')
            ->distinct()
            ->orderBy('module')
            ->pluck('module');

        $actions = ActivityLog::query()
            ->whereNotNull('action')
            ->distinct()
            ->orderBy('action')
            ->pluck('action');

        return view(
            'admin.activity-logs.index',
            compact(
                'activities',
                'users',
                'modules',
                'actions'
            )
        );
    }

    /**
     * Activity details.
     */
    public function show(ActivityLog $activityLog)
    {
        $activityLog->load('user', 'subject');

        return view(
            'admin.activity-logs.show',
            compact('activityLog')
        );
    }
}