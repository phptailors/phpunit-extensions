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
#[CoversClass(ArrayValuesEqualTo::class)]
#[CoversClass(ProvArrayValuesTrait::class)]
#[CoversClass(ConstraintTestCase::class)]
final class ArrayValuesEqualToTest extends ConstraintTestCase
{
    use ProvArrayValuesTrait;

    public static function subject(): string
    {
        return 'an array or ArrayAccess';
    }

    public static function selectable(): string
    {
        return 'values';
    }

    public static function adjective(): string
    {
        return 'equal to';
    }

    public static function getConstraintClass(): string
    {
        return ArrayValuesEqualTo::class;
    }

    public static function createConstraint(...$args): Constraint
    {
        return ArrayValuesEqualTo::create(...$args);
    }

    /**
     * @param mixed $actual
     */
    #[DataProvider('provArrayValuesIdenticalTo')]
    #[DataProvider('provArrayValuesEqualButNotIdenticalTo')]
    public function testArrayValuesEqualToSucceeds(array $expect, $actual, string $string): void
    {
        parent::examineValuesMatchSucceeds($expect, $actual);
    }

    /**
     * @param mixed $actual
     */
    #[DataProvider('provArrayValuesNotEqualTo')]
    #[DataProvider('provArrayValuesNotEqualToNonArray')]
    public function testArrayValuesEqualToFails(array $expect, $actual, string $string): void
    {
        parent::examineValuesMatchFails($expect, $actual, $string);
    }

    /**
     * @param mixed $actual
     */
    #[DataProvider('provArrayValuesNotEqualTo')]
    #[DataProvider('provArrayValuesNotEqualToNonArray')]
    public function testNotArrayValuesEqualToSucceeds(array $expect, $actual, string $string): void
    {
        parent::examineNotValuesMatchSucceeds($expect, $actual);
    }

    /**
     * @param mixed $actual
     */
    #[DataProvider('provArrayValuesIdenticalTo')]
    #[DataProvider('provArrayValuesEqualButNotIdenticalTo')]
    public function testNotArrayValuesEqualToFails(array $expect, $actual, string $string): void
    {
        parent::examineNotValuesMatchFails($expect, $actual, $string);
    }
}

// vim: syntax=php sw=4 ts=4 et:
