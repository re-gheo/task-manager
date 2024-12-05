<?php

namespace App\Providers;
use App\Repositories\TaskRepository;
use Illuminate\Support\ServiceProvider;
use App\Repositories\TaskRepositoryInterface;

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
        //
        $this->app->bind(TaskRepositoryInterface::class, TaskRepository::class);
    }
}
