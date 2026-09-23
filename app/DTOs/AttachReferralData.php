<?php

namespace App\DTOs;

/**
 * Валидированный пользовательский ввод для POST /api/referrals/attach.
 */
readonly class AttachReferralData
{
    public function __construct(
        public string $code,
    ) {
    }
}
