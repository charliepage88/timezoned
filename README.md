# Timezoned

## What is it?

Laravel trait for converting Eloquent model timestamps according to timezones.

**Warning**

Work in progress - I feel that it's ready for general use but I'm expecting
more bugs to come out, so please test it and report any issues.

## Installation

1. Install through composer:
    ```
    composer require ___/___ @TODO!!!
    ```

1. Package should register itself automatically on Laravel 5.5 or newer. On older please
add this line to the list of providers in `config/app.php` in your application:

    ```
    RyanWeber\Mutators\TimezonedServiceProvider::class,
    ```

1. Publish config file and change according to your requirements.

    ```
    php artisan vendor:publish --provider="RyanWeber\Mutators\TimezonedServiceProvider"
    ```

## Configuration

Config file contains only one key called `user_timezone` and its value is a callback
which should fetch logged in user's timezone. By default it's trying to get it from
`timezone` column in `users` table:

```
'user_timezone' => function() { return \Auth::user()->timezone; }
```

You can publish config file and edit it as much as you like. For example, if you have
all user's preferences stored in settings model, you could change config to:

```
'user_timezone' => function() { return \Auth::user()->settings->timezone; }
```

It just depends on your application. Just always describe it as a callback, even if it
returns static string.

## Author

Catch me on [Twitter](https://twitter.com/rweber7912) or check out my 
[business website](https://picoprime.com).
