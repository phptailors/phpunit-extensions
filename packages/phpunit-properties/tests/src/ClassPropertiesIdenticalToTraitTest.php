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
 * @covers \Tailors\PHPUnit\ClassPropertiesIdenticalToTrait
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
final class ClassPropertiesIdenticalToTraitTest extends TestCase
{
    use ClassPropertiesIdenticalToTrait;
    use ProvClassPropertiesTrait;

    /**
     * @dataProvider provClassPropertiesIdenticalTo
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testClassPropertiesIdenticalTo(array $expect, string $actual): void
    {
        self::assertThat($actual, self::classPropertiesIdenticalTo($expect));
    }

    /**
     * @dataProvider provClassPropertiesNotEqualTo
     * @dataProvider provClassPropertiesEqualButNotIdenticalTo
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testLogicalNotClassPropertiesIdenticalTo(array $expect, string $actual): void
    {
        self::assertThat($actual, self::logicalNot(self::classPropertiesIdenticalTo($expect)));
    }

    /**
     * @dataProvider provClassPropertiesIdenticalTo
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAssertClassPropertiesIdenticalTo(array $expect, string $actual): void
    {
        self::assertClassPropertiesIdenticalTo($expect, $actual);
    }

    /**
     * @dataProvider provClassPropertiesNotEqualTo
     * @dataProvider provClassPropertiesEqualButNotIdenticalTo
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAssertClassPropertiesIdenticalToFails(array $expect, string $actual): void
    {
        $regexp = '/^Lorem ipsum.\n'.
            'Failed asserting that .+ is a class '.
            'with properties identical to specified./';
        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessageMatches($regexp);

        self::assertClassPropertiesIdenticalTo($expect, $actual, 'Lorem ipsum.');
    }

    /**
     * @dataProvider provClassPropertiesNotEqualTo
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAssertNotClassPropertiesIdenticalTo(array $expect, string $actual): void
    {
        self::assertNotClassPropertiesIdenticalTo($expect, $actual);
    }

    /**
     * @dataProvider provClassPropertiesIdenticalTo
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAssertNotClassPropertiesIdenticalToFails(array $expect, string $actual): void
    {
        $regexp = '/^Lorem ipsum.\n'.
            'Failed asserting that .+ fails to be a class '.
            'with properties identical to specified./';
        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessageMatches($regexp);

        self::assertNotClassPropertiesIdenticalTo($expect, $actual, 'Lorem ipsum.');
    }
}

// vim: syntax=php sw=4 ts=4 et:
