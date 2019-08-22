# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.1.6] - 22-08-2019

### Changed

- Fix fields casted to array not working by [@jplhomer](https://github.com/jplhomer)

## [1.1.5] - 16-08-2019

### Changed

- Fix support packages that wrap fields like `nova-grid` by using better `$refs` for calculating dropdown position
- Fix JS runtime crash when data in database is invalid (not a JSON array)

## [1.1.4]

### Changed

- Fix fields casted to array not working by [@victorlap](https://github.com/victorlap)

## [1.1.3]

### Changed

- Fixed detail field trying to display empty array as a value

## [1.1.2]

### Added

- Added support for `nullable()` (as requested by [@potentweb](https://github.com/potentweb))

## [1.1.1]

### Changed

- Fixed multiselect field opening towards the bottom even when there's no room for it (by [@marttinnotta](https://github.com/marttinnotta))

## [1.1.0]

### Added

- Added `resolveForPageResponseUsing(callable $callback)` option

### Changed

- Changed the way data is stored - instead of a separated list (string), data is now stored as JSON

## [1.0.0]

Initial release.

### Added

- Basic multiple select field using [vue-multiselect](https://github.com/shentao/vue-multiselect)

[1.1.6]: https://github.com/optimistdigital/nova-multiselect-field/compare/1.1.5...1.1.6
[1.1.5]: https://github.com/optimistdigital/nova-multiselect-field/compare/1.1.4...1.1.5
[1.1.4]: https://github.com/optimistdigital/nova-multiselect-field/compare/1.1.3...1.1.4
[1.1.3]: https://github.com/optimistdigital/nova-multiselect-field/compare/1.1.2...1.1.3
[1.1.2]: https://github.com/optimistdigital/nova-multiselect-field/compare/1.1.1...1.1.2
[1.1.1]: https://github.com/optimistdigital/nova-multiselect-field/compare/1.1.0...1.1.1
[1.1.0]: https://github.com/optimistdigital/nova-multiselect-field/compare/1.0.0...1.1.0
[1.0.0]: https://github.com/optimistdigital/nova-multiselect-field/compare/f0356c8395ddabcacbc2b1ee72558623b04194e1...1.0.0
