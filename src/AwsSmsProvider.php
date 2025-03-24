<?php

namespace NotificationChannels\AwsSms;

use Illuminate\Support\ServiceProvider;

class AwsSmsProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->app->when(Channel::class)
            ->needs(AwsSms::class)
            ->give(function () {
                $config = config('broadcasting.connections.awssms');
                return new AwsSms();
            });
    }

    /**
     * Register the application services.
     */
    public function register()
    {
    }
}
