<?php

namespace Outl1ne\MultiselectField;

use Laravel\Nova\Nova;
use Laravel\Nova\Events\ServingNova;
use Illuminate\Support\ServiceProvider;
use Outl1ne\NovaTranslationsLoader\LoadsNovaTranslations;

class FieldServiceProvider extends ServiceProvider
{
    use LoadsNovaTranslations;

    public function boot()
    {
        Nova::serving(function (ServingNova $event) {
            Nova::script('multiselect-field', __DIR__ . '/../dist/js/entry.js');
            Nova::style('multiselect-field', __DIR__ . '/../dist/css/entry.css');
        });

        $this->loadTranslations(__DIR__ . '/../resources/lang', 'nova-multiselect-field', true);
    }
}
