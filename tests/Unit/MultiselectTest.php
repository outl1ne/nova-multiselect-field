<?php

namespace Outl1ne\MultiselectField\Tests\Unit;

use Illuminate\Support\Arr;
use Outl1ne\MultiselectField\Multiselect;
use Outl1ne\MultiselectField\Tests\TestCase;

class MultiselectTest extends TestCase
{
    public function test_it_should_support_array_of_options(): void
    {
        $field = Multiselect::make('Football teams', 'football-teams')
            ->options([
                'liverpool' => 'Liverpool FC',
                'tottenham' => 'Tottenham Hotspur',
            ]);

        $options = Arr::get($field->meta(), 'options');

        $this->assertTrue(count($options) == 2);
        $this->assertTrue(count(array_keys($options[0])) == 2);
        $this->assertTrue($options[0] == ['label' => 'Liverpool FC', 'value' => 'liverpool']);
    }

    public function test_it_should_support_callable_function(): void
    {
        $callable_options = fn () => ['cat', 'dog'];

        $field = Multiselect::make('Pets', 'pets')
            ->options($callable_options);

        $options = Arr::get($field->meta(), 'options');

        $this->assertTrue(count($options) === 2);
        $this->assertTrue($options[0] == ['label' => 'cat', 'value' => 0]);
    }

    public function test_it_should_support_array_of_group_options(): void
    {
        $field = Multiselect::make('size', 'size')
            ->options([
                'MS' => ['label' => 'Small', 'group' => 'Men Sizes'],
                'MM' => ['label' => 'Medium', 'group' => 'Men Sizes'],
                'WS' => ['label' => 'Small', 'group' => 'Women Sizes'],
                'WM' => ['label' => 'Medium', 'group' => 'Women Sizes'],
            ]);

        $options = Arr::get($field->meta(), 'options');

        $this->assertTrue(count($options) == 2);
        $this->assertTrue(array_keys($options[0]) == ['label', 'values']);
        $this->assertTrue(count($options[0]['values']) == 2);
        $this->assertTrue(Arr::has($options[0]['values'][0], ["label", "value"]));
    }
}
