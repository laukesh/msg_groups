<?php

namespace App\Http\Controllers\Admin\Land;

use App\Http\Controllers\Controller;
use App\Models\Land;
use App\Models\LandDueDiligence;
use Illuminate\Http\Request;

class LandTechnicalDueDiligenceController extends Controller
{
    /**
     * Display technical due diligence records.
     */
    public function index(Land $land)
    {
        $dueDiligences = $land->dueDiligences()
            ->where('type', 'Technical')
            ->latest('id')
            ->paginate(15);

        return view(
            'land-acquisition.technical-due-diligences.index',
            compact(
                'land',
                'dueDiligences'
            )
        );
    }


    /**
     * Show create form.
     */
    public function create(Land $land)
    {
        return view(
            'land-acquisition.technical-due-diligences.create',
            compact('land')
        );
    }


    /**
     * Store technical due diligence.
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

        $validated['type'] = 'Technical';

        $validated['created_by'] = auth()->id();


        LandDueDiligence::create(
            $validated
        );


        return redirect()
            ->route(
                'admin.land.lands.show',
                $land
            )
            ->with(
                'success',
                'Technical due diligence added successfully.'
            );
    }


    /**
     * Display technical due diligence.
     */
    public function show(
        Land $land,
        LandDueDiligence $dueDiligence
    ) {

        $this->validateBelongsToLand(
            $land,
            $dueDiligence
        );
//dd($land);
        abort_unless(
            $dueDiligence->type === 'Technical',
            404
        );


        return view(
            'land-acquisition.technical-due-diligences.show',
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
        LandDueDiligence $dueDiligence
    ) {
        $this->validateBelongsToLand(
            $land,
            $dueDiligence
        );

        abort_unless(
            $dueDiligence->type === 'Technical',
            404
        );


        return view(
            'land-acquisition.technical-due-diligences.edit',
            compact(
                'land',
                'dueDiligence'
            )
        );
    }


    /**
     * Update technical due diligence.
     */
    public function update(
        Request $request,
        Land $land,
        LandDueDiligence $dueDiligence
    ) {
        $this->validateBelongsToLand(
            $land,
            $dueDiligence
        );

        abort_unless(
            $dueDiligence->type === 'Technical',
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


        $validated['type'] = 'Technical';

        $validated['updated_by'] = auth()->id();


        $dueDiligence->update(
            $validated
        );


        return redirect()
            ->route(
                'admin.land.lands.show',
                $land
            )
            ->with(
                'success',
                'Technical due diligence updated successfully.'
            );
    }


    /**
     * Delete technical due diligence.
     */
    public function destroy(
        Land $land,
        LandDueDiligence $dueDiligence
    ) {
        $this->validateBelongsToLand(
            $land,
            $dueDiligence
        );

        abort_unless(
            $dueDiligence->type === 'Technical',
            404
        );


        $dueDiligence->delete();


        return redirect()
            ->route(
                'admin.land.lands.show',
                $land
            )
            ->with(
                'success',
                'Technical due diligence deleted successfully.'
            );
    }


    /**
     * Validate record belongs to land.
     */
    private function validateBelongsToLand(
        Land $land,
        LandDueDiligence $dueDiligence
    ): void {
        abort_unless(
		    (int) $dueDiligence->land_id === (int) $land->id,
		    404
		);
    }

    /*private function validateBelongsToLand(
    Land $land,
    LandDueDiligence $dueDiligence
): void {

    dd([
        'land_id' => $land->id,
        'land_id_type' => gettype($land->id),

        'due_diligence_id' => $dueDiligence->id,
        'due_diligence_land_id' => $dueDiligence->land_id,
        'due_diligence_land_id_type' => gettype($dueDiligence->land_id),

        'due_diligence_type' => $dueDiligence->type,

        'comparison' =>
            (int) $dueDiligence->land_id === (int) $land->id,
    ]);
}*/
}