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
use Tailors\PHPUnit\Constraint\ArrayValuesIdenticalTo;
use Tailors\PHPUnit\Constraint\ProvArrayValuesTrait;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\ArrayValuesIdenticalToTrait
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
final class ArrayValuesIdenticalToTraitTest extends TestCase
{
    use ArrayValuesIdenticalToTrait;
    use ProvArrayValuesTrait;

    /**
     * @param mixed $args
     */
    public function createConstraint(...$args): ArrayValuesIdenticalTo
    {
        return ArrayValuesIdenticalTo::create(...$args);
    }

    /**
     * @dataProvider provArrayValuesIdenticalTo
     *
     * @param mixed $actual
     */
    public function testArrayValuesIdenticalTo(array $expect, $actual)
    {
        self::assertThat($actual, self::arrayValuesIdenticalTo($expect));
    }

    /**
     * @dataProvider provArrayValuesNotEqualTo
     * @dataProvider provArrayValuesEqualButNotIdenticalTo
     *
     * @param mixed $actual
     */
    public function testLogicalNotArrayValuesIdenticalTo(array $expect, $actual)
    {
        self::assertThat($actual, self::logicalNot(self::arrayValuesIdenticalTo($expect)));
    }

    /**
     * @dataProvider provArrayValuesIdenticalTo
     *
     * @param mixed $actual
     */
    public function testAssertArrayValuesIdenticalTo(array $expect, $actual)
    {
        self::assertArrayValuesIdenticalTo($expect, $actual);
    }

    /**
     * @dataProvider provArrayValuesNotEqualTo
     * @dataProvider provArrayValuesEqualButNotIdenticalTo
     *
     * @param mixed $actual
     */
    public function testAssertArrayValuesIdenticalToFails(array $expect, $actual)
    {
        $regexp = '/^Lorem ipsum.\n'.
            'Failed asserting that .+ is an array or ArrayAccess '.
            'with values identical to specified./';
        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessageMatches($regexp);

        self::assertArrayValuesIdenticalTo($expect, $actual, 'Lorem ipsum.');
    }

    /**
     * @dataProvider provArrayValuesNotEqualTo
     *
     * @param mixed $actual
     */
    public function testAssertNotArrayValuesIdenticalTo(array $expect, $actual)
    {
        self::assertNotArrayValuesIdenticalTo($expect, $actual);
    }

    /**
     * @dataProvider provArrayValuesIdenticalTo
     *
     * @param mixed $actual
     */
    public function testAssertNotArrayValuesIdenticalToFails(array $expect, $actual)
    {
        $regexp = '/^Lorem ipsum.\n'.
            'Failed asserting that .+ fails to be an array or ArrayAccess '.
            'with values identical to specified./';
        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessageMatches($regexp);

        self::assertNotArrayValuesIdenticalTo($expect, $actual, 'Lorem ipsum.');
    }
}

// vim: syntax=php sw=4 ts=4 et:
