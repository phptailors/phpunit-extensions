<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit;

use PHPUnit\Framework\Attributes\CoversTrait;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Small;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\Constraint\ArrayValuesEqualTo;
use Tailors\PHPUnit\Constraint\ProvArrayValuesTrait;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
#[CoversTrait(ArrayValuesEqualToTrait::class)]
#[Small]
final class ArrayValuesEqualToTraitTest extends TestCase
{
    use ArrayValuesEqualToTrait;
    use ProvArrayValuesTrait;

    #[\Override]
    public static function createConstraint(mixed ...$args): ArrayValuesEqualTo
    {
        return ArrayValuesEqualTo::create(...$args);
    }

    #[DataProvider('provArrayValuesIdenticalTo')]
    #[DataProvider('provArrayValuesEqualButNotIdenticalTo')]
    public function testArrayValuesEqualToSucceeds(array $expect, mixed $actual, string $string)
    {
        self::assertThat($actual, self::arrayValuesEqualTo($expect));
    }

    #[DataProvider('provArrayValuesIdenticalTo')]
    #[DataProvider('provArrayValuesEqualButNotIdenticalTo')]
    public function testAssertArrayValuesEqualToSucceeds(array $expect, mixed $actual, string $string)
    {
        self::assertArrayValuesEqualTo($expect, $actual);
    }

    #[DataProvider('provArrayValuesNotEqualTo')]
    public function testAssertArrayValuesEqualToFails(array $expect, mixed $actual, string $string)
    {
        $regexp = '/^Lorem ipsum.\n'.
            'Failed asserting that .+ is an array or ArrayAccess '.
            'with values equal to specified./';
        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessageMatches($regexp);

        self::assertArrayValuesEqualTo($expect, $actual, 'Lorem ipsum.');
    }

    #[DataProvider('provArrayValuesNotEqualTo')]
    public function testNotArrayValuesEqualToSucceeds(array $expect, mixed $actual, string $string)
    {
        self::assertThat($actual, self::logicalNot(self::arrayValuesEqualTo($expect)));
    }

    #[DataProvider('provArrayValuesNotEqualTo')]
    public function testAssertNotArrayValuesEqualToSucceeds(array $expect, mixed $actual, string $string)
    {
        self::assertNotArrayValuesEqualTo($expect, $actual);
    }

    #[DataProvider('provArrayValuesIdenticalTo')]
    #[DataProvider('provArrayValuesEqualButNotIdenticalTo')]
    public function testAssertNotArrayValuesEqualToFails(array $expect, mixed $actual, string $string)
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
