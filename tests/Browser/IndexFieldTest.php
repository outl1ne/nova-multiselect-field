<?php

namespace Outl1ne\MultiselectField\Tests\Browser;

use Laravel\Dusk\Browser;
use Laravel\Nova\Testing\Browser\Pages\Detail;
use Outl1ne\MultiselectField\Tests\BrowserTestCase;
use Workbench\App\Models\User;

class IndexFieldTest extends BrowserTestCase
{
    public function test_index_field()
    {
        $user = User::first();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs(1)
                ->visit('/')
                ->pause(50000);
        });
    }
}
