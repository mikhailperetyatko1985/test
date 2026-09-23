<?php

namespace App\Repositories\Eloquent;

use App\Contracts\ReferralReadRepositoryInterface;
use App\Enums\ReferralStatus;
use App\Models\Referral;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ReferralReadRepository implements ReferralReadRepositoryInterface
{
    public function findPendingByReferredMasterId(int $referredMasterId): ?Referral
    {
        return Referral::where(Referral::F_REFERRED_MASTER_ID, $referredMasterId)
            ->where(Referral::F_STATUS, ReferralStatus::Pending)
            ->first();
    }

    public function paginateByReferrer(int $referrerMasterId, int $perPage): LengthAwarePaginator
    {
        return Referral::query()
            ->where(Referral::F_REFERRER_MASTER_ID, $referrerMasterId)
            ->with('referredMaster')
            // id DESC — детерминированный порядок по дате привязки без filesort:
            // индексу достаточно обратного обхода по rowid.
            ->orderByDesc(Referral::F_ID)
            ->paginate($perPage);
    }

    public function countRewardedByReferrer(int $referrerMasterId): int
    {
        return (int) Referral::query()
            ->where(Referral::F_REFERRER_MASTER_ID, $referrerMasterId)
            ->where(Referral::F_STATUS, ReferralStatus::Rewarded)
            ->count();
    }
}
