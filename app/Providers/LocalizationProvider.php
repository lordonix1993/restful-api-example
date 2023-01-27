<?php
namespace App\Providers;
use Illuminate\Support\ServiceProvider;

class LocalizationProvider extends ServiceProvider {
    public function register() {
        $this->app->bind('Localization', 'App\Services\LocalizationService');
    }
}
