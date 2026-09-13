<?php

namespace App\Http\Controllers\Admin\Construction;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\View\View;

class ConstructionHseController extends Controller
{
    public function index(Project $project): View
    {
        return view(
            'construction.hse.index',
            [
                'project' => $project,
            ]
        );
    }
}