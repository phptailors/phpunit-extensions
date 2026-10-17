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
 * @covers \Tailors\PHPUnit\Properties\ActualClassProperties
 * @covers \Tailors\PHPUnit\Properties\ActualClassPropertiesTest
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
final class ActualClassPropertiesTest extends AbstractClassPropertiesTestCase
{
    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    final public function testImplementsResultInterface(): void
    {
        self::assertInstanceOf(ResultInterface::class, new ActualClassProperties([]));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    final public function testActual(): void
    {
        self::assertSame(true, (new ActualClassProperties([]))->actual());
    }

    /**
     * @psalm-pure
     *
     * @psalm-return class-string<ActualClassProperties>
     */
    public static function getClass(): string
    {
        return ActualClassProperties::class;
    }
}
// vim: syntax=php sw=4 ts=4 et:
