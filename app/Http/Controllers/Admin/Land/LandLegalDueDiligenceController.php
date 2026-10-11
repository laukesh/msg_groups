<?php

namespace App\Http\Controllers\Admin\Land;

use App\Http\Controllers\Controller;
use App\Models\Land;
use App\Models\LandDueDiligence;
use Illuminate\Http\Request;

class LandLegalDueDiligenceController extends Controller
{
    /**
     * Display legal due diligence records.
     */
    public function index(Land $land)
    {
        $legalDueDiligences = $land->dueDiligences()
            ->where('type', 'Legal')
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        return view(
            'land-acquisition.legal-due-diligences.index',
            compact(
                'land',
                'legalDueDiligences'
            )
        );
    }


    /**
     * Show create form.
     */
    public function create(Land $land)
    {
        return view(
            'land-acquisition.legal-due-diligences.create',
            compact('land')
        );
    }


    /**
     * Store legal due diligence.
     */
    public function store(
        Request $request,
        Land $land
    ) {
        $validated = $request->validate([

            'reference_no' => [
                'nullable',
                'string',
                'max:100'
            ],

            'assessment_date' => [
                'nullable',
                'date'
            ],

            'conducted_by' => [
                'nullable',
                'string',
                'max:255'
            ],

            'status' => [
                'required',
                'string',
                'max:50'
            ],

            'summary' => [
                'nullable',
                'string'
            ],

            'findings' => [
                'nullable',
                'string'
            ],

            'recommendations' => [
                'nullable',
                'string'
            ],

            'remarks' => [
                'nullable',
                'string'
            ],

        ]);

        $validated['land_id'] = $land->id;

        // Always Legal for this controller
        $validated['type'] = 'Legal';

        $validated['created_by'] = auth()->id();

        LandDueDiligence::create($validated);

        return redirect()
            ->route(
                'admin.land.lands.legal-due-diligences.index',
                $land
            )
            ->with(
                'success',
                'Legal due diligence added successfully.'
            );
    }


    /**
     * Display due diligence.
     */
    public function show(
        Land $land,
        LandDueDiligence $legal_due_diligence
    ) {
        $this->validateBelongsToLand(
            $land,
            $legal_due_diligence
        );

        abort_unless(
            $legal_due_diligence->type === 'Legal',
            404
        );

        // Keep the variable name used by the Blade files.
        $dueDiligence = $legal_due_diligence;

        return view(
            'land-acquisition.legal-due-diligences.show',
            compact(
                'land',
                'dueDiligence'
            )
        );
    }


    /**
     * Show edit form.
     */
    public function edit(
        Land $land,
        LandDueDiligence $legal_due_diligence
    ) {
        $this->validateBelongsToLand(
            $land,
            $legal_due_diligence
        );

        abort_unless(
            $legal_due_diligence->type === 'Legal',
            404
        );

        $dueDiligence = $legal_due_diligence;

        return view(
            'land-acquisition.legal-due-diligences.edit',
            compact(
                'land',
                'dueDiligence'
            )
        );
    }


    /**
     * Update due diligence.
     */
    public function update(
        Request $request,
        Land $land,
        LandDueDiligence $legal_due_diligence
    ) {
        $this->validateBelongsToLand(
            $land,
            $legal_due_diligence
        );

        abort_unless(
            $legal_due_diligence->type === 'Legal',
            404
        );

        $validated = $request->validate([

            'reference_no' => [
                'nullable',
                'string',
                'max:100'
            ],

            'assessment_date' => [
                'nullable',
                'date'
            ],

            'conducted_by' => [
                'nullable',
                'string',
                'max:255'
            ],

            'status' => [
                'required',
                'string',
                'max:50'
            ],

            'summary' => [
                'nullable',
                'string'
            ],

            'findings' => [
                'nullable',
                'string'
            ],

            'recommendations' => [
                'nullable',
                'string'
            ],

            'remarks' => [
                'nullable',
                'string'
            ],

        ]);

        $validated['type'] = 'Legal';

        $validated['updated_by'] = auth()->id();

        $legal_due_diligence->update($validated);

        return redirect()
            ->route(
                'admin.land.lands.legal-due-diligences.show',
                [
                    'land' => $land,
                    'legal_due_diligence' => $legal_due_diligence
                ]
            )
            ->with(
                'success',
                'Legal due diligence updated successfully.'
            );
    }


    /**
     * Delete due diligence.
     */
    public function destroy(
        Land $land,
        LandDueDiligence $legal_due_diligence
    ) {
        $this->validateBelongsToLand(
            $land,
            $legal_due_diligence
        );

        abort_unless(
            $legal_due_diligence->type === 'Legal',
            404
        );

        $legal_due_diligence->delete();

        return redirect()
            ->route(
                'admin.land.lands.legal-due-diligences.index',
                $land
            )
            ->with(
                'success',
                'Legal due diligence deleted successfully.'
            );
    }


    /**
     * Validate that the due diligence belongs to the land.
     */
    private function validateBelongsToLand(
        Land $land,
        LandDueDiligence $dueDiligence
    ): void {
        abort_unless(
            $dueDiligence->land_id === $land->id,
            404
        );
    }
}