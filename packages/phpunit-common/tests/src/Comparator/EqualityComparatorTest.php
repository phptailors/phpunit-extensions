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
 * @covers \Tailors\PHPUnit\Comparator\EqualityComparator
 * @covers \Tailors\PHPUnit\Comparator\EqualityComparatorTest
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
final class EqualityComparatorTest extends TestCase
{
    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testImplementsComparatorInterface(): void
    {
        self::assertInstanceOf(ComparatorInterface::class, new EqualityComparator());
    }

    /**
     * @psalm-return iterable<string, list{mixed, mixed, bool}>
     */
    public static function provCompare(): iterable
    {
        yield basename(__FILE__).':'.__LINE__ => [
            'a', 'a', true,
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            '123', 123, true,
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            '', null, true,
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
        $comparator = new EqualityComparator();
        self::assertSame($expect, $comparator->compare($left, $right));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAdjective(): void
    {
        $comparator = new EqualityComparator();
        self::assertSame('equal to', $comparator->adjective());
    }
}
// vim: syntax=php sw=4 ts=4 et:
