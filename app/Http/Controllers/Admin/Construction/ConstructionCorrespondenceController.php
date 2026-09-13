<?php

namespace App\Http\Controllers\Admin\Construction;

use App\Http\Controllers\Controller;
use App\Models\ConstructionClaim;
use App\Models\ConstructionCorrespondence;
use App\Models\ConstructionDelay;
use App\Models\ConstructionRisk;
use App\Models\ConstructionScheduleActivity;
use App\Models\ConstructionWorkOrder;
use App\Models\Project;
use App\Models\ProcurementContract;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ConstructionCorrespondenceController extends Controller
{
    /**
     * Display correspondence register.
     */
    public function index(Project $project): View
    {
        $query = ConstructionCorrespondence::query()
            ->where('project_id', $project->id)
            ->with([
                'workOrder',
                'procurementContract',
                'claim',
                'delay',
                'risk',
                'assignedTo',
            ])
            ->latest('correspondence_date');

        if ($search = request('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('correspondence_number', 'like', "%{$search}%")
                    ->orWhere('reference_number', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%")
                    ->orWhere('sender_name', 'like', "%{$search}%")
                    ->orWhere('receiver_name', 'like', "%{$search}%");
            });
        }

        if ($type = request('correspondence_type')) {
            $query->where('correspondence_type', $type);
        }

        if ($status = request('status')) {
            $query->where('status', $status);
        }

        if ($priority = request('priority')) {
            $query->where('priority', $priority);
        }

        $correspondences = $query->paginate(20);

        return view(
            'construction.correspondence.index',
            compact('project', 'correspondences')
        );
    }


    /**
     * Show create form.
     */
    public function create(Project $project): View
    {
        $data = $this->loadReferenceData($project);

        return view(
            'construction.correspondence.create',
            array_merge(
                ['project' => $project],
                $data
            )
        );
    }


    /**
     * Store correspondence.
     */
    public function store(
        Request $request,
        Project $project
    ): RedirectResponse {
        $validated = $this->validateCorrespondence($request);

        $this->validateRelationships(
            $project,
            $validated
        );

        $validated['project_id'] = $project->id;

        $validated['correspondence_number'] =
            $this->generateCorrespondenceNumber();

        $validated['created_by'] = auth()->id();
        $validated['updated_by'] = auth()->id();
        $validated['status'] = 'Draft';

        $correspondence = ConstructionCorrespondence::create(
            $validated
        );

        $this->addHistory(
            $correspondence,
            'Created',
            null,
            $correspondence->status,
            'Correspondence created.'
        );

        return redirect()
            ->route(
                'admin.projects.construction.correspondence.show',
                [
                    'project' => $project,
                    'correspondence' => $correspondence,
                ]
            )
            ->with(
                'success',
                'Correspondence created successfully.'
            );
    }


    /**
     * Display correspondence.
     */
    public function show(
        Project $project,
        ConstructionCorrespondence $correspondence
    ): View {
        $this->validateProject(
            $project,
            $correspondence
        );

        $correspondence->load([
            'project',
            'workOrder',
            'procurementContract',
            'claim',
            'delay',
            'risk',
            'assignedTo',
            'creator',
            'updater',
            'documents.uploadedBy',
            'history.performedBy',
        ]);

        return view(
            'construction.correspondence.show',
            compact(
                'project',
                'correspondence'
            )
        );
    }


    /**
     * Show edit form.
     */
    public function edit(
        Project $project,
        ConstructionCorrespondence $correspondence
    ): View {
        $this->validateProject(
            $project,
            $correspondence
        );

        $data = $this->loadReferenceData($project);

        return view(
            'construction.correspondence.edit',
            array_merge(
                [
                    'project' => $project,
                    'correspondence' => $correspondence,
                ],
                $data
            )
        );
    }


    /**
     * Update correspondence.
     */
    public function update(
        Request $request,
        Project $project,
        ConstructionCorrespondence $correspondence
    ): RedirectResponse {
        $this->validateProject(
            $project,
            $correspondence
        );

        $oldStatus = $correspondence->status;

        $validated = $this->validateCorrespondence(
            $request
        );

        $this->validateRelationships(
            $project,
            $validated
        );

        $validated['updated_by'] = auth()->id();

        $correspondence->update($validated);

        if ($oldStatus !== $correspondence->status) {

            $this->addHistory(
                $correspondence,
                'Status Updated',
                $oldStatus,
                $correspondence->status,
                'Correspondence status updated.'
            );

        } else {

            $this->addHistory(
                $correspondence,
                'Updated',
                $oldStatus,
                $correspondence->status,
                'Correspondence updated.'
            );
        }

        return redirect()
            ->route(
                'admin.projects.construction.correspondence.show',
                [
                    'project' => $project,
                    'correspondence' => $correspondence,
                ]
            )
            ->with(
                'success',
                'Correspondence updated successfully.'
            );
    }


    /**
     * Register correspondence.
     */
    public function register(
        Project $project,
        ConstructionCorrespondence $correspondence
    ): RedirectResponse {
        $this->validateProject(
            $project,
            $correspondence
        );

        $this->changeStatus(
            $correspondence,
            'Registered',
            'Correspondence registered.'
        );

        return back()
            ->with(
                'success',
                'Correspondence registered successfully.'
            );
    }


    /**
     * Start review.
     */
    public function review(
        Project $project,
        ConstructionCorrespondence $correspondence
    ): RedirectResponse {
        $this->validateProject(
            $project,
            $correspondence
        );

        $this->changeStatus(
            $correspondence,
            'Under Review',
            'Correspondence moved to review.'
        );

        return back()
            ->with(
                'success',
                'Correspondence moved to review.'
            );
    }


    /**
     * Mark action required.
     */
    public function actionRequired(
        Request $request,
        Project $project,
        ConstructionCorrespondence $correspondence
    ): RedirectResponse {
        $this->validateProject(
            $project,
            $correspondence
        );

        $validated = $request->validate([
            'action_description' => [
                'required',
                'string',
            ],

            'assigned_to' => [
                'nullable',
                'integer',
                'exists:users,id',
            ],

            'responsible_party_type' => [
                'nullable',
                'in:Client,Consultant,Contractor,Supplier,Project Team,Other',
            ],

            'responsible_party_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'response_due_date' => [
                'nullable',
                'date',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],
        ]);

        $oldStatus = $correspondence->status;

        $correspondence->update([
            'status' => 'Action Required',

            'action_required' => true,

            'action_description' =>
                $validated['action_description'],

            'assigned_to' =>
                $validated['assigned_to'] ?? null,

            'responsible_party_type' =>
                $validated['responsible_party_type'] ?? null,

            'responsible_party_name' =>
                $validated['responsible_party_name'] ?? null,

            'response_required' => true,

            'response_due_date' =>
                $validated['response_due_date'] ?? null,

            'remarks' =>
                $validated['remarks'] ?? $correspondence->remarks,

            'updated_by' => auth()->id(),
        ]);

        $this->addHistory(
            $correspondence,
            'Action Required',
            $oldStatus,
            $correspondence->status,
            $validated['action_description']
        );

        return back()
            ->with(
                'success',
                'Correspondence marked as action required.'
            );
    }


    /**
     * Mark correspondence as responded.
     */
    public function respond(
        Request $request,
        Project $project,
        ConstructionCorrespondence $correspondence
    ): RedirectResponse {
        $this->validateProject(
            $project,
            $correspondence
        );

        $validated = $request->validate([
            'response_date' => [
                'required',
                'date',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],
        ]);

        $oldStatus = $correspondence->status;

        $correspondence->update([
            'status' => 'Responded',

            'response_date' =>
                $validated['response_date'],

            'remarks' =>
                $validated['remarks'] ?? $correspondence->remarks,

            'updated_by' => auth()->id(),
        ]);

        $this->addHistory(
            $correspondence,
            'Responded',
            $oldStatus,
            'Responded',
            'Response recorded.'
        );

        return back()
            ->with(
                'success',
                'Correspondence response recorded.'
            );
    }


    /**
     * Close correspondence.
     */
    public function close(
        Request $request,
        Project $project,
        ConstructionCorrespondence $correspondence
    ): RedirectResponse {
        $this->validateProject(
            $project,
            $correspondence
        );

        $validated = $request->validate([
            'remarks' => [
                'nullable',
                'string',
            ],
        ]);

        $oldStatus = $correspondence->status;

        $correspondence->update([
            'status' => 'Closed',

            'action_required' => false,

            'remarks' =>
                $validated['remarks'] ?? $correspondence->remarks,

            'updated_by' => auth()->id(),
        ]);

        $this->addHistory(
            $correspondence,
            'Closed',
            $oldStatus,
            'Closed',
            $validated['remarks'] ?? 'Correspondence closed.'
        );

        return back()
            ->with(
                'success',
                'Correspondence closed successfully.'
            );
    }


    /**
     * Archive correspondence.
     */
    public function archive(
        Project $project,
        ConstructionCorrespondence $correspondence
    ): RedirectResponse {
        $this->validateProject(
            $project,
            $correspondence
        );

        $oldStatus = $correspondence->status;

        $correspondence->update([
            'status' => 'Archived',
            'updated_by' => auth()->id(),
        ]);

        $this->addHistory(
            $correspondence,
            'Archived',
            $oldStatus,
            'Archived',
            'Correspondence archived.'
        );

        return back()
            ->with(
                'success',
                'Correspondence archived successfully.'
            );
    }


    /**
     * Delete correspondence.
     */
    public function destroy(
        Project $project,
        ConstructionCorrespondence $correspondence
    ): RedirectResponse {
        $this->validateProject(
            $project,
            $correspondence
        );

        if (
            !in_array(
                $correspondence->status,
                ['Draft', 'Registered']
            )
        ) {
            return back()
                ->with(
                    'error',
                    'Only Draft or Registered correspondence can be deleted.'
                );
        }

        $correspondence->delete();

        return redirect()
            ->route(
                'admin.projects.construction.correspondence.index',
                $project
            )
            ->with(
                'success',
                'Correspondence deleted successfully.'
            );
    }


    /**
     * Validate correspondence input.
     */
    protected function validateCorrespondence(
        Request $request
    ): array {
        return $request->validate([

            'reference_number' => [
                'nullable',
                'string',
                'max:150',
            ],

            'correspondence_type' => [
                'required',
                'in:Incoming,Outgoing,Internal,Notice,Instruction,Letter,Email,Memo,Other',
            ],

            'correspondence_date' => [
                'required',
                'date',
            ],

            'received_date' => [
                'nullable',
                'date',
            ],

            'sent_date' => [
                'nullable',
                'date',
            ],

            'subject' => [
                'required',
                'string',
                'max:255',
            ],

            'sender_type' => [
                'nullable',
                'in:Client,Consultant,Contractor,Supplier,Authority,Project Team,Other',
            ],

            'sender_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'sender_organization' => [
                'nullable',
                'string',
                'max:255',
            ],

            'receiver_type' => [
                'nullable',
                'in:Client,Consultant,Contractor,Supplier,Authority,Project Team,Other',
            ],

            'receiver_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'receiver_organization' => [
                'nullable',
                'string',
                'max:255',
            ],

            'communication_method' => [
                'required',
                'in:Email,Letter,Meeting,Phone,Portal,Hand Delivery,Other',
            ],

            'priority' => [
                'required',
                'in:Low,Medium,High,Critical',
            ],

            /*'status' => [
                'required',
                'in:Draft,Registered,Under Review,Action Required,Responded,Closed,Archived',
            ],*/

            'response_required' => [
                'nullable',
                'boolean',
            ],

            'response_due_date' => [
                'nullable',
                'date',
            ],

            'response_date' => [
                'nullable',
                'date',
            ],

            'action_required' => [
                'nullable',
                'boolean',
            ],

            'action_description' => [
                'nullable',
                'string',
            ],

            'assigned_to' => [
                'nullable',
                'integer',
                'exists:users,id',
            ],

            'responsible_party_type' => [
                'nullable',
                'in:Client,Consultant,Contractor,Supplier,Project Team,Other',
            ],

            'responsible_party_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],

            'construction_work_order_id' => [
                'nullable',
                'integer',
                'exists:construction_work_orders,id',
            ],

            'procurement_contract_id' => [
                'nullable',
                'integer',
                'exists:procurement_contracts,id',
            ],

            'construction_claim_id' => [
                'nullable',
                'integer',
                'exists:construction_claims,id',
            ],

            'construction_delay_id' => [
                'nullable',
                'integer',
                'exists:construction_delays,id',
            ],

            'construction_risk_id' => [
                'nullable',
                'integer',
                'exists:construction_risks,id',
            ],
        ]);
    }


    /**
     * Validate all selected references belong to project.
     */
    protected function validateRelationships(
        Project $project,
        array $data
    ): void {
        if (!empty($data['construction_work_order_id'])) {

            $exists = ConstructionWorkOrder::query()
                ->where('id', $data['construction_work_order_id'])
                ->where('project_id', $project->id)
                ->exists();

            if (!$exists) {
                abort(
                    422,
                    'Selected work order does not belong to this project.'
                );
            }
        }


        /*if (!empty($data['procurement_contract_id'])) {

            $exists = ProcurementContract::query()
                ->where(
                    'id',
                    $data['procurement_contract_id']
                )
                ->where('project_id', $project->id)
                ->exists();

            if (!$exists) {
                abort(
                    422,
                    'Selected contract does not belong to this project.'
                );
            }
        }*/

        if (!empty($validated['procurement_contract_id'])) {

            $exists = ProcurementContract::query()
                ->whereKey($validated['procurement_contract_id'])
                ->whereHas(
                    'tender',
                    function ($query) use ($project) {

                        $query->whereHas(
                            'package',
                            function ($query) use ($project) {

                                $query->whereHas(
                                    'procurementPlan',
                                    function ($query) use ($project) {

                                        $query->where(
                                            'project_id',
                                            $project->id
                                        );

                                    }
                                );

                            }
                        );

                    }
                )
                ->exists();

            if (!$exists) {

                abort(
                    422,
                    'Selected procurement contract does not belong to this project.'
                );

            }
        }


        if (!empty($data['construction_claim_id'])) {

            $exists = ConstructionClaim::query()
                ->where(
                    'id',
                    $data['construction_claim_id']
                )
                ->where('project_id', $project->id)
                ->exists();

            if (!$exists) {
                abort(
                    422,
                    'Selected claim does not belong to this project.'
                );
            }
        }


        if (!empty($data['construction_delay_id'])) {

            $exists = ConstructionDelay::query()
                ->where(
                    'id',
                    $data['construction_delay_id']
                )
                ->where('project_id', $project->id)
                ->exists();

            if (!$exists) {
                abort(
                    422,
                    'Selected delay does not belong to this project.'
                );
            }
        }


        if (!empty($data['construction_risk_id'])) {

            $exists = ConstructionRisk::query()
                ->where(
                    'id',
                    $data['construction_risk_id']
                )
                ->where('project_id', $project->id)
                ->exists();

            if (!$exists) {
                abort(
                    422,
                    'Selected risk does not belong to this project.'
                );
            }
        }
    }


    /**
     * Load project reference data.
     */
    protected function loadReferenceData(
        Project $project
    ): array {

        $contracts = ProcurementContract::query()
        ->whereHas(
            'tender',
            function ($query) use ($project) {

                $query->whereHas(
                    'package',
                    function ($query) use ($project) {

                        $query->whereHas(
                            'procurementPlan',
                            function ($query) use ($project) {

                                $query->where(
                                    'project_id',
                                    $project->id
                                );

                            }
                        );

                    }
                );

            }
        )
        ->with([
            'bidder',
            'tender.package.procurementPlan',
        ])
        ->orderByDesc('id')
        ->get();
        return [

            'workOrders' => ConstructionWorkOrder::query()
                ->where('project_id', $project->id)
                ->orderBy('work_order_number')
                ->get(),

            'contracts' => $contracts,

            'claims' => ConstructionClaim::query()
                ->where('project_id', $project->id)
                ->orderBy('claim_number')
                ->get(),

            'delays' => ConstructionDelay::query()
                ->where('project_id', $project->id)
                ->orderBy('delay_number')
                ->get(),

            'risks' => ConstructionRisk::query()
                ->where('project_id', $project->id)
                ->orderBy('risk_number')
                ->get(),

            'users' => User::query()
                ->orderBy('name')
                ->get(),
        ];
    }


    /**
     * Change correspondence status.
     */
    protected function changeStatus(
        ConstructionCorrespondence $correspondence,
        string $newStatus,
        string $remarks
    ): void {
        $oldStatus = $correspondence->status;

        $correspondence->update([
            'status' => $newStatus,
            'updated_by' => auth()->id(),
        ]);

        $this->addHistory(
            $correspondence,
            $newStatus,
            $oldStatus,
            $newStatus,
            $remarks
        );
    }


    /**
     * Add correspondence history.
     */
    protected function addHistory(
        ConstructionCorrespondence $correspondence,
        string $action,
        ?string $oldStatus,
        ?string $newStatus,
        ?string $remarks = null
    ): void {
        $correspondence->history()->create([
            'action' => $action,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'remarks' => $remarks,
            'performed_by' => auth()->id(),
            'performed_at' => now(),
        ]);
    }


    /**
     * Generate correspondence number.
     */
    protected function generateCorrespondenceNumber(): string
    {
        $year = now()->format('Y');

        $prefix = 'COR-' . $year . '-';

        $last = ConstructionCorrespondence::withTrashed()
            ->where(
                'correspondence_number',
                'like',
                $prefix . '%'
            )
            ->orderByDesc('id')
            ->value('correspondence_number');

        $sequence = 1;

        if ($last) {

            $lastSequence = (int) substr(
                $last,
                strlen($prefix)
            );

            $sequence = $lastSequence + 1;
        }

        return $prefix .
            str_pad(
                $sequence,
                6,
                '0',
                STR_PAD_LEFT
            );
    }


    /**
     * Validate correspondence belongs to project.
     */
    protected function validateProject(
        Project $project,
        ConstructionCorrespondence $correspondence
    ): void {
        if (
            (int) $correspondence->project_id !==
            (int) $project->id
        ) {
            abort(404);
        }
    }
}