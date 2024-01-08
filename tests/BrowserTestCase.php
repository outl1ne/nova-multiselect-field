<?php

namespace Outl1ne\MultiselectField\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\Concerns\WithWorkbench;

class BrowserTestCase extends \Orchestra\Testbench\Dusk\TestCase
{
    use WithWorkbench;
    use RefreshDatabase;

    #[\Override]
    protected function getBasePath()
    {
        return __DIR__ . '/../vendor/orchestra/testbench-dusk/laravel';
    }
}
