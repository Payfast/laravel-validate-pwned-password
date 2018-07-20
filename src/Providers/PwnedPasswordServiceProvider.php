<?php

namespace PayFast\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class PwnedPasswordServiceProvider extends ServiceProvider
{
    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('pwnedpassword', 'PayFast\Lib\PwnedPassword@validate');
        Validator::replacer('pwnedpassword', 'PayFast\Lib\PwnedPassword@message');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['PayFast\\Providers\\PwnedPasswordServiceProvider'];
    }
}
