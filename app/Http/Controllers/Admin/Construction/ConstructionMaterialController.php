<?php

namespace App\Http\Controllers\Admin\Construction;

use App\Http\Controllers\Controller;
use App\Models\ConstructionMaterial;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ConstructionMaterialController extends Controller
{
    /**
     * Materials Dashboard
     */
    public function index(Project $project)
    {
        $totalMaterials = ConstructionMaterial::count();

        $activeMaterials = ConstructionMaterial::where(
            'status',
            'Active'
        )->count();

        $inactiveMaterials = ConstructionMaterial::where(
            'status',
            'Inactive'
        )->count();

        $stockItems = $project->materialStocks()->count();

        return view(
            'construction.materials.index',
            compact(
                'project',
                'totalMaterials',
                'activeMaterials',
                'inactiveMaterials',
                'stockItems'
            )
        );
    }

    /**
     * Material Master
     */
    public function master(Project $project, Request $request)
    {
        $query = ConstructionMaterial::query();

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where(
                    'material_code',
                    'like',
                    '%' . $search . '%'
                );

                $q->orWhere(
                    'material_name',
                    'like',
                    '%' . $search . '%'
                );

                $q->orWhere(
                    'category',
                    'like',
                    '%' . $search . '%'
                );
            });
        }

        if ($request->filled('status')) {

            $query->where(
                'status',
                $request->status
            );
        }

        $materials = $query
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return view(
            'construction.materials.master.index',
            compact(
                'project',
                'materials'
            )
        );
    }

    /**
     * Create Material
     */
    public function create(Project $project)
    {
        return view(
            'construction.materials.master.create',
            compact('project')
        );
    }

    /**
     * Store Material
     */
    public function store(
        Request $request,
        Project $project
    ) {
        $validated = $request->validate([

            'material_name' => [
                'required',
                'string',
                'max:255'
            ],

            'category' => [
                'nullable',
                'string',
                'max:100'
            ],

            'specification' => [
                'nullable',
                'string',
                'max:255'
            ],

            'unit' => [
                'nullable',
                'string',
                'max:50'
            ],

            'description' => [
                'nullable',
                'string'
            ],

            'status' => [
                'required',
                'in:Active,Inactive'
            ],
        ]);

        DB::transaction(function () use (
            $validated
        ) {

            $material = new ConstructionMaterial();

            $material->material_code =
                $this->generateMaterialCode();

            $material->material_name =
                $validated['material_name'];

            $material->category =
                $validated['category'] ?? null;

            $material->specification =
                $validated['specification'] ?? null;

            $material->unit =
                $validated['unit'] ?? null;

            $material->description =
                $validated['description'] ?? null;

            $material->status =
                $validated['status'];

            $material->created_by =
                Auth::id();

            $material->save();
        });

        return redirect()
            ->route(
                'admin.projects.construction.materials.master.index',
                $project
            )
            ->with(
                'success',
                'Material created successfully.'
            );
    }

    /**
     * Show Material
     */
    public function show(
        Project $project,
        ConstructionMaterial $material
    ) {
        return view(
            'construction.materials.master.show',
            compact(
                'project',
                'material'
            )
        );
    }

    /**
     * Edit Material
     */
    public function edit(
        Project $project,
        ConstructionMaterial $material
    ) {
        return view(
            'construction.materials.master.edit',
            compact(
                'project',
                'material'
            )
        );
    }

    /**
     * Update Material
     */
    public function update(
        Request $request,
        Project $project,
        ConstructionMaterial $material
    ) {
        $validated = $request->validate([

            'material_name' => [
                'required',
                'string',
                'max:255'
            ],

            'category' => [
                'nullable',
                'string',
                'max:100'
            ],

            'specification' => [
                'nullable',
                'string',
                'max:255'
            ],

            'unit' => [
                'nullable',
                'string',
                'max:50'
            ],

            'description' => [
                'nullable',
                'string'
            ],

            'status' => [
                'required',
                'in:Active,Inactive'
            ],
        ]);

        $material->material_name =
            $validated['material_name'];

        $material->category =
            $validated['category'] ?? null;

        $material->specification =
            $validated['specification'] ?? null;

        $material->unit =
            $validated['unit'] ?? null;

        $material->description =
            $validated['description'] ?? null;

        $material->status =
            $validated['status'];

        $material->updated_by =
            Auth::id();

        $material->save();

        return redirect()
            ->route(
                'admin.projects.construction.materials.master.show',
                [
                    'project' => $project->id,
                    'material' => $material->id,
                ]
            )
            ->with(
                'success',
                'Material updated successfully.'
            );
    }

    /**
     * Delete Material
     */
    public function destroy(
        Project $project,
        ConstructionMaterial $material
    ) {
        /*
         * Prevent deletion if material has
         * already been used in construction records.
         */
        if (
            $material->requirements()->exists() ||
            $material->requestItems()->exists() ||
            $material->deliveryItems()->exists() ||
            $material->receiptItems()->exists() ||
            $material->stocks()->exists() ||
            $material->transactions()->exists()
        ) {
            return back()->with(
                'error',
                'This material cannot be deleted because it is already used in construction records.'
            );
        }

        $material->delete();

        return redirect()
            ->route(
                'admin.projects.construction.materials.master.index',
                $project
            )
            ->with(
                'success',
                'Material deleted successfully.'
            );
    }

    /**
     * Generate Material Code
     *
     * MAT-000001
     */
    private function generateMaterialCode(): string
    {
        $lastId = ConstructionMaterial::withTrashed()
            ->max('id');

        $nextId = ((int) $lastId) + 1;

        return 'MAT-' . str_pad(
            $nextId,
            6,
            '0',
            STR_PAD_LEFT
        );
    }
}