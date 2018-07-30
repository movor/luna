<?php

namespace Movor\LaravelDbRedirect\Facades;

use Illuminate\Support\Facades\Facade;
use Movor\LaravelDbRedirect\Providers\DbRedirectServiceProvider;

class DbRedirectFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return DbRedirectServiceProvider::$packageName;
    }
}