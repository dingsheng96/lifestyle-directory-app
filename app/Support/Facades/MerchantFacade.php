<?php

namespace App\Support\Facades;

use Illuminate\Support\Facades\Facade;

class MerchantFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'merchantFacade';
    }
}
