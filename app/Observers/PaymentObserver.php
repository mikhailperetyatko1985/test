<?php

namespace App\Observers;

use App\Enums\ReferralEarningStatus;
use App\Enums\ReferralStatus;
use App\Models\Payment;
use App\Models\Referral;
use App\Models\ReferralEarning;
use App\Services\Referral\ReferralService;

class PaymentObserver
{
    public function __construct(private ReferralService $referrals)
    {
    }

    public function created(Payment $payment): void
    {
        if (!Payment::isMonetary($payment)) {
            return;
        }

        $referral = Referral::where(Referral::F_REFERRED_MASTER_ID, $payment->{Payment::F_MASTER_ID})
            ->where(Referral::F_STATUS, ReferralStatus::Pending)
            ->first();

        if (empty($referral)) {
            return;
        }

        $monetaryCount = Payment::where(Payment::F_MASTER_ID, $payment->{Payment::F_MASTER_ID})
            ->monetary()
            ->count();

        if ($monetaryCount > 1) {
            return;
        }

        ReferralEarning::create([
            ReferralEarning::F_REFERRER_MASTER_ID => $referral->{Referral::F_REFERRER_MASTER_ID},
            ReferralEarning::F_REFERRED_MASTER_ID => $referral->{Referral::F_REFERRED_MASTER_ID},
            ReferralEarning::F_REFERRAL_ID => $referral->{Referral::F_ID},
            ReferralEarning::F_PAYMENT_ID => $payment->{Payment::F_ID},
            ReferralEarning::F_PAYMENT_AMOUNT => $payment->{Payment::F_AMOUNT},
            ReferralEarning::F_AMOUNT => $this->referrals->rewardAmount((int) $payment->{Payment::F_AMOUNT}),
            ReferralEarning::F_PERCENT => (int) config('referral.percent'),
            ReferralEarning::F_STATUS => ReferralEarningStatus::Pending,
        ]);

        $referral->update([Referral::F_STATUS => ReferralStatus::Rewarded]);
    }
}
