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
use Tailors\PHPUnit\InvalidArgumentException;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\Constraint\KsortedArrayIdenticalTo
 * @covers \Tailors\PHPUnit\Constraint\KsortedArrayIdenticalToTest
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-type ConstraintClass      = KsortedArrayIdenticalTo
 * @psalm-type CreateConstraintArgs = list{0: array, 1?: int}
 *
 * @template-extends KsortedConstraintTestCase<KsortedArrayIdenticalTo>
 */
final class KsortedArrayIdenticalToTest extends KsortedConstraintTestCase
{
    use ProvKsortedArrayTrait;

    public static function adjective(): string
    {
        return 'identical to';
    }

    /**
     * @psalm-return class-string<KsortedArrayIdenticalTo>
     *
     * @psalm-pure
     */
    public static function getConstraintClass(): string
    {
        return KsortedArrayIdenticalTo::class;
    }

    /**
     * @throws InvalidArgumentException
     *
     * @psalm-param CreateConstraintArgs $args
     */
    public static function createConstraint(array $args): Constraint
    {
        return KsortedArrayIdenticalTo::create(...$args);
    }

    /**
     * @dataProvider provKsortedArrayIdenticalTo
     *
     * @param mixed $actual
     *
     * @psalm-suppress MissingThrowsDocblock
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
     *
     * @psalm-suppress MissingThrowsDocblock
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
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testNotKsortedArrayIdenticalToSucceeds(array $expect, $actual): void
    {
        parent::examineNotConstraintMatchSucceeds([$expect], $actual);
    }

    /**
     * @dataProvider provKsortedArrayIdenticalTo
     *
     * @param mixed $actual
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testNotKsortedArrayIdenticalToFails(array $expect, $actual, string $string): void
    {
        parent::examineNotConstraintMatchFails([$expect], $actual, self::message($string, true));
    }
}

// vim: syntax=php sw=4 ts=4 et:
