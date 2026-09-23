<?php

namespace App\Contracts;

use App\Enums\ReferralProgram;
use App\Enums\ReferralStatus;
use App\Models\Referral;

/**
 * Запись по referrals. Чтение отсутствует — см. ReferralReadRepositoryInterface.
 */
interface ReferralWriteRepositoryInterface
{
    /**
     * Привязывает реферала к мастеру: создаёт запись, если она ещё не существует.
     */
    public function attachToMaster(
        int $referredMasterId,
        int $referrerMasterId,
        ReferralProgram $program = ReferralProgram::MasterInvite,
        ReferralStatus $status = ReferralStatus::Pending
    ): Referral;
}
