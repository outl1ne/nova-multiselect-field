<?php

namespace OptimistDigital\MultiselectField;

use Exception;
use RuntimeException;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;

class Multiselect extends Field
{
    public $component = 'multiselect-field';

    protected $pageResponseResolveCallback;
    protected $saveAsJSON = false;
    protected $resourceClass = null;

    /**
     * Sets the options available for select.
     *
     * @param  array|callable
     * @return \OptimistDigital\MultiselectField\Multiselect
     **/
    public function options($options = [])
    {
        if (is_callable($options)) $options = call_user_func($options);
        $options = collect($options ?? []);

        $isOptionGroup = $options->contains(function ($label, $value) {
            return is_array($label);
        });

        if ($isOptionGroup) {
            $_options = $options
                ->map(function ($value, $key) {
                    return collect($value + ['value' => $key]);
                })
                ->groupBy('group')
                ->map(function ($value, $key) {
                    return ['label' => $key, 'values' => $value->map->only(['label', 'value'])->toArray()];
                })
                ->values()
                ->toArray();

            return $this->withMeta(['options' => $_options]);
        }


        return $this->withMeta([
            'options' => $options->map(function ($label, $value) {
                return ['label' => $label, 'value' => $value];
            })->values()->all(),
        ]);
    }

    public function api($path, $resourceClass)
    {
        $this->resourceClass = $resourceClass;
        if (empty($resourceClass)) throw new Exception('Multiselect requires resourceClass, none provided.');
        if (empty($path)) throw new Exception('Multiselect requires apiUrl, none provided.');

        $this->resolveUsing(function ($value) {
            $this->options([]);
            $value = array_values((array)$value);

            if (empty($value)) return $value;

            // Handle translatable/collection where values are an array of arrays
            if (is_array($value) && is_array($value[0] ?? null)) {
                $value = collect($value)->flatten(1)->toArray();
            }

            try {
                $options = [];
                $modelObj = (new $this->resourceClass::$model);
                $models = $this->resourceClass::$model::whereIn($modelObj->getKeyName(), $value)->get();
                $models->each(function ($model) use (&$options) {
                    $options[$model[$model->getKeyName()]] = (new $this->resourceClass($model))->title();
                });
                $this->options($options);
            } catch (Exception $e) {
            }

            return $value;
        });

        return $this->withMeta(['apiUrl' => $path, 'labelKey' => $resourceClass::$title]);
    }

    public function asyncResource($resourceClass)
    {
        $this->resourceClass = $resourceClass;
        $apiUrl = "/nova-api/{$resourceClass::uriKey()}";
        return $this->api($apiUrl, $resourceClass);
    }

    protected function resolveAttribute($resource, $attribute)
    {
        $singleSelect = $this->meta['singleSelect'] ?? false;
        $value = data_get($resource, str_replace('->', '.', $attribute));

        if ($this->saveAsJSON || $singleSelect) return $value;
        return is_array($value) || is_object($value) ? (array) $value : json_decode($value);
    }

    protected function fillAttributeFromRequest(NovaRequest $request, $requestAttribute, $model, $attribute)
    {
        $singleSelect = $this->meta['singleSelect'] ?? false;
        $value = $request->input($requestAttribute) ?? null;

        if ($singleSelect) {
            $model->{$attribute} = $value;
        } else {
            $model->{$attribute} = $this->saveAsJSON || is_null($value) ? $value : json_encode($value);
        }
    }

    /**
     * Allows the field to save an actual JSON array to a SQL JSON column.
     *
     * @param bool $saveAsJSON
     * @return \OptimistDigital\MultiselectField\Multiselect
     **/
    public function saveAsJSON($saveAsJSON = true)
    {
        $this->saveAsJSON = $saveAsJSON;
        return $this;
    }

    /**
     * Sets the max number of options the user can select.
     *
     * @param int $max
     * @return \OptimistDigital\MultiselectField\Multiselect
     **/
    public function max($max)
    {
        return $this->withMeta(['max' => $max]);
    }

    /**
     * Sets the placeholder value displayed on the field.
     *
     * @param string $placeholder
     * @return \OptimistDigital\MultiselectField\Multiselect
     **/
    public function placeholder($placeholder)
    {
        return $this->withMeta(['placeholder' => $placeholder]);
    }

    /**
     * Sets the maximum number of options displayed at once.
     *
     * @param int $optionsLimit
     * @return \OptimistDigital\MultiselectField\Multiselect
     **/
    public function optionsLimit($optionsLimit)
    {
        return $this->withMeta(['optionsLimit' => $optionsLimit]);
    }

    /**
     * Enables or disables reordering of the field values.
     *
     * @param bool $reorderable
     * @return \OptimistDigital\MultiselectField\Multiselect
     **/
    public function reorderable($reorderable = true)
    {
        return $this->withMeta(['reorderable' => $reorderable]);
    }

    /**
     * Enables the field to be used as a single select.
     *
     * This forces the value saved to be a single value and not an array.
     *
     * @param bool $singleSelect
     * @return \OptimistDigital\MultiselectField\Multiselect
     **/
    public function singleSelect($singleSelect = true)
    {
        return $this->withMeta(['singleSelect' => $singleSelect]);
    }

