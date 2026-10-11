<?php

namespace App\Http\Controllers\Admin\Commissioning;

use App\Http\Controllers\Controller;
use App\Models\CommissioningCertificate;
use App\Models\CommissioningDocument;
use App\Models\CommissioningScope;
use App\Models\CommissioningTest;
use App\Models\CommissioningTestPlan;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CommissioningDocumentController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Document List
    |--------------------------------------------------------------------------
    */
    public function index(
        Request $request,
        Project $project
    ): View {
        $query = CommissioningDocument::with([
            'scope',
            'testPlan',
            'test',
            'certificate',
            'uploadedBy',
        ])
            ->where('project_id', $project->id);

        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->where('document_name', 'like', "%{$search}%")
                    ->orWhere('file_name', 'like', "%{$search}%")
                    ->orWhere('document_type', 'like', "%{$search}%")
                    ->orWhere('document_reference', 'like', "%{$search}%");
            });
        }

        if ($request->filled('document_type')) {
            $query->where(
                'document_type',
                $request->document_type
            );
        }

        if ($request->filled('status')) {
            $query->where(
                'status',
                $request->status
            );
        }

        $documents = $query
            ->latest('id')
            ->paginate(20)
            ->withQueryString();

        $documentTypes = [
            'Test Procedure',
            'Test Report',
            'Inspection Report',
            'Calibration Certificate',
            'Manufacturer Certificate',
            'Compliance Certificate',
            'Photographic Evidence',
            'Video Evidence',
            'Signed Test Sheet',
            'Commissioning Certificate',
            'As-Built Document',
            'O&M Document',
            'Warranty Document',
            'Authority Approval',
            'Other',
        ];

        return view(
            'admin.commissioning.documents.index',
            compact(
                'project',
                'documents',
                'documentTypes'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Upload Form
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

        $testPlans = CommissioningTestPlan::where(
            'project_id',
            $project->id
        )
            ->orderBy('test_plan_no')
            ->get();

        $tests = CommissioningTest::where(
            'project_id',
            $project->id
        )
            ->latest('id')
            ->get();

        $certificates = CommissioningCertificate::where(
            'project_id',
            $project->id
        )
            ->latest('id')
            ->get();

        $documentTypes = [
            'Test Procedure',
            'Test Report',
            'Inspection Report',
            'Calibration Certificate',
            'Manufacturer Certificate',
            'Compliance Certificate',
            'Photographic Evidence',
            'Video Evidence',
            'Signed Test Sheet',
            'Commissioning Certificate',
            'As-Built Document',
            'O&M Document',
            'Warranty Document',
            'Authority Approval',
            'Other',
        ];

        return view(
            'admin.commissioning.documents.create',
            compact(
                'project',
                'scopes',
                'testPlans',
                'tests',
                'certificates',
                'documentTypes'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Upload
    |--------------------------------------------------------------------------
    */
    public function store(
        Request $request,
        Project $project
    ): RedirectResponse {

        $data = $request->validate([

            'commissioning_scope_id' =>
                'nullable|integer|exists:commissioning_scopes,id',

            'test_plan_id' =>
                'nullable|integer|exists:commissioning_test_plans,id',

            'test_id' =>
                'nullable|integer|exists:commissioning_tests,id',

            'certificate_id' =>
                'nullable|integer|exists:commissioning_certificates,id',

            'document_type' =>
                'required|string|max:100',

            'document_name' =>
                'required|string|max:255',

            'document_version' =>
                'nullable|string|max:50',

            'description' =>
                'nullable|string',

            'status' =>
                'required|in:Draft,Submitted,Approved,Rejected,Archived',

            'file' =>
                'required|file|max:51200',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Validate relationships belong to project
        |--------------------------------------------------------------------------
        */
        $this->validateRelations(
            $project,
            $data
        );

        /*
        |--------------------------------------------------------------------------
        | Upload
        |--------------------------------------------------------------------------
        */
        $file = $request->file('file');

        $directory = 'commissioning/documents/' . $project->id;

        $filePath = $file->store(
            $directory,
            'public'
        );

        $data['project_id'] = $project->id;

        $data['file_name'] = $file->getClientOriginalName();

        $data['file_path'] = $filePath;

        $data['mime_type'] = $file->getMimeType();

        $data['file_size'] = $file->getSize();

        $data['uploaded_by'] = auth()->id();

        /*
        |--------------------------------------------------------------------------
        | Remove upload-only field
        |--------------------------------------------------------------------------
        */
        unset($data['file']);

        CommissioningDocument::create($data);

        return redirect()
            ->route(
                'admin.projects.commissioning.documents.index',
                $project
            )
            ->with(
                'success',
                'Commissioning document uploaded successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Download
    |--------------------------------------------------------------------------
    */
    public function download(
        Project $project,
        CommissioningDocument $document
    ) {
        $this->check(
            $project,
            $document
        );

        abort_unless(
            Storage::disk('public')->exists($document->file_path),
            404,
            'Document file not found.'
        );

        return Storage::disk('public')->download(
            $document->file_path,
            $document->file_name
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Delete
    |--------------------------------------------------------------------------
    */
    public function destroy(
        Project $project,
        CommissioningDocument $document
    ): RedirectResponse {

        $this->check(
            $project,
            $document
        );

        if (
            $document->file_path &&
            Storage::disk('public')->exists(
                $document->file_path
            )
        ) {
            Storage::disk('public')->delete(
                $document->file_path
            );
        }

        $document->delete();

        return back()->with(
            'success',
            'Commissioning document deleted successfully.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Validate Related Records
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
                'Selected scope does not belong to this project.'
            );
        }

        if (!empty($data['test_plan_id'])) {

            abort_unless(
                CommissioningTestPlan::whereKey(
                    $data['test_plan_id']
                )
                    ->where(
                        'project_id',
                        $project->id
                    )
                    ->exists(),
                422,
                'Selected test plan does not belong to this project.'
            );
        }

        if (!empty($data['test_id'])) {

            abort_unless(
                CommissioningTest::whereKey(
                    $data['test_id']
                )
                    ->where(
                        'project_id',
                        $project->id
                    )
                    ->exists(),
                422,
                'Selected test does not belong to this project.'
            );
        }

        if (!empty($data['certificate_id'])) {

            abort_unless(
                CommissioningCertificate::whereKey(
                    $data['certificate_id']
                )
                    ->where(
                        'project_id',
                        $project->id
                    )
                    ->exists(),
                422,
                'Selected certificate does not belong to this project.'
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Project Check
    |--------------------------------------------------------------------------
    */
    private function check(
        Project $project,
        CommissioningDocument $document
    ): void {

        abort_unless(
            (int) $document->project_id ===
            (int) $project->id,
            404
        );
    }
}