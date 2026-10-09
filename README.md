# Iranimij_Base

**No license checks. No phone-home. No ads. Just a config tab.**

A deliberately tiny base module shared by all `iranimij/*` Magento 2 and Mage-OS extensions (for example [OpenLabel](https://github.com/iranimij/openlabel)). It exists so that every product does not have to ship the same three things.

## What it contains

- The **Iranimij** tab in Stores › Configuration. Each product adds its own section under it.
- An **Installed Iranimij modules** page (System › Iranimij) listing name, version, documentation and changelog links read from Composer.
- Two typed helpers: a store-scoped typed config reader and a safe JSON serializer.

## What it never contains

Licence checks, phone-home, telemetry, admin notifications, ads or upsell, business logic, indexers, cron. It stays under 1,000 lines of PHP (enforced by a test), follows strict semver and never introduces a breaking change in a minor release.

## Compatibility

| Magento Open Source / Mage-OS | PHP |
|---|---|
| 2.4.7, 2.4.8, 2.4.9 (Adobe Commerce same versions) | 8.2, 8.3, 8.4 |

## Installation

```bash
composer require iranimij/module-base
bin/magento module:enable Iranimij_Base
bin/magento setup:upgrade
```

You normally do not install this module directly; the product metapackages require it.

## License

MIT. See [LICENSE](LICENSE).
