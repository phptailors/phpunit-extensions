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
 */
final class KsortedArrayEqualToTraitTest extends TestCase
{
    use KsortedArrayEqualToTrait;
    use ProvKsortedArrayTrait;

    public static function createConstraint(mixed ...$args): KsortedArrayEqualTo
    {
        return KsortedArrayEqualTo::create(...$args);
    }

    /**
     * @dataProvider provKsortedArrayIdenticalTo
     * @dataProvider provKsortedArrayEqualButNotIdenticalTo
     */
    public function testKsortedArrayEqualToSucceeds(array $expect, mixed $actual, string $string)
    {
        self::assertThat($actual, self::ksortedArrayEqualTo($expect));
    }

    /**
     * @dataProvider provKsortedArrayIdenticalTo
     * @dataProvider provKsortedArrayEqualButNotIdenticalTo
     */
    public function testAssertKsortedArrayEqualToSucceeds(array $expect, mixed $actual, string $string)
    {
        self::assertKsortedArrayEqualTo($expect, $actual);
    }

    /**
     * @dataProvider provKsortedArrayNotEqualTo
     */
    public function testAssertKsortedArrayEqualToFails(array $expect, mixed $actual, string $string)
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
     */
    public function testNotKsortedArrayEqualToSucceeds(array $expect, mixed $actual, string $string)
    {
        self::assertThat($actual, self::logicalNot(self::ksortedArrayEqualTo($expect)));
    }

    /**
     * @dataProvider provKsortedArrayNotEqualTo
     */
    public function testAssertNotKsortedArrayEqualToSucceeds(array $expect, mixed $actual, string $string)
    {
        self::assertNotKsortedArrayEqualTo($expect, $actual);
    }

    /**
     * @dataProvider provKsortedArrayIdenticalTo
     * @dataProvider provKsortedArrayEqualButNotIdenticalTo
     */
    public function testAssertNotKsortedArrayEqualToFails(array $expect, mixed $actual, string $string)
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
