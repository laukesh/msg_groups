<?php

namespace App\Http\Controllers\Admin\Assets\Operations;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Assets\Operations\StoreAssetConditionRequest;
use App\Http\Requests\Admin\Assets\Operations\UpdateAssetConditionRequest;
use App\Models\Asset;
use App\Models\AssetCondition;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AssetConditionController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(Request $request): View
    {
        $query = AssetCondition::with([
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
                    'condition',
                    'like',
                    "%{$search}%"
                )

                ->orWhere(
                    'recommended_action',
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
        | Condition Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('condition')) {

            $query->where(
                'condition',
                $request->condition
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Date Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('inspection_date')) {

            $query->whereDate(
                'inspection_date',
                $request->inspection_date
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $conditions = $query
            ->latest('inspection_date')
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        return view(
            'admin.assets.operations.condition.index',
            compact('conditions')
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
            'admin.assets.operations.conditions.create',
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
        StoreAssetConditionRequest $request
    ): RedirectResponse {

        $data = $request->validated();

        $data['created_by'] = auth()->id();

        AssetCondition::create($data);

        return redirect()
            ->route(
                'admin.assets.operations.conditions.index'
            )
            ->with(
                'success',
                'Asset condition record created successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(
        AssetCondition $condition
    ): View {

        $condition->load([
            'asset',
            'asset.industryOperatingProfile',
            'inspector',
            'creator',
            'updater',
        ]);

        return view(
            'admin.assets.operations.condition.show',
            compact('condition')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit(
        AssetCondition $condition
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
            'admin.assets.operations.condition.edit',
            compact(
                'condition',
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
        UpdateAssetConditionRequest $request,
        AssetCondition $condition
    ): RedirectResponse {

        $data = $request->validated();

        $data['updated_by'] = auth()->id();

        $condition->update($data);

        return redirect()
            ->route(
                'admin.assets.operations.conditions.index'
            )
            ->with(
                'success',
                'Asset condition record updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    public function destroy(
        AssetCondition $condition
    ): RedirectResponse {

        $condition->delete();

        return redirect()
            ->route(
                'admin.assets.operations.conditions.index'
            )
            ->with(
                'success',
                'Asset condition record deleted successfully.'
            );
    }
}