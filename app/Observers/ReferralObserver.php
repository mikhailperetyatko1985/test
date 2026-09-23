<?php

namespace App\Observers;

use App\Models\Referral;
use App\Repositories\Cached\CachedReferralEarningsSummaryRepository;

/**
 * Сбрасывает тегированный кеш сводки по деньгам при изменении referrals:
 * от статуса (rewarded) зависит поле counted_referrals в GET /api/referrals/earnings.
 * Затронута только сводка реферера этой записи — сбрасываем её личный тег,
 * а не общий кеш всех мастеров.
 */
class ReferralObserver
{
    public function created(Referral $referral): void
    {
        CachedReferralEarningsSummaryRepository::flushCache((int) $referral->{Referral::F_REFERRER_MASTER_ID});
    }

    public function updated(Referral $referral): void
    {
        CachedReferralEarningsSummaryRepository::flushCache((int) $referral->{Referral::F_REFERRER_MASTER_ID});
    }

    public function deleted(Referral $referral): void
    {
        CachedReferralEarningsSummaryRepository::flushCache((int) $referral->{Referral::F_REFERRER_MASTER_ID});
    }
}
