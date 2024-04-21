<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit;

use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\Constraint\LogicalNot;
use PHPUnit\Framework\ExpectationFailedException;
use Tailors\PHPUnit\Constraint\ArrayValuesIdenticalTo;

trait ArrayValuesIdenticalToTrait
{
    /**
     * Evaluates a \PHPUnit\Framework\Constraint\Constraint matcher object.
     *
     * @param mixed      $value
     * @param Constraint $constraint
     * @param string     $message
     *
     * @throws ExpectationFailedException
     */
    abstract public static function assertThat($value, Constraint $constraint, string $message = ''): void;

    /**
     * Asserts that selected values of *$actual* array are identical to *$expected* ones.
     *
     * @param array  $expected an array of expected values
     * @param mixed  $actual   an array to be examined
     * @param string $message  optional failure message
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public static function assertArrayValuesIdenticalTo(
        array $expected,
        $actual,
        string $message = ''
    ): void {
        self::assertThat($actual, self::arrayValuesIdenticalTo($expected), $message);
    }

    /**
     * Asserts that selected properties of *$actual* are not identical to *$expected* ones.
     *
     * @param array  $expected an array of expected values
     * @param mixed  $actual   an array to be examined
     * @param string $message  optional failure message
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public static function assertNotArrayValuesIdenticalTo(
        array $expected,
        $actual,
        string $message = ''
    ): void {
        self::assertThat($actual, new LogicalNot(self::arrayValuesIdenticalTo($expected)), $message);
    }

    /**
     * Compares selected properties of *$actual* with *$expected* ones.
     *
     * @param array $expected an array of expected values
     *
     * @throws InvalidArgumentException
     */
    public static function arrayValuesIdenticalTo(array $expected): ArrayValuesIdenticalTo
    {
        return ArrayValuesIdenticalTo::create($expected);
    }
}

// vim: syntax=php sw=4 ts=4 et:
