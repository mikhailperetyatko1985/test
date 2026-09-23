<?php

namespace App\Contracts;

use App\DTOs\ReferralEarningData;
use App\Models\ReferralEarning;

interface ReferralEarningRepositoryInterface
{
    /**
     * Создаёт запись о реферальном вознаграждении.
     */
    public function create(ReferralEarningData $data): ReferralEarning;
}
