<?php declare(strict_types=1);

/*
 * This file is part of php-tailors/phpunit-extensions.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

namespace Tailors\PHPUnit\Constraint;

use Tailors\PHPUnit\ExtendsClassTrait;
use Tailors\PHPUnit\ImplementsInterfaceTrait;
use Tailors\PHPUnit\Properties\EqualityComparator;

/**
 * @small
 * @covers \Tailors\PHPUnit\Constraint\ObjectPropertiesEqualTo
 * @covers \Tailors\PHPUnit\Constraint\PropertiesConstraintTestCase
 * @covers \Tailors\PHPUnit\Constraint\ProvObjectPropertiesTrait
 * @covers \Tailors\PHPUnit\Properties\AbstractConstraint
 *
 * @internal This class is not covered by the backward compatibility promise
 * @psalm-internal Tailors\PHPUnit
 */
final class ObjectPropertiesEqualToTest extends PropertiesConstraintTestCase
{
    use ImplementsInterfaceTrait;
    use ExtendsClassTrait;
    use ProvObjectPropertiesTrait;

    public static function subject(): string
    {
        return 'an object';
    }

    public static function adjective(): string
    {
        return 'equal to';
    }

    public static function constraintClass(): string
    {
        return ObjectPropertiesEqualTo::class;
    }

    public static function comparatorClass(): string
    {
        return EqualityComparator::class;
    }

    /**
     * @dataProvider provObjectPropertiesIdenticalTo
     * @dataProvider provObjectPropertiesEqualButNotIdenticalTo
     *
     * @param mixed $actual
     */
    public function testObjectPropertiesEqualToSucceeds(array $expect, $actual): void
    {
        parent::examinePropertiesMatchSucceeds($expect, $actual);
    }

    /**
     * @dataProvider provObjectPropertiesNotEqualTo
     * @dataProvider provObjectPropertiesNotEqualToNonObject
     *
     * @param mixed $actual
     */
    public function testObjectPropertiesEqualToFails(array $expect, $actual, string $string): void
    {
        parent::examinePropertiesMatchFails($expect, $actual, $string);
    }

    /**
     * @dataProvider provObjectPropertiesNotEqualTo
     * @dataProvider provObjectPropertiesNotEqualToNonObject
     *
     * @param mixed $actual
     */
    public function testNotObjectPropertiesEqualToSucceeds(array $expect, $actual): void
    {
        parent::examineNotPropertiesMatchSucceeds($expect, $actual);
    }

    /**
     * @dataProvider provObjectPropertiesIdenticalTo
     * @dataProvider provObjectPropertiesEqualButNotIdenticalTo
     *
     * @param mixed $actual
     */
    public function testNotObjectPropertiesEqualToFails(array $expect, $actual, string $string): void
    {
        parent::examineNotPropertiesMatchFails($expect, $actual, $string);
    }
}

// vim: syntax=php sw=4 ts=4 et:
