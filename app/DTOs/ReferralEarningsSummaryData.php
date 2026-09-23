<?php

namespace App\DTOs;

/**
 * Сводка по реферальным деньгам (GET /api/referrals/earnings).
 */
readonly class ReferralEarningsSummaryData
{
    public function __construct(
        public int $total,
        public int $pending,
        public int $paid,
        public int $countedReferrals,
    ) {
    }
}
