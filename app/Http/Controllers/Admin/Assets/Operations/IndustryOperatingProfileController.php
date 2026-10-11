<?php

namespace App\Http\Controllers\Admin\Assets\Operations;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Assets\Operations\StoreIndustryOperatingProfileRequest;
use App\Http\Requests\Admin\Assets\Operations\UpdateIndustryOperatingProfileRequest;
use App\Models\Asset;
use App\Models\IndustryOperatingProfile as IndustryProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class IndustryOperatingProfileController extends Controller
{
    /**
     * Display all industry operating profiles.
     */
    public function index(Request $request): View
    {
        $query = IndustryProfile::query();

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = trim($request->input('search'));

            $query->where(function ($q) use ($search) {

                $q->where(
                    'profile_code',
                    'like',
                    '%' . $search . '%'
                )
                ->orWhere(
                    'profile_name',
                    'like',
                    '%' . $search . '%'
                )
                ->orWhere(
                    'industry_type',
                    'like',
                    '%' . $search . '%'
                );

            });
        }

        /*
        |--------------------------------------------------------------------------
        | Industry Type
        |--------------------------------------------------------------------------
        */

        if ($request->filled('industry_type')) {

            $query->where(
                'industry_type',
                $request->input('industry_type')
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Status
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {

            $query->where(
                'is_active',
                $request->input('status') === 'active'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Profiles
        |--------------------------------------------------------------------------
        */

        $profiles = $query
            ->orderBy('profile_name', 'asc')
            ->paginate(15)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Asset Counts
        |--------------------------------------------------------------------------
        */

        $profileIds = $profiles
            ->getCollection()
            ->pluck('id')
            ->filter()
            ->values();

        $assetCounts = collect();

        if ($profileIds->isNotEmpty()) {

            $assetCounts = Asset::query()
                ->whereIn(
                    'industry_profile_id',
                    $profileIds
                )
                ->selectRaw(
                    'industry_profile_id, COUNT(*) as total'
                )
                ->groupBy('industry_profile_id')
                ->pluck(
                    'total',
                    'industry_profile_id'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Attach Asset Count
        |--------------------------------------------------------------------------
        */

        $profiles->getCollection()->transform(
            function ($profile) use ($assetCounts) {

                $profile->asset_count = (int) (
                    $assetCounts->get($profile->id) ?? 0
                );

                return $profile;
            }
        );

        return view(
            'admin.assets.operations.industry-profiles.index',
            compact('profiles')
        );
    }

    /**
     * Show create form.
     */
    public function create(): View
    {
        return view(
            'admin.assets.operations.industry-profiles.create'
        );
    }

    /**
     * Store profile.
     */
    public function store(
        StoreIndustryOperatingProfileRequest $request
    ): RedirectResponse {

        $data = $request->validated();

        /*
        |--------------------------------------------------------------------------
        | Boolean Fields
        |--------------------------------------------------------------------------
        */

        $data['is_active'] = $request->boolean('is_active');

        $data['revenue_tracking'] =
            $request->boolean('revenue_tracking');

        $data['operating_expense_tracking'] =
            $request->boolean('operating_expense_tracking');

        $data['capex_tracking'] =
            $request->boolean('capex_tracking');

        /*
        |--------------------------------------------------------------------------
        | Audit Fields
        |--------------------------------------------------------------------------
        */

        $userId = auth()->id();

        $data['created_by'] = $userId;
        $data['updated_by'] = $userId;

        /*
        |--------------------------------------------------------------------------
        | Create
        |--------------------------------------------------------------------------
        */

        $profile = IndustryProfile::create($data);

        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'admin.assets.operations.industry-profiles.show',
                [
                    'industry_profile' => $profile->getKey(),
                ]
            )
            ->with(
                'success',
                'Industry operating profile created successfully.'
            );
    }

    /**
     * Display profile.
     */
    public function show(
        IndustryProfile $industry_profile
    ): View {

        /*
        |--------------------------------------------------------------------------
        | Verify Model Binding
        |--------------------------------------------------------------------------
        */

        abort_if(
            !$industry_profile->exists,
            404,
            'Industry operating profile not found.'
        );

        /*
        |--------------------------------------------------------------------------
        | Creator / Updater
        |--------------------------------------------------------------------------
        */

        $industry_profile->load([
            'creator',
            'updater',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Asset Count
        |
        | Direct query avoids the previous assets() relationship issue.
        |--------------------------------------------------------------------------
        */

        $assetCount = Asset::query()
            ->where(
                'industry_profile_id',
                $industry_profile->getKey()
            )
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Profile
        |--------------------------------------------------------------------------
        */

        $profile = $industry_profile;

        return view(
            'admin.assets.operations.industry-profiles.show',
            compact(
                'industry_profile',
                //'industryOperatingProfile',
                'profile',
                'assetCount'
            )
        );
    }

    /**
     * Show edit form.
     */
    public function edit(
        IndustryProfile $industry_profile
    ): View {

        /*
        |--------------------------------------------------------------------------
        | Verify Model Binding
        |--------------------------------------------------------------------------
        */

        abort_if(
            !$industry_profile->exists,
            404,
            'Industry operating profile not found.'
        );

        /*
        |--------------------------------------------------------------------------
        | Profile Alias
        |--------------------------------------------------------------------------
        */

        $profile = $industry_profile;

        return view(
            'admin.assets.operations.industry-profiles.edit',
            compact('profile')
        );
    }

    /**
     * Update profile.
     */
    public function update(
        UpdateIndustryOperatingProfileRequest $request,
        IndustryProfile $industry_profile
    ): RedirectResponse {

        /*
        |--------------------------------------------------------------------------
        | Verify Model Binding
        |--------------------------------------------------------------------------
        */

        abort_if(
            !$industry_profile->exists,
            404,
            'Industry operating profile not found.'
        );

        /*
        |--------------------------------------------------------------------------
        | Validated Data
        |--------------------------------------------------------------------------
        */

        $data = $request->validated();

        /*
        |--------------------------------------------------------------------------
        | Boolean Fields
        |--------------------------------------------------------------------------
        */

        $data['is_active'] =
            $request->boolean('is_active');

        $data['revenue_tracking'] =
            $request->boolean('revenue_tracking');

        $data['operating_expense_tracking'] =
            $request->boolean('operating_expense_tracking');

        $data['capex_tracking'] =
            $request->boolean('capex_tracking');

        /*
        |--------------------------------------------------------------------------
        | Audit
        |--------------------------------------------------------------------------
        */

        $data['updated_by'] = auth()->id();

        /*
        |--------------------------------------------------------------------------
        | Update
        |--------------------------------------------------------------------------
        */

        $industry_profile->update($data);

        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'admin.assets.operations.industry-profiles.show',
                [
                    'industry_profile' =>
                        $industry_profile->getKey(),
                ]
            )
            ->with(
                'success',
                'Industry operating profile updated successfully.'
            );
    }

    /**
     * Delete profile.
     */
    public function destroy(
        IndustryProfile $industry_profile
    ): RedirectResponse {

        /*
        |--------------------------------------------------------------------------
        | Verify Model Binding
        |--------------------------------------------------------------------------
        */

        abort_if(
            !$industry_profile->exists,
            404,
            'Industry operating profile not found.'
        );

        /*
        |--------------------------------------------------------------------------
        | Check Assigned Assets
        |--------------------------------------------------------------------------
        */

        $hasAssets = Asset::query()
            ->where(
                'industry_profile_id',
                $industry_profile->getKey()
            )
            ->exists();

        if ($hasAssets) {

            return redirect()
                ->back()
                ->with(
                    'error',
                    'This profile cannot be deleted because it is assigned to one or more assets.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Delete
        |--------------------------------------------------------------------------
        */

        $industry_profile->delete();

        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'admin.assets.operations.industry-profiles.index'
            )
            ->with(
                'success',
                'Industry operating profile deleted successfully.'
            );
    }
}