    /**
     * Enables vue-multiselect's group-select feature which allows the
     * user to select the whole group at once.
     *
     * @param bool $groupSelect
     * @return \OptimistDigital\MultiselectField\Multiselect
     **/
    public function groupSelect($groupSelect = true)
    {
        return $this->withMeta(['groupSelect' => $groupSelect]);
    }

    /**
     * Enable other-field dependency.
     *
     * @param string $otherFieldName
     * @return \OptimistDigital\MultiselectField\Multiselect
     **/
    public function dependsOn($otherFieldName)
    {
        return $this->withMeta(['dependsOn' => $otherFieldName]);
    }

    /**
     * Set dependency options map as a keyed array of options.
     *
     * @param array $options
     * @return \OptimistDigital\MultiselectField\Multiselect
     **/
    public function dependsOnOptions(array $options)
    {
        return $this->withMeta(['dependsOnOptions' => $options]);
    }

    /**
     * Set max selectable value count as a keyed array of numbers.
     *
     * @param array $maxOptions
     * @return \OptimistDigital\MultiselectField\Multiselect
     **/
    public function dependsOnMax(array $maxOptions)
    {
        return $this->withMeta(['dependsOnMax' => $maxOptions]);
    }

    public function resolveResponseValue($value, $templateModel)
    {
        $parsedValue = isset($value) ? ($this->saveAsJSON ? $value : json_decode($value)) : null;
        return is_callable($this->pageResponseResolveCallback)
            ? call_user_func($this->pageResponseResolveCallback, $parsedValue, $templateModel)
            : $parsedValue;
    }

    public function resolveForPageResponseUsing(callable $resolveCallback)
    {
        $this->pageResponseResolveCallback = $resolveCallback;
        return $this;
    }


    /**
     * Makes the field to manage a BelongsToMany relationship.
     *
     * @param string $resourceClass The Nova Resource class for the other model.
     * @return \OptimistDigital\MultiselectField\Multiselect
     **/
    public function belongsToMany($resourceClass, $async = true)
    {
        $model = $resourceClass::$model;
        $primaryKey = (new $model)->getKeyName();

        $this->resolveUsing(function ($value) use ($async, $primaryKey, $resourceClass) {
            $value = collect(array_values($value ?? []))->flatten(1)->pluck($primaryKey);
            if ($async) $this->asyncResource($resourceClass);

            $options = [];
            $models = $async
                ? $resourceClass::$model::whereIn($primaryKey, $value)->get()->filter()
                : $resourceClass::$model::all();
            $models->each(function ($model) use (&$options, $resourceClass) {
                $options[$model[$model->getKeyName()]] = $model[$resourceClass::$title];
            });
            $this->options($options);

            return $value;
        });

        $this->fillUsing(function ($request, $model, $requestAttribute, $attribute) {
            $model::saved(function ($model) use ($attribute, $request) {
                // Validate
                if (!method_exists($model, $attribute)) {
                    throw new RuntimeException("{$model}::{$attribute} must be a relation method.");
                }

                $relation = $model->{$attribute}();

                if (!method_exists($relation, 'sync')) {
                    throw new RuntimeException("{$model}::{$attribute} does not appear to model a BelongsToMany or MorphsToMany.");
                }

                // Sync
                $relation->sync($request->get($attribute) ?? []);
            });
        });

        return $this;
    }

    /**
     * Makes the field to manage a BelongsTo relationship.
     *
     * @param string $resourceClass The Nova Resource class for the other model.
     * @return \OptimistDigital\MultiselectField\Multiselect
     **/
    public function belongsTo($resourceClass, $async = true)
    {
        $this->singleSelect();

        $model = $resourceClass::$model;
        $primaryKey = (new $model)->getKeyName();

        $this->resolveUsing(function ($value) use ($async, $primaryKey, $resourceClass) {
            $value = $value->{$primaryKey} ?? null;
            if ($async) $this->asyncResource($resourceClass);

            $options = [];
            if ($async && isset($value)) {
                $model = $resourceClass::$model::find($value);
                if (isset($model)) $options[$model[$primaryKey]] = $model[$resourceClass::$title];
            } else {
                $models = $resourceClass::$model::all();
                $models->each(function ($model) use (&$options, $resourceClass) {
                    $options[$model[$model->getKeyName()]] = $model[$resourceClass::$title];
                });
            }
            $this->options($options);

            return $value;
        });

        $this->fillUsing(function ($request, $model, $requestAttribute, $attribute) use ($resourceClass) {
            $modelClass = get_class($model);

            // Validate
            if (!method_exists($model, $attribute)) {
                throw new RuntimeException("{$modelClass}::{$attribute} must be a relation method.");
            }

            $relation = $model->{$attribute}();

            if (!method_exists($relation, 'associate')) {
                throw new RuntimeException("{$modelClass}::{$attribute} does not appear to model a BelongsTo relationship.");
            }

            // Sync
            $relation->associate($resourceClass::$model::find($request->get($attribute)));
        });

        return $this;
    }

    public function clearOnSelect($clearOnSelect = true)
    {
        return $this->withMeta(['clearOnSelect' => $clearOnSelect]);
    }
}
