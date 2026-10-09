<?php
/**
 * Copyright © Iman Aboheydary. All rights reserved.
 * See LICENSE for license details (MIT).
 */

declare(strict_types=1);

namespace Iranimij\Base\Serializer;

/**
 * JSON encoding and decoding that never returns something unexpected: decode() always yields an array
 * and both directions throw \InvalidArgumentException instead of returning false or null.
 *
 * @api
 */
class SafeJson
{
    private const DEPTH = 512;

    /**
     * Encode a value as JSON, keeping unicode and slashes readable.
     *
     * @param mixed $value
     * @return string
     * @throws \InvalidArgumentException when the value cannot be encoded
     */
    public function encode(mixed $value): string
    {
        try {
            return json_encode(
                $value,
                JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES,
                self::DEPTH
            );
        } catch (\JsonException $e) {
            throw new \InvalidArgumentException('Unable to encode value as JSON: ' . $e->getMessage(), 0, $e);
        }
    }

    /**
     * Decode a JSON document into an associative array. An empty string decodes to an empty array.
     *
     * @param string $json
     * @return array<int|string, mixed>
     * @throws \InvalidArgumentException when the document is malformed or is not an object or array
     */
    public function decode(string $json): array
    {
        if (trim($json) === '') {
            return [];
        }
        try {
            $decoded = json_decode($json, true, self::DEPTH, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            throw new \InvalidArgumentException('Unable to decode JSON: ' . $e->getMessage(), 0, $e);
        }
        if (!is_array($decoded)) {
            throw new \InvalidArgumentException('JSON document must be an object or an array, ' . get_debug_type($decoded) . ' given');
        }

        return $decoded;
    }
}
