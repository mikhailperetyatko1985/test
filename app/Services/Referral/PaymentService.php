<?php

namespace App\Services\Referral;

use App\Contracts\PaymentRepositoryInterface;

class PaymentService
{
    public function __construct(private PaymentRepositoryInterface $payments)
    {
    }

    public function countMonetaryByMasterId(int $masterId): int
    {
        return $this->payments->countMonetaryByMasterId($masterId);
    }
}
