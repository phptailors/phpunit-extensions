<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Constraint;

use Tailors\PHPUnit\Arrays\KsortedConstraintTestCase;
use Tailors\PHPUnit\Comparator\IdentityComparator;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\Arrays\AbstractKsortedConstraint
 * @covers \Tailors\PHPUnit\Arrays\KsortedConstraintTestCase
 * @covers \Tailors\PHPUnit\Constraint\KsortedArrayIdenticalTo
 * @covers \Tailors\PHPUnit\Constraint\ProvKsortedArrayTrait
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
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
     * @dataProvider provKsortedArrayIdenticalTo
     *
     * @param mixed $actual
     */
    public function testKsortedArrayIdenticalToSucceeds(array $expect, $actual): void
    {
        parent::examineConstraintMatchSucceeds([$expect], $actual);
    }

    /**
     * @dataProvider provKsortedArrayEqualButNotIdenticalTo
     * @dataProvider provKsortedArrayNotEqualTo
     * @dataProvider provKsortedArrayNotEqualToNonArray
     *
     * @param mixed $actual
     */
    public function testKsortedArrayIdenticalToFails(array $expect, $actual, string $string): void
    {
        parent::examineConstraintMatchFails([$expect], $actual, self::message($string));
    }

    /**
     * @dataProvider provKsortedArrayEqualButNotIdenticalTo
     * @dataProvider provKsortedArrayNotEqualTo
     * @dataProvider provKsortedArrayNotEqualToNonArray
     *
     * @param mixed $actual
     */
    public function testNotKsortedArrayIdenticalToSucceeds(array $expect, $actual): void
    {
        parent::examineNotConstraintMatchSucceeds([$expect], $actual);
    }

    /**
     * @dataProvider provKsortedArrayIdenticalTo
     *
     * @param mixed $actual
     */
    public function testNotKsortedArrayIdenticalToFails(array $expect, $actual, string $string): void
    {
        parent::examineNotConstraintMatchFails([$expect], $actual, self::message($string, true));
    }
}

// vim: syntax=php sw=4 ts=4 et:
