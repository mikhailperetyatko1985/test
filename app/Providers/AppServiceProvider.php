<?php

namespace App\Providers;

use App\Contracts\PaymentRepositoryInterface;
use App\Contracts\ReferralEarningRepositoryInterface;
use App\Contracts\ReferralRepositoryInterface;
use App\Models\Payment;
use App\Observers\PaymentObserver;
use App\Repositories\Eloquent\PaymentRepository;
use App\Repositories\Eloquent\ReferralEarningRepository;
use App\Repositories\Eloquent\ReferralRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ReferralRepositoryInterface::class, ReferralRepository::class);
        $this->app->bind(PaymentRepositoryInterface::class, PaymentRepository::class);
        $this->app->bind(ReferralEarningRepositoryInterface::class, ReferralEarningRepository::class);
    }

    public function boot(): void
    {
        Payment::observe(PaymentObserver::class);
    }
}
