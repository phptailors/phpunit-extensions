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
use Tailors\PHPUnit\Constraint\ObjectPropertiesEqualTo;
use Tailors\PHPUnit\Constraint\ProvObjectPropertiesTrait;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\ObjectPropertiesEqualToTrait
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
final class ObjectPropertiesEqualToTraitTest extends TestCase
{
    use ObjectPropertiesEqualToTrait;
    use ProvObjectPropertiesTrait;

    public static function createConstraint(...$args): ObjectPropertiesEqualTo
    {
        return ObjectPropertiesEqualTo::create(...$args);
    }

    /**
     * @dataProvider provObjectPropertiesIdenticalTo
     * @dataProvider provObjectPropertiesEqualButNotIdenticalTo
     */
    public function testObjectPropertiesEqualTo(array $expect, object $actual, string $string)
    {
        self::assertThat($actual, self::objectPropertiesEqualTo($expect));
    }

    /**
     * @dataProvider provObjectPropertiesNotEqualTo
     */
    public function testLogicalNotObjectPropertiesEqualTo(array $expect, object $actual, string $string)
    {
        self::assertThat($actual, self::logicalNot(self::objectPropertiesEqualTo($expect)));
    }

    /**
     * @dataProvider provObjectPropertiesIdenticalTo
     * @dataProvider provObjectPropertiesEqualButNotIdenticalTo
     */
    public function testAssertObjectPropertiesEqualTo(array $expect, object $actual, string $string)
    {
        self::assertObjectPropertiesEqualTo($expect, $actual);
    }

    /**
     * @dataProvider provObjectPropertiesNotEqualTo
     */
    public function testAssertObjectPropertiesEqualToFails(array $expect, object $actual, string $string)
    {
        $regexp = '/^Lorem ipsum.\n'.
            'Failed asserting that object class\@.+ is an object '.
            'with properties equal to specified./';
        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessageMatches($regexp);

        self::assertObjectPropertiesEqualTo($expect, $actual, 'Lorem ipsum.');
    }

    /**
     * @dataProvider provObjectPropertiesNotEqualTo
     */
    public function testAssertNotObjectPropertiesEqualTo(array $expect, object $actual, string $string)
    {
        self::assertNotObjectPropertiesEqualTo($expect, $actual);
    }

    /**
     * @dataProvider provObjectPropertiesIdenticalTo
     * @dataProvider provObjectPropertiesEqualButNotIdenticalTo
     */
    public function testAssertNotObjectPropertiesEqualToFails(array $expect, object $actual, string $string)
    {
        $regexp = '/^Lorem ipsum.\n'.
            'Failed asserting that object class@.+ fails to be an object '.
            'with properties equal to specified./';
        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessageMatches($regexp);

        self::assertNotObjectPropertiesEqualTo($expect, $actual, 'Lorem ipsum.');
    }
}

// vim: syntax=php sw=4 ts=4 et:
