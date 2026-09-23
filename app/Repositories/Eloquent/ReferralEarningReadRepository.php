<?php

namespace App\Repositories\Eloquent;

use App\Contracts\ReferralEarningReadRepositoryInterface;
use App\Enums\ReferralEarningStatus;
use App\Models\ReferralEarning;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ReferralEarningReadRepository implements ReferralEarningReadRepositoryInterface
{
    public function sumAmountByReferrer(int $referrerMasterId, ?ReferralEarningStatus $status = null): int
    {
        return (int) ReferralEarning::query()
            ->where(ReferralEarning::F_REFERRER_MASTER_ID, $referrerMasterId)
            ->when($status !== null, fn (Builder $q) => $q->where(ReferralEarning::F_STATUS, $status))
            ->sum(ReferralEarning::F_AMOUNT);
    }

    public function sumsGroupedByReferredMaster(int $referrerMasterId): Collection
    {
        return ReferralEarning::query()
            ->where(ReferralEarning::F_REFERRER_MASTER_ID, $referrerMasterId)
            ->select(
                ReferralEarning::F_REFERRED_MASTER_ID,
                DB::raw('SUM(' . ReferralEarning::F_AMOUNT . ') as total'),
            )
            ->groupBy(ReferralEarning::F_REFERRED_MASTER_ID)
            ->pluck('total', ReferralEarning::F_REFERRED_MASTER_ID);
    }
}
