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
use Tailors\PHPUnit\Comparator\IdentityComparator;

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
#[CoversClass(KsortedArrayIdenticalTo::class)]
#[CoversClass(ProvKsortedArrayTrait::class)]
final class KsortedArrayIdenticalToTest extends KsortedConstraintTestCase
{
    use ProvKsortedArrayTrait;

    public static function adjective(): string
    {
        return 'identical to';
    }

    public static function getConstraintClass(): string
    {
        return KsortedArrayIdenticalTo::class;
    }

    public static function getComparatorClass(): string
    {
        return IdentityComparator::class;
    }

    /**
     * @param mixed $actual
     */
    #[DataProvider('provKsortedArrayIdenticalTo')]
    public function testKsortedArrayIdenticalToSucceeds(array $expect, $actual, string $string): void
    {
        parent::examineConstraintMatchSucceeds([$expect], $actual);
    }

    /**
     * @param mixed $actual
     */
    #[DataProvider('provKsortedArrayEqualButNotIdenticalTo')]
    #[DataProvider('provKsortedArrayNotEqualTo')]
    #[DataProvider('provKsortedArrayNotEqualToNonArray')]
    public function testKsortedArrayIdenticalToFails(array $expect, $actual, string $string): void
    {
        parent::examineConstraintMatchFails([$expect], $actual, self::message($string));
    }

    /**
     * @param mixed $actual
     */
    #[DataProvider('provKsortedArrayEqualButNotIdenticalTo')]
    #[DataProvider('provKsortedArrayNotEqualTo')]
    #[DataProvider('provKsortedArrayNotEqualToNonArray')]
    public function testNotKsortedArrayIdenticalToSucceeds(array $expect, $actual, string $string): void
    {
        parent::examineNotConstraintMatchSucceeds([$expect], $actual);
    }

    /**
     * @param mixed $actual
     */
    #[DataProvider('provKsortedArrayIdenticalTo')]
    public function testNotKsortedArrayIdenticalToFails(array $expect, $actual, string $string): void
    {
        parent::examineNotConstraintMatchFails([$expect], $actual, self::message($string, true));
    }
}

// vim: syntax=php sw=4 ts=4 et:
