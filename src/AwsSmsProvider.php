<?php

namespace NotificationChannels\AwsSms;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Notification;
use Illuminate\Notifications\ChannelManager;
use Illuminate\Broadcasting\Channel;

class AwsSmsProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $manager = $this->app->make(ChannelManager::class);
        if ($manager) {
            $manager->extend('sms', function ($app) {
                return new AwsSmsChannel(new AwsSms());
            });
            $manager->channel("sms");
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
    }
}
