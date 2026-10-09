<?php
/**
 * Copyright © Iman Aboheydary. All rights reserved.
 * See LICENSE for license details (MIT).
 */

declare(strict_types=1);

namespace Iranimij\Base\Test\Unit\Model;

use Iranimij\Base\Model\ModulesProvider;
use Iranimij\Base\Serializer\SafeJson;
use Magento\Framework\Composer\ComposerInformation;
use Magento\Framework\Filesystem\Driver\File;
use Magento\Framework\Module\Dir as ModuleDir;
use Magento\Framework\Module\FullModuleList;
use Magento\Framework\Module\ModuleListInterface;
use PHPUnit\Framework\TestCase;

class ModulesProviderTest extends TestCase
{
    private const BASE_COMPOSER = '{"name":"iranimij/module-base","support":{"docs":"https://docs.example/base"},'
        . '"extra":{"iranimij":{"changelog":"https://changelog.example/base"}}}';
    private const OPENLABEL_COMPOSER = '{"name":"iranimij/openlabel","version":"0.1.0"}';

    public function testListsOnlyIranimijModulesSortedByNameWithVersionAndLinks(): void
    {
        $fullList = $this->createStub(FullModuleList::class);
        $fullList->method('getNames')->willReturn(['Magento_Catalog', 'Iranimij_OpenLabel', 'Iranimij_Base']);

        $enabledList = $this->createStub(ModuleListInterface::class);
        $enabledList->method('has')->willReturnCallback(static fn (string $name) => $name !== 'Iranimij_OpenLabel');

        $dir = $this->createStub(ModuleDir::class);
        $dir->method('getDir')->willReturnCallback(static fn (string $name) => '/m/' . $name);

        $file = $this->createStub(File::class);
        $file->method('isExists')->willReturn(true);
        $file->method('fileGetContents')->willReturnCallback(static fn (string $path) => match ($path) {
            '/m/Iranimij_Base/composer.json' => self::BASE_COMPOSER,
            '/m/Iranimij_OpenLabel/composer.json' => self::OPENLABEL_COMPOSER,
            default => '{}',
        });

        $composer = $this->createStub(ComposerInformation::class);
        $composer->method('getInstalledMagentoPackages')->willReturn([
            'iranimij/module-base' => ['name' => 'iranimij/module-base', 'type' => 'magento2-module', 'version' => '1.0.0'],
        ]);

        $provider = new ModulesProvider($fullList, $enabledList, $dir, $file, $composer, new SafeJson());

        self::assertSame(
            [
                [
                    'module' => 'Iranimij_Base',
                    'package' => 'iranimij/module-base',
                    'version' => '1.0.0',
                    'enabled' => true,
                    'docs' => 'https://docs.example/base',
                    'changelog' => 'https://changelog.example/base',
                ],
                [
                    'module' => 'Iranimij_OpenLabel',
                    'package' => 'iranimij/openlabel',
                    'version' => '0.1.0',
                    'enabled' => false,
                    'docs' => '',
                    'changelog' => '',
                ],
            ],
            $provider->getModules()
        );
    }

    public function testModuleWithoutComposerJsonIsListedWithUnknownVersion(): void
    {
        $fullList = $this->createStub(FullModuleList::class);
        $fullList->method('getNames')->willReturn(['Iranimij_Local']);
        $enabledList = $this->createStub(ModuleListInterface::class);
        $enabledList->method('has')->willReturn(true);
        $dir = $this->createStub(ModuleDir::class);
        $dir->method('getDir')->willReturn('/m/Iranimij_Local');
        $file = $this->createStub(File::class);
        $file->method('isExists')->willReturn(false);
        $composer = $this->createStub(ComposerInformation::class);
        $composer->method('getInstalledMagentoPackages')->willReturn([]);

        $rows = (new ModulesProvider($fullList, $enabledList, $dir, $file, $composer, new SafeJson()))->getModules();

        self::assertCount(1, $rows);
        self::assertSame('Iranimij_Local', $rows[0]['module']);
        self::assertSame('', $rows[0]['package']);
        self::assertSame('unknown', $rows[0]['version']);
    }
}
