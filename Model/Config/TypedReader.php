<?php
/**
 * Copyright © Iman Aboheydary. All rights reserved.
 * See LICENSE for license details (MIT).
 */

declare(strict_types=1);

namespace Iranimij\Base\Model\Config;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * Store-scoped, typed access to system configuration values.
 *
 * Every getter returns a value of the declared type and a sensible zero value when the path is unset,
 * so callers never deal with null or string-to-number casting.
 *
 * @api
 */
class TypedReader
{
    /**
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        private readonly ScopeConfigInterface $scopeConfig
    ) {
    }

    /**
     * Read a string value, '' when unset.
     *
     * @param string $path
     * @param string $scope
     * @param int|string|null $scopeId
     * @return string
     */
    public function getString(string $path, string $scope = ScopeInterface::SCOPE_STORE, int|string|null $scopeId = null): string
    {
        $value = $this->scopeConfig->getValue($path, $scope, $scopeId);

        return $value === null ? '' : (string) $value;
    }

    /**
     * Read an integer value, 0 when unset or not numeric.
     *
     * @param string $path
     * @param string $scope
     * @param int|string|null $scopeId
     * @return int
     */
    public function getInt(string $path, string $scope = ScopeInterface::SCOPE_STORE, int|string|null $scopeId = null): int
    {
        $value = $this->scopeConfig->getValue($path, $scope, $scopeId);

        return is_numeric($value) ? (int) $value : 0;
    }

    /**
     * Read a float value, 0.0 when unset or not numeric.
     *
     * @param string $path
     * @param string $scope
     * @param int|string|null $scopeId
     * @return float
     */
    public function getFloat(string $path, string $scope = ScopeInterface::SCOPE_STORE, int|string|null $scopeId = null): float
    {
        $value = $this->scopeConfig->getValue($path, $scope, $scopeId);

        return is_numeric($value) ? (float) $value : 0.0;
    }

    /**
     * Read a yes/no flag with Magento's flag semantics ("1", "true", "yes" are true).
     *
     * @param string $path
     * @param string $scope
     * @param int|string|null $scopeId
     * @return bool
     */
    public function getBool(string $path, string $scope = ScopeInterface::SCOPE_STORE, int|string|null $scopeId = null): bool
    {
        return $this->scopeConfig->isSetFlag($path, $scope, $scopeId);
    }

    /**
     * Read a comma-separated value (multiselect fields) as a list of trimmed, non-empty strings.
     *
     * @param string $path
     * @param string $scope
     * @param int|string|null $scopeId
     * @return string[]
     */
    public function getList(string $path, string $scope = ScopeInterface::SCOPE_STORE, int|string|null $scopeId = null): array
    {
        $raw = $this->getString($path, $scope, $scopeId);
        if ($raw === '') {
            return [];
        }
        $items = array_map('trim', explode(',', $raw));

        return array_values(array_filter($items, static fn (string $item): bool => $item !== ''));
    }
}
