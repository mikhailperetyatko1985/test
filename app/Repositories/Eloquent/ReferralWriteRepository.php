<?php

namespace App\Repositories\Eloquent;

use App\Contracts\ReferralWriteRepositoryInterface;
use App\Enums\ReferralProgram;
use App\Enums\ReferralStatus;
use App\Models\Referral;

/**
 * Запись по referrals. Чтения через репозиторий чтения здесь не требуется:
 * firstOrCreate сам выполняет точечную выборку до вставки.
 */
class ReferralWriteRepository implements ReferralWriteRepositoryInterface
{
    public function attachToMaster(
        int $referredMasterId,
        int $referrerMasterId,
        ReferralProgram $program = ReferralProgram::MasterInvite,
        ReferralStatus $status = ReferralStatus::Pending
    ): Referral {
        return Referral::firstOrCreate(
            [
                Referral::F_REFERRED_MASTER_ID => $referredMasterId,
            ],
            [
                Referral::F_REFERRER_MASTER_ID => $referrerMasterId,
                Referral::F_PROGRAM => $program,
                Referral::F_STATUS => $status,
            ]
        );
    }
}
