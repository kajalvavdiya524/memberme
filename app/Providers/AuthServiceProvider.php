<?php

namespace App\Providers;

use App\Services\ScannerGuard;
use App\Services\ScannerUserProvider;
use Auth;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use JsonSchema\Exception\InvalidConfigException;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Auth::provider('scanner-provider',function ($app, array $config){
            if(! isset($config['model'])){
                throw new InvalidConfigException('Please add model in the config/auth for scanner-provider');
            }
            return new ScannerUserProvider($app->make(Hasher::class), $config['model']);
        });

        Auth::extend('scanner', function ($app, $name, array $config) {
            return new ScannerGuard(Auth::createUserProvider($config['provider']),$app->make('request'));
        });
    }
}
