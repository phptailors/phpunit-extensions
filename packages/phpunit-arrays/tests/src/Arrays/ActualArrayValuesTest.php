<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Arrays;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Small;
use Tailors\PHPUnit\Result\ResultInterface;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
#[CoversClass(AbstractArrayValuesTestCase::class)]
#[CoversClass(ActualArrayValues::class)]
#[CoversClass(ActualArrayValuesTest::class)]
#[Small]
final class ActualArrayValuesTest extends AbstractArrayValuesTestCase
{
    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    final public function testImplementsResultInterface(): void
    {
        self::assertInstanceOf(ResultInterface::class, new ActualArrayValues([]));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    final public function testExpected(): void
    {
        self::assertSame(true, (new ActualArrayValues([]))->actual());
    }

    /**
     * @psalm-pure
     *
     * @psalm-return class-string<ActualArrayValues>
     */
    public static function getClass(): string
    {
        return ActualArrayValues::class;
    }
}
// vim: syntax=php sw=4 ts=4 et:
