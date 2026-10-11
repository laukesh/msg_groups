<?php

namespace App\Http\Controllers\Admin\Assets\Operations;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Assets\Operations\StoreAssetPerformanceRequest;
use App\Http\Requests\Admin\Assets\Operations\UpdateAssetPerformanceRequest;
use App\Models\Asset;
use App\Models\Operations\AssetPerformance;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AssetPerformanceController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(Request $request): View
    {
        $query = AssetPerformance::with([
            'asset',
            'inspector',
            'creator',
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
                    'performance_status',
                    'like',
                    "%{$search}%"
                )

                ->orWhereHas('asset', function ($assetQuery) use ($search) {

                    $assetQuery
                        ->where(
                            'asset_name',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'asset_code',
                            'like',
                            "%{$search}%"
                        );

                })

                ->orWhereHas('inspector', function ($userQuery) use ($search) {

                    $userQuery
                        ->where(
                            'name',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'email',
                            'like',
                            "%{$search}%"
                        );

                });

            });
        }

        /*
        |--------------------------------------------------------------------------
        | Status Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('performance_status')) {

            $query->where(
                'performance_status',
                $request->performance_status
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Date Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('performance_date')) {

            $query->whereDate(
                'performance_date',
                $request->performance_date
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $performances = $query
            ->latest('performance_date')
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        return view(
            'admin.assets.operations.performance.index',
            compact('performances')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create(): View
    {
        $assets = Asset::query()
            ->select([
                'id',
                'asset_code',
                'asset_name',
            ])
            ->orderBy('asset_name')
            ->get();

        $inspectors = User::query()
            ->select([
                'id',
                'name',
                'email',
            ])
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view(
            'admin.assets.operations.performance.create',
            compact(
                'assets',
                'inspectors'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(
        StoreAssetPerformanceRequest $request
    ): RedirectResponse {

        $data = $request->validated();

        $data['created_by'] = auth()->id();

        AssetPerformance::create($data);

        return redirect()
            ->route(
                'admin.assets.operations.performance.index'
            )
            ->with(
                'success',
                'Asset performance record created successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(
        AssetPerformance $performance
    ): View {

    $profile = $performance
    ->asset
    ?->industryOperatingProfile;
    $performanceTarget = $profile?->performance_target;

$utilizationTarget = $profile?->utilization_target;

$availabilityTarget = $profile?->availability_target;

$efficiencyTarget = $profile?->efficiency_target;
        $performance->load([
            'asset',
            'asset.industryOperatingProfile',
            'inspector',
            'creator',
            'updater',
        ]);

        return view(
            'admin.assets.operations.performance.show',
            compact('performance')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit(
        AssetPerformance $performance
    ): View {

        $assets = Asset::query()
            ->select([
                'id',
                'asset_code',
                'asset_name',
            ])
            ->orderBy('asset_name')
            ->get();

        $inspectors = User::query()
            ->select([
                'id',
                'name',
                'email',
            ])
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view(
            'admin.assets.operations.performance.edit',
            compact(
                'performance',
                'assets',
                'inspectors'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        UpdateAssetPerformanceRequest $request,
        AssetPerformance $performance
    ): RedirectResponse {

        $data = $request->validated();

        $data['updated_by'] = auth()->id();

        $performance->update($data);

        return redirect()
            ->route(
                'admin.assets.operations.performance.index'
            )
            ->with(
                'success',
                'Asset performance record updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    public function destroy(
        AssetPerformance $performance
    ): RedirectResponse {

        $performance->delete();

        return redirect()
            ->route(
                'admin.assets.operations.performance.index'
            )
            ->with(
                'success',
                'Asset performance record deleted successfully.'
            );
    }
}