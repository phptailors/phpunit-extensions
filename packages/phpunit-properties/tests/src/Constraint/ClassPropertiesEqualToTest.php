<?php declare(strict_types=1);

/*
 * This file is part of php-tailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Constraint;

use Tailors\PHPUnit\Comparator\EqualityComparator;

/**
 * @small
 * @covers \Tailors\PHPUnit\Constraint\ClassPropertiesEqualTo
 * @covers \Tailors\PHPUnit\Constraint\PropertiesConstraintTestCase
 * @covers \Tailors\PHPUnit\Constraint\ProvClassPropertiesTrait
 * @covers \Tailors\PHPUnit\Properties\AbstractConstraint
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

    public static function adjective(): string
    {
        return 'equal to';
    }

    public static function constraintClass(): string
    {
        return ClassPropertiesEqualTo::class;
    }

    public static function comparatorClass(): string
    {
        return EqualityComparator::class;
    }

    /**
     * @dataProvider provClassPropertiesIdenticalTo
     * @dataProvider provClassPropertiesEqualButNotIdenticalTo
     *
     * @param mixed $actual
     */
    public function testPropertiesEqualToSucceeds(array $expect, $actual): void
    {
        parent::examinePropertiesMatchSucceeds($expect, $actual);
    }

    /**
     * @dataProvider provClassPropertiesNotEqualTo
     * @dataProvider provClassPropertiesNotEqualToNonClass
     *
     * @param mixed $actual
     */
    public function testPropertiesEqualToFails(array $expect, $actual, string $string): void
    {
        parent::examinePropertiesMatchFails($expect, $actual, $string);
    }

    /**
     * @dataProvider provClassPropertiesNotEqualTo
     * @dataProvider provClassPropertiesNotEqualToNonClass
     *
     * @param mixed $actual
     */
    public function testNotClassPropertiesEqualToSucceeds(array $expect, $actual): void
    {
        parent::examineNotPropertiesMatchSucceeds($expect, $actual);
    }

    /**
     * @dataProvider provClassPropertiesIdenticalTo
     * @dataProvider provClassPropertiesEqualButNotIdenticalTo
     *
     * @param mixed $actual
     */
    public function testNotClassPropertiesEqualToFails(array $expect, $actual, string $string): void
    {
        parent::examineNotPropertiesMatchFails($expect, $actual, $string);
    }
}

// vim: syntax=php sw=4 ts=4 et:
