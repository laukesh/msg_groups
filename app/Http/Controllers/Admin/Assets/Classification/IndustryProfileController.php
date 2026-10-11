<?php

namespace App\Http\Controllers\Admin\Assets\Classification;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Assets\Classification\StoreIndustryProfileRequest;
use App\Http\Requests\Admin\Assets\Classification\UpdateIndustryProfileRequest;
use App\Models\IndustryProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class IndustryProfileController extends Controller
{
    /**
     * Display a listing of industry profiles.
     */
    public function index(Request $request): View
    {
        $query = IndustryProfile::query();

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

        $industryProfiles = $query
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view(
            'admin.assets.classification.industry-profiles.index',
            compact('industryProfiles')
        );
    }

    /**
     * Show the form for creating a new industry profile.
     */
    public function create(): View
    {
        return view(
            'admin.assets.classification.industry-profiles.create'
        );
    }

    /**
     * Store a newly created industry profile.
     */
    public function store(
        StoreIndustryProfileRequest $request
    ): RedirectResponse {
        try {
            DB::beginTransaction();

            $data = $request->validated();

            $data['created_by'] = auth()->id();
            $data['updated_by'] = auth()->id();

            $industryProfile = IndustryProfile::create($data);

            DB::commit();

            $this->logActivity(
                'created',
                'industry_profiles',
                'Created industry profile: ' . $industryProfile->name,
                $industryProfile->id
            );

            return redirect()
                ->route('admin.assets.industry-profiles.index')
                ->with(
                    'success',
                    'Industry profile created successfully.'
                );

        } catch (\Throwable $e) {

            DB::rollBack();

            report($e);

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Unable to create industry profile. Please try again.'
                );
        }
    }

    /**
     * Display the specified industry profile.
     */
    public function show($id): View
    {
        $industryProfile = IndustryProfile::withCount('assets')
            ->with([
                'creator:id,name',
                'updater:id,name',
            ])
            ->findOrFail($id);

        return view(
            'admin.assets.classification.industry-profiles.show',
            compact('industryProfile')
        );
    }

    /**
     * Show the form for editing the specified industry profile.
     */
    public function edit($id): View
    {
        $industryProfile = IndustryProfile::findOrFail($id);

        return view(
            'admin.assets.classification.industry-profiles.edit',
            compact('industryProfile')
        );
    }

    /**
     * Update the specified industry profile.
     */
    public function update(
        UpdateIndustryProfileRequest $request,
        $id
    ): RedirectResponse {
        $industryProfile = IndustryProfile::findOrFail($id);

        try {
            DB::beginTransaction();

            $oldValues = $industryProfile->getOriginal();

            $data = $request->validated();

            $data['updated_by'] = auth()->id();

            $industryProfile->update($data);

            $newValues = $industryProfile->fresh()->toArray();

            DB::commit();

            $this->logActivity(
                'updated',
                'industry_profiles',
                'Updated industry profile: ' . $industryProfile->name,
                $industryProfile->id,
                $oldValues,
                $newValues
            );

            return redirect()
                ->route('admin.assets.industry-profiles.index')
                ->with(
                    'success',
                    'Industry profile updated successfully.'
                );

        } catch (\Throwable $e) {

            DB::rollBack();

            report($e);

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Unable to update industry profile. Please try again.'
                );
        }
    }

    /**
     * Remove the specified industry profile.
     */
    public function destroy($id): RedirectResponse
    {
        $industryProfile = IndustryProfile::findOrFail($id);

        try {
            DB::beginTransaction();

            $name = $industryProfile->name;

            $industryProfile->delete();

            DB::commit();

            $this->logActivity(
                'deleted',
                'industry_profiles',
                'Deleted industry profile: ' . $name,
                $industryProfile->id
            );

            return redirect()
                ->route('admin.assets.industry-profiles.index')
                ->with(
                    'success',
                    'Industry profile deleted successfully.'
                );

        } catch (\Throwable $e) {

            DB::rollBack();

            report($e);

            return back()
                ->with(
                    'error',
                    'Unable to delete industry profile. Please try again.'
                );
        }
    }

    /**
     * Restore a soft-deleted industry profile.
     */
    public function restore($id): RedirectResponse
    {
        $industryProfile = IndustryProfile::withTrashed()
            ->findOrFail($id);

        if (!$industryProfile->trashed()) {
            return redirect()
                ->route('admin.assets.industry-profiles.index')
                ->with(
                    'info',
                    'Industry profile is already active.'
                );
        }

        try {
            DB::beginTransaction();

            $industryProfile->restore();

            $industryProfile->update([
                'updated_by' => auth()->id(),
            ]);

            DB::commit();

            $this->logActivity(
                'restored',
                'industry_profiles',
                'Restored industry profile: ' . $industryProfile->name,
                $industryProfile->id
            );

            return redirect()
                ->route('admin.assets.industry-profiles.index')
                ->with(
                    'success',
                    'Industry profile restored successfully.'
                );

        } catch (\Throwable $e) {

            DB::rollBack();

            report($e);

            return back()
                ->with(
                    'error',
                    'Unable to restore industry profile. Please try again.'
                );
        }
    }

    /**
     * Activity logger wrapper.
     *
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
         * Connect your existing ActivityLogger here.
         *
         * Do not guess the ActivityLogger::log() arguments.
         */
    }
}