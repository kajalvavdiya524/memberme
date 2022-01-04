<?php

namespace App\Providers;

use App\Member;
use App\MemberViewLog;
use App\Observers\MemberObserver;
use App\Observers\MemberViewLogObserver;
use App\repositories\MemberRepository;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        if(config('app.env') === 'production' || config('app.env') === 'beta') {
            \URL::forceScheme('https');
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment() !== 'production') {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }
        $this->app->singleton(MemberRepository::class, function ($app) {
            return new MemberRepository();
        });
    }
}
