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
use Tailors\PHPUnit\Constraint\ExtendsClass;

trait ExtendsClassTrait
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
     * Asserts that *$subject* extends the class *$parent*.
     *
     * @param string $parent  name of the class that is supposed to be extended by *$subject*
     * @param mixed  $subject an object or a class name that is being examined
     * @param string $message custom message
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public static function assertExtendsClass(string $parent, $subject, string $message = ''): void
    {
        self::assertThat($subject, self::extendsClass($parent), $message);
    }

    /**
     * Asserts that *$subject* does not extend the class *$parent*.
     *
     * @param string $parent  name of the class that is expected to be extended by *$subject*
     * @param mixed  $subject an object or a class name that is being examined
     * @param string $message custom message
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public static function assertNotExtendsClass(string $parent, $subject, string $message = ''): void
    {
        self::assertThat($subject, new LogicalNot(self::extendsClass($parent)), $message);
    }

    /**
     * Checks objects (an classes) that they extend *$parent* class.
     *
     * @param string $parent name of the class that is expected to be extended
     *
     * @throws InvalidArgumentException
     */
    public static function extendsClass(string $parent): ExtendsClass
    {
        return ExtendsClass::create($parent);
    }
}

// vim: syntax=php sw=4 ts=4 et:
