<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit;

use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\Constraint\KsortedArrayEqualTo;
use Tailors\PHPUnit\Constraint\ProvKsortedArrayTrait;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\KsortedArrayEqualToTrait
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-type CreateConstraintArgs = list{0: array, 1?: int}
 */
final class KsortedArrayEqualToTraitTest extends TestCase
{
    use KsortedArrayEqualToTrait;
    use ProvKsortedArrayTrait;

    /**
     * @dataProvider provKsortedArrayIdenticalTo
     * @dataProvider provKsortedArrayEqualButNotIdenticalTo
     *
     * @param mixed $actual
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testKsortedArrayEqualToSucceeds(array $expect, $actual): void
    {
        self::assertThat($actual, self::ksortedArrayEqualTo($expect));
    }

    /**
     * @dataProvider provKsortedArrayIdenticalTo
     * @dataProvider provKsortedArrayEqualButNotIdenticalTo
     *
     * @param mixed $actual
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAssertKsortedArrayEqualToSucceeds(array $expect, $actual): void
    {
        self::assertKsortedArrayEqualTo($expect, $actual);
    }

    /**
     * @dataProvider provKsortedArrayNotEqualTo
     *
     * @param mixed $actual
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAssertKsortedArrayEqualToFails(array $expect, $actual): void
    {
        $regexp = '/^Lorem ipsum.\n'.
            'Failed asserting that .+ is an array '.
            'equal to specified one when ksorted./';
        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessageMatches($regexp);

        self::assertKsortedArrayEqualTo($expect, $actual, 'Lorem ipsum.');
    }

    /**
     * @dataProvider provKsortedArrayNotEqualTo
     *
     * @param mixed $actual
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testNotKsortedArrayEqualToSucceeds(array $expect, $actual): void
    {
        self::assertThat($actual, self::logicalNot(self::ksortedArrayEqualTo($expect)));
    }

    /**
     * @dataProvider provKsortedArrayNotEqualTo
     *
     * @param mixed $actual
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAssertNotKsortedArrayEqualToSucceeds(array $expect, $actual): void
    {
        self::assertNotKsortedArrayEqualTo($expect, $actual);
    }

    /**
     * @dataProvider provKsortedArrayIdenticalTo
     * @dataProvider provKsortedArrayEqualButNotIdenticalTo
     *
     * @param mixed $actual
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAssertNotKsortedArrayEqualToFails(array $expect, $actual): void
    {
        $regexp = '/^Lorem ipsum.\n'.
            'Failed asserting that .+ fails to be an array '.
            'equal to specified one when ksorted./';
        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessageMatches($regexp);

        self::assertNotKsortedArrayEqualTo($expect, $actual, 'Lorem ipsum.');
    }
}

// vim: syntax=php sw=4 ts=4 et:
