# Contributing

Thank you for helping. A few rules keep this module tiny and trustworthy.

## Scope

`Iranimij_Base` contains only: the "Iranimij" configuration tab, the "Installed Iranimij modules" page and two typed helpers. It never contains licence checks, phone-home, telemetry, admin notifications, ads, business logic, indexers or cron. It stays under 1,000 lines of PHP. Pull requests that widen the scope are closed with thanks.

## Clean-room rule

All code must be written from scratch. Do not copy or adapt code, templates, CSS or assets from paid Magento extensions, even "just to see how they do it". Only Magento Open Source, Mage-OS, Hyvä (OSL) and other OSL/MIT/GPL code may be referenced.

## Workflow

- Branch from `main`, use [Conventional Commits](https://www.conventionalcommits.org/) (`feat:`, `fix:`, `test:`, `docs:`, `chore:`).
- Every change ships with tests (PHPUnit unit and/or Magento integration tests).
- CI must be green: PHPCS (Magento2 standard), PHPStan level 6, unit and integration tests on the full matrix.
- Public classes and methods carry PHPDoc; `Api/` interfaces follow strict semver.

## Running the tests locally

From a Magento root where the module is installed:

```bash
vendor/bin/phpcs --standard=Magento2 vendor/iranimij/module-base
vendor/bin/phpstan analyse -c vendor/iranimij/module-base/phpstan.neon
vendor/bin/phpunit -c vendor/iranimij/module-base/phpunit.xml.dist
cd dev/tests/integration && ../../../vendor/bin/phpunit -c phpunit.xml ../../../vendor/iranimij/module-base/Test/Integration
```
