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
use Tailors\PHPUnit\Constraint\UsesTrait;

trait UsesTraitTrait
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
     */
    abstract public static function assertThat($value, Constraint $constraint, string $message = ''): void;

    /**
     * Asserts that *$subject* uses *$trait*.
     *
     * @param string $trait   name of the trait that is supposed to be included by *$subject*
     * @param mixed  $subject an object or a class name that is being examined
     * @param string $message custom message
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \Tailors\PHPUnit\InvalidArgumentException
     */
    public static function assertUsesTrait(string $trait, $subject, string $message = ''): void
    {
        self::assertThat($subject, self::usesTrait($trait), $message);
    }

    /**
     * Asserts that *$subject* does not use *$trait*.
     *
     * @param string $trait   name of the trait that is expected to be used by *$subject*
     * @param mixed  $subject an object or a class name that is being examined
     * @param string $message custom message
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \Tailors\PHPUnit\InvalidArgumentException
     */
    public static function assertNotUsesTrait(string $trait, $subject, string $message = ''): void
    {
        self::assertThat($subject, new LogicalNot(self::usesTrait($trait)), $message);
    }

    /**
     * Checks objects (an classes) that they use given *$trait*.
     *
     * @param string $trait name of the trait that is expected to be included
     *
     * @throws \Tailors\PHPUnit\InvalidArgumentException
     */
    public static function usesTrait(string $trait): UsesTrait
    {
        return UsesTrait::create($trait);
    }
}

// vim: syntax=php sw=4 ts=4 et:
