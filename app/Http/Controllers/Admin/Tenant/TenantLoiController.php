<?php

namespace App\Http\Controllers\Admin\Tenant;

use App\Http\Controllers\Controller;
use App\Models\TenantLoi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class TenantLoiController extends Controller
{
     private function formData(): array
    {
        return [
            'tenants' => DB::table('tenants')
                ->whereNull('deleted_at')
                ->orderBy('id', 'desc')
                ->get(),

            'units' => DB::table('units')
                ->whereNull('deleted_at')
                ->orderBy('unit_no')
                ->get(),
        ];
    }

    public function index(Request $request)
    {
        TenantLoi::whereNotNull('expiry_date')
            ->whereDate('expiry_date', '<', today())
            ->whereNotIn('status', ['ACCEPTED', 'REJECTED', 'EXPIRED'])
            ->update(['status' => 'EXPIRED']);

        $query = TenantLoi::query()
            ->whereHas('tenant')
            ->with(['tenant', 'unit']);

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('loi_number', 'like', "%{$search}%")
                    ->orWhere('title', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('tenant_id')) {
            $query->where('tenant_id', $request->tenant_id);
        }

        $lois = $query->latest()->paginate(15)->withQueryString();

        $stats = [
            'total' => TenantLoi::count(),
            'draft' => TenantLoi::where('status', 'DRAFT')->count(),
            'pending' => TenantLoi::where('status', 'PENDING_APPROVAL')->count(),
            'approved' => TenantLoi::where('status', 'APPROVED')->count(),
            'accepted' => TenantLoi::where('status', 'ACCEPTED')->count(),
            'expired' => TenantLoi::where('status', 'EXPIRED')->count(),
        ];

        return view('admin.tenants.lois.index', compact('lois', 'stats'));
    }

    public function create()
    {
        return view(
            'admin.tenants.lois.create',
            $this->formData()
        );
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request);

        $data['loi_number'] = $this->generateLoiNumber();
        $data['status'] = 'DRAFT';
        $data['approval_status'] = 'NOT_SUBMITTED';
        $data['acceptance_status'] = 'PENDING';
        $data['created_by'] = Auth::id();
        $data['updated_by'] = Auth::id();

        if ($request->hasFile('attachment')) {
            $data['attachment_path'] = $request->file('attachment')
                ->store('tenant-lois', 'public');
        }

        $loi = TenantLoi::create($data);

        return redirect()
            ->route('admin.tenants.lois.show', $loi)
            ->with('success', 'LOI created successfully.');
    }

    public function show(TenantLoi $loi)
    {
        $loi->load(['tenant', 'unit']);

        return view('admin.tenants.lois.show', compact('loi'));
    }

    public function edit(TenantLoi $loi)
    {
        if (!in_array($loi->status, ['DRAFT', 'REJECTED'], true)) {
            return back()->with(
                'error',
                'Only draft or rejected LOIs can be edited.'
            );
        }

        return view(
            'admin.tenants.lois.edit',
            array_merge($this->formData(), compact('loi'))
        );
    }

    public function update(Request $request, TenantLoi $loi)
    {
        if (!in_array($loi->status, ['DRAFT', 'REJECTED'], true)) {
            return back()->with(
                'error',
                'This LOI can no longer be edited.'
            );
        }

        $data = $this->validatedData($request, $loi);

        $data['updated_by'] = Auth::id();

        if ($request->hasFile('attachment')) {
            if ($loi->attachment_path) {
                Storage::disk('public')->delete($loi->attachment_path);
            }

            $data['attachment_path'] = $request->file('attachment')
                ->store('tenant-lois', 'public');
        }

        $loi->update($data);

        return redirect()
            ->route('admin.tenants.lois.show', $loi)
            ->with('success', 'LOI updated successfully.');
    }

    public function destroy(TenantLoi $loi)
    {
        if (!in_array($loi->status, ['DRAFT', 'REJECTED'], true)) {
            return back()->with(
                'error',
                'Only draft or rejected LOIs can be deleted.'
            );
        }

        if ($loi->attachment_path) {
            Storage::disk('public')->delete($loi->attachment_path);
        }

        $loi->delete();

        return redirect()
            ->route('admin.tenants.lois.index')
            ->with('success', 'LOI deleted successfully.');
    }

    public function submit(TenantLoi $loi)
    {
        if (!in_array($loi->status, ['DRAFT', 'REJECTED'], true)) {
            return back()->with('error', 'This LOI cannot be submitted.');
        }

        $loi->update([
            'status' => 'PENDING_APPROVAL',
            'approval_status' => 'PENDING',
            'rejection_reason' => null,
            'updated_by' => Auth::id(),
        ]);

        return back()->with('success', 'LOI submitted for approval.');
    }

    public function approve(Request $request, TenantLoi $loi)
    {
        $request->validate([
            'approval_notes' => ['nullable', 'string', 'max:5000'],
        ]);

        if ($loi->status !== 'PENDING_APPROVAL') {
            return back()->with('error', 'Only submitted LOIs can be approved.');
        }

        $loi->update([
            'status' => 'APPROVED',
            'approval_status' => 'APPROVED',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'approval_notes' => $request->approval_notes,
            'updated_by' => Auth::id(),
        ]);

        return back()->with('success', 'LOI approved successfully.');
    }

    public function reject(Request $request, TenantLoi $loi)
    {
        $request->validate([
            'rejection_reason' => ['required', 'string', 'max:5000'],
        ]);

        if ($loi->status !== 'PENDING_APPROVAL') {
            return back()->with('error', 'Only submitted LOIs can be rejected.');
        }

        $loi->update([
            'status' => 'REJECTED',
            'approval_status' => 'REJECTED',
            'rejection_reason' => $request->rejection_reason,
            'updated_by' => Auth::id(),
        ]);

        return back()->with('success', 'LOI rejected.');
    }

    public function issue(TenantLoi $loi)
    {
        if ($loi->status !== 'APPROVED') {
            return back()->with('error', 'Approve the LOI before issuing it.');
        }

        $loi->update([
            'status' => 'ISSUED',
            'issue_date' => $loi->issue_date ?? today(),
            'updated_by' => Auth::id(),
        ]);

        return back()->with('success', 'LOI marked as issued.');
    }

    public function accept(Request $request, TenantLoi $loi)
    {
        $request->validate([
            'acceptance_date' => ['required', 'date'],
            'acceptance_notes' => ['nullable', 'string', 'max:5000'],
        ]);

        if (!in_array($loi->status, ['ISSUED', 'APPROVED'], true)) {
            return back()->with('error', 'Only an issued LOI can be accepted.');
        }

        if ($loi->expiry_date && $loi->expiry_date->lt(today())) {
            $loi->update(['status' => 'EXPIRED']);

            return back()->with('error', 'This LOI has expired.');
        }

        $loi->update([
            'status' => 'ACCEPTED',
            'acceptance_status' => 'ACCEPTED',
            'acceptance_date' => $request->acceptance_date,
            'acceptance_notes' => $request->acceptance_notes,
            'updated_by' => Auth::id(),
        ]);

        return back()->with('success', 'LOI acceptance recorded.');
    }

    private function validatedData(
        Request $request,
        ?TenantLoi $loi = null
    ): array {
        $data = $request->validate([
            'tenant_id' => [
                'required',
                'integer',
                Rule::exists('tenants', 'id'),
            ],
            'unit_id' => [
                'required',
                'integer',
                Rule::exists('units', 'id'),
            ],
            'title' => ['nullable', 'string', 'max:255'],
            'key_terms' => ['nullable', 'string', 'max:50000'],
            'conditions' => ['nullable', 'string', 'max:50000'],
            'proposed_rent' => ['nullable', 'numeric', 'min:0'],
            'service_charge' => ['nullable', 'numeric', 'min:0'],
            'security_deposit' => ['nullable', 'numeric', 'min:0'],
            'proposed_area' => ['nullable', 'numeric', 'min:0'],
            'lease_term_months' => ['nullable', 'integer', 'min:1'],
            'issue_date' => ['nullable', 'date'],
            'expiry_date' => ['nullable', 'date', 'after_or_equal:issue_date'],
            'remarks' => ['nullable', 'string', 'max:10000'],
            'attachment' => [
                'nullable',
                'file',
                'mimes:pdf,doc,docx,jpg,jpeg,png',
                'max:10240',
            ],
        ]);

        unset($data['attachment']);

        return $data;
    }

    private function generateLoiNumber(): string
    {
        do {
            $number = 'LOI-' . now()->format('Ymd') . '-'
                . strtoupper(substr(bin2hex(random_bytes(4)), 0, 8));
        } while (TenantLoi::where('loi_number', $number)->exists());

        return $number;
    }
}