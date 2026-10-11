<?php

namespace App\Http\Controllers\Admin\Assets\Classification;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Assets\Classification\StoreAssetTypeRequest;
use App\Http\Requests\Admin\Assets\Classification\UpdateAssetTypeRequest;
use App\Models\Assets\AssetType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AssetTypeController extends Controller
{
    public function index(Request $request)
    {
        $items = AssetType::query()
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = trim($request->search);

                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('code', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view(
            'admin.assets.classification.asset-types.index',
            compact('items')
        );
    }

    public function create()
    {
        return view(
            'admin.assets.classification.asset-types.create'
        );
    }

    public function store(StoreAssetTypeRequest $request)
    {
        $item = DB::transaction(function () use ($request) {
            return AssetType::create([
                ...$request->validated(),
                'sort_order' => $request->integer('sort_order', 0),
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
            ]);
        });

        $this->logActivity(
            'created',
            'asset_types',
            "Created Asset Type: {$item->name}",
            $item
        );

        return redirect()
            ->route('admin.assets.asset-types.index')
            ->with('success', 'Asset Type created successfully.');
    }

    public function show($id)
    {
        $item = AssetType::with(['creator', 'updater'])
            ->findOrFail($id);

        $this->logActivity(
            'viewed',
            'asset_types',
            "Viewed Asset Type: {$item->name}",
            $item
        );

        return view(
            'admin.assets.classification.asset-types.show',
            compact('item')
        );
    }

    public function edit($id)
    {
        $item = AssetType::findOrFail($id);

        return view(
            'admin.assets.classification.asset-types.edit',
            compact('item')
        );
    }

    public function update(
        UpdateAssetTypeRequest $request,
        $id
    ) {
        $item = AssetType::findOrFail($id);

        $oldValues = $item->only([
            'name',
            'code',
            'description',
            'status',
            'sort_order',
        ]);

        $item->update([
            ...$request->validated(),
            'sort_order' => $request->integer('sort_order', 0),
            'updated_by' => auth()->id(),
        ]);

        $this->logActivity(
            'updated',
            'asset_types',
            "Updated Asset Type: {$item->name}",
            $item,
            $oldValues,
            $item->fresh()->only([
                'name',
                'code',
                'description',
                'status',
                'sort_order',
            ])
        );

        return redirect()
            ->route('admin.assets.asset-types.index')
            ->with('success', 'Asset Type updated successfully.');
    }

    public function destroy($id)
    {
        $item = AssetType::findOrFail($id);

        $oldValues = $item->only([
            'name',
            'code',
            'description',
            'status',
            'sort_order',
        ]);

        $item->delete();

        $this->logActivity(
            'deleted',
            'asset_types',
            "Deleted Asset Type: {$item->name}",
            $item,
            $oldValues
        );

        return redirect()
            ->route('admin.assets.asset-types.index')
            ->with('success', 'Asset Type deleted successfully.');
    }

    public function restore($id)
    {
        $item = AssetType::withTrashed()->findOrFail($id);

        $item->restore();

        $item->update([
            'updated_by' => auth()->id(),
        ]);

        $this->logActivity(
            'restored',
            'asset_types',
            "Restored Asset Type: {$item->name}",
            $item
        );

        return redirect()
            ->route('admin.assets.asset-types.index')
            ->with('success', 'Asset Type restored successfully.');
    }

    protected function logActivity(
        string $action,
        string $module,
        string $description,
        $subject,
        ?array $oldValues = null,
        ?array $newValues = null
    ): void {
        /*
         * IMPORTANT:
         * Connect this wrapper to your project's actual
         * ActivityLogger::log() signature.
         */
    }
}