<?php

namespace App\Http\Controllers\Admin\Tenant;

use App\Http\Controllers\Controller;
use App\Models\LeaseApproval;
use App\Models\LeaseApprovalStep;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class LeaseApprovalController extends Controller
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

            'lois' => DB::table('tenant_lois')
                ->whereIn('status', ['APPROVED', 'ISSUED', 'ACCEPTED'])
                ->orderByDesc('id')
                ->get(),

            'users' => DB::table('users')
                ->orderBy('name')
                ->get(),
        ];
    }

    public function index(Request $request)
    {
        $query = LeaseApproval::query()
            ->with(['tenant', 'unit', 'steps']);

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('approval_number', 'like', "%{$search}%")
                    ->orWhere('lease_summary', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('tenant_id')) {
            $query->where('tenant_id', $request->tenant_id);
        }

        if ($request->input('sla') === 'overdue') {
            $query->whereNotNull('due_at')
                ->where('due_at', '<', now())
                ->whereIn('status', ['PENDING_APPROVAL', 'IN_REVIEW']);
        }

        $approvals = $query->latest()->paginate(15)->withQueryString();

        $stats = [
            'total' => LeaseApproval::count(),
            'draft' => LeaseApproval::where('status', 'DRAFT')->count(),
            'pending' => LeaseApproval::whereIn(
                'status',
                ['PENDING_APPROVAL', 'IN_REVIEW']
            )->count(),
            'approved' => LeaseApproval::where('status', 'APPROVED')->count(),
            'rejected' => LeaseApproval::where('status', 'REJECTED')->count(),
            'overdue' => LeaseApproval::whereNotNull('due_at')
                ->where('due_at', '<', now())
                ->whereIn('status', ['PENDING_APPROVAL', 'IN_REVIEW'])
                ->count(),
        ];

        return view(
            'admin.tenants.lease-approvals.index',
            compact('approvals', 'stats')
        );
    }

    public function create()
    {
        return view(
            'admin.tenants.lease-approvals.create',
            $this->formData()
        );
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request);

        $data['approval_number'] = $this->generateNumber();
        $data['status'] = 'DRAFT';
        $data['credit_check_status'] = $data['credit_check_status'] ?? 'PENDING';
        $data['sla_days'] = $data['sla_days'] ?? 5;
        $data['authority_matrix'] = $this->matrixFromRequest($request);
        $data['created_by'] = Auth::id();
        $data['updated_by'] = Auth::id();

        if ($request->hasFile('attachment')) {
            $data['attachment_path'] = $request->file('attachment')
                ->store('lease-approvals', 'public');
        }

        $approval = LeaseApproval::create($data);

        return redirect()
            ->route('admin.tenants.lease-approvals.show', $approval)
            ->with('success', 'Lease approval request created.');
    }

    public function show(LeaseApproval $leaseApproval)
    {
        $leaseApproval->load(['tenant', 'unit', 'loi', 'steps.approver']);

        return view(
            'admin.tenants.lease-approvals.show',
            ['approval' => $leaseApproval]
        );
    }

    public function edit(LeaseApproval $leaseApproval)
    {
        if (!$this->canEdit($leaseApproval)) {
            return back()->with(
                'error',
                'Only draft or returned requests can be edited.'
            );
        }

        return view(
            'admin.tenants.lease-approvals.edit',
            array_merge(
                $this->formData(),
                ['approval' => $leaseApproval]
            )
        );
    }

    public function update(Request $request, LeaseApproval $leaseApproval)
    {
        if (!$this->canEdit($leaseApproval)) {
            return back()->with(
                'error',
                'This request is locked because approval has started.'
            );
        }

        $data = $this->validatedData($request);
        $data['authority_matrix'] = $this->matrixFromRequest($request);
        $data['updated_by'] = Auth::id();

        if ($request->hasFile('attachment')) {
            if ($leaseApproval->attachment_path) {
                Storage::disk('public')->delete($leaseApproval->attachment_path);
            }

            $data['attachment_path'] = $request->file('attachment')
                ->store('lease-approvals', 'public');
        }

        $leaseApproval->update($data);

        return redirect()
            ->route('admin.tenants.lease-approvals.show', $leaseApproval)
            ->with('success', 'Lease approval request updated.');
    }

    public function destroy(LeaseApproval $leaseApproval)
    {
        if (!$this->canEdit($leaseApproval)) {
            return back()->with(
                'error',
                'Only draft or returned requests can be deleted.'
            );
        }

        if ($leaseApproval->attachment_path) {
            Storage::disk('public')->delete($leaseApproval->attachment_path);
        }

        $leaseApproval->delete();

        return redirect()
            ->route('admin.tenants.lease-approvals.index')
            ->with('success', 'Approval request deleted.');
    }

    public function submit(LeaseApproval $leaseApproval)
    {
        if (!$this->canEdit($leaseApproval)) {
            return back()->with('error', 'This request cannot be submitted.');
        }

        $matrix = $leaseApproval->authority_matrix ?? [];
        $roles = ['COMMERCIAL', 'LEGAL', 'FINANCE'];

        DB::transaction(function () use ($leaseApproval, $matrix, $roles) {
            $leaseApproval->steps()->delete();

            foreach ($roles as $role) {
                $userId = $matrix[$role] ?? null;

                LeaseApprovalStep::create([
                    'lease_approval_id' => $leaseApproval->id,
                    'step_name' => $role,
                    'approver_user_id' => $userId ?: null,
                    'decision' => 'PENDING',
                ]);
            }

            $leaseApproval->update([
                'status' => 'PENDING_APPROVAL',
                'submitted_at' => now(),
                'due_at' => now()->addDays((int) $leaseApproval->sla_days),
                'updated_by' => Auth::id(),
            ]);
        });

        return back()->with('success', 'Request submitted for approval.');
    }

    public function decide(Request $request, LeaseApproval $leaseApproval)
    {
        $data = $request->validate([
            'step_name' => [
                'required',
                Rule::in(['COMMERCIAL', 'LEGAL', 'FINANCE']),
            ],
            'decision' => [
                'required',
                Rule::in(['APPROVED', 'REJECTED', 'CHANGES_REQUESTED']),
            ],
            'comments' => ['nullable', 'string', 'max:5000'],
        ]);

        if (!in_array($leaseApproval->status, [
            'PENDING_APPROVAL', 'IN_REVIEW',
        ], true)) {
            return back()->with('error', 'This request is not awaiting decisions.');
        }

        $step = $leaseApproval->steps()
            ->where('step_name', $data['step_name'])
            ->first();

        if (!$step || $step->decision !== 'PENDING') {
            return back()->with(
                'error',
                'This approval step has already been decided or does not exist.'
            );
        }

        // If a user was assigned to this step, only that user may decide it.
        if ($step->approver_user_id
            && (int) $step->approver_user_id !== (int) Auth::id()) {
            abort(403, 'You are not assigned to this approval step.');
        }

        DB::transaction(function () use ($leaseApproval, $step, $data) {
            $step->update([
                'decision' => $data['decision'],
                'comments' => $data['comments'],
                'decided_at' => now(),
            ]);

            if ($data['decision'] === 'REJECTED') {
                $leaseApproval->update([
                    'status' => 'REJECTED',
                    'final_decision' => 'REJECTED',
                    'final_notes' => $data['comments'],
                    'decided_at' => now(),
                    'updated_by' => Auth::id(),
                ]);

                return;
            }

            if ($data['decision'] === 'CHANGES_REQUESTED') {
                $leaseApproval->update([
                    'status' => 'CHANGES_REQUESTED',
                    'final_decision' => 'CHANGES_REQUESTED',
                    'final_notes' => $data['comments'],
                    'updated_by' => Auth::id(),
                ]);

                return;
            }

            $remaining = $leaseApproval->steps()
                ->where('decision', 'PENDING')
                ->exists();

            if (!$remaining) {
                $leaseApproval->update([
                    'status' => 'APPROVED',
                    'final_decision' => 'APPROVED',
                    'final_notes' => $data['comments'],
                    'decided_at' => now(),
                    'updated_by' => Auth::id(),
                ]);
            } else {
                $leaseApproval->update([
                    'status' => 'IN_REVIEW',
                    'updated_by' => Auth::id(),
                ]);
            }
        });

        return back()->with('success', 'Approval decision recorded.');
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'tenant_id' => ['required', 'integer', Rule::exists('tenants', 'id')],
            'unit_id' => ['required', 'integer', Rule::exists('units', 'id')],
            'loi_id' => ['nullable', 'integer', Rule::exists('tenant_lois', 'id')],
            'lease_summary' => ['required', 'string', 'max:10000'],
            'proposed_rent' => ['nullable', 'numeric', 'min:0'],
            'agreed_rent' => ['nullable', 'numeric', 'min:0'],
            'service_charge' => ['nullable', 'numeric', 'min:0'],
            'security_deposit' => ['nullable', 'numeric', 'min:0'],
            'lease_term_months' => ['nullable', 'integer', 'min:1'],
            'proposed_start_date' => ['nullable', 'date'],
            'proposed_end_date' => ['nullable', 'date', 'after_or_equal:proposed_start_date'],
            'deviations' => ['nullable', 'string', 'max:50000'],
            'exceptions' => ['nullable', 'string', 'max:50000'],
            'credit_check_status' => [
                'required',
                Rule::in(['PENDING', 'PASSED', 'FAILED', 'CONDITIONAL']),
            ],
            'credit_risk_rating' => [
                'nullable',
                Rule::in(['LOW', 'MEDIUM', 'HIGH', 'CRITICAL']),
            ],
            'credit_check_notes' => ['nullable', 'string', 'max:10000'],
            'conditions' => ['nullable', 'string', 'max:50000'],
            'sla_days' => ['required', 'integer', 'min:1', 'max:90'],
            'commercial_approver_id' => ['nullable', 'integer', Rule::exists('users', 'id')],
            'legal_approver_id' => ['nullable', 'integer', Rule::exists('users', 'id')],
            'finance_approver_id' => ['nullable', 'integer', Rule::exists('users', 'id')],
            'attachment' => ['nullable', 'file', 'mimes:pdf,doc,docx,jpg,jpeg,png', 'max:10240'],
        ]);
    }

    private function matrixFromRequest(Request $request): array
    {
        return [
            'COMMERCIAL' => $request->input('commercial_approver_id'),
            'LEGAL' => $request->input('legal_approver_id'),
            'FINANCE' => $request->input('finance_approver_id'),
        ];
    }

    private function canEdit(LeaseApproval $approval): bool
    {
        return in_array($approval->status, [
            'DRAFT', 'CHANGES_REQUESTED',
        ], true);
    }

    private function generateNumber(): string
    {
        do {
            $number = 'LAP-' . now()->format('Ymd') . '-'
                . strtoupper(bin2hex(random_bytes(4)));
        } while (LeaseApproval::where('approval_number', $number)->exists());

        return $number;
    }
}
