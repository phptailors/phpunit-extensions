<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Properties;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Small;
use Tailors\PHPUnit\Result\ResultInterface;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
#[CoversClass(AbstractClassPropertiesTestCase::class)]
#[CoversClass(ActualClassProperties::class)]
#[CoversClass(ActualClassPropertiesTest::class)]
#[Small]
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
