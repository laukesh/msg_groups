<?php

namespace App\Http\Controllers\Admin\Assets\Operations;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Models\Occupancy;
use Illuminate\Http\Request;

class OccupancyController extends Controller
{
    public function index()
    {
        $occupancies = Occupancy::with('asset')
            ->latest()
            ->paginate(15);

        return view(
            'admin.assets.operations.occupancy.index',
            compact('occupancies')
        );
    }

    public function create()
    {
        $assets = Asset::orderBy('asset_name')->get();

        return view(
            'admin.assets.operations.occupancy.create',
            compact('assets')
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'asset_id' => ['required', 'exists:assets,id'],
            'occupancy_status' => ['required'],
            'occupancy_percentage' => ['required', 'numeric', 'between:0,100'],
            'tenant_name' => ['nullable', 'string', 'max:255'],
            'lease_start_date' => ['nullable', 'date'],
            'lease_end_date' => ['nullable', 'date'],
            'monthly_rent' => ['nullable', 'numeric', 'min:0'],
            'remarks' => ['nullable', 'string'],
        ]);

        $validated['created_by'] = auth()->id();

        Occupancy::create($validated);

        return redirect()
            ->route('admin.assets.operations.occupancy.index')
            ->with('success', 'Occupancy created successfully.');
    }

    public function show(Occupancy $occupancy)
    {
        $occupancy->load('asset');

        return view(
            'admin.assets.operations.occupancy.show',
            compact('occupancy')
        );
    }

    public function edit(Occupancy $occupancy)
    {
        $assets = Asset::orderBy('asset_name')->get();

        return view(
            'admin.assets.operations.occupancy.edit',
            compact('occupancy', 'assets')
        );
    }

    public function update(Request $request, Occupancy $occupancy)
    {
        $validated = $request->validate([
            'asset_id' => ['required', 'exists:assets,id'],
            'occupancy_status' => ['required'],
            'occupancy_percentage' => ['required', 'numeric', 'between:0,100'],
            'tenant_name' => ['nullable', 'string', 'max:255'],
            'lease_start_date' => ['nullable', 'date'],
            'lease_end_date' => ['nullable', 'date'],
            'monthly_rent' => ['nullable', 'numeric', 'min:0'],
            'remarks' => ['nullable', 'string'],
        ]);

        $validated['updated_by'] = auth()->id();

        $occupancy->update($validated);

        return redirect()
            ->route('admin.assets.operations.occupancy.index')
            ->with('success', 'Occupancy updated successfully.');
    }

    public function destroy(Occupancy $occupancy)
    {
        $occupancy->delete();

        return redirect()
            ->route('admin.assets.operations.occupancy.index')
            ->with('success', 'Occupancy deleted successfully.');
    }
}