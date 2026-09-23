<?php

namespace App\Services\Referral;

use App\Contracts\MasterRepositoryInterface;
use App\Contracts\ReferralRepositoryInterface;
use App\Models\Master;
use App\Models\Referral;

class ReferralService
{
    public function __construct(
        private ReferralRepositoryInterface $referrals,
        private MasterRepositoryInterface $masters,
    ) {
    }

    public function registerReferral(Master $referred, string $code): ?Referral
    {
        $referrer = $this->masters->findByReferralCode($code);

        if (empty($referrer) || $referrer->{Master::F_ID} === $referred->{Master::F_ID}) {
            return null;
        }

        return $this->referrals->attachToMaster(
            $referred->{Master::F_ID},
            $referrer->{Master::F_ID},
        );
    }

    public function findPendingByReferredMasterId(int $referredMasterId): ?Referral
    {
        return $this->referrals->findPendingByReferredMasterId($referredMasterId);
    }

    public function rewardAmount(int $paymentAmount): int
    {
        $percent = (int) config('referral.percent');

        return (int) round($paymentAmount * $percent);
    }
}
