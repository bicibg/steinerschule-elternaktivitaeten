<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule the command to run every hour
Schedule::command('app:update-expired-items')->hourly();

// Anonymize users whose 30-day deletion grace period has expired
Schedule::command('app:anonymize-expired-users')->daily();
