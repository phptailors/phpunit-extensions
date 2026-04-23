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
#[CoversClass(ArrayValuesEqualTo::class)]
#[CoversTrait(ProvArrayValuesTrait::class)]
#[CoversClass(ConstraintTestCase::class)]
#[Small]
final class ArrayValuesEqualToTest extends ConstraintTestCase
{
    use ProvArrayValuesTrait;

    #[\Override]
    public static function subject(): string
    {
        return 'an array or ArrayAccess';
    }

    #[\Override]
    public static function selectable(): string
    {
        return 'values';
    }

    #[\Override]
    public static function adjective(): string
    {
        return 'equal to';
    }

    #[\Override]
    public static function getConstraintClass(): string
    {
        return ArrayValuesEqualTo::class;
    }

    #[\Override]
    public static function createConstraint(...$args): Constraint
    {
        return ArrayValuesEqualTo::create(...$args);
    }

    #[DataProvider('provArrayValuesIdenticalTo')]
    #[DataProvider('provArrayValuesEqualButNotIdenticalTo')]
    public function testArrayValuesEqualToSucceeds(array $expect, mixed $actual, string $string): void
    {
        parent::examineValuesMatchSucceeds($expect, $actual);
    }

    #[DataProvider('provArrayValuesNotEqualTo')]
    #[DataProvider('provArrayValuesNotEqualToNonArray')]
    public function testArrayValuesEqualToFails(array $expect, mixed $actual, string $string): void
    {
        parent::examineValuesMatchFails($expect, $actual, $string);
    }

    #[DataProvider('provArrayValuesNotEqualTo')]
    #[DataProvider('provArrayValuesNotEqualToNonArray')]
    public function testNotArrayValuesEqualToSucceeds(array $expect, mixed $actual, string $string): void
    {
        parent::examineNotValuesMatchSucceeds($expect, $actual);
    }

    #[DataProvider('provArrayValuesIdenticalTo')]
    #[DataProvider('provArrayValuesEqualButNotIdenticalTo')]
    public function testNotArrayValuesEqualToFails(array $expect, mixed $actual, string $string): void
    {
        parent::examineNotValuesMatchFails($expect, $actual, $string);
    }
}

// vim: syntax=php sw=4 ts=4 et:
