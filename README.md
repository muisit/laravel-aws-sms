# Laravel Notification channel for sending SMS through AWS

## Contents

This package is based on the Laravel notification channels skeleton: https://github.com/laravel-notification-channels/skeleton


## Installation

This package uses the Laravel AWS interface:

`composer require aws/aws-sdk-php aws/aws-sdk-php-laravel`

Then install the package from the repository:

`composer require muisit/laravel-aws-sms:dev-master --prefer-dist`

### Setting up the service

The service provider should be loaded automatically. If not, add the service provider to your `app` configuration:

```php
'providers' => [
        ...
        Aws\Laravel\AwsServiceProvider::class,
        NotificationChannels\AwsSms\AwsSmsProvider::class,
        ...
    ],    
```

Configure the service using settings in the `config/services.php` file:

```php
    ...
    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_REGION', 'us-east-1'),
    ],

    'awssms' => [
        'topic' => env('AWS_SMS_TOPIC'),
        'type' => env('AWS_SMS_TYPE', 'Transactional'), // Optional: Choose between 'Transactional' or 'Promotional'
        'senderid' => env('AWS_SMS_SENDERID', env('APP_NAME')) // The Sender ID can be up to 11 characters long.
    ]
    ...
```

## Usage

This package extends Laravel with an `sms` channel. You can send to this channel by routing your notifications through `sms` in a similar way as e-mail messages are sent. Make sure to add a `via` method to your notification:

```php
    ...
    public function via($notifiable)
    {
        return ['sms'];
    }
    ...
```

The notification should then implement a `toSms` method that returns a `NotificationChannels\AwsSms\AwsSmsMessage`:

```php
use NotificationChannels\AwsSms\AwsSmsMessage;
    ...
    public function toSms($notifiable)
    {
        $message = new AwsSmsMessage($body);
        $message->setRecipient($notifiable->routeNotificationFor('sms'));
        return $message;
    }
    ...
```

The notifiable can then implement the `routeNotificationFor` method to return the recipient phone numbers.

You can return a list of `AwsSmsMessage` objects to send out several SMS text messages at once.

### Available Message methods

The `recipient` should be an international phone number: `+<country code>-<internal country code>`

The `body` of the message contains the SMS text. Long texts are automatically cut in smaller pieces, but incur higher transaction costs.


## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Security

If you discover any security related issues, please email laravel@muisit.nl instead of using the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Michiel Uitdehaag](https://github.com/muisit)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
