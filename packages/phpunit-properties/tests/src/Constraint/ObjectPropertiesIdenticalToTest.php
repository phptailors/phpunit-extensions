<?php declare(strict_types=1);

/*
 * This file is part of php-tailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Constraint;

use Tailors\PHPUnit\Comparator\IdentityComparator;
use Tailors\PHPUnit\Values\AbstractConstraint;

/**
 * @small
 * @covers \Tailors\PHPUnit\Constraint\ObjectPropertiesIdenticalTo
 * @covers \Tailors\PHPUnit\Constraint\PropertiesConstraintTestCase
 * @covers \Tailors\PHPUnit\Constraint\ProvObjectPropertiesTrait
 * @covers \Tailors\PHPUnit\Values\AbstractConstraint
 * @covers \Tailors\PHPUnit\Values\ConstraintTestCase
 *
 * @internal This class is not covered by the backward compatibility promise
 * @psalm-internal Tailors\PHPUnit
 */
final class ObjectPropertiesIdenticalToTest extends PropertiesConstraintTestCase
{
    use ProvObjectPropertiesTrait;

    public static function subject(): string
    {
        return 'an object';
    }

    public static function selectable(): string
    {
        return 'properties';
    }

    public static function adjective(): string
    {
        return 'identical to';
    }

    public static function createConstraint(...$args): AbstractConstraint
    {
        return ObjectPropertiesIdenticalTo::create(...$args);
    }

    public static function constraintClass(): string
    {
        return ObjectPropertiesIdenticalTo::class;
    }

    public static function comparatorClass(): string
    {
        return IdentityComparator::class;
    }

    /**
     * @dataProvider provObjectPropertiesIdenticalTo
     *
     * @param mixed $actual
     */
    public function testObjectPropertiesIdenticalToSucceeds(array $expect, $actual): void
    {
        parent::examineValuesMatchSucceeds($expect, $actual);
    }

    /**
     * @dataProvider provObjectPropertiesNotEqualTo
     * @dataProvider provObjectPropertiesNotEqualToNonObject
     * @dataProvider provObjectPropertiesEqualButNotIdenticalTo
     *
     * @param mixed $actual
     */
    public function testObjectPropertiesIdenticalToFails(array $expect, $actual, string $string): void
    {
        parent::examineValuesMatchFails($expect, $actual, $string);
    }

    /**
     * @dataProvider provObjectPropertiesNotEqualTo
     * @dataProvider provObjectPropertiesNotEqualToNonObject
     * @dataProvider provObjectPropertiesEqualButNotIdenticalTo
     *
     * @param mixed $actual
     */
    public function testNotObjectPropertiesIdenticalToSucceeds(array $expect, $actual): void
    {
        parent::examineNotValuesMatchSucceeds($expect, $actual);
    }

    /**
     * @dataProvider provObjectPropertiesIdenticalTo
     *
     * @param mixed $actual
     */
    public function testNotObjectPropertiesIdenticalToFails(array $expect, $actual, string $string): void
    {
        parent::examineNotValuesMatchFails($expect, $actual, $string);
    }
}

// vim: syntax=php sw=4 ts=4 et:
