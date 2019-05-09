# Nova Multiselect

This [Laravel Nova](https://nova.laravel.com) package adds a multiselect to Nova's arsenal of fields.

## Installation

Install the package in a Laravel Nova project via Composer:

```bash
composer require optimistdigital/nova-multiselect-field
```

## Usage

The field is used similarly to Nova's native Select field.

```php
use OptimistDigital\MultiselectField\Multiselect;

public function fields(Request $request)
{
    return [
      Multiselect
        ::make('Favourite football teams', 'football_teams')
        ->options([
          'liverpool' => 'Liverpool FC',
          'tottenham' => 'Tottenham Hotspur',
          'bvb' => 'Borussia Dortmund',
          'bayern' => 'FC Bayern Munich',
          'barcelona' => 'FC Barcelona',
          'juventus' => 'Juventus FC',
          'psg' => 'Paris Saint-Germain FC',
        ])

        // Optional:
        ->placeholder('Choose football teams')
        ->max(4)
        ->optionsLimit(5)
    ];
}
```

## Options

Possible options you can pass to the field using the option name as a function, ie `->placeholder('Choose peanuts')`.

| Option         | type   | default    | description                                                                                            |
| -------------- | ------ | ---------- | ------------------------------------------------------------------------------------------------------ |
| `options`      | Array  | []         | Options in an array as key-value pairs (`['id' => 'value']`).                                          |
| `placeholder`  | String | Field name | The placeholder string displayed when the field is empty.                                              |
| `max`          | Number | Infinite   | The maximum number of options a user can select.                                                       |
| `optionsLimit` | Number | 1000       | The maximum number of options displayed at once. Other options are still accessible through searching. |

## Credits

-   [Tarvo Reinpalu](https://github.com/Tarpsvo)
-   [shentao/vue-multiselect](https://vue-multiselect.js.org)

## License

This project is open-sourced software licensed under the [MIT license](LICENSE.md).
