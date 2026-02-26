<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            \App\Repositories\Contracts\EmployeeRepositoryInterface::class,
            \App\Repositories\Eloquent\EmployeeRepository::class
        );
        $this->app->bind(
            \App\Repositories\Contracts\ProjectRepositoryInterface::class,
            \App\Repositories\Eloquent\ProjectRepository::class
        );
        $this->app->bind(
            \App\Repositories\Contracts\TaskRepositoryInterface::class,
            \App\Repositories\Eloquent\TaskRepository::class
        );
        $this->app->bind(
            \App\Repositories\Contracts\DailyLogRepositoryInterface::class,
            \App\Repositories\Eloquent\DailyLogRepository::class
        );
        $this->app->bind(
            \App\Repositories\Contracts\SupportTicketRepositoryInterface::class,
            \App\Repositories\Eloquent\SupportTicketRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
