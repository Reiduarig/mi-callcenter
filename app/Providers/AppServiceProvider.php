<?php

namespace App\Providers;

use App\Domains\Staff\Repositories\AbsenceRepository;
use App\Domains\Staff\Repositories\Contracts\AbsenceRepositoryInterface;
use App\Domains\Staff\Repositories\Contracts\ShiftRepositoryInterface;
use App\Domains\Staff\Repositories\ShiftRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind repository interfaces to implementations
        $this->app->bind(AbsenceRepositoryInterface::class, AbsenceRepository::class);
        $this->app->bind(ShiftRepositoryInterface::class, ShiftRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
