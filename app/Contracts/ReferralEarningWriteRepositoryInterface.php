<?php

namespace App\Contracts;

use App\DTOs\ReferralEarningData;
use App\Models\ReferralEarning;

/**
 * Запись по referral_earnings. Чтение отсутствует — см. ReferralEarningReadRepositoryInterface.
 */
interface ReferralEarningWriteRepositoryInterface
{
    /**
     * Создаёт запись о реферальном вознаграждении.
     */
    public function create(ReferralEarningData $data): ReferralEarning;
}
