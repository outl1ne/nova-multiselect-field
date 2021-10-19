# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [2.3.10] - 2021-10-19

### Changed

- Fixed legacy support (correctly this time)

## [2.3.9] - 2021-10-19

### Changed

- Fixed legacy support for belongsTo associatable query (3.26.1 or earlier)

## [2.3.8] - 2021-10-06

### Changed

- Fixed async display values not being displayed with new `belongsTo` logic

## [2.3.7] - 2021-10-05

### Changed

- Fixed compatibility with Nova versions earlier than `3.26.1`

## [2.3.6] - 2021-10-05

### Changed

- Fixed `relatableQuery` support for async relations

## [2.3.5] - 2021-10-05

### Added

- Added support for relatable queries with `belongsTo` relations
- Added index field resource router link to `belongsTo` relations
- Added detail field resource router link to `belongsTo` relations

### Changed

- Updated packages

## [2.3.4] - 2021-10-01

### Added

- Added support for relatable queries (huge thanks to [@Tim-Frensch](https://github.com/Tim-Frensch))

### Changed

- Updated packages

## [2.3.3] - 2021-09-02

### Added

- Added `->dependsOnOutsideFlexible($fieldName)` function

## [2.3.2] - 2021-09-02

### Changed

- Fixed distinct not working with singleSelect and multiSelect together
- Updated packages

## [2.3.1] - 2021-06-22

### Changed

- Fixed a minor crash
- Updated packages

## [2.3.0] - 2021-06-16

### Added

- Added three new functions
  - `indexDelimiter` - Define the delimiter used for index display
  - `indexValueDisplayLimit` - Define how many values to display at once
  - `indexCharDisplayLimit` - Set char limit for index display value

### Changed

- Updated packages

## [2.2.1] - 2021-05-06

### Removed

- Remove length validator (serves no meaning)

## [2.2.0] - 2021-05-06

### Added

- Improved multiselect initial load time.
  - Computed options are no longer loaded before user opens multiselect
- Added `distinct` options.
  - Disables options that have already been used by other multiselects in the same distinct group.
  - Supports Multi and Singleselect options. Pull request to support async options are welcome.

## [2.1.1] - 2021-04-28

### Changed

- Fixed issue where `$this->resourceClass` was undefined when using `belongsTo` synchronously

## [2.1.0] - 2021-04-27

### Added

- Added Persian(Farsi) translations (thanks to [@FaridAghili](https://github.com/FaridAghili))

### Changed

- Use resource title method (thanks to [@shawnheide](https://github.com/shawnheide))
- Make use of resource `newModel()` function.
- Fixed inverse of nullable not storing an empty array (thanks to [@Ali-Shaikh](https://github.com/Ali-Shaikh))

## [2.0.7] - 2021-02-15

### Changed

- Fixed `belongsToMany` making duplicate queries
- Updated packages

## [2.0.6] - 2021-01-07

### Changed

- Fixed `readonly` not working
- Fixed border color when the field receives an error
- Improved border radius

## [2.0.5] - 2021-01-07

### Changed

- Fixed crash when using `->asyncResource` in an action
- Updated packages

## [2.0.4] - 2021-01-04

### Changed

- Fixed help text element always being rendered
- Updated packages

## [2.0.3] - 2020-12-16

### Changed

- Fixed Resource titles when using `->asyncResource`

## [2.0.2] - 2020-12-16

### Changed

- Fix exception thrown when using `->asyncResource`

## [2.0.1] - 2020-12-15

### Changed

- Theoretical fix to `e.fill` is not a function
- Updated packages

## [2.0.0] - 2020-12-02

### Changed

- Dropped PHP 7.1, Laravel 6 and Nova 2 support

## [1.11.5] - 2020-11-24

### Changed

- Fixed `->belongsToMany` crashing with an invalid relationship method and value

## [1.11.4] - 2020-11-24

### Changed

- Fixed `->belongsTo` crashing with an invalid relationship method and value

## [1.11.3] - 2020-11-18

### Changed

- Fixed `->belongsTo` crashing on create view

## [1.11.2] - 2020-11-18

### Changed

- Fixed help text not being displayed
- Fixed a plausible crash when using `dependsOnOptions`
- Made `belongsTo` and `belongsToMany` async optional (second argument is now a boolean toggling async)
- Updated packages

## [1.11.1] - 2020-10-30

### Changed

- Upgraded nova-translations-loader
- Fix translations publishing

## [1.11.0] - 2020-10-30

### Added

- Added async searching (thanks to [@MarikaMustV](https://github.com/MarikaMustV))
- Added `belongsTo`
- Make `belongsToMany` work asynchronously

### Changed

- Updated packages

## [1.10.3] - 2020-10-22

### Added

- Added Estonian (et) translations
- Replaced translations logic with `nova-translations-loader`

## [1.10.2] - 2020-10-22

### Changed

- Fixed `belongsToMany` in some cases (thanks to [@DanielLavoie90](https://github.com/DanielLavoie90))
- Updated packages

## [1.10.1] - 2020-10-14

### Changed

- Fixed `belongsToMany` in some cases
- Updated packages

## [1.10.0] - 2020-09-25

### Added

- Support for `->belongsToMany()` usage

### Changed

- Updated packages

## [1.9.9] - 2020-09-14

### Changed

- Added Dutch translations (thanks to [@preliot](https://github.com/preliot))

## [1.9.8] - 2020-09-04

### Changed

- Fix invalid `v-for` key
- Updated packages

## [1.9.7] - 2020-09-01

### Changed

- More theoretical fixes to Nova's translation logic crash

## [1.9.6] - 2020-09-01

### Added

- Added `dependsOnMax` option
- Added `nova-flexible-content` support

## [1.9.5] - 2020-08-10

### Changed

- Fixed typo that caused a runtime crash (thanks to [@gmedeiros](https://github.com/gmedeiros))

## [1.9.4] - 2020-08-06

### Changed

- Reworked translations logic

## [1.9.3] - 2020-08-05

### Changed

- More workarounds for Nova's bug that causes runtime crashes
- Added French translations (thanks to [@nonovd](https://github.com/nonovd))

## [1.9.2] - 2020-07-31

### Changed

- Workaround for Nova's bug that causes runtime crashes
- Updated packages

## [1.9.1] - 2020-07-21

### Changed

- Fix bug causing nullable multiselects to save as 'null' string (thanks to [@mstyles](https://github.com/mstyles))
- Updated packages

## [1.9.0] - 2020-04-16

### Added

- Multiselect dependency (`dependsOn`) functionality (thanks to [@alberto-bottarini](https://github.com/alberto-bottarini))

## [1.8.2] - 2020-04-16

### Changed

- Show group name when using option groups and multiple items have the same label
- Fix plausible crash when using `saveAsJSON()` and `singleSelect()` together
- Updated packages

## [1.8.1] - 2020-03-05

### Added

- Support Nova 3.0 in `composer.json` requirements

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
