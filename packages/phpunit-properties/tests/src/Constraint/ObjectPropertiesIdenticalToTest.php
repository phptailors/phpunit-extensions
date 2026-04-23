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
use PHPUnit\Framework\Attributes\CoversTrait;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Small;
use PHPUnit\Framework\Constraint\Constraint;
use Tailors\PHPUnit\Values\ConstraintTestCase;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
#[CoversClass(ObjectPropertiesIdenticalTo::class)]
#[CoversClass(PropertiesConstraintTestCase::class)]
#[CoversTrait(ProvObjectPropertiesTrait::class)]
#[CoversClass(ConstraintTestCase::class)]
#[Small]
final class ObjectPropertiesIdenticalToTest extends PropertiesConstraintTestCase
{
    use ProvObjectPropertiesTrait;

    #[\Override]
    public static function subject(): string
    {
        return 'an object';
    }

    #[\Override]
    public static function selectable(): string
    {
        return 'properties';
    }

    #[\Override]
    public static function adjective(): string
    {
        return 'identical to';
    }

    #[\Override]
    public static function getConstraintClass(): string
    {
        return ObjectPropertiesIdenticalTo::class;
    }

    #[\Override]
    public static function createConstraint(...$args): Constraint
    {
        return ObjectPropertiesIdenticalTo::create(...$args);
    }

    #[DataProvider('provObjectPropertiesIdenticalTo')]
    public function testObjectPropertiesIdenticalToSucceeds(array $expect, mixed $actual, string $string): void
    {
        parent::examineValuesMatchSucceeds($expect, $actual);
    }

    #[DataProvider('provObjectPropertiesNotEqualTo')]
    #[DataProvider('provObjectPropertiesNotEqualToNonObject')]
    #[DataProvider('provObjectPropertiesEqualButNotIdenticalTo')]
    public function testObjectPropertiesIdenticalToFails(array $expect, mixed $actual, string $string): void
    {
        parent::examineValuesMatchFails($expect, $actual, $string);
    }

    #[DataProvider('provObjectPropertiesNotEqualTo')]
    #[DataProvider('provObjectPropertiesNotEqualToNonObject')]
    #[DataProvider('provObjectPropertiesEqualButNotIdenticalTo')]
    public function testNotObjectPropertiesIdenticalToSucceeds(array $expect, mixed $actual, string $string): void
    {
        parent::examineNotValuesMatchSucceeds($expect, $actual);
    }

    #[DataProvider('provObjectPropertiesIdenticalTo')]
    public function testNotObjectPropertiesIdenticalToFails(array $expect, mixed $actual, string $string): void
    {
        parent::examineNotValuesMatchFails($expect, $actual, $string);
    }
}

// vim: syntax=php sw=4 ts=4 et:
