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
use Tailors\PHPUnit\Constraint\KsortedArrayEqualTo;

trait KsortedArrayEqualToTrait
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
     * Asserts that *$actual* is an array equal to *$expected* when key-sorted.
     *
     * @param array  $expected expected array
     * @param mixed  $actual   actual value
     * @param string $message  optional failure message
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public static function assertKsortedArrayEqualTo(
        array $expected,
        $actual,
        string $message = ''
    ): void {
        self::assertThat($actual, self::ksortedArrayEqualTo($expected), $message);
    }

    /**
     * Asserts that *$actual* fails to be an array equal to *$expected* when key-sorted.
     *
     * @param array  $expected expected array
     * @param mixed  $actual   actual value
     * @param string $message  optional failure message
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public static function assertNotKsortedArrayEqualTo(
        array $expected,
        $actual,
        string $message = ''
    ): void {
        self::assertThat($actual, new LogicalNot(self::ksortedArrayEqualTo($expected)), $message);
    }

    /**
     * Compares selected *$actual* with *$expected* array after key-sorting both sides.
     *
     * @param array $expected expected array
     *
     * @throws InvalidArgumentException
     */
    public static function ksortedArrayEqualTo(array $expected): KsortedArrayEqualTo
    {
        return KsortedArrayEqualTo::create($expected);
    }
}

// vim: syntax=php sw=4 ts=4 et:
