<?php

namespace App\Services\Referral;

use App\Contracts\ReferralEarningWriteRepositoryInterface;
use App\DTOs\ReferralEarningData;
use App\Enums\ReferralEarningStatus;
use App\Models\Payment;
use App\Models\Referral;
use App\Models\ReferralEarning;

class ReferralEarningService
{
    public function __construct(
        private ReferralEarningWriteRepositoryInterface $earnings,
        private ReferralService $referrals,
    ) {
    }

    public function grantForPayment(Payment $payment, Referral $referral): ReferralEarning
    {
        return $this->earnings->create(new ReferralEarningData(
            referrerMasterId: (int) $referral->{Referral::F_REFERRER_MASTER_ID},
            referredMasterId: (int) $referral->{Referral::F_REFERRED_MASTER_ID},
            referralId: (int) $referral->{Referral::F_ID},
            paymentId: (int) $payment->{Payment::F_ID},
            paymentAmount: (int) $payment->{Payment::F_AMOUNT},
            amount: $this->referrals->rewardAmount((int) $payment->{Payment::F_AMOUNT}),
            percent: (int) config('referral.percent'),
            status: ReferralEarningStatus::Pending,
        ));
    }
}
