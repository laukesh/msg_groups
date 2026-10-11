<?php

namespace App\Http\Controllers\Admin\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class TenantOpeningController extends Controller
{
    private const CHECKLIST = [
        'unit_condition' => 'Unit condition inspected',
        'flooring'       => 'Flooring checked',
        'electrical'     => 'Electrical installation checked',
        'water'          => 'Water supply checked',
        'hvac'            => 'HVAC checked',
        'fire_safety'    => 'Fire safety checked',
        'cleaning'       => 'Unit cleaned',
        'keys'           => 'Keys handed over',
        'access_cards'   => 'Access cards issued',
        'meters'         => 'Utility meters recorded',
        'documents'      => 'Required documents received',
    ];

    public function index(Request $request)
    {
        $query = DB::table('handovers as h')
            ->leftJoin('tenants as t', 't.id', '=', 'h.tenant_id')
            ->leftJoin('units as u', 'u.id', '=', 'h.unit_id')
            ->whereNull('h.deleted_at')
            ->select(
                'h.*',
                't.company_name',
                't.brand_name',
                'u.unit_no'
            );

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('h.handover_number', 'like', "%{$search}%")
                    ->orWhere('t.company_name', 'like', "%{$search}%")
                    ->orWhere('t.brand_name', 'like', "%{$search}%")
                    ->orWhere('u.unit_no', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('h.status', $request->status);
        }

        $handovers = $query
            ->orderByDesc('h.id')
            ->paginate(15)
            ->withQueryString();

        $stats = [
            'total' => DB::table('handovers')->whereNull('deleted_at')->count(),
            'pending' => DB::table('handovers')->whereNull('deleted_at')
                ->whereIn('status', ['Pending', 'Scheduled', 'In Progress'])->count(),
            'completed' => DB::table('handovers')->whereNull('deleted_at')
                ->where('status', 'Completed')->count(),
            'rejected' => DB::table('handovers')->whereNull('deleted_at')
                ->where('status', 'Rejected')->count(),
        ];

        return view('admin.tenants.openings.index', compact('handovers', 'stats'));
    }

