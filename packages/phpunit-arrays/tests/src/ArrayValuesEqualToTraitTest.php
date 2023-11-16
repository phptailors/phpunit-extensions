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
use Tailors\PHPUnit\Constraint\ArrayValuesEqualTo;
use Tailors\PHPUnit\Constraint\ProvArrayValuesTrait;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\ArrayValuesEqualToTrait
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
final class ArrayValuesEqualToTraitTest extends TestCase
{
    use ArrayValuesEqualToTrait;
    use ProvArrayValuesTrait;

    /**
     * @param mixed $args
     */
    public static function createConstraint(...$args): ArrayValuesEqualTo
    {
        return ArrayValuesEqualTo::create(...$args);
    }

    /**
     * @dataProvider provArrayValuesIdenticalTo
     * @dataProvider provArrayValuesEqualButNotIdenticalTo
     *
     * @param mixed $actual
     */
    public function testArrayValuesEqualToSucceeds(array $expect, $actual)
    {
        self::assertThat($actual, self::arrayValuesEqualTo($expect));
    }

    /**
     * @dataProvider provArrayValuesIdenticalTo
     * @dataProvider provArrayValuesEqualButNotIdenticalTo
     *
     * @param mixed $actual
     */
    public function testAssertArrayValuesEqualToSucceeds(array $expect, $actual)
    {
        self::assertArrayValuesEqualTo($expect, $actual);
    }

    /**
     * @dataProvider provArrayValuesNotEqualTo
     *
     * @param mixed $actual
     */
    public function testAssertArrayValuesEqualToFails(array $expect, $actual)
    {
        $regexp = '/^Lorem ipsum.\n'.
            'Failed asserting that .+ is an array or ArrayAccess '.
            'with values equal to specified./';
        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessageMatches($regexp);

        self::assertArrayValuesEqualTo($expect, $actual, 'Lorem ipsum.');
    }

    /**
     * @dataProvider provArrayValuesNotEqualTo
     *
     * @param mixed $actual
     */
    public function testNotArrayValuesEqualToSucceeds(array $expect, $actual)
    {
        self::assertThat($actual, self::logicalNot(self::arrayValuesEqualTo($expect)));
    }

    /**
     * @dataProvider provArrayValuesNotEqualTo
     *
     * @param mixed $actual
     */
    public function testAssertNotArrayValuesEqualToSucceeds(array $expect, $actual)
    {
        self::assertNotArrayValuesEqualTo($expect, $actual);
    }

    /**
     * @dataProvider provArrayValuesIdenticalTo
     * @dataProvider provArrayValuesEqualButNotIdenticalTo
     *
     * @param mixed $actual
     */
    public function testAssertNotArrayValuesEqualToFails(array $expect, $actual)
    {
        $regexp = '/^Lorem ipsum.\n'.
            'Failed asserting that .+ fails to be an array or ArrayAccess '.
            'with values equal to specified./';
        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessageMatches($regexp);

        self::assertNotArrayValuesEqualTo($expect, $actual, 'Lorem ipsum.');
    }
}

// vim: syntax=php sw=4 ts=4 et:
