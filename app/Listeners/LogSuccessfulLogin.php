<?php

namespace App\Listeners;

use App\Support\ActivityLogger;
use Illuminate\Auth\Events\Login;

class LogSuccessfulLogin
{
    public function handle(Login $event): void
    {
        ActivityLogger::log(
            'login',
            'Authentication',
            'User logged into the system',
            $event->user?->getKey()
        );
    }
}