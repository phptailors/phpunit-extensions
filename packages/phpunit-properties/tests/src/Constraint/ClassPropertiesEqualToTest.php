<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Constraint;

use Tailors\PHPUnit\Values\AbstractConstraint;

/**
 * @small
 * @covers \Tailors\PHPUnit\Constraint\ClassPropertiesEqualTo
 * @covers \Tailors\PHPUnit\Constraint\PropertiesConstraintTestCase
 * @covers \Tailors\PHPUnit\Constraint\ProvClassPropertiesTrait
 * @covers \Tailors\PHPUnit\Values\ConstraintTestCase
 *
 * @internal This class is not covered by the backward compatibility promise
 * @psalm-internal Tailors\PHPUnit
 */
final class ClassPropertiesEqualToTest extends PropertiesConstraintTestCase
{
    use ProvClassPropertiesTrait;

    public static function subject(): string
    {
        return 'a class';
    }

    public static function selectable(): string
    {
        return 'properties';
    }

    public static function adjective(): string
    {
        return 'equal to';
    }

    public function createConstraint(...$args): AbstractConstraint
    {
        return ClassPropertiesEqualTo::create(...$args);
    }

    /**
     * @dataProvider provClassPropertiesIdenticalTo
     * @dataProvider provClassPropertiesEqualButNotIdenticalTo
     *
     * @param mixed $actual
     */
    public function testPropertiesEqualToSucceeds(array $expect, $actual): void
    {
        parent::examineValuesMatchSucceeds($expect, $actual);
    }

    /**
     * @dataProvider provClassPropertiesNotEqualTo
     * @dataProvider provClassPropertiesNotEqualToNonClass
     *
     * @param mixed $actual
     */
    public function testPropertiesEqualToFails(array $expect, $actual, string $string): void
    {
        parent::examineValuesMatchFails($expect, $actual, $string);
    }

    /**
     * @dataProvider provClassPropertiesNotEqualTo
     * @dataProvider provClassPropertiesNotEqualToNonClass
     *
     * @param mixed $actual
     */
    public function testNotClassPropertiesEqualToSucceeds(array $expect, $actual): void
    {
        parent::examineNotValuesMatchSucceeds($expect, $actual);
    }

    /**
     * @dataProvider provClassPropertiesIdenticalTo
     * @dataProvider provClassPropertiesEqualButNotIdenticalTo
     *
     * @param mixed $actual
     */
    public function testNotClassPropertiesEqualToFails(array $expect, $actual, string $string): void
    {
        parent::examineNotValuesMatchFails($expect, $actual, $string);
    }
}

// vim: syntax=php sw=4 ts=4 et:
