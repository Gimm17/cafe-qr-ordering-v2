<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule cleanup for pending orders older than 10 minutes
// Run every minute
Schedule::command('orders:cleanup-pending --minutes=10')->everyFiveMinutes();
