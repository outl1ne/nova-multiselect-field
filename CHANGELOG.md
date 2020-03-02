# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.8.0] - 2020-03-02

### Added

- Added option groups support (see README.md)

### Changed

- Updated packages

## [1.7.4] - 2020-02-25

### Changed

- Improve `nova-flexible-content` support

## [1.7.3] - 2020-02-14

### Changed

- PHP 7.4 support improvements
- Value resolve/save fixes

## [1.7.2] - 2020-02-14

### Changed

- Fix mysterious ternary operator error w/ nullable

## [1.7.1] - 2020-02-13

### Changed

- Fix `singleSelect()` not saving correctly

## [1.7.0] - 2020-02-13

### Changed

- Improved validation support (`min`, `max`, `array` etc)
- Updated packages

## [1.6.2] - 2020-01-28

### Changed

- Updated packages

## [1.6.1] - 2019-12-05

### Changed

- `->options($options)` method now accepts callable type

## [1.6.0] - 2019-11-13

### Added

- Added option to overwrite the detail field value component (`NovaMultiselectDetailFieldValue`)

## [1.5.0] - 2019-11-05

### Added

- Added optional `singleSelect` support for picking single values through the same searchable input

## [1.4.0] - 2019-11-04

### Added

- Added optional reordering functionality that allows the selected items to be arranged in a new sequence
  - The feature can be enabled on a per field basis with `->reorderable()` or `->reorderable(true)`

## [1.3.5] - 2019-10-14

### Changed

- Fix loading and registering translation files

## [1.3.4] - 2019-10-10

### Changed

- Fix values not being displayed on Index view
- Fix missing translation on Index view (n items selected)
- Fix invalid `is_callable` call in ServiceProvider

## [1.3.3] - 2019-10-02

### Changed

- Fix values not displaying on DetailField (thanks to [@CristianGiordano](https://github.com/CristianGiordano))

## [1.3.2] - 2019-09-15

### Changed

- Fix Composer crash due to incorrect capitalization of `Nova::translations` method call

## [1.3.1] - 2019-09-13

### Changed

- Fix `resolveResponseValue` not working with `saveAsJSON`

## [1.3.0] - 2019-09-10

### Added

- Added `saveAsJSON` option which allows the field to save the value as a SQL JSON array
- Added translation option

### Changed

- Undo saving value as not an array when the field max is set to 1

## [1.2.0] - 2019-09-10

### Changed

- Do not save value as an array when field max is set to 1

## [1.1.6] - 2019-08-22

### Changed

- Fix fields casted to array not working by [@jplhomer](https://github.com/jplhomer)

## [1.1.5] - 2019-08-16

### Changed

- Fix support packages that wrap fields like `nova-grid` by using better `$refs` for calculating dropdown position
- Fix JS runtime crash when data in database is invalid (not a JSON array)

## [1.1.4] - 2019-08-16

### Changed

- Fix fields casted to array not working by [@victorlap](https://github.com/victorlap)

## [1.1.3] - 2019-08-16

### Changed

- Fixed detail field trying to display empty array as a value

## [1.1.2] - 2019-08-16

### Added

- Added support for `nullable()` (as requested by [@potentweb](https://github.com/potentweb))

## [1.1.1] - 2019-08-16

### Changed

- Fixed multiselect field opening towards the bottom even when there's no room for it (by [@marttinnotta](https://github.com/marttinnotta))

## [1.1.0] - 2019-08-16

### Added

- Added `resolveForPageResponseUsing(callable $callback)` option

### Changed

- Changed the way data is stored - instead of a separated list (string), data is now stored as JSON

## [1.0.0] - 2019-08-16

Initial release.

### Added

- Basic multiple select field using [vue-multiselect](https://github.com/shentao/vue-multiselect)

[1.8.0]: https://github.com/optimistdigital/nova-multiselect-field/compare/1.7.4...1.8.0
[1.7.4]: https://github.com/optimistdigital/nova-multiselect-field/compare/1.7.3...1.7.4
[1.7.3]: https://github.com/optimistdigital/nova-multiselect-field/compare/1.7.2...1.7.3
[1.7.2]: https://github.com/optimistdigital/nova-multiselect-field/compare/1.7.1...1.7.2
[1.7.1]: https://github.com/optimistdigital/nova-multiselect-field/compare/1.7.0...1.7.1
[1.7.0]: https://github.com/optimistdigital/nova-multiselect-field/compare/1.6.2...1.7.0
[1.6.2]: https://github.com/optimistdigital/nova-multiselect-field/compare/1.6.1...1.6.2
[1.6.1]: https://github.com/optimistdigital/nova-multiselect-field/compare/1.6.0...1.6.1
[1.6.0]: https://github.com/optimistdigital/nova-multiselect-field/compare/1.5.0...1.6.0
[1.5.0]: https://github.com/optimistdigital/nova-multiselect-field/compare/1.4.0...1.5.0
[1.4.0]: https://github.com/optimistdigital/nova-multiselect-field/compare/1.3.5...1.4.0
[1.3.5]: https://github.com/optimistdigital/nova-multiselect-field/compare/1.3.4...1.3.5
[1.3.4]: https://github.com/optimistdigital/nova-multiselect-field/compare/1.3.3...1.3.4
[1.3.3]: https://github.com/optimistdigital/nova-multiselect-field/compare/1.3.2...1.3.3
[1.3.2]: https://github.com/optimistdigital/nova-multiselect-field/compare/1.3.1...1.3.2
[1.3.1]: https://github.com/optimistdigital/nova-multiselect-field/compare/1.3.0...1.3.1
[1.3.0]: https://github.com/optimistdigital/nova-multiselect-field/compare/1.2.0...1.3.0
[1.2.0]: https://github.com/optimistdigital/nova-multiselect-field/compare/1.1.6...1.2.0
[1.1.6]: https://github.com/optimistdigital/nova-multiselect-field/compare/1.1.5...1.1.6
[1.1.5]: https://github.com/optimistdigital/nova-multiselect-field/compare/1.1.4...1.1.5
[1.1.4]: https://github.com/optimistdigital/nova-multiselect-field/compare/1.1.3...1.1.4
[1.1.3]: https://github.com/optimistdigital/nova-multiselect-field/compare/1.1.2...1.1.3
[1.1.2]: https://github.com/optimistdigital/nova-multiselect-field/compare/1.1.1...1.1.2
[1.1.1]: https://github.com/optimistdigital/nova-multiselect-field/compare/1.1.0...1.1.1
[1.1.0]: https://github.com/optimistdigital/nova-multiselect-field/compare/1.0.0...1.1.0
[1.0.0]: https://github.com/optimistdigital/nova-multiselect-field/compare/f0356c8395ddabcacbc2b1ee72558623b04194e1...1.0.0
