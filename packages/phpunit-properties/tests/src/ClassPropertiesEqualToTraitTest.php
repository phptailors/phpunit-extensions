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
     */
    public function testClassPropertiesEqualToSucceeds(array $expect, string $class)
    {
        self::assertThat($class, self::classPropertiesEqualTo($expect));
    }

    /**
     * @dataProvider provClassPropertiesIdenticalTo
     * @dataProvider provClassPropertiesEqualButNotIdenticalTo
     */
    public function testAssertClassPropertiesEqualToSucceeds(array $expect, string $class)
    {
        self::assertClassPropertiesEqualTo($expect, $class);
    }

    /**
     * @dataProvider provClassPropertiesNotEqualTo
     */
    public function testAssertClassPropertiesEqualToFails(array $expect, string $class)
    {
        $regexp = '/^Lorem ipsum.\n'.
            'Failed asserting that .+ is a class '.
            'with properties equal to specified./';
        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessageMatches($regexp);

        self::assertClassPropertiesEqualTo($expect, $class, 'Lorem ipsum.');
    }

    /**
     * @dataProvider provClassPropertiesNotEqualTo
     */
    public function testNotClassPropertiesEqualToSucceeds(array $expect, string $class)
    {
        self::assertThat($class, self::logicalNot(self::classPropertiesEqualTo($expect)));
    }

    /**
     * @dataProvider provClassPropertiesNotEqualTo
     */
    public function testAssertNotClassPropertiesEqualToSucceeds(array $expect, string $class)
    {
        self::assertNotClassPropertiesEqualTo($expect, $class);
    }

    /**
     * @dataProvider provClassPropertiesIdenticalTo
     * @dataProvider provClassPropertiesEqualButNotIdenticalTo
     */
    public function testAssertNotClassPropertiesEqualToFails(array $expect, string $class)
    {
        $regexp = '/^Lorem ipsum.\n'.
            'Failed asserting that .+ fails to be a class '.
            'with properties equal to specified./';
        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessageMatches($regexp);

        self::assertNotClassPropertiesEqualTo($expect, $class, 'Lorem ipsum.');
    }
}

// vim: syntax=php sw=4 ts=4 et:
