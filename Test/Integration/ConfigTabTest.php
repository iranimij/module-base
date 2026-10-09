<?php
/**
 * Copyright © Iman Aboheydary. All rights reserved.
 * See LICENSE for license details (MIT).
 */

declare(strict_types=1);

namespace Iranimij\Base\Test\Integration;

use Magento\Config\Model\Config\Structure\Data as ConfigStructureData;
use Magento\Framework\Acl\AclResource\ProviderInterface as AclResourceProvider;
use Magento\TestFramework\Helper\Bootstrap;
use PHPUnit\Framework\TestCase;

/**
 * @magentoAppArea adminhtml
 */
class ConfigTabTest extends TestCase
{
    public function testIranimijTabIsDeclaredInSystemConfiguration(): void
    {
        $data = Bootstrap::getObjectManager()->get(ConfigStructureData::class)->get();

        self::assertArrayHasKey('iranimij', $data['tabs']);
        self::assertSame('Iranimij', $data['tabs']['iranimij']['label']);
    }

    public function testConfigAclResourceIsDeclaredUnderMagentoConfig(): void
    {
        $resources = Bootstrap::getObjectManager()->get(AclResourceProvider::class)->getAclResources();

        self::assertTrue(
            $this->containsResource($resources, 'Iranimij_Base::config'),
            'Iranimij_Base::config ACL resource must exist'
        );
    }

    /**
     * @param array<int, array<string, mixed>> $resources
     */
    private function containsResource(array $resources, string $id): bool
    {
        foreach ($resources as $resource) {
            if (($resource['id'] ?? null) === $id) {
                return true;
            }
            if (!empty($resource['children']) && $this->containsResource($resource['children'], $id)) {
                return true;
            }
        }
        return false;
    }
}
