<?php

/**
 * @author Ryan Weber <ryan@picoprime.com>
 */

namespace RyanWeber\Mutators;

use Illuminate\Support\ServiceProvider;

class TimezonedServiceProvider extends ServiceProvider
{
    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $source = realpath(__DIR__ . '/../config/timezoned.php');
        $this->publishes([$source => config_path('timezoned.php')]);
        $this->mergeConfigFrom($source, 'timezoned');
    }
}
