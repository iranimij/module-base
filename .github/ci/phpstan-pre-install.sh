#!/usr/bin/env bash
# Runs inside the ExtDN PHPStan container before the module is installed.
# Adds the Magento-aware PHPStan extension (factories, proxies, generated classes).
set -e
composer require --dev --no-interaction --no-progress --with-all-dependencies bitexpert/phpstan-magento
