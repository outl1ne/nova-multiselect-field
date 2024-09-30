<?php

namespace Workbench\Database\Factories;

use Orchestra\Testbench\Factories\UserFactory as Factory;
use Workbench\App\Models\User;

/**
 * @tmeplate TModel of \Workbench\App\Models\User
 *
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<TModel>
 */
class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<TModel>
     */
    protected $model = User::class;
}
