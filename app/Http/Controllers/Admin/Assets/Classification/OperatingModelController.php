<?php

namespace App\Http\Controllers\Admin\Assets\Classification;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Assets\Classification\StoreOperatingModelRequest;
use App\Http\Requests\Admin\Assets\Classification\UpdateOperatingModelRequest;
use App\Models\OperatingModel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class OperatingModelController extends Controller
{
    /**
     * Display a listing of operating models.
     */
    public function index(Request $request): View
    {
        $query = OperatingModel::query();

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */
        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");

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

        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */
        $operatingModels = $query
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view(
            'admin.assets.classification.operating-models.index',
            compact('operatingModels')
        );
    }

    /**
     * Show create form.
     */
    public function create(): View
    {
        return view(
            'admin.assets.classification.operating-models.create'
        );
    }

    /**
     * Store operating model.
     */
    public function store(
        StoreOperatingModelRequest $request
    ): RedirectResponse {

        try {

            DB::beginTransaction();

            $data = $request->validated();

            $data['created_by'] = auth()->id();
            $data['updated_by'] = auth()->id();

            $operatingModel = OperatingModel::create($data);

            DB::commit();

            $this->logActivity(
                'created',
                'operating_models',
                'Created operating model: ' . $operatingModel->name,
                $operatingModel->id
            );

            return redirect()
                ->route('admin.assets.operating-models.index')
                ->with(
                    'success',
                    'Operating model created successfully.'
                );

        } catch (\Throwable $e) {

            DB::rollBack();

            report($e);

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Unable to create operating model. Please try again.'
                );
        }
    }

    /**
     * Display operating model.
     */
    public function show($id): View
    {
        $operatingModel = OperatingModel::withCount('assets')
            ->with([
                'creator:id,name',
                'updater:id,name',
            ])
            ->findOrFail($id);

        return view(
            'admin.assets.classification.operating-models.show',
            compact('operatingModel')
        );
    }

    /**
     * Show edit form.
     */
    public function edit($id): View
    {
        $operatingModel = OperatingModel::findOrFail($id);

        return view(
            'admin.assets.classification.operating-models.edit',
            compact('operatingModel')
        );
    }

    /**
     * Update operating model.
     */
    public function update(
        UpdateOperatingModelRequest $request,
        $id
    ): RedirectResponse {

        $operatingModel = OperatingModel::findOrFail($id);

        try {

            DB::beginTransaction();

            $oldValues = $operatingModel->getOriginal();

            $data = $request->validated();

            $data['updated_by'] = auth()->id();

            $operatingModel->update($data);

            $newValues = $operatingModel
                ->fresh()
                ->toArray();

            DB::commit();

            $this->logActivity(
                'updated',
                'operating_models',
                'Updated operating model: ' . $operatingModel->name,
                $operatingModel->id,
                $oldValues,
                $newValues
            );

            return redirect()
                ->route('admin.assets.operating-models.index')
                ->with(
                    'success',
                    'Operating model updated successfully.'
                );

        } catch (\Throwable $e) {

            DB::rollBack();

            report($e);

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Unable to update operating model. Please try again.'
                );
        }
    }

    /**
     * Delete operating model.
     */
    public function destroy($id): RedirectResponse
    {
        $operatingModel = OperatingModel::findOrFail($id);

        try {

            DB::beginTransaction();

            $name = $operatingModel->name;

            $operatingModel->delete();

            DB::commit();

            $this->logActivity(
                'deleted',
                'operating_models',
                'Deleted operating model: ' . $name,
                $operatingModel->id
            );

            return redirect()
                ->route('admin.assets.operating-models.index')
                ->with(
                    'success',
                    'Operating model deleted successfully.'
                );

        } catch (\Throwable $e) {

            DB::rollBack();

            report($e);

            return back()
                ->with(
                    'error',
                    'Unable to delete operating model. Please try again.'
                );
        }
    }

    /**
     * Restore deleted operating model.
     */
    public function restore($id): RedirectResponse
    {
        $operatingModel = OperatingModel::withTrashed()
            ->findOrFail($id);

        if (!$operatingModel->trashed()) {

            return redirect()
                ->route('admin.assets.operating-models.index')
                ->with(
                    'info',
                    'Operating model is already active.'
                );
        }

        try {

            DB::beginTransaction();

            $operatingModel->restore();

            $operatingModel->update([
                'updated_by' => auth()->id(),
            ]);

            DB::commit();

            $this->logActivity(
                'restored',
                'operating_models',
                'Restored operating model: ' . $operatingModel->name,
                $operatingModel->id
            );

            return redirect()
                ->route('admin.assets.operating-models.index')
                ->with(
                    'success',
                    'Operating model restored successfully.'
                );

        } catch (\Throwable $e) {

            DB::rollBack();

            report($e);

            return back()
                ->with(
                    'error',
                    'Unable to restore operating model. Please try again.'
                );
        }
    }

    /**
     * Activity logger wrapper.
     */
    protected function logActivity(
        string $action,
        string $module,
        string $description,
        ?int $subjectId = null,
        ?array $oldValues = null,
        ?array $newValues = null
    ): void {

        /*
         |--------------------------------------------------------------------------
         | Connect your existing ActivityLogger here.
         |--------------------------------------------------------------------------
         |
         | Do not guess ActivityLogger::log() arguments until the
         | existing ActivityLogger.php signature is confirmed.
         |
         */
    }
}