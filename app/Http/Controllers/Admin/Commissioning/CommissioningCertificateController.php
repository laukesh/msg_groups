<?php

namespace App\Http\Controllers\Admin\Commissioning;

use App\Http\Controllers\Controller;
use App\Models\CommissioningCertificate;
use App\Models\CommissioningScope;
use App\Models\Project;
use App\Models\User;
use App\Services\CommissioningAuditLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CommissioningCertificateController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(
        Request $request,
        Project $project
    ): View {
        $query = CommissioningCertificate::with([
            'scope',
            'issuedBy',
            'approvedBy',
        ])
            ->where(
                'project_id',
                $project->id
            );

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->where(
                    'certificate_no',
                    'like',
                    "%{$search}%"
                )
                    ->orWhere(
                        'certificate_type',
                        'like',
                        "%{$search}%"
                    )
                    ->orWhere(
                        'document_reference',
                        'like',
                        "%{$search}%"
                    )
                    ->orWhereHas('scope', function ($scope) use ($search) {

                        $scope->where(
                            'scope_code',
                            'like',
                            "%{$search}%"
                        )
                            ->orWhere(
                                'scope_name',
                                'like',
                                "%{$search}%"
                            );

                    });

            });
        }

        /*
        |--------------------------------------------------------------------------
        | Status
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {

            $query->where(
                'status',
                $request->status
            );

        }

        /*
        |--------------------------------------------------------------------------
        | Certificate Type
        |--------------------------------------------------------------------------
        */

        if ($request->filled('certificate_type')) {

            $query->where(
                'certificate_type',
                'like',
                '%' . trim($request->certificate_type) . '%'
            );

        }

        /*
        |--------------------------------------------------------------------------
        | Date
        |--------------------------------------------------------------------------
        */

        if ($request->filled('from_date')) {

            $query->whereDate(
                'issue_date',
                '>=',
                $request->from_date
            );

        }

        if ($request->filled('to_date')) {

            $query->whereDate(
                'issue_date',
                '<=',
                $request->to_date
            );

        }

        $certificates = $query
            ->latest('issue_date')
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | KPI
        |--------------------------------------------------------------------------
        */

        $base = CommissioningCertificate::where(
            'project_id',
            $project->id
        );

        $totalCertificates = (clone $base)->count();

        $draftCertificates = (clone $base)
            ->where('status', 'Draft')
            ->count();

        $submittedCertificates = (clone $base)
            ->where('status', 'Submitted')
            ->count();

        $approvedCertificates = (clone $base)
            ->where('status', 'Approved')
            ->count();

        $rejectedCertificates = (clone $base)
            ->where('status', 'Rejected')
            ->count();

        $expiredCertificates = (clone $base)
            ->where('status', 'Expired')
            ->count();

        $expiringCertificates = (clone $base)
            ->where('status', 'Approved')
            ->whereNotNull('valid_until')
            ->whereDate(
                'valid_until',
                '<=',
                now()->addDays(30)
            )
            ->whereDate(
                'valid_until',
                '>=',
                now()
            )
            ->count();

        return view(
            'admin.commissioning.certificates.index',
            compact(
                'project',
                'certificates',
                'totalCertificates',
                'draftCertificates',
                'submittedCertificates',
                'approvedCertificates',
                'rejectedCertificates',
                'expiredCertificates',
                'expiringCertificates'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create(Project $project): View
    {
        $scopes = CommissioningScope::where(
            'project_id',
            $project->id
        )
            ->orderBy('scope_code')
            ->get();

        $users = User::orderBy('name')->get();

        return view(
            'admin.commissioning.certificates.create',
            compact(
                'project',
                'scopes',
                'users'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(
        Request $request,
        Project $project
    ): RedirectResponse {

        $data = $this->validateData($request);

        $this->validateRelations(
            $project,
            $data
        );

        /*
        |--------------------------------------------------------------------------
        | Unique Certificate Number
        |--------------------------------------------------------------------------
        */

        $exists = CommissioningCertificate::where(
            'project_id',
            $project->id
        )
            ->where(
                'certificate_no',
                $data['certificate_no']
            )
            ->exists();

        if ($exists) {

            return back()
                ->withInput()
                ->withErrors([
                    'certificate_no' =>
                        'This certificate number already exists in this project.'
                ]);

        }

        /*
        |--------------------------------------------------------------------------
        | Always start as Draft
        |--------------------------------------------------------------------------
        */

        $data['project_id'] = $project->id;

        $data['status'] = 'Draft';

        $data['created_by'] = auth()->id();

        $data['updated_by'] = auth()->id();

        $certificate = CommissioningCertificate::create(
            $data
        );

        CommissioningAuditLogService::created(
            $certificate,
            'Commissioning certificate created.'
        );

        return redirect()
            ->route(
                'admin.projects.commissioning.certificates.show',
                [$project, $certificate]
            )
            ->with(
                'success',
                'Commissioning certificate created successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(
        Project $project,
        CommissioningCertificate $certificate
    ): View {

        $this->check(
            $project,
            $certificate
        );

        $certificate->load([
            'project',
            'scope.workOrder',
            'issuedBy',
            'approvedBy',
            'createdBy',
            'updatedBy',
            'documents',
        ]);

        return view(
            'admin.commissioning.certificates.show',
            compact(
                'project',
                'certificate'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit(
        Project $project,
        CommissioningCertificate $certificate
    ): View {

        $this->check(
            $project,
            $certificate
        );

        abort_unless(
            in_array(
                $certificate->status,
                [
                    'Draft',
                    'Rejected',
                ],
                true
            ),
            403,
            'Only Draft or Rejected certificates can be edited.'
        );

        $scopes = CommissioningScope::where(
            'project_id',
            $project->id
        )
            ->orderBy('scope_code')
            ->get();

        $users = User::orderBy('name')->get();

        return view(
            'admin.commissioning.certificates.edit',
            compact(
                'project',
                'certificate',
                'scopes',
                'users'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        Project $project,
        CommissioningCertificate $certificate
    ): RedirectResponse {

        $this->check(
            $project,
            $certificate
        );

        abort_unless(
            in_array(
                $certificate->status,
                [
                    'Draft',
                    'Rejected',
                ],
                true
            ),
            403,
            'Only Draft or Rejected certificates can be edited.'
        );

        $data = $this->validateData(
            $request,
            $certificate->id
        );

        $this->validateRelations(
            $project,
            $data
        );

        /*
        |--------------------------------------------------------------------------
        | Duplicate Certificate Number
        |--------------------------------------------------------------------------
        */

        $exists = CommissioningCertificate::where(
            'project_id',
            $project->id
        )
            ->where(
                'certificate_no',
                $data['certificate_no']
            )
            ->where(
                'id',
                '<>',
                $certificate->id
            )
            ->exists();

        if ($exists) {

            return back()
                ->withInput()
                ->withErrors([
                    'certificate_no' =>
                        'This certificate number already exists in this project.'
                ]);

        }

        $oldValues = $certificate->getOriginal();

        /*
        |--------------------------------------------------------------------------
        | Rejected → Draft
        |--------------------------------------------------------------------------
        */

        if ($certificate->status === 'Rejected') {

            $data['status'] = 'Draft';

            $data['rejection_reason'] = null;

        } else {

            $data['status'] = $certificate->status;

        }

        $data['updated_by'] = auth()->id();

        $certificate->update(
            $data
        );

        CommissioningAuditLogService::updated(
            $certificate,
            $oldValues,
            'Commissioning certificate updated.'
        );

        return redirect()
            ->route(
                'admin.projects.commissioning.certificates.show',
                [$project, $certificate]
            )
            ->with(
                'success',
                'Commissioning certificate updated successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Project $project,
        CommissioningCertificate $certificate
    ): RedirectResponse {

        $this->check(
            $project,
            $certificate
        );

        abort_unless(
            in_array(
                $certificate->status,
                [
                    'Draft',
                    'Rejected',
                ],
                true
            ),
            403,
            'Only Draft or Rejected certificates can be deleted.'
        );

        $oldValues = $certificate->toArray();

        CommissioningAuditLogService::deleted(
            $certificate,
            $oldValues,
            'Commissioning certificate deleted.'
        );

        $certificate->delete();

        return redirect()
            ->route(
                'admin.projects.commissioning.certificates.index',
                $project
            )
            ->with(
                'success',
                'Commissioning certificate deleted successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | SUBMIT
    |--------------------------------------------------------------------------
    */

    public function submit(
        Project $project,
        CommissioningCertificate $certificate
    ): RedirectResponse {

        $this->check(
            $project,
            $certificate
        );

        abort_unless(
            $certificate->status === 'Draft',
            422,
            'Only Draft certificates can be submitted.'
        );

        abort_unless(
            !empty($certificate->certificate_no)
            && !empty($certificate->certificate_type)
            && !empty($certificate->document_path),
            422,
            'Certificate number, type and document are required before submission.'
        );

        $oldValues = $certificate->toArray();

        $certificate->update([
            'status' => 'Submitted',
            'submitted_at' => now(),
            'updated_by' => auth()->id(),
        ]);

        CommissioningAuditLogService::updated(
            $certificate,
            $oldValues,
            'Commissioning certificate submitted for approval.'
        );

        return back()->with(
            'success',
            'Certificate submitted for approval.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | APPROVE
    |--------------------------------------------------------------------------
    */

    public function approve(
        Project $project,
        CommissioningCertificate $certificate
    ): RedirectResponse {

        $this->check(
            $project,
            $certificate
        );

        abort_unless(
            $certificate->status === 'Submitted',
            422,
            'Only Submitted certificates can be approved.'
        );

        $oldValues = $certificate->toArray();

        $certificate->update([
            'status' => 'Approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'rejection_reason' => null,
            'updated_by' => auth()->id(),
        ]);

        CommissioningAuditLogService::updated(
            $certificate,
            $oldValues,
            'Commissioning certificate approved.'
        );

        return back()->with(
            'success',
            'Commissioning certificate approved successfully.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | REJECT
    |--------------------------------------------------------------------------
    */

    public function reject(
        Request $request,
        Project $project,
        CommissioningCertificate $certificate
    ): RedirectResponse {

        $this->check(
            $project,
            $certificate
        );

        abort_unless(
            $certificate->status === 'Submitted',
            422,
            'Only Submitted certificates can be rejected.'
        );

        $request->validate([
            'rejection_reason' =>
                'required|string|max:2000',
        ]);

        $oldValues = $certificate->toArray();

        $certificate->update([
            'status' => 'Rejected',
            'rejection_reason' =>
                $request->rejection_reason,
            'approved_by' => null,
            'approved_at' => null,
            'updated_by' => auth()->id(),
        ]);

        CommissioningAuditLogService::updated(
            $certificate,
            $oldValues,
            'Commissioning certificate rejected.'
        );

        return back()->with(
            'success',
            'Certificate rejected successfully.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | EXPIRE
    |--------------------------------------------------------------------------
    */

    public function expire(
        Project $project,
        CommissioningCertificate $certificate
    ): RedirectResponse {

        $this->check(
            $project,
            $certificate
        );

        abort_unless(
            $certificate->status === 'Approved',
            422,
            'Only Approved certificates can be expired.'
        );

        abort_unless(
            $certificate->valid_until
            && $certificate->valid_until->isPast(),
            422,
            'This certificate has not reached its expiry date.'
        );

        $oldValues = $certificate->toArray();

        $certificate->update([
            'status' => 'Expired',
            'updated_by' => auth()->id(),
        ]);

        CommissioningAuditLogService::updated(
            $certificate,
            $oldValues,
            'Commissioning certificate marked as expired.'
        );

        return back()->with(
            'success',
            'Certificate marked as expired.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | VALIDATION
    |--------------------------------------------------------------------------
    */

    private function validateData(
        Request $request
    ): array {

        return $request->validate([
            'commissioning_scope_id' =>
                'nullable|integer|exists:commissioning_scopes,id',

            'certificate_no' =>
                'required|string|max:80',

            'certificate_type' =>
                'required|string|max:150',

            'description' =>
                'nullable|string',

            'issue_date' =>
                'nullable|date',

            'valid_until' =>
                'nullable|date|after_or_equal:issue_date',

            'document_reference' =>
                'nullable|string|max:255',

            'document_path' =>
                'nullable|string|max:500',

            'issued_by' =>
                'nullable|integer|exists:users,id',

            'remarks' =>
                'nullable|string',
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | RELATION VALIDATION
    |--------------------------------------------------------------------------
    */

    private function validateRelations(
        Project $project,
        array $data
    ): void {

        if (!empty($data['commissioning_scope_id'])) {

            abort_unless(
                CommissioningScope::whereKey(
                    $data['commissioning_scope_id']
                )
                    ->where(
                        'project_id',
                        $project->id
                    )
                    ->exists(),
                422,
                'Selected commissioning scope does not belong to this project.'
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | PROJECT CHECK
    |--------------------------------------------------------------------------
    */

    private function check(
        Project $project,
        CommissioningCertificate $certificate
    ): void {

        abort_unless(
            (int) $certificate->project_id ===
            (int) $project->id,
            404
        );
    }
}