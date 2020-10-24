<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Comparator;

use PHPUnit\Framework\TestCase;

/**
 * @small
 * @covers \Tailors\PHPUnit\Comparator\IdentityComparator
 *
 * @internal This class is not covered by the backward compatibility promise
 * @psalm-internal Tailors\PHPUnit
 */
final class IdentityComparatorTest extends TestCase
{
    public function testImplementsComparatorInterface(): void
    {
        self::assertInstanceOf(ComparatorInterface::class, new IdentityComparator());
    }

    public static function provCompare(): array
    {
        return [
            'IdentityComparatorTest.php:'.__LINE__ => [
                'a', 'a', true,
            ],

            'IdentityComparatorTest.php:'.__LINE__ => [
                '123', 123, false,
            ],

            'IdentityComparatorTest.php:'.__LINE__ => [
                '', null, false,
            ],

            'IdentityComparatorTest.php:'.__LINE__ => [
                'a', 'b', false,
            ],
        ];
    }

    /**
     * @dataProvider provCompare
     *
     * @param mixed $left
     * @param mixed $right
     */
    public function testCompare($left, $right, bool $expect): void
    {
        $comparator = new IdentityComparator();
        self::assertSame($expect, $comparator->compare($left, $right));
    }

    public function testAdjective(): void
    {
        $comparator = new IdentityComparator();
        self::assertSame('identical to', $comparator->adjective());
    }
}
// vim: syntax=php sw=4 ts=4 et:
