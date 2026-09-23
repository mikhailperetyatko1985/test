<?php

namespace App\Repositories\Eloquent;

use App\Contracts\ReferralEarningRepositoryInterface;
use App\DTOs\ReferralEarningData;
use App\Models\ReferralEarning;

class ReferralEarningRepository implements ReferralEarningRepositoryInterface
{
    public function create(ReferralEarningData $data): ReferralEarning
    {
        return ReferralEarning::create($data->toAttributes());
    }
}
