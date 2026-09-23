<?php

namespace App\Contracts;

use App\Enums\ReferralStatus;
use App\Models\Referral;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Чтение по referrals. Записи отсутствуют — см. ReferralWriteRepositoryInterface.
 */
interface ReferralReadRepositoryInterface
{
    /**
     * Ищет ожидающий (Pending) реферал по идентификатору приведённого мастера.
     */
    public function findPendingByReferredMasterId(int $referredMasterId): ?Referral;

    /**
     * Приведённые данным мастером записи с подгруженными мастерами, постранично.
     */
    public function paginateByReferrer(int $referrerMasterId, int $perPage): LengthAwarePaginator;

    /**
     * Сколько рефералов данного мастера засчитано (статус Rewarded).
     */
    public function countRewardedByReferrer(int $referrerMasterId): int;
}
