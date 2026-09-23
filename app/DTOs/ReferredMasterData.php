<?php

namespace App\DTOs;

use Illuminate\Support\Carbon;

/**
 * Строка списка "приведённые мной мастера" (GET /api/referrals/my).
 */
readonly class ReferredMasterData
{
    public function __construct(
        public string $name,
        public Carbon $attachedAt,
        public bool $counted,
        public int $earnedAmount,
    ) {
    }
}
