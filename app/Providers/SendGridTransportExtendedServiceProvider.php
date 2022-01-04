<?php
/**
 * Created by PhpStorm.
 * User: Faisal
 * Date: 11/7/2019
 * Time: 6:22 PM
 */

namespace App\Providers;


use Illuminate\Mail\TransportManager;
use Illuminate\Support\Arr;
use Sichikawa\LaravelSendgridDriver\SendgridTransportServiceProvider;
use Sichikawa\LaravelSendgridDriver\Transport\SendgridTransport;
use GuzzleHttp\Client as HttpClient;

class SendGridTransportExtendedServiceProvider extends SendgridTransportServiceProvider
{
    /**
     * Register the Swift Transport instance.
     *
     * @return void
     */
    public function register()
    {
        $this->app->afterResolving(TransportManager::class, function(TransportManager $manager) {
            $this->extendTransportManager($manager);
        });
    }

    public function extendTransportManager(TransportManager $manager)
    {
        $manager->extend('sendgrid', function() {
            $config = $this->app['config']->get('services.sendgrid', array());
            $client = new HttpClient(Arr::get($config, 'guzzle', []));
            $endpoint = isset($config['endpoint']) ? $config['endpoint'] : null;

            return new SendgridTransport($client, $config['api_key'], $endpoint);
        });
    }
}