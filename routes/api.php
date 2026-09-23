<?php

use App\Http\Controllers\Api\ReferralController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API
|--------------------------------------------------------------------------
|
| Текущий мастер приходит в заголовке X-Master-Id и уже разложен
| в атрибуты запроса middleware'ом ResolveCurrentMaster:
|
|     $master = $request->attributes->get('current_master');
|
*/

Route::get('/ping', fn () => ['ok' => true]);

Route::prefix('referrals')->group(function () {
    Route::post('/attach', [ReferralController::class, 'attach']);
    Route::get('/my', [ReferralController::class, 'my']);
    Route::get('/earnings', [ReferralController::class, 'earnings']);
});
