<?php

namespace App\Helpers\Minio\Facades;

use Illuminate\Support\Facades\Facade;

class Minio extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'minio';
    }

}
