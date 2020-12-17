<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
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
 * @covers \Tailors\PHPUnit\KsortedArrayEqualToTrait
 *
 * @internal This class is not covered by the backward compatibility promise
 * @psalm-internal Tailors\PHPUnit
 */
final class KsortedArrayEqualToTraitTest extends TestCase
{
    use KsortedArrayEqualToTrait;
    use ProvKsortedArrayTrait;

    /**
     * @param mixed $args
     */
    public function createConstraint(...$args): KsortedArrayEqualTo
    {
        return KsortedArrayEqualTo::create(...$args);
    }

    /**
     * @dataProvider provKsortedArrayIdenticalTo
     * @dataProvider provKsortedArrayEqualButNotIdenticalTo
     *
     * @param mixed $actual
     */
    public function testKsortedArrayEqualToSucceeds(array $expect, $actual)
    {
        self::assertThat($actual, self::ksortedArrayEqualTo($expect));
    }

    /**
     * @dataProvider provKsortedArrayIdenticalTo
     * @dataProvider provKsortedArrayEqualButNotIdenticalTo
     *
     * @param mixed $actual
     */
    public function testAssertKsortedArrayEqualToSucceeds(array $expect, $actual)
    {
        self::assertKsortedArrayEqualTo($expect, $actual);
    }

    /**
     * @dataProvider provKsortedArrayNotEqualTo
     *
     * @param mixed $actual
     */
    public function testAssertKsortedArrayEqualToFails(array $expect, $actual)
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
     */
    public function testNotKsortedArrayEqualToSucceeds(array $expect, $actual)
    {
        self::assertThat($actual, self::logicalNot(self::ksortedArrayEqualTo($expect)));
    }

    /**
     * @dataProvider provKsortedArrayNotEqualTo
     *
     * @param mixed $actual
     */
    public function testAssertNotKsortedArrayEqualToSucceeds(array $expect, $actual)
    {
        self::assertNotKsortedArrayEqualTo($expect, $actual);
    }

    /**
     * @dataProvider provKsortedArrayIdenticalTo
     * @dataProvider provKsortedArrayEqualButNotIdenticalTo
     *
     * @param mixed $actual
     */
    public function testAssertNotKsortedArrayEqualToFails(array $expect, $actual)
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
