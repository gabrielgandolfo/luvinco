<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Jobs\SyncProductsJob;

Schedule::job(new SyncProductsJob)->dailyAt('00:05');
Schedule::job(new SyncProductsJob)->dailyAt('08:00');
Schedule::job(new SyncProductsJob)->dailyAt('16:00');
