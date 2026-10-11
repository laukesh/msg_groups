<?php

namespace App\Http\Controllers\Admin\Assets;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Models\Unit;
use App\Repositories\UnitDocumentRepositoryInterface;
use Illuminate\Http\Request;

class UnitDocumentController extends Controller
{
    public function __construct(
        protected UnitDocumentRepositoryInterface $repository
    ) {
    }

    /**
     * Display all unit documents.
     */
    public function index(Request $request)
    {
        $documents = $this->repository->all(
            $request->only([
                'search',
                'unit_id',
            ])
        );

        return view(
            'admin.assets.unit_documents.index',
            compact('documents')
        );
    }

    /**
     * Show create form.
     */
    public function create()
    {
        $units = Unit::orderBy('unit_no')
            ->pluck('unit_no', 'id');

        return view(
            'admin.assets.unit_documents.create',
            compact('units')
        );
    }

    /**
     * Store unit document.
     */
    public function store(Request $request)
{
    $validated = $request->validate([
        'unit_id' => [
            'required',
            'integer',
            'exists:units,id',
        ],

        'document_type' => [
            'required',
            'string',
            'max:100',
        ],

        'document_name' => [
            'required',
            'string',
            'max:255',
        ],

        'document_file' => [
            'nullable',
            'file',
            'mimes:pdf,doc,docx,jpg,jpeg,png',
            'max:10240',
        ],

        'document_number' => [
            'nullable',
            'string',
            'max:100',
        ],

        'document_date' => [
            'nullable',
            'date',
        ],

        'expiry_date' => [
            'nullable',
            'date',
            'after_or_equal:document_date',
        ],

        'remarks' => [
            'nullable',
            'string',
            'max:1000',
        ],
    ]);

    if ($request->hasFile('document_file')) {
        $validated['document_path'] = $request
            ->file('document_file')
            ->store('documents/unit-documents', 'public');
    }

    unset($validated['document_file']);

    $validated['created_by'] = auth()->id();

    $this->repository->create($validated);

    return redirect()
        ->route('admin.assets.unit-documents.index')
        ->with(
            'success',
            'Unit document created successfully.'
        );
}


    /**
     * Display unit document.
     */
    public function show(int $id)
    {
        $document = $this->repository->find($id);

        return view(
            'admin.assets.unit_documents.show',
            compact('document')
        );
    }

    /**
     * Show edit form.
     */
    public function edit(int $id)
    {
        $document = $this->repository->find($id);

        $units = Unit::orderBy('unit_no')
            ->pluck('unit_no', 'id');

        return view(
            'admin.assets.unit_documents.edit',
            compact(
                'document',
                'units'
            )
        );
    }

    /**
     * Update unit document.
     */
   public function update(Request $request, int $id)
{
    $document = $this->repository->find($id);

    $validated = $request->validate([
        'unit_id' => [
            'required',
            'integer',
            'exists:units,id',
        ],

        'document_type' => [
            'required',
            'string',
            'max:100',
        ],

        'document_name' => [
            'required',
            'string',
            'max:255',
        ],

        'document_file' => [
            'nullable',
            'file',
            'mimes:pdf,doc,docx,jpg,jpeg,png',
            'max:10240',
        ],

        'document_number' => [
            'nullable',
            'string',
            'max:100',
        ],

        'document_date' => [
            'nullable',
            'date',
        ],

        'expiry_date' => [
            'nullable',
            'date',
            'after_or_equal:document_date',
        ],

        'remarks' => [
            'nullable',
            'string',
            'max:1000',
        ],
    ]);

    if ($request->hasFile('document_file')) {

        // Delete old file
        if ($document->document_path) {
            Storage::disk('public')->delete(
                $document->document_path
            );
        }

        // Store new file
        $validated['document_path'] = $request
            ->file('document_file')
            ->store('documents/unit-documents', 'public');
    }

    unset($validated['document_file']);

    $validated['updated_by'] = auth()->id();

    $this->repository->update(
        $id,
        $validated
    );

    return redirect()
        ->route('admin.assets.unit-documents.index')
        ->with(
            'success',
            'Unit document updated successfully.'
        );
}

    /**
     * Delete unit document.
     */
    public function destroy(int $id)
{
    $document = $this->repository->find($id);

    if ($document->document_path) {
        Storage::disk('public')->delete(
            $document->document_path
        );
    }

    $this->repository->delete($id);

    return redirect()
        ->route('admin.assets.unit-documents.index')
        ->with(
            'success',
            'Unit document deleted successfully.'
        );
}

}