<?php

namespace App\Observers;

use App\Models\User;
use App\Models\UserStatusAudit;

class UserObserver
{
    public function updated(User $user)
    {
        $changedBy = auth()->id() ?? $user->updated_by ?? null;

        if ($user->isDirty('is_active')) {
            UserStatusAudit::create([
                'user_id' => $user->id,
                'field' => 'is_active',
                'old_value' => (string) $user->getOriginal('is_active'),
                'new_value' => (string) $user->is_active,
                'changed_by' => $changedBy,
            ]);
        }

        if ($user->isDirty('status')) {
            UserStatusAudit::create([
                'user_id' => $user->id,
                'field' => 'status',
                'old_value' => (string) $user->getOriginal('status'),
                'new_value' => (string) $user->status,
                'changed_by' => $changedBy,
            ]);
        }
    }
}
