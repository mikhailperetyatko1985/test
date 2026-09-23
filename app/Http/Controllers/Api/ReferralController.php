<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AttachReferralRequest;
use App\Http\Resources\AttachReferralResource;
use App\Http\Resources\ReferredMasterResource;
use App\Http\Resources\ReferralEarningsSummaryResource;
use App\Models\Master;
use App\Services\Referral\ReferralQueryService;
use App\Services\Referral\ReferralService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ReferralController extends Controller
{
    public function __construct(
        private ReferralService $referrals,
        private ReferralQueryService $query,
    ) {
    }

    /**
     * POST /api/referrals/attach
     */
    public function attach(Request $request, AttachReferralRequest $attach): JsonResponse
    {
        $result = $this->referrals->attach($this->currentMaster($request), $attach->toData());

        return response()
            ->json((new AttachReferralResource($result))->resolve(), $result->created ? Response::HTTP_CREATED : Response::HTTP_OK);
    }

    /**
     * GET /api/referrals/my
     */
    public function my(Request $request)
    {
        return ReferredMasterResource::collection(
            $this->query->myReferredMasters((int) $this->currentMaster($request)->{Master::F_ID}),
        );
    }

    /**
     * GET /api/referrals/earnings
     */
    public function earnings(Request $request)
    {
        return ReferralEarningsSummaryResource::make(
            $this->query->earningsSummary((int) $this->currentMaster($request)->{Master::F_ID}),
        );
    }

    /**
     * Текущий мастер кладётся в атрибуты запроса middleware'ом ResolveCurrentMaster.
     */
    private function currentMaster(Request $request): Master
    {
        $master = $request->attributes->get('current_master');

        if (!$master instanceof Master) {
            abort(401, 'Missing or invalid X-Master-Id header.');
        }

        return $master;
    }
}
