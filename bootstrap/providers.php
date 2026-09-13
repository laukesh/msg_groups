<?php

use App\Providers\AppServiceProvider;
use App\Providers\RepositoryServiceProvider;
use App\Providers\ActivityAuditServiceProvider;
return [
    AppServiceProvider::class,
    RepositoryServiceProvider::class,
    ActivityAuditServiceProvider::class,
];