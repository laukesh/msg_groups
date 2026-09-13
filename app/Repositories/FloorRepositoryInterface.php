<?php

namespace App\Repositories;

use App\Models\Floor;

interface FloorRepositoryInterface
{
    public function all(array $filters = []);

    public function find($id);

    public function create(array $data);

    public function update(Floor $floor, array $data);

    public function delete(Floor $floor);
}