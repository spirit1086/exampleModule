<?php

namespace App\Helpers\Dates\Facades;

use Illuminate\Support\Facades\Facade;

class DatesFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'dates';
    }
}
