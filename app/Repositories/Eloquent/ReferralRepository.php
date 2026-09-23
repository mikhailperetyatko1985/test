<?php

namespace App\Repositories\Eloquent;

use App\Contracts\ReferralRepositoryInterface;
use App\Enums\ReferralProgram;
use App\Enums\ReferralStatus;
use App\Models\Referral;

class ReferralRepository implements ReferralRepositoryInterface
{
    public function findPendingByReferredMasterId(int $referredMasterId): ?Referral
    {
        return Referral::where(Referral::F_REFERRED_MASTER_ID, $referredMasterId)
            ->where(Referral::F_STATUS, ReferralStatus::Pending)
            ->first();
    }

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
