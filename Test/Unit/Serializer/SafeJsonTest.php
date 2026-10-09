<?php
/**
 * Copyright © Iman Aboheydary. All rights reserved.
 * See LICENSE for license details (MIT).
 */

declare(strict_types=1);

namespace Iranimij\Base\Test\Unit\Serializer;

use Iranimij\Base\Serializer\SafeJson;
use PHPUnit\Framework\TestCase;

class SafeJsonTest extends TestCase
{
    private SafeJson $json;

    protected function setUp(): void
    {
        $this->json = new SafeJson();
    }

    public function testEncodeKeepsUnicodeAndSlashesReadable(): void
    {
        self::assertSame('{"name":"Käse/Brot","n":1}', $this->json->encode(['name' => 'Käse/Brot', 'n' => 1]));
    }

    public function testEncodeThrowsOnUnencodableValue(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->json->encode(['x' => NAN]);
    }

    public function testDecodeReturnsAssociativeArray(): void
    {
        self::assertSame(['a' => 1, 'b' => ['c' => null]], $this->json->decode('{"a":1,"b":{"c":null}}'));
        self::assertSame([], $this->json->decode('[]'));
        self::assertSame([], $this->json->decode('{}'));
    }

    public function testDecodeTreatsEmptyStringAsEmptyArray(): void
    {
        self::assertSame([], $this->json->decode(''));
    }

    public function testDecodeRejectsScalarJson(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->json->decode('"just a string"');
    }

    public function testDecodeRejectsMalformedJson(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->json->decode('{"a":');
    }
}
