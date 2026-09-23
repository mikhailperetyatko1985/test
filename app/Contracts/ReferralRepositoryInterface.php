<?php

namespace App\Contracts;

use App\Models\Referral;

interface ReferralRepositoryInterface
{
    /**
     * Ищет ожидающий (Pending) реферал по идентификатору приведённого мастера.
     */
    public function findPendingByReferredMasterId(int $referredMasterId): ?Referral;
}
