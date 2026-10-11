<?php

namespace App\Http\Controllers\Admin\Revenue;

use App\Http\Controllers\Controller;
use App\Models\RevenueAuditLog;
use Illuminate\Http\Request;

class RevenueAuditLogController extends Controller
{
    public function index(Request $request)
    {
        $query = RevenueAuditLog::with('user')
            ->orderByDesc('id');


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
                    'action',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'reference_id',
                    $search
                );

            });
        }


        /*
        |--------------------------------------------------------------------------
        | Module
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
        | Action
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
        | Date
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


        $logs = $query
            ->paginate(25)
            ->withQueryString();


        $modules = RevenueAuditLog::query()
            ->select('module')
            ->distinct()
            ->orderBy('module')
            ->pluck('module');


        $actions = RevenueAuditLog::query()
            ->select('action')
            ->distinct()
            ->orderBy('action')
            ->pluck('action');


        return view(
            'admin.revenue.audit.index',
            compact(
                'logs',
                'modules',
                'actions'
            )
        );
    }
}