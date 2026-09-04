<?php

namespace App\Http\Controllers\Admin\Construction;

use App\Http\Controllers\Controller;
use App\Models\ConstructionHseInspection;
use App\Models\ConstructionHseInspectionItem;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ConstructionHseInspectionItemController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(
        Project $project,
        ConstructionHseInspection $inspection
    ): View {

        $this->validateInspectionRelation(
            $project,
            $inspection
        );

        $items = $inspection
            ->items()
            ->orderBy('id')
            ->get();

        return view(
            'construction.hse.inspection-items.index',
            [
                'project' => $project,
                'inspection' => $inspection,
                'items' => $items,
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create(
        Project $project,
        ConstructionHseInspection $inspection
    ): View {

        $this->validateInspectionRelation(
            $project,
            $inspection
        );

        $itemNumber =
            $this->generateItemNumber($inspection);

        return view(
            'construction.hse.inspection-items.create',
            [
                'project' => $project,
                'inspection' => $inspection,
                'itemNumber' => $itemNumber,
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(
        Request $request,
        Project $project,
        ConstructionHseInspection $inspection
    ): RedirectResponse {

        $this->validateInspectionRelation(
            $project,
            $inspection
        );

        $validated = $request->validate([

            'checklist_category' => [
                'nullable',
                'string',
                'max:150',
            ],

            'checklist_question' => [
                'required',
                'string',
            ],

            'response' => [
                'nullable',
                'in:Compliant,Non-Compliant,Partially Compliant,Not Applicable',
            ],

            'observation' => [
                'nullable',
                'string',
            ],

            'severity' => [
                'nullable',
                'in:Low,Medium,High,Critical',
            ],

            'corrective_required' => [
                'nullable',
                'boolean',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],

        ]);


        $validated[
            'construction_hse_inspection_id'
        ] = $inspection->id;


        $validated['item_number'] =
            $this->generateItemNumber($inspection);


        $validated['corrective_required'] =
            $request->boolean(
                'corrective_required'
            );


        $validated['created_by'] =
            Auth::id();

        $validated['updated_by'] =
            Auth::id();


        ConstructionHseInspectionItem::create(
            $validated
        );


        return redirect()
            ->route(
                'admin.projects.construction.hse.inspections.items.index',
                [
                    'project' => $project,
                    'inspection' => $inspection,
                ]
            )
            ->with(
                'success',
                'Inspection checklist item added successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(
        Project $project,
        ConstructionHseInspection $inspection,
        ConstructionHseInspectionItem $item
    ): View {

        $this->validateItemRelation(
            $project,
            $inspection,
            $item
        );

        return view(
            'construction.hse.inspection-items.show',
            [
                'project' => $project,
                'inspection' => $inspection,
                'item' => $item,
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit(
        Project $project,
        ConstructionHseInspection $inspection,
        ConstructionHseInspectionItem $item
    ): View {

        $this->validateItemRelation(
            $project,
            $inspection,
            $item
        );

        return view(
            'construction.hse.inspection-items.edit',
            [
                'project' => $project,
                'inspection' => $inspection,
                'item' => $item,
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        Project $project,
        ConstructionHseInspection $inspection,
        ConstructionHseInspectionItem $item
    ): RedirectResponse {

        $this->validateItemRelation(
            $project,
            $inspection,
            $item
        );

        $validated = $request->validate([

            'checklist_category' => [
                'nullable',
                'string',
                'max:150',
            ],

            'checklist_question' => [
                'required',
                'string',
            ],

            'response' => [
                'nullable',
                'in:Compliant,Non-Compliant,Partially Compliant,Not Applicable',
            ],

            'observation' => [
                'nullable',
                'string',
            ],

            'severity' => [
                'nullable',
                'in:Low,Medium,High,Critical',
            ],

            'corrective_required' => [
                'nullable',
                'boolean',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],

        ]);


        $validated['corrective_required'] =
            $request->boolean(
                'corrective_required'
            );


        $validated['updated_by'] =
            Auth::id();


        $item->update(
            $validated
        );


        return redirect()
            ->route(
                'admin.projects.construction.hse.inspections.items.show',
                [
                    'project' => $project,
                    'inspection' => $inspection,
                    'item' => $item,
                ]
            )
            ->with(
                'success',
                'Inspection checklist item updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Project $project,
        ConstructionHseInspection $inspection,
        ConstructionHseInspectionItem $item
    ): RedirectResponse {

        $this->validateItemRelation(
            $project,
            $inspection,
            $item
        );


        $item->delete();


        return redirect()
            ->route(
                'admin.projects.construction.hse.inspections.items.index',
                [
                    'project' => $project,
                    'inspection' => $inspection,
                ]
            )
            ->with(
                'success',
                'Inspection checklist item deleted successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | GENERATE ITEM NUMBER
    |--------------------------------------------------------------------------
    */

    private function generateItemNumber(
        ConstructionHseInspection $inspection
    ): string {

        $nextNumber =
            $inspection
                ->items()
                ->count() + 1;


        do {

            $itemNumber =
                'ITEM-' .
                str_pad(
                    $nextNumber,
                    3,
                    '0',
                    STR_PAD_LEFT
                );


            $exists =
                $inspection
                    ->items()
                    ->where(
                        'item_number',
                        $itemNumber
                    )
                    ->exists();


            if ($exists) {
                $nextNumber++;
            }

        } while ($exists);


        return $itemNumber;
    }


    /*
    |--------------------------------------------------------------------------
    | VALIDATE INSPECTION
    |--------------------------------------------------------------------------
    */

    private function validateInspectionRelation(
        Project $project,
        ConstructionHseInspection $inspection
    ): void {

        abort_unless(
            (int) $inspection->project_id ===
            (int) $project->id,
            404
        );
    }


    /*
    |--------------------------------------------------------------------------
    | VALIDATE ITEM
    |--------------------------------------------------------------------------
    */

    private function validateItemRelation(
        Project $project,
        ConstructionHseInspection $inspection,
        ConstructionHseInspectionItem $item
    ): void {

        abort_unless(
            (int) $inspection->project_id ===
            (int) $project->id,
            404
        );


        abort_unless(
            (int) $item->construction_hse_inspection_id ===
            (int) $inspection->id,
            404
        );
    }
}