<?php

namespace App\Providers;

use App\Events\UserSaved;
use App\Listeners\SaveUserBackgroundInformation;
use App\Services\UserService;
use App\Services\UserServiceInterface;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(UserServiceInterface::class, UserService::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Event::listen(UserSaved::class, SaveUserBackgroundInformation::class, 'handle');
    }
}
