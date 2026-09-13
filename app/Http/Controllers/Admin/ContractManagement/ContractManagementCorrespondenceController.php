<?php

namespace App\Http\Controllers\Admin\ContractManagement;

use App\Http\Controllers\Controller;
use App\Models\ContractManagementContract;
use App\Models\ContractManagementCorrespondence;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ContractManagementCorrespondenceController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Index
    |--------------------------------------------------------------------------
    */

    public function index(
        Project $project,
        ContractManagementContract $contract
    ): View {

        $this->validateContract(
            $project,
            $contract
        );


        $correspondence =
            ContractManagementCorrespondence::query()
                ->where(
                    'contract_management_contract_id',
                    $contract->id
                )
                ->with([
                    'relatedCorrespondence',
                    'creator',
                    'updater',
                ])
                ->orderByDesc(
                    'correspondence_date'
                )
                ->orderByDesc('id')
                ->get();


        /*
        |--------------------------------------------------------------------------
        | Summary
        |--------------------------------------------------------------------------
        */

        $summary = [

            'total' =>
                $correspondence->count(),

            'incoming' =>
                $correspondence
                    ->where(
                        'direction',
                        'Incoming'
                    )
                    ->count(),

            'outgoing' =>
                $correspondence
                    ->where(
                        'direction',
                        'Outgoing'
                    )
                    ->count(),

            'open' =>
                $correspondence
                    ->where(
                        'status',
                        'Open'
                    )
                    ->count(),

            'pending_response' =>
                $correspondence
                    ->where(
                        'status',
                        'Pending Response'
                    )
                    ->count(),

            'overdue' =>
                $correspondence
                    ->filter(
                        function ($item) {

                            return $item
                                ->isResponseOverdue();

                        }
                    )
                    ->count(),

            'closed' =>
                $correspondence
                    ->where(
                        'status',
                        'Closed'
                    )
                    ->count(),
        ];


        /*
        |--------------------------------------------------------------------------
        | Communication Types
        |--------------------------------------------------------------------------
        */

        $typeSummary =
            $correspondence
                ->groupBy(
                    'communication_type'
                )
                ->map(
                    function ($items) {

                        return $items->count();

                    }
                )
                ->sortDesc();


        return view(
            'contract-management.correspondence.index',
            compact(
                'project',
                'contract',
                'correspondence',
                'summary',
                'typeSummary'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Create
    |--------------------------------------------------------------------------
    */

    public function create(
        Project $project,
        ContractManagementContract $contract
    ): View {

        $this->validateContract(
            $project,
            $contract
        );


        $previousCorrespondence =
            ContractManagementCorrespondence::query()
                ->where(
                    'contract_management_contract_id',
                    $contract->id
                )
                ->orderByDesc(
                    'correspondence_date'
                )
                ->orderByDesc('id')
                ->get();


        return view(
            'contract-management.correspondence.create',
            compact(
                'project',
                'contract',
                'previousCorrespondence'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Store
    |--------------------------------------------------------------------------
    */

    public function store(
        Request $request,
        Project $project,
        ContractManagementContract $contract
    ): RedirectResponse {

        $this->validateContract(
            $project,
            $contract
        );


        $validated = $request->validate([

            'correspondence_date' =>
                'required|date',

            'direction' =>
                'required|in:Incoming,Outgoing',

            'communication_type' =>
                'required|string|max:100',

            'subject' =>
                'required|string|max:255',

            'from_party' =>
                'nullable|string|max:255',

            'to_party' =>
                'nullable|string|max:255',

            'cc_party' =>
                'nullable|string',

            'reference_number' =>
                'nullable|string|max:100',

            'related_correspondence_id' =>
                'nullable|integer',

            'response_required' =>
                'nullable|boolean',

            'response_due_date' =>
                'nullable|date',

            'response_date' =>
                'nullable|date',

            'priority' =>
                'required|in:Low,Normal,High,Urgent',

            'status' =>
                'required|in:Open,Responded,Closed,Pending Response,For Information,Archived',

            'description' =>
                'nullable|string',

            'remarks' =>
                'nullable|string',

            'correspondence_file' =>
                'nullable|file|max:51200|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Response Required
        |--------------------------------------------------------------------------
        */

        $validated['response_required'] =
            $request->boolean(
                'response_required'
            );


        /*
        |--------------------------------------------------------------------------
        | Generate Number
        |--------------------------------------------------------------------------
        */

        $validated['correspondence_number'] =
            $this->generateCorrespondenceNumber();


        /*
        |--------------------------------------------------------------------------
        | Project / Contract
        |--------------------------------------------------------------------------
        */

        $validated['project_id'] =
            $project->id;


        $validated[
            'contract_management_contract_id'
        ] =
            $contract->id;


        /*
        |--------------------------------------------------------------------------
        | Validate Related Correspondence
        |--------------------------------------------------------------------------
        */

        if (
            !empty(
                $validated[
                    'related_correspondence_id'
                ]
            )
        ) {

            $related =
                ContractManagementCorrespondence::query()
                    ->where(
                        'id',
                        $validated[
                            'related_correspondence_id'
                        ]
                    )
                    ->where(
                        'contract_management_contract_id',
                        $contract->id
                    )
                    ->exists();


            if (!$related) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'related_correspondence_id' =>
                            'Selected correspondence does not belong to this contract.',
                    ]);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Upload File
        |--------------------------------------------------------------------------
        */

        if (
            $request->hasFile(
                'correspondence_file'
            )
        ) {

            $file =
                $request->file(
                    'correspondence_file'
                );


            $directory =
                'contract-correspondence/' .
                $project->id .
                '/' .
                $contract->id;


            $filePath =
                $file->store(
                    $directory,
                    'public'
                );


            $validated['file_name'] =
                $file->getClientOriginalName();


            $validated['file_path'] =
                $filePath;


            $validated['file_size'] =
                $file->getSize();


            $validated['mime_type'] =
                $file->getClientMimeType();
        }


        /*
        |--------------------------------------------------------------------------
        | Audit
        |--------------------------------------------------------------------------
        */

        $validated['created_by'] =
            Auth::id();

        $validated['updated_by'] =
            Auth::id();


        ContractManagementCorrespondence::create(
            $validated
        );


        return redirect()
            ->route(
                'admin.projects.contract-management.contracts.correspondence.index',
                [
                    $project,
                    $contract,
                ]
            )
            ->with(
                'success',
                'Contract correspondence added successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Edit
    |--------------------------------------------------------------------------
    */

    public function edit(
        Project $project,
        ContractManagementContract $contract,
        ContractManagementCorrespondence $correspondence
    ): View {

        $this->validateContract(
            $project,
            $contract
        );


        $this->validateCorrespondence(
            $contract,
            $correspondence
        );


        $previousCorrespondence =
            ContractManagementCorrespondence::query()
                ->where(
                    'contract_management_contract_id',
                    $contract->id
                )
                ->where(
                    'id',
                    '!=',
                    $correspondence->id
                )
                ->orderByDesc(
                    'correspondence_date'
                )
                ->orderByDesc('id')
                ->get();


        return view(
            'contract-management.correspondence.edit',
            compact(
                'project',
                'contract',
                'correspondence',
                'previousCorrespondence'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Update
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        Project $project,
        ContractManagementContract $contract,
        ContractManagementCorrespondence $correspondence
    ): RedirectResponse {

        $this->validateContract(
            $project,
            $contract
        );


        $this->validateCorrespondence(
            $contract,
            $correspondence
        );


        $validated = $request->validate([

            'correspondence_date' =>
                'required|date',

            'direction' =>
                'required|in:Incoming,Outgoing',

            'communication_type' =>
                'required|string|max:100',

            'subject' =>
                'required|string|max:255',

            'from_party' =>
                'nullable|string|max:255',

            'to_party' =>
                'nullable|string|max:255',

            'cc_party' =>
                'nullable|string',

            'reference_number' =>
                'nullable|string|max:100',

            'related_correspondence_id' =>
                'nullable|integer',

            'response_required' =>
                'nullable|boolean',

            'response_due_date' =>
                'nullable|date',

            'response_date' =>
                'nullable|date',

            'priority' =>
                'required|in:Low,Normal,High,Urgent',

            'status' =>
                'required|in:Open,Responded,Closed,Pending Response,For Information,Archived',

            'description' =>
                'nullable|string',

            'remarks' =>
                'nullable|string',

            'correspondence_file' =>
                'nullable|file|max:51200|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png',
        ]);


        $validated['response_required'] =
            $request->boolean(
                'response_required'
            );


        /*
        |--------------------------------------------------------------------------
        | Prevent Self Reference
        |--------------------------------------------------------------------------
        */

        if (
            !empty(
                $validated[
                    'related_correspondence_id'
                ]
            )
            &&
            (int)
            $validated[
                'related_correspondence_id'
            ]
            ===
            (int)
            $correspondence->id
        ) {

            return back()
                ->withInput()
                ->withErrors([
                    'related_correspondence_id' =>
                        'A correspondence cannot be related to itself.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Validate Related
        |--------------------------------------------------------------------------
        */

        if (
            !empty(
                $validated[
                    'related_correspondence_id'
                ]
            )
        ) {

            $related =
                ContractManagementCorrespondence::query()
                    ->where(
                        'id',
                        $validated[
                            'related_correspondence_id'
                        ]
                    )
                    ->where(
                        'contract_management_contract_id',
                        $contract->id
                    )
                    ->exists();


            if (!$related) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'related_correspondence_id' =>
                            'Selected correspondence does not belong to this contract.',
                    ]);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Replace File
        |--------------------------------------------------------------------------
        */

        if (
            $request->hasFile(
                'correspondence_file'
            )
        ) {

            if (
                $correspondence->file_path
                &&
                Storage::disk('public')
                    ->exists(
                        $correspondence->file_path
                    )
            ) {

                Storage::disk('public')
                    ->delete(
                        $correspondence->file_path
                    );
            }


            $file =
                $request->file(
                    'correspondence_file'
                );


            $directory =
                'contract-correspondence/' .
                $project->id .
                '/' .
                $contract->id;


            $filePath =
                $file->store(
                    $directory,
                    'public'
                );


            $validated['file_name'] =
                $file->getClientOriginalName();


            $validated['file_path'] =
                $filePath;


            $validated['file_size'] =
                $file->getSize();


            $validated['mime_type'] =
                $file->getClientMimeType();
        }


        $validated['updated_by'] =
            Auth::id();


        $correspondence->update(
            $validated
        );


        return redirect()
            ->route(
                'admin.projects.contract-management.contracts.correspondence.index',
                [
                    $project,
                    $contract,
                ]
            )
            ->with(
                'success',
                'Contract correspondence updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Download
    |--------------------------------------------------------------------------
    */

    public function download(
        Project $project,
        ContractManagementContract $contract,
        ContractManagementCorrespondence $correspondence
    ) {

        $this->validateContract(
            $project,
            $contract
        );


        $this->validateCorrespondence(
            $contract,
            $correspondence
        );


        if (
            !$correspondence->file_path
            ||
            !Storage::disk('public')
                ->exists(
                    $correspondence->file_path
                )
        ) {

            abort(
                404,
                'Correspondence file not found.'
            );
        }


        return Storage::disk('public')
            ->download(
                $correspondence->file_path,
                $correspondence->file_name
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Destroy
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Project $project,
        ContractManagementContract $contract,
        ContractManagementCorrespondence $correspondence
    ): RedirectResponse {

        $this->validateContract(
            $project,
            $contract
        );


        $this->validateCorrespondence(
            $contract,
            $correspondence
        );


        if (
            $correspondence->file_path
            &&
            Storage::disk('public')
                ->exists(
                    $correspondence->file_path
                )
        ) {

            Storage::disk('public')
                ->delete(
                    $correspondence->file_path
                );
        }


        $correspondence->delete();


        return redirect()
            ->route(
                'admin.projects.contract-management.contracts.correspondence.index',
                [
                    $project,
                    $contract,
                ]
            )
            ->with(
                'success',
                'Contract correspondence deleted successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Generate Number
    |--------------------------------------------------------------------------
    */

    protected function generateCorrespondenceNumber(): string
    {
        $lastId =
            ContractManagementCorrespondence::max('id')
            ??
            0;


        return 'COR-' .
            str_pad(
                $lastId + 1,
                6,
                '0',
                STR_PAD_LEFT
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Validate Contract
    |--------------------------------------------------------------------------
    */

    protected function validateContract(
        Project $project,
        ContractManagementContract $contract
    ): void {

        if (
            (int) $contract->project_id
            !==
            (int) $project->id
        ) {

            abort(
                404,
                'Contract does not belong to this project.'
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Validate Correspondence
    |--------------------------------------------------------------------------
    */

    protected function validateCorrespondence(
        ContractManagementContract $contract,
        ContractManagementCorrespondence $correspondence
    ): void {

        if (
            (int)
            $correspondence
                ->contract_management_contract_id
            !==
            (int) $contract->id
        ) {

            abort(
                404,
                'Correspondence does not belong to this contract.'
            );
        }
    }
}