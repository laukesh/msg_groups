<?php

namespace App\Http\Controllers\Admin\Assets;

use App\Http\Controllers\Controller;
use App\Repositories\AssetCategoryRepositoryInterface;
use Illuminate\Http\Request;

class AssetCategoryController extends Controller
{
    public function __construct(
        protected AssetCategoryRepositoryInterface $repository
    ) {
    }

    public function index(Request $request)
    {
        $categories = $this->repository->all(
            $request->only([
                'search',
                'is_active',
            ])
        );

        return view(
            'admin.assets.asset_categories.index',
            compact('categories')
        );
    }

    public function create()
    {
        return view('admin.assets.asset_categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_name' => [
                'required',
                'string',
                'max:150',
            ],

            'description' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'is_active' => [
                'required',
                'boolean',
            ],
        ]);

        $validated['created_by'] = auth()->id();

        $this->repository->create($validated);

        return redirect()
            ->route('admin.assets.asset-categories.index')
            ->with('success', 'Asset category created successfully.');
    }

    public function show(int $id)
    {
        $category = $this->repository->find($id);

        return view(
            'admin.assets.asset_categories.show',
            compact('category')
        );
    }

    public function edit(int $id)
    {
        $category = $this->repository->find($id);

        return view(
            'admin.assets.asset_categories.edit',
            compact('category')
        );
    }

    public function update(Request $request, int $id)
    {
        $validated = $request->validate([
            'category_name' => [
                'required',
                'string',
                'max:150',
            ],

            'description' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'is_active' => [
                'required',
                'boolean',
            ],
        ]);

        $validated['updated_by'] = auth()->id();

        $this->repository->update($id, $validated);

        return redirect()
            ->route('admin.assets.asset-categories.index')
            ->with('success', 'Asset category updated successfully.');
    }

    public function destroy(int $id)
    {
        $this->repository->delete($id);

        return redirect()
            ->route('admin.assets.asset-categories.index')
            ->with('success', 'Asset category deleted successfully.');
    }
}