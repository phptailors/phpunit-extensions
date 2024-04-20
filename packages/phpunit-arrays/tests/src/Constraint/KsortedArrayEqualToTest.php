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
use Tailors\PHPUnit\Arrays\AbstractKsortedConstraint;
use Tailors\PHPUnit\Arrays\KsortedConstraintTestCase;
use Tailors\PHPUnit\Comparator\EqualityComparator;

/**
 * @small
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @coversNothing
 */
#[CoversClass(AbstractKsortedConstraint::class)]
#[CoversClass(KsortedConstraintTestCase::class)]
#[CoversClass(KsortedArrayEqualTo::class)]
#[CoversClass(ProvKsortedArrayTrait::class)]
final class KsortedArrayEqualToTest extends KsortedConstraintTestCase
{
    use ProvKsortedArrayTrait;

    public static function adjective(): string
    {
        return 'equal to';
    }

    public static function getConstraintClass(): string
    {
        return KsortedArrayEqualTo::class;
    }

    public static function getComparatorClass(): string
    {
        return EqualityComparator::class;
    }

    /**
     * @param mixed $actual
     */
    #[DataProvider('provKsortedArrayIdenticalTo')]
    #[DataProvider('provKsortedArrayEqualButNotIdenticalTo')]
    public function testKsortedArrayEqualToSucceeds(array $expect, $actual, string $string): void
    {
        parent::examineConstraintMatchSucceeds([$expect], $actual);
    }

    /**
     * @param mixed $actual
     */
    #[DataProvider('provKsortedArrayNotEqualTo')]
    #[DataProvider('provKsortedArrayNotEqualToNonArray')]
    public function testKsortedArrayEqualToFails(array $expect, $actual, string $string): void
    {
        parent::examineConstraintMatchFails([$expect], $actual, self::message($string));
    }

    /**
     * @param mixed $actual
     */
    #[DataProvider('provKsortedArrayNotEqualTo')]
    #[DataProvider('provKsortedArrayNotEqualToNonArray')]
    public function testNotKsortedArrayEqualToSucceeds(array $expect, $actual, string $string): void
    {
        parent::examineNotConstraintMatchSucceeds([$expect], $actual);
    }

    /**
     * @param mixed $actual
     */
    #[DataProvider('provKsortedArrayIdenticalTo')]
    #[DataProvider('provKsortedArrayEqualButNotIdenticalTo')]
    public function testNotKsortedArrayEqualToFails(array $expect, $actual, string $string): void
    {
        parent::examineNotConstraintMatchFails([$expect], $actual, self::message($string, true));
    }
}

// vim: syntax=php sw=4 ts=4 et:
