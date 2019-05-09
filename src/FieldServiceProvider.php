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
    }

    public function register()
    {
        //
    }
}
