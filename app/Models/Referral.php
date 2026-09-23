<?php

namespace App\Models;

use App\Enums\ReferralProgram;
use App\Enums\ReferralStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Referral extends Model
{
    use HasFactory;

    public const string F_ID = 'id';
    public const string F_REFERRER_MASTER_ID = 'referrer_master_id';
    public const string F_REFERRED_MASTER_ID = 'referred_master_id';
    public const string F_PROGRAM = 'program';
    public const string F_STATUS = 'status';

    protected $fillable = [
        self::F_REFERRER_MASTER_ID,
        self::F_REFERRED_MASTER_ID,
        self::F_PROGRAM,
        self::F_STATUS,
    ];

    protected $casts = [
        self::F_PROGRAM => ReferralProgram::class,
        self::F_STATUS => ReferralStatus::class,
    ];

    public function referrerMaster(): BelongsTo
    {
        return $this->belongsTo(Master::class, self::F_REFERRER_MASTER_ID);
    }

    public function referredMaster(): BelongsTo
    {
        return $this->belongsTo(Master::class, self::F_REFERRED_MASTER_ID);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where(self::F_STATUS, ReferralStatus::Rewarded);
    }
}
