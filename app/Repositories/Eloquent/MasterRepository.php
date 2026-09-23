<?php

namespace App\Repositories\Eloquent;

use App\Contracts\MasterRepositoryInterface;
use App\Models\Master;

class MasterRepository implements MasterRepositoryInterface
{
    public function findByReferralCode(string $referralCode): ?Master
    {
        return Master::where(Master::F_REFERRAL_CODE, $referralCode)->first();
    }
}
