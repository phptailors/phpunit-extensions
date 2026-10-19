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
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use Tailors\PHPUnit\Constraint\KsortedArrayIdenticalTo;

trait KsortedArrayIdenticalToTrait
{
    /**
     * Evaluates a \PHPUnit\Framework\Constraint\Constraint matcher object.
     *
     * @param mixed $value
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    abstract public static function assertThat($value, Constraint $constraint, string $message = ''): void;

    /**
     * Asserts that *$actual* is an array identical to *$expected* when key-sorted.
     *
     * @param array  $expected expected array
     * @param mixed  $actual   actual value
     * @param string $message  optional failure message
     * @param int    $flags    optional flags passed to ksort()
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws \Tailors\PHPUnit\InvalidArgumentException
     */
    public static function assertKsortedArrayIdenticalTo(
        array $expected,
        $actual,
        string $message = '',
        int $flags = SORT_REGULAR
    ): void {
        self::assertThat($actual, self::ksortedArrayIdenticalTo($expected, $flags), $message);
    }

    /**
     * Asserts that *$actual* fails to be an array identical to *$expected* when key-sorted.
     *
     * @param array  $expected expected array
     * @param mixed  $actual   actual value
     * @param string $message  optional failure message
     * @param int    $flags    optional flags passed to ksort()
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws \Tailors\PHPUnit\InvalidArgumentException
     */
    public static function assertNotKsortedArrayIdenticalTo(
        array $expected,
        $actual,
        string $message = '',
        int $flags = SORT_REGULAR
    ): void {
        self::assertThat($actual, new LogicalNot(self::ksortedArrayIdenticalTo($expected, $flags)), $message);
    }

    /**
     * Compares selected *$actual* with *$expected* array after key-sorting both sides.
     *
     * @param array $expected expected array
     * @param int   $flags    optional flags passed to ksort()
     *
     * @throws \Tailors\PHPUnit\InvalidArgumentException
     */
    public static function ksortedArrayIdenticalTo(array $expected, int $flags = SORT_REGULAR): KsortedArrayIdenticalTo
    {
        return KsortedArrayIdenticalTo::create($expected, $flags);
    }
}

// vim: syntax=php sw=4 ts=4 et:
