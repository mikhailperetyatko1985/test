<?php

namespace App\Services\Referral;

use App\Contracts\MasterRepositoryInterface;
use App\Contracts\ReferralWriteRepositoryInterface;
use App\DTOs\AttachReferralData;
use App\DTOs\AttachReferralResult;
use App\Exceptions\SelfReferralException;
use App\Exceptions\UnknownReferralCodeException;
use App\Models\Master;
use App\Models\Referral;

class ReferralService
{
    public function __construct(
        private ReferralWriteRepositoryInterface $referrals,
        private MasterRepositoryInterface $masters,
    ) {
    }

    /**
     * Закрепляет мастера за владельцем кода.
     * Повторный вызов идемпотентен: возвращает существующую привязку (created = false).
     */
    public function attach(Master $referred, AttachReferralData $data): AttachReferralResult
    {
        $referrer = $this->masters->findByReferralCode($data->code);

        if ($referrer === null) {
            throw new UnknownReferralCodeException("Referral code [{$data->code}] not found.");
        }

        if ((int) $referrer->{Master::F_ID} === (int) $referred->{Master::F_ID}) {
            throw new SelfReferralException('A master cannot attach to their own referral code.');
        }

        $referral = $this->referrals->attachToMaster(
            (int) $referred->{Master::F_ID},
            (int) $referrer->{Master::F_ID},
        );
        $referral->load('referrerMaster');

        return new AttachReferralResult(referral: $referral, created: $referral->wasRecentlyCreated);
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

    public function rewardAmount(int $paymentAmount): int
    {
        $percent = (int) config('referral.percent');

        return (int) round($paymentAmount * $percent / 100);
    }
}
