<?php

namespace App\Http\Controllers\Admin\Assets\Classification;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Assets\Classification\StoreAssetPurposeRequest;
use App\Http\Requests\Admin\Assets\Classification\UpdateAssetPurposeRequest;
use App\Models\AssetPurpose;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AssetPurposeController extends Controller
{
    /**
     * Display a listing of asset purposes.
     */
    public function index(Request $request): View
    {
        $query = AssetPurpose::query();

        // Search
        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $assetPurposes = $query
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view(
            'admin.assets.classification.asset-purposes.index',
            compact('assetPurposes')
        );
    }

    /**
     * Show the form for creating a new asset purpose.
     */
    public function create(): View
    {
        return view(
            'admin.assets.classification.asset-purposes.create'
        );
    }

    /**
     * Store a newly created asset purpose.
     */
    public function store(
        StoreAssetPurposeRequest $request
    ): RedirectResponse {
        try {
            DB::beginTransaction();

            $data = $request->validated();

            $data['created_by'] = auth()->id();
            $data['updated_by'] = auth()->id();

            $assetPurpose = AssetPurpose::create($data);

            DB::commit();

            $this->logActivity(
                'created',
                'asset_purposes',
                'Created asset purpose: ' . $assetPurpose->name,
                $assetPurpose->id
            );

            return redirect()
                ->route('admin.assets.purposes.index')
                ->with(
                    'success',
                    'Asset purpose created successfully.'
                );
        } catch (\Throwable $e) {
            DB::rollBack();

            report($e);

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Unable to create asset purpose. Please try again.'
                );
        }
    }

    /**
     * Display the specified asset purpose.
     */
    public function show($id): View
    {
        $assetPurpose = AssetPurpose::withCount('assets')
            ->with([
                'creator:id,name',
                'updater:id,name',
            ])
            ->findOrFail($id);

        return view(
            'admin.assets.classification.asset-purposes.show',
            compact('assetPurpose')
        );
    }

    /**
     * Show the form for editing the specified asset purpose.
     */
    public function edit($id): View
    {
        $assetPurpose = AssetPurpose::findOrFail($id);

        return view(
            'admin.assets.classification.asset-purposes.edit',
            compact('assetPurpose')
        );
    }

    /**
     * Update the specified asset purpose.
     */
    public function update(
        UpdateAssetPurposeRequest $request,
        $id
    ): RedirectResponse {
        $assetPurpose = AssetPurpose::findOrFail($id);

        try {
            DB::beginTransaction();

            $oldValues = $assetPurpose->getOriginal();

            $data = $request->validated();

            $data['updated_by'] = auth()->id();

            $assetPurpose->update($data);

            $newValues = $assetPurpose->fresh()->toArray();

            DB::commit();

            $this->logActivity(
                'updated',
                'asset_purposes',
                'Updated asset purpose: ' . $assetPurpose->name,
                $assetPurpose->id,
                $oldValues,
                $newValues
            );

            return redirect()
                ->route('admin.assets.purposes.index')
                ->with(
                    'success',
                    'Asset purpose updated successfully.'
                );
        } catch (\Throwable $e) {
            DB::rollBack();

            report($e);

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Unable to update asset purpose. Please try again.'
                );
        }
    }

    /**
     * Remove the specified asset purpose.
     */
    public function destroy($id): RedirectResponse
    {
        $assetPurpose = AssetPurpose::findOrFail($id);

        try {
            DB::beginTransaction();

            $name = $assetPurpose->name;

            $assetPurpose->delete();

            DB::commit();

            $this->logActivity(
                'deleted',
                'asset_purposes',
                'Deleted asset purpose: ' . $name,
                $assetPurpose->id
            );

            return redirect()
                ->route('admin.assets.purposes.index')
                ->with(
                    'success',
                    'Asset purpose deleted successfully.'
                );
        } catch (\Throwable $e) {
            DB::rollBack();

            report($e);

            return back()
                ->with(
                    'error',
                    'Unable to delete asset purpose. Please try again.'
                );
        }
    }

    /**
     * Restore a soft-deleted asset purpose.
     */
    public function restore($id): RedirectResponse
    {
        $assetPurpose = AssetPurpose::withTrashed()
            ->findOrFail($id);

        if (!$assetPurpose->trashed()) {
            return redirect()
                ->route('admin.assets.purposes.index')
                ->with(
                    'info',
                    'Asset purpose is already active.'
                );
        }

        try {
            DB::beginTransaction();

            $assetPurpose->restore();

            $assetPurpose->update([
                'updated_by' => auth()->id(),
            ]);

            DB::commit();

            $this->logActivity(
                'restored',
                'asset_purposes',
                'Restored asset purpose: ' . $assetPurpose->name,
                $assetPurpose->id
            );

            return redirect()
                ->route('admin.assets.purposes.index')
                ->with(
                    'success',
                    'Asset purpose restored successfully.'
                );
        } catch (\Throwable $e) {
            DB::rollBack();

            report($e);

            return back()
                ->with(
                    'error',
                    'Unable to restore asset purpose. Please try again.'
                );
        }
    }

    /**
     * Log activity.
     *
     * IMPORTANT:
     * Connect this method to your existing ActivityLogger
     * using its actual method signature.
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
         * Example only:
         *
         * ActivityLogger::log(
         *     $action,
         *     $module,
         *     $description,
         *     auth()->id(),
         *     $oldValues,
         *     $newValues
         * );
         *
         * Do NOT copy this blindly until it matches your
         * existing ActivityLogger::log() signature.
         */
    }
}