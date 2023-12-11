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
use Tailors\PHPUnit\Constraint\ClassPropertiesEqualTo;

trait ClassPropertiesEqualToTrait
{
    /**
     * Evaluates a \PHPUnit\Framework\Constraint\Constraint matcher object.
     *
     * @param mixed      $value
     * @param Constraint $constraint
     * @param string     $message
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    abstract public static function assertThat($value, Constraint $constraint, string $message = ''): void;

    /**
     * Asserts that selected properties of *$class* are identical to *$expected* ones.
     *
     * @param array  $expected
     *                         An array of key => value pairs with property names as keys and
     *                         their expected values as values
     * @param string $class
     *                         A name of a class to be examined
     * @param string $message
     *                         Optional failure message
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \Tailors\PHPUnit\InvalidArgumentException
     */
    public static function assertClassPropertiesEqualTo(
        array $expected,
        string $class,
        string $message = ''
    ): void {
        self::assertThat($class, self::classPropertiesEqualTo($expected), $message);
    }

    /**
     * Asserts that selected properties of *$class* are not identical to *$expected* ones.
     *
     * @param array  $expected
     *                         An array of key => value pairs with property names as keys and
     *                         their expected values as values
     * @param string $class
     *                         A name of a class to be examined
     * @param string $message
     *                         Optional failure message
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \Tailors\PHPUnit\InvalidArgumentException
     */
    public static function assertNotClassPropertiesEqualTo(
        array $expected,
        string $class,
        string $message = ''
    ): void {
        self::assertThat($class, new LogicalNot(self::classPropertiesEqualTo($expected)), $message);
    }

    /**
     * Compares selected properties of *$class* with *$expected* ones.
     *
     * @param array $expected
     *                        An array of key => value pairs with expected values of attributes
     *
     * @throws \Tailors\PHPUnit\InvalidArgumentException when non-string keys are found in *$expected*
     */
    public static function classPropertiesEqualTo(array $expected): ClassPropertiesEqualTo
    {
        return ClassPropertiesEqualTo::create($expected);
    }
}

// vim: syntax=php sw=4 ts=4 et:
