<?php

namespace App\Http\Resources;

use App\DTOs\ReferredMasterData;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Строка списка GET /api/referrals/my.
 */
class ReferredMasterResource extends JsonResource
{
    /**
     * @param  ReferredMasterData  $resource
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->resource->name,
            'attached_at' => $this->resource->attachedAt->toISOString(),
            'counted' => $this->resource->counted,
            'earned_amount' => $this->resource->earnedAmount,
        ];
    }
}
