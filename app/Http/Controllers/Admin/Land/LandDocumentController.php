<?php

namespace App\Http\Controllers\Admin\Land;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Land;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LandDocumentController extends Controller
{
    /**
     * Display documents.
     */
    public function index(Land $land)
    {
        $documents = $land->documents()
            ->latest('id')
            ->paginate(15);

        return view(
            'land-acquisition.documents.index',
            compact(
                'land',
                'documents'
            )
        );
    }


    /**
     * Show upload form.
     */
    public function create(Land $land)
    {
        return view(
            'land-acquisition.documents.create',
            compact('land')
        );
    }


    /**
     * Store document.
     */
    public function store(
        Request $request,
        Land $land
    ) {
        $validated = $request->validate([

            'document_type' => [
                'required',
                'string',
                'max:100'
            ],

            'document_number' => [
                'nullable',
                'string',
                'max:100'
            ],

            'title' => [
                'required',
                'string',
                'max:255'
            ],

            'description' => [
                'nullable',
                'string'
            ],

            'document_date' => [
                'nullable',
                'date'
            ],

            'expiry_date' => [
                'nullable',
                'date'
            ],

            'approval_status' => [
                'required',
                'string',
                'max:50'
            ],

            'owner_id' => [
                'nullable',
                'exists:users,id'
            ],

            'remarks' => [
                'nullable',
                'string'
            ],

            'file' => [
                'required',
                'file',
                'max:51200'
            ],

        ]);


        $file = $request->file('file');


        $path = $file->store(
            'documents/land/' . $land->id,
            'public'
        );


        $land->documents()->create([

            'document_type' =>
                $validated['document_type'],

            'document_number' =>
                $validated['document_number'] ?? null,

            'title' =>
                $validated['title'],

            'description' =>
                $validated['description'] ?? null,

            'file_name' =>
                $file->getClientOriginalName(),

            'file_path' =>
                $path,

            'file_extension' =>
                $file->getClientOriginalExtension(),

            'mime_type' =>
                $file->getMimeType(),

            'file_size' =>
                $file->getSize(),

            'version' =>
                '1.0',

            'approval_status' =>
                $validated['approval_status'],

            'document_date' =>
                $validated['document_date'] ?? null,

            'expiry_date' =>
                $validated['expiry_date'] ?? null,

            'owner_id' =>
                $validated['owner_id'] ?? null,

            'is_current' =>
                true,

            'remarks' =>
                $validated['remarks'] ?? null,

            'created_by' =>
                auth()->id(),

        ]);


        return redirect()
            ->route(
                'admin.land.lands.documents.index',
                $land
            )
            ->with(
                'success',
                'Document uploaded successfully.'
            );
    }


    /**
     * Display document.
     */
    public function show(
        Land $land,
        Document $document
    ) {
        $this->validateBelongsToLand(
            $land,
            $document
        );

        return view(
            'land-acquisition.documents.show',
            compact(
                'land',
                'document'
            )
        );
    }


    /**
     * Download document.
     */
    public function download(
        Land $land,
        Document $document
    ) {
        $this->validateBelongsToLand(
            $land,
            $document
        );

        abort_unless(
            Storage::disk('public')
                ->exists($document->file_path),
            404
        );


        return Storage::disk('public')
            ->download(
                $document->file_path,
                $document->file_name
            );
    }


    /**
     * Delete document.
     */
    public function destroy(
        Land $land,
        Document $document
    ) {
        $this->validateBelongsToLand(
            $land,
            $document
        );


        if (
            Storage::disk('public')
                ->exists($document->file_path)
        ) {
            Storage::disk('public')
                ->delete($document->file_path);
        }


        $document->delete();


        return redirect()
            ->route(
                'admin.land.lands.documents.index',
                $land
            )
            ->with(
                'success',
                'Document deleted successfully.'
            );
    }


    /**
     * Validate document belongs to land.
     */
    private function validateBelongsToLand(
        Land $land,
        Document $document
    ): void {

        abort_unless(
            $document->documentable_type === Land::class &&
            (int) $document->documentable_id === (int) $land->id,
            404
        );
    }
}