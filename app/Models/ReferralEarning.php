<?php

namespace App\Models;

use App\Enums\ReferralEarningStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class ReferralEarning extends Model
{
    use HasFactory;

    public const string F_ID = 'id';
    public const string F_REFERRER_MASTER_ID = 'referrer_master_id';
    public const string F_REFERRED_MASTER_ID = 'referred_master_id';
    public const string F_REFERRAL_ID = 'referral_id';
    public const string F_PAYMENT_ID = 'payment_id';
    public const string F_PAYMENT_AMOUNT = 'payment_amount';
    public const string F_AMOUNT = 'amount';
    public const string F_PERCENT = 'percent';
    public const string F_STATUS = 'status';

    protected $fillable = [
        self::F_REFERRER_MASTER_ID,
        self::F_REFERRED_MASTER_ID,
        self::F_REFERRAL_ID,
        self::F_PAYMENT_ID,
        self::F_PAYMENT_AMOUNT,
        self::F_AMOUNT,
        self::F_PERCENT,
        self::F_STATUS,
    ];

    protected $casts = [
        self::F_PAYMENT_AMOUNT => 'integer',
        self::F_AMOUNT => 'integer',
        self::F_STATUS => ReferralEarningStatus::class,
    ];

    public function referrerMaster(): BelongsTo
    {
        return $this->belongsTo(Master::class, self::F_REFERRER_MASTER_ID);
    }

    public function referredMaster(): BelongsTo
    {
        return $this->belongsTo(Master::class, self::F_REFERRED_MASTER_ID);
    }

    public function referral(): BelongsTo
    {
        return $this->belongsTo(Referral::class);
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }
}
