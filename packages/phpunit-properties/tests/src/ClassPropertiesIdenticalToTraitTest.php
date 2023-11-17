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
     */
    public function testClassPropertiesIdenticalTo(array $expect, string $class)
    {
        self::assertThat($class, self::classPropertiesIdenticalTo($expect));
    }

    /**
     * @dataProvider provClassPropertiesNotEqualTo
     * @dataProvider provClassPropertiesEqualButNotIdenticalTo
     */
    public function testLogicalNotClassPropertiesIdenticalTo(array $expect, string $class)
    {
        self::assertThat($class, self::logicalNot(self::classPropertiesIdenticalTo($expect)));
    }

    /**
     * @dataProvider provClassPropertiesIdenticalTo
     */
    public function testAssertClassPropertiesIdenticalTo(array $expect, string $class)
    {
        self::assertClassPropertiesIdenticalTo($expect, $class);
    }

    /**
     * @dataProvider provClassPropertiesNotEqualTo
     * @dataProvider provClassPropertiesEqualButNotIdenticalTo
     */
    public function testAssertClassPropertiesIdenticalToFails(array $expect, string $class)
    {
        $regexp = '/^Lorem ipsum.\n'.
            'Failed asserting that .+ is a class '.
            'with properties identical to specified./';
        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessageMatches($regexp);

        self::assertClassPropertiesIdenticalTo($expect, $class, 'Lorem ipsum.');
    }

    /**
     * @dataProvider provClassPropertiesNotEqualTo
     */
    public function testAssertNotClassPropertiesIdenticalTo(array $expect, string $class)
    {
        self::assertNotClassPropertiesIdenticalTo($expect, $class);
    }

    /**
     * @dataProvider provClassPropertiesIdenticalTo
     */
    public function testAssertNotClassPropertiesIdenticalToFails(array $expect, string $class)
    {
        $regexp = '/^Lorem ipsum.\n'.
            'Failed asserting that .+ fails to be a class '.
            'with properties identical to specified./';
        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessageMatches($regexp);

        self::assertNotClassPropertiesIdenticalTo($expect, $class, 'Lorem ipsum.');
    }
}

// vim: syntax=php sw=4 ts=4 et:
