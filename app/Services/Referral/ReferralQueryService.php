<?php

namespace App\Services\Referral;

use App\Contracts\CachedReferralEarningsSummaryRepositoryInterface;
use App\Contracts\ReferralEarningReadRepositoryInterface;
use App\Contracts\ReferralReadRepositoryInterface;
use App\DTOs\ReferredMasterData;
use App\DTOs\ReferralEarningsSummaryData;
use App\Enums\ReferralStatus;
use App\Models\Master;
use App\Models\Referral;
use Illuminate\Pagination\LengthAwarePaginator;

class ReferralQueryService
{
    public function __construct(
        private ReferralReadRepositoryInterface $referrals,
        private ReferralEarningReadRepositoryInterface $earnings,
        private CachedReferralEarningsSummaryRepositoryInterface $cachedEarnings,
    ) {
    }

    /**
     * Страница "приведённые мной мастера". Кеширования здесь нет: идут прямые
     * (некешированные) чтения. Всего три запроса: count для пагинатора, сама
     * страница с подгруженными мастерами и один GROUP BY по начислениям — без N+1.
     */
    public function myReferredMasters(int $referrerMasterId): LengthAwarePaginator
    {
        $page = $this->referrals->paginateByReferrer(
            $referrerMasterId,
            max(1, (int) config('referral.per_page')),
        );

        $totals = $this->earnings->sumsGroupedByReferredMaster($referrerMasterId);

        return new LengthAwarePaginator(
            items: $page->getCollection()
                ->map(function (Referral $referral) use ($totals): ReferredMasterData {
                    $referredId = (int) $referral->{Referral::F_REFERRED_MASTER_ID};

                    return new ReferredMasterData(
                        name: (string) $referral->referredMaster->{Master::F_NAME},
                        attachedAt: $referral->created_at,
                        counted: $referral->{Referral::F_STATUS} === ReferralStatus::Rewarded,
                        earnedAmount: (int) ($totals[$referredId] ?? 0),
                    );
                })
                ->all(),
            total: $page->total(),
            perPage: $page->perPage(),
            currentPage: $page->currentPage(),
        );
    }

    /**
     * Сводка по деньгам для GET /api/referrals/earnings.
     * Берётся из тегированного кеша (сброс — обсерверами моделей), внутри которого
     * кешированный репозиторий вызывает методы базовых некешированных чтений.
     */
    public function earningsSummary(int $referrerMasterId): ReferralEarningsSummaryData
    {
        return $this->cachedEarnings->summaryForReferrer($referrerMasterId);
    }
}
