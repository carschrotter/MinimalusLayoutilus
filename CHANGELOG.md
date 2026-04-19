# Changelog

All notable changes to this project will be documented in this file.

## [0.9.2] - 2026-04-19

### Fixed
- `Control/ControlDefault`: Added concrete `actionIndex()` implementation to prevent fatal "Cannot call abstract method" error
- `Control/ControlIndex`: Removed illegal `parent::actionIndex()` call on abstract method
- `traits/Instances`: `issetInstance()` was non-static, conflicting with `interfaces\Instances` declaration — added `static`
- `traits/NoInstances`: `isInit()` used `$this` in static context — replaced with `get_called_class()`
- `traits/Prototype`: `Prototype()` declared `static` in trait but non-static in interface; `$_prototype` was undefined — removed `static`, fixed to `$this->_prototype`
- `Error`: `ExceptionEventParms` was constructed before `is_object()` guard — reordered check to prevent fatal error when `getLastException()` returns `null`
- `EventParms/ExceptionEventParms`: `getException()` had double `$this->$this->` — fixed to `$this->_parms['exception']`
- `Programm`: Hardcoded lowercase `'template'` in `makeClassName()` call — changed to `'Template'` after directory rename
- `Template`: Relative namespace `template\EventParms` — changed to `Template\EventParms` after rename

### Added
- `Control/ControlIndex`: Browser language detection (`HTTP_ACCEPT_LANGUAGE`), German/English i18n content via `getI18n()` / `getLang()`
- `View/ViewIndexHtml`: Renders localised project introduction (jumbotron, feature list, Composer install command) via `ControlIndex::getI18n()`
- `templates/template.default.php`: Clean Bootstrap 3 (CDN 3.4.1) layout — navbar, system messages, component slot, footer; all hardcoded personal content removed
- `README.md`: Full project documentation (requirements, quick start, structure, DDEV, dependencies)

---

## [0.9.1] - 2026-04-19

### Added
- `composer.json` — package `mnhcc/minimalus-layoutilus`, PHP `>=5.4`, Composer dependencies
- DDEV project configuration for local development (PHP 5.6)
- `CHANGELOG.md`
- Composer dependencies: `barbushin/php-imap` (require), `raveren/kint` (dev), `firephp/firephp-core` (dev)

### Fixed
- **Critical**: `reqire_once` → `require_once` in `BootstrapHandler::loadLibrary()`
- `extention` / `$_extentions` → `extension` / `$_extensions` throughout (property, methods, local variables, docblocks)
- Public API renamed: `getExtentions()` → `getExtensions()`, `setExtention()` → `setExtension()`, `setExtentions()` → `setExtensions()`, `getExtention()` → `getExtension()`
- `aplication` → `application` in config keys (`initial.php`, `ApplicationConfig`, `Template`)
- Comment typos: `Seperator`, `Bootstraping`, `Erorrohandling`, `autload`, `exitist`, `Type-Identifierf`

### Changed
- `classes/exception/` merged into `classes/Exception/` (PascalCase); namespace declarations corrected
- `classes/template/` → `classes/Template/`, `classes/control/` → `classes/Control/`, `classes/records/` → `classes/Records/`
- Namespace declarations in all moved files updated accordingly
- Empty library placeholder directories removed (`kint/`, `firephp-core/`, `php-imapp/`) in favour of Composer

---

## [0.9.0] - 2013 – 2014

Initial development version. Established core framework structure:
- MVC architecture with `Control`, `View`, `Template` base classes
- Namespace-based autoloading via `BootstrapHandler`
- Event system (`EventManager`, `EventParms`)
- Configurable bootstrapping, error handling (`Error`), routing (`Router`)
- Traits: `Instances`, `NoInstances`, `MNHcC`, `Prototype`, `Event`, `Actions`
- Interfaces: `Instances`, `MNHcC`, `Prototype`, `View`, `Viewable`, `Parameters`
