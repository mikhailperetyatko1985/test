<?php

namespace App\Contracts;

use App\Enums\ReferralProgram;
use App\Enums\ReferralStatus;
use App\Models\Referral;

interface ReferralRepositoryInterface
{
    /**
     * Ищет ожидающий (Pending) реферал по идентификатору приведённого мастера.
     */
    public function findPendingByReferredMasterId(int $referredMasterId): ?Referral;

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
