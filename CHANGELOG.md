# Changelog

All notable changes to this project will be documented in this file.

## [0.9.3] - 2026-04-25

### Changed
- Framework classes extracted into independent Composer packages (`mnhcc/ml-core`, `mnhcc/ml-bugcatcher`, `mnhcc/ml-mvc`); project now consumes them as dependencies
- `initial.php` slimmed to project-specific bootstrap — framework setup delegated to `BootstrapHandler::initial()`
- `ext-pdo` and `ext-imap` moved from `require` to `suggest`; neither is mandatory for all use cases
- Project description corrected: "example application" replaced by actual template-engine philosophy
- `index.php`: namespace `mnhcc\example` → `mnhcc\minimaluslayoutilus`

### Added
- Packagist deployment: `composer.local.json` (dev, path repos to sibling `../ml-*`) / `composer.json` (dist, auto-generated)
- Pre-commit Git hook (`.githooks/pre-commit`) reads current tag from each sibling repo and writes the matching `^major.minor` constraint into `composer.json`
- DDEV: `COMPOSER=composer.local.json` set as `web_environment` so `ddev composer` resolves local packages automatically; `ddev test` custom command; `post-start` hook activates `.githooks` via `git config core.hooksPath`
- DDEV opt-in imap: `.ddev/config.imap.yaml.dist` → copy to `config.imap.yaml` + `ddev restart`
- GitHub Actions CI: PHP 5.6 / 7.0 / 7.1 matrix; sibling-repo checkout; path repos injected via `composer config`
- Root `phpunit.xml.dist` aggregates all three package test suites from `vendor/mnhcc/*/tests/Unit`

### Fixed
- `templates/error/template.default.php`: 404 page evaluated `$_GET['error']` instead of the in-scope `$code` variable — caused blank error body for all routing errors
- Autoloader no longer required manually; Composer autoload handles `BootstrapHandler` discovery

---

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
