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
#[CoversClass(ActualKsortedArray::class)]
#[CoversClass(ActualKsortedArrayTest::class)]
#[Small]
final class ActualKsortedArrayTest extends AbstractKsortedArrayTestCase
{
    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    final public function testImplementsResultInterface(): void
    {
        self::assertInstanceOf(ResultInterface::class, new ActualKsortedArray([]));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    final public function testActual(): void
    {
        self::assertSame(true, (new ActualKsortedArray([]))->actual());
    }

    /**
     * @psalm-pure
     *
     * @psalm-return class-string<ActualKsortedArray>
     */
    public static function getClass(): string
    {
        return ActualKsortedArray::class;
    }
}
// vim: syntax=php sw=4 ts=4 et:
