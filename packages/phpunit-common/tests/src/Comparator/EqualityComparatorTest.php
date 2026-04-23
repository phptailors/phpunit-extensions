<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Comparator;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Small;
use PHPUnit\Framework\TestCase;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
#[CoversClass(EqualityComparator::class)]
#[Small]
final class EqualityComparatorTest extends TestCase
{
    public function testImplementsComparatorInterface(): void
    {
        self::assertInstanceOf(ComparatorInterface::class, new EqualityComparator());
    }

    public static function provCompare(): array
    {
        return [
            'EqualityComparatorTest.php:'.__LINE__ => [
                'a', 'a', true,
            ],

            'EqualityComparatorTest.php:'.__LINE__ => [
                '123', 123, true,
            ],

            'EqualityComparatorTest.php:'.__LINE__ => [
                '', null, true,
            ],

            'EqualityComparatorTest.php:'.__LINE__ => [
                'a', 'b', false,
            ],
        ];
    }

    #[DataProvider('provCompare')]
    public function testCompare(mixed $left, mixed $right, bool $expect): void
    {
        $comparator = new EqualityComparator();
        self::assertSame($expect, $comparator->compare($left, $right));
    }

    public function testAdjective(): void
    {
        $comparator = new EqualityComparator();
        self::assertSame('equal to', $comparator->adjective());
    }
}
// vim: syntax=php sw=4 ts=4 et:
