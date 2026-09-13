<?php

namespace App\Http\Controllers\Admin\Construction;

use App\Http\Controllers\Controller;
use App\Models\ConstructionMaterial;
use App\Models\ConstructionMaterialDelivery;
use App\Models\ConstructionMaterialRequest;
use App\Models\ConstructionMaterialRequestItem;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ConstructionMaterialDeliveryController extends Controller
{
    /**
     * Delivery List
     */
    public function index(
        Project $project,
        Request $request
    ) {
        $query = ConstructionMaterialDelivery::query()
            ->where('project_id', $project->id)
            ->with([
                'materialRequest',
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
                    'delivery_number',
                    'like',
                    '%' . $search . '%'
                );

                $q->orWhere(
                    'challan_number',
                    'like',
                    '%' . $search . '%'
                );

                $q->orWhere(
                    'vehicle_number',
                    'like',
                    '%' . $search . '%'
                );

                $q->orWhereHas(
                    'materialRequest',
                    function ($requestQuery) use ($search) {

                        $requestQuery->where(
                            'request_number',
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


        $deliveries = $query
            ->orderByDesc('delivery_date')
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();


        return view(
            'construction.materials.deliveries.index',
            compact(
                'project',
                'deliveries'
            )
        );
    }


    /**
     * Create Delivery
     *
     * Delivery must originate from
     * an Approved Material Request.
     */
    public function create(
        Project $project,
        ConstructionMaterialRequest $materialRequest
    ) {
        $this->validateProjectRequest(
            $project,
            $materialRequest
        );


        if ($materialRequest->status !== 'Approved') {

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
                    'error',
                    'Only approved material requests can be converted into deliveries.'
                );
        }


        $materialRequest->load([
            'items.material',
            'workOrder',
        ]);


        return view(
            'construction.materials.deliveries.create',
            compact(
                'project',
                'materialRequest'
            )
        );
    }


    /**
     * Store Delivery
     */
    public function store(
        Request $request,
        Project $project
    ) {
        $validated = $request->validate([

            'material_request_id' => [
                'required',
                'integer',
                'exists:construction_material_requests,id',
            ],

            'delivery_date' => [
                'required',
                'date',
            ],

            'vehicle_number' => [
                'nullable',
                'string',
                'max:100',
            ],

            'challan_number' => [
                'nullable',
                'string',
                'max:100',
            ],

            'challan_date' => [
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

            'items.*.ordered_quantity' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'items.*.delivered_quantity' => [
                'required',
                'numeric',
                'gt:0',
            ],

            'items.*.unit' => [
                'required',
                'string',
                'max:50',
            ],

            'items.*.batch_number' => [
                'nullable',
                'string',
                'max:100',
            ],

            'items.*.remarks' => [
                'nullable',
                'string',
            ],
        ]);


        $materialRequest =
            ConstructionMaterialRequest::with('items')
                ->findOrFail(
                    $validated['material_request_id']
                );


        /*
        |--------------------------------------------------------------------------
        | Project Validation
        |--------------------------------------------------------------------------
        */

        $this->validateProjectRequest(
            $project,
            $materialRequest
        );


        /*
        |--------------------------------------------------------------------------
        | Request Status
        |--------------------------------------------------------------------------
        */

        if ($materialRequest->status !== 'Approved') {

            return back()
                ->withInput()
                ->withErrors([
                    'material_request_id' =>
                        'Only approved material requests can have deliveries.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Validate Delivery Items
        |--------------------------------------------------------------------------
        */

        $requestItems = $materialRequest
            ->items
            ->keyBy('material_id');


        foreach ($validated['items'] as $item) {

            if (
                !$requestItems->has(
                    $item['material_id']
                )
            ) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'items' =>
                            'A selected material does not belong to the material request.',
                    ]);
            }

            $requestItem =
                $requestItems->get(
                    $item['material_id']
                );

            if (
                !empty($item['ordered_quantity']) &&
                (float) $item['ordered_quantity'] >
                (float) $requestItem->requested_quantity
            ) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'items' =>
                            'Ordered quantity cannot exceed requested quantity.',
                    ]);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Create Delivery
        |--------------------------------------------------------------------------
        */

        $delivery = DB::transaction(
            function () use (
                $validated,
                $project,
                $materialRequest
            ) {

                $delivery =
                    new ConstructionMaterialDelivery();

                $delivery->project_id =
                    $project->id;

                $delivery->material_request_id =
                    $materialRequest->id;

                $delivery->delivery_number =
                    $this->generateDeliveryNumber();

                $delivery->delivery_date =
                    $validated['delivery_date'];

                $delivery->vehicle_number =
                    $validated['vehicle_number'] ?? null;

                $delivery->challan_number =
                    $validated['challan_number'] ?? null;

                $delivery->challan_date =
                    $validated['challan_date'] ?? null;

                /*
                 * New delivery starts as
                 * Partially Delivered or Delivered
                 * based on quantities.
                 */
                $delivery->status =
                    'Delivered';

                $delivery->remarks =
                    $validated['remarks'] ?? null;

                $delivery->created_by =
                    Auth::id();

                $delivery->save();


                /*
                |--------------------------------------------------------------------------
                | Delivery Items
                |--------------------------------------------------------------------------
                */

                foreach (
                    $validated['items']
                    as $item
                ) {

                    $delivery->items()->create([

                        'material_id' =>
                            $item['material_id'],

                        'ordered_quantity' =>
                            $item['ordered_quantity'] ?? null,

                        'delivered_quantity' =>
                            $item['delivered_quantity'],

                        'unit' =>
                            $item['unit'],

                        'batch_number' =>
                            $item['batch_number'] ?? null,

                        'remarks' =>
                            $item['remarks'] ?? null,
                    ]);
                }


                return $delivery;
            }
        );


        return redirect()
            ->route(
                'admin.projects.construction.materials.deliveries.show',
                [
                    'project' => $project->id,
                    'materialDelivery' =>
                        $delivery->id,
                ]
            )
            ->with(
                'success',
                'Material delivery recorded successfully.'
            );
    }


    /**
     * Show Delivery
     */
    public function show(
        Project $project,
        ConstructionMaterialDelivery $materialDelivery
    ) {
        $this->validateProjectDelivery(
            $project,
            $materialDelivery
        );


        $materialDelivery->load([
            'materialRequest',
            'materialRequest.workOrder',
            'items.material',
            'creator',
            'updater',
        ]);


        return view(
            'construction.materials.deliveries.show',
            compact(
                'project',
                'materialDelivery'
            )
        );
    }


    /**
     * Edit Delivery
     */
    public function edit(
        Project $project,
        ConstructionMaterialDelivery $materialDelivery
    ) {
        $this->validateProjectDelivery(
            $project,
            $materialDelivery
        );


        if (
            in_array(
                $materialDelivery->status,
                ['Received', 'Cancelled']
            )
        ) {

            return back()->with(
                'error',
                'This delivery cannot be edited.'
            );
        }


        $materialDelivery->load([
            'materialRequest.items.material',
            'items.material',
        ]);


        return view(
            'construction.materials.deliveries.edit',
            compact(
                'project',
                'materialDelivery'
            )
        );
    }


    /**
     * Update Delivery
     */
    public function update(
        Request $request,
        Project $project,
        ConstructionMaterialDelivery $materialDelivery
    ) {
        $this->validateProjectDelivery(
            $project,
            $materialDelivery
        );


        if (
            in_array(
                $materialDelivery->status,
                ['Received', 'Cancelled']
            )
        ) {

            return back()->with(
                'error',
                'This delivery cannot be edited.'
            );
        }


        $validated = $request->validate([

            'delivery_date' => [
                'required',
                'date',
            ],

            'vehicle_number' => [
                'nullable',
                'string',
                'max:100',
            ],

            'challan_number' => [
                'nullable',
                'string',
                'max:100',
            ],

            'challan_date' => [
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

            'items.*.ordered_quantity' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'items.*.delivered_quantity' => [
                'required',
                'numeric',
                'gt:0',
            ],

            'items.*.unit' => [
                'required',
                'string',
                'max:50',
            ],

            'items.*.batch_number' => [
                'nullable',
                'string',
                'max:100',
            ],

            'items.*.remarks' => [
                'nullable',
                'string',
            ],
        ]);


        DB::transaction(
            function () use (
                $validated,
                $materialDelivery
            ) {

                $materialDelivery->delivery_date =
                    $validated['delivery_date'];

                $materialDelivery->vehicle_number =
                    $validated['vehicle_number'] ?? null;

                $materialDelivery->challan_number =
                    $validated['challan_number'] ?? null;

                $materialDelivery->challan_date =
                    $validated['challan_date'] ?? null;

                $materialDelivery->remarks =
                    $validated['remarks'] ?? null;

                $materialDelivery->updated_by =
                    Auth::id();

                $materialDelivery->save();


                $materialDelivery->items()->delete();


                foreach (
                    $validated['items']
                    as $item
                ) {

                    $materialDelivery->items()->create([

                        'material_id' =>
                            $item['material_id'],

                        'ordered_quantity' =>
                            $item['ordered_quantity'] ?? null,

                        'delivered_quantity' =>
                            $item['delivered_quantity'],

                        'unit' =>
                            $item['unit'],

                        'batch_number' =>
                            $item['batch_number'] ?? null,

                        'remarks' =>
                            $item['remarks'] ?? null,
                    ]);
                }
            }
        );


        return redirect()
            ->route(
                'admin.projects.construction.materials.deliveries.show',
                [
                    'project' => $project->id,
                    'materialDelivery' =>
                        $materialDelivery->id,
                ]
            )
            ->with(
                'success',
                'Material delivery updated successfully.'
            );
    }


    /**
     * Mark as Received
     *
     * Actual receipt/inspection will be
     * handled by the next module.
     */
    public function receive(
        Project $project,
        ConstructionMaterialDelivery $materialDelivery
    ) {
        $this->validateProjectDelivery(
            $project,
            $materialDelivery
        );


        if (
            !in_array(
                $materialDelivery->status,
                ['Delivered', 'Partially Delivered']
            )
        ) {

            return back()->with(
                'error',
                'Only delivered materials can be marked as received.'
            );
        }


        $materialDelivery->status =
            'Received';

        $materialDelivery->updated_by =
            Auth::id();

        $materialDelivery->save();


        return back()->with(
            'success',
            'Material delivery marked as received.'
        );
    }


    /**
     * Cancel Delivery
     */
    public function cancel(
        Project $project,
        ConstructionMaterialDelivery $materialDelivery
    ) {
        $this->validateProjectDelivery(
            $project,
            $materialDelivery
        );


        if (
            in_array(
                $materialDelivery->status,
                ['Received', 'Cancelled']
            )
        ) {

            return back()->with(
                'error',
                'This delivery cannot be cancelled.'
            );
        }


        $materialDelivery->status =
            'Cancelled';

        $materialDelivery->updated_by =
            Auth::id();

        $materialDelivery->save();


        return back()->with(
            'success',
            'Material delivery cancelled.'
        );
    }


    /**
     * Validate Request belongs to Project
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
     * Validate Delivery belongs to Project
     */
    private function validateProjectDelivery(
        Project $project,
        ConstructionMaterialDelivery $materialDelivery
    ): void {
        if (
            $materialDelivery->project_id !==
            $project->id
        ) {
            abort(404);
        }
    }


    /**
     * Generate Delivery Number
     *
     * MD-YYYY-000001
     */
    private function generateDeliveryNumber(): string
    {
        $lastId =
            ConstructionMaterialDelivery::withTrashed()
                ->max('id');

        $nextId =
            ((int) $lastId) + 1;

        return 'MD-' .
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