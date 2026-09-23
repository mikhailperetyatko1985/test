<?php

namespace App\Exceptions;

use RuntimeException;

/**
 * Реферальный код не найден. Рендерится как 404 (см. bootstrap/app.php).
 */
class UnknownReferralCodeException extends RuntimeException
{
}
