<?php

namespace NotificationChannels\AwsSms;

use Exception;
use NotificationChannels\AwsSms\Exceptions\CouldNotSendNotification;
use Aws\Sns\SnsClient;
use Aws\Pinpoint\PinpointClient;

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
            $awsRegion = config('services.ses.region');
            $awsAccessKeyId = config('services.ses.key');
            $awsSecretAccessKey = config('services.ses.secret');
            $topic = config('services.awssms.topic');
            $type = config('services.awssms.type');
            $senderid = config('services.awssms.senderid');

            $credentials = [
                'region' => $awsRegion,
                'version' => 'latest',
                'credentials' => [
                    'key' => $awsAccessKeyId,
                    'secret' => $awsSecretAccessKey,
                ],
            ];

            $sns = new SnsClient($credentials);

            $attributes = [
                'AWS.SNS.SMS.SMSType' => [
                    'DataType' => 'String',
                    'StringValue' => $type, // Optional: Choose between 'Transactional' or 'Promotional'
                ]
            ];
            if (!empty($senderid)) {
                $attributes['AWS.SNS.SMS.SenderID'] = [
                    'DataType' => 'String',
                    'StringValue' => $senderid
                ];
            }

            // publish method of the SnsClient is called to send the SMS message
            $response = $sns->publish([
                'TopicARN' => $topic,
                'Message' => $message->body,
                'PhoneNumber' => $message->recipient,
                'MessageAttributes' => $attributes
            ]);
            return json_encode($response);
        }
        catch (Exception $exception) {
            throw CouldNotSendNotification::serviceRespondedWithAnError($exception);
        }
    }
}
