# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [4.5.6] - 29-02-2024

### Changed

- Fixed issue with selected values not being shown in some cases

## [4.5.5] - 27-02-2024

### Changed

- Fixed issue with (soft-)deleted models throwing an exception when rendering field

## [4.5.1] - 27-02-2024

### Changed

- Fixed issue with really long fields with a lot of values not allowing removing items using X

## [4.5.0] - 17-10-2023

### Added

- Added Nova Devtool (immensely improves first set-up developer experience) (huge thanks to [@crynobone](https://github.com/crynobone))

### Changed

- Fixed UI issues relating to multiselect fields inside action modals

## [4.4.2] - 25-08-2023

### Changed

- Fixed a bug with fillIfVisible (thanks to [@LorenzoAlu](https://github.com/LorenzoAlu) and [@muhammadsaeedparacha](https://github.com/muhammadsaeedparacha))

## [4.4.1] - 10-08-2023

### Changed

- Fixed crash with empty belongsToMany

## [4.4.0] - 10-08-2023

### Changed

- Bumped minimum Nova version to 4.27 due to inner method visibility change inside Nova
- Fixed resolveDefaultValue visibility
- Fixed a bug when using show() and hide() inside dependsOn (thanks to [@LorenzoAlu](https://github.com/LorenzoAlu))
- Fixed fillIfVisible running even when the field was not visible (thanks to [@puzzledmonkey](https://github.com/puzzledmonkey))

## [4.3.6] - 24-07-2023

### Changed

- Improved .gitattributes to reduce vendor size

## [4.3.5] - 30-06-2023

### Changed

- Fixed issue with Laravel Octane observer leaks
- Fixed issue with some null default values

## [4.3.4] - 13-06-2023

### Changed

- Fixed Nova 4.25 support (UndefinedValue class as new default value)

## [4.3.3] - 31-05-2023

### Changed

- Fixed dependsOn changes not propagating further than one layer down

## [4.3.2] - 30-05-2023

### Changed

- Fixed unintended crash with null defaultValue

## [4.3.1] - 30-05-2023

### Changed

- Fixed unnecessary group labels on form view

## [4.3.0] - 29-05-2023

### Added

- Added BelongsToMany default() support
  - Default value should be a model or an array/collection of models
- Added Slovak language (thanks to [@wamesro](https://github.com/wamesro))

### Changed

- Fixed dependsOnOptions changes not propagating further than one layer down
- Fixed PHP arrow fn usage (thanks to [@Alvaro-Vidal-Azevedo-Pinheiro](https://github.com/Alvaro-Vidal-Azevedo-Pinheiro))
- Fixed attribute usage when filling field (thanks to [@lonnylot](https://github.com/lonnylot))
- Updated packages

## [4.2.3] - 23-11-2022

### Changed

- Fixed multi-word resource name in belongsToMany relationship (thanks to [@mrleblanc101](https://github.com/mrleblanc101))
- Fixed group options dark mode style issues (thanks to [@mrleblanc101](https://github.com/mrleblanc101))
- BelongsTo resolve performance improvements
- Improved indexCharDisplayLimit support
- Updated packages

## [4.2.2] - 21-10-2022

### Changed

- Fixed belongsToMany detail view link styles (thanks to [@mrleblanc101](https://github.com/mrleblanc101))
- Fixed disable state opacity (thanks to [@mrleblanc101](https://github.com/mrleblanc101))
- Fixed missing nova-assets post-autoload command
- Fixed group options dark mode style issues

## [4.2.1] - 20-10-2022

### Changed

- Fixed dark mode not always applying when using `system` theme setting

## [4.2.0] - 20-10-2022

### Added

- Added `resourceKeyName` helper function to Multiselect which allows specifying a custom key name for resource selects
- Added model links support to BelongsToMany relationship field (thanks to [@mrleblanc101](https://github.com/mrleblanc101))

### Changed

- Fixed `readOnly` not reacting to dependsOn changes (thanks to [@mrleblanc101](https://github.com/mrleblanc101))
- Fixed `readOnly` field styles (thanks to [@mrleblanc101](https://github.com/mrleblanc101))

## [4.1.1] - 12-10-2022

### Changed

- Added `resourceId` to associatable request

## [4.1.0] - 05-10-2022

### Added

- Nova's `dependsOn` support

### Changed

- Use `nova-kit/nova-packages-tool` (thanks to [@crynobone](https://github.com/crynobone))
- Fixed UI issue with flexible content (thanks to [@anand-patel](https://github.com/anand-patel))
- Updated packages

## [4.0.10] - 28-07-2022

### Changed

- Fixed an UI issue with border colors on dark mode

## [4.0.9] - 27-07-2022

### Changed

- Fixed an issue with empty model causing an exception in the `shouldSaveAsJSON` function

## [4.0.8] - 20-07-2022

### Changed

- Fixed reorder not working due to wrong vue-draggable version
- Fixed single select color in light mode

## [4.0.7] - 20-07-2022

### Added

- Added ability to override option tag for form field (thanks to [@gavro](https://github.com/gavro))

### Changed

- UI compactness improvements
- Updated packages

## [4.0.6] - 19-07-2022

### Changed

- Made UI more compact
- Fixed clear all button overlapping with selected items
- Updated packages

## [4.0.5] - 09-06-2022

### Changed

- Fixed colors that broke with Nova upgrades

## [4.0.4] - 24-05-2022

### Changed

- Make form field class name more unique
- Fix compatibility issue with Flexible field (thanks to [@alancolant](https://github.com/alancolant))

## [4.0.3] - 13-05-2022

### Changed

- Fixed exception with nova-settings

## [4.0.2] - 29-04-2022

### Changed

- Fixed belongsTo index field

## [4.0.1] - 29-04-2022

### Changed

- Use Outl1ne translations loader 5.0
- Re-add support for Laravel 8.0 and PHP 8.0

## [4.0.0] - 22-04-2022

**NB! Namespace has changed from OptimistDigital to Outl1ne**

Please rename `optimistdigital/nova-multiselect-field` to `outl1ne/nova-multiselect-field` in `composer.json`, use version `^4.0` and run `composer update`.

Then change `use OptimistDigital\MultiselectField\Multiselect` to `use Outl1ne\MultiselectField\Multiselect`. Thanks!

### Changed

- Changed namespace from OptimistDigital to Outl1ne
- Fixed light/dark mode support
- Updated packages

## [3.0.1] - 08-04-2022

### Changed

- Fixed taggable info text colors

## [3.0.0] - 08-04-2022

### Added

- Nova 4 support
- Fully compatible with light and dark modes

### Changed

- Dropped Laravel 7 and 8 support
- Dropped PHP 7.X support
- Dropped Nova 3 support
