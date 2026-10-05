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
use Tailors\PHPUnit\Common\TypesInterface;
use Tailors\PHPUnit\Constraint\ProvArrayValuesTrait;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\ArrayValuesEqualToTrait
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-import-type ArrayLike from TypesInterface
 */
final class ArrayValuesEqualToTraitTest extends TestCase
{
    use ArrayValuesEqualToTrait;
    use ProvArrayValuesTrait;

    /**
     * @dataProvider provArrayValuesIdenticalTo
     * @dataProvider provArrayValuesEqualButNotIdenticalTo
     *
     * @param mixed $actual
     *
     * @psalm-param array $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testArrayValuesEqualToSucceeds(array $expect, $actual): void
    {
        self::assertThat($actual, self::arrayValuesEqualTo($expect));
    }

    /**
     * @dataProvider provArrayValuesIdenticalTo
     * @dataProvider provArrayValuesEqualButNotIdenticalTo
     *
     * @param mixed $actual
     *
     * @psalm-param array $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAssertArrayValuesEqualToSucceeds(array $expect, $actual): void
    {
        self::assertArrayValuesEqualTo($expect, $actual);
    }

    /**
     * @dataProvider provArrayValuesNotEqualTo
     *
     * @param mixed $actual
     *
     * @psalm-param array $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAssertArrayValuesEqualToFails(array $expect, $actual): void
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
     *
     * @psalm-param array $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testNotArrayValuesEqualToSucceeds(array $expect, $actual): void
    {
        self::assertThat($actual, self::logicalNot(self::arrayValuesEqualTo($expect)));
    }

    /**
     * @dataProvider provArrayValuesNotEqualTo
     *
     * @param mixed $actual
     *
     * @psalm-param array $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAssertNotArrayValuesEqualToSucceeds(array $expect, $actual): void
    {
        self::assertNotArrayValuesEqualTo($expect, $actual);
    }

    /**
     * @dataProvider provArrayValuesIdenticalTo
     * @dataProvider provArrayValuesEqualButNotIdenticalTo
     *
     * @param mixed $actual
     *
     * @psalm-param array $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAssertNotArrayValuesEqualToFails(array $expect, $actual): void
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
