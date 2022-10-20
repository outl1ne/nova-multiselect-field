# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

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
