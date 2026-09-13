<?php

namespace App\Repositories;

use App\Models\Mall;

interface MallRepositoryInterface
{
    public function all(array $filters = []);
    public function find(int $id): ?Mall;
    public function create(array $data): Mall;
    public function update(Mall $mall, array $data): Mall;
    public function delete(Mall $mall): bool;
}
