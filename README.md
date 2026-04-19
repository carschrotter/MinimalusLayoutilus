# Minimalus Layoutilus

A lightweight PHP MVC framework with a flexible templating system.

## Features

- MVC architecture with separated controller, view and template layers
- Flexible namespace-based autoloading (`BootstrapHandler`)
- Event system with `EventManager` and `EventParms`
- Configurable bootstrapping and error handling
- Installable via Composer, PHP >= 5.4
- LGPL 2.1 licensed

## Requirements

- PHP >= 5.4
- ext-json
- ext-pdo

## Installation

```bash
composer require mnhcc/minimalus-layoutilus
```

## Quick Start

```php
<?php
namespace myapp {
    use mnhcc\ml\classes\Programm;
    define('mnhcc\\ml\\INDEX', true);
    require_once 'vendor/autoload.php';
    require_once 'initial.php';
    Programm::getInstance(Programm::DEFAULTINSTANCE)->runn();
}
```

## Structure

```
classes/        PHP classes (.class.php)
  Config/       Application config
  Control/      Default controllers
  Exception/    Exception classes
  Records/      Record classes
  Template/     Template implementations (Html, Json, Heap)
interfaces/     PHP interfaces (.interface.php)
traits/         PHP traits (.trait.php)
library/        Bundled third-party libraries (epub, Benchmark)
templates/      HTML template files
```

## Local Development (DDEV)

```bash
ddev start
ddev composer install
```

## Composer Dependencies

| Package | Type | Purpose |
|---|---|---|
| `barbushin/php-imap` | require | IMAP support |
| `raveren/kint` | require-dev | Debug output |
| `firephp/firephp-core` | require-dev | FirePHP debugging |

## License

LGPL 2.1 — see [LICENSE](LICENSE)

## Author

Michael Hegenbarth (carschrotter) &lt;mnh@mn-hegenbarth.de&gt;
