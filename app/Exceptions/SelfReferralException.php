<?php

namespace App\Exceptions;

use RuntimeException;

/**
 * Мастер пытается закрепиться за собственным кодом. Рендерится как 422 (см. bootstrap/app.php).
 */
class SelfReferralException extends RuntimeException
{
}
