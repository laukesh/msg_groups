<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Simple metrics could be added here; keep minimal
        return view('admin.dashboard');
    }
}
