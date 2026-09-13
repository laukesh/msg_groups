<?php

namespace App\Listeners;

use App\Support\ActivityLogger;
use Illuminate\Auth\Events\Logout;

class LogSuccessfulLogout
{
    public function handle(Logout $event): void
    {
        ActivityLogger::log(
            'logout',
            'Authentication',
            'User logged out successfully',
            $event->user?->getKey()
        );
    }
}