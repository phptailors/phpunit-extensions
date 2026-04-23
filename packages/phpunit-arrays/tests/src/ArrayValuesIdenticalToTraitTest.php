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
use PHPUnit\Framework\Attributes\Small;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\Constraint\ArrayValuesIdenticalTo;
use Tailors\PHPUnit\Constraint\ProvArrayValuesTrait;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
#[CoversClass(ArrayValuesIdenticalToTrait::class)]
#[Small]
final class ArrayValuesIdenticalToTraitTest extends TestCase
{
    use ArrayValuesIdenticalToTrait;
    use ProvArrayValuesTrait;

    public static function createConstraint(mixed ...$args): ArrayValuesIdenticalTo
    {
        return ArrayValuesIdenticalTo::create(...$args);
    }

    #[DataProvider('provArrayValuesIdenticalTo')]
    public function testArrayValuesIdenticalTo(array $expect, mixed $actual, string $string)
    {
        self::assertThat($actual, self::arrayValuesIdenticalTo($expect));
    }

    #[DataProvider('provArrayValuesNotEqualTo')]
    #[DataProvider('provArrayValuesEqualButNotIdenticalTo')]
    public function testLogicalNotArrayValuesIdenticalTo(array $expect, mixed $actual, string $string)
    {
        self::assertThat($actual, self::logicalNot(self::arrayValuesIdenticalTo($expect)));
    }

    #[DataProvider('provArrayValuesIdenticalTo')]
    public function testAssertArrayValuesIdenticalTo(array $expect, mixed $actual, string $string)
    {
        self::assertArrayValuesIdenticalTo($expect, $actual);
    }

    #[DataProvider('provArrayValuesNotEqualTo')]
    #[DataProvider('provArrayValuesEqualButNotIdenticalTo')]
    public function testAssertArrayValuesIdenticalToFails(array $expect, mixed $actual, string $string)
    {
        $regexp = '/^Lorem ipsum.\n'.
            'Failed asserting that .+ is an array or ArrayAccess '.
            'with values identical to specified./';
        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessageMatches($regexp);

        self::assertArrayValuesIdenticalTo($expect, $actual, 'Lorem ipsum.');
    }

    #[DataProvider('provArrayValuesNotEqualTo')]
    public function testAssertNotArrayValuesIdenticalTo(array $expect, mixed $actual, string $string)
    {
        self::assertNotArrayValuesIdenticalTo($expect, $actual);
    }

    #[DataProvider('provArrayValuesIdenticalTo')]
    public function testAssertNotArrayValuesIdenticalToFails(array $expect, mixed $actual, string $string)
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
