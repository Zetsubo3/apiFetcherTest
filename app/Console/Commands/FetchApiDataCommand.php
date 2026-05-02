<?php

namespace App\Console\Commands;

use App\Jobs\DataFetchJob;
use App\Jobs\FetchOrdersJob;
use App\Jobs\FetchSalesJob;
use App\Jobs\FetchStocksJob;
use Illuminate\Console\Command;

class FetchApiDataCommand extends Command
{
    protected $signature = 'api:data-fetch
                            {--entity= : Тип выгружаемых данных (orders, sales, stocks, incomes)}';

    protected $description = 'Запуск выгрузки данных через очередь';

    public function handle(): void
    {
        $entity = $this->option('entity');

        if ($entity === null) {
            $this->info('Dispatching DataFetchJob (all entities)...');
            DataFetchJob::dispatch();
            $this->info('DataFetchJob dispatched!');
            return;
        }

        switch ($entity) {
            case 'orders':
                $this->info('Dispatching FetchOrdersJob...');
                FetchOrdersJob::dispatch();
                break;
            case 'sales':
                $this->info('Dispatching FetchSalesJob...');
                FetchSalesJob::dispatch();
                break;
            case 'stocks':
                $this->info('Dispatching FetchStocksJob...');
                FetchStocksJob::dispatch();
                break;
            default:
                $this->error("Unknown entity: {$entity}");
                $this->info('Available entities: orders, sales, stocks, incomes');
                return;
        }

        $this->info('Job dispatched');
    }
}
