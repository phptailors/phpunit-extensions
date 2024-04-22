<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Constraint;

use PHPUnit\Framework\Constraint\Constraint;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\Constraint\ObjectPropertiesEqualTo
 * @covers \Tailors\PHPUnit\Constraint\PropertiesConstraintTestCase
 * @covers \Tailors\PHPUnit\Constraint\ProvObjectPropertiesTrait
 * @covers \Tailors\PHPUnit\Values\ConstraintTestCase
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
final class ObjectPropertiesEqualToTest extends PropertiesConstraintTestCase
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
        return 'equal to';
    }

    public static function getConstraintClass(): string
    {
        return ObjectPropertiesEqualTo::class;
    }

    public static function createConstraint(...$args): Constraint
    {
        return ObjectPropertiesEqualTo::create(...$args);
    }

    /**
     * @dataProvider provObjectPropertiesIdenticalTo
     * @dataProvider provObjectPropertiesEqualButNotIdenticalTo
     *
     * @param mixed $actual
     */
    public function testObjectPropertiesEqualToSucceeds(array $expect, $actual, string $string): void
    {
        parent::examineValuesMatchSucceeds($expect, $actual);
    }

    /**
     * @dataProvider provObjectPropertiesNotEqualTo
     * @dataProvider provObjectPropertiesNotEqualToNonObject
     *
     * @param mixed $actual
     */
    public function testObjectPropertiesEqualToFails(array $expect, $actual, string $string): void
    {
        parent::examineValuesMatchFails($expect, $actual, $string);
    }

    /**
     * @dataProvider provObjectPropertiesNotEqualTo
     * @dataProvider provObjectPropertiesNotEqualToNonObject
     *
     * @param mixed $actual
     */
    public function testNotObjectPropertiesEqualToSucceeds(array $expect, $actual, string $string): void
    {
        parent::examineNotValuesMatchSucceeds($expect, $actual);
    }

    /**
     * @dataProvider provObjectPropertiesIdenticalTo
     * @dataProvider provObjectPropertiesEqualButNotIdenticalTo
     *
     * @param mixed $actual
     */
    public function testNotObjectPropertiesEqualToFails(array $expect, $actual, string $string): void
    {
        parent::examineNotValuesMatchFails($expect, $actual, $string);
    }
}

// vim: syntax=php sw=4 ts=4 et:
