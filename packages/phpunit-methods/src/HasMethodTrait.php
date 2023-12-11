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
     * @throws \Tailors\PHPUnit\InvalidArgumentException
     */
    abstract public static function assertThat($value, Constraint $constraint, string $message = ''): void;

    /**
     * Asserts that *$subject* has method specified with *$methodSpec*.
     *
     * @param string $methodSpec
     *                           Method specification (name and optionally specified modifiers)
     * @param mixed  $subject
     *                           An object, or a name of class, trait or interface to be examined
     * @param string $message
     *                           Optional failure message
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \Tailors\PHPUnit\InvalidArgumentException
     *
     * // due to psalm bug #9151 we can't use this:
     * // @psalm-assert object|class-string|trait-string|interface-string $subject
     */
    public static function assertHasMethod(
        string $methodSpec,
        $subject,
        string $message = ''
    ): void {
        self::assertThat($subject, self::hasMethod($methodSpec), $message);
    }

    /**
     * Asserts that *$subject* has no method specified with *$methodSpec*.
     *
     * @param string $methodSpec
     *                           Method specification (name and optionally specified modifiers)
     * @param mixed  $subject
     *                           An object, or a name of class, trait or interface to be examined
     * @param string $message
     *                           Optional failure message
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \Tailors\PHPUnit\InvalidArgumentException
     */
    public static function assertNotHasMethod(
        string $methodSpec,
        $subject,
        string $message = ''
    ): void {
        self::assertThat($subject, new LogicalNot(self::hasMethod($methodSpec)), $message);
    }

    /**
     * Checks if an object, class, trait or interface has given method.
     *
     * @param string $methodSpec
     *                           Method specification (name and optionally specified modifiers)
     *
     * @throws \Tailors\PHPUnit\InvalidArgumentException
     */
    public static function hasMethod(string $methodSpec): HasMethod
    {
        return HasMethod::create($methodSpec);
    }
}

// vim: syntax=php sw=4 ts=4 et:
