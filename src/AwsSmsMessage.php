<?php

namespace NotificationChannels\AwsSms;

use Illuminate\Support\Arr;

class AwsSmsMessage
{
    // Message structure here
    public $body;
    public $recipient;

    public static function create($body = '')
    {
        return new static($body);
    }

    public function __construct($body = '')
    {
        if (! empty($body)) {
            $this->body = trim($body);
        }
    }
    public function setRecipient($recipient)
    {
        $this->recipient = $recipient;

        return $this;
    }

    public function __toString()
    {
        return $this->body;
    }
}
