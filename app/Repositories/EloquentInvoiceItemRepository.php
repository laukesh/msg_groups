<?php

namespace App\Repositories;

use App\Models\InvoiceItem;

class EloquentInvoiceItemRepository implements InvoiceItemRepositoryInterface
{
    /**
     * Get all invoice items.
     */
    public function all(array $filters = [])
    {
        $query = InvoiceItem::with([
            'invoice',
            'chargeType',
            'creator',
            'updater',
        ]);

        if (!empty($filters['invoice_id'])) {
            $query->where(
                'invoice_id',
                $filters['invoice_id']
            );
        }

        if (!empty($filters['charge_type_id'])) {
            $query->where(
                'charge_type_id',
                $filters['charge_type_id']
            );
        }

        return $query
            ->latest('id')
            ->get();
    }

    /**
     * Find invoice item.
     */
    public function find(int $id)
    {
        return InvoiceItem::with([
            'invoice',
            'chargeType',
            'creator',
            'updater',
        ])->findOrFail($id);
    }

    /**
     * Create invoice item.
     */
    public function create(array $data)
    {
        return InvoiceItem::create($data);
    }

    /**
     * Update invoice item.
     */
    public function update(int $id, array $data)
    {
        $item = InvoiceItem::findOrFail($id);

        $item->update($data);

        return $item->fresh([
            'invoice',
            'chargeType',
        ]);
    }

    /**
     * Delete invoice item.
     */
    public function delete(int $id)
    {
        $item = InvoiceItem::findOrFail($id);

        return $item->delete();
    }

    /**
     * Get items by invoice.
     */
    public function getByInvoice(int $invoiceId)
    {
        return InvoiceItem::with([
            'chargeType',
            'creator',
            'updater',
        ])
        ->where('invoice_id', $invoiceId)
        ->latest('id')
        ->get();
    }

    /**
     * Get items by charge type.
     */
    public function getByChargeType(int $chargeTypeId)
    {
        return InvoiceItem::with([
            'invoice',
        ])
        ->where(
            'charge_type_id',
            $chargeTypeId
        )
        ->latest('id')
        ->get();
    }
}