<?php

namespace App\Repositories;

use App\Models\UnitDocument;

class EloquentUnitDocumentRepository implements UnitDocumentRepositoryInterface
{
    /**
     * Get all unit documents.
     */
    public function all(array $filters = [])
    {
        $query = UnitDocument::with([
            'unit',
            'creator',
            'updater',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if (!empty($filters['search'])) {

            $search = trim($filters['search']);

            $query->where(function ($q) use ($search) {

                $q->where(
                    'document_name',
                    'like',
                    "%{$search}%"
                )

                ->orWhere(
                    'document_type',
                    'like',
                    "%{$search}%"
                )

                ->orWhere(
                    'document_number',
                    'like',
                    "%{$search}%"
                )

                ->orWhere(
                    'remarks',
                    'like',
                    "%{$search}%"
                );
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Unit Filter
        |--------------------------------------------------------------------------
        */

        if (
            isset($filters['unit_id']) &&
            $filters['unit_id'] !== ''
        ) {
            $query->where(
                'unit_id',
                $filters['unit_id']
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        return $query
            ->latest('id')
            ->paginate(15)
            ->withQueryString();
    }

    /**
     * Find unit document.
     */
    public function find(int $id)
    {
        return UnitDocument::with([
            'unit',
            'creator',
            'updater',
        ])->findOrFail($id);
    }

    /**
     * Create unit document.
     */
    public function create(array $data)
    {
        return UnitDocument::create($data);
    }

    /**
     * Update unit document.
     */
    public function update(int $id, array $data)
    {
        $document = UnitDocument::findOrFail($id);

        $document->update($data);

        return $document->fresh([
            'unit',
            'creator',
            'updater',
        ]);
    }

    /**
     * Delete unit document.
     */
    public function delete(int $id)
    {
        $document = UnitDocument::findOrFail($id);

        return $document->delete();
    }
}