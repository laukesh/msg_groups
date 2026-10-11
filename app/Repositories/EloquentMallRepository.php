<?php

namespace App\Repositories;

use App\Models\Mall;

class EloquentMallRepository implements MallRepositoryInterface
{
    public function all(array $filters = [])
    {
        $query = Mall::query();

        if (! empty($filters['search'])) {
            $s = '%'.$filters['search'].'%';
            $query->where('mall_name', 'like', $s)
                  ->orWhere('mall_code', 'like', $s)
                  ->orWhere('city', 'like', $s);
        }

        return $query->orderBy('id', 'desc')->paginate(20);
    }

    public function find(int $id): ?Mall
    {
        return Mall::find($id);
    }

    public function create(array $data): Mall
    {
        return Mall::create($data);
    }

    public function update(Mall $mall, array $data): Mall
    {
        $mall->fill($data);
        $mall->save();
        return $mall;
    }

    public function delete(Mall $mall): bool
    {
        return (bool) $mall->delete();
    }
}
