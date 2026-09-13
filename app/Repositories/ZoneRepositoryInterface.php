<?php

namespace App\Repositories;

use App\Models\Zone;

interface ZoneRepositoryInterface
{
    public function all(array $filters = []);

    public function find($id);

    public function create(array $data);

    public function update(Zone $zone, array $data);

    public function delete(Zone $zone);
}