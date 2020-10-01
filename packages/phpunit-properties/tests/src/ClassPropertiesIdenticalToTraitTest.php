<?php

declare(strict_types=1);

/*
 * This file is part of php-fox/phpunit-extensions.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

namespace PHPFox\PHPUnit;

use PHPUnit\Framework\Constraint\UnaryOperator;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use PHPFox\PHPUnit\Constraint\ProvClassPropertiesTrait;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \PHPFox\PHPUnit\ClassPropertiesIdenticalToTrait
 * @small
 *
 * @internal
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
