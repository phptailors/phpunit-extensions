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
use Tailors\PHPUnit\Constraint\KsortedArrayEqualTo;
use Tailors\PHPUnit\Constraint\ProvKsortedArrayTrait;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
#[CoversTrait(KsortedArrayEqualToTrait::class)]
#[Small]
final class KsortedArrayEqualToTraitTest extends TestCase
{
    use KsortedArrayEqualToTrait;
    use ProvKsortedArrayTrait;

    #[\Override]
    public static function createConstraint(mixed ...$args): KsortedArrayEqualTo
    {
        return KsortedArrayEqualTo::create(...$args);
    }

    #[DataProvider('provKsortedArrayIdenticalTo')]
    #[DataProvider('provKsortedArrayEqualButNotIdenticalTo')]
    public function testKsortedArrayEqualToSucceeds(array $expect, mixed $actual, string $string)
    {
        self::assertThat($actual, self::ksortedArrayEqualTo($expect));
    }

    #[DataProvider('provKsortedArrayIdenticalTo')]
    #[DataProvider('provKsortedArrayEqualButNotIdenticalTo')]
    public function testAssertKsortedArrayEqualToSucceeds(array $expect, mixed $actual, string $string)
    {
        self::assertKsortedArrayEqualTo($expect, $actual);
    }

    #[DataProvider('provKsortedArrayNotEqualTo')]
    public function testAssertKsortedArrayEqualToFails(array $expect, mixed $actual, string $string)
    {
        $regexp = '/^Lorem ipsum.\n'.
            'Failed asserting that .+ is an array '.
            'equal to specified one when ksorted./';
        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessageMatches($regexp);

        self::assertKsortedArrayEqualTo($expect, $actual, 'Lorem ipsum.');
    }

    #[DataProvider('provKsortedArrayNotEqualTo')]
    public function testNotKsortedArrayEqualToSucceeds(array $expect, mixed $actual, string $string)
    {
        self::assertThat($actual, self::logicalNot(self::ksortedArrayEqualTo($expect)));
    }

    #[DataProvider('provKsortedArrayNotEqualTo')]
    public function testAssertNotKsortedArrayEqualToSucceeds(array $expect, mixed $actual, string $string)
    {
        self::assertNotKsortedArrayEqualTo($expect, $actual);
    }

    #[DataProvider('provKsortedArrayIdenticalTo')]
    #[DataProvider('provKsortedArrayEqualButNotIdenticalTo')]
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
