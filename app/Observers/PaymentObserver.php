<?php

namespace App\Observers;

use App\Models\Payment;
use App\Services\Referral\ReferralRewardPipeline;

class PaymentObserver
{
    public function __construct(private ReferralRewardPipeline $pipeline)
    {
    }

    public function created(Payment $payment): void
    {
        $this->pipeline->handle($payment);
    }
}
