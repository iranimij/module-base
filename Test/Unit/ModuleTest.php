<?php
/**
 * Copyright © Iman Aboheydary. All rights reserved.
 * See LICENSE for license details (MIT).
 */

declare(strict_types=1);

namespace Iranimij\Base\Test\Unit;

use Magento\Framework\Component\ComponentRegistrar;
use PHPUnit\Framework\TestCase;

/**
 * Guards the module identity and the "no dependencies outside Magento core" promise.
 */
class ModuleTest extends TestCase
{
    private const MODULE_NAME = 'Iranimij_Base';

    public function testModuleIsRegisteredWithComponentRegistrar(): void
    {
        $path = (new ComponentRegistrar())->getPath(ComponentRegistrar::MODULE, self::MODULE_NAME);

        self::assertNotNull($path, 'registration.php must register ' . self::MODULE_NAME);
        self::assertSame(realpath(dirname(__DIR__, 2)), realpath((string) $path));
    }

    public function testModuleXmlDeclaresTheModuleName(): void
    {
        $xml = simplexml_load_file(dirname(__DIR__, 2) . '/etc/module.xml');

        self::assertNotFalse($xml);
        self::assertSame(self::MODULE_NAME, (string) $xml->module['name']);
    }

    public function testComposerRequiresOnlyPhpAndMagentoCorePackages(): void
    {
        $composer = json_decode((string) file_get_contents(dirname(__DIR__, 2) . '/composer.json'), true);

        self::assertSame('magento2-module', $composer['type']);
        self::assertSame('MIT', $composer['license']);
        foreach (array_keys($composer['require']) as $package) {
            self::assertMatchesRegularExpression(
                '#^(php|ext-[a-z0-9_]+|magento/[a-z0-9-]+)$#',
                $package,
                'Only php, extensions and magento/* packages may be required'
            );
        }
        self::assertArrayNotHasKey('require-dev', $composer, 'No dev dependencies in the base module');
    }
}
