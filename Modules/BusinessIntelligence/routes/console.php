<?php

use App\Jobs\GenerateDashboardInsight;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('sync:finance')->everyMinute()->withoutOverlapping()->runInBackground();
Schedule::command('sync:inventory')->everyMinute()->withoutOverlapping()->runInBackground();
Schedule::command('sync:manufacturing')->everyMinute()->withoutOverlapping()->runInBackground();
Schedule::command('sync:procurement')->everyMinute()->withoutOverlapping()->runInBackground();
Schedule::command('sync:fulfillment')->everyMinute()->withoutOverlapping()->runInBackground();
Schedule::command('sync:ecommerce')->everyMinute()->withoutOverlapping()->runInBackground();
Schedule::command('cache:warm-dashboard')->everyMinute()->withoutOverlapping()->runInBackground();