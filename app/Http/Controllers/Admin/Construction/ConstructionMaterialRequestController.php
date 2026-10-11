<?php

namespace App\Http\Controllers\Admin\Construction;

use App\Http\Controllers\Controller;
use App\Models\ConstructionMaterial;
use App\Models\ConstructionMaterialRequest;
use App\Models\ConstructionMaterialRequestItem;
use App\Models\ConstructionMaterialRequirement;
use App\Models\ConstructionWorkOrder;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ConstructionMaterialRequestController extends Controller
{
    /**
     * List Material Requests
     */
    public function index(
        Project $project,
        Request $request
    ): View {
        $query = ConstructionMaterialRequest::query()
            ->where('project_id', $project->id)
            ->with([
                'workOrder',
                'requestedBy',
                'items.material',
                'items.materialRequirement',
            ]);

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->where(
                    'request_number',
                    'like',
                    '%' . $search . '%'
                );

                $q->orWhereHas(
                    'workOrder',
                    function ($workOrderQuery) use ($search) {

                        $workOrderQuery
                            ->where(
                                'work_order_number',
                                'like',
                                '%' . $search . '%'
                            )
                            ->orWhere(
                                'work_order_title',
                                'like',
                                '%' . $search . '%'
                            );
                    }
                );

                $q->orWhereHas(
                    'items.material',
                    function ($materialQuery) use ($search) {

                        $materialQuery
                            ->where(
                                'material_code',
                                'like',
                                '%' . $search . '%'
                            )
                            ->orWhere(
                                'material_name',
                                'like',
                                '%' . $search . '%'
                            );
                    }
                );

                $q->orWhereHas(
                    'items.materialRequirement',
                    function ($requirementQuery) use ($search) {

                        $requirementQuery
                            ->where(
                                'purpose',
                                'like',
                                '%' . $search . '%'
                            );
                    }
                );
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Status
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {

            $query->where(
                'status',
                $request->status
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $requests = $query
            ->orderByDesc('request_date')
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Summary
        |--------------------------------------------------------------------------
        */

        $summaryQuery = ConstructionMaterialRequest::query()
            ->where(
                'project_id',
                $project->id
            );

        $summary = [

            'total' => (clone $summaryQuery)
                ->count(),

            'draft' => (clone $summaryQuery)
                ->where('status', 'Draft')
                ->count(),

            'submitted' => (clone $summaryQuery)
                ->where('status', 'Submitted')
                ->count(),

            'under_review' => (clone $summaryQuery)
                ->where('status', 'Under Review')
                ->count(),

            'approved' => (clone $summaryQuery)
                ->where('status', 'Approved')
                ->count(),

            'changes_requested' => (clone $summaryQuery)
                ->where(
                    'status',
                    'Changes Requested'
                )
                ->count(),
        ];

        return view(
            'construction.materials.requests.index',
            compact(
                'project',
                'requests',
                'summary'
            )
        );
    }


    /**
     * Create Request
     */
    public function create(
        Project $project
    ): View {

        $materials = ConstructionMaterial::query()
            ->where('status', 'Active')
            ->orderBy('material_name')
            ->get();

        $workOrders = ConstructionWorkOrder::query()
            ->where(
                'project_id',
                $project->id
            )
            ->orderBy(
                'work_order_number'
            )
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Available Requirements
        |--------------------------------------------------------------------------
        |
        | Cancelled / Fulfilled requirements are not offered.
        |
        */

        $requirements =
            ConstructionMaterialRequirement::query()
                ->where(
                    'project_id',
                    $project->id
                )
                ->whereNotIn(
                    'status',
                    [
                        'Cancelled',
                        'Fulfilled',
                    ]
                )
                ->with([
                    'material',
                    'workOrder',
                ])
                ->orderBy(
                    'required_date'
                )
                ->orderBy('id')
                ->get();

        return view(
            'construction.materials.requests.create',
            compact(
                'project',
                'materials',
                'workOrders',
                'requirements'
            )
        );
    }


    /**
     * Store Request
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

            'items.*.material_requirement_id' => [
                'nullable',
                'integer',
                'exists:construction_material_requirements,id',
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
        | Validate Work Order
        |--------------------------------------------------------------------------
        */

        if (
            !empty(
                $validated['construction_work_order_id']
            )
        ) {

            $workOrder =
                ConstructionWorkOrder::query()
                    ->where(
                        'id',
                        $validated[
                            'construction_work_order_id'
                        ]
                    )
                    ->where(
                        'project_id',
                        $project->id
                    )
                    ->first();

            if (!$workOrder) {

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
        | Validate Request Items
        |--------------------------------------------------------------------------
        */

        $preparedItems = [];

        foreach (
            $validated['items']
            as $index => $item
        ) {

            /*
            |--------------------------------------------------------------------------
            | Validate Material
            |--------------------------------------------------------------------------
            */

            $material =
                ConstructionMaterial::query()
                    ->where(
                        'id',
                        $item['material_id']
                    )
                    ->where(
                        'status',
                        'Active'
                    )
                    ->first();

            if (!$material) {

                return back()
                    ->withInput()
                    ->withErrors([
                        "items.$index.material_id" =>
                            'Selected material is not active.',
                    ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Requirement
            |--------------------------------------------------------------------------
            */

            $requirement = null;

            if (
                !empty(
                    $item['material_requirement_id']
                )
            ) {

                $requirement =
                    ConstructionMaterialRequirement::query()
                        ->where(
                            'id',
                            $item[
                                'material_requirement_id'
                            ]
                        )
                        ->where(
                            'project_id',
                            $project->id
                        )
                        ->first();

                if (!$requirement) {

                    return back()
                        ->withInput()
                        ->withErrors([
                            "items.$index.material_requirement_id" =>
                                'Selected material requirement does not belong to this project.',
                        ]);
                }

                /*
                |--------------------------------------------------------------------------
                | Requirement Status
                |--------------------------------------------------------------------------
                */

                if (
                    in_array(
                        $requirement->status,
                        [
                            'Cancelled',
                            'Fulfilled',
                        ],
                        true
                    )
                ) {

                    return back()
                        ->withInput()
                        ->withErrors([
                            "items.$index.material_requirement_id" =>
                                'This material requirement is no longer available for requests.',
                        ]);
                }

                /*
                |--------------------------------------------------------------------------
                | Material Must Match Requirement
                |--------------------------------------------------------------------------
                */

                if (
                    (int) $requirement->material_id !==
                    (int) $item['material_id']
                ) {

                    return back()
                        ->withInput()
                        ->withErrors([
                            "items.$index.material_id" =>
                                'Selected material does not match the material requirement.',
                        ]);
                }

                /*
                |--------------------------------------------------------------------------
                | Unit Should Match Requirement
                |--------------------------------------------------------------------------
                */

                if (
                    strtolower(
                        trim($requirement->unit)
                    ) !==
                    strtolower(
                        trim($item['unit'])
                    )
                ) {

                    return back()
                        ->withInput()
                        ->withErrors([
                            "items.$index.unit" =>
                                'Selected unit does not match the material requirement unit.',
                        ]);
                }

                /*
                |--------------------------------------------------------------------------
                | Already Requested
                |--------------------------------------------------------------------------
                */

                $alreadyRequested =
                    ConstructionMaterialRequestItem::query()
                        ->where(
                            'material_requirement_id',
                            $requirement->id
                        )
                        ->whereHas(
                            'materialRequest',
                            function ($query) {

                                $query->whereNotIn(
                                    'status',
                                    [
                                        'Rejected',
                                        'Cancelled',
                                    ]
                                );
                            }
                        )
                        ->sum(
                            'requested_quantity'
                        );

                /*
                |--------------------------------------------------------------------------
                | Remaining Requirement
                |--------------------------------------------------------------------------
                */

                $remaining =
                    (float)
                        $requirement->required_quantity
                    -
                    (float)
                        $alreadyRequested;

                $requestedQuantity =
                    (float)
                        $item['requested_quantity'];

                if ($remaining <= 0) {

                    return back()
                        ->withInput()
                        ->withErrors([
                            "items.$index.requested_quantity" =>
                                'This material requirement has already been fully requested.',
                        ]);
                }

                if (
                    $requestedQuantity >
                    $remaining
                ) {

                    return back()
                        ->withInput()
                        ->withErrors([
                            "items.$index.requested_quantity" =>
                                'Requested quantity exceeds the remaining requirement. Remaining: '
                                . number_format(
                                    $remaining,
                                    4
                                )
                                . ' '
                                . $requirement->unit,
                        ]);
                }
            }

            $preparedItems[] = [

                'material_requirement_id' =>
                    $item[
                        'material_requirement_id'
                    ] ?? null,

                'material_id' =>
                    $item['material_id'],

                'requested_quantity' =>
                    $item['requested_quantity'],

                'unit' =>
                    $item['unit'],

                'remarks' =>
                    $item['remarks'] ?? null,
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Create Request
        |--------------------------------------------------------------------------
        */

        $materialRequest =
            DB::transaction(
                function () use (
                    $validated,
                    $project,
                    $preparedItems
                ) {

                    $materialRequest =
                        new ConstructionMaterialRequest();

                    $materialRequest->project_id =
                        $project->id;

                    $materialRequest->construction_work_order_id =
                        $validated[
                            'construction_work_order_id'
                        ] ?? null;

                    $materialRequest->request_number =
                        $this->generateRequestNumber();

                    $materialRequest->request_date =
                        $validated['request_date'];

                    $materialRequest->requested_by =
                        Auth::id();

                    $materialRequest->required_date =
                        $validated[
                            'required_date'
                        ] ?? null;

                    $materialRequest->status =
                        'Draft';

                    $materialRequest->remarks =
                        $validated[
                            'remarks'
                        ] ?? null;

                    $materialRequest->created_by =
                        Auth::id();

                    $materialRequest->save();

                    /*
                    |--------------------------------------------------------------------------
                    | Items
                    |--------------------------------------------------------------------------
                    */

                    foreach (
                        $preparedItems
                        as $item
                    ) {

                        $materialRequest
                            ->items()
                            ->create($item);
                    }

                    return $materialRequest;
                }
            );

        return redirect()
            ->route(
                'admin.projects.construction.materials.requests.show',
                [
                    'project' =>
                        $project->id,

                    'materialRequest' =>
                        $materialRequest->id,
                ]
            )
            ->with(
                'success',
                'Material request created successfully.'
            );
    }


    /**
     * Show Request
     */
    public function show(
        Project $project,
        ConstructionMaterialRequest $materialRequest
    ): View {

        $this->validateProjectRequest(
            $project,
            $materialRequest
        );

        $materialRequest->load([
            'project',
            'workOrder',
            'requestedBy',
            'approvedBy',
            'creator',
            'updater',
            'items.material',
            'items.materialRequirement',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Requirement Summary
        |--------------------------------------------------------------------------
        */

        $requirementSummary = [];

        foreach (
            $materialRequest->items
            as $item
        ) {

            if (
                !$item->materialRequirement
            ) {
                continue;
            }

            $requirement =
                $item->materialRequirement;

            $alreadyRequested =
                ConstructionMaterialRequestItem::query()
                    ->where(
                        'material_requirement_id',
                        $requirement->id
                    )
                    ->whereHas(
                        'materialRequest',
                        function ($query) {

                            $query->whereNotIn(
                                'status',
                                [
                                    'Rejected',
                                    'Cancelled',
                                ]
                            );
                        }
                    )
                    ->sum(
                        'requested_quantity'
                    );

            $remaining =
                max(
                    0,
                    (float)
                        $requirement->required_quantity
                    -
                    (float)
                        $alreadyRequested
                );

            $requirementSummary[
                $requirement->id
            ] = [

                'required' =>
                    (float)
                    $requirement->required_quantity,

                'requested' =>
                    (float)
                    $alreadyRequested,

                'remaining' =>
                    $remaining,

                'unit' =>
                    $requirement->unit,
            ];
        }

        return view(
            'construction.materials.requests.show',
            compact(
                'project',
                'materialRequest',
                'requirementSummary'
            )
        );
    }


    /**
     * Edit Request
     */
    public function edit(
        Project $project,
        ConstructionMaterialRequest $materialRequest
    ): View {

        $this->validateProjectRequest(
            $project,
            $materialRequest
        );

        if (
            !in_array(
                $materialRequest->status,
                [
                    'Draft',
                    'Changes Requested',
                ],
                true
            )
        ) {

            return redirect()
                ->route(
                    'admin.projects.construction.materials.requests.show',
                    [
                        'project' =>
                            $project->id,

                        'materialRequest' =>
                            $materialRequest->id,
                    ]
                )
                ->with(
                    'error',
                    'This request cannot be edited in its current status.'
                );
        }

        $materialRequest->load([
            'items.material',
            'items.materialRequirement',
        ]);

        $materials = ConstructionMaterial::query()
            ->where('status', 'Active')
            ->orderBy('material_name')
            ->get();

        $workOrders = ConstructionWorkOrder::query()
            ->where(
                'project_id',
                $project->id
            )
            ->orderBy(
                'work_order_number'
            )
            ->get();

        $requirements =
            ConstructionMaterialRequirement::query()
                ->where(
                    'project_id',
                    $project->id
                )
                ->whereNotIn(
                    'status',
                    [
                        'Cancelled',
                        'Fulfilled',
                    ]
                )
                ->with([
                    'material',
                    'workOrder',
                ])
                ->orderBy(
                    'required_date'
                )
                ->orderBy('id')
                ->get();

        return view(
            'construction.materials.requests.edit',
            compact(
                'project',
                'materialRequest',
                'materials',
                'workOrders',
                'requirements'
            )
        );
    }


    /**
     * Update Request
     */
    public function update(
        Request $request,
        Project $project,
        ConstructionMaterialRequest $materialRequest
    ) {

        $this->validateProjectRequest(
            $project,
            $materialRequest
        );

        if (
            !in_array(
                $materialRequest->status,
                [
                    'Draft',
                    'Changes Requested',
                ],
                true
            )
        ) {

            return back()
                ->with(
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

            'items.*.material_requirement_id' => [
                'nullable',
                'integer',
                'exists:construction_material_requirements,id',
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
        | Work Order Validation
        |--------------------------------------------------------------------------
        */

        if (
            !empty(
                $validated['construction_work_order_id']
            )
        ) {

            $validWorkOrder =
                ConstructionWorkOrder::query()
                    ->where(
                        'id',
                        $validated[
                            'construction_work_order_id'
                        ]
                    )
                    ->where(
                        'project_id',
                        $project->id
                    )
                    ->exists();

            if (!$validWorkOrder) {

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
        | Validate Items
        |--------------------------------------------------------------------------
        */

        $preparedItems = [];

        foreach (
            $validated['items']
            as $index => $item
        ) {

            $material =
                ConstructionMaterial::query()
                    ->where(
                        'id',
                        $item['material_id']
                    )
                    ->where(
                        'status',
                        'Active'
                    )
                    ->first();

            if (!$material) {

                return back()
                    ->withInput()
                    ->withErrors([
                        "items.$index.material_id" =>
                            'Selected material is not active.',
                    ]);
            }

            $requirement = null;

            if (
                !empty(
                    $item['material_requirement_id']
                )
            ) {

                $requirement =
                    ConstructionMaterialRequirement::query()
                        ->where(
                            'id',
                            $item[
                                'material_requirement_id'
                            ]
                        )
                        ->where(
                            'project_id',
                            $project->id
                        )
                        ->first();

                if (!$requirement) {

                    return back()
                        ->withInput()
                        ->withErrors([
                            "items.$index.material_requirement_id" =>
                                'Selected requirement does not belong to this project.',
                        ]);
                }

                if (
                    in_array(
                        $requirement->status,
                        [
                            'Cancelled',
                            'Fulfilled',
                        ],
                        true
                    )
                ) {

                    return back()
                        ->withInput()
                        ->withErrors([
                            "items.$index.material_requirement_id" =>
                                'Selected requirement is no longer available.',
                        ]);
                }

                if (
                    (int) $requirement->material_id !==
                    (int) $item['material_id']
                ) {

                    return back()
                        ->withInput()
                        ->withErrors([
                            "items.$index.material_id" =>
                                'Selected material does not match the requirement.',
                        ]);
                }

                if (
                    strtolower(
                        trim($requirement->unit)
                    ) !==
                    strtolower(
                        trim($item['unit'])
                    )
                ) {

                    return back()
                        ->withInput()
                        ->withErrors([
                            "items.$index.unit" =>
                                'Selected unit does not match the requirement.',
                        ]);
                }

                /*
                |--------------------------------------------------------------------------
                | Existing Quantity From Other Requests
                |--------------------------------------------------------------------------
                |
                | Exclude the current request while editing.
                |
                */

                $alreadyRequested =
                    ConstructionMaterialRequestItem::query()
                        ->where(
                            'material_requirement_id',
                            $requirement->id
                        )
                        ->where(
                            'material_request_id',
                            '!=',
                            $materialRequest->id
                        )
                        ->whereHas(
                            'materialRequest',
                            function ($query) {

                                $query->whereNotIn(
                                    'status',
                                    [
                                        'Rejected',
                                        'Cancelled',
                                    ]
                                );
                            }
                        )
                        ->sum(
                            'requested_quantity'
                        );

                $remaining =
                    (float)
                        $requirement->required_quantity
                    -
                    (float)
                        $alreadyRequested;

                if (
                    (float)
                        $item['requested_quantity']
                    >
                    $remaining
                ) {

                    return back()
                        ->withInput()
                        ->withErrors([
                            "items.$index.requested_quantity" =>
                                'Requested quantity exceeds the remaining requirement. Remaining: '
                                . number_format(
                                    max(
                                        0,
                                        $remaining
                                    ),
                                    4
                                )
                                . ' '
                                . $requirement->unit,
                        ]);
                }
            }

            $preparedItems[] = [

                'material_requirement_id' =>
                    $item[
                        'material_requirement_id'
                    ] ?? null,

                'material_id' =>
                    $item['material_id'],

                'requested_quantity' =>
                    $item['requested_quantity'],

                'unit' =>
                    $item['unit'],

                'remarks' =>
                    $item['remarks'] ?? null,
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Update
        |--------------------------------------------------------------------------
        */

        DB::transaction(
            function () use (
                $materialRequest,
                $validated,
                $preparedItems
            ) {

                $materialRequest->construction_work_order_id =
                    $validated[
                        'construction_work_order_id'
                    ] ?? null;

                $materialRequest->request_date =
                    $validated['request_date'];

                $materialRequest->required_date =
                    $validated[
                        'required_date'
                    ] ?? null;

                $materialRequest->remarks =
                    $validated[
                        'remarks'
                    ] ?? null;

                /*
                |--------------------------------------------------------------------------
                | Changes Requested → Draft
                |--------------------------------------------------------------------------
                */

                if (
                    $materialRequest->status ===
                    'Changes Requested'
                ) {

                    $materialRequest->status =
                        'Draft';
                }

                $materialRequest->updated_by =
                    Auth::id();

                $materialRequest->save();

                /*
                |--------------------------------------------------------------------------
                | Replace Items
                |--------------------------------------------------------------------------
                */

                $materialRequest
                    ->items()
                    ->delete();

                foreach (
                    $preparedItems
                    as $item
                ) {

                    $materialRequest
                        ->items()
                        ->create($item);
                }
            }
        );

        return redirect()
            ->route(
                'admin.projects.construction.materials.requests.show',
                [
                    'project' =>
                        $project->id,

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

        if (
            $materialRequest->status !==
            'Draft'
        ) {

            return back()
                ->with(
                    'error',
                    'Only draft requests can be submitted.'
                );
        }

        $materialRequest->status =
            'Submitted';

        $materialRequest->updated_by =
            Auth::id();

        $materialRequest->save();

        return back()
            ->with(
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

        if (
            $materialRequest->status !==
            'Submitted'
        ) {

            return back()
                ->with(
                    'error',
                    'Only submitted requests can be moved to review.'
                );
        }

        $materialRequest->status =
            'Under Review';

        $materialRequest->updated_by =
            Auth::id();

        $materialRequest->save();

        return back()
            ->with(
                'success',
                'Material request moved to review.'
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

        if (
            $materialRequest->status !==
            'Under Review'
        ) {

            return back()
                ->with(
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

        return back()
            ->with(
                'success',
                'Material request approved successfully.'
            );
    }


    /**
     * Request Changes
     */
    public function requestChanges(
        Project $project,
        ConstructionMaterialRequest $materialRequest
    ) {

        $this->validateProjectRequest(
            $project,
            $materialRequest
        );

        if (
            $materialRequest->status !==
            'Under Review'
        ) {

            return back()
                ->with(
                    'error',
                    'Only requests under review can have changes requested.'
                );
        }

        $materialRequest->status =
            'Changes Requested';

        $materialRequest->updated_by =
            Auth::id();

        $materialRequest->save();

        return back()
            ->with(
                'success',
                'Changes requested for this material request.'
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

        if (
            $materialRequest->status !==
            'Under Review'
        ) {

            return back()
                ->with(
                    'error',
                    'Only requests under review can be rejected.'
                );
        }

        $materialRequest->status =
            'Rejected';

        $materialRequest->updated_by =
            Auth::id();

        $materialRequest->save();

        return back()
            ->with(
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
                [
                    'Approved',
                    'Rejected',
                    'Cancelled',
                ],
                true
            )
        ) {

            return back()
                ->with(
                    'error',
                    'This material request cannot be cancelled.'
                );
        }

        $materialRequest->status =
            'Cancelled';

        $materialRequest->updated_by =
            Auth::id();

        $materialRequest->save();

        return back()
            ->with(
                'success',
                'Material request cancelled successfully.'
            );
    }


    /**
     * Validate Request Belongs to Project
     */
    private function validateProjectRequest(
        Project $project,
        ConstructionMaterialRequest $materialRequest
    ): void {

        if (
            (int) $materialRequest->project_id !==
            (int) $project->id
        ) {
            abort(404);
        }
    }


    /**
     * Generate Request Number
     *
     * Format:
     * MR-2026-000001
     */
    private function generateRequestNumber(): string
    {
        $lastId =
            ConstructionMaterialRequest::withTrashed()
                ->max('id');

        $nextId =
            ((int) $lastId) + 1;

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