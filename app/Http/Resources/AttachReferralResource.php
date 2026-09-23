<?php

namespace App\Http\Resources;

use App\DTOs\AttachReferralResult;
use App\Models\Master;
use App\Models\Referral;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Ответ POST /api/referrals/attach.
 */
class AttachReferralResource extends JsonResource
{
    /**
     * @param  AttachReferralResult  $resource
     */
    public function toArray(Request $request): array
    {
        return [
            'created' => $this->resource->created,
            'status' => $this->resource->referral->{Referral::F_STATUS}->value,
            'referrer_name' => (string) $this->resource->referral->referrerMaster->{Master::F_NAME},
            'attached_at' => $this->resource->referral->created_at->toISOString(),
        ];
    }
}
