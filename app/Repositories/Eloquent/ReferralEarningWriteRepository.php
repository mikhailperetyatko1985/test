<?php

namespace App\Repositories\Eloquent;

use App\Contracts\ReferralEarningWriteRepositoryInterface;
use App\DTOs\ReferralEarningData;
use App\Models\ReferralEarning;

class ReferralEarningWriteRepository implements ReferralEarningWriteRepositoryInterface
{
    public function create(ReferralEarningData $data): ReferralEarning
    {
        return ReferralEarning::create($data->toAttributes());
    }
}
