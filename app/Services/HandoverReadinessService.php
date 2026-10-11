<?php

namespace App\Services;

use App\Models\CommissioningCertificate;
use App\Models\CommissioningScope;
use App\Models\CommissioningTest;
use App\Models\CommissioningTestPlan;
use App\Models\HandoverDefect;
use App\Models\HandoverProject;
use App\Models\HandoverRequirement;
use App\Models\HandoverSnag;
use App\Models\Project;

class HandoverReadinessService
{
    /*
    |--------------------------------------------------------------------------
    | SYNC
    |--------------------------------------------------------------------------
    */

    public function sync(Project $project): void
    {
        $handover = HandoverProject::where(
            'project_id',
            $project->id
        )
            ->latest('id')
            ->first();

        if (!$handover) {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | System Controlled Readiness
        |--------------------------------------------------------------------------
        */

        $this->syncCommissioning($handover);

        $this->syncSnagging($handover);

        $this->syncDefects($handover);

        /*
        |--------------------------------------------------------------------------
        | Final Readiness Calculation
        |--------------------------------------------------------------------------
        */

        $this->calculateReadiness($handover);
    }


    /*
    |--------------------------------------------------------------------------
    | COMMISSIONING
    |--------------------------------------------------------------------------
    */

    protected function syncCommissioning(
        HandoverProject $handover
    ): void {

        $requirement = HandoverRequirement::where(
            'handover_project_id',
            $handover->id
        )
            ->where(
                'requirement_code',
                'COMM-COMP'
            )
            ->first();

        if (!$requirement) {
            return;
        }

        $scopes = CommissioningScope::where(
            'project_id',
            $handover->project_id
        )->get();

        $testPlans = CommissioningTestPlan::where(
            'project_id',
            $handover->project_id
        )->get();

        $tests = CommissioningTest::where(
            'project_id',
            $handover->project_id
        )->get();

        $certificates = CommissioningCertificate::where(
            'project_id',
            $handover->project_id
        )->get();


        /*
        |--------------------------------------------------------------------------
        | Scopes
        |--------------------------------------------------------------------------
        */

        if ($scopes->isEmpty()) {

            $this->updateRequirement(
                $requirement,
                'Pending',
                'Commissioning scopes have not been defined yet.'
            );

            return;
        }


        $acceptedScopes = $scopes
            ->where(
                'readiness_status',
                'Accepted'
            )
            ->count();

        $totalScopes = $scopes->count();


        if ($acceptedScopes !== $totalScopes) {

            $this->updateRequirement(
                $requirement,
                'In Progress',
                "Commissioning scopes: {$acceptedScopes}/{$totalScopes} accepted."
            );

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Test Plans
        |--------------------------------------------------------------------------
        */

        if ($testPlans->isEmpty()) {

            $this->updateRequirement(
                $requirement,
                'In Progress',
                'All commissioning scopes are accepted, but no test plans exist.'
            );

            return;
        }


        $completedPlans = $testPlans
            ->where(
                'status',
                'Completed'
            )
            ->count();

        $totalPlans = $testPlans->count();


        if ($completedPlans !== $totalPlans) {

            $this->updateRequirement(
                $requirement,
                'In Progress',
                "Commissioning test plans: {$completedPlans}/{$totalPlans} completed."
            );

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Tests
        |--------------------------------------------------------------------------
        */

        if ($tests->isEmpty()) {

            $this->updateRequirement(
                $requirement,
                'In Progress',
                'Test plans are completed, but no test executions exist.'
            );

            return;
        }


        $successfulTests = $tests
            ->where(
                'status',
                'Completed'
            )
            ->whereIn(
                'result',
                [
                    'Pass',
                    'Conditional Pass',
                ]
            )
            ->count();

        $totalTests = $tests->count();


        if ($successfulTests !== $totalTests) {

            $this->updateRequirement(
                $requirement,
                'In Progress',
                "Commissioning tests successfully completed: {$successfulTests}/{$totalTests}."
            );

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Certificate
        |--------------------------------------------------------------------------
        */

        if ($certificates->isEmpty()) {

            $this->updateRequirement(
                $requirement,
                'In Progress',
                'Commissioning tests are complete, but no commissioning certificate exists.'
            );

            return;
        }


        $approvedCertificates = $certificates
            ->where(
                'status',
                'Approved'
            )
            ->count();


        if ($approvedCertificates === 0) {

            $this->updateRequirement(
                $requirement,
                'In Progress',
                'Commissioning certificate is awaiting approval.'
            );

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Completed
        |--------------------------------------------------------------------------
        */

        $this->updateRequirement(
            $requirement,
            'Completed',
            'Commissioning completion verified automatically from commissioning records.',
            true
        );
    }


    /*
    |--------------------------------------------------------------------------
    | SNAGGING
    |--------------------------------------------------------------------------
    */

    protected function syncSnagging(
        HandoverProject $handover
    ): void {

        $requirement = HandoverRequirement::where(
            'handover_project_id',
            $handover->id
        )
            ->where(
                'requirement_code',
                'SNAG-COMP'
            )
            ->first();

        if (!$requirement) {
            return;
        }


        $snags = HandoverSnag::where(
            'project_id',
            $handover->project_id
        )
            ->where(
                'handover_project_id',
                $handover->id
            )
            ->get();


        /*
        |--------------------------------------------------------------------------
        | No Snags
        |--------------------------------------------------------------------------
        */

        if ($snags->isEmpty()) {

            $this->updateRequirement(
                $requirement,
                'Pending',
                'No snag / punch list items have been raised yet.'
            );

            return;
        }


        $totalSnags = $snags->count();

        $closedSnags = $snags
            ->where(
                'status',
                'Closed'
            )
            ->count();

        $outstandingSnags =
            $totalSnags - $closedSnags;


        if ($closedSnags !== $totalSnags) {

            $this->updateRequirement(
                $requirement,
                'In Progress',
                "Snagging progress: {$closedSnags}/{$totalSnags} closed. {$outstandingSnags} item(s) outstanding."
            );

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Completed
        |--------------------------------------------------------------------------
        */

        $this->updateRequirement(
            $requirement,
            'Completed',
            'All snag / punch list items have been verified and closed.',
            true
        );
    }


    /*
    |--------------------------------------------------------------------------
    | DEFECTS
    |--------------------------------------------------------------------------
    */

    protected function syncDefects(
        HandoverProject $handover
    ): void {

        $requirement = HandoverRequirement::where(
            'handover_project_id',
            $handover->id
        )
            ->where(
                'requirement_code',
                'DEFECT-COMP'
            )
            ->first();

        if (!$requirement) {
            return;
        }


        $defects = HandoverDefect::where(
            'project_id',
            $handover->project_id
        )
            ->where(
                'handover_project_id',
                $handover->id
            )
            ->get();


        /*
        |--------------------------------------------------------------------------
        | No Defects
        |--------------------------------------------------------------------------
        |
        | No outstanding defects means the defect requirement
        | is considered satisfied.
        |
        */

        if ($defects->isEmpty()) {

            $this->updateRequirement(
                $requirement,
                'Completed',
                'No handover defects are currently outstanding.',
                true
            );

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Count Closed
        |--------------------------------------------------------------------------
        */

        $totalDefects = $defects->count();

        $closedDefects = $defects
            ->where(
                'status',
                'Closed'
            )
            ->count();

        $outstandingDefects =
            $totalDefects - $closedDefects;


        /*
        |--------------------------------------------------------------------------
        | Outstanding Defects
        |--------------------------------------------------------------------------
        */

        if ($closedDefects !== $totalDefects) {

            $this->updateRequirement(
                $requirement,
                'In Progress',
                "Defect closure progress: {$closedDefects}/{$totalDefects} closed. {$outstandingDefects} defect(s) outstanding."
            );

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | All Defects Closed
        |--------------------------------------------------------------------------
        */

        $this->updateRequirement(
            $requirement,
            'Completed',
            'All handover defects have been verified and closed.',
            true
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CALCULATE READINESS
    |--------------------------------------------------------------------------
    */

    protected function calculateReadiness(
        HandoverProject $handover
    ): void {

        $requirements = $handover
            ->requirements()
            ->where(
                'is_mandatory',
                true
            )
            ->get();

        $totalRequirements =
            $requirements->count();


        if ($totalRequirements === 0) {

            $handover->update([
                'readiness_percentage' => 0,
                'updated_by' => auth()->id(),
            ]);

            return;
        }


        $completedRequirements = $requirements
            ->whereIn(
                'status',
                [
                    'Completed',
                    'Waived',
                ]
            )
            ->count();


        $percentage = round(
            ($completedRequirements / $totalRequirements) * 100,
            2
        );


        $handover->update([
            'readiness_percentage' => $percentage,
            'updated_by' => auth()->id(),
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE REQUIREMENT
    |--------------------------------------------------------------------------
    */

    protected function updateRequirement(
        HandoverRequirement $requirement,
        string $status,
        string $remarks,
        bool $completed = false
    ): void {

        $data = [
            'status' =>
                $status,

            'remarks' =>
                $remarks,

            'updated_by' =>
                auth()->id(),
        ];


        if ($completed) {

            $data['completed_by'] = null;

            $data['completed_at'] = now();

        } else {

            $data['completed_by'] = null;

            $data['completed_at'] = null;
        }


        $requirement->update($data);
    }
}