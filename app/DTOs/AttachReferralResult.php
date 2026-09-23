<?php

namespace App\DTOs;

use App\Models\Referral;

/**
 * Результат закрепления: актуальная привязка и была ли она создана этим вызовом.
 */
readonly class AttachReferralResult
{
    public function __construct(
        public Referral $referral,
        public bool $created,
    ) {
    }
}
