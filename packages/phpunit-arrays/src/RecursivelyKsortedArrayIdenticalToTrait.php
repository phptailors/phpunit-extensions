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
use Tailors\PHPUnit\Constraint\RecursivelyKsortedArrayIdenticalTo;

trait RecursivelyKsortedArrayIdenticalToTrait
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
     * Asserts that *$actual* is an array identical to *$expected* when key-sorted recursively.
     *
     * @param array  $expected expected array
     * @param mixed  $actual   actual value
     * @param string $message  optional failure message
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws \Tailors\PHPUnit\InvalidArgumentException
     */
    public static function assertRecursivelyKsortedArrayIdenticalTo(
        array $expected,
        $actual,
        string $message = '',
        int $flags = SORT_REGULAR
    ): void {
        self::assertThat($actual, self::recursivelyKsortedArrayIdenticalTo($expected, $flags), $message);
    }

    /**
     * Asserts that *$actual* fails to be an array identical to *$expected* when key-sorted recursively.
     *
     * @param array  $expected expected array
     * @param mixed  $actual   actual value
     * @param string $message  optional failure message
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws \Tailors\PHPUnit\InvalidArgumentException
     */
    public static function assertNotRecursivelyKsortedArrayIdenticalTo(
        array $expected,
        $actual,
        string $message = '',
        int $flags = SORT_REGULAR
    ): void {
        self::assertThat($actual, new LogicalNot(self::recursivelyKsortedArrayIdenticalTo($expected, $flags)), $message);
    }

    /**
     * Compares selected *$actual* with *$expected* array after recursively key-sorting both sides.
     *
     * @param array $expected expected array
     *
     * @throws \Tailors\PHPUnit\InvalidArgumentException
     */
    public static function recursivelyKsortedArrayIdenticalTo(array $expected, int $flags = SORT_REGULAR): RecursivelyKsortedArrayIdenticalTo
    {
        return RecursivelyKsortedArrayIdenticalTo::create($expected, $flags);
    }
}

// vim: syntax=php sw=4 ts=4 et:
