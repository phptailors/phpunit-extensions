<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Constraint;

use PHPUnit\Framework\Constraint\Constraint;
use Tailors\PHPUnit\Values\ConstraintTestCase;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\Constraint\ArrayValuesEqualTo
 * @covers \Tailors\PHPUnit\Constraint\ProvArrayValuesTrait
 * @covers \Tailors\PHPUnit\Values\ConstraintTestCase
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
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

    public function createConstraint(...$args): Constraint
    {
        return ArrayValuesEqualTo::create(...$args);
    }

    /**
     * @dataProvider provArrayValuesIdenticalTo
     * @dataProvider provArrayValuesEqualButNotIdenticalTo
     *
     * @param mixed $actual
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
     */
    public function testNotArrayValuesEqualToFails(array $expect, $actual, string $string): void
    {
        parent::examineNotValuesMatchFails($expect, $actual, $string);
    }
}

// vim: syntax=php sw=4 ts=4 et:
