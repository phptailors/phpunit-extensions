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
use Tailors\PHPUnit\Constraint\ProvClassPropertiesTrait;

/**
 * @small
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @coversNothing
 */
#[CoversClass(ClassPropertiesEqualToTrait::class)]
final class ClassPropertiesEqualToTraitTest extends TestCase
{
    use ClassPropertiesEqualToTrait;
    use ProvClassPropertiesTrait;

    #[DataProvider('provClassPropertiesIdenticalTo')]
    #[DataProvider('provClassPropertiesEqualButNotIdenticalTo')]
    public function testClassPropertiesEqualToSucceeds(array $expect, string $actual, string $string)
    {
        self::assertThat($actual, self::classPropertiesEqualTo($expect));
    }

    #[DataProvider('provClassPropertiesIdenticalTo')]
    #[DataProvider('provClassPropertiesEqualButNotIdenticalTo')]
    public function testAssertClassPropertiesEqualToSucceeds(array $expect, string $actual, string $string)
    {
        self::assertClassPropertiesEqualTo($expect, $actual);
    }

    #[DataProvider('provClassPropertiesNotEqualTo')]
    public function testAssertClassPropertiesEqualToFails(array $expect, string $actual, string $string)
    {
        $regexp = '/^Lorem ipsum.\n'.
            'Failed asserting that .+ is a class '.
            'with properties equal to specified./';
        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessageMatches($regexp);

        self::assertClassPropertiesEqualTo($expect, $actual, 'Lorem ipsum.');
    }

    #[DataProvider('provClassPropertiesNotEqualTo')]
    public function testNotClassPropertiesEqualToSucceeds(array $expect, string $actual, string $string)
    {
        self::assertThat($actual, self::logicalNot(self::classPropertiesEqualTo($expect)));
    }

    #[DataProvider('provClassPropertiesNotEqualTo')]
    public function testAssertNotClassPropertiesEqualToSucceeds(array $expect, string $actual, string $string)
    {
        self::assertNotClassPropertiesEqualTo($expect, $actual);
    }

    #[DataProvider('provClassPropertiesIdenticalTo')]
    #[DataProvider('provClassPropertiesEqualButNotIdenticalTo')]
    public function testAssertNotClassPropertiesEqualToFails(array $expect, string $actual, string $string)
    {
        $regexp = '/^Lorem ipsum.\n'.
            'Failed asserting that .+ fails to be a class '.
            'with properties equal to specified./';
        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessageMatches($regexp);

        self::assertNotClassPropertiesEqualTo($expect, $actual, 'Lorem ipsum.');
    }
}

// vim: syntax=php sw=4 ts=4 et:
