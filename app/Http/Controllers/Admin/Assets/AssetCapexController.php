<?php

namespace App\Http\Controllers\Admin\Assets;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Assets\StoreAssetCapexRequest;
use App\Http\Requests\Admin\Assets\UpdateAssetCapexRequest;
use App\Models\Asset;
use App\Models\AssetCapex;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AssetCapexController extends Controller
{
    /**
     * Display CAPEX records.
     */
    public function index(Request $request): View
    {
        $query = AssetCapex::with([
            'asset',
            'vendor',
            'createdBy',
            'updatedBy',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('capex_code', 'like', "%{$search}%")
                    ->orWhere('title', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%");
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Status Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        /*
        |--------------------------------------------------------------------------
        | Priority Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        $capex = $query
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        return view(
            'admin.assets.capex.index',
            compact('capex')
        );
    }

    /**
     * Show create form.
     */
    public function create(): View
    {
        $assets = Asset::orderBy('asset_name')->get([
            'id',
            'asset_code',
            'asset_name',
        ]);

        $users = User::where('is_active', true)
            ->orderBy('name')
            ->get([
                'id',
                'name',
                'email',
            ]);

        return view(
            'admin.assets.capex.create',
            compact('assets', 'users')
        );
    }

    /**
     * Store CAPEX.
     */
    public function store(
        StoreAssetCapexRequest $request
    ): RedirectResponse {

        $data = $request->validated();

        $data['estimated_cost'] =
            $data['estimated_cost'] ?? 0;

        $data['approved_budget'] =
            $data['approved_budget'] ?? 0;

        $data['actual_cost'] =
            $data['actual_cost'] ?? 0;

        $data['created_by'] =
            auth()->id();

        $data['updated_by'] =
            auth()->id();

        $capex = AssetCapex::create($data);

        return redirect()
            ->route('admin.assets.capex.show', $capex->id)
            ->with(
                'success',
                'Asset CAPEX created successfully.'
            );
    }

    /**
     * Display CAPEX.
     */
    public function show(
        AssetCapex $capex
    ): View {
$profile = $capex
    ->asset
    ?->industryOperatingProfile;
    if (
        $profile &&
        !$profile->capex_tracking
    ) {
        // CAPEX tracking is disabled for this profile.
    }
        $capex->load([
            'asset',
            'asset.industryOperatingProfile',
            'vendor',
            'createdBy',
            'updatedBy',
        ]);

        return view(
            'admin.assets.capex.show',
            compact('capex')
        );
    }

    /**
     * Show edit form.
     */
    public function edit(
        AssetCapex $capex
    ): View {

        $assets = Asset::orderBy('asset_name')->get([
            'id',
            'asset_code',
            'asset_name',
        ]);

        $users = User::where('is_active', true)
            ->orderBy('name')
            ->get([
                'id',
                'name',
                'email',
            ]);

        return view(
            'admin.assets.capex.edit',
            compact(
                'capex',
                'assets',
                'users'
            )
        );
    }

    /**
     * Update CAPEX.
     */
    public function update(
        UpdateAssetCapexRequest $request,
        AssetCapex $capex
    ): RedirectResponse {

        $data = $request->validated();

        $data['estimated_cost'] =
            $data['estimated_cost'] ?? 0;

        $data['approved_budget'] =
            $data['approved_budget'] ?? 0;

        $data['actual_cost'] =
            $data['actual_cost'] ?? 0;

        $data['updated_by'] =
            auth()->id();

        $capex->update($data);

        return redirect()
            ->route(
                'admin.assets.capex.show',
                $capex->id
            )
            ->with(
                'success',
                'Asset CAPEX updated successfully.'
            );
    }

    /**
     * Delete CAPEX.
     */
    public function destroy(
        AssetCapex $capex
    ): RedirectResponse {

        $capex->delete();

        return redirect()
            ->route('admin.assets.capex.index')
            ->with(
                'success',
                'Asset CAPEX deleted successfully.'
            );
    }
}