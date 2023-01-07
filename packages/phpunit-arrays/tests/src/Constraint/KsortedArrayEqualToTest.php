<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Constraint;

use Tailors\PHPUnit\Arrays\KsortedConstraintTestCase;
use Tailors\PHPUnit\Comparator\EqualityComparator;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\Arrays\AbstractKsortedConstraint
 * @covers \Tailors\PHPUnit\Arrays\KsortedConstraintTestCase
 * @covers \Tailors\PHPUnit\Constraint\KsortedArrayEqualTo
 * @covers \Tailors\PHPUnit\Constraint\ProvKsortedArrayTrait
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
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
     * @dataProvider provKsortedArrayIdenticalTo
     * @dataProvider provKsortedArrayEqualButNotIdenticalTo
     *
     * @param mixed $actual
     */
    public function testKsortedArrayEqualToSucceeds(array $expect, $actual): void
    {
        parent::examineConstraintMatchSucceeds([$expect], $actual);
    }

    /**
     * @dataProvider provKsortedArrayNotEqualTo
     * @dataProvider provKsortedArrayNotEqualToNonArray
     *
     * @param mixed $actual
     */
    public function testKsortedArrayEqualToFails(array $expect, $actual, string $string): void
    {
        parent::examineConstraintMatchFails([$expect], $actual, self::message($string));
    }

    /**
     * @dataProvider provKsortedArrayNotEqualTo
     * @dataProvider provKsortedArrayNotEqualToNonArray
     *
     * @param mixed $actual
     */
    public function testNotKsortedArrayEqualToSucceeds(array $expect, $actual): void
    {
        parent::examineNotConstraintMatchSucceeds([$expect], $actual);
    }

    /**
     * @dataProvider provKsortedArrayIdenticalTo
     * @dataProvider provKsortedArrayEqualButNotIdenticalTo
     *
     * @param mixed $actual
     */
    public function testNotKsortedArrayEqualToFails(array $expect, $actual, string $string): void
    {
        parent::examineNotConstraintMatchFails([$expect], $actual, self::message($string, true));
    }
}

// vim: syntax=php sw=4 ts=4 et:
