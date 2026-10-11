<?php

namespace App\Repositories;

interface InvoiceRepositoryInterface
{
    public function all(array $filters = []);

    public function find(int $id);

    public function findByUuid(string $uuid);

    public function create(array $data);

    public function update(int $id, array $data);

    public function delete(int $id);

    public function restore(int $id);

    public function getByTenant(int $tenantId);

    public function getByLeaseAgreement(int $leaseAgreementId);

    public function getByStatus(string $status);
}