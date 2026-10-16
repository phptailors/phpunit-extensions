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
 * @covers \Tailors\PHPUnit\Arrays\AbstractArrayValuesTestCase
 * @covers \Tailors\PHPUnit\Arrays\ExpectedArrayValues
 * @covers \Tailors\PHPUnit\Arrays\ExpectedArrayValuesTest
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
final class ExpectedArrayValuesTest extends AbstractArrayValuesTestCase
{
    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    final public function testImplementsResultInterface(): void
    {
        self::assertInstanceOf(ResultInterface::class, new ExpectedArrayValues([]));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    final public function testExpected(): void
    {
        self::assertSame(false, (new ExpectedArrayValues([]))->actual());
    }

    /**
     * @psalm-pure
     *
     * @psalm-return class-string<ExpectedArrayValues>
     */
    public static function getClass(): string
    {
        return ExpectedArrayValues::class;
    }
}
// vim: syntax=php sw=4 ts=4 et:
