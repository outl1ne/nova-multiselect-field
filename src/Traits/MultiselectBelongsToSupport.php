<?php

namespace OptimistDigital\MultiselectField\Traits;

use RuntimeException;
use Laravel\Nova\Nova;
use Illuminate\Support\Str;
use Laravel\Nova\TrashedStatus;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\FormatsRelatableDisplayValues;

trait MultiselectBelongsToSupport
{
    use FormatsRelatableDisplayValues;

    public $resourceClass;
    public $display;

    /**
     * Makes the field to manage a BelongsTo relationship.
     *
     * @param string $resourceClass The Nova Resource class for the other model.
     * @return \OptimistDigital\MultiselectField\Multiselect
     **/
    public function belongsTo($resourceClass, $async = true)
    {
        $this->singleSelect();
        $primaryKey =  $resourceClass::newModel()->getKeyName();
        $this->resourceClass = $resourceClass;

        $this->resolveUsing(function ($value) use ($async, $primaryKey, $resourceClass) {
            if ($async) $this->associatableResource($resourceClass);

            $request = app(NovaRequest::class);
            $value = $value->{$primaryKey} ?? null;
            $model = $resourceClass::newModel();

            $models = isset($value)
                ? collect([$model::find($value)])
                : forward_static_call($this->associatableQueryCallable($request, $model, $resourceClass), $request, $model::query())->get();

            $this->setOptionsFromModels($models, $resourceClass);

            $resource = isset($value) ? new $resourceClass($models->first()) : null;
            $this->withMeta([
                'belongsToDisplayValue' => $resource ? (string) $resource->title() : null,
                'belongsToResourceName' => $resource ? $resource::uriKey() : null,
                'viewable' => $resource ? $resource->authorizedToView(request()) : false,
            ]);

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
            $relation->associate($resourceClass::newModel()::find($request->get($attribute)));
        });

        return $this;
    }

    public function associatableResource($resourceClass)
    {
        $resource = Nova::newResourceFromModel($this->resource);
        $apiUrl = "/nova-api/{$resource::uriKey()}/associatable/{$this->attribute}";
        return $this->api($apiUrl, $resourceClass);
    }

    /**
     * Makes the field to manage a BelongsToMany relationship.
     *
     * @param string $resourceClass The Nova Resource class for the other model.
     * @return \OptimistDigital\MultiselectField\Multiselect
     **/
    public function belongsToMany($resourceClass, $async = true)
    {
        $this->resourceClass = $resourceClass;

        $this->resolveUsing(function ($value) use ($async, $resourceClass) {
            if ($async) $this->associatableResource($resourceClass);

            $value = $value ?: collect();
            $request = app(NovaRequest::class);
            $model = $resourceClass::newModel();

            $models = $async
                ? $value
                : forward_static_call($this->associatableQueryCallable($request, $model, $resourceClass), $request, $model::query())->get();

            $this->setOptionsFromModels($models, $resourceClass);

            return $value->map(function ($model) {
                return $model[$model->getKeyName()];
            })->toArray();
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
                $relation->sync($request->get($attribute) ?: []);
            });
        });

        return $this;
    }


    // ------------------------------
    // Helpers from BelongsTo
    // ------------------------------

    /**
     * Build an associatable query for the field.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  bool  $withTrashed
     * @return \Laravel\Nova\Contracts\QueryBuilder
     */
    public function buildAssociatableQuery(NovaRequest $request, $withTrashed = false)
    {
        $model = forward_static_call(
            [$resourceClass = $this->resourceClass, 'newModel']
        );

        $query = Nova::version() >= '3.26.1'
            ? app()->make('Laravel\Nova\Contracts\QueryBuilder', [$resourceClass])
            : new \Laravel\Nova\Query\Builder($resourceClass);

        $request->first === 'true'
            ? $query->whereKey($model->newQueryWithoutScopes(), $request->current)
            : $query->search(
                $request,
                $model->newQuery(),
                $request->search,
                [],
                [],
                TrashedStatus::fromBoolean($withTrashed)
            );

        return $query->tap(function ($query) use ($request, $model) {
            forward_static_call($this->associatableQueryCallable($request, $model), $request, $query, $this);
        });
    }

    /**
     * Get the associatable query method name.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return array
     */
    protected function associatableQueryCallable(NovaRequest $request, $model)
    {
        return ($method = $this->associatableQueryMethod($request, $model))
            ? [$request->resource(), $method]
            : [$this->resourceClass, 'relatableQuery'];
    }

    /**
     * Get the attachable query method name.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return string
     */
    protected function associatableQueryMethod(NovaRequest $request, $model)
    {
        $method = 'relatable' . Str::plural(class_basename($model));

        if (method_exists($request->resource(), $method)) {
            return $method;
        }
    }

    /**
     * Format the given associatable resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  mixed  $resource
     * @return array
     */
    public function formatAssociatableResource(NovaRequest $request, $resource)
    {
        $value = Nova::version() >= '3.25.0'
            ? \Laravel\Nova\Util::safeInt($resource->getKey())
            : $resource->getKey();

        return array_filter([
            'avatar' => $resource->resolveAvatarUrl($request),
            'display' => $this->formatDisplayValue($resource),
            'subtitle' => $resource->subtitle(),
            'value' => $value,
        ]);
    }

    public function shouldReorderAssociatableValues(NovaRequest $request)
    {
        return false;
    }
}
