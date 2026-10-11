<?php

namespace App\Http\Controllers\Admin\Assets\Operations;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Assets\Operations\StoreAssetLifecycleRequest;
use App\Http\Requests\Admin\Assets\Operations\UpdateAssetLifecycleRequest;
use App\Models\Asset;
use App\Models\AssetLifecycle;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AssetLifecycleController extends Controller
{
    /**
     * Display lifecycle records.
     */
    public function index(Request $request): View
    {
        $query = AssetLifecycle::with([
            'asset',
            'responsibleUser',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {
            $search = $request->search;

            $query->whereHas('asset', function ($q) use ($search) {
                $q->where('asset_code', 'like', "%{$search}%")
                    ->orWhere('asset_name', 'like', "%{$search}%");
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Stage Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('lifecycle_stage')) {
            $query->where(
                'lifecycle_stage',
                $request->lifecycle_stage
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Status Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('lifecycle_status')) {
            $query->where(
                'lifecycle_status',
                $request->lifecycle_status
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Priority Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('priority')) {
            $query->where(
                'priority',
                $request->priority
            );
        }

        $lifecycles = $query
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        return view(
            'admin.assets.operations.lifecycle.index',
            compact('lifecycles')
        );
    }

    /**
     * Show create form.
     */
    public function create(): View
    {
        $assets = Asset::query()
            ->orderBy('asset_name')
            ->get();

        $users = User::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view(
            'admin.assets.operations.lifecycle.create',
            compact('assets', 'users')
        );
    }

    /**
     * Store lifecycle record.
     */
    public function store(
        StoreAssetLifecycleRequest $request
    ): RedirectResponse {
        $data = $request->validated();

        $data['created_by'] = auth()->id();
        $data['updated_by'] = auth()->id();

        AssetLifecycle::create($data);

        return redirect()
            ->route('admin.assets.operations.lifecycle.index')
            ->with(
                'success',
                'Asset lifecycle record created successfully.'
            );
    }

    /**
     * Display lifecycle record.
     */
    public function show(
        AssetLifecycle $lifecycle
    ): View {

        $profile = $lifecycle
    ->asset
    ?->industryOperatingProfile;
    $asset = Asset::with(
    'industryOperatingProfile'
)->findOrFail(
    $request->asset_id
);
$data = $request->validated();

if (
    empty($data['expected_life_years']) &&
    $profile
) {
    $data['expected_life_years'] =
        $profile->default_asset_life_years;
}
$profile = $asset->industryOperatingProfile;
    $defaultLife =
    $profile?->default_asset_life_years;

$renewalPeriod =
    $profile?->renewal_period_years;

$replacementThreshold =
    $profile?->replacement_threshold;

$reviewFrequency =
    $profile?->lifecycle_review_frequency_months;
        $lifecycle->load([
            'asset', 'asset.industryOperatingProfile',
            'responsibleUser',
            'creator',
            'updater',
        ]);

        return view(
            'admin.assets.operations.lifecycle.show',
            compact('lifecycle')
        );
    }

    /**
     * Show edit form.
     */
    public function edit(
        AssetLifecycle $lifecycle
    ): View {
        $assets = Asset::query()
            ->orderBy('asset_name')
            ->get();

        $users = User::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view(
            'admin.assets.operations.lifecycle.edit',
            compact(
                'lifecycle',
                'assets',
                'users'
            )
        );
    }

    /**
     * Update lifecycle record.
     */
    public function update(
        UpdateAssetLifecycleRequest $request,
        AssetLifecycle $lifecycle
    ): RedirectResponse {
        $data = $request->validated();

        $data['updated_by'] = auth()->id();

        $lifecycle->update($data);

        return redirect()
            ->route(
                'admin.assets.operations.lifecycle.show',
                $lifecycle
            )
            ->with(
                'success',
                'Asset lifecycle record updated successfully.'
            );
    }

    /**
     * Delete lifecycle record.
     */
    public function destroy(
        AssetLifecycle $lifecycle
    ): RedirectResponse {
        $lifecycle->delete();

        return redirect()
            ->route('admin.assets.operations.lifecycle.index')
            ->with(
                'success',
                'Asset lifecycle record deleted successfully.'
            );
    }
}