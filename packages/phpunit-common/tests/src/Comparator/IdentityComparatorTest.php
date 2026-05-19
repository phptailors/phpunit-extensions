<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Comparator;

use PHPUnit\Framework\TestCase;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\Comparator\IdentityComparator
 * @covers \Tailors\PHPUnit\Comparator\IdentityComparatorTest
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
final class IdentityComparatorTest extends TestCase
{
    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testImplementsComparatorInterface(): void
    {
        self::assertInstanceOf(ComparatorInterface::class, new IdentityComparator());
    }

    /**
     * @psalm-return iterable<string, list{mixed, mixed, bool}>
     *
     * @codeCoverageIgnore
     */
    public static function provCompare(): iterable
    {
        yield basename(__FILE__).':'.__LINE__ => [
            'a', 'a', true,
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            '123', 123, false,
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            '', null, false,
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'a', 'b', false,
        ];
    }

    /**
     * @dataProvider provCompare
     *
     * @param mixed $left
     * @param mixed $right
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testCompare($left, $right, bool $expect): void
    {
        $comparator = new IdentityComparator();
        self::assertSame($expect, $comparator->compare($left, $right));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAdjective(): void
    {
        $comparator = new IdentityComparator();
        self::assertSame('identical to', $comparator->adjective());
    }
}
// vim: syntax=php sw=4 ts=4 et:
