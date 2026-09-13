<?php

namespace App\Http\Controllers\Admin\Assets;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\ProposalUnitRepository;
use App\Models\Proposal;
use App\Models\Unit;

class ProposalUnitController extends Controller
{
    protected $repo;

    public function __construct(ProposalUnitRepository $repo)
    {
        $this->repo = $repo;

        $this->middleware('permission:proposal_units.view')->only(['index', 'show']);
        $this->middleware('permission:proposal_units.create')->only(['create', 'store']);
        $this->middleware('permission:proposal_units.edit')->only(['edit', 'update']);
        $this->middleware('permission:proposal_units.delete')->only(['destroy']);
    }

    public function index()
    {
        $items = $this->repo->paginate(20);

        return view('admin.assets.proposal_units.index', compact('items'));
    }

    public function create()
    {
        $proposals = '';//Proposal::pluck('proposal_id', 'id');
        $units = Unit::pluck('unit_no', 'id');

        return view('admin.assets.proposal_units.create', compact('proposals', 'units'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'proposal_id' => 'required|integer|exists:proposals,id',
            'unit_id' => 'required|integer|exists:units,id',
            'proposed_rent' => 'nullable|numeric',
            'proposed_cam_rate' => 'nullable|numeric',
            'proposed_security_deposit' => 'nullable|numeric',
            'rent_free_days' => 'nullable|integer',
            'fitout_period_days' => 'nullable|integer',
            'remarks' => 'nullable|string',
        ]);

        $data['created_by'] = auth()->id();
        $data['updated_by'] = auth()->id();

        $this->repo->create($data);

        return redirect()->route('admin.assets.proposal_units.index')->with('success', 'Proposal unit created.');
    }

    public function show($id)
    {
        $item = $this->repo->find($id);

        return view('admin.assets.proposal_units.show', compact('item'));
    }

    public function edit($id)
    {
        $item = $this->repo->find($id);
        $proposals = Proposal::pluck('title', 'id');
        $units = Unit::pluck('unit_no', 'id');

        return view('admin.assets.proposal_units.edit', compact('item', 'proposals', 'units'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'proposal_id' => 'required|integer|exists:proposals,id',
            'unit_id' => 'required|integer|exists:units,id',
            'proposed_rent' => 'nullable|numeric',
            'proposed_cam_rate' => 'nullable|numeric',
            'proposed_security_deposit' => 'nullable|numeric',
            'rent_free_days' => 'nullable|integer',
            'fitout_period_days' => 'nullable|integer',
            'remarks' => 'nullable|string',
        ]);

        $data['updated_by'] = auth()->id();

        $this->repo->update($id, $data);

        return redirect()->route('admin.assets.proposal_units.index')->with('success', 'Proposal unit updated.');
    }

    public function destroy($id)
    {
        $this->repo->delete($id);

        return redirect()->route('admin.assets.proposal_units.index')->with('success', 'Proposal unit deleted.');
    }
}
