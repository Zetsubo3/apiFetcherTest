<?php

namespace App\Console\Commands;

use App\Jobs\DataFetchJob;
use Illuminate\Console\Command;

class FetchApiDataCommand extends Command
{
    protected $signature = 'api:data-fetch';
    protected $description = 'Запуск выгрузки всех данных через очередь';

    public function handle(): void
    {
        $this->info('Dispatching DataFetchJob...');

        DataFetchJob::dispatch();
    }
}
