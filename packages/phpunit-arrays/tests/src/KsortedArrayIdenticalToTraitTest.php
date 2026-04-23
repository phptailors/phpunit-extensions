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
use Tailors\PHPUnit\Constraint\KsortedArrayIdenticalTo;
use Tailors\PHPUnit\Constraint\ProvKsortedArrayTrait;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
#[CoversClass(KsortedArrayIdenticalToTrait::class)]
#[Small]
final class KsortedArrayIdenticalToTraitTest extends TestCase
{
    use KsortedArrayIdenticalToTrait;
    use ProvKsortedArrayTrait;

    public static function createConstraint(mixed ...$args): KsortedArrayIdenticalTo
    {
        return KsortedArrayIdenticalTo::create(...$args);
    }

    #[DataProvider('provKsortedArrayIdenticalTo')]
    public function testKsortedArrayIdenticalToSucceeds(array $expect, mixed $actual, string $string)
    {
        self::assertThat($actual, self::ksortedArrayIdenticalTo($expect));
    }

    #[DataProvider('provKsortedArrayIdenticalTo')]
    public function testAssertKsortedArrayIdenticalToSucceeds(array $expect, mixed $actual, string $string)
    {
        self::assertKsortedArrayIdenticalTo($expect, $actual);
    }

    #[DataProvider('provKsortedArrayNotEqualTo')]
    #[DataProvider('provKsortedArrayEqualButNotIdenticalTo')]
    public function testAssertKsortedArrayIdenticalToFails(array $expect, mixed $actual, string $string)
    {
        $regexp = '/^Lorem ipsum.\n'.
            'Failed asserting that .+ is an array '.
            'identical to specified one when ksorted./';
        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessageMatches($regexp);

        self::assertKsortedArrayIdenticalTo($expect, $actual, 'Lorem ipsum.');
    }

    #[DataProvider('provKsortedArrayNotEqualTo')]
    public function testNotKsortedArrayIdenticalToSucceeds(array $expect, mixed $actual, string $string)
    {
        self::assertThat($actual, self::logicalNot(self::ksortedArrayIdenticalTo($expect)));
    }

    #[DataProvider('provKsortedArrayNotEqualTo')]
    #[DataProvider('provKsortedArrayEqualButNotIdenticalTo')]
    public function testAssertNotKsortedArrayIdenticalToSucceeds(array $expect, mixed $actual, string $string)
    {
        self::assertNotKsortedArrayIdenticalTo($expect, $actual);
    }

    #[DataProvider('provKsortedArrayIdenticalTo')]
    public function testAssertNotKsortedArrayIdenticalToFails(array $expect, mixed $actual, string $string)
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
