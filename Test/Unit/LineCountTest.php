<?php
/**
 * Copyright © Iman Aboheydary. All rights reserved.
 * See LICENSE for license details (MIT).
 */

declare(strict_types=1);

namespace Iranimij\Base\Test\Unit;

use PHPUnit\Framework\TestCase;

/**
 * The base module promises to stay tiny: under 1,000 lines of production PHP, forever.
 */
class LineCountTest extends TestCase
{
    private const LIMIT = 1000;

    public function testProductionPhpStaysUnderTheLimit(): void
    {
        $root = dirname(__DIR__, 2);
        $total = 0;
        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($root, \FilesystemIterator::SKIP_DOTS));
        /** @var \SplFileInfo $file */
        foreach ($iterator as $file) {
            $relative = substr($file->getPathname(), strlen($root) + 1);
            if (!in_array($file->getExtension(), ['php', 'phtml'], true)
                || str_starts_with($relative, 'Test/')
                || str_starts_with($relative, 'vendor/')
                || str_starts_with($relative, '.')
            ) {
                continue;
            }
            $total += count(file($file->getPathname()) ?: []);
        }

        self::assertGreaterThan(0, $total);
        self::assertLessThan(self::LIMIT, $total, sprintf('Iranimij_Base has %d lines of PHP; the limit is %d', $total, self::LIMIT));
    }
}
