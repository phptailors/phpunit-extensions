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
use Tailors\PHPUnit\Constraint\ProvClassPropertiesTrait;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\ClassPropertiesEqualToTrait
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
final class ClassPropertiesEqualToTraitTest extends TestCase
{
    use ClassPropertiesEqualToTrait;
    use ProvClassPropertiesTrait;

    /**
     * @dataProvider provClassPropertiesIdenticalTo
     * @dataProvider provClassPropertiesEqualButNotIdenticalTo
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testClassPropertiesEqualToSucceeds(array $expect, string $actual): void
    {
        self::assertThat($actual, self::classPropertiesEqualTo($expect));
    }

    /**
     * @dataProvider provClassPropertiesIdenticalTo
     * @dataProvider provClassPropertiesEqualButNotIdenticalTo
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAssertClassPropertiesEqualToSucceeds(array $expect, string $actual): void
    {
        self::assertClassPropertiesEqualTo($expect, $actual);
    }

    /**
     * @dataProvider provClassPropertiesNotEqualTo
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAssertClassPropertiesEqualToFails(array $expect, string $actual): void
    {
        $regexp = '/^Lorem ipsum.\n'.
            'Failed asserting that .+ is a class '.
            'with properties equal to the specified ones./';
        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessageMatches($regexp);

        self::assertClassPropertiesEqualTo($expect, $actual, 'Lorem ipsum.');
    }

    /**
     * @dataProvider provClassPropertiesNotEqualTo
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testNotClassPropertiesEqualToSucceeds(array $expect, string $actual): void
    {
        self::assertThat($actual, self::logicalNot(self::classPropertiesEqualTo($expect)));
    }

    /**
     * @dataProvider provClassPropertiesNotEqualTo
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAssertNotClassPropertiesEqualToSucceeds(array $expect, string $actual): void
    {
        self::assertNotClassPropertiesEqualTo($expect, $actual);
    }

    /**
     * @dataProvider provClassPropertiesIdenticalTo
     * @dataProvider provClassPropertiesEqualButNotIdenticalTo
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAssertNotClassPropertiesEqualToFails(array $expect, string $actual): void
    {
        $regexp = '/^Lorem ipsum.\n'.
            'Failed asserting that .+ fails to be a class '.
            'with properties equal to the specified ones./';
        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessageMatches($regexp);

        self::assertNotClassPropertiesEqualTo($expect, $actual, 'Lorem ipsum.');
    }
}

// vim: syntax=php sw=4 ts=4 et:
