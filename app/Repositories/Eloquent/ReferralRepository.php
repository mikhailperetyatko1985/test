<?php

namespace App\Repositories\Eloquent;

use App\Contracts\ReferralRepositoryInterface;
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
}
