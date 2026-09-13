<?php

namespace App\Http\Controllers\Admin\Leasing;

use App\Http\Controllers\Controller;
use App\Models\LeaseHistory;

class LeaseHistoryController extends Controller
{
    public function index()
    {
        $histories = LeaseHistory::with([
            'agreement',
            'performer',
        ])
        ->latest('activity_date')
        ->paginate(25);

        return view(
            'admin.leasing.history.index',
            compact('histories')
        );
    }
}