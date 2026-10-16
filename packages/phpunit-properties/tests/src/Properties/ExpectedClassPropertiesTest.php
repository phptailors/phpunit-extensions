<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Properties;

use Tailors\PHPUnit\Result\ResultInterface;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\Properties\AbstractClassPropertiesTestCase
 * @covers \Tailors\PHPUnit\Properties\ExpectedClassProperties
 * @covers \Tailors\PHPUnit\Properties\ExpectedClassPropertiesTest
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
final class ExpectedClassPropertiesTest extends AbstractClassPropertiesTestCase
{
    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    final public function testImplementsResultInterface(): void
    {
        self::assertInstanceOf(ResultInterface::class, new ExpectedClassProperties([]));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    final public function testExpected(): void
    {
        self::assertSame(false, (new ExpectedClassProperties([]))->actual());
    }

    /**
     * @psalm-pure
     *
     * @psalm-return class-string<ExpectedClassProperties>
     */
    public static function getClass(): string
    {
        return ExpectedClassProperties::class;
    }
}
// vim: syntax=php sw=4 ts=4 et:
