<?php

namespace App\Repositories;

interface AssetIncomeRepositoryInterface
{
    public function paginateByAsset(
        int $assetId,
        array $filters = [],
        int $perPage = 15
    );

    public function find(int $id);

    public function create(array $data);

    public function update(int $id, array $data);

    public function delete(int $id);
}