<?php

namespace App\Http\Controllers\Admin\Project;

use App\Http\Controllers\Controller;
use App\Models\DevelopmentBrief;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Models\DevelopmentBriefDocument;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\DevelopmentBriefComment;
use App\Models\DevelopmentBriefVersion;
use App\Models\DevelopmentBriefWorkflowHistory;
class DevelopmentBriefController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | PROJECT-DEPENDENT METHODS
    |--------------------------------------------------------------------------
    */

    /**
     * Project-dependent index
     *
     * URL:
     * /admin/projects/{project}/development-brief
     */
    public function index(Project $project)
    {
        $developmentBriefs = DevelopmentBrief::where(
                'project_id',
                $project->id
            )
            ->withCount('comments')
            ->latest('id')
            ->paginate(15);

        return view(
            'projects.development-brief.index',
            compact(
                'project',
                'developmentBriefs'
            )
        );
    }

    /**
     * Project-dependent create
     */
    public function create(Project $project)
    {
        return view(
            'projects.development-brief.create',
            compact('project')
        );
    }

    /**
     * Project-dependent store
     */
    public function store(Request $request, Project $project)
    {
        $validated = $this->validateBrief($request);

        DB::beginTransaction();

        try {
            $briefReference = $this->generateBriefReference(
                $project->id
            );

            $developmentBrief = DevelopmentBrief::create([
                'project_id' => $project->id,
                'brief_reference' => $briefReference,

                'brief_title' => $validated['brief_title'],

                'version' => $validated['version'] ?? '1.0',

                'revision_number' => $validated['revision_number'] ?? '00',

                'document_date' => $validated['document_date']
                    ?? now()->format('Y-m-d'),

                'prepared_by' => $validated['prepared_by']
                    ?? Auth::id(),

                /*
                |--------------------------------------------------------------------------
                | Business Information
                |--------------------------------------------------------------------------
                */
                'business_need' => $validated['business_need'] ?? null,
                'target_users' => $validated['target_users'] ?? null,
                'target_use' => $validated['target_use'] ?? null,
                'area_schedule' => $validated['area_schedule'] ?? null,
                'room_unit_mix' => $validated['room_unit_mix'] ?? null,

                /*
                |--------------------------------------------------------------------------
                | Requirements
                |--------------------------------------------------------------------------
                */
                'business_requirements' =>
                    $validated['business_requirements'] ?? null,

                'functional_requirements' =>
                    $validated['functional_requirements'] ?? null,

                'operational_requirements' =>
                    $validated['operational_requirements'] ?? null,

                /*
                |--------------------------------------------------------------------------
                | Quality and Design
                |--------------------------------------------------------------------------
                */
                'quality_requirements' =>
                    $validated['quality_requirements'] ?? null,

                'quality_level' =>
                    $validated['quality_level'] ?? null,

                'design_criteria' =>
                    $validated['design_criteria'] ?? null,

                'design_intent' =>
                    $validated['design_intent'] ?? null,

                'technical_requirements' =>
                    $validated['technical_requirements'] ?? null,

                /*
                |--------------------------------------------------------------------------
                | Commercial and Sustainability
                |--------------------------------------------------------------------------
                */
                'commercial_intent' =>
                    $validated['commercial_intent'] ?? null,

                'sustainability_requirements' =>
                    $validated['sustainability_requirements'] ?? null,

                /*
                |--------------------------------------------------------------------------
                | Controls and Dependencies
                |--------------------------------------------------------------------------
                */
                'success_criteria' =>
                    $validated['success_criteria'] ?? null,

                'key_constraints' =>
                    $validated['key_constraints'] ?? null,

                'key_assumptions' =>
                    $validated['key_assumptions'] ?? null,

                'key_dependencies' =>
                    $validated['key_dependencies'] ?? null,

                'required_studies' =>
                    $validated['required_studies'] ?? null,

                'approvals_required' =>
                    $validated['approvals_required'] ?? null,

                /*
                |--------------------------------------------------------------------------
                | Approval
                |--------------------------------------------------------------------------
                */
                'approval_status' =>
                    $validated['approval_status'] ?? 'Draft',

                'approval_remarks' =>
                    $validated['approval_remarks'] ?? null,

                'revision_history' =>
                    $validated['revision_history'] ?? null,

                /*
                |--------------------------------------------------------------------------
                | Audit
                |--------------------------------------------------------------------------
                */
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ]);

            DB::commit();

            return redirect()
                ->route(
                    'admin.projects.development-brief.show',
                    [
                        'project' => $project->id,
                        'developmentBrief' => $developmentBrief->id,
                    ]
                )
                ->with(
                    'success',
                    'Development Brief created successfully.'
                );

        } catch (\Throwable $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Unable to create Development Brief: '
                    . $e->getMessage()
                );
        }
    }

    /**
     * Project-dependent show
     */
    public function show(
        Project $project,
        DevelopmentBrief $developmentBrief
    ) {
        abort_if(
            $developmentBrief->project_id != $project->id,
            404
        );

        $developmentBrief->load([
            'project',
            'creator',
            'preparer',
            'updater',
        ]);

        $developmentBrief->loadCount([
            'comments',
            'documents',
            'versions',
        ]);

        return view(
            'projects.development-brief.show',
            compact(
                'project',
                'developmentBrief'
            )
        );
    }

    /**
     * Project-dependent edit
     */
    public function edit(
        Project $project,
        DevelopmentBrief $developmentBrief
    ) {
        abort_if(
            $developmentBrief->project_id != $project->id,
            404
        );

        return view(
            'projects.development-brief.edit',
            compact(
                'project',
                'developmentBrief'
            )
        );
    }

    /**
     * Project-dependent update
     */
    public function update(
        Request $request,
        Project $project,
        DevelopmentBrief $developmentBrief
    ) {
        abort_if(
            $developmentBrief->project_id != $project->id,
            404
        );

        $validated = $this->validateBrief($request);

        DB::beginTransaction();

        try {
            /*
             * Pass the validated array as the third parameter.
             */
            $this->updateBriefData(
                $developmentBrief,
                $project->id,
                $validated
            );

            DB::commit();

            return redirect()
                ->route(
                    'admin.projects.development-brief.show',
                    [
                        'project' => $project->id,
                        'developmentBrief' => $developmentBrief->id,
                    ]
                )
                ->with(
                    'success',
                    'Development Brief updated successfully.'
                );

        } catch (\Throwable $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Unable to update Development Brief: '
                    . $e->getMessage()
                );
        }
    }

    /**
     * Project-dependent destroy
     */
    public function destroy(
        Project $project,
        DevelopmentBrief $developmentBrief
    ) {
        abort_if(
            $developmentBrief->project_id != $project->id,
            404
        );

        try {
            $developmentBrief->delete();

            return redirect()
                ->route(
                    'admin.projects.development-brief.index',
                    $project->id
                )
                ->with(
                    'success',
                    'Development Brief deleted successfully.'
                );

        } catch (\Throwable $e) {
            return back()->with(
                'error',
                'Unable to delete Development Brief: '
                . $e->getMessage()
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | GLOBAL METHODS
    |--------------------------------------------------------------------------
    */

    /**
     * Global index
     *
     * URL:
     * /admin/development-briefs
     */
    public function globalIndex(Request $request)
    {
        $query = DevelopmentBrief::query()
            ->with(['project'])
            ->latest('id');

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('brief_reference', 'like', "%{$search}%")
                    ->orWhere('title', 'like', "%{$search}%")
                    ->orWhereHas('project', function ($projectQuery) use ($search) {
                        $projectQuery
                            ->where('name', 'like', "%{$search}%")
                            ->orWhere('project_code', 'like', "%{$search}%");
                    });
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Approval Status Filter
        |--------------------------------------------------------------------------
        */
        if ($request->filled('approval_status')) {
            $query->where(
                'approval_status',
                $request->approval_status
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Project Filter
        |--------------------------------------------------------------------------
        */
        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        $briefs = $query->paginate(15)->withQueryString();

        $projects = \App\Models\Project::query()
            ->orderBy('project_name')
            ->get(['id', 'project_name', 'project_code']);

        $statuses = [
            'Draft',
            'Under Review',
            'Revision Required',
            'Approved',
            'Rejected',
            'Archived',
        ];

        return view(
            'projects.development-brief.global-index',
            compact(
                'briefs',
                'projects',
                'statuses'
            )
        );
    }

    /**
     * Global create
     */
    public function globalCreate()
    {
        $projects = Project::orderByDesc('id')->get();

        return view(
            'projects.development-brief.global-create',
            compact('projects')
        );
    }

    /**
     * Global store
     */
    public function globalStore(Request $request)
    {
        $validated = $this->validateBrief(
            $request,
            true
        );

        DB::beginTransaction();

        try {
            $project = Project::findOrFail(
                $validated['project_id']
            );

            $briefReference = $this->generateBriefReference(
                $project->id
            );

            $developmentBrief = DevelopmentBrief::create([
                'project_id' => $project->id,
                'brief_reference' => $briefReference,

                'brief_title' => $validated['brief_title'],

                'version' => $validated['version'] ?? '1.0',

                'revision_number' => $validated['revision_number'] ?? '00',

                'document_date' => $validated['document_date']
                    ?? now()->format('Y-m-d'),

                'prepared_by' => $validated['prepared_by']
                    ?? Auth::id(),

                /*
                |--------------------------------------------------------------------------
                | Business Information
                |--------------------------------------------------------------------------
                */
                'business_need' => $validated['business_need'] ?? null,
                'target_users' => $validated['target_users'] ?? null,
                'target_use' => $validated['target_use'] ?? null,
                'area_schedule' => $validated['area_schedule'] ?? null,
                'room_unit_mix' => $validated['room_unit_mix'] ?? null,

                /*
                |--------------------------------------------------------------------------
                | Requirements
                |--------------------------------------------------------------------------
                */
                'business_requirements' =>
                    $validated['business_requirements'] ?? null,

                'functional_requirements' =>
                    $validated['functional_requirements'] ?? null,

                'operational_requirements' =>
                    $validated['operational_requirements'] ?? null,

                /*
                |--------------------------------------------------------------------------
                | Quality and Design
                |--------------------------------------------------------------------------
                */
                'quality_requirements' =>
                    $validated['quality_requirements'] ?? null,

                'quality_level' =>
                    $validated['quality_level'] ?? null,

                'design_criteria' =>
                    $validated['design_criteria'] ?? null,

                'design_intent' =>
                    $validated['design_intent'] ?? null,

                'technical_requirements' =>
                    $validated['technical_requirements'] ?? null,

                /*
                |--------------------------------------------------------------------------
                | Commercial and Sustainability
                |--------------------------------------------------------------------------
                */
                'commercial_intent' =>
                    $validated['commercial_intent'] ?? null,

                'sustainability_requirements' =>
                    $validated['sustainability_requirements'] ?? null,

                /*
                |--------------------------------------------------------------------------
                | Controls and Dependencies
                |--------------------------------------------------------------------------
                */
                'success_criteria' =>
                    $validated['success_criteria'] ?? null,

                'key_constraints' =>
                    $validated['key_constraints'] ?? null,

                'key_assumptions' =>
                    $validated['key_assumptions'] ?? null,

                'key_dependencies' =>
                    $validated['key_dependencies'] ?? null,

                'required_studies' =>
                    $validated['required_studies'] ?? null,

                'approvals_required' =>
                    $validated['approvals_required'] ?? null,

                /*
                |--------------------------------------------------------------------------
                | Approval
                |--------------------------------------------------------------------------
                */
                'approval_status' =>
                    $validated['approval_status'] ?? 'Draft',

                'approval_remarks' =>
                    $validated['approval_remarks'] ?? null,

                'revision_history' =>
                    $validated['revision_history'] ?? null,

                /*
                |--------------------------------------------------------------------------
                | Audit
                |--------------------------------------------------------------------------
                */
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ]);

            DB::commit();

            return redirect()
                ->route(
                    'admin.development-briefs.show',
                    $developmentBrief->id
                )
                ->with(
                    'success',
                    'Development Brief created successfully.'
                );

        } catch (\Throwable $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Unable to create Development Brief: '
                    . $e->getMessage()
                );
        }
    }

    /**
     * Global show
     */
    public function globalShow(
        DevelopmentBrief $developmentBrief
    ) {
        $developmentBrief->load([
            'project',
            'creator',
            'updater',
            'preparer',
        ]);

        return view(
            'projects.development-brief.global-show',
            compact('developmentBrief')
        );
    }

    /**
     * Global edit
     */
    public function globalEdit(
        DevelopmentBrief $developmentBrief
    ) {
        $projects = Project::orderByDesc('id')->get();

        return view(
            'projects.development-brief.global-edit',
            compact(
                'developmentBrief',
                'projects'
            )
        );
    }

    /**
     * Global update
     */
    public function globalUpdate(
        Request $request,
        DevelopmentBrief $developmentBrief
    ) {
        $validated = $this->validateBrief(
            $request,
            true,
            $developmentBrief
        );

        DB::beginTransaction();

        try {
            /*
             * Correct:
             * The third parameter is the validated array.
             */
            $this->updateBriefData(
                $developmentBrief,
                $validated['project_id'],
                $validated
            );

            DB::commit();

            return redirect()
                ->route(
                    'admin.development-briefs.show',
                    $developmentBrief->id
                )
                ->with(
                    'success',
                    'Development Brief updated successfully.'
                );

        } catch (\Throwable $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Unable to update Development Brief: '
                    . $e->getMessage()
                );
        }
    }

    /**
     * Global destroy
     */
    public function globalDestroy(
        DevelopmentBrief $developmentBrief
    ) {
        try {
            $developmentBrief->delete();

            return redirect()
                ->route('admin.development-briefs.index')
                ->with(
                    'success',
                    'Development Brief deleted successfully.'
                );

        } catch (\Throwable $e) {
            return back()->with(
                'error',
                'Unable to delete Development Brief: '
                . $e->getMessage()
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | SHARED PRIVATE METHODS
    |--------------------------------------------------------------------------
    */

    /**
     * Common validation
     */
    private function validateBrief(
        Request $request,
        bool $isGlobal = false,
        ?DevelopmentBrief $developmentBrief = null
    ): array {
        $briefId = $developmentBrief?->id;

        $rules = [
            'brief_reference' => [
                'nullable',
                'string',
                'max:100',
                Rule::unique(
                    'development_briefs',
                    'brief_reference'
                )->ignore($briefId),
            ],

            'brief_title' => [
                'required',
                'string',
                'max:255',
            ],

            'version' => [
                'nullable',
                'string',
                'max:50',
            ],

            'revision_number' => [
                'nullable',
                'string',
                'max:20',
            ],

            'document_date' => [
                'nullable',
                'date',
            ],

            'prepared_by' => [
                'nullable',
                'integer',
            ],

            /*
            |--------------------------------------------------------------------------
            | Business Information
            |--------------------------------------------------------------------------
            */
            'business_need' => [
                'nullable',
                'string',
            ],

            'target_users' => [
                'nullable',
                'string',
            ],

            'target_use' => [
                'nullable',
                'string',
            ],

            'area_schedule' => [
                'nullable',
                'string',
            ],

            'room_unit_mix' => [
                'nullable',
                'string',
            ],

            /*
            |--------------------------------------------------------------------------
            | Requirements
            |--------------------------------------------------------------------------
            */
            'business_requirements' => [
                'nullable',
                'string',
            ],

            'functional_requirements' => [
                'nullable',
                'string',
            ],

            'operational_requirements' => [
                'nullable',
                'string',
            ],

            /*
            |--------------------------------------------------------------------------
            | Quality and Design
            |--------------------------------------------------------------------------
            */
            'quality_requirements' => [
                'nullable',
                'string',
            ],

            'quality_level' => [
                'nullable',
                'string',
                'max:100',
            ],

            'design_criteria' => [
                'nullable',
                'string',
            ],

            'design_intent' => [
                'nullable',
                'string',
            ],

            'technical_requirements' => [
                'nullable',
                'string',
            ],

            /*
            |--------------------------------------------------------------------------
            | Commercial and Sustainability
            |--------------------------------------------------------------------------
            */
            'commercial_intent' => [
                'nullable',
                'string',
            ],

            'sustainability_requirements' => [
                'nullable',
                'string',
            ],

            /*
            |--------------------------------------------------------------------------
            | Controls and Dependencies
            |--------------------------------------------------------------------------
            */
            'success_criteria' => [
                'nullable',
                'string',
            ],

            'key_constraints' => [
                'nullable',
                'string',
            ],

            'key_assumptions' => [
                'nullable',
                'string',
            ],

            'key_dependencies' => [
                'nullable',
                'string',
            ],

            'required_studies' => [
                'nullable',
                'string',
            ],

            'approvals_required' => [
                'nullable',
                'string',
            ],

            /*
            |--------------------------------------------------------------------------
            | Approval
            |--------------------------------------------------------------------------
            */
            'approval_status' => [
                'nullable',
                Rule::in([
                    'Draft',
                    'Under Review',
                    'Revision Required',
                    'Approved',
                    'Rejected',
                    'Archived',
                ]),
            ],

            'approval_remarks' => [
                'nullable',
                'string',
            ],

            'revision_history' => [
                'nullable',
                'string',
            ],
        ];

        if ($isGlobal) {
            $rules['project_id'] = [
                'required',
                'integer',
                'exists:projects,id',
            ];
        }

        return $request->validate($rules);
    }

    /**
     * Common update data
     *
     * The third parameter is an ARRAY, not Request.
     */
    private function updateBriefData(
        DevelopmentBrief $developmentBrief,
        $projectId,
        array $validated
    ) {
        $data = [
            'project_id' => $projectId,

            'brief_reference' =>
                $validated['brief_reference'] ?? null,

            'brief_title' =>
                $validated['brief_title'],

            'version' =>
                $validated['version'] ?? '1.0',

            'revision_number' =>
                $validated['revision_number'] ?? '00',

            'document_date' =>
                $validated['document_date'] ?? null,

            'prepared_by' =>
                $validated['prepared_by']
                ?? $developmentBrief->prepared_by
                ?? Auth::id(),

            /*
            |--------------------------------------------------------------------------
            | Business Information
            |--------------------------------------------------------------------------
            */
            'business_need' =>
                $validated['business_need'] ?? null,

            'target_users' =>
                $validated['target_users'] ?? null,

            'target_use' =>
                $validated['target_use'] ?? null,

            'area_schedule' =>
                $validated['area_schedule'] ?? null,

            'room_unit_mix' =>
                $validated['room_unit_mix'] ?? null,

            /*
            |--------------------------------------------------------------------------
            | Requirements
            |--------------------------------------------------------------------------
            */
            'business_requirements' =>
                $validated['business_requirements'] ?? null,

            'functional_requirements' =>
                $validated['functional_requirements'] ?? null,

            'operational_requirements' =>
                $validated['operational_requirements'] ?? null,

            /*
            |--------------------------------------------------------------------------
            | Quality and Design
            |--------------------------------------------------------------------------
            */
            'quality_requirements' =>
                $validated['quality_requirements'] ?? null,

            'quality_level' =>
                $validated['quality_level'] ?? null,

            'design_criteria' =>
                $validated['design_criteria'] ?? null,

            'design_intent' =>
                $validated['design_intent'] ?? null,

            'technical_requirements' =>
                $validated['technical_requirements'] ?? null,

            /*
            |--------------------------------------------------------------------------
            | Commercial and Sustainability
            |--------------------------------------------------------------------------
            */
            'commercial_intent' =>
                $validated['commercial_intent'] ?? null,

            'sustainability_requirements' =>
                $validated['sustainability_requirements'] ?? null,

            /*
            |--------------------------------------------------------------------------
            | Controls and Dependencies
            |--------------------------------------------------------------------------
            */
            'success_criteria' =>
                $validated['success_criteria'] ?? null,

            'key_constraints' =>
                $validated['key_constraints'] ?? null,

            'key_assumptions' =>
                $validated['key_assumptions'] ?? null,

            'key_dependencies' =>
                $validated['key_dependencies'] ?? null,

            'required_studies' =>
                $validated['required_studies'] ?? null,

            'approvals_required' =>
                $validated['approvals_required'] ?? null,

            /*
            |--------------------------------------------------------------------------
            | Approval
            |--------------------------------------------------------------------------
            */
            'approval_status' =>
                $validated['approval_status'] ?? 'Draft',

            'approval_remarks' =>
                $validated['approval_remarks'] ?? null,

            'revision_history' =>
                $validated['revision_history'] ?? null,

            /*
            |--------------------------------------------------------------------------
            | Audit
            |--------------------------------------------------------------------------
            */
            'updated_by' => Auth::id(),
        ];

        $developmentBrief->update($data);

        return $developmentBrief;
    }

    /**
     * Generate unique brief reference
     *
     * Example:
     * DB-P5-2026-0001
     */
    private function generateBriefReference(
        int $projectId
    ): string {
        $year = now()->format('Y');

        $lastBrief = DevelopmentBrief::where(
            'project_id',
            $projectId
        )
            ->whereYear('created_at', $year)
            ->orderByDesc('id')
            ->first();

        $sequence = 1;

        if (
            $lastBrief &&
            $lastBrief->brief_reference
        ) {
            preg_match(
                '/(\d+)$/',
                $lastBrief->brief_reference,
                $matches
            );

            if (!empty($matches[1])) {
                $sequence = ((int) $matches[1]) + 1;
            }
        }

        return sprintf(
            'DB-P%d-%s-%04d',
            $projectId,
            $year,
            $sequence
        );
    }

    public function documents(
        $projectId,
        DevelopmentBrief $developmentBrief
    ) {
        abort_if(
            $developmentBrief->project_id != $projectId,
            404
        );

        $documents = $developmentBrief
            ->documents()
            ->with('uploader')
            ->latest('id')
            ->paginate(15);

        return view(
            'projects.development-brief.documents',
            compact(
                'projectId',
                'developmentBrief',
                'documents'
            )
        );
    }

    public function storeDocument(
        Request $request,
        $projectId,
        DevelopmentBrief $developmentBrief
    ) {
        abort_if(
            $developmentBrief->project_id != $projectId,
            404
        );

        $validated = $request->validate([
            'document_title' => [
                'required',
                'string',
                'max:255',
            ],

            'document_type' => [
                'nullable',
                'string',
                'max:100',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'document_date' => [
                'nullable',
                'date',
            ],

            'document' => [
                'required',
                'file',
                'mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png',
                'max:10240',
            ],
        ]);

        $file = $request->file('document');

        $originalName = $file->getClientOriginalName();

        $safeName = time() . '_' .
            \Illuminate\Support\Str::slug(
                pathinfo($originalName, PATHINFO_FILENAME)
            ) . '.' .
            $file->getClientOriginalExtension();

        $filePath = $file->storeAs(
            'development-briefs/' . $developmentBrief->id . '/documents',
            $safeName,
            'public'
        );

        \App\Models\DevelopmentBriefDocument::create([
            'development_brief_id' => $developmentBrief->id,

            'document_title' => $validated['document_title'],

            'document_type' => $validated['document_type'] ?? null,

            'description' => $validated['description'] ?? null,

            'document_date' => $validated['document_date'] ?? null,

            'file_name' => $originalName,

            'file_path' => $filePath,

            'file_size' => $file->getSize(),

            'mime_type' => $file->getMimeType(),

            'document_version' => '1.0',

            'uploaded_by' => auth()->id(),

            'uploaded_at' => now(),
        ]);

        return redirect()
            ->back()
            ->with('success', 'Document uploaded successfully.');
    }

    public function downloadDocument(
        $projectId,
        DevelopmentBrief $developmentBrief,
        DevelopmentBriefDocument $document
    ) {
        abort_if(
            $developmentBrief->project_id != $projectId ||
            $document->development_brief_id != $developmentBrief->id,
            404
        );

        abort_unless(
            \Illuminate\Support\Facades\Storage::disk('public')
                ->exists($document->file_path),
            404
        );

        return \Illuminate\Support\Facades\Storage::disk('public')
            ->download(
                $document->file_path,
                $document->file_name
            );
    }

    public function destroyDocument(
        $projectId,
        DevelopmentBrief $developmentBrief,
        DevelopmentBriefDocument $document
    ) {
        abort_if(
            $developmentBrief->project_id != $projectId ||
            $document->development_brief_id != $developmentBrief->id,
            404
        );

        if (
            \Illuminate\Support\Facades\Storage::disk('public')
                ->exists($document->file_path)
        ) {
            \Illuminate\Support\Facades\Storage::disk('public')
                ->delete($document->file_path);
        }

        $document->delete();

        return redirect()
            ->back()
            ->with('success', 'Document deleted successfully.');
    }

    public function comments(
        $projectId,
        DevelopmentBrief $developmentBrief
    ) {
        abort_if(
            $developmentBrief->project_id != $projectId,
            404
        );

        $comments = $developmentBrief
            ->comments()
            ->with(['user', 'resolvedBy'])
            ->latest('id')
            ->paginate(15);

        return view(
            'projects.development-brief.comments',
            compact(
                'projectId',
                'developmentBrief',
                'comments'
            )
        );
    }

    public function storeComment(
        Request $request,
        $projectId,
        DevelopmentBrief $developmentBrief
    ) {
        abort_if(
            $developmentBrief->project_id != $projectId,
            404
        );

        $validated = $request->validate([
            'comment' => [
                'required',
                'string',
            ],

            'comment_type' => [
                'required',
                'in:General,Clarification,Revision Required,Approval',
            ],
        ]);

        DevelopmentBriefComment::create([
            'development_brief_id' => $developmentBrief->id,

            'user_id' => auth()->id(),

            'comment' => $validated['comment'],

            'comment_type' => $validated['comment_type'],

            'status' => 'Open',
        ]);

        return redirect()
            ->back()
            ->with('success', 'Comment added successfully.');
    }

    public function updateCommentStatus(
        Request $request,
        $projectId,
        DevelopmentBrief $developmentBrief,
        DevelopmentBriefComment $comment
    ) {
        abort_if(
            $developmentBrief->project_id != $projectId ||
            $comment->development_brief_id != $developmentBrief->id,
            404
        );

        $validated = $request->validate([
            'status' => [
                'required',
                'in:Open,Resolved,Closed',
            ],
        ]);

        $data = [
            'status' => $validated['status'],
        ];

        if ($validated['status'] === 'Resolved') {
            $data['resolved_by'] = auth()->id();
            $data['resolved_at'] = now();
        } else {
            $data['resolved_by'] = null;
            $data['resolved_at'] = null;
        }

        $comment->update($data);

        return redirect()
            ->back()
            ->with('success', 'Comment status updated successfully.');
    }

    public function destroyComment(
        $projectId,
        DevelopmentBrief $developmentBrief,
        DevelopmentBriefComment $comment
    ) {
        abort_if(
            $developmentBrief->project_id != $projectId ||
            $comment->development_brief_id != $developmentBrief->id,
            404
        );

        $comment->delete();

        return redirect()
            ->back()
            ->with('success', 'Comment deleted successfully.');
    }

    /**
     * Update Development Brief workflow status
     */
    public function workflowAction(
        Request $request,
        Project $project,
        DevelopmentBrief $developmentBrief,
        string $action
    ) {
        abort_if(
            $developmentBrief->project_id != $project->id,
            404
        );

        $allowedActions = [
            'submit',
            'approve',
            'reject',
            'return',
            'resubmit',
            'supersede',
        ];

        abort_unless(
            in_array($action, $allowedActions),
            404
        );

        $currentStatus = $developmentBrief->approval_status ?: 'Draft';

        $newStatus = null;
        $successMessage = null;

        switch ($action) {

            case 'submit':

                abort_unless(
                    $currentStatus === 'Draft',
                    403,
                    'Only Draft briefs can be submitted for review.'
                );

                $newStatus = 'Under Review';

                $successMessage =
                    'Development Brief submitted for review successfully.';

                break;


            case 'approve':

                abort_unless(
                    $currentStatus === 'Under Review',
                    403,
                    'Only briefs under review can be approved.'
                );

                $newStatus = 'Approved';

                $successMessage =
                    'Development Brief approved successfully.';

                break;


            case 'reject':

                abort_unless(
                    $currentStatus === 'Under Review',
                    403,
                    'Only briefs under review can be rejected.'
                );

                $request->validate([
                    'approval_remarks' => 'required|string|max:5000',
                ], [
                    'approval_remarks.required' =>
                        'Please provide rejection remarks.',
                ]);

                $newStatus = 'Rejected';

                $successMessage =
                    'Development Brief rejected successfully.';

                break;


            case 'return':

                abort_unless(
                    $currentStatus === 'Under Review',
                    403,
                    'Only briefs under review can be returned for revision.'
                );

                $request->validate([
                    'approval_remarks' => 'required|string|max:5000',
                ], [
                    'approval_remarks.required' =>
                        'Please provide revision remarks.',
                ]);

                $newStatus = 'Draft';

                $successMessage =
                    'Development Brief returned for revision.';

                break;


            case 'resubmit':

                abort_unless(
                    $currentStatus === 'Rejected',
                    403,
                    'Only rejected briefs can be resubmitted.'
                );

                $newStatus = 'Under Review';

                $successMessage =
                    'Development Brief resubmitted for review.';

                break;


            case 'supersede':

                abort_unless(
                    $currentStatus === 'Approved',
                    403,
                    'Only approved briefs can be superseded.'
                );

                $newStatus = 'Superseded';

                $successMessage =
                    'Development Brief marked as superseded.';

                break;
        }

        DB::transaction(function () use (
            $developmentBrief,
            $currentStatus,
            $newStatus,
            $request,
            $action
        ) {

            $remarks = $request->input('approval_remarks');

            /*
            |--------------------------------------------------------------------------
            | Update Development Brief Status
            |--------------------------------------------------------------------------
            */

            $developmentBrief->approval_status = $newStatus;

            if ($request->filled('approval_remarks')) {
                $developmentBrief->approval_remarks =
                    $request->input('approval_remarks');
            }

            $developmentBrief->save();

            /*
            |--------------------------------------------------------------------------
            | Save Workflow History
            |--------------------------------------------------------------------------
            */

            DevelopmentBriefWorkflowHistory::create([
                'development_brief_id' => $developmentBrief->id,
                'from_status' => $currentStatus,
                'to_status' => $newStatus,
                'action' => ucfirst($action),
                'remarks' => $remarks,
                'performed_by' => Auth::id(),
                'performed_at' => now(),
            ]);

            /*
            |--------------------------------------------------------------------------
            | Automatic Version Snapshot
            |--------------------------------------------------------------------------
            */

            if (in_array($action, ['submit', 'resubmit'])) {

                $changeSummary = $action === 'submit'
                    ? 'Development Brief submitted for review.'
                    : 'Development Brief resubmitted for review.';

                $this->createAutomaticVersion(
                    $developmentBrief,
                    $changeSummary
                );
            }
        });

        return redirect()
            ->route(
                'admin.projects.development-brief.show',
                [
                    'project' => $project->id,
                    'developmentBrief' => $developmentBrief->id,
                ]
            )
            ->with('success', $successMessage);
    }


    /**
     * Display Development Brief version history
     */
    public function versions(
        Project $project,
        DevelopmentBrief $developmentBrief
    ) {
        abort_if(
            $developmentBrief->project_id != $project->id,
            404
        );

        $versions = DevelopmentBriefVersion::with('creator')
            ->where('development_brief_id', $developmentBrief->id)
            ->latest()
            ->paginate(15);

        return view(
            'projects.development-brief.versions',
            compact(
                'project',
                'developmentBrief',
                'versions'
            )
        );
    }

    /**
     * Create a new Development Brief version
     */
    public function storeVersion(
        Request $request,
        Project $project,
        DevelopmentBrief $developmentBrief
    ) {
        abort_if(
            $developmentBrief->project_id != $project->id,
            404
        );

        $validated = $request->validate([
            'version' => [
                'required',
                'string',
                'max:50',
            ],
            'revision_number' => [
                'nullable',
                'string',
                'max:20',
            ],
            'change_summary' => [
                'nullable',
                'string',
                'max:10000',
            ],
            'status' => [
                'nullable',
                'string',
                'max:50',
            ],
        ]);

        $versionExists = DevelopmentBriefVersion::where(
            'development_brief_id',
            $developmentBrief->id
        )
            ->where('version', $validated['version'])
            ->exists();

        if ($versionExists) {
            return back()
                ->withInput()
                ->withErrors([
                    'version' => 'This version already exists for this Development Brief.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Create snapshot
        |--------------------------------------------------------------------------
        */

        $snapshotData = $developmentBrief
            ->fresh()
            ->toArray();

        DevelopmentBriefVersion::create([
            'development_brief_id' => $developmentBrief->id,
            'version' => $validated['version'],
            'revision_number' => $validated['revision_number'] ?? null,
            'change_summary' => $validated['change_summary'] ?? null,
            'snapshot_data' => $snapshotData,
            'status' => $validated['status'] ?? 'Draft',
            'created_by' => Auth::id(),
        ]);

        return redirect()
            ->route(
                'admin.projects.development-brief.versions',
                [
                    'project' => $project->id,
                    'developmentBrief' => $developmentBrief->id,
                ]
            )
            ->with(
                'success',
                'Development Brief version created successfully.'
            );
    }

    /**
     * Display a specific Development Brief version
     */
    public function showVersion(
        Project $project,
        DevelopmentBrief $developmentBrief,
        DevelopmentBriefVersion $version
    ) {
        abort_if(
            $developmentBrief->project_id != $project->id,
            404
        );

        abort_if(
            $version->development_brief_id != $developmentBrief->id,
            404
        );

        $version->load('creator');

        return view(
            'projects.development-brief.version-show',
            compact(
                'project',
                'developmentBrief',
                'version'
            )
        );
    }

    /**
     * Create automatic version snapshot
     */
    private function createAutomaticVersion(
        DevelopmentBrief $developmentBrief,
        ?string $changeSummary = null
    ): DevelopmentBriefVersion {

        $latestVersion = DevelopmentBriefVersion::where(
            'development_brief_id',
            $developmentBrief->id
        )
            ->orderByDesc('id')
            ->first();

        if ($latestVersion && is_numeric($latestVersion->version)) {
            $nextVersion = number_format(
                ((float) $latestVersion->version) + 0.1,
                1,
                '.',
                ''
            );
        } elseif ($developmentBrief->version) {
            $nextVersion = $developmentBrief->version;
        } else {
            $nextVersion = '1.0';
        }

        // Prevent duplicate version values
        while (
            DevelopmentBriefVersion::where('development_brief_id', $developmentBrief->id)
                ->where('version', $nextVersion)
                ->exists()
        ) {
            $nextVersion = number_format(
                ((float) $nextVersion) + 0.1,
                1,
                '.',
                ''
            );
        }

        return DevelopmentBriefVersion::create([
            'development_brief_id' => $developmentBrief->id,
            'version' => $nextVersion,
            'revision_number' => $developmentBrief->revision_number,
            'change_summary' => $changeSummary,
            'snapshot_data' => $developmentBrief->fresh()->toArray(),
            'status' => $developmentBrief->approval_status ?: 'Draft',
            'created_by' => Auth::id(),
        ]);
    }

    /**
     * Display Development Brief workflow history
     */
    public function workflowHistory(
        Project $project,
        DevelopmentBrief $developmentBrief
    ) {
        abort_if(
            $developmentBrief->project_id != $project->id,
            404
        );

        $history = DevelopmentBriefWorkflowHistory::with('performer')
            ->where('development_brief_id', $developmentBrief->id)
            ->latest('performed_at')
            ->paginate(15);

        return view(
            'projects.development-brief.workflow-history',
            compact(
                'project',
                'developmentBrief',
                'history'
            )
        );
    }
}