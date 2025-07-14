<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\SyncProductsJob;
class ScheduleProductSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        SyncProductsJob::dispatch();
        $this->info('Job de sincronização disparado com sucesso!');
    }
}
