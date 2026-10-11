<?php

namespace App\Http\Controllers\Admin\Revenue;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\CamChargeRepository;
use App\Models\CamCharge;

class CamChargeController extends Controller
{
    protected $repo;

    public function __construct(CamChargeRepository $repo)
    {
        $this->repo = $repo;

        // Example middleware for permission checks - adjust to your permission system
        $this->middleware('can:viewAny,App\\Models\\CamCharge')->only(['index', 'show']);
        $this->middleware('can:create,App\\Models\\CamCharge')->only(['create', 'store']);
        $this->middleware('can:update,App\\Models\\CamCharge')->only(['edit', 'update']);
        $this->middleware('can:delete,App\\Models\\CamCharge')->only(['destroy']);
    }

    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 15);
        $filters = $request->only(['lease_agreement_id']);
        $camCharges = $this->repo->paginate($perPage, $filters);

        return view('admin.revenue.cam_charges.index', compact('camCharges'));
    }

    public function create()
    {
        return view('admin.revenue.cam_charges.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'uuid' => 'nullable|string',
            'lease_agreement_id' => 'nullable|integer',
            'unit_id' => 'nullable|integer',
            'invoice_item_id' => 'nullable|integer',
            'billing_period' => 'nullable|string',
            'period_start' => 'nullable|date',
            'period_end' => 'nullable|date',
            'leasable_area' => 'nullable|numeric',
            'cam_rate' => 'nullable|numeric',
            'escalation_percentage' => 'nullable|numeric',
            'base_amount' => 'nullable|numeric',
            'escalation_amount' => 'nullable|numeric',
            'taxable_amount' => 'nullable|numeric',
            'tax_amount' => 'nullable|numeric',
            'total_amount' => 'nullable|numeric',
            'charge_status' => 'nullable|string',
            'remarks' => 'nullable|string',
        ]);

        $data['created_by'] = auth()->id();

        $this->repo->create($data);

        return redirect()->route('admin.revenue.cam_charges.index')->with('success', 'CAM charge created.');
    }

    public function show($id)
    {
        $camCharge = $this->repo->find($id);
        return view('admin.revenue.cam_charges.show', compact('camCharge'));
    }

    public function edit($id)
    {
        $camCharge = $this->repo->find($id);
        return view('admin.revenue.cam_charges.edit', compact('camCharge'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'uuid' => 'nullable|string',
            'lease_agreement_id' => 'nullable|integer',
            'unit_id' => 'nullable|integer',
            'invoice_item_id' => 'nullable|integer',
            'billing_period' => 'nullable|string',
            'period_start' => 'nullable|date',
            'period_end' => 'nullable|date',
            'leasable_area' => 'nullable|numeric',
            'cam_rate' => 'nullable|numeric',
            'escalation_percentage' => 'nullable|numeric',
            'base_amount' => 'nullable|numeric',
            'escalation_amount' => 'nullable|numeric',
            'taxable_amount' => 'nullable|numeric',
            'tax_amount' => 'nullable|numeric',
            'total_amount' => 'nullable|numeric',
            'charge_status' => 'nullable|string',
            'remarks' => 'nullable|string',
        ]);

        $data['updated_by'] = auth()->id();

        $this->repo->update($id, $data);

        return redirect()->route('admin.revenue.cam_charges.index')->with('success', 'CAM charge updated.');
    }

    public function destroy($id)
    {
        $this->repo->delete($id);
        return redirect()->route('admin.revenue.cam_charges.index')->with('success', 'CAM charge deleted.');
    }
}
