<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit;

use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\Constraint\LogicalNot;
use Tailors\PHPUnit\Constraint\HasMethod;

trait HasMethodTrait
{
    /**
     * Evaluates a \PHPUnit\Framework\Constraint\Constraint matcher object.
     *
     * @param mixed      $value
     * @param Constraint $constraint
     * @param string     $message
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \Tailors\PHPUnit\InvalidArgumentException
     */
    abstract public static function assertThat($value, Constraint $constraint, string $message = ''): void;

    /**
     * Asserts that *$subject* has method named *$method*.
     *
     * @param string $method
     *                        Name of the method to be expected
     * @param mixed  $subject
     *                        An object, or a name of class, trait or interface to be examined
     * @param string $message
     *                        Optional failure message
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \Tailors\PHPUnit\InvalidArgumentException
     *
     * @psalm-param non-empty-string $method
     */
    public static function assertHasMethod(
        string $method,
        $subject,
        string $message = ''
    ): void {
        self::assertThat($subject, self::hasMethod($method), $message);
    }

    /**
     * Asserts that *$subject* has no method named *$method*.
     *
     * @param string $method
     *                        Name of the method to be expected
     * @param mixed  $subject
     *                        An object, or a name of class, trait or interface to be examined
     * @param string $message
     *                        Optional failure message
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \Tailors\PHPUnit\InvalidArgumentException
     *
     * @psalm-param non-empty-string $method
     */
    public static function assertNotHasMethod(
        string $method,
        $subject,
        string $message = ''
    ): void {
        self::assertThat($subject, new LogicalNot(self::hasMethod($method)), $message);
    }

    /**
     * Checks if an object, class, trait or interface has given method.
     *
     * @param string $method
     *                       Name of the method to be expected
     *
     * @throws \Tailors\PHPUnit\InvalidArgumentException
     *
     * @psalm-param non-empty-string $method
     */
    public static function hasMethod(string $method): HasMethod
    {
        return HasMethod::create($method);
    }
}

// vim: syntax=php sw=4 ts=4 et:
