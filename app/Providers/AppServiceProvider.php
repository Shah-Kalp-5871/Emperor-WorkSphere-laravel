<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Support\Facades\Broadcast::routes(['middleware' => ['api', 'auth:api']]);
        require base_path('routes/channels.php');

        \App\Models\Project::observe(\App\Observers\AuditObserver::class);
        \App\Models\Task::observe(\App\Observers\AuditObserver::class);
        \App\Models\Employee::observe(\App\Observers\AuditObserver::class);
    }
}
