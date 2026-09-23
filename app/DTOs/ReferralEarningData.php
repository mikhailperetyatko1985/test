<?php

namespace App\DTOs;

use App\Enums\ReferralEarningStatus;
use App\Models\ReferralEarning;

readonly class ReferralEarningData
{
    public function __construct(
        public int $referrerMasterId,
        public int $referredMasterId,
        public int $referralId,
        public int $paymentId,
        public int $paymentAmount,
        public int $amount,
        public int $percent,
        public ReferralEarningStatus $status,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function toAttributes(): array
    {
        return [
            ReferralEarning::F_REFERRER_MASTER_ID => $this->referrerMasterId,
            ReferralEarning::F_REFERRED_MASTER_ID => $this->referredMasterId,
            ReferralEarning::F_REFERRAL_ID => $this->referralId,
            ReferralEarning::F_PAYMENT_ID => $this->paymentId,
            ReferralEarning::F_PAYMENT_AMOUNT => $this->paymentAmount,
            ReferralEarning::F_AMOUNT => $this->amount,
            ReferralEarning::F_PERCENT => $this->percent,
            ReferralEarning::F_STATUS => $this->status,
        ];
    }
}
