<?php

namespace App\Repositories\Eloquent;

use App\Contracts\PaymentRepositoryInterface;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Builder;

class PaymentRepository implements PaymentRepositoryInterface
{
    public function searchByMasterId(int $masterId): Builder
    {
        return Payment::where(Payment::F_MASTER_ID, $masterId);
    }

    public function countMonetaryByMasterId(int $masterId): int
    {
        return (int) $this->searchByMasterId($masterId)->monetary()->count();
    }
}
