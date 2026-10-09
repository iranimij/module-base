<?php
/**
 * Copyright © Iman Aboheydary. All rights reserved.
 * See LICENSE for license details (MIT).
 */

declare(strict_types=1);

namespace Iranimij\Base\Test\Unit\Model\Config;

use Iranimij\Base\Model\Config\TypedReader;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use PHPUnit\Framework\TestCase;

class TypedReaderTest extends TestCase
{
    /**
     * @param array<string, mixed> $values path => raw config value
     */
    private function readerWith(array $values): TypedReader
    {
        $scopeConfig = $this->createStub(ScopeConfigInterface::class);
        $scopeConfig->method('getValue')->willReturnCallback(
            static fn (string $path) => $values[$path] ?? null
        );
        $scopeConfig->method('isSetFlag')->willReturnCallback(
            static fn (string $path) => filter_var($values[$path] ?? null, FILTER_VALIDATE_BOOLEAN)
        );

        return new TypedReader($scopeConfig);
    }

    public function testGetStringReturnsEmptyStringWhenUnset(): void
    {
        self::assertSame('', $this->readerWith([])->getString('a/b/c'));
        self::assertSame('hello', $this->readerWith(['a/b/c' => 'hello'])->getString('a/b/c'));
    }

    public function testGetIntCastsNumericStringsAndFallsBackToZero(): void
    {
        self::assertSame(12, $this->readerWith(['a/b/c' => '12'])->getInt('a/b/c'));
        self::assertSame(0, $this->readerWith([])->getInt('a/b/c'));
        self::assertSame(0, $this->readerWith(['a/b/c' => 'abc'])->getInt('a/b/c'));
    }

    public function testGetFloatCastsAndFallsBackToZero(): void
    {
        self::assertSame(1.5, $this->readerWith(['a/b/c' => '1.5'])->getFloat('a/b/c'));
        self::assertSame(0.0, $this->readerWith([])->getFloat('a/b/c'));
    }

    public function testGetBoolUsesMagentoFlagSemantics(): void
    {
        self::assertTrue($this->readerWith(['a/b/c' => '1'])->getBool('a/b/c'));
        self::assertFalse($this->readerWith(['a/b/c' => '0'])->getBool('a/b/c'));
        self::assertFalse($this->readerWith([])->getBool('a/b/c'));
    }

    public function testGetListSplitsCommaSeparatedValuesAndDropsEmptyEntries(): void
    {
        self::assertSame(['1', '2', '3'], $this->readerWith(['a/b/c' => '1, 2,,3 '])->getList('a/b/c'));
        self::assertSame([], $this->readerWith([])->getList('a/b/c'));
        self::assertSame([], $this->readerWith(['a/b/c' => ''])->getList('a/b/c'));
    }

    public function testScopeAndScopeIdArePassedThrough(): void
    {
        $scopeConfig = $this->createMock(ScopeConfigInterface::class);
        $scopeConfig->expects(self::once())
            ->method('getValue')
            ->with('a/b/c', ScopeInterface::SCOPE_WEBSITE, 3)
            ->willReturn('x');

        self::assertSame('x', (new TypedReader($scopeConfig))->getString('a/b/c', ScopeInterface::SCOPE_WEBSITE, 3));
    }

    public function testDefaultScopeIsStore(): void
    {
        $scopeConfig = $this->createMock(ScopeConfigInterface::class);
        $scopeConfig->expects(self::once())
            ->method('getValue')
            ->with('a/b/c', ScopeInterface::SCOPE_STORE, null)
            ->willReturn('x');

        (new TypedReader($scopeConfig))->getString('a/b/c');
    }
}
