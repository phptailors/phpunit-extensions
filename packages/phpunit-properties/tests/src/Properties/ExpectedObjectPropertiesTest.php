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
#[CoversClass(AbstractObjectPropertiesTestCase::class)]
#[CoversClass(ExpectedObjectProperties::class)]
#[CoversClass(ExpectedObjectPropertiesTest::class)]
#[Small]
final class ExpectedObjectPropertiesTest extends AbstractObjectPropertiesTestCase
{
    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    final public function testImplementsResultInterface(): void
    {
        self::assertInstanceOf(ResultInterface::class, new ExpectedObjectProperties([]));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    final public function testActual(): void
    {
        self::assertSame(false, (new ExpectedObjectProperties([]))->actual());
    }

    /**
     * @psalm-pure
     *
     * @psalm-return class-string<ExpectedObjectProperties>
     */
    public static function getClass(): string
    {
        return ExpectedObjectProperties::class;
    }
}
// vim: syntax=php sw=4 ts=4 et:
