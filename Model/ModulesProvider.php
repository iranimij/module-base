<?php
/**
 * Copyright © Iman Aboheydary. All rights reserved.
 * See LICENSE for license details (MIT).
 */

declare(strict_types=1);

namespace Iranimij\Base\Model;

use Iranimij\Base\Serializer\SafeJson;
use Magento\Framework\Composer\ComposerInformation;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Filesystem\Driver\File;
use Magento\Framework\Module\Dir as ModuleDir;
use Magento\Framework\Module\FullModuleList;
use Magento\Framework\Module\ModuleListInterface;

/**
 * Lists every installed Iranimij_* module with its Composer package, version, state and documentation links.
 *
 * Reads only local files (each module's composer.json and Composer's installed packages). Nothing is sent anywhere.
 */
class ModulesProvider
{
    private const PREFIX = 'Iranimij_';

    /**
     * @param FullModuleList $fullModuleList
     * @param ModuleListInterface $enabledModuleList
     * @param ModuleDir $moduleDir
     * @param File $fileDriver
     * @param ComposerInformation $composerInformation
     * @param SafeJson $json
     */
    public function __construct(
        private readonly FullModuleList $fullModuleList,
        private readonly ModuleListInterface $enabledModuleList,
        private readonly ModuleDir $moduleDir,
        private readonly File $fileDriver,
        private readonly ComposerInformation $composerInformation,
        private readonly SafeJson $json
    ) {
    }

    /**
     * Rows sorted by module name.
     *
     * @return array<int, array{module: string, package: string, version: string, enabled: bool, docs: string, changelog: string}>
     */
    public function getModules(): array
    {
        $installed = $this->composerInformation->getInstalledMagentoPackages();
        $rows = [];
        foreach ($this->fullModuleList->getNames() as $moduleName) {
            if (!str_starts_with($moduleName, self::PREFIX)) {
                continue;
            }
            $composer = $this->readComposerJson($moduleName);
            $package = (string) ($composer['name'] ?? '');
            $version = $installed[$package]['version'] ?? $composer['version'] ?? 'unknown';
            $rows[$moduleName] = [
                'module' => $moduleName,
                'package' => $package,
                'version' => (string) $version,
                'enabled' => $this->enabledModuleList->has($moduleName),
                'docs' => (string) ($composer['support']['docs'] ?? ''),
                'changelog' => (string) ($composer['extra']['iranimij']['changelog'] ?? ''),
            ];
        }
        ksort($rows);

        return array_values($rows);
    }

    /**
     * @param string $moduleName
     * @return array<string, mixed>
     */
    private function readComposerJson(string $moduleName): array
    {
        $path = $this->moduleDir->getDir($moduleName) . '/composer.json';
        try {
            if (!$this->fileDriver->isExists($path)) {
                return [];
            }
            return $this->json->decode($this->fileDriver->fileGetContents($path));
        } catch (FileSystemException | \InvalidArgumentException $e) {
            return [];
        }
    }
}
