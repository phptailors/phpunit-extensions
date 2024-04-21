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
use Tailors\PHPUnit\Constraint\ObjectPropertiesEqualTo;

trait ObjectPropertiesEqualToTrait
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
     * @throws ExpectationFailedException
     * @throws \Tailors\PHPUnit\InvalidArgumentException
     */
    public static function assertObjectPropertiesEqualTo(
        array $expected,
        object $object,
        string $message = ''
    ): void {
        self::assertThat($object, self::objectPropertiesEqualTo($expected), $message);
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
     * @throws ExpectationFailedException
     * @throws \Tailors\PHPUnit\InvalidArgumentException
     */
    public static function assertNotObjectPropertiesEqualTo(
        array $expected,
        object $object,
        string $message = ''
    ): void {
        self::assertThat($object, new LogicalNot(self::objectPropertiesEqualTo($expected)), $message);
    }

    /**
     * Compares selected properties of *$object* with *$expected* ones.
     *
     * @param array $expected
     *                        An array of key => value pairs with expected values of attributes
     *
     * @throws \Tailors\PHPUnit\InvalidArgumentException
     */
    public static function objectPropertiesEqualTo(array $expected): ObjectPropertiesEqualTo
    {
        return ObjectPropertiesEqualTo::create($expected);
    }
}

// vim: syntax=php sw=4 ts=4 et:
