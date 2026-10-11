<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Hashing\HashManager;

class EloquentUserRepository implements UserRepositoryInterface
{
    public function create(array $data): User
    {
        $data['password'] = app(HashManager::class)->make($data['password']);
        return User::create($data);
    }

    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function findById(int $id): ?User
    {
        return User::find($id);
    }

    public function update(User $user, array $data): User
    {
        if (! empty($data['password'])) {
            $data['password'] = app(HashManager::class)->make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->fill($data);
        $user->save();
        return $user;
    }

    public function allStatuses(): array
    {
        return User::select('id','name','email','is_active','status')->get()->toArray();
    }
}
