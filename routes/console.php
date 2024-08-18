<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

//schedule the daily admmin report to run every day at 8am
Schedule::command('app:daily-orders-report')
    ->dailyAt('08:00')
    ->timezone('GMT+3');
