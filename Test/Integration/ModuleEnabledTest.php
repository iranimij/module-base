<?php
/**
 * Copyright © Iman Aboheydary. All rights reserved.
 * See LICENSE for license details (MIT).
 */

declare(strict_types=1);

namespace Iranimij\Base\Test\Integration;

use Magento\Framework\Module\ModuleList;
use Magento\TestFramework\Helper\Bootstrap;
use PHPUnit\Framework\TestCase;

class ModuleEnabledTest extends TestCase
{
    public function testModuleIsEnabledAfterInstall(): void
    {
        $moduleList = Bootstrap::getObjectManager()->get(ModuleList::class);

        self::assertTrue($moduleList->has('Iranimij_Base'));
    }
}
