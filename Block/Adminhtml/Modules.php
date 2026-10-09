<?php
/**
 * Copyright © Iman Aboheydary. All rights reserved.
 * See LICENSE for license details (MIT).
 */

declare(strict_types=1);

namespace Iranimij\Base\Block\Adminhtml;

use Iranimij\Base\Model\ModulesProvider;
use Magento\Backend\Block\Template;
use Magento\Backend\Block\Template\Context;

/**
 * Renders the "Installed Iranimij modules" table.
 */
class Modules extends Template
{
    /**
     * @param Context $context
     * @param ModulesProvider $modulesProvider
     * @param array<string, mixed> $data
     */
    public function __construct(
        Context $context,
        private readonly ModulesProvider $modulesProvider,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    /**
     * @return array<int, array{module: string, package: string, version: string, enabled: bool, docs: string, changelog: string}>
     */
    public function getModules(): array
    {
        return $this->modulesProvider->getModules();
    }
}
