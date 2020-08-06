<?php

namespace OptimistDigital\MultiselectField;

use Laravel\Nova\Nova;
use Laravel\Nova\Events\ServingNova;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\File;

class FieldServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Nova::serving(function (ServingNova $event) {
            Nova::script('multiselect-field', __DIR__ . '/../dist/js/multiselect-field.js');
            Nova::style('multiselect-field', __DIR__ . '/../dist/css/multiselect-field.css');
        });

        $this->translations();
    }

    protected function translations()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([__DIR__ . '/../resources/lang' => resource_path('lang/vendor/nova-multiselect-field')], 'translations');
        } else if (method_exists('Nova', 'translations')) {
            $locale = app()->getLocale();
            $fallbackLocale = config('app.fallback_locale');

            if ($this->attemptToLoadTranslations($locale, 'project')) return;
            if ($this->attemptToLoadTranslations($locale, 'local')) return;
            if ($this->attemptToLoadTranslations($fallbackLocale, 'project')) return;
            if ($this->attemptToLoadTranslations($fallbackLocale, 'local')) return;
            $this->attemptToLoadTranslations('en', 'local');
        }
    }

    protected function attemptToLoadTranslations($locale, $from)
    {
        $filePath = $from === 'local'
            ? __DIR__ . '/../resources/lang/' . $locale . '.json'
            : resource_path('lang/vendor/nova-multiselect-field') . '/' . $locale . '.json';

        $localeFileExists = File::exists($filePath);
        if ($localeFileExists) {
            Nova::translations($filePath);
            return true;
        }
        return false;
    }
}
