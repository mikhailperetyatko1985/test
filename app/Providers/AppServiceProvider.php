<?php

namespace App\Providers;

use App\Contracts\CachedReferralEarningsSummaryRepositoryInterface;
use App\Contracts\MasterRepositoryInterface;
use App\Contracts\PaymentRepositoryInterface;
use App\Contracts\ReferralEarningReadRepositoryInterface;
use App\Contracts\ReferralEarningWriteRepositoryInterface;
use App\Contracts\ReferralReadRepositoryInterface;
use App\Contracts\ReferralWriteRepositoryInterface;
use App\Models\Payment;
use App\Models\Referral;
use App\Models\ReferralEarning;
use App\Observers\PaymentObserver;
use App\Observers\ReferralEarningObserver;
use App\Observers\ReferralObserver;
use App\Repositories\Cached\CachedReferralEarningsSummaryRepository;
use App\Repositories\Eloquent\MasterRepository;
use App\Repositories\Eloquent\PaymentRepository;
use App\Repositories\Eloquent\ReferralEarningReadRepository;
use App\Repositories\Eloquent\ReferralEarningWriteRepository;
use App\Repositories\Eloquent\ReferralReadRepository;
use App\Repositories\Eloquent\ReferralWriteRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(MasterRepositoryInterface::class, MasterRepository::class);
        $this->app->bind(PaymentRepositoryInterface::class, PaymentRepository::class);

        // Чтение/запись по referrals разнесены: в DI подставляется именно то,
        // что нужно конкретной зависимости (и можно заменить реализацию гибко).
        $this->app->bind(ReferralReadRepositoryInterface::class, ReferralReadRepository::class);
        $this->app->bind(ReferralWriteRepositoryInterface::class, ReferralWriteRepository::class);

        $this->app->bind(ReferralEarningReadRepositoryInterface::class, ReferralEarningReadRepository::class);
        $this->app->bind(ReferralEarningWriteRepositoryInterface::class, ReferralEarningWriteRepository::class);

        // Кешированная сводка для /api/referrals/earnings — отдельным контрактом.
        $this->app->bind(CachedReferralEarningsSummaryRepositoryInterface::class, CachedReferralEarningsSummaryRepository::class);
    }

    public function boot(): void
    {
        Payment::observe(PaymentObserver::class);

        // Сброс тегированного кеша сводки при изменении моделей, от которых она зависит.
        Referral::observe(ReferralObserver::class);
        ReferralEarning::observe(ReferralEarningObserver::class);
    }
}
