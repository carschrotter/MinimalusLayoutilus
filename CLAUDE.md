# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Local Development

```bash
ddev start                  # start environment (PHP 5.6, MariaDB 11.8, nginx)
ddev composer install       # install dependencies
ddev describe               # show URLs and service status
ddev stop                   # stop environment
```

The project root is the docroot (`docroot: ""`). The entry point is `index.php`, which defines `mnhcc\ml\INDEX` and requires `initial.php`.

## File Naming Conventions

All framework files use non-standard extensions that the custom autoloader maps to PHP types:

| Extension | Type |
|---|---|
| `.class.php` | class |
| `.interface.php` | interface |
| `.trait.php` | trait |

Standard `.php` is also supported as fallback. **Do not rename files to plain `.php`** — the autoloader relies on these extensions to determine the type and locate files.

## Architecture

### Boot sequence

`index.php` → `initial.php` → `BootstrapHandler::initial()` → `Programm::runn()`

`initial.php` sets up global constants (`NSS`, `n`, `br`, `php`, `DS`, `MNHCC_PATH`, `ROOT_PATH`), loads config files, registers the autoloader, and initialises `Error`. The constant `mnhcc\ml\INDEX` must be defined before requiring `initial.php`.

### Autoloader (`BootstrapHandler`)

`BootstrapHandler` is the custom SPL autoloader. It maps namespace segments to directories:

- `mnhcc\ml\classes\Foo` → `classes/Foo.class.php`
- `mnhcc\ml\classes\Bar\Baz` → `classes/Bar/Baz.class.php`
- `mnhcc\ml\interfaces\Foo` → `interfaces/Foo.interface.php`
- `mnhcc\ml\traits\Foo` → `traits/Foo.trait.php`

Subdirectory names under `classes/` must be **PascalCase** — they map directly to namespace segments. When adding a new subdirectory, ensure the directory name matches the namespace segment exactly (case-sensitive on Linux).

`library/` subdirectories are loaded via their own `load.php` files that return a config array mapping class names to file paths.

### MVC request cycle

```
Programm::runn()
  → Router (parses URL → control/action/parms)
  → Bootstrap::getOverloadedClass()  (allows app namespace to override framework classes)
  → Control::onBeforeAction() → Control::actionIndex() (or other action)
  → Control::getComponent(ParmsControl)
  → View::getView(type, controlClass) → View subclass::renderComponent()
  → Template subclass::render()  (wraps output in HTML layout)
```

`Bootstrap::getOverloadedClass()` checks `APPLICATIONNAMESPACE` — if a matching class exists there, it is used instead of the framework class. This is the extension point for applications.

### Base class hierarchy

- `MNHcC` (abstract) — base for all framework classes; uses `traits\MNHcC` (provides `__toString`, `getClass`, `getCalledClass`, `__call`, `__callStatic`)
- `Bootstrap` (abstract, extends `MNHcC`) — static helpers for constants and class overloading
- `Control` (abstract, extends `MNHcC`) — base controller; subclass in `classes/Control/`
- `View` (abstract, extends `MNHcC`) — base view; subclass in `classes/View/`
- `Template` (abstract, extends `MNHcC`) — base template; subclass in `classes/Template/`
- `EventParms` (extends `MNHcC`) — parameter bag passed to event handlers

### Traits

- `Instances` — singleton-style `getInstance()` with named instances and overload support
- `NoInstances` — variant that does not cache instances
- `MNHcC` — magic `__call`/`__callStatic`, `getClass`, `getCalledClass`
- `Prototype` — runtime method injection via `setFunction()`/`setFunctionStatic()`
- `Event` — base for event classes (`getParms`, `setParms`, `addParms`, `getEventName`)

### Event system

`EventManager::register(Event $event)` and `EventManager::raise($name, EventParms $parms)`. Event names are normalised: leading `on` stripped, then `ucfirst`. Internal events: `templateCreated`, `shutdown`, `exception`.

### Template tags

Templates use custom XML-like tags processed by the template engine:

```
<mnhccTemplate:include type="component" name="content" renderType="html" />
<mnhccTemplate:include type="modul"     name="foo"     renderType="html" />
<mnhccTemplate:include type="system"    name="message" renderType="html" />
<mnhccTemplate:include type="head"      name="style"   renderer="none"   />
```

### Config keys

`$config` is a global array populated from `config.php`, `../config/` files, and `config.json`. Key keys:
- `provider` + `application` — together form `APPLICATIONNAMESPACE` (used for class overloading)
- `paths` — associative array of named paths

## Composer

Package: `mnhcc/minimalus-layoutilus`, type `library`, PHP ≥ 5.4.  
Autoloading uses `classmap` over `classes/`, `interfaces/`, `traits/` — Composer scans all `*.php` files including `*.class.php`.
