<?php

namespace App\Models;

use App\Enums\PaymentType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    public const string F_ID = 'id';
    public const string F_MASTER_ID = 'master_id';
    public const string F_AMOUNT = 'amount';
    public const string F_TYPE = 'type';

    protected $fillable = [
        self::F_MASTER_ID,
        self::F_AMOUNT,
        self::F_TYPE,
    ];

    protected $casts = [
        self::F_AMOUNT => 'integer',
        self::F_TYPE => PaymentType::class,
    ];

    public function master(): BelongsTo
    {
        return $this->belongsTo(Master::class);
    }

    public static function isMonetary(self $payment): bool
    {
        return in_array($payment->{self::F_TYPE}, [PaymentType::Card, PaymentType::Sbp], true)
            && $payment->{self::F_AMOUNT} > 0;
    }

    public function scopeMonetary(Builder $query): Builder
    {
        return $query->whereIn(self::F_TYPE, [PaymentType::Card, PaymentType::Sbp]);
    }
}
