<?php

namespace App\Http\Controllers\Admin\Assets\Classification;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Assets\Classification\StoreRevenueStatusRequest;
use App\Http\Requests\Admin\Assets\Classification\UpdateRevenueStatusRequest;
use App\Models\RevenueStatus;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Throwable;

class RevenueStatusController extends Controller
{
    /**
     * Display a listing of revenue statuses.
     */
    public function index(Request $request): View
    {
        $query = RevenueStatus::query()
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

        $revenueStatuses = $query
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view(
            'admin.assets.classification.revenue-statuses.index',
            compact('revenueStatuses')
        );
    }

    /**
     * Show create form.
     */
    public function create(): View
    {
        return view(
            'admin.assets.classification.revenue-statuses.create'
        );
    }

    /**
     * Store a new revenue status.
     */
    public function store(
        StoreRevenueStatusRequest $request
    ): RedirectResponse {
        try {
            DB::beginTransaction();

            $revenueStatus = RevenueStatus::create([
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
                'revenue_statuses',
                "Created revenue status: {$revenueStatus->name}",
                $revenueStatus
            );

            return redirect()
                ->route('admin.assets.revenue-statuses.index')
                ->with(
                    'success',
                    'Revenue status created successfully.'
                );

        } catch (Throwable $e) {
            DB::rollBack();

            report($e);

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Unable to create revenue status. Please try again.'
                );
        }
    }

    /**
     * Display revenue status details.
     */
    public function show($id): View
    {
        $revenueStatus = RevenueStatus::query()
            ->with([
                'creator:id,name',
                'updater:id,name',
            ])
            ->withCount('assets')
            ->findOrFail($id);

        return view(
            'admin.assets.classification.revenue-statuses.show',
            compact('revenueStatus')
        );
    }

    /**
     * Show edit form.
     */
    public function edit($id): View
    {
        $revenueStatus = RevenueStatus::findOrFail($id);

        return view(
            'admin.assets.classification.revenue-statuses.edit',
            compact('revenueStatus')
        );
    }

    /**
     * Update revenue status.
     */
    public function update(
        UpdateRevenueStatusRequest $request,
        $id
    ): RedirectResponse {
        try {
            DB::beginTransaction();

            $revenueStatus = RevenueStatus::findOrFail($id);

            $oldValues = $revenueStatus->getOriginal();

            $revenueStatus->update([
                'name'        => $request->name,
                'code'        => $request->code,
                'description' => $request->description,
                'status'      => $request->status,
                'sort_order'  => $request->sort_order ?? 0,
                'updated_by'  => auth()->id(),
            ]);

            $newValues = $revenueStatus->fresh()->toArray();

            DB::commit();

            $this->logActivity(
                'updated',
                'revenue_statuses',
                "Updated revenue status: {$revenueStatus->name}",
                $revenueStatus,
                $oldValues,
                $newValues
            );

            return redirect()
                ->route('admin.assets.revenue-statuses.index')
                ->with(
                    'success',
                    'Revenue status updated successfully.'
                );

        } catch (Throwable $e) {
            DB::rollBack();

            report($e);

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Unable to update revenue status. Please try again.'
                );
        }
    }

    /**
     * Soft delete revenue status.
     */
    public function destroy($id): RedirectResponse
    {
        try {
            DB::beginTransaction();

            $revenueStatus = RevenueStatus::findOrFail($id);

            // Prevent deletion if assets are using this revenue status.
            if ($revenueStatus->assets()->exists()) {
                DB::rollBack();

                return back()->with(
                    'error',
                    'This revenue status cannot be deleted because it is assigned to one or more assets.'
                );
            }

            $oldValues = $revenueStatus->toArray();

            $revenueStatus->delete();

            DB::commit();

            $this->logActivity(
                'deleted',
                'revenue_statuses',
                "Deleted revenue status: {$revenueStatus->name}",
                $revenueStatus,
                $oldValues,
                null
            );

            return redirect()
                ->route('admin.assets.revenue-statuses.index')
                ->with(
                    'success',
                    'Revenue status deleted successfully.'
                );

        } catch (Throwable $e) {
            DB::rollBack();

            report($e);

            return back()->with(
                'error',
                'Unable to delete revenue status. Please try again.'
            );
        }
    }

    /**
     * Restore soft deleted revenue status.
     */
    public function restore($id): RedirectResponse
    {
        try {
            DB::beginTransaction();

            $revenueStatus = RevenueStatus::withTrashed()
                ->findOrFail($id);

            $revenueStatus->restore();

            $revenueStatus->update([
                'updated_by' => auth()->id(),
            ]);

            DB::commit();

            $this->logActivity(
                'restored',
                'revenue_statuses',
                "Restored revenue status: {$revenueStatus->name}",
                $revenueStatus
            );

            return redirect()
                ->route('admin.assets.revenue-statuses.index')
                ->with(
                    'success',
                    'Revenue status restored successfully.'
                );

        } catch (Throwable $e) {
            DB::rollBack();

            report($e);

            return back()->with(
                'error',
                'Unable to restore revenue status. Please try again.'
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
        ?RevenueStatus $revenueStatus = null,
        ?array $oldValues = null,
        ?array $newValues = null
    ): void {
        // ActivityLogger::log(...) can be added here
        // after confirming the exact project signature.
    }
}