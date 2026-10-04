<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Constraint;

use PHPUnit\Framework\Constraint\Constraint;
use Tailors\PHPUnit\Arrays\KsortedConstraintTestCase;

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
 *
 * @psalm-type ArrayLike            = iterable<array-key, mixed>
 * @psalm-type ConstraintClass      = KsortedArrayEqualTo
 * @psalm-type CreateConstraintArgs = list{ArrayLike}
 *
 * @template-extends KsortedConstraintTestCase<KsortedArrayEqualTo, CreateConstraintArgs>
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

    /**
     * @psalm-param CreateConstraintArgs $args
     */
    public static function createConstraint(array $args): Constraint
    {
        return KsortedArrayEqualTo::create(...$args);
    }

    /**
     * @dataProvider provKsortedArrayIdenticalTo
     * @dataProvider provKsortedArrayEqualButNotIdenticalTo
     *
     * @param mixed $actual
     */
    public function testKsortedArrayEqualToSucceeds(array $expect, $actual, string $string): void
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
    public function testNotKsortedArrayEqualToSucceeds(array $expect, $actual, string $string): void
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
