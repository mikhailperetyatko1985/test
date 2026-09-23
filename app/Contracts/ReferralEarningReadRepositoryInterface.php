<?php

namespace App\Contracts;

use App\Enums\ReferralEarningStatus;
use Illuminate\Support\Collection;

/**
 * Чтение по referral_earnings. Записи отсутствуют — см. ReferralEarningWriteRepositoryInterface.
 */
interface ReferralEarningReadRepositoryInterface
{
    /**
     * Сумма начислений реферера, при желании — только по одному статусу.
     */
    public function sumAmountByReferrer(int $referrerMasterId, ?ReferralEarningStatus $status = null): int;

    /**
     * Сколько всего начислено по каждому приведённому мастеру (referred_master_id => сумма).
     *
     * @return Collection<int, int>
     */
    public function sumsGroupedByReferredMaster(int $referrerMasterId): Collection;
}
