<?php

namespace App\Http\Controllers\Admin\Construction;

use App\Http\Controllers\Controller;
use App\Models\ConstructionMaterial;
use App\Models\ConstructionMaterialRequest;
use App\Models\ConstructionWorkOrder;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ConstructionMaterialRequestController extends Controller
{
    /**
     * Material Requests
     */
    public function index(
        Project $project,
        Request $request
    ) {
        $query = ConstructionMaterialRequest::query()
            ->where('project_id', $project->id)
            ->with([
                'workOrder',
                'requestedBy',
                'items.material',
            ]);

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where(
                    'request_number',
                    'like',
                    '%' . $search . '%'
                );

                $q->orWhereHas(
                    'workOrder',
                    function ($workOrder) use ($search) {

                        $workOrder->where(
                            'work_order_number',
                            'like',
                            '%' . $search . '%'
                        );
                    }
                );
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Status Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {

            $query->where(
                'status',
                $request->status
            );
        }

        $requests = $query
            ->orderByDesc('request_date')
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return view(
            'construction.materials.requests.index',
            compact(
                'project',
                'requests'
            )
        );
    }


    /**
     * Create Material Request
     */
    public function create(Project $project)
    {
        $materials = ConstructionMaterial::query()
            ->where('status', 'Active')
            ->orderBy('material_name')
            ->get();

        $workOrders = ConstructionWorkOrder::query()
            ->where('project_id', $project->id)
            ->orderByDesc('id')
            ->get();

        return view(
            'construction.materials.requests.create',
            compact(
                'project',
                'materials',
                'workOrders'
            )
        );
    }


    /**
     * Store Material Request
     */
    public function store(
        Request $request,
        Project $project
    ) {
        $validated = $request->validate([

            'construction_work_order_id' => [
                'nullable',
                'integer',
                'exists:construction_work_orders,id',
            ],

            'request_date' => [
                'required',
                'date',
            ],

            'required_date' => [
                'nullable',
                'date',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],

            'items' => [
                'required',
                'array',
                'min:1',
            ],

            'items.*.material_id' => [
                'required',
                'integer',
                'exists:construction_materials,id',
            ],

            'items.*.requested_quantity' => [
                'required',
                'numeric',
                'gt:0',
            ],

            'items.*.unit' => [
                'required',
                'string',
                'max:50',
            ],

            'items.*.remarks' => [
                'nullable',
                'string',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Validate Work Order belongs to Project
        |--------------------------------------------------------------------------
        */

        if (!empty($validated['construction_work_order_id'])) {

            $workOrderExists = ConstructionWorkOrder::query()
                ->where('id', $validated['construction_work_order_id'])
                ->where('project_id', $project->id)
                ->exists();

            if (!$workOrderExists) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'construction_work_order_id' =>
                            'Selected work order does not belong to this project.',
                    ]);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Validate Materials are Active
        |--------------------------------------------------------------------------
        */

        $materialIds = collect($validated['items'])
            ->pluck('material_id')
            ->unique();

        $activeMaterialCount = ConstructionMaterial::query()
            ->whereIn('id', $materialIds)
            ->where('status', 'Active')
            ->count();

        if ($activeMaterialCount !== $materialIds->count()) {

            return back()
                ->withInput()
                ->withErrors([
                    'items' =>
                        'One or more selected materials are inactive.',
                ]);
        }


        DB::transaction(function () use (
            $validated,
            $project
        ) {

            $materialRequest =
                new ConstructionMaterialRequest();

            $materialRequest->project_id =
                $project->id;

            $materialRequest->construction_work_order_id =
                $validated['construction_work_order_id'] ?? null;

            $materialRequest->request_number =
                $this->generateRequestNumber();

            $materialRequest->request_date =
                $validated['request_date'];

            $materialRequest->requested_by =
                Auth::id();

            $materialRequest->required_date =
                $validated['required_date'] ?? null;

            $materialRequest->status =
                'Draft';

            $materialRequest->remarks =
                $validated['remarks'] ?? null;

            $materialRequest->created_by =
                Auth::id();

            $materialRequest->save();


            /*
            |--------------------------------------------------------------------------
            | Items
            |--------------------------------------------------------------------------
            */

            foreach ($validated['items'] as $item) {

                $materialRequest->items()->create([

                    'material_id' =>
                        $item['material_id'],

                    'requested_quantity' =>
                        $item['requested_quantity'],

                    'unit' =>
                        $item['unit'],

                    'remarks' =>
                        $item['remarks'] ?? null,

                ]);
            }
        });


        return redirect()
            ->route(
                'admin.projects.construction.materials.requests.index',
                $project
            )
            ->with(
                'success',
                'Material request created successfully.'
            );
    }


    /**
     * Show Material Request
     */
    public function show(
        Project $project,
        ConstructionMaterialRequest $materialRequest
    ) {
        if (
            $materialRequest->project_id !==
            $project->id
        ) {
            abort(404);
        }

        $materialRequest->load([
            'project',
            'workOrder',
            'requestedBy',
            'approvedBy',
            'creator',
            'updater',
            'items.material',
        ]);

        return view(
            'construction.materials.requests.show',
            compact(
                'project',
                'materialRequest'
            )
        );
    }


    /**
     * Edit Material Request
     */
    public function edit(
        Project $project,
        ConstructionMaterialRequest $materialRequest
    ) {
        if (
            $materialRequest->project_id !==
            $project->id
        ) {
            abort(404);
        }

        if (
            !in_array(
                $materialRequest->status,
                ['Draft', 'Rejected']
            )
        ) {
            return back()->with(
                'error',
                'Only Draft or Rejected requests can be edited.'
            );
        }

        $materialRequest->load('items.material');

        $materials = ConstructionMaterial::query()
            ->where('status', 'Active')
            ->orderBy('material_name')
            ->get();

        $workOrders = ConstructionWorkOrder::query()
            ->where('project_id', $project->id)
            ->orderByDesc('id')
            ->get();

        return view(
            'construction.materials.requests.edit',
            compact(
                'project',
                'materialRequest',
                'materials',
                'workOrders'
            )
        );
    }


    /**
     * Update Material Request
     */
    public function update(
        Request $request,
        Project $project,
        ConstructionMaterialRequest $materialRequest
    ) {
        if (
            $materialRequest->project_id !==
            $project->id
        ) {
            abort(404);
        }

        if (
            !in_array(
                $materialRequest->status,
                ['Draft', 'Rejected']
            )
        ) {
            return back()->with(
                'error',
                'This request cannot be edited in its current status.'
            );
        }

        $validated = $request->validate([

            'construction_work_order_id' => [
                'nullable',
                'integer',
                'exists:construction_work_orders,id',
            ],

            'request_date' => [
                'required',
                'date',
            ],

            'required_date' => [
                'nullable',
                'date',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],

            'items' => [
                'required',
                'array',
                'min:1',
            ],

            'items.*.material_id' => [
                'required',
                'integer',
                'exists:construction_materials,id',
            ],

            'items.*.requested_quantity' => [
                'required',
                'numeric',
                'gt:0',
            ],

            'items.*.unit' => [
                'required',
                'string',
                'max:50',
            ],

            'items.*.remarks' => [
                'nullable',
                'string',
            ],
        ]);


        if (!empty($validated['construction_work_order_id'])) {

            $workOrderExists = ConstructionWorkOrder::query()
                ->where(
                    'id',
                    $validated['construction_work_order_id']
                )
                ->where(
                    'project_id',
                    $project->id
                )
                ->exists();

            if (!$workOrderExists) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'construction_work_order_id' =>
                            'Selected work order does not belong to this project.',
                    ]);
            }
        }


        $materialIds = collect($validated['items'])
            ->pluck('material_id')
            ->unique();

        $activeMaterialCount = ConstructionMaterial::query()
            ->whereIn('id', $materialIds)
            ->where('status', 'Active')
            ->count();

        if ($activeMaterialCount !== $materialIds->count()) {

            return back()
                ->withInput()
                ->withErrors([
                    'items' =>
                        'One or more selected materials are inactive.',
                ]);
        }


        DB::transaction(function () use (
            $validated,
            $materialRequest
        ) {

            $materialRequest->construction_work_order_id =
                $validated['construction_work_order_id'] ?? null;

            $materialRequest->request_date =
                $validated['request_date'];

            $materialRequest->required_date =
                $validated['required_date'] ?? null;

            $materialRequest->remarks =
                $validated['remarks'] ?? null;

            /*
             * Rejected request becomes Draft again
             * when edited.
             */
            if ($materialRequest->status === 'Rejected') {
                $materialRequest->status = 'Draft';
            }

            $materialRequest->updated_by =
                Auth::id();

            $materialRequest->save();


            /*
            |--------------------------------------------------------------------------
            | Replace Items
            |--------------------------------------------------------------------------
            */

            $materialRequest->items()->delete();

            foreach ($validated['items'] as $item) {

                $materialRequest->items()->create([

                    'material_id' =>
                        $item['material_id'],

                    'requested_quantity' =>
                        $item['requested_quantity'],

                    'unit' =>
                        $item['unit'],

                    'remarks' =>
                        $item['remarks'] ?? null,

                ]);
            }
        });


        return redirect()
            ->route(
                'admin.projects.construction.materials.requests.show',
                [
                    'project' => $project->id,
                    'materialRequest' =>
                        $materialRequest->id,
                ]
            )
            ->with(
                'success',
                'Material request updated successfully.'
            );
    }


    /**
     * Submit Request
     */
    public function submit(
        Project $project,
        ConstructionMaterialRequest $materialRequest
    ) {
        $this->validateProjectRequest(
            $project,
            $materialRequest
        );

        if ($materialRequest->status !== 'Draft') {

            return back()->with(
                'error',
                'Only Draft requests can be submitted.'
            );
        }

        if (!$materialRequest->items()->exists()) {

            return back()->with(
                'error',
                'A material request must contain at least one item.'
            );
        }

        $materialRequest->status =
            'Submitted';

        $materialRequest->updated_by =
            Auth::id();

        $materialRequest->save();

        return back()->with(
            'success',
            'Material request submitted for review.'
        );
    }


    /**
     * Start Review
     */
    public function review(
        Project $project,
        ConstructionMaterialRequest $materialRequest
    ) {
        $this->validateProjectRequest(
            $project,
            $materialRequest
        );

        if ($materialRequest->status !== 'Submitted') {

            return back()->with(
                'error',
                'Only Submitted requests can be moved to review.'
            );
        }

        $materialRequest->status =
            'Under Review';

        $materialRequest->updated_by =
            Auth::id();

        $materialRequest->save();

        return back()->with(
            'success',
            'Material request is now under review.'
        );
    }


    /**
     * Approve Request
     */
    public function approve(
        Project $project,
        ConstructionMaterialRequest $materialRequest
    ) {
        $this->validateProjectRequest(
            $project,
            $materialRequest
        );

        if ($materialRequest->status !== 'Under Review') {

            return back()->with(
                'error',
                'Only requests under review can be approved.'
            );
        }

        $materialRequest->status =
            'Approved';

        $materialRequest->approved_by =
            Auth::id();

        $materialRequest->approved_at =
            now();

        $materialRequest->updated_by =
            Auth::id();

        $materialRequest->save();

        return back()->with(
            'success',
            'Material request approved successfully.'
        );
    }


    /**
     * Reject Request
     */
    public function reject(
        Project $project,
        ConstructionMaterialRequest $materialRequest
    ) {
        $this->validateProjectRequest(
            $project,
            $materialRequest
        );

        if ($materialRequest->status !== 'Under Review') {

            return back()->with(
                'error',
                'Only requests under review can be rejected.'
            );
        }

        $materialRequest->status =
            'Rejected';

        $materialRequest->updated_by =
            Auth::id();

        $materialRequest->save();

        return back()->with(
            'success',
            'Material request rejected.'
        );
    }


    /**
     * Cancel Request
     */
    public function cancel(
        Project $project,
        ConstructionMaterialRequest $materialRequest
    ) {
        $this->validateProjectRequest(
            $project,
            $materialRequest
        );

        if (
            in_array(
                $materialRequest->status,
                ['Approved', 'Completed', 'Cancelled']
            )
        ) {
            return back()->with(
                'error',
                'This request cannot be cancelled.'
            );
        }

        $materialRequest->status =
            'Cancelled';

        $materialRequest->updated_by =
            Auth::id();

        $materialRequest->save();

        return back()->with(
            'success',
            'Material request cancelled.'
        );
    }


    /**
     * Validate project/request relation
     */
    private function validateProjectRequest(
        Project $project,
        ConstructionMaterialRequest $materialRequest
    ): void {
        if (
            $materialRequest->project_id !==
            $project->id
        ) {
            abort(404);
        }
    }


    /**
     * Generate Request Number
     *
     * MR-YYYY-000001
     */
    private function generateRequestNumber(): string
    {
        $lastId = ConstructionMaterialRequest::withTrashed()
            ->max('id');

        $nextId = ((int) $lastId) + 1;

        return 'MR-' .
            date('Y') .
            '-' .
            str_pad(
                $nextId,
                6,
                '0',
                STR_PAD_LEFT
            );
    }
}