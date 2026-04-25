# Minimalus Layoutilus

Example application and project template for the [mnhcc/ml-*](https://packagist.org/packages/mnhcc/) PHP framework.

## Create a new project

```bash
composer create-project mnhcc/minimalus-layoutilus myproject
cd myproject
ddev start
```

## Requirements

- PHP ≥ 5.4, ext-json, ext-pdo
- [DDEV](https://ddev.readthedocs.io) (local environment, PHP 5.6, MariaDB, nginx)

## Framework packages

| Package | Description |
|---|---|
| [mnhcc/ml-core](https://packagist.org/packages/mnhcc/ml-core) | Autoloader, base classes, helpers |
| [mnhcc/ml-bugcatcher](https://packagist.org/packages/mnhcc/ml-bugcatcher) | Error handler, events, exceptions |
| [mnhcc/ml-mvc](https://packagist.org/packages/mnhcc/ml-mvc) | Router, Control, View, Template |

## Development (working on the framework packages)

When developing the `ml-*` packages alongside this project, use `composer.local.json`
which resolves packages from sibling directories (`../ml-core` etc.) instead of Packagist:

```bash
make dev-install   # installs from ../ml-core, ../ml-bugcatcher, ../ml-mvc
make dev-update    # updates from sibling dirs
make dev-test      # runs the test suite
```

Without `make`, use `COMPOSER=composer.local.json composer install` directly.
The lock file for local development is `composer.local.lock` (gitignored).

## License

[LGPL-2.1-or-later](https://www.gnu.org/licenses/old-licenses/lgpl-2.1.html) — Michael Hegenbarth (carschrotter)
