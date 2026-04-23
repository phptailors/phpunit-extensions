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
use Tailors\PHPUnit\Arrays\AbstractKsortedConstraint;
use Tailors\PHPUnit\Arrays\KsortedConstraintTestCase;
use Tailors\PHPUnit\Comparator\EqualityComparator;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
#[CoversClass(AbstractKsortedConstraint::class)]
#[CoversClass(KsortedConstraintTestCase::class)]
#[CoversClass(KsortedArrayEqualTo::class)]
#[CoversTrait(ProvKsortedArrayTrait::class)]
#[Small]
final class KsortedArrayEqualToTest extends KsortedConstraintTestCase
{
    use ProvKsortedArrayTrait;

    #[\Override]
    public static function adjective(): string
    {
        return 'equal to';
    }

    #[\Override]
    public static function getConstraintClass(): string
    {
        return KsortedArrayEqualTo::class;
    }

    #[\Override]
    public static function getComparatorClass(): string
    {
        return EqualityComparator::class;
    }

    #[\Override]
    public static function createConstraint(...$args): Constraint
    {
        return KsortedArrayEqualTo::create(...$args);
    }

    #[DataProvider('provKsortedArrayIdenticalTo')]
    #[DataProvider('provKsortedArrayEqualButNotIdenticalTo')]
    public function testKsortedArrayEqualToSucceeds(array $expect, mixed $actual, string $string): void
    {
        parent::examineConstraintMatchSucceeds([$expect], $actual);
    }

    #[DataProvider('provKsortedArrayNotEqualTo')]
    #[DataProvider('provKsortedArrayNotEqualToNonArray')]
    public function testKsortedArrayEqualToFails(array $expect, mixed $actual, string $string): void
    {
        parent::examineConstraintMatchFails([$expect], $actual, self::message($string));
    }

    #[DataProvider('provKsortedArrayNotEqualTo')]
    #[DataProvider('provKsortedArrayNotEqualToNonArray')]
    public function testNotKsortedArrayEqualToSucceeds(array $expect, mixed $actual, string $string): void
    {
        parent::examineNotConstraintMatchSucceeds([$expect], $actual);
    }

    #[DataProvider('provKsortedArrayIdenticalTo')]
    #[DataProvider('provKsortedArrayEqualButNotIdenticalTo')]
    public function testNotKsortedArrayEqualToFails(array $expect, mixed $actual, string $string): void
    {
        parent::examineNotConstraintMatchFails([$expect], $actual, self::message($string, true));
    }
}

// vim: syntax=php sw=4 ts=4 et:
