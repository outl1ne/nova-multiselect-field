<?php

namespace OptimistDigital\MultiselectField;

use Laravel\Nova\Nova;
use Laravel\Nova\Events\ServingNova;
use Illuminate\Support\ServiceProvider;

class FieldServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Nova::serving(function (ServingNova $event) {
            Nova::script('multiselect-field', __DIR__ . '/../dist/js/multiselect-field.js');
            Nova::style('multiselect-field', __DIR__ . '/../dist/css/multiselect-field.css');
        });


        $localTranslationsFilePath = __DIR__ . '/../resources/lang/en.json';
        $translationsFilePath = resource_path('lang/vendor/nova-multiselect/en.json');
        $this->publishes([$localTranslationsFilePath => $translationsFilePath,], 'translations');

        if (is_callable(Nova::translations)) {
            Nova::translations($localTranslationsFilePath);
            if (file_exists($translationsFilePath)) Nova::translations($translationsFilePath);
        }
    }

    public function register()
    {
        //
    }
}
