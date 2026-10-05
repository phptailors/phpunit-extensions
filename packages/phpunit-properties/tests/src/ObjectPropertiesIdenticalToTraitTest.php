<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit;

use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\Constraint\ObjectPropertiesIdenticalTo;
use Tailors\PHPUnit\Constraint\ProvObjectPropertiesTrait;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\ObjectPropertiesIdenticalToTrait
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-import-type ArrayLike from \Tailors\PHPUnit\Common\TypesInterface
 * @psalm-type CreateConstraintArgs = list{ArrayLike}
 */
final class ObjectPropertiesIdenticalToTraitTest extends TestCase
{
    use ObjectPropertiesIdenticalToTrait;
    use ProvObjectPropertiesTrait;

    /**
     * @throws InvalidArgumentException
     *
     * @psalm-param CreateConstraintArgs $args
     */
    public static function createConstraint(array $args): Constraint
    {
        return ObjectPropertiesIdenticalTo::create(...$args);
    }

    /**
     * @dataProvider provObjectPropertiesIdenticalTo
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testObjectPropertiesIdenticalTo(array $expect, object $actual): void
    {
        self::assertThat($actual, self::objectPropertiesIdenticalTo($expect));
    }

    /**
     * @dataProvider provObjectPropertiesNotEqualTo
     * @dataProvider provObjectPropertiesEqualButNotIdenticalTo
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testLogicalNotObjectPropertiesIdenticalTo(array $expect, object $actual): void
    {
        self::assertThat($actual, self::logicalNot(self::objectPropertiesIdenticalTo($expect)));
    }

    /**
     * @dataProvider provObjectPropertiesIdenticalTo
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAssertObjectPropertiesIdenticalTo(array $expect, object $actual): void
    {
        self::assertObjectPropertiesIdenticalTo($expect, $actual);
    }

    /**
     * @dataProvider provObjectPropertiesNotEqualTo
     * @dataProvider provObjectPropertiesEqualButNotIdenticalTo
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAssertObjectPropertiesIdenticalToFails(array $expect, object $actual): void
    {
        $regexp = '/^Lorem ipsum.\n'.
            'Failed asserting that object class\@.+ is an object '.
            'with properties identical to specified./';
        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessageMatches($regexp);

        self::assertObjectPropertiesIdenticalTo($expect, $actual, 'Lorem ipsum.');
    }

    /**
     * @dataProvider provObjectPropertiesNotEqualTo
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAssertNotObjectPropertiesIdenticalTo(array $expect, object $actual): void
    {
        self::assertNotObjectPropertiesIdenticalTo($expect, $actual);
    }

    /**
     * @dataProvider provObjectPropertiesIdenticalTo
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAssertNotObjectPropertiesIdenticalToFails(array $expect, object $actual): void
    {
        $regexp = '/^Lorem ipsum.\n'.
            'Failed asserting that object class@.+ fails to be an object '.
            'with properties identical to specified./';
        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessageMatches($regexp);

        self::assertNotObjectPropertiesIdenticalTo($expect, $actual, 'Lorem ipsum.');
    }
}

// vim: syntax=php sw=4 ts=4 et:
