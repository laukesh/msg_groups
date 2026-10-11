<?php

namespace App\Http\Controllers\Admin\Assets\Areas;

use App\Http\Controllers\Controller;
use App\Models\AreaRevision;
use App\Models\PropertyArea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AreaRevisionController extends Controller
{
    /**
     * Display all revisions for a property area.
     */
    public function index(PropertyArea $area)
    {
        $revisions = $area->revisions()
            ->latest('revision_number')
            ->paginate(25);

        return view(
            'admin.assets.areas.revisions.index',
            compact('area', 'revisions')
        );
    }

    /**
     * Show create revision form.
     */
    public function create(PropertyArea $area)
    {
        return view(
            'admin.assets.areas.revisions.create',
            compact('area')
        );
    }

    /**
     * Store a new area revision.
     */
    public function store(Request $request, PropertyArea $area)
    {
        $validated = $request->validate([
            'new_gross_area' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'new_net_area' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'new_rentable_area' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'reason' => [
                'required',
                'string',
                'max:500',
            ],

            'revision_date' => [
                'required',
                'date',
            ],

            'effective_from' => [
                'nullable',
                'date',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Generate Next Revision Number
        |--------------------------------------------------------------------------
        */

        $lastRevision = $area->revisions()
            ->max('revision_number');

        $nextRevisionNumber = ($lastRevision ?? 0) + 1;

        /*
        |--------------------------------------------------------------------------
        | Create Revision
        |--------------------------------------------------------------------------
        */

        $area->revisions()->create([
            'revision_number' => $nextRevisionNumber,

            /*
             * Previous/current values
             */
            'previous_gross_area' => $area->gross_area,
            'previous_net_area' => $area->net_area,
            'previous_rentable_area' => $area->rentable_area,

            /*
             * Proposed new values
             */
            'new_gross_area' => $validated['new_gross_area'] ?? null,
            'new_net_area' => $validated['new_net_area'] ?? null,
            'new_rentable_area' => $validated['new_rentable_area'] ?? null,

            /*
             * Revision information
             */
            'reason' => $validated['reason'],
            'revision_date' => $validated['revision_date'],
            'effective_from' => $validated['effective_from'] ?? null,

            /*
             * Workflow
             */
            'status' => 'DRAFT',
            'submitted_by' => auth()->id(),

            /*
             * Additional information
             */
            'remarks' => $validated['remarks'] ?? null,
        ]);

        return redirect()
            ->route(
                'admin.assets.revisions.index',
                [
                    'area' => $area->id,
                ]
            )
            ->with(
                'success',
                'Area revision created successfully.'
            );
    }

    /**
     * Display a single revision.
     */
    public function show(
        PropertyArea $area,
        AreaRevision $revision
    ) {
        $this->verifyRevisionBelongsToArea(
            $area,
            $revision
        );

        $revision->load('propertyArea');

        return view(
            'admin.assets.areas.revisions.show',
            compact('area', 'revision')
        );
    }

    /**
     * Show edit form.
     *
     * Only DRAFT and REJECTED revisions can be edited.
     */
    public function edit(
        PropertyArea $area,
        AreaRevision $revision
    ) {
        $this->verifyRevisionBelongsToArea(
            $area,
            $revision
        );

        $status = strtoupper(
            (string) $revision->status
        );

        abort_unless(
            in_array(
                $status,
                [
                    'DRAFT',
                    'REJECTED',
                ],
                true
            ),
            422,
            'Only draft or rejected revisions can be edited.'
        );

        return view(
            'admin.assets.areas.revisions.edit',
            compact('area', 'revision')
        );
    }

    /**
     * Update an existing revision.
     */
    public function update(
        Request $request,
        PropertyArea $area,
        AreaRevision $revision
    ) {
        $this->verifyRevisionBelongsToArea(
            $area,
            $revision
        );

        $status = strtoupper(
            (string) $revision->status
        );

        abort_unless(
            in_array(
                $status,
                [
                    'DRAFT',
                    'REJECTED',
                ],
                true
            ),
            422,
            'Only draft or rejected revisions can be updated.'
        );

        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'new_gross_area' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'new_net_area' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'new_rentable_area' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'reason' => [
                'required',
                'string',
                'max:500',
            ],

            'revision_date' => [
                'required',
                'date',
            ],

            'effective_from' => [
                'nullable',
                'date',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Update Data
        |--------------------------------------------------------------------------
        */

        $data = [
            'new_gross_area' => $validated['new_gross_area'] ?? null,

            'new_net_area' => $validated['new_net_area'] ?? null,

            'new_rentable_area' =>
                $validated['new_rentable_area'] ?? null,

            'reason' => $validated['reason'],

            'revision_date' =>
                $validated['revision_date'],

            'effective_from' =>
                $validated['effective_from'] ?? null,

            'remarks' =>
                $validated['remarks'] ?? null,
        ];

        /*
        |--------------------------------------------------------------------------
        | Rejected Revision
        |--------------------------------------------------------------------------
        |
        | If a rejected revision is edited again, return it to DRAFT.
        |
        */

        if ($status === 'REJECTED') {
            $data['status'] = 'DRAFT';
        }

        $revision->update($data);

        return redirect()
            ->route(
                'admin.assets.revisions.show',
                [
                    'area' => $area->id,
                    'revision' => $revision->id,
                ]
            )
            ->with(
                'success',
                'Area revision updated successfully.'
            );
    }

    /**
     * Submit a draft revision for approval.
     *
     * IMPORTANT:
     * Raw route parameters are intentionally used here.
     *
     * This prevents:
     *
     * AreaRevisionController::submit():
     * Argument #1 ($revision) must be of type AreaRevision,
     * string given
     */
    public function submit(
        $area,
        $revision
    ) {
        /*
        |--------------------------------------------------------------------------
        | Resolve Models Explicitly
        |--------------------------------------------------------------------------
        */

        $area = PropertyArea::findOrFail($area);

        $revision = AreaRevision::findOrFail($revision);

        /*
        |--------------------------------------------------------------------------
        | Verify Relationship
        |--------------------------------------------------------------------------
        */

        $this->verifyRevisionBelongsToArea(
            $area,
            $revision
        );

        /*
        |--------------------------------------------------------------------------
        | Check Status
        |--------------------------------------------------------------------------
        */

        abort_unless(
            strtoupper((string) $revision->status) === 'DRAFT',
            422,
            'Only draft revisions can be submitted.'
        );

        /*
        |--------------------------------------------------------------------------
        | Submit
        |--------------------------------------------------------------------------
        */

        $revision->update([
            'status' => 'SUBMITTED',
            'submitted_by' => auth()->id(),
        ]);

        return redirect()
            ->route(
                'admin.assets.revisions.index',
                [
                    'area' => $area->id,
                ]
            )
            ->with(
                'success',
                'Area revision submitted successfully.'
            );
    }

    /**
     * Approve a submitted revision.
     *
     * Approval updates the PropertyArea values.
     */
    public function approve(
        $area,
        $revision
    ) {
        /*
        |--------------------------------------------------------------------------
        | Resolve Models Explicitly
        |--------------------------------------------------------------------------
        */

        $area = PropertyArea::findOrFail($area);

        $revision = AreaRevision::findOrFail($revision);

        /*
        |--------------------------------------------------------------------------
        | Verify Relationship
        |--------------------------------------------------------------------------
        */

        $this->verifyRevisionBelongsToArea(
            $area,
            $revision
        );

        /*
        |--------------------------------------------------------------------------
        | Check Status
        |--------------------------------------------------------------------------
        */

        abort_unless(
            strtoupper((string) $revision->status) === 'SUBMITTED',
            422,
            'Only submitted revisions can be approved.'
        );

        /*
        |--------------------------------------------------------------------------
        | Approve Transaction
        |--------------------------------------------------------------------------
        */

        DB::transaction(
            function () use (
                $area,
                $revision
            ) {

                /*
                |--------------------------------------------------------------------------
                | Update Property Area
                |--------------------------------------------------------------------------
                */

                $area->update([
                    'gross_area' =>
                        $revision->new_gross_area,

                    'net_area' =>
                        $revision->new_net_area,

                    'rentable_area' =>
                        $revision->new_rentable_area,

                    'effective_from' =>
                        $revision->effective_from,
                ]);

                /*
                |--------------------------------------------------------------------------
                | Update Revision
                |--------------------------------------------------------------------------
                */

                $revision->update([
                    'status' => 'APPROVED',

                    'approved_by' =>
                        auth()->id(),

                    'approved_at' =>
                        now(),
                ]);
            }
        );

        return redirect()
            ->route(
                'admin.assets.revisions.index',
                [
                    'area' => $area->id,
                ]
            )
            ->with(
                'success',
                'Area revision approved successfully.'
            );
    }

    /**
     * Reject a submitted revision.
     */
    public function reject(
        Request $request,
        $area,
        $revision
    ) {
        /*
        |--------------------------------------------------------------------------
        | Resolve Models Explicitly
        |--------------------------------------------------------------------------
        */

        $area = PropertyArea::findOrFail($area);

        $revision = AreaRevision::findOrFail($revision);

        /*
        |--------------------------------------------------------------------------
        | Verify Relationship
        |--------------------------------------------------------------------------
        */

        $this->verifyRevisionBelongsToArea(
            $area,
            $revision
        );

        /*
        |--------------------------------------------------------------------------
        | Check Status
        |--------------------------------------------------------------------------
        */

        abort_unless(
            strtoupper((string) $revision->status) === 'SUBMITTED',
            422,
            'Only submitted revisions can be rejected.'
        );

        /*
        |--------------------------------------------------------------------------
        | Validate Rejection
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'remarks' => [
                'nullable',
                'string',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Reject
        |--------------------------------------------------------------------------
        */

        $revision->update([
            'status' => 'REJECTED',

            'remarks' =>
                $validated['remarks']
                ?? $revision->remarks,
        ]);

        return redirect()
            ->route(
                'admin.assets.revisions.index',
                [
                    'area' => $area->id,
                ]
            )
            ->with(
                'success',
                'Area revision rejected successfully.'
            );
    }

    /**
     * Delete a revision.
     *
     * Only DRAFT and REJECTED revisions can be deleted.
     */
    public function destroy(
        PropertyArea $area,
        AreaRevision $revision
    ) {
        /*
        |--------------------------------------------------------------------------
        | Verify Relationship
        |--------------------------------------------------------------------------
        */

        $this->verifyRevisionBelongsToArea(
            $area,
            $revision
        );

        /*
        |--------------------------------------------------------------------------
        | Check Status
        |--------------------------------------------------------------------------
        */

        $status = strtoupper(
            (string) $revision->status
        );

        abort_unless(
            in_array(
                $status,
                [
                    'DRAFT',
                    'REJECTED',
                ],
                true
            ),
            422,
            'Only draft or rejected revisions can be deleted.'
        );

        /*
        |--------------------------------------------------------------------------
        | Delete
        |--------------------------------------------------------------------------
        */

        $revision->delete();

        return redirect()
            ->route(
                'admin.assets.revisions.index',
                [
                    'area' => $area->id,
                ]
            )
            ->with(
                'success',
                'Area revision deleted successfully.'
            );
    }

    /**
     * Verify that the revision belongs to the selected property area.
     */
    protected function verifyRevisionBelongsToArea(
        PropertyArea $area,
        AreaRevision $revision
    ): void {
        abort_unless(
            (int) $revision->property_area_id ===
            (int) $area->id,
            404,
            'The selected revision does not belong to this property area.'
        );
    }
}