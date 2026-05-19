<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Arrays;

use Tailors\PHPUnit\Result\ResultInterface;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\Arrays\AbstractKsortedArrayTestCase
 * @covers \Tailors\PHPUnit\Arrays\ExpectedKsortedArray
 * @covers \Tailors\PHPUnit\Arrays\ExpectedKsortedArrayTest
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
final class ExpectedKsortedArrayTest extends AbstractKsortedArrayTestCase
{
    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    final public function testImplementsResultInterface(): void
    {
        self::assertInstanceOf(ResultInterface::class, new ExpectedKsortedArray([]));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    final public function testActual(): void
    {
        self::assertSame(false, (new ExpectedKsortedArray([]))->actual());
    }

    /**
     * @psalm-pure
     *
     * @psalm-return class-string<ExpectedKsortedArray>
     */
    public static function getClass(): string
    {
        return ExpectedKsortedArray::class;
    }
}
// vim: syntax=php sw=4 ts=4 et:
