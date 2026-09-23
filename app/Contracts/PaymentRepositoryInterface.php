<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Builder;

interface PaymentRepositoryInterface
{
    /**
     * Возвращает запрос для поиска платежей мастера.
     */
    public function searchByMasterId(int $masterId): Builder;

    /**
     * Количество денежных (monetary) платежей мастера.
     */
    public function countMonetaryByMasterId(int $masterId): int;
}
