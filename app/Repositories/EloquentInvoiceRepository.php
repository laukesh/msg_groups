<?php

namespace App\Repositories;

use App\Models\Invoice;

class EloquentInvoiceRepository implements InvoiceRepositoryInterface
{
    /**
     * Get all invoices.
     */
    public function all(array $filters = [])
    {
        $query = Invoice::with([
            'leaseAgreement',
            'tenant',
            'items',
            'generator',
            'creator',
            'updater',
        ]);

        if (!empty($filters['invoice_no'])) {
            $query->where(
                'invoice_no',
                'like',
                '%' . $filters['invoice_no'] . '%'
            );
        }

        if (!empty($filters['tenant_id'])) {
            $query->where('tenant_id', $filters['tenant_id']);
        }

        if (!empty($filters['lease_agreement_id'])) {
            $query->where(
                'lease_agreement_id',
                $filters['lease_agreement_id']
            );
        }

        if (!empty($filters['invoice_type'])) {
            $query->where(
                'invoice_type',
                $filters['invoice_type']
            );
        }

        if (!empty($filters['invoice_status'])) {
            $query->where(
                'invoice_status',
                $filters['invoice_status']
            );
        }

        if (!empty($filters['from_date'])) {
            $query->whereDate(
                'invoice_date',
                '>=',
                $filters['from_date']
            );
        }

        if (!empty($filters['to_date'])) {
            $query->whereDate(
                'invoice_date',
                '<=',
                $filters['to_date']
            );
        }

        return $query
            ->latest('id')
            ->get();
    }

    /**
     * Find invoice by ID.
     */
    public function find(int $id)
    {
        return Invoice::with([
            'leaseAgreement',
            'tenant',
            'items',
            'generator',
            'creator',
            'updater',
        ])->findOrFail($id);
    }

    /**
     * Find invoice by UUID.
     */
    public function findByUuid(string $uuid)
    {
        return Invoice::with([
            'leaseAgreement',
            'tenant',
            'items',
            'generator',
            'creator',
            'updater',
        ])
        ->where('uuid', $uuid)
        ->firstOrFail();
    }

    /**
     * Create invoice.
     */
    public function create(array $data)
    {
        return Invoice::create($data);
    }

    /**
     * Update invoice.
     */
    public function update(int $id, array $data)
    {
        $invoice = Invoice::findOrFail($id);

        $invoice->update($data);

        return $invoice->fresh([
            'leaseAgreement',
            'tenant',
            'items',
        ]);
    }

    /**
     * Delete invoice.
     */
    public function delete(int $id)
    {
        $invoice = Invoice::findOrFail($id);

        return $invoice->delete();
    }

    /**
     * Restore soft deleted invoice.
     */
    public function restore(int $id)
    {
        $invoice = Invoice::withTrashed()->findOrFail($id);

        $invoice->restore();

        return $invoice;
    }

    /**
     * Get invoices by tenant.
     */
    public function getByTenant(int $tenantId)
    {
        return Invoice::with([
            'leaseAgreement',
            'items',
        ])
        ->where('tenant_id', $tenantId)
        ->latest('invoice_date')
        ->get();
    }

    /**
     * Get invoices by lease agreement.
     */
    public function getByLeaseAgreement(int $leaseAgreementId)
    {
        return Invoice::with([
            'tenant',
            'items',
        ])
        ->where(
            'lease_agreement_id',
            $leaseAgreementId
        )
        ->latest('invoice_date')
        ->get();
    }

    /**
     * Get invoices by status.
     */
    public function getByStatus(string $status)
    {
        return Invoice::with([
            'tenant',
            'items',
        ])
        ->where(
            'invoice_status',
            $status
        )
        ->latest('invoice_date')
        ->get();
    }
}