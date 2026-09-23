<?php

namespace App\Services\Referral;

use App\Contracts\ReferralReadRepositoryInterface;
use App\Enums\ReferralStatus;
use App\Models\Payment;
use App\Models\Referral;
use Illuminate\Support\Facades\DB;

class ReferralRewardPipeline
{
    public function __construct(
        private ReferralReadRepositoryInterface $referrals,
        private PaymentService $payments,
        private ReferralEarningService $earnings,
    ) {
    }

    /**
     * Обрабатывает созданный платёж и при соблюдении условий начисляет реферальное вознаграждение.
     * Все шаги выполняются в одной транзакции: исключение на любом этапе приводит к откату.
     */
    public function handle(Payment $payment): void
    {
        DB::transaction(function () use ($payment): void {
            if (!$this->isMonetaryPayment($payment)) {
                return;
            }

            $referral = $this->findPendingReferral($payment);

            if ($referral === null) {
                return;
            }

            if (!$this->isFirstMonetaryPayment($payment)) {
                return;
            }

            $this->grantEarning($payment, $referral);

            $this->markReferralRewarded($referral);
        });
    }

    private function isMonetaryPayment(Payment $payment): bool
    {
        return Payment::isMonetary($payment);
    }

    private function findPendingReferral(Payment $payment): ?Referral
    {
        return $this->referrals->findPendingByReferredMasterId((int) $payment->{Payment::F_MASTER_ID});
    }

    private function isFirstMonetaryPayment(Payment $payment): bool
    {
        return $this->payments->countMonetaryByMasterId((int) $payment->{Payment::F_MASTER_ID}) <= 1;
    }

    private function grantEarning(Payment $payment, Referral $referral): void
    {
        $this->earnings->grantForPayment($payment, $referral);
    }

    private function markReferralRewarded(Referral $referral): void
    {
        $referral->update([Referral::F_STATUS => ReferralStatus::Rewarded]);
    }
}
