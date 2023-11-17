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
use Tailors\PHPUnit\Constraint\KsortedArrayIdenticalTo;
use Tailors\PHPUnit\Constraint\ProvKsortedArrayTrait;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\KsortedArrayIdenticalToTrait
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
final class KsortedArrayIdenticalToTraitTest extends TestCase
{
    use KsortedArrayIdenticalToTrait;
    use ProvKsortedArrayTrait;

    /**
     * @param mixed $args
     */
    public function createConstraint(...$args): KsortedArrayIdenticalTo
    {
        return KsortedArrayIdenticalTo::create(...$args);
    }

    /**
     * @dataProvider provKsortedArrayIdenticalTo
     *
     * @param mixed $actual
     */
    public function testKsortedArrayIdenticalToSucceeds(array $expect, $actual)
    {
        self::assertThat($actual, self::ksortedArrayIdenticalTo($expect));
    }

    /**
     * @dataProvider provKsortedArrayIdenticalTo
     *
     * @param mixed $actual
     */
    public function testAssertKsortedArrayIdenticalToSucceeds(array $expect, $actual)
    {
        self::assertKsortedArrayIdenticalTo($expect, $actual);
    }

    /**
     * @dataProvider provKsortedArrayNotEqualTo
     * @dataProvider provKsortedArrayEqualButNotIdenticalTo
     *
     * @param mixed $actual
     */
    public function testAssertKsortedArrayIdenticalToFails(array $expect, $actual)
    {
        $regexp = '/^Lorem ipsum.\n'.
            'Failed asserting that .+ is an array '.
            'identical to specified one when ksorted./';
        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessageMatches($regexp);

        self::assertKsortedArrayIdenticalTo($expect, $actual, 'Lorem ipsum.');
    }

    /**
     * @dataProvider provKsortedArrayNotEqualTo
     *
     * @param mixed $actual
     */
    public function testNotKsortedArrayIdenticalToSucceeds(array $expect, $actual)
    {
        self::assertThat($actual, self::logicalNot(self::ksortedArrayIdenticalTo($expect)));
    }

    /**
     * @dataProvider provKsortedArrayNotEqualTo
     * @dataProvider provKsortedArrayEqualButNotIdenticalTo
     *
     * @param mixed $actual
     */
    public function testAssertNotKsortedArrayIdenticalToSucceeds(array $expect, $actual)
    {
        self::assertNotKsortedArrayIdenticalTo($expect, $actual);
    }

    /**
     * @dataProvider provKsortedArrayIdenticalTo
     *
     * @param mixed $actual
     */
    public function testAssertNotKsortedArrayIdenticalToFails(array $expect, $actual)
    {
        $regexp = '/^Lorem ipsum.\n'.
            'Failed asserting that .+ fails to be an array '.
            'identical to specified one when ksorted./';
        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessageMatches($regexp);

        self::assertNotKsortedArrayIdenticalTo($expect, $actual, 'Lorem ipsum.');
    }
}

// vim: syntax=php sw=4 ts=4 et:
