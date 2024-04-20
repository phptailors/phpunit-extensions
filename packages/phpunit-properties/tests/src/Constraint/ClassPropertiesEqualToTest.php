<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Constraint;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Constraint\Constraint;
use Tailors\PHPUnit\Values\ConstraintTestCase;

/**
 * @small
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @coversNothing
 */
#[CoversClass(ClassPropertiesEqualTo::class)]
#[CoversClass(PropertiesConstraintTestCase::class)]
#[CoversClass(ProvClassPropertiesTrait::class)]
#[CoversClass(ConstraintTestCase::class)]
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

    public static function getConstraintClass(): string
    {
        return ClassPropertiesEqualTo::class;
    }

    public static function createConstraint(...$args): Constraint
    {
        return ClassPropertiesEqualTo::create(...$args);
    }

    /**
     * @param mixed $actual
     */
    #[DataProvider('provClassPropertiesIdenticalTo')]
    #[DataProvider('provClassPropertiesEqualButNotIdenticalTo')]
    public function testPropertiesEqualToSucceeds(array $expect, $actual, string $string): void
    {
        parent::examineValuesMatchSucceeds($expect, $actual);
    }

    /**
     * @param mixed $actual
     */
    #[DataProvider('provClassPropertiesNotEqualTo')]
    #[DataProvider('provClassPropertiesNotEqualToNonClass')]
    public function testPropertiesEqualToFails(array $expect, $actual, string $string): void
    {
        parent::examineValuesMatchFails($expect, $actual, $string);
    }

    /**
     * @param mixed $actual
     */
    #[DataProvider('provClassPropertiesNotEqualTo')]
    #[DataProvider('provClassPropertiesNotEqualToNonClass')]
    public function testNotClassPropertiesEqualToSucceeds(array $expect, $actual, string $string): void
    {
        parent::examineNotValuesMatchSucceeds($expect, $actual);
    }

    /**
     * @param mixed $actual
     */
    #[DataProvider('provClassPropertiesIdenticalTo')]
    #[DataProvider('provClassPropertiesEqualButNotIdenticalTo')]
    public function testNotClassPropertiesEqualToFails(array $expect, $actual, string $string): void
    {
        parent::examineNotValuesMatchFails($expect, $actual, $string);
    }
}

// vim: syntax=php sw=4 ts=4 et:
