<?php

namespace App\Http\Controllers\Admin\DesignManagement\Concerns;

use App\Models\DesignDiscipline;
use App\Models\DesignPackage;
use App\Models\Project;
use App\Models\ProjectConsultant;
use App\Models\User;

trait LoadsDesignFormData
{
    protected function disciplines()
    {
        return DesignDiscipline::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    }

    protected function projectConsultants(Project $project)
    {
        return ProjectConsultant::query()
            ->where('project_id', $project->id)
            ->orderBy('company_name')
            ->get();
    }

    protected function projectPackages(Project $project)
    {
        return DesignPackage::query()
            ->where('project_id', $project->id)
            ->orderBy('package_code')
            ->get();
    }

    protected function users()
    {
        return User::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'email']);
    }
}
