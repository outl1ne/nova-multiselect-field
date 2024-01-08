<?php

namespace Outl1ne\MultiselectField\Tests;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Orchestra\Testbench\Concerns\InteractsWithPublishedFiles;
use Orchestra\Testbench\Concerns\WithWorkbench;

class TestCase extends \Orchestra\Testbench\TestCase
{
    use InteractsWithPublishedFiles;
    use WithWorkbench;

    #[\Override]
    protected function getBasePath()
    {
        // Adjust this path depending on where your override is located.
        return __DIR__ . '/../vendor/orchestra/testbench-dusk/laravel';
    }
}
