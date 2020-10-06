<?php declare(strict_types=1);

/*
 * This file is part of php-tailors/phpunit-extensions.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit;

use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\Constraint\LogicalNot;
use Tailors\PHPUnit\Constraint\ImplementsInterface;

trait ImplementsInterfaceTrait
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
     * Asserts that *$subject* implements *$interface*.
     *
     * @param string $interface name of the interface that is expected to be implemented
     * @param mixed  $subject   an object or a class name that is being examined
     * @param string $message   custom message
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \Tailors\PHPUnit\InvalidArgumentException
     */
    public static function assertImplementsInterface(string $interface, $subject, string $message = ''): void
    {
        self::assertThat($subject, self::implementsInterface($interface), $message);
    }

    /**
     * Asserts that *$subject* does not implement *$interface*.
     *
     * @param string $interface name of the interface that is expected to be implemented
     * @param mixed  $subject   an object or a class name that is being examined
     * @param string $message   custom message
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \Tailors\PHPUnit\InvalidArgumentException
     */
    public static function assertNotImplementsInterface(string $interface, $subject, string $message = ''): void
    {
        self::assertThat($subject, new LogicalNot(self::implementsInterface($interface)), $message);
    }

    /**
     * Checks classes that they implement *$interface*.
     *
     * @param string $interface name of the interface that is expected to be implemented
     *
     * @throws \Tailors\PHPUnit\InvalidArgumentException
     */
    public static function implementsInterface(string $interface): ImplementsInterface
    {
        return ImplementsInterface::create($interface);
    }
}

// vim: syntax=php sw=4 ts=4 et:
