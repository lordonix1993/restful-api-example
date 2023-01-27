<?php
namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class LocalizationFacade extends Facade {
    protected static function getFacadeAccessor()
    {
        return 'Localization';
    }
}
