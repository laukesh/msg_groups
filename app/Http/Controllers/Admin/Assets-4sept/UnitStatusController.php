<?php

namespace App\Http\Controllers\Admin\Assets;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\UnitStatusRepository;

class UnitStatusController extends Controller
{
    protected $repo;

    public function __construct(UnitStatusRepository $repo)
    {
        $this->repo = $repo;

        $this->middleware('permission:unit_statuses.view')->only(['index', 'show']);
        $this->middleware('permission:unit_statuses.create')->only(['create', 'store']);
        $this->middleware('permission:unit_statuses.edit')->only(['edit', 'update']);
        $this->middleware('permission:unit_statuses.delete')->only(['destroy']);
    }

    public function index()
    {
        $statuses = $this->repo->paginate(25);

        return view('admin.assets.unit_statuses.index', compact('statuses'));
    }

    public function create()
    {
        return view('admin.assets.unit_statuses.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'status_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'color_code' => 'nullable|string|max:20',
            'sort_order' => 'nullable|integer',
            'is_active' => 'required|boolean',
        ]);

        $this->repo->create($data);

        return redirect()->route('admin.assets.unit_statuses.index')->with('success', 'Unit status created.');
    }

    public function show($id)
    {
        $status = $this->repo->find($id);

        return view('admin.assets.unit_statuses.show', compact('status'));
    }

    public function edit($id)
    {
        $status = $this->repo->find($id);

        return view('admin.assets.unit_statuses.edit', compact('status'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'status_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'color_code' => 'nullable|string|max:20',
            'sort_order' => 'nullable|integer',
            'is_active' => 'required|boolean',
        ]);

        $this->repo->update($id, $data);

        return redirect()->route('admin.assets.unit_statuses.index')->with('success', 'Unit status updated.');
    }

    public function destroy($id)
    {
        $this->repo->delete($id);

        return redirect()->route('admin.assets.unit_statuses.index')->with('success', 'Unit status deleted.');
    }
}
