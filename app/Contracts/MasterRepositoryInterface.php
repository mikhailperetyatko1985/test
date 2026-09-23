<?php

namespace App\Contracts;

use App\Models\Master;

interface MasterRepositoryInterface
{
    /**
     * Ищет мастера по реферальному коду.
     */
    public function findByReferralCode(string $referralCode): ?Master;
}
