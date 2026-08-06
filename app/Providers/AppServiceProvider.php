<?php

namespace App\Providers;

use App\Services\GreenApi\GreenApiClient;
use App\Services\OneC\OneCClient;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(OneCClient::class, function ($app) {
            $config = $app['config']->get('services.onec');

            return new OneCClient(
                baseUrl: $config['base_url'],
                login: $config['login'],
                password: (string) $config['password'],
                timeout: $config['timeout'],
                verify: $config['verify_ssl'],
            );
        });

        $this->app->singleton(GreenApiClient::class, function ($app) {
            $config = $app['config']->get('services.green_api');

            return new GreenApiClient(
                apiUrl: $config['api_url'],
                idInstance: (string) $config['id_instance'],
                apiToken: (string) $config['api_token'],
                timeout: $config['timeout'],
                verify: $config['verify_ssl'],
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
