<?php

namespace App\Contracts\Repositories;
interface StockRepositoryInterface
{
    /**
     * Массово вставить или обновить записи
     *
     * @param array $items Массив данных для вставки
     * @return void
     */
    public function upsertBatch(array $items): void;

    /**
     * Подсчёт количества записей в таблице
     *
     * @return int
     */
    public function count(): int;

    /**
     * Очистить таблицу
     *
     * @return void
     */
    public function truncate(): void;

}
