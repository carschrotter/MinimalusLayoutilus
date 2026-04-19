# Changelog

All notable changes to this project will be documented in this file.

## [0.9.0] - Unreleased

### Added
- `composer.json` — package installable via Composer, PHP `>=5.4` requirement
- DDEV project configuration for local development (PHP 5.6)
- Composer dependencies: `barbushin/php-imap`, `raveren/kint` (dev), `firephp/firephp-core` (dev)

### Fixed
- **Critical bug**: `reqire_once` → `require_once` in `BootstrapHandler` (`loadLibrary`)
- Typo `aplication` → `application` in config keys (`initial.php`, `ApplicationConfig`, `Template`)
- Typo `extention`/`$_extentions` → `extension`/`$_extensions` throughout (property, local variables, docblocks)
- Renamed public API methods: `getExtentions()` → `getExtensions()`, `setExtention()` → `setExtension()`, `setExtentions()` → `setExtensions()`, `getExtention()` → `getExtension()`
- Comment typos: `Seperator` → `Separator`, `Bootstraping` → `Bootstrapping`, `Erorrohandling` → `Error Handling`, `autload` → `autoload`, `exitist` → `existing`, `Type-Identifierf` → `Type-Identifier`

### Changed
- Moved `classes/exception/` → `classes/Exception/` (PascalCase, consistent with other subdirs)
- Renamed `classes/template/` → `classes/Template/`, `classes/control/` → `classes/Control/`, `classes/records/` → `classes/Records/`
- Fixed namespace declarations in moved/renamed files accordingly
- Removed empty library placeholder directories (`kint/`, `firephp-core/`, `php-imapp/`) in favour of Composer
