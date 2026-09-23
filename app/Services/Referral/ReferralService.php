<?php

namespace App\Services\Referral;

use App\Enums\ReferralProgram;
use App\Enums\ReferralStatus;
use App\Models\Master;
use App\Models\Referral;

class ReferralService
{
    public function registerReferral(Master $referred, string $code): ?Referral
    {
        $referrer = Master::where(Master::F_REFERRAL_CODE, $code)->first();

        if (empty($referrer) || $referrer->{Master::F_ID} === $referred->{Master::F_ID}) {
            return null;
        }

        return Referral::firstOrCreate(
            [
                Referral::F_REFERRED_MASTER_ID => $referred->{Master::F_ID},
            ],
            [
                Referral::F_REFERRER_MASTER_ID => $referrer->{Master::F_ID},
                Referral::F_PROGRAM => ReferralProgram::MasterInvite,
                Referral::F_STATUS => ReferralStatus::Pending,
            ]
        );
    }

    public function rewardAmount(int $paymentAmount): int
    {
        $percent = (int) config('referral.percent');

        return (int) round($paymentAmount * $percent);
    }
}
