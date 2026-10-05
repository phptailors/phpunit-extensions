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
use Tailors\PHPUnit\InvalidArgumentException;
use Tailors\PHPUnit\RecursiveConstraint\RecursiveConstraintTestCase;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\Constraint\ArrayValuesEqualTo
 * @covers \Tailors\PHPUnit\Constraint\ProvArrayValuesTrait
 * @covers \Tailors\PHPUnit\RecursiveConstraint\RecursiveConstraintTestCase
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-import-type ArrayLike from \Tailors\PHPUnit\Common\TypesInterface
 * @psalm-type ConstraintClass      = ArrayValuesEqualTo
 * @psalm-type CreateConstraintArgs = list{ArrayLike}
 *
 * @template-extends RecursiveConstraintTestCase<ArrayValuesEqualTo>
 */
final class ArrayValuesEqualToTest extends RecursiveConstraintTestCase
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

    /**
     * @psalm-return class-string<ArrayValuesEqualTo>
     *
     * @psalm-pure
     */
    public static function getConstraintClass(): string
    {
        return ArrayValuesEqualTo::class;
    }

    /**
     * @throws InvalidArgumentException
     *
     * @psalm-param CreateConstraintArgs $args
     */
    public static function createConstraint(array $args): Constraint
    {
        return ArrayValuesEqualTo::create(...$args);
    }

    /**
     * @dataProvider provArrayValuesIdenticalTo
     * @dataProvider provArrayValuesEqualButNotIdenticalTo
     *
     * @param mixed $actual
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testArrayValuesEqualToSucceeds(array $expect, $actual): void
    {
        parent::examineValuesMatchSucceeds($expect, $actual);
    }

    /**
     * @dataProvider provArrayValuesNotEqualTo
     * @dataProvider provArrayValuesNotEqualToNonArray
     *
     * @param mixed $actual
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testArrayValuesEqualToFails(array $expect, $actual, string $string): void
    {
        parent::examineValuesMatchFails($expect, $actual, $string);
    }

    /**
     * @dataProvider provArrayValuesNotEqualTo
     * @dataProvider provArrayValuesNotEqualToNonArray
     *
     * @param mixed $actual
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testNotArrayValuesEqualToSucceeds(array $expect, $actual): void
    {
        parent::examineNotValuesMatchSucceeds($expect, $actual);
    }

    /**
     * @dataProvider provArrayValuesIdenticalTo
     * @dataProvider provArrayValuesEqualButNotIdenticalTo
     *
     * @param mixed $actual
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testNotArrayValuesEqualToFails(array $expect, $actual, string $string): void
    {
        parent::examineNotValuesMatchFails($expect, $actual, $string);
    }
}

// vim: syntax=php sw=4 ts=4 et:
