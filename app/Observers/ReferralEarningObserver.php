<?php

namespace App\Observers;

use App\Models\ReferralEarning;
use App\Repositories\Cached\CachedReferralEarningsSummaryRepository;

/**
 * Сбрасывает тегированный кеш сводки по деньгам при изменении начислений:
 * суммы total/pending/paid в GET /api/referrals/earnings считаются именно от этой таблицы.
 * Затронута только сводка реферера этого начисления — сбрасываем её личный тег,
 * а не общий кеш всех мастеров.
 */
class ReferralEarningObserver
{
    public function created(ReferralEarning $earning): void
    {
        CachedReferralEarningsSummaryRepository::flushCache((int) $earning->{ReferralEarning::F_REFERRER_MASTER_ID});
    }

    public function updated(ReferralEarning $earning): void
    {
        CachedReferralEarningsSummaryRepository::flushCache((int) $earning->{ReferralEarning::F_REFERRER_MASTER_ID});
    }

    public function deleted(ReferralEarning $earning): void
    {
        CachedReferralEarningsSummaryRepository::flushCache((int) $earning->{ReferralEarning::F_REFERRER_MASTER_ID});
    }
}
