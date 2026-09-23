<?php

namespace App\Contracts;

use App\DTOs\ReferralEarningsSummaryData;

/**
 * Кешированная (тегированный кеш) сводка по реферальным деньгам для
 * GET /api/referrals/earnings. Отдельный интерфейс, чтобы в DI подставлять
 * именно кешированную реализацию там, где нужен кешированный вывод,
 * и некешированные базовые чтения — везде остальном.
 */
interface CachedReferralEarningsSummaryRepositoryInterface
{
    /**
     * Сводка по деньгам реферера (total/pending/paid/засчитанные рефералы).
     */
    public function summaryForReferrer(int $referrerMasterId): ReferralEarningsSummaryData;
}
