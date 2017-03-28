<?php

/**
 * Created by PhpStorm.
 * User: ruudnike
 * Date: 2017-03-28
 * Time: 오후 1:48
 */
namespace App\Libraries\Google\Facades;
use Illuminate\Support\Facades\Facade;

class GoogleApiFacade extends Facade{
    protected static function getFacadeAccessor()
    {
        return 'GoogleApi';
    }
}