    public function create()
    {
        $tenants = DB::table('tenants')
            ->whereNull('deleted_at')
            ->orderBy('company_name')
            ->get(['id', 'company_name', 'brand_name']);

        $units = DB::table('units')
            ->whereNull('deleted_at')
            ->orderBy('unit_no')
            ->get(['id', 'unit_no']);

        $fitoutRequests = DB::table('fitout_requests')
            ->whereNull('deleted_at')
            ->orderByDesc('id')
            ->get(['id', 'request_no', 'tenant_id', 'unit_id', 'lease_agreement_id', 'fitout_status']);

        return view('admin.tenants.openings.create', [
            'tenants' => $tenants,
            'units' => $units,
            'fitoutRequests' => $fitoutRequests,
            'checklistItems' => self::CHECKLIST,
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        $id = DB::transaction(function () use ($data) {
            $id = DB::table('handovers')->insertGetId([
                'uuid' => (string) Str::uuid(),
                'handover_number' => $this->nextNumber(),
                'fitout_request_id' => $data['fitout_request_id'],
                'unit_id' => $data['unit_id'],
                'tenant_id' => $data['tenant_id'],
                'handover_date' => $data['handover_date'] ?? null,
                'handover_type' => $data['handover_type'],
                'status' => 'Scheduled',
                'unit_condition' => $data['unit_condition'] ?? null,
                'key_count' => $data['key_count'] ?? 0,
                'access_card_count' => $data['access_card_count'] ?? 0,
                'electricity_meter_no' => $data['electricity_meter_no'] ?? null,
                'electricity_meter_reading' => $data['electricity_meter_reading'] ?? null,
                'water_meter_no' => $data['water_meter_no'] ?? null,
                'water_meter_reading' => $data['water_meter_reading'] ?? null,
                'checklist_json' => json_encode($data['checklist'] ?? []),
                'outstanding_items' => $data['outstanding_items'] ?? null,
                'remarks' => $data['remarks'] ?? null,
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return $id;
        });

        return redirect()
            ->route('admin.tenants.openings.show', $id)
            ->with('success', 'Tenant handover scheduled successfully.');
    }

    public function show(int $handover)
    {
        $record = $this->findRecord($handover);

        return view('admin.tenants.openings.show', [
            'handover' => $record,
            'checklistItems' => self::CHECKLIST,
            'checklist' => json_decode($record->checklist_json ?? '[]', true) ?: [],
        ]);
    }

    public function edit(int $handover)
    {
        $record = $this->findRecord($handover);

        abort_unless(in_array($record->status, ['Pending', 'Scheduled', 'In Progress']), 403,
            'Only pending or in-progress handovers can be edited.');

        $tenants = DB::table('tenants')->whereNull('deleted_at')
            ->orderBy('company_name')->get(['id', 'company_name', 'brand_name']);

        $units = DB::table('units')->whereNull('deleted_at')
            ->orderBy('unit_no')->get(['id', 'unit_no']);

        $fitoutRequests = DB::table('fitout_requests')->whereNull('deleted_at')
            ->orderByDesc('id')->get([
                'id', 'request_no', 'tenant_id', 'unit_id',
                'lease_agreement_id', 'fitout_status'
            ]);

        return view('admin.tenants.openings.edit', [
            'handover' => $record,
            'tenants' => $tenants,
            'units' => $units,
            'fitoutRequests' => $fitoutRequests,
            'checklistItems' => self::CHECKLIST,
            'checklist' => json_decode($record->checklist_json ?? '[]', true) ?: [],
        ]);
    }

    public function update(Request $request, int $handover)
    {
        $record = $this->findRecord($handover);

        abort_unless(in_array($record->status, ['Pending', 'Scheduled', 'In Progress']), 403,
            'This handover can no longer be edited.');

        $data = $this->validated($request, $record->id);

        DB::table('handovers')->where('id', $record->id)->update([
            'fitout_request_id' => $data['fitout_request_id'],
            'unit_id' => $data['unit_id'],
            'tenant_id' => $data['tenant_id'],
            'handover_date' => $data['handover_date'] ?? null,
            'handover_type' => $data['handover_type'],
            'unit_condition' => $data['unit_condition'] ?? null,
            'key_count' => $data['key_count'] ?? 0,
            'access_card_count' => $data['access_card_count'] ?? 0,
            'electricity_meter_no' => $data['electricity_meter_no'] ?? null,
            'electricity_meter_reading' => $data['electricity_meter_reading'] ?? null,
            'water_meter_no' => $data['water_meter_no'] ?? null,
            'water_meter_reading' => $data['water_meter_reading'] ?? null,
            'checklist_json' => json_encode($data['checklist'] ?? []),
            'outstanding_items' => $data['outstanding_items'] ?? null,
            'remarks' => $data['remarks'] ?? null,
            'updated_by' => auth()->id(),
            'updated_at' => now(),
        ]);

        return redirect()
            ->route('admin.tenants.openings.show', $record->id)
            ->with('success', 'Handover record updated.');
    }

    public function start(int $handover)
    {
        $record = $this->findRecord($handover);

        abort_unless(in_array($record->status, ['Pending', 'Scheduled']), 403);

        DB::table('handovers')->where('id', $record->id)->update([
            'status' => 'In Progress',
            'updated_by' => auth()->id(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Handover inspection started.');
    }

    public function accept(Request $request, int $handover)
    {
        $record = $this->findRecord($handover);

        abort_unless(in_array($record->status, ['In Progress', 'Scheduled']), 403);

        $request->validate([
            'acceptance_notes' => ['nullable', 'string', 'max:5000'],
        ]);

        $checklist = json_decode($record->checklist_json ?? '[]', true) ?: [];
        $missing = array_diff(array_keys(self::CHECKLIST), array_keys($checklist));

        if ($missing) {
            return back()->withErrors([
                'checklist' => 'Complete every checklist item before tenant acceptance.',
            ]);
        }

        if ($record->unit_condition === 'Not Ready' || $record->unit_condition === 'Major Issues') {
            return back()->withErrors([
                'unit_condition' => 'Resolve major issues or mark the unit ready before acceptance.',
            ]);
        }

        DB::table('handovers')->where('id', $record->id)->update([
            'status' => 'Accepted',
            'tenant_accepted_by' => auth()->id(),
            'tenant_accepted_at' => now(),
            'remarks' => trim(($record->remarks ?? '') . "\nTenant acceptance: " . ($request->acceptance_notes ?? 'Accepted')),
            'updated_by' => auth()->id(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Tenant acceptance recorded.');
    }

    public function approve(int $handover)
    {
        $record = $this->findRecord($handover);

        abort_unless($record->status === 'Accepted', 403,
            'Tenant acceptance is required before mall approval.');

        DB::table('handovers')->where('id', $record->id)->update([
            'mall_approved_by' => auth()->id(),
            'mall_approved_at' => now(),
            'updated_by' => auth()->id(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Mall sign-off recorded.');
    }

    public function complete(int $handover)
    {
        $record = $this->findRecord($handover);

        abort_unless($record->status === 'Accepted', 403,
            'Tenant acceptance is required before completion.');

        abort_unless($record->mall_approved_at !== null, 403,
            'Mall approval is required before completion.');

        DB::table('handovers')->where('id', $record->id)->update([
            'status' => 'Completed',
            'updated_by' => auth()->id(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Handover completed.');
    }

    private function findRecord(int $id): object
    {
        $record = DB::table('handovers')
            ->where('id', $id)
            ->whereNull('deleted_at')
            ->first();

        abort_if(!$record, 404);

        return $record;
    }

    private function validated(Request $request, ?int $ignoreId = null): array
    {
        $data = $request->validate([
            'tenant_id' => ['required', 'integer', 'exists:tenants,id'],
            'unit_id' => ['required', 'integer', 'exists:units,id'],
            'fitout_request_id' => [
                'required', 'integer',
                Rule::exists('fitout_requests', 'id'),
            ],
            'handover_date' => ['nullable', 'date'],
            'handover_type' => ['required', Rule::in([
                'Fit-Out Handover', 'Final Handover', 'Partial Handover'
            ])],
            'unit_condition' => ['nullable', Rule::in([
                'Good', 'Minor Issues', 'Major Issues', 'Not Ready'
            ])],
            'key_count' => ['nullable', 'integer', 'min:0', 'max:10000'],
            'access_card_count' => ['nullable', 'integer', 'min:0', 'max:10000'],
            'electricity_meter_no' => ['nullable', 'string', 'max:100'],
            'electricity_meter_reading' => ['nullable', 'numeric', 'min:0'],
            'water_meter_no' => ['nullable', 'string', 'max:100'],
            'water_meter_reading' => ['nullable', 'numeric', 'min:0'],
            'checklist' => ['nullable', 'array'],
            'checklist.*' => ['nullable', Rule::in(['done', 'pending', 'not_applicable'])],
            'outstanding_items' => ['nullable', 'string', 'max:10000'],
            'remarks' => ['nullable', 'string', 'max:10000'],
        ]);

        $fitout = DB::table('fitout_requests')
            ->where('id', $data['fitout_request_id'])
            ->whereNull('deleted_at')
            ->first();

        abort_if(!$fitout, 422, 'Selected fit-out request was not found.');

        if ((int) $fitout->tenant_id !== (int) $data['tenant_id']
            || (int) $fitout->unit_id !== (int) $data['unit_id']) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'fitout_request_id' => 'The selected fit-out request must belong to the selected tenant and unit.',
            ]);
        }

        return $data;
    }

    private function nextNumber(): string
    {
        $prefix = 'HO-' . now()->format('Y') . '-';

        $last = DB::table('handovers')
            ->where('handover_number', 'like', $prefix . '%')
            ->orderByDesc('id')
            ->value('handover_number');

        $next = $last ? ((int) substr($last, -5)) + 1 : 1;

        return $prefix . str_pad((string) $next, 5, '0', STR_PAD_LEFT);
    }
}