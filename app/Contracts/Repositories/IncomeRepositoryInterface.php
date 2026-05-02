<?php

namespace App\Contracts\Repositories;
interface IncomeRepositoryInterface
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
}
