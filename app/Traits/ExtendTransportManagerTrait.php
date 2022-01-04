<?php
namespace App\Traits;

use Illuminate\Mail\TransportManager;
use GuzzleHttp\Client as HttpClient;
use Illuminate\Support\Arr;
use Sichikawa\LaravelSendgridDriver\Transport\SendgridTransport;

trait ExtendTransportManagerTrait {

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