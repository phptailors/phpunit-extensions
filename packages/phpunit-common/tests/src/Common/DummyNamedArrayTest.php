<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Common;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Small;
use PHPUnit\Framework\TestCase;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
#[CoversClass(DummyNamedArray::class)]
#[CoversClass(DummyNamedArrayTest::class)]
#[Small]
final class DummyNamedArrayTest extends TestCase
{
    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testDummyNamedArray(): void
    {
        $array = new DummyNamedArray(['a' => 'A']);
        self::assertInstanceOf(\ArrayObject::class, $array);
        self::assertInstanceOf(ExportableNameInterface::class, $array);
        self::assertSame(['a' => 'A'], (array) $array);
        self::assertSame('DummyNamedArray', $array->exportableName());
    }
}
// vim: syntax=php sw=4 ts=4 et:
