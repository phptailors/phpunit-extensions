<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\Constraint\ArrayValuesEqualTo;
use Tailors\PHPUnit\Constraint\ProvArrayValuesTrait;

/**
 * @small
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @coversNothing
 */
#[CoversClass(ArrayValuesEqualToTrait::class)]
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
     * @param mixed $actual
     */
    #[DataProvider('provArrayValuesIdenticalTo')]
    #[DataProvider('provArrayValuesEqualButNotIdenticalTo')]
    public function testArrayValuesEqualToSucceeds(array $expect, $actual, string $string)
    {
        self::assertThat($actual, self::arrayValuesEqualTo($expect));
    }

    /**
     * @param mixed $actual
     */
    #[DataProvider('provArrayValuesIdenticalTo')]
    #[DataProvider('provArrayValuesEqualButNotIdenticalTo')]
    public function testAssertArrayValuesEqualToSucceeds(array $expect, $actual, string $string)
    {
        self::assertArrayValuesEqualTo($expect, $actual);
    }

    /**
     * @param mixed $actual
     */
    #[DataProvider('provArrayValuesNotEqualTo')]
    public function testAssertArrayValuesEqualToFails(array $expect, $actual, string $string)
    {
        $regexp = '/^Lorem ipsum.\n'.
            'Failed asserting that .+ is an array or ArrayAccess '.
            'with values equal to specified./';
        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessageMatches($regexp);

        self::assertArrayValuesEqualTo($expect, $actual, 'Lorem ipsum.');
    }

    /**
     * @param mixed $actual
     */
    #[DataProvider('provArrayValuesNotEqualTo')]
    public function testNotArrayValuesEqualToSucceeds(array $expect, $actual, string $string)
    {
        self::assertThat($actual, self::logicalNot(self::arrayValuesEqualTo($expect)));
    }

    /**
     * @param mixed $actual
     */
    #[DataProvider('provArrayValuesNotEqualTo')]
    public function testAssertNotArrayValuesEqualToSucceeds(array $expect, $actual, string $string)
    {
        self::assertNotArrayValuesEqualTo($expect, $actual);
    }

    /**
     * @param mixed $actual
     */
    #[DataProvider('provArrayValuesIdenticalTo')]
    #[DataProvider('provArrayValuesEqualButNotIdenticalTo')]
    public function testAssertNotArrayValuesEqualToFails(array $expect, $actual, string $string)
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
