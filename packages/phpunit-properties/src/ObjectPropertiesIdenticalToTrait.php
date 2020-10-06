<?php declare(strict_types=1);

/*
 * This file is part of php-tailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit;

use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\Constraint\LogicalNot;
use Tailors\PHPUnit\Constraint\ObjectPropertiesIdenticalTo;

trait ObjectPropertiesIdenticalToTrait
{
    /**
     * Evaluates a \PHPUnit\Framework\Constraint matcher object.
     *
     * @param mixed $value
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    abstract public static function assertThat($value, Constraint $constraint, string $message = ''): void;

    /**
     * Asserts that selected properties of *$object* are identical to *$expected* ones.
     *
     * @param array  $expected
     *                         An array of key => value pairs with property names as keys and
     *                         their expected values as values
     * @param object $object
     *                         An object to be examined
     * @param string $message
     *                         Optional failure message
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \Tailors\PHPUnit\InvalidArgumentException
     */
    public static function assertObjectPropertiesIdenticalTo(
        array $expected,
        object $object,
        string $message = ''
    ): void {
        self::assertThat($object, self::objectPropertiesIdenticalTo($expected), $message);
    }

    /**
     * Asserts that selected properties of *$object* are not identical to *$expected* ones.
     *
     * @param array  $expected
     *                         An array of key => value pairs with property names as keys and
     *                         their expected values as values
     * @param object $object
     *                         An object to be examined
     * @param string $message
     *                         Optional failure message
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \Tailors\PHPUnit\InvalidArgumentException
     */
    public static function assertNotObjectPropertiesIdenticalTo(
        array $expected,
        object $object,
        string $message = ''
    ): void {
        self::assertThat($object, new LogicalNot(self::objectPropertiesIdenticalTo($expected)), $message);
    }

    /**
     * Compares selected properties of *$object* with *$expected* ones.
     *
     * @param array $expected
     *                        An array of key => value pairs with expected values of attributes
     *
     * @throws \Tailors\PHPUnit\InvalidArgumentException
     */
    public static function objectPropertiesIdenticalTo(array $expected): ObjectPropertiesIdenticalTo
    {
        return ObjectPropertiesIdenticalTo::create($expected);
    }
}

// vim: syntax=php sw=4 ts=4 et:
