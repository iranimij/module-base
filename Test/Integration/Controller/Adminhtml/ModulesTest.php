<?php
/**
 * Copyright © Iman Aboheydary. All rights reserved.
 * See LICENSE for license details (MIT).
 */

declare(strict_types=1);

namespace Iranimij\Base\Test\Integration\Controller\Adminhtml;

use Magento\TestFramework\TestCase\AbstractBackendController;

/**
 * @magentoAppArea adminhtml
 */
class ModulesTest extends AbstractBackendController
{
    /**
     * @var string
     */
    protected $uri = 'backend/iranimij_base/modules/index';

    /**
     * @var string
     */
    protected $resource = 'Iranimij_Base::modules';

    public function testPageListsTheBaseModule(): void
    {
        $this->dispatch($this->uri);

        self::assertSame(200, $this->getResponse()->getHttpResponseCode());
        $body = $this->getResponse()->getBody();
        self::assertStringContainsString('Installed Iranimij modules', $body);
        self::assertStringContainsString('Iranimij_Base', $body);
        self::assertStringContainsString('iranimij/module-base', $body);
        self::assertStringContainsString('https://github.com/iranimij/module-base/blob/main/CHANGELOG.md', $body);
    }
}
