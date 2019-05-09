<?php

namespace OptimistDigital\MultiselectField;

use Laravel\Nova\Fields\Field;

class Multiselect extends Field
{
    public $component = 'multiselect-field';

    public function options($options)
    {
        return $this->withMeta([
            'options' => collect($options ?? [])->map(function ($label, $value) {
                return is_array($label) ? $label + ['value' => $value] : ['label' => $label, 'value' => $value];
            })->values()->all(),
        ]);
    }

    public function max($max)
    {
        return $this->withMeta(['max' => $max]);
    }
}
