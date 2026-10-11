<?php

namespace App\Http\Controllers\Admin\Assets\Classification;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Assets\Classification\StoreOwnershipRequest;
use App\Http\Requests\Admin\Assets\Classification\UpdateOwnershipRequest;
use App\Models\Ownership;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Throwable;

class OwnershipController extends Controller
{
    /**
     * Display a listing of ownership records.
     */
    public function index(Request $request): View
    {
        $query = Ownership::query()
            ->withCount('assets');

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

        $ownerships = $query
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view(
            'admin.assets.classification.ownerships.index',
            compact('ownerships')
        );
    }

    /**
     * Show create form.
     */
    public function create(): View
    {
        return view(
            'admin.assets.classification.ownerships.create'
        );
    }

    /**
     * Store ownership record.
     */
    public function store(
        StoreOwnershipRequest $request
    ): RedirectResponse {
        try {
            DB::beginTransaction();

            $ownership = Ownership::create([
                'name'        => $request->name,
                'code'        => $request->code,
                'description' => $request->description,
                'status'      => $request->status ?? 'Active',
                'sort_order'  => $request->sort_order ?? 0,
                'created_by'  => auth()->id(),
                'updated_by'  => auth()->id(),
            ]);

            DB::commit();

            $this->logActivity(
                'created',
                'ownerships',
                "Created ownership: {$ownership->name}",
                $ownership
            );

            return redirect()
                ->route('admin.assets.ownerships.index')
                ->with(
                    'success',
                    'Ownership created successfully.'
                );

        } catch (Throwable $e) {
            DB::rollBack();

            report($e);

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Unable to create ownership. Please try again.'
                );
        }
    }

    /**
     * Display ownership details.
     */
    public function show($id): View
    {
        $ownership = Ownership::query()
            ->with([
                'creator:id,name',
                'updater:id,name',
            ])
            ->withCount('assets')
            ->findOrFail($id);

        return view(
            'admin.assets.classification.ownerships.show',
            compact('ownership')
        );
    }

    /**
     * Show edit form.
     */
    public function edit($id): View
    {
        $ownership = Ownership::findOrFail($id);

        return view(
            'admin.assets.classification.ownerships.edit',
            compact('ownership')
        );
    }

    /**
     * Update ownership record.
     */
    public function update(
        UpdateOwnershipRequest $request,
        $id
    ): RedirectResponse {
        try {
            DB::beginTransaction();

            $ownership = Ownership::findOrFail($id);

            $oldValues = $ownership->getOriginal();

            $ownership->update([
                'name'        => $request->name,
                'code'        => $request->code,
                'description' => $request->description,
                'status'      => $request->status,
                'sort_order'  => $request->sort_order ?? 0,
                'updated_by'  => auth()->id(),
            ]);

            $newValues = $ownership->fresh()->toArray();

            DB::commit();

            $this->logActivity(
                'updated',
                'ownerships',
                "Updated ownership: {$ownership->name}",
                $ownership,
                $oldValues,
                $newValues
            );

            return redirect()
                ->route('admin.assets.ownerships.index')
                ->with(
                    'success',
                    'Ownership updated successfully.'
                );

        } catch (Throwable $e) {
            DB::rollBack();

            report($e);

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Unable to update ownership. Please try again.'
                );
        }
    }

    /**
     * Soft delete ownership.
     */
    public function destroy($id): RedirectResponse
    {
        try {
            DB::beginTransaction();

            $ownership = Ownership::findOrFail($id);

            // Prevent deletion when assets are using this ownership.
            if ($ownership->assets()->exists()) {
                DB::rollBack();

                return back()->with(
                    'error',
                    'This ownership cannot be deleted because it is assigned to one or more assets.'
                );
            }

            $oldValues = $ownership->toArray();

            $ownership->delete();

            DB::commit();

            $this->logActivity(
                'deleted',
                'ownerships',
                "Deleted ownership: {$ownership->name}",
                $ownership,
                $oldValues,
                null
            );

            return redirect()
                ->route('admin.assets.ownerships.index')
                ->with(
                    'success',
                    'Ownership deleted successfully.'
                );

        } catch (Throwable $e) {
            DB::rollBack();

            report($e);

            return back()
                ->with(
                    'error',
                    'Unable to delete ownership. Please try again.'
                );
        }
    }

    /**
     * Restore soft deleted ownership.
     */
    public function restore($id): RedirectResponse
    {
        try {
            DB::beginTransaction();

            $ownership = Ownership::withTrashed()
                ->findOrFail($id);

            $ownership->restore();

            $ownership->update([
                'updated_by' => auth()->id(),
            ]);

            DB::commit();

            $this->logActivity(
                'restored',
                'ownerships',
                "Restored ownership: {$ownership->name}",
                $ownership
            );

            return redirect()
                ->route('admin.assets.ownerships.index')
                ->with(
                    'success',
                    'Ownership restored successfully.'
                );

        } catch (Throwable $e) {
            DB::rollBack();

            report($e);

            return back()->with(
                'error',
                'Unable to restore ownership. Please try again.'
            );
        }
    }

    /**
     * Activity logger placeholder.
     *
     * Keep this method until the exact ActivityLogger::log()
     * signature in your project is confirmed.
     */
    protected function logActivity(
        string $action,
        string $module,
        string $description,
        ?Ownership $ownership = null,
        ?array $oldValues = null,
        ?array $newValues = null
    ): void {
        // ActivityLogger::log(...) can be added here
        // after confirming the exact project signature.
    }
}