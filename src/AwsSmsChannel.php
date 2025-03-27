<?php

namespace NotificationChannels\AwsSms;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Notifications\Events\NotificationFailed;
use NotificationChannels\AwsSms\Exceptions\CouldNotSendNotification;
use Illuminate\Notifications\Notification;

class AwsSmsChannel
{
    private $dispatcher;
    private $client;

    public function __construct(AwsSms $client, Dispatcher $dispatcher = null)
    {
        $this->client = $client;
        $this->dispatcher = $dispatcher;
    }

    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     *
     * @throws \NotificationChannels\:channel_namespace\Exceptions\CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification)
    {
        $messages = $notification->toSms($notifiable);

        if (!is_array($messages)) {
            if (is_string($messages)) {
                $messages = AwsSmsMessage::create($messages);
            }
            $messages = [$messages];
        }

        try {
            foreach ($messages as $message) {
                if (empty($message->recipient) && ($to = $notifiable->routeNotificationFor('sms', $notification))) {
                    $message->setRecipient($to);
                }

                $data = $this->client->send($message);

                if ($this->dispatcher !== null) {
                    $this->dispatcher->dispatch('aws-sms', [$notifiable, $notification, $data]);
                }
            }
        }
        catch (CouldNotSendNotification $e) {
            if ($this->dispatcher !== null) {
                $this->dispatcher->dispatch(
                    new NotificationFailed(
                        $notifiable,
                        $notification,
                        'aws-sms',
                        $e->getMessage()
                    )
                );
            }
        }
    }
}
