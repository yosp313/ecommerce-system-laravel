<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Jobs\DailyOrdersReportJob;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

//schedule the daily orders report job to run every day at 8am
Schedule::job(new DailyOrdersReportJob())
    ->dailyAt('08:00')
    ->timezone('GMT+3');
