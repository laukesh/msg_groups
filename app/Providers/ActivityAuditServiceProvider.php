<?php

namespace App\Providers;

use App\Models\User;
use App\Models\UserStatusAudit;
use App\Support\ActivityLogger;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class ActivityAuditServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        /*
        |--------------------------------------------------------------------------
        | CREATED
        |--------------------------------------------------------------------------
        */

        Event::listen('eloquent.created: *', function ($event, $models) {

            $model = $models[0] ?? null;

            if ($model instanceof Model) {

                ActivityLogger::model(
                    $model,
                    'created',
                    null,
                    $model->getAttributes()
                );
            }
        });

        /*
        |--------------------------------------------------------------------------
        | UPDATED
        |--------------------------------------------------------------------------
        */

        Event::listen('eloquent.updated: *', function ($event, $models) {

            $model = $models[0] ?? null;

            if (!$model instanceof Model) {
                return;
            }

            $changes = $model->getChanges();

            if (empty($changes)) {
                return;
            }

            /*
            |--------------------------------------------------------------------------
            | Old values
            |--------------------------------------------------------------------------
            */

            $oldValues = [];

            foreach (array_keys($changes) as $field) {

                $oldValues[$field] = $model->getOriginal($field);
            }

            /*
            |--------------------------------------------------------------------------
            | New values
            |--------------------------------------------------------------------------
            */

            $newValues = [];

            foreach (array_keys($changes) as $field) {

                $newValues[$field] = $model->getAttribute($field);
            }

            ActivityLogger::model(
                $model,
                'updated',
                $oldValues,
                $newValues
            );

            /*
            |--------------------------------------------------------------------------
            | User status audit
            |--------------------------------------------------------------------------
            */

            if ($model instanceof User) {

                foreach ([
                    'is_active',
                    'status',
                ] as $field) {

                    if (array_key_exists($field, $changes)) {

                        try {

                            UserStatusAudit::create([
                                'user_id' => $model->getKey(),

                                'field' => $field,

                                'old_value' => $model->getOriginal($field),

                                'new_value' => $model->getAttribute($field),

                                'changed_by' => auth()->id(),

                            ]);

                        } catch (\Throwable $e) {

                            report($e);
                        }
                    }
                }
            }
        });

        /*
        |--------------------------------------------------------------------------
        | DELETED
        |--------------------------------------------------------------------------
        */

        Event::listen('eloquent.deleted: *', function ($event, $models) {

            $model = $models[0] ?? null;

            if ($model instanceof Model) {

                ActivityLogger::model(
                    $model,
                    'deleted',
                    $model->getOriginal(),
                    null
                );
            }
        });

        /*
        |--------------------------------------------------------------------------
        | RESTORED
        |--------------------------------------------------------------------------
        */

        Event::listen('eloquent.restored: *', function ($event, $models) {

            $model = $models[0] ?? null;

            if ($model instanceof Model) {

                ActivityLogger::model(
                    $model,
                    'restored',
                    null,
                    $model->getAttributes()
                );
            }
        });

        /*
        |--------------------------------------------------------------------------
        | LOGIN
        |--------------------------------------------------------------------------
        */

        Event::listen(Login::class, function (Login $event) {

            ActivityLogger::custom(
                'login',
                'Authentication',
                'User logged into the system',
                $event->user?->getKey()
            );
        });

        /*
        |--------------------------------------------------------------------------
        | LOGOUT
        |--------------------------------------------------------------------------
        */

        Event::listen(Logout::class, function (Logout $event) {

            ActivityLogger::custom(
                'logout',
                'Authentication',
                'User logged out of the system',
                $event->user?->getKey()
            );
        });

        /*
        |--------------------------------------------------------------------------
        | FAILED LOGIN
        |--------------------------------------------------------------------------
        */

        Event::listen(Failed::class, function (Failed $event) {

            ActivityLogger::custom(
                'failed_login',
                'Authentication',
                'Failed login attempt',
                null,
                null,
                [
                    'credentials' => [
                        'username' => $event->credentials['email']
                            ?? $event->credentials['username']
                            ?? null,
                    ],
                ]
            );
        });
    }
}