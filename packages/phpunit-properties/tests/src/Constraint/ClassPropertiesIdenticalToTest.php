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
use PHPUnit\Framework\Attributes\Small;
use PHPUnit\Framework\Constraint\Constraint;
use Tailors\PHPUnit\Values\ConstraintTestCase;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
#[CoversClass(ClassPropertiesIdenticalTo::class)]
#[CoversClass(PropertiesConstraintTestCase::class)]
#[CoversClass(ProvClassPropertiesTrait::class)]
#[CoversClass(ConstraintTestCase::class)]
#[Small]
final class ClassPropertiesIdenticalToTest extends PropertiesConstraintTestCase
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
        return 'identical to';
    }

    public static function getConstraintClass(): string
    {
        return ClassPropertiesIdenticalTo::class;
    }

    public static function createConstraint(...$args): Constraint
    {
        return ClassPropertiesIdenticalTo::create(...$args);
    }

    #[DataProvider('provClassPropertiesIdenticalTo')]
    public function testClassPropertiesIdenticalToSucceeds(array $expect, mixed $actual, string $string): void
    {
        parent::examineValuesMatchSucceeds($expect, $actual);
    }

    #[DataProvider('provClassPropertiesNotEqualTo')]
    #[DataProvider('provClassPropertiesNotEqualToNonClass')]
    #[DataProvider('provClassPropertiesEqualButNotIdenticalTo')]
    public function testClassPropertiesIdenticalToFails(array $expect, mixed $actual, string $string): void
    {
        parent::examineValuesMatchFails($expect, $actual, $string);
    }

    #[DataProvider('provClassPropertiesNotEqualTo')]
    #[DataProvider('provClassPropertiesNotEqualToNonClass')]
    #[DataProvider('provClassPropertiesEqualButNotIdenticalTo')]
    public function testNotClassPropertiesIdenticalToSucceeds(array $expect, mixed $actual, string $string): void
    {
        parent::examineNotValuesMatchSucceeds($expect, $actual);
    }

    #[DataProvider('provClassPropertiesIdenticalTo')]
    public function testNotClassPropertiesIdenticalToFails(array $expect, mixed $actual, string $string): void
    {
        parent::examineNotValuesMatchFails($expect, $actual, $string);
    }
}

// vim: syntax=php sw=4 ts=4 et:
