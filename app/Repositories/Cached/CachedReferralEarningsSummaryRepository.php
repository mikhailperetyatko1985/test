<?php

namespace App\Repositories\Cached;

use App\Contracts\CachedReferralEarningsSummaryRepositoryInterface;
use App\Contracts\ReferralEarningReadRepositoryInterface;
use App\Contracts\ReferralReadRepositoryInterface;
use App\DTOs\ReferralEarningsSummaryData;
use App\Enums\ReferralEarningStatus;
use Illuminate\Support\Facades\Cache;

/**
 * Кешированный read-model для GET /api/referrals/earnings.
 *
 * Обёртка над базовыми (некешированными) репозиториями чтения: сама ничего не
 * запрашивает из БД, а вызывает их методы и кладёт готовую сводку в тегированный
 * кеш. Каждая сводка хранится под двумя тегами: общим (referral-earnings-summary)
 * и личным мастеру (referral-earnings-summary:{master_id}). Изменения моделей
 * сбрасывают только личный тег затронутого мастера; общий оставлен для полного
 * ручного сброса всего кеша сводки.
 */
class CachedReferralEarningsSummaryRepository implements CachedReferralEarningsSummaryRepositoryInterface
{
    public const string CACHE_TAG = 'referral-earnings-summary';

    public function __construct(
        private ReferralReadRepositoryInterface $referrals,
        private ReferralEarningReadRepositoryInterface $earnings,
    ) {
    }

    public function summaryForReferrer(int $referrerMasterId): ReferralEarningsSummaryData
    {
        return Cache::tags([self::CACHE_TAG, self::masterTag($referrerMasterId)])->rememberForever(
            sprintf('summary:%d', $referrerMasterId),
            fn () => new ReferralEarningsSummaryData(
                total: $this->earnings->sumAmountByReferrer($referrerMasterId),
                pending: $this->earnings->sumAmountByReferrer($referrerMasterId, ReferralEarningStatus::Pending),
                paid: $this->earnings->sumAmountByReferrer($referrerMasterId, ReferralEarningStatus::Paid),
                countedReferrals: $this->referrals->countRewardedByReferrer($referrerMasterId),
            ),
        );
    }

    /**
     * Личный тег сводки конкретного мастера.
     */
    public static function masterTag(int $masterId): string
    {
        return sprintf('%s:%d', self::CACHE_TAG, $masterId);
    }

    /**
     * Сброс кеша сводки: по умолчанию — только личный тег затронутого мастера.
     * Вызов без аргумента (flushCache()) сбрасывает общий тег и вместе с ним всё.
     */
    public static function flushCache(?int $masterId = null): void
    {
        $tag = $masterId === null ? self::CACHE_TAG : self::masterTag($masterId);

        Cache::tags([$tag])->flush();
    }
}
