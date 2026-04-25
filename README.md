# Minimalus Layoutilus

*Leichtgewichtige PHP-Template-Engine und MVC-Framework — ein moderner Ansatz der klassischen PHP-Include-Architektur der frühen 2000er.*

Minimalus Layoutilus verfolgt ein minimales Setup: statische Inhalte werden über ein strukturiertes Controller/View-System in anpassbare Templates eingefügt. Der ironisch lateinisch anmutende Name steht Programm — minimal in der Konfiguration, maximal in der Anpassbarkeit.

## Schnellstart

```bash
composer create-project mnhcc/minimalus-layoutilus myproject
cd myproject
ddev start
ddev composer install
```

## Voraussetzungen

- PHP ≥ 5.4, ext-json
- [DDEV](https://ddev.readthedocs.io) (PHP 5.6, MariaDB, nginx)
- Optional: ext-pdo (Datenbankzugriff), ext-imap (E-Mail-Features)

## Framework-Pakete

| Paket | Beschreibung |
|---|---|
| [mnhcc/ml-core](https://packagist.org/packages/mnhcc/ml-core) | Autoloader, Basisklassen, Helpers |
| [mnhcc/ml-bugcatcher](https://packagist.org/packages/mnhcc/ml-bugcatcher) | Fehlerhandler, Events, Exceptions |
| [mnhcc/ml-mvc](https://packagist.org/packages/mnhcc/ml-mvc) | Router, Control, View, Template |

## Entwicklung (an den Framework-Paketen arbeiten)

`ddev start` aktiviert automatisch den Git-Hook, der beim Commit `composer.json` aus den aktuellen Tags der Geschwister-Repos generiert:

```bash
ddev composer install   # nutzt composer.local.json mit Path-Repos zu ../ml-*
ddev test               # PHPUnit
```

Ohne DDEV einmalig aktivieren: `git config core.hooksPath .githooks`

## imap aktivieren

```bash
cp .ddev/config.imap.yaml.dist .ddev/config.imap.yaml
ddev restart
```

## Lizenz

[LGPL-2.1-or-later](https://www.gnu.org/licenses/old-licenses/lgpl-2.1.html) — Michael Hegenbarth (carschrotter)
