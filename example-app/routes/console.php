<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Modules\Reporting\Services\DashboardCacheService;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('reports:refresh-dashboard-cache', function (DashboardCacheService $service) {
    $service->refresh();

    $this->info('Dashboard cache refreshed successfully.');
})->purpose('Recompute the dashboard reporting cache');
