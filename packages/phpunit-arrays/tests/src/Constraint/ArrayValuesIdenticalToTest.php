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
use Tailors\PHPUnit\Values\ConstraintTestCase;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\Constraint\ArrayValuesIdenticalTo
 * @covers \Tailors\PHPUnit\Constraint\ProvArrayValuesTrait
 * @covers \Tailors\PHPUnit\Values\ConstraintTestCase
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
final class ArrayValuesIdenticalToTest extends ConstraintTestCase
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
        return 'identical to';
    }

    public static function getConstraintClass(): string
    {
        return ArrayValuesIdenticalTo::class;
    }

    public static function createConstraint(...$args): Constraint
    {
        return ArrayValuesIdenticalTo::create(...$args);
    }

    /**
     * @dataProvider provArrayValuesIdenticalTo
     *
     * @param mixed $actual
     */
    public function testArrayValuesIdenticalToSucceeds(array $expect, $actual, string $string): void
    {
        parent::examineValuesMatchSucceeds($expect, $actual);
    }

    /**
     * @dataProvider provArrayValuesNotEqualTo
     * @dataProvider provArrayValuesEqualButNotIdenticalTo
     * @dataProvider provArrayValuesNotEqualToNonArray
     *
     * @param mixed $actual
     */
    public function testArrayValuesIdenticalToFails(array $expect, $actual, string $string): void
    {
        parent::examineValuesMatchFails($expect, $actual, $string);
    }

    /**
     * @dataProvider provArrayValuesNotEqualTo
     *
     * @dateProvider provArrayValuesEqualButNotIdenticalTo
     *
     * @dataProvider provArrayValuesNotEqualToNonArray
     *
     * @param mixed $actual
     */
    public function testNotArrayValuesIdenticalToSucceeds(array $expect, $actual, string $string): void
    {
        parent::examineNotValuesMatchSucceeds($expect, $actual);
    }

    /**
     * @dataProvider provArrayValuesIdenticalTo
     *
     * @param mixed $actual
     */
    public function testNotArrayValuesIdenticalToFails(array $expect, $actual, string $string): void
    {
        parent::examineNotValuesMatchFails($expect, $actual, $string);
    }
}

// vim: syntax=php sw=4 ts=4 et:
