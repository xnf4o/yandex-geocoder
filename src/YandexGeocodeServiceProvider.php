<?php namespace Yandex\Geocode;

use Illuminate\Support\ServiceProvider;

class YandexGeocodeServiceProvider extends ServiceProvider
{
    /**
     *
     */
    public function boot()
    {
        $configPath = __DIR__ . '/config/yandex-geocoder.php';
        $this->publishes([$configPath => config_path('yandex-geocoder.php')], 'yandex-geocoding-config');
    }

    /**
     *
     */
    public function register()
    {
        $this->app->bind('yandexGeocoding', function () {
            return new Api;
        });

    }

}
