<?php

namespace App\Http\Controllers\Admin\Assets\Operations;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Assets\Operations\StoreOccupancyRequest;
use App\Http\Requests\Admin\Assets\Operations\UpdateOccupancyRequest;
use App\Models\Asset;
use App\Models\Occupancy;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OccupancyController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(Request $request): View
    {
        $query = Occupancy::with([
            'asset',
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

                $q->where('tenant_name', 'like', "%{$search}%")
                    ->orWhere('occupancy_status', 'like', "%{$search}%")
                    ->orWhereHas('asset', function ($assetQuery) use ($search) {

                        $assetQuery
                            ->where('asset_name', 'like', "%{$search}%")
                            ->orWhere('asset_code', 'like', "%{$search}%");

                    });

            });
        }

        /*
        |--------------------------------------------------------------------------
        | Status Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('occupancy_status')) {

            $query->where(
                'occupancy_status',
                $request->occupancy_status
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $occupancies = $query
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        return view(
            'admin.assets.operations.occupancy.index',
            compact('occupancies')
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

        return view(
            'admin.assets.operations.occupancy.create',
            compact('assets')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(
        StoreOccupancyRequest $request
    ): RedirectResponse {

        $data = $request->validated();

        $data['created_by'] = auth()->id();

        Occupancy::create($data);

        return redirect()
            ->route('admin.assets.operations.occupancy.index')
            ->with(
                'success',
                'Occupancy record created successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(Occupancy $occupancy): View
    {
        $occupancyTarget = $profile?->occupancy_target;
        $profile = $occupancy
            ->asset
            ?->industryOperatingProfile;
        $occupancy->load([
            'asset',
            'asset.industryOperatingProfile',
            'creator',
            'updater',
        ]);

        return view(
            'admin.assets.operations.occupancy.show',
            compact('occupancy')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit(Occupancy $occupancy): View
    {
        $assets = Asset::query()
            ->select([
                'id',
                'asset_code',
                'asset_name',
            ])
            ->orderBy('asset_name')
            ->get();

        return view(
            'admin.assets.operations.occupancy.edit',
            compact(
                'occupancy',
                'assets'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        UpdateOccupancyRequest $request,
        Occupancy $occupancy
    ): RedirectResponse {

        $data = $request->validated();

        $data['updated_by'] = auth()->id();

        $occupancy->update($data);

        return redirect()
            ->route(
                'admin.assets.operations.occupancy.index'
            )
            ->with(
                'success',
                'Occupancy record updated successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Occupancy $occupancy
    ): RedirectResponse {

        $occupancy->delete();

        return redirect()
            ->route(
                'admin.assets.operations.occupancy.index'
            )
            ->with(
                'success',
                'Occupancy record deleted successfully.'
            );
    }
}