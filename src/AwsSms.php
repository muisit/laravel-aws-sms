<?php

namespace NotificationChannels\AwsSms;

use Exception;
use NotificationChannels\AwsSms\Exceptions\CouldNotSendNotification;
use Aws\Sns\SnsClient;

class AwsSms
{
    /**
     * AwsSms constructor.
     */
    public function __construct()
    {
    }

    /**
     * Send the Message.
     * @param AwsSmsMessage $message
     * @return
     * @throws CouldNotSendNotification
     */
    public function send(AwsSmsMessage $message)
    {
        try {
            $sns = new SnsClient([
                'region' => env('AWS_REGION'),
                'version' => 'latest',
                'credentials' => [
                    'key' => env('AWS_ACCESS_KEY_ID'),
                    'secret' => env('AWS_SECRET_ACCESS_KEY'),
                ],
            ]);
            // publish method of the SnsClient is called to send the SMS message
            $response = $sns->publish([
                'Message' => $message->body,
                'PhoneNumber' => $message->recipient,
            ]);
            return json_encode($response);
        }
        catch (Exception $exception) {
            throw CouldNotSendNotification::serviceRespondedWithAnError($exception);
        }
    }
}
