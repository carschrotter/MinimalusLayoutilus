# Minimalus Layoutilus

*A lightweight PHP template engine and MVC framework — a modern take on the classic PHP include patterns of the early 2000s.*

Minimalus Layoutilus follows a minimal setup philosophy: static content is injected into customizable templates through a structured Controller/View system. The ironically Latin-sounding name says it all — minimal in configuration, maximum in adaptability.

## Quick Start

```bash
composer create-project mnhcc/minimalus-layoutilus myproject
cd myproject
ddev start
ddev composer install
```

## Requirements

- PHP ≥ 5.4, ext-json
- [DDEV](https://ddev.readthedocs.io) (PHP 5.6, MariaDB, nginx)
- Optional: ext-pdo (database access), ext-imap (email features)

## Framework Packages

| Package | Description |
|---|---|
| [mnhcc/ml-core](https://packagist.org/packages/mnhcc/ml-core) | Autoloader, base classes, helpers |
| [mnhcc/ml-bugcatcher](https://packagist.org/packages/mnhcc/ml-bugcatcher) | Error handler, events, exceptions |
| [mnhcc/ml-mvc](https://packagist.org/packages/mnhcc/ml-mvc) | Router, Control, View, Template |

## Development (working on the framework packages)

`ddev start` automatically activates the Git hook that generates `composer.json` from the current tags of sibling repos on every commit:

```bash
ddev composer install   # uses composer.local.json with path repos to ../ml-*
ddev test               # PHPUnit
```

Without DDEV, activate once: `git config core.hooksPath .githooks`

## Enable imap

```bash
cp .ddev/config.imap.yaml.dist .ddev/config.imap.yaml
ddev restart
```

## License

[LGPL-2.1-or-later](https://www.gnu.org/licenses/old-licenses/lgpl-2.1.html) — Michael Hegenbarth (carschrotter)
