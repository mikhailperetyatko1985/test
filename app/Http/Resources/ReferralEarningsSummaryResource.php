<?php

namespace App\Http\Resources;

use App\DTOs\ReferralEarningsSummaryData;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Ответ GET /api/referrals/earnings. Суммы в тех же единицах, что и платежи (копейки).
 */
class ReferralEarningsSummaryResource extends JsonResource
{
    /**
     * @param  ReferralEarningsSummaryData  $resource
     */
    public function toArray(Request $request): array
    {
        return [
            'total' => $this->resource->total,
            'pending' => $this->resource->pending,
            'paid' => $this->resource->paid,
            'counted_referrals' => $this->resource->countedReferrals,
        ];
    }
}
