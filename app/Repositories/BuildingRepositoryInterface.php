<?php

namespace App\Repositories;

use App\Models\Building;

interface BuildingRepositoryInterface
{
    public function all(array $filters = []);

    public function find($id);

    public function create(array $data);

    public function update(
        Building $building,
        array $data
    );

    public function delete(Building $building);
}