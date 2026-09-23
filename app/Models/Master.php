<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Master extends Model
{
    use HasFactory;

    public const string F_ID = 'id';
    public const string F_NAME = 'name';
    public const string F_REFERRAL_CODE = 'referral_code';

    protected $fillable = [
        self::F_NAME,
        self::F_REFERRAL_CODE,
    ];

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function referrals(): HasMany
    {
        return $this->hasMany(Referral::class, Referral::F_REFERRER_MASTER_ID);
    }

    public function referralEarnings(): HasMany
    {
        return $this->hasMany(ReferralEarning::class, ReferralEarning::F_REFERRER_MASTER_ID);
    }

    public function isPaid(): bool
    {
        return $this->payments()->exists();
    }
}
