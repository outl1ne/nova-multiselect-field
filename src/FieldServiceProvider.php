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

        $this->publishes([__DIR__ . '/../resources/lang' => resource_path('lang/vendor/nova-multiselect')], 'translations');

        if (method_exists('Nova', 'translations')) {
            // Load local translation files
            $localTranslationFiles = File::files(__DIR__ . '/../resources/lang');
            foreach ($localTranslationFiles as $file) {
                Nova::translations($file->getPathName());
            }

            // Load project translation files
            $projectTransFilesPath = resource_path('lang/vendor/nova-multiselect');
            if (File::exists($projectTransFilesPath)) {
                $projectTranslationFiles = File::files($projectTransFilesPath);
                foreach ($projectTranslationFiles as $file) {
                    Nova::translations($file->getPathName());
                }
            }
        }
    }

    public function register()
    {
        //
    }
}
