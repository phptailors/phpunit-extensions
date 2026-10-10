<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Common;

use PHPUnit\Framework\TestCase;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\Common\DummyNamedArray
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
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
