# Timezoned

## What is it?

Laravel trait for converting Eloquent model timestamps according to timezones.

**Work in progress** - I feel that it's ready for general use but I'm expecting
more bugs to come out, so please test it and report any issues.

## Installation

1. Install through composer:
    ```
    composer require rweber7912/timezoned:dev-master
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

## Usage

Add `RyanWeber\Mutators\Timezoned` trait to every model that should use timezones.
For example to use timezones in your Example model edit `Example.php` like this:

```
use Illuminate\Database\Eloquent\Model;
use RyanWeber\Mutators\Timezoned; // add this line +++

class Example extends Model
{
    use Timezoned; // and this one too! +++
```

By default this trait will process every field listed in `$dates` property on your model,
so that would be `created_at` and `updated_at` as well as all your date/time columns
listed in `$dates` property.

If you want to restrict timezones swap to selected columns, you can create new property
`$timezoned` in your model and list columns that should be processed by Timezoned trait:

```
protected $timezoned = ['processed_at', 'updated_at'];
```

To stop processing date/times by Timezoned trait just remove it from `use` or set 
`timezoned` to empty array.

## Author

Catch me on [Twitter](https://twitter.com/rweber7912) or check out my 
[business website](https://picoprime.com).
