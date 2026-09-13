<?php

namespace App\Repositories;

interface InvoiceItemRepositoryInterface
{
    public function all(array $filters = []);

    public function find(int $id);

    public function create(array $data);

    public function update(int $id, array $data);

    public function delete(int $id);

    public function getByInvoice(int $invoiceId);

    public function getByChargeType(int $chargeTypeId);
}