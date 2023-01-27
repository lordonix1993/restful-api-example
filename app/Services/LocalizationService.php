<?php
namespace App\Services;

class LocalizationService
{
    public function locale() {
        $locale = request()->segment(3, '');

        if($locale && in_array($locale, config('app.locales'))) {
            return $locale;
        }
        return '';
    }
}
