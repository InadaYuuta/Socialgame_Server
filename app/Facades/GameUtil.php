<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class GameUtil extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'GameUtil';
    }
}