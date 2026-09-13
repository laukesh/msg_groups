<?php

namespace App\Repositories;

interface AssetRepositoryInterface
{
    public function all(array $filters = []);

    public function paginate(array $filters = [], int $perPage = 15);

    public function find(int $id);

    public function create(array $data);

    public function update(int $id, array $data);

    public function delete(int $id): bool;
    public function getEconomicSummary(int $id);
}