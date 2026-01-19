<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Clean up old backups daily at 1:00 AM
Schedule::command('backup:clean')->daily()->at('01:00');

// Run a new backup daily at 1:30 AM
Schedule::command('backup:run')->daily()->at('01:30');