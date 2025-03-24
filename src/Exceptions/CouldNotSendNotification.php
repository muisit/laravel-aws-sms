<?php

namespace NotificationChannels\AwsSms\Exceptions;

class CouldNotSendNotification extends \Exception
{
    public static function serviceRespondedWithAnError($response)
    {
        return new static("Could not send message through AWS SNS");
    }
